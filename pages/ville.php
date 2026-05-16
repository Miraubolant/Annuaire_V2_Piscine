<?php
// Variables injectées par router.php
$commune    ??= [];
$deptCode   ??= '';
$deptSlug   ??= '';
$regionSlug ??= '';
$villeSlug  ??= '';
$villeCp    ??= '';
$geoData    ??= [];
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../functions.php';

$artisans   = getArtisansByVille($deptCode, $villeSlug);
$aides      = getAidesForCommune($commune);
$potentiel  = getPotentielRenovation($commune);
$nbArtisans = count($artisans) ?: getCompteurArtisans($commune);
$deptMap    = getDeptMapping();
$deptNom    = $deptMap[$deptCode]['nom'] ?? '';

// ─── SEO ──────────────────────────────────────────────────────────────────────
$title         = seoTitle('ville', $commune);
$description   = seoDescription('ville', $commune);
$canonical_url = urlVille($regionSlug, $deptSlug, $villeSlug, $villeCp);
$robots        = 'index,follow';

$trail = [
    ['name' => 'Accueil',                              'url' => SITE_URL . '/'],
    ['name' => nomRegion($regionSlug),                 'url' => urlRegion($regionSlug)],
    ['name' => $deptNom . ' (' . $deptCode . ')',      'url' => urlDepartement($regionSlug, $deptSlug)],
    ['name' => $commune['nom'] . ' (' . $villeCp . ')', 'url' => $canonical_url],
];

$jsonLd = [jsonLdBreadcrumbs($trail)];
foreach ($artisans as $a) { $jsonLd[] = jsonLdLocalBusiness($a, $commune); }

// FAQ ville dynamique
$villeNom = $commune['nom'];
$artDept  = articleDepartement($deptCode);
$artVille = articleVille($villeNom);
$zone     = $commune['aides_etat']['zone_climatique'] ?? 'H2';
$pct      = $potentiel['pct_avant_1990'];

$faq = [
    ['q' => "Combien y a-t-il d'installateurs VMC à {$villeNom} ?",
     'r' => "{$villeNom} compte " . getCompteurLabel($nbArtisans) . " référencés dans notre annuaire. Pour obtenir un devis adapté à votre projet de ventilation VMC, comparez plusieurs artisans et vérifiez leur certification RGE."],
    ['q' => "Quelles aides sont disponibles pour des travaux de ventilation VMC {$artVille} ?",
     'r' => "Les habitants de {$villeNom} bénéficient de la TVA à 5,5% pour tous les travaux de ventilation VMC, de la prime CEE (BAR-TH-125 VMC double flux, BAR-TH-187 VMC hygroréglable), de l'Éco-PTZ et de MaPrimeRénov'. La zone climatique {$zone} détermine le montant des primes CEE."],
    ['q' => "La TVA à 5,5% s'applique-t-elle pour des travaux de ventilation VMC {$artVille} ?",
     'r' => "Oui, la TVA à 5,5% s'applique à tous les travaux d'installation VMC dans les logements de plus de 2 ans à {$villeNom}. C'est le taux le plus bas applicable, représentant une économie de 14,5% par rapport au taux normal."],
    ['q' => "Quelle est la meilleure période pour faire des travaux de ventilation VMC {$artVille} ?",
     'r' => "Les travaux d'installation VMC se réalisent toute l'année, en logement occupé ou non. Il est conseillé d'anticiper et de contacter votre installateur VMC {$artVille} dès l'automne pour bénéficier des meilleures disponibilités."],
    ['q' => "Comment choisir un installateur VMC {$artVille} ?",
     'r' => "Pour choisir un installateur VMC à {$villeNom}, vérifiez sa certification RGE (indispensable pour les aides), ses qualifications (QualiPAC, RGE Eco Artisan), lisez les avis clients et comparez au moins 3 devis détaillés."],
    ['q' => "Quel est le coût d'une installation VMC double flux à {$villeNom} ?",
     'r' => "L'installation d'une VMC double flux coûte entre 3 000 € et 8 000 € à {$villeNom} selon la surface et le modèle. Après aides CEE BAR-TH-125 (zone {$zone}) et MaPrimeRénov', le reste à charge peut être significativement réduit."],
    ['q' => "{$pct}% des logements de {$villeNom} ont été construits avant 1990 — est-ce important ?",
     'r' => "Oui, les logements construits avant 1990 ont souvent des systèmes de ventilation obsolètes ou inexistants. Ils sont prioritaires pour les aides à la rénovation : TVA à 5,5% sur l'installation VMC, prime CEE BAR-TH-125, Éco-PTZ et MaPrimeRénov'."],
    ['q' => "Peut-on obtenir plusieurs devis d'installateurs VMC {$artVille} gratuitement ?",
     'r' => "Absolument. Il est recommandé de demander au moins 3 devis comparatifs. Notre annuaire vous permet de contacter directement les installateurs VMC de {$villeNom} et de demander des devis gratuits via notre formulaire."],
    ['q' => "Les installateurs VMC de {$villeNom} sont-ils certifiés RGE ?",
     'r' => "Oui, tous les installateurs VMC référencés dans notre annuaire possèdent ou peuvent justifier d'une certification RGE (Reconnu Garant de l'Environnement). Cette certification est indispensable pour bénéficier des aides CEE, MaPrimeRénov' et l'Éco-PTZ."],
    ['q' => "Comment bénéficier de la prime CEE pour l'installation VMC double flux {$artVille} ?",
     'r' => "Pour obtenir la prime CEE BAR-TH-125 à {$villeNom}, l'installation VMC double flux doit respecter les critères d'efficacité énergétique requis, être réalisée par un installateur VMC certifié RGE, et l'accord doit être signé avant le début des travaux."],
];
$jsonLd[] = jsonLdFAQ($faq);

