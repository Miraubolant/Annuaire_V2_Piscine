<?php
/**
 * Generation SEO -- Piscine
 * Toutes les communes (avec ET sans artisan reference)
 * Sortie : JSON {text, meta} par commune
 *
 * Usage CLI :
 *   php generate-seo-villes.php              -> tous les departements
 *   php generate-seo-villes.php 75           -> departement 75 uniquement
 *   php generate-seo-villes.php 75 force     -> regenere meme si deja present
 */

declare(strict_types=1);
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';

const DEEPSEEK_API_KEY = 'sk-d155937287894871a81e3e31d1c65fee';
const DEEPSEEK_MODEL   = 'deepseek-chat';
const SEO_OUTPUT_DIR   = __DIR__ . '/output/seo';
const DELAY_MS         = 150;
const MAX_RETRIES      = 3;

$filterDept = isset($argv[1]) ? strtoupper($argv[1]) : null;
$forceRegen = isset($argv[2]) && $argv[2] === 'force';

$deptMap = getDeptMapping();
$stats   = ['done' => 0, 'skipped' => 0, 'errors' => 0, 'tokens_in' => 0, 'tokens_out' => 0];

echo "=== SEO Piscine — toutes communes ===\n";
echo 'Filtre dept : ' . ($filterDept ?? 'tous') . ' | Force : ' . ($forceRegen ? 'oui' : 'non') . "\n\n";

foreach ($deptMap as $deptCode => $deptInfo) {
    $deptCode = (string)$deptCode;
    if ($filterDept && $filterDept !== strtoupper($deptCode)) continue;

    $geoFile = DATA_DIR . '/' . strtoupper($deptCode) . '.json';
    if (!file_exists($geoFile)) continue;

    $geo       = json_decode(file_get_contents($geoFile), true);
    $communes  = $geo['communes'] ?? [];
    $deptNom   = $deptInfo['nom'];
    $regionNom = nomRegion($deptInfo['region_slug']);
    $total     = count($communes);

    $outDir = SEO_OUTPUT_DIR . '/' . strtoupper($deptCode);
    if (!is_dir($outDir)) mkdir($outDir, 0755, true);

    echo "\n┌── DEPT {$deptCode} — {$deptNom} ({$total} communes) ──\n";

    $i = 0;
    foreach ($communes as $commune) {
        $i++;
        $outFile    = $outDir . '/' . $commune['slug'] . '.json';
        $nbArtisans = (int)($commune['artisans'][NICHE_KEY] ?? 0);

        if (!$forceRegen && file_exists($outFile)) {
            $stats['skipped']++;
            echo "│  [{$i}/{$total}] {$commune['nom']} — ignoré (déjà présent)\n";
            continue;
        }

        echo "│  [{$i}/{$total}] {$commune['nom']}... ";

        $prompt = $nbArtisans > 0
            ? buildPromptAvecArtisans($commune, $deptNom, $regionNom, $nbArtisans)
            : buildPromptSansArtisans($commune, $deptNom, $regionNom);

        $json = callWithRetry($prompt, $stats);

        if ($json === null) {
            echo "✗ ERREUR\n";
            $stats['errors']++;
            continue;
        }

        file_put_contents($outFile, $json);
        $stats['done']++;
        echo "✓  (total: {$stats['done']} | coût: \$" . number_format(estimateCost($stats), 3) . ")\n";

        usleep(DELAY_MS * 1000);
    }

    echo "└── {$deptCode} terminé\n";
}

echo "\n=== Résumé ===\n";
echo 'Générés  : ' . $stats['done'] . "\n";
echo 'Ignorés  : ' . $stats['skipped'] . "\n";
echo 'Erreurs  : ' . $stats['errors'] . "\n";
echo 'Coût estimé : $' . number_format(estimateCost($stats), 4) . " USD\n";

// ─── Prompts ──────────────────────────────────────────────────────────────────