$zoneLabels = ['H1' => 'Zone H1 — Nord', 'H2' => 'Zone H2 — Centre', 'H3' => 'Zone H3 — Sud'];

$seoFile = DATA_DIR . '/seo/' . strtoupper($deptCode) . '/' . $villeSlug . '.json';
$seoData = file_exists($seoFile) ? json_decode(file_get_contents($seoFile), true) : null;
$seoText = $seoData['text'] ?? null;
if (!empty($seoData['meta'])) $description = $seoData['meta'];

require __DIR__ . '/../templates/header.php';
?>

<!-- Breadcrumb strip -->
<div style="background:var(--stone);border-bottom:1px solid rgba(0,0,0,.06);">
    <div style="max-width:1280px;margin:0 auto;padding:10px 24px;">
        <?php require __DIR__ . '/../components/breadcrumb.php'; ?>
    </div>
</div>

<!-- Hero 2 colonnes -->
<section class="ph-section">
    <div class="hero-grain"></div>
    <div class="ph-grid">

        <!-- Colonne gauche : éditorial -->
        <div>
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:20px;">
                <span style="color:#F59E0B;letter-spacing:2px;font-size:16px;">★★★★★</span>
                <span style="font-weight:600;color:#fff;font-size:14px;">4.9/5</span>
                <span style="color:rgba(255,255,255,.4);font-size:13px;">· 40 000 installateurs VMC en France</span>
            </div>

            <h1 style="font-family:var(--font-display);font-size:clamp(26px,3.5vw,44px);font-weight:700;color:#fff;line-height:1.2;margin-bottom:14px;letter-spacing:-.02em;">
                Installateur VMC <?= htmlspecialchars($artVille) ?><br>
                <em style="color:#F0A07A;font-style:italic;"><?= htmlspecialchars($villeNom) ?></em>
            </h1>

            <p style="font-size:15px;color:rgba(255,255,255,.65);margin-bottom:28px;line-height:1.7;">
                Trouvez un installateur VMC certifié RGE à <strong style="color:#fff;"><?= htmlspecialchars($villeNom) ?></strong> — devis gratuit, TVA 5,5%, prime CEE disponibles.
            </p>

            <div class="ph-badge-grid">
                <div class="ph-badge">
                    <div class="ph-badge-icon">✓</div>
                    <div>
                        <div style="font-weight:700;color:#fff;font-size:13px;">Devis Gratuit</div>
                        <div style="font-size:11px;color:rgba(255,255,255,.5);margin-top:2px;">Sous 48 heures</div>
                    </div>
                </div>
                <div class="ph-badge">
                    <div class="ph-badge-icon">🏅</div>
                    <div>
                        <div style="font-weight:700;color:#fff;font-size:13px;">Certification RGE</div>
                        <div style="font-size:11px;color:rgba(255,255,255,.5);margin-top:2px;">Certifiés & Assurés</div>
                    </div>
                </div>
            </div>

            <div>
                <p style="font-size:11px;color:rgba(255,255,255,.4);text-transform:uppercase;letter-spacing:.08em;font-weight:700;margin-bottom:10px;">Nos services ventilation VMC <?= htmlspecialchars($artVille) ?></p>
                <div class="ph-tags">
                    <?php foreach (array_slice(MODELES, 0, 5) as $m): ?>
                    <span class="ph-tag"><?= $m['emoji'] ?> <?= htmlspecialchars($m['nom']) ?></span>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Colonne droite : widget devis -->
        <div>
            <div class="ph-widget-card">
                <div class="ph-widget-header">
                    <span style="font-size:22px;">🪟</span>
                    <div>
                        <div style="font-weight:700;color:#fff;font-size:15px;">Obtenir un devis gratuit</div>
                        <div style="font-size:11px;color:rgba(255,255,255,.55);">Réponse sous 48h · Sans engagement</div>
                    </div>
                </div>
                <div style="color:#1a1a1a;">
                    <div id="v2e29b6034ad"></div>
                    <script>
                        vud_partenaire_id = '<?= VUD_PARTENAIRE_ID ?>';
                        vud_categorie_id  = '<?= VUD_CATEGORIE_ID ?>';
                        var vud_js = document.createElement('script');
                        vud_js.type = 'text/javascript';
                        vud_js.src = '//www.viteundevis.com/2e29b6034a/' + vud_partenaire_id + '/' + vud_categorie_id + '/';
                        var s = document.getElementsByTagName('script')[0];
                        s.parentNode.insertBefore(vud_js, s);
                    </script>
                </div>
            </div>
        </div>

    </div>
</section>

<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="lg:grid lg:grid-cols-3 lg:gap-8 mt-6">

        <!-- ── Colonne principale (2/3) ─────────────────────────────────────── -->
        <div class="lg:col-span-2 space-y-10">

            <!-- Artisans -->
            <section id="artisans">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
                    <h2 style="font-family:var(--font-display);font-size:20px;font-weight:700;color:var(--text);">
                        Les installateurs VMC recommandés <?= htmlspecialchars($artVille) ?>
                    </h2>
                    <?php if ($nbArtisans > count($artisans)): ?>
                    <a href="<?= htmlspecialchars(urlArtisans($regionSlug, $deptSlug, $villeSlug, $villeCp)) ?>"
                       style="font-size:13px;font-weight:600;color:var(--forest);text-decoration:none;">
                        Voir tous →
                    </a>
                    <?php endif; ?>
                </div>

                <?php if (!empty($artisans)): ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <?php foreach ($artisans as $artisan): ?>
                    <?php require __DIR__ . '/../components/card-artisan.php'; ?>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <div style="background:var(--stone);border-radius:var(--radius-lg);padding:32px;text-align:center;">
                    <p style="font-weight:600;color:var(--text);margin-bottom:6px;">Aucun installateur VMC référencé à <?= htmlspecialchars($villeNom) ?> pour l'instant.</p>
                    <p style="font-size:14px;color:var(--text-muted);">Consultez les villes proches ou demandez un devis — nos partenaires couvrent toute la région.</p>
                </div>
                <?php endif; ?>
            </section>

            <!-- Zone d'intervention — Google Maps -->
            <?php
            $lat = $commune['latitude']  ?? null;
            $lng = $commune['longitude'] ?? null;
            if ($lat && $lng):
                $mapUrl = 'https://maps.google.com/maps?q=' . $lat . ',' . $lng . '&hl=fr&z=13&output=embed';
            ?>
            <section>
                <h2 style="font-family:var(--font-display);font-size:20px;font-weight:700;color:var(--text);margin-bottom:16px;">
                    Zone d'intervention : <?= htmlspecialchars($villeNom) ?>
                </h2>
                <div style="background:#fff;border-radius:var(--radius-lg);border:1px solid var(--stone);overflow:hidden;box-shadow:var(--shadow-sm);">
                    <iframe src="<?= htmlspecialchars($mapUrl) ?>" width="100%" height="300" style="border:none;display:block;" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="installateurs VMC <?= htmlspecialchars($villeNom) ?>"></iframe>
                    <div style="padding:12px 20px;background:var(--cream);display:flex;align-items:center;justify-content:space-between;font-size:12px;color:var(--text-muted);">
                        <span><?= htmlspecialchars($villeNom) ?> · <?= $nbArtisans ?> installateur<?= $nbArtisans > 1 ? 's VMC' : ' VMC' ?> référencé<?= $nbArtisans > 1 ? 's' : '' ?></span>
                        <a href="https://www.google.com/maps/search/installateur+vmc+<?= urlencode($villeNom . ' ' . $villeCp) ?>/" target="_blank" rel="noopener" style="color:var(--forest);font-weight:600;text-decoration:none;">Voir sur Google Maps →</a>
                    </div>
                </div>
            </section>
            <?php endif; ?>

            <!-- Aides locales -->
            <?php require __DIR__ . '/../components/aides-locales.php'; ?>

            <!-- Potentiel rénovation -->
            <?php require __DIR__ . '/../components/potentiel-renovation.php'; ?>

            <!-- 40 modèles de services -->
            <section>
                <h2 style="font-family:var(--font-display);font-size:20px;font-weight:700;color:var(--text);margin-bottom:8px;">
                    Services ventilation VMC <?= htmlspecialchars($artVille) ?>
                </h2>
                <p style="font-size:14px;color:var(--text-muted);margin-bottom:16px;">
                    Découvrez nos prestations VMC — nos artisans locaux vous accompagnent de l'étude au chantier.
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                    <?php foreach (MODELES as $modele): ?>
                    <?php require __DIR__ . '/../components/card-modele.php'; ?>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- Section expert -->
            <section>
                <h2 style="font-family:var(--font-display);font-size:22px;font-weight:700;color:var(--text);margin-bottom:12px;">
                    Votre installateur VMC expert <?= htmlspecialchars($artVille) ?> (<?= htmlspecialchars($villeCp) ?>)
                </h2>
                <?php if ($seoText): ?>
                <div style="font-size:15px;color:var(--text-muted);line-height:1.85;margin-bottom:24px;">
                    <?php foreach (array_filter(explode("\n\n", $seoText)) as $para): ?>
                    <p style="margin-bottom:14px;"><?= nl2br(htmlspecialchars(trim($para))) ?></p>
                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                <p style="font-size:15px;color:var(--text-muted);line-height:1.8;margin-bottom:24px;">
                    Besoin d'un devis à <strong style="color:var(--text);"><?= htmlspecialchars($villeNom) ?></strong> ?
                    Nos artisans certifiés RGE interviennent pour l'étude de votre projet et sa réalisation dans les règles de l'art,
                    avec TVA à 5,5% et prime CEE disponibles.
                </p>
                <?php endif; ?>

                <!-- Pourquoi choisir -->
                <div style="background:#fff;border-radius:var(--radius-lg);border:1px solid var(--stone);padding:24px;margin-bottom:24px;box-shadow:var(--shadow-sm);">
                    <h3 style="font-weight:700;color:var(--text);margin-bottom:16px;">Pourquoi choisir nos installateurs VMC <?= htmlspecialchars($artVille) ?> ?</h3>
                    <ul style="display:flex;flex-direction:column;gap:12px;list-style:none;">
                        <li style="display:flex;align-items:center;gap:10px;font-size:14px;color:var(--text);">
                            <span style="color:var(--forest);font-weight:700;font-size:16px;">✓</span> Certifiés RGE — éligibles aux aides MaPrimeRénov' et CEE
                        </li>
                        <li style="display:flex;align-items:center;gap:10px;font-size:14px;color:var(--text);">
                            <span style="color:var(--forest);font-weight:700;font-size:16px;">✓</span> Artisans locaux vérifiés et certifiés RGE
                        </li>
                        <li style="display:flex;align-items:center;gap:10px;font-size:14px;color:var(--text);">
                            <span style="color:var(--forest);font-weight:700;font-size:16px;">✓</span> TVA à 5,5% sur tous les travaux de ventilation VMC
                        </li>
                        <li style="display:flex;align-items:center;gap:10px;font-size:14px;color:var(--text);">
                            <span style="color:var(--forest);font-weight:700;font-size:16px;">✓</span> Devis gratuit et sans engagement sous 48h
                        </li>
                    </ul>
                </div>

                <!-- Étapes du projet -->
                <h3 style="font-weight:700;color:var(--text);margin-bottom:16px;">Les étapes de votre projet <?= htmlspecialchars($artVille) ?></h3>
                <div class="grid sm:grid-cols-3 gap-4">
                    <div style="background:#fff;border-radius:var(--radius-lg);border:1px solid var(--stone);padding:24px;text-align:center;box-shadow:var(--shadow-sm);">
                        <div style="width:40px;height:40px;border-radius:50%;background:var(--forest);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;margin:0 auto 12px;">01</div>
                        <h4 style="font-weight:700;color:var(--text);margin-bottom:6px;">Demande</h4>
                        <p style="font-size:13px;color:var(--text-muted);">Décrivez votre projet en 2 minutes via le formulaire.</p>
                    </div>
                    <div style="background:#fff;border-radius:var(--radius-lg);border:1px solid var(--stone);padding:24px;text-align:center;box-shadow:var(--shadow-sm);">
                        <div style="width:40px;height:40px;border-radius:50%;background:var(--forest);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;margin:0 auto 12px;">02</div>
                        <h4 style="font-weight:700;color:var(--text);margin-bottom:6px;">Devis</h4>
                        <p style="font-size:13px;color:var(--text-muted);">Recevez 3 propositions d'installateurs VMC <?= htmlspecialchars($artVille) ?>.</p>
                    </div>
                    <div style="background:#fff;border-radius:var(--radius-lg);border:1px solid var(--stone);padding:24px;text-align:center;box-shadow:var(--shadow-sm);">
                        <div style="width:40px;height:40px;border-radius:50%;background:var(--forest);color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;margin:0 auto 12px;">03</div>
                        <h4 style="font-weight:700;color:var(--text);margin-bottom:6px;">Travaux</h4>
                        <p style="font-size:13px;color:var(--text-muted);">Votre artisan certifié RGE intervient et gère vos dossiers d'aides.</p>
                    </div>
                </div>
            </section>

            <!-- FAQ -->
            <?php
            $questions = $faq;
            $title = 'Questions fréquentes — installateur VMC ' . $artVille;
            require __DIR__ . '/../components/faq.php';
            ?>

            <!-- Villes proches -->
            <?php if (!empty($commune['villes_proches'])): ?>
            <section>
                <h2 style="font-family:var(--font-display);font-size:20px;font-weight:700;color:var(--text);margin-bottom:16px;">
                    Installateurs VMC à proximité de <?= htmlspecialchars($villeNom) ?>
                </h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                    <?php
                    $proches = array_slice($commune['villes_proches'], 0, 12);
                    foreach ($proches as $proche):
                        $procheDeptCode = strlen($proche['code_insee']) === 5
                            ? (substr($proche['code_insee'], 0, 2) === '97'
                                ? substr($proche['code_insee'], 0, 3)
                                : substr($proche['code_insee'], 0, 2))
                            : substr($proche['code_insee'], 0, 2);
                        $procheDeptCode = strtoupper($procheDeptCode);
                        $procheMap      = getDeptMapping();
                        $procheDept     = $procheMap[$procheDeptCode] ?? null;
                        if (!$procheDept) continue;
                        $procheUrl = urlVille($procheDept['region_slug'], $procheDept['slug'], $proche['slug'], $proche['code_postal']);
                    ?>
                    <a href="<?= htmlspecialchars($procheUrl) ?>"
                       style="display:block;background:#fff;border:1px solid var(--stone);border-radius:var(--radius);padding:12px;text-decoration:none;transition:border-color .15s,box-shadow .15s;"
                       onmouseover="this.style.borderColor='var(--forest-light)';this.style.boxShadow='var(--shadow)';"
                       onmouseout="this.style.borderColor='var(--stone)';this.style.boxShadow='none';">
                        <div style="width:32px;height:32px;border-radius:50%;background:rgba(61,74,82,.08);color:var(--forest);display:flex;align-items:center;justify-content:center;margin-bottom:8px;font-size:14px;">📍</div>
                        <p style="font-weight:600;color:var(--text);font-size:13px;margin-bottom:2px;">Installateur VMC <?= htmlspecialchars($proche['nom']) ?></p>
                        <p style="color:var(--text-muted);font-size:11px;"><?= htmlspecialchars($proche['code_postal']) ?> · <?= number_format($proche['distance_km'], 1) ?> km</p>
                    </a>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>

        </div>

        <!-- ── Sidebar (1/3) ────────────────────────────────────────────────── -->
        <aside class="lg:col-span-1 space-y-6 mt-10 lg:mt-0">
            <div class="lg:sticky lg:top-24 space-y-6">

                <?php require __DIR__ . '/../components/sidebar-cta.php'; ?>

                <!-- Info ville -->
                <div style="background:#fff;border-radius:var(--radius-lg);border:1px solid var(--stone);padding:24px;box-shadow:var(--shadow-sm);">
                    <h3 style="font-family:var(--font-display);font-weight:700;color:var(--text);font-size:15px;margin-bottom:16px;">
                        <?= htmlspecialchars($villeNom) ?> en bref
                    </h3>
                    <ul style="display:flex;flex-direction:column;gap:10px;list-style:none;">
                        <li style="display:flex;justify-content:space-between;font-size:13px;padding-bottom:10px;border-bottom:1px solid var(--stone);">
                            <span style="color:var(--text-muted);">Code postal</span>
                            <span style="font-weight:600;color:var(--text);"><?= htmlspecialchars($villeCp) ?></span>
                        </li>
                        <li style="display:flex;justify-content:space-between;font-size:13px;padding-bottom:10px;border-bottom:1px solid var(--stone);">
                            <span style="color:var(--text-muted);">Département</span>
                            <span style="font-weight:600;color:var(--text);"><?= htmlspecialchars($deptNom) ?></span>
                        </li>
                        <li style="display:flex;justify-content:space-between;font-size:13px;padding-bottom:10px;border-bottom:1px solid var(--stone);">
                            <span style="color:var(--text-muted);">Région</span>
                            <span style="font-weight:600;color:var(--text);"><?= htmlspecialchars(nomRegion($regionSlug)) ?></span>
                        </li>
                        <li style="display:flex;justify-content:space-between;font-size:13px;padding-bottom:10px;border-bottom:1px solid var(--stone);">
                            <span style="color:var(--text-muted);">Zone climatique</span>
                            <span style="font-weight:600;color:var(--text);">Zone <?= htmlspecialchars($zone) ?></span>
                        </li>
                        <li style="display:flex;justify-content:space-between;font-size:13px;">
                            <span style="color:var(--text-muted);">Installateurs VMC référencés</span>
                            <span style="font-weight:600;color:var(--forest);"><?= $nbArtisans ?></span>
                        </li>
                    </ul>
                </div>

            </div>
        </aside>

    </div>

    <!-- CTA mobile -->
    <div class="lg:hidden mt-8">
        <?php
        $villeName = $villeNom;
        $context   = 'ville';
        require __DIR__ . '/../components/cta-devis.php';
        ?>
    </div>

</main>

<?php require __DIR__ . '/../templates/footer.php'; ?>