function buildPromptAvecArtisans(array $c, string $dept, string $region, int $nb): string
{
    $ctx      = communeContext($c);
    $nom      = $c['nom'];
    $cp       = $c['code_postal'];
    $pop      = $ctx['population'];
    $zone     = $ctx['zone_label'];
    $aidesSpe = $ctx['aides_speciales'];

    return <<<PROMPT
Tu es redacteur SEO specialise en construction et entretien de piscines. Genere le contenu SEO pour la page d'annuaire des piscinistes a {$nom} ({$cp}).

DONNEES LOCALES :
- Commune : {$nom}, {$dept} ({$region})
- Population : {$pop} hab.
- Piscinistes references : {$nb}
- Contexte climatique : {$zone}
{$aidesSpe}

CONSIGNES TEXTE (280-340 mots) :
- Pas de nom de marque ni de pisciniste specifique
- Angle editorial : qualite de vie, loisirs, valorisation immobiliere, securite et normes
- Mots-cles naturels : pisciniste {$nom}, construction piscine {$nom}, piscine beton {$nom}, piscine coque {$nom}
- Financements a mentionner : TVA 10% sur travaux de renovation, financement pisciniste, credit impot PAC piscine
- Services a mentionner : construction piscine beton, piscine coque polyester, liner et revetement, traitement eau, hivernage, pompe a chaleur piscine, abri piscine, electrolyseur sel
- Mentionner les normes de securite obligatoires (alarme, barriere, couverture — NF P90-306/307/308)
- Paragraphes courts (3-4 lignes), pas de titre H1/H2, texte brut

CONSIGNES META (130-155 caracteres) :
- Inclure : pisciniste {$nom}, devis gratuit, un service cle (construction ou renovation)
- Formule comme une invitation a l'action

FORMAT DE REPONSE — JSON valide uniquement, sans markdown ni balise :
{{"text":"Texte editorial ici","meta":"Meta description ici"}}
PROMPT;
}

function buildPromptSansArtisans(array $c, string $dept, string $region): string
{
    $ctx      = communeContext($c);
    $proches  = villesProchesLabel($c);
    $nom      = $c['nom'];
    $cp       = $c['code_postal'];
    $pop      = $ctx['population'];
    $zone     = $ctx['zone_label'];
    $aidesSpe = $ctx['aides_speciales'];

    return <<<PROMPT
Tu es redacteur SEO specialise en construction et entretien de piscines. Genere le contenu SEO pour la page d'annuaire piscinistes pres de {$nom} ({$cp}).

SITUATION : aucun pisciniste n'est repertorie a {$nom}, mais des professionnels de {$proches} couvrent ce secteur.

DONNEES LOCALES :
- Commune : {$nom}, {$dept} ({$region})
- Population : {$pop} hab.
- Contexte climatique : {$zone}
{$aidesSpe}

CONSIGNES TEXTE (240-300 mots) :
- Mentionne que les piscinistes de {$proches} interviennent a {$nom} et alentours
- Valorise les deplacements gratuits pour devis et les solutions de financement accessibles partout
- Mots-cles : pisciniste pres de {$nom}, piscine {$nom}, construction piscine {$nom}
- Financements : TVA 10%, financement pisciniste, credit impot PAC piscine
- Mentionner les normes de securite obligatoires (NF P90-306/307/308)
- Paragraphes courts, pas de titre H1/H2, texte brut

CONSIGNES META (130-155 caracteres) :
- Inclure : pisciniste {$nom}, devis gratuit
- Formule comme une invitation a l'action

FORMAT DE REPONSE — JSON valide uniquement, sans markdown ni balise :
{{"text":"Texte editorial ici","meta":"Meta description ici"}}
PROMPT;
}

// ─── Helpers ──────────────────────────────────────────────────────────────────

function communeContext(array $c): array
{
    $log          = is_array($c['logement'] ?? null) ? $c['logement'] : [];
    $logTotal     = max(1, (int)($log['logements_total'] ?? 1));
    $logAvant1990 = (int)($log['logements_avant_1990'] ?? 0);
    $pct          = (int)round($logAvant1990 / $logTotal * 100);

    $zone = (string)($c['aides_etat']['zone_climatique'] ?? 'H2');
    $zoneLabels = [
        'H1' => 'nord/est de la France, hivers froids — piscine couverte ou chauffee recommandee, saison de baignade avril-septembre',
        'H2' => 'centre de la France, climat tempere — saison de baignade mai-octobre, pompe a chaleur conseillée pour prolonger la saison',
        'H3' => 'sud de la France, climat chaud — forte demande piscines, saison de baignade avril-novembre, traitement eau intensif en ete',
    ];

    $aides = [];
    if (!empty($c['aides_etat']['qpv'])) $aides[] = '- QPV : aides locales possibles pour amenagement exterieur';
    if (!empty($c['aides_etat']['action_coeur_de_ville'])) $aides[] = '- Action Coeur de Ville : subventions rehabilitation et amenagement';
    if (!empty($c['aides_etat']['petites_villes_de_demain'])) $aides[] = '- Petites Villes de Demain : soutien amenagement cadre de vie';

    return [
        'population'      => number_format((int)($c['population'] ?? 0), 0, ',', ' '),
        'pct_avant_1990'  => $pct,
        'zone'            => $zone,
        'zone_label'      => $zoneLabels[$zone] ?? '',
        'aides_speciales' => $aides ? implode("\n", $aides) : '',
    ];
}

function villesProchesLabel(array $c): string
{
    $proches = array_slice($c['villes_proches'] ?? [], 0, 3);
    if (empty($proches)) return 'communes voisines';
    $parts = [];
    foreach ($proches as $v) {
        $parts[] = $v['nom'] . ' (' . (int)round($v['distance_km']) . ' km)';
    }
    return implode(', ', $parts);
}

function callWithRetry(string $prompt, array &$stats): ?string
{
    for ($i = 1; $i <= MAX_RETRIES; $i++) {
        $result = callDeepSeek($prompt, $stats);
        if ($result !== null) return $result;
        if ($i < MAX_RETRIES) sleep($i * 2);
    }
    return null;
}

function callDeepSeek(string $prompt, array &$stats): ?string
{
    $payload = json_encode([
        'model'       => DEEPSEEK_MODEL,
        'messages'    => [
            ['role' => 'system', 'content' => 'Tu es redacteur SEO expert en construction et entretien de piscines. Reponds UNIQUEMENT avec un objet JSON valide contenant les cles "text" et "meta". Aucun markdown, aucun commentaire, aucune balise.'],
            ['role' => 'user',   'content' => $prompt],
        ],
        'max_tokens'  => 700,
        'temperature' => 0.85,
    ]);

    $ch = curl_init('https://api.deepseek.com/v1/chat/completions');
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $payload,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 30,
        CURLOPT_HTTPHEADER     => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . DEEPSEEK_API_KEY,
        ],
    ]);

    $raw      = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    unset($ch);

    if ($raw === false || $httpCode !== 200) return null;

    $response = json_decode($raw, true);
    $content  = trim($response['choices'][0]['message']['content'] ?? '');
    if ($content === '') return null;

    $content = preg_replace('/^```(?:json)?\s*|\s*```$/s', '', $content);

    $data = json_decode(trim($content), true);
    if (!is_array($data) || empty($data['text']) || empty($data['meta'])) return null;

    $data['meta'] = mb_substr(trim($data['meta']), 0, 155);

    $stats['tokens_in']  += $response['usage']['prompt_tokens']     ?? 0;
    $stats['tokens_out'] += $response['usage']['completion_tokens'] ?? 0;

    return json_encode($data, JSON_UNESCAPED_UNICODE);
}

function estimateCost(array $stats): float
{
    return $stats['tokens_in'] * 0.00000014 + $stats['tokens_out'] * 0.00000028;
}
