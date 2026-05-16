<?php
// ─── Identité site ────────────────────────────────────────────────────────────
define('SITE_NAME',    'Annuaire VMC');
define('SITE_URL',     rtrim(getenv('SITE_URL') ?: 'https://annuaire-vmc-france.fr', '/'));
define('SITE_YEAR',    date('Y'));
define('METIER',       'installateur VMC');
define('METIER_PLURIEL', 'installateurs VMC');
define('METIER_CAP',   'Installateur VMC');
define('NICHE_KEY',    'vmc');
define('NICHE_DIR',    'artisans-vmc');
define('DATA_DIR',     __DIR__ . '/output');

// ─── ViteUnDevis ─────────────────────────────────────────────────────────────
define('VUD_PARTENAIRE_ID', 2372);
define('VUD_CATEGORIE_ID',  98);
define('VUD_BASE_URL',      'https://www.viteundevis.com');
define('VUD_DEVIS_URL',     'https://www.viteundevis.com/devis-0-98-devis_vmc.php');

// ─── Modèles de services VMC ──────────────────────────────────────────────────
define('MODELES', [
    // Installation
    ['slug' => 'vmc-double-flux',               'nom' => 'VMC double flux',                      'emoji' => '♻️',  'vud_cat' => 98],
    ['slug' => 'vmc-simple-flux-hygro-b',       'nom' => 'VMC simple flux hygroréglable B',      'emoji' => '💧', 'vud_cat' => 98],
    ['slug' => 'vmc-simple-flux-hygro-a',       'nom' => 'VMC simple flux hygroréglable A',      'emoji' => '🌬️', 'vud_cat' => 98],
    ['slug' => 'vmc-autoreglable',              'nom' => 'VMC autoréglable',                     'emoji' => '⚙️',  'vud_cat' => 98],
    ['slug' => 'installation-vmc-neuf',         'nom' => 'Installation VMC neuf',                'emoji' => '🏗️',  'vud_cat' => 98],
    ['slug' => 'installation-vmc-renovation',   'nom' => 'Installation VMC en rénovation',       'emoji' => '🔧', 'vud_cat' => 98],
    // Remplacement & entretien
    ['slug' => 'remplacement-vmc',              'nom' => 'Remplacement / changement VMC',        'emoji' => '🔄', 'vud_cat' => 98],
    ['slug' => 'entretien-vmc',                 'nom' => 'Entretien et nettoyage VMC',           'emoji' => '🧹', 'vud_cat' => 98],
    ['slug' => 'depannage-vmc',                 'nom' => 'Dépannage VMC',                        'emoji' => '🛠️',  'vud_cat' => 98],
    // Systèmes spécialisés
    ['slug' => 'vmc-gainable',                  'nom' => 'VMC gainable centralisée',             'emoji' => '🏠', 'vud_cat' => 98],
    ['slug' => 'vmc-thermodynamique',           'nom' => 'VMC thermodynamique (PAC air/air)',    'emoji' => '🌡️', 'vud_cat' => 98],
    ['slug' => 'ventilation-naturelle-assistee', 'nom' => 'VNA — Ventilation naturelle assistée', 'emoji' => '🌿', 'vud_cat' => 98],
    // Bilan & audit
    ['slug' => 'bilan-ventilation',             'nom' => 'Bilan et audit ventilation',           'emoji' => '📋', 'vud_cat' => 98],
    ['slug' => 'etude-aeraulique',              'nom' => 'Étude aéraulique',                     'emoji' => '📐', 'vud_cat' => 98],
]);

// ─── Aides nationales ────────────────────────────────────────────────────────
define('AIDES_NATIONALES', [
    'cee_bar_th_125' => [
        'nom'         => 'Prime CEE BAR-TH-125',
        'code'        => 'BAR-TH-125',
        'description' => 'Prime CEE pour l\'installation d\'une VMC double flux avec échangeur à haute efficacité. L\'une des aides les plus intéressantes pour la ventilation.',
        'montant'     => 'Jusqu\'à 1 500 € selon la zone climatique et les revenus',
        'conditions'  => 'Logement de plus de 2 ans, artisan RGE, efficacité thermique de l\'échangeur ≥ 85%',
        'travaux'     => ['vmc-double-flux', 'vmc-thermodynamique'],
        'url'         => 'https://www.ecologie.gouv.fr/dispositif-des-certificats-deconomies-denergie',
    ],
    'cee_bar_th_187' => [
        'nom'         => 'Prime CEE BAR-TH-187',
        'code'        => 'BAR-TH-187',
        'description' => 'Prime CEE pour le remplacement d\'une VMC simple flux autoréglable par une VMC hygroréglable de type A ou B.',
        'montant'     => 'Jusqu\'à 800 € selon la zone et les revenus',
        'conditions'  => 'Remplacement d\'une VMC existante, logement de plus de 2 ans, artisan RGE',
        'travaux'     => ['vmc-simple-flux-hygro-b', 'vmc-simple-flux-hygro-a', 'remplacement-vmc'],
        'url'         => 'https://www.ecologie.gouv.fr/dispositif-des-certificats-deconomies-denergie',
    ],
    'maprimerenov' => [
        'nom'         => 'MaPrimeRénov\'',
        'description' => 'Aide de l\'État pour l\'installation ou le remplacement d\'une VMC double flux. Cumulable avec les primes CEE.',
        'montant'     => 'Jusqu\'à 2 500 € pour une VMC double flux selon les revenus du foyer',
        'conditions'  => 'Propriétaire occupant ou bailleur, logement construit depuis plus de 15 ans, artisan RGE obligatoire',
        'travaux'     => ['vmc-double-flux', 'vmc-thermodynamique', 'installation-vmc-renovation'],
        'url'         => 'https://www.maprimerenov.gouv.fr',
    ],
    'tva_55' => [
        'nom'         => 'TVA à 5,5%',
        'description' => 'Taux de TVA réduit à 5,5% applicable aux travaux d\'installation ou de remplacement de VMC dans les logements de plus de 2 ans.',
        'montant'     => 'Réduction de 14,5% sur le montant TTC des travaux',
        'conditions'  => 'Logement achevé depuis plus de 2 ans',
        'travaux'     => ['vmc-double-flux', 'vmc-simple-flux-hygro-b', 'remplacement-vmc', 'installation-vmc-renovation'],
        'url'         => 'https://www.impots.gouv.fr/particulier/questions/jai-fait-des-travaux-dans-mon-logement-quelle-tva-sappliquer',
    ],
    'eco_ptz' => [
        'nom'         => 'Éco-PTZ (Prêt à Taux Zéro)',
        'description' => 'Prêt sans intérêts pour financer des travaux de ventilation dans le cadre d\'une rénovation énergétique globale. Cumulable avec MaPrimeRénov\'.',
        'montant'     => 'Jusqu\'à 50 000 € remboursable sur 20 ans sans intérêts',
        'conditions'  => 'Logement construit avant le 1er janvier 1990, résidence principale, travaux combinés à d\'autres gestes de rénovation',
        'travaux'     => ['vmc-double-flux', 'installation-vmc-renovation'],
        'url'         => 'https://www.ecologie.gouv.fr/leco-pret-taux-zero-leco-ptz',
    ],
]);

// ─── Types de systèmes VMC (remplace les zones climatiques) ──────────────────
define('ZONES_CLIMATIQUES', [
    'double-flux' => [
        'label'       => 'VMC Double Flux',
        'description' => 'Système le plus performant : récupère la chaleur de l\'air extrait pour préchauffer l\'air entrant. Éligible BAR-TH-125. Économies jusqu\'à 30% sur le chauffage.',
        'cee_bonus'   => true,
        'couleur'     => 'blue',
    ],
    'hygro-b' => [
        'label'       => 'VMC Hygro B',
        'description' => 'Adapte le débit d\'air selon le taux d\'humidité de chaque pièce. Éligible BAR-TH-187. Meilleur confort et qualité d\'air pour les logements existants.',
        'cee_bonus'   => true,
        'couleur'     => 'green',
    ],
    'thermodynamique' => [
        'label'       => 'VMC Thermodynamique',
        'description' => 'Combine ventilation et pompe à chaleur air/air. Chauffe et rafraîchit le logement tout en assurant une ventilation optimale. Idéal pour les maisons BBC.',
        'cee_bonus'   => false,
        'couleur'     => 'orange',
    ],
]);

// ─── Aides dispositifs urbains ────────────────────────────────────────────────
define('AIDES_QPV', [
    'nom'         => 'Quartier Prioritaire de la Ville (QPV)',
    'description' => 'Cette commune est classée en Quartier Prioritaire de la Ville. Des aides renforcées sont disponibles pour les travaux de ventilation.',
    'avantages'   => ['Majoration MaPrimeRénov\' jusqu\'à 100%', 'TVA à 5,5% élargie', 'Accompagnement ANRU possible'],
]);

define('AIDES_ACV', [
    'nom'         => 'Action Cœur de Ville',
    'description' => 'Cette commune bénéficie du programme Action Cœur de Ville, qui soutient la rénovation énergétique des logements en centre-ville.',
    'avantages'   => ['Aides à la réhabilitation thermique', 'Accompagnement personnalisé', 'Subventions locales potentielles'],
]);

define('AIDES_PVD', [
    'nom'         => 'Petites Villes de Demain',
    'description' => 'Cette commune participe au programme Petites Villes de Demain, favorisant la rénovation énergétique du bâti et l\'amélioration de la ventilation des logements anciens.',
    'avantages'   => ['Subventions rénovation habitat', 'Ingénierie de projet financée', 'Partenariats locaux renforcés'],
]);

// ─── FAQ homepage ─────────────────────────────────────────────────────────────
define('FAQ_ACCUEIL', [
    [
        'q' => 'Comment trouver un installateur VMC certifié près de chez moi ?',
        'r' => 'Utilisez notre moteur de recherche en saisissant le nom de votre ville. Vous obtiendrez la liste des entreprises de ventilation et installateurs VMC dans votre commune, avec leurs avis clients et coordonnées. Demandez ensuite plusieurs devis pour comparer les prix et les systèmes proposés.',
    ],
    [
        'q' => 'Quel est le tarif moyen d\'une VMC double flux ?',
        'r' => 'L\'installation d\'une VMC double flux coûte entre 3 000 € et 8 000 € selon la taille du logement et le modèle choisi. Après les aides (prime CEE BAR-TH-125 + MaPrimeRénov\'), le reste à charge peut descendre à 1 500-3 000 €. La VMC simple flux hygroréglable est moins coûteuse : 600 à 1 500 € posée.',
    ],
    [
        'q' => 'Qu\'est-ce qu\'une VMC hygroréglable et pourquoi la choisir ?',
        'r' => 'Une VMC hygroréglable adapte automatiquement son débit d\'air en fonction du taux d\'humidité dans chaque pièce. Le type B (Hygro B) module les entrées et sorties d\'air, tandis que le type A ne régule que les sorties. Hygro B est le plus performant pour la qualité d\'air et les économies d\'énergie, et est éligible à la prime CEE BAR-TH-187.',
    ],
    [
        'q' => 'Puis-je bénéficier de MaPrimeRénov\' pour une VMC ?',
        'r' => 'Oui, MaPrimeRénov\' finance l\'installation d\'une VMC double flux jusqu\'à 2 500 €, sous conditions de revenus. Les travaux doivent être réalisés par un artisan certifié RGE. La prime est cumulable avec la prime CEE BAR-TH-125 et la TVA à 5,5%.',
    ],
    [
        'q' => 'La VMC double flux réduit-elle vraiment la facture de chauffage ?',
        'r' => 'Oui, une VMC double flux avec échangeur à haute efficacité (≥ 85%) récupère 70 à 90% de la chaleur de l\'air extrait pour préchauffer l\'air entrant. Cela peut réduire les déperditions thermiques liées au renouvellement d\'air de 20 à 30%, soit 200 à 500 € d\'économies annuelles sur le chauffage.',
    ],
    [
        'q' => 'Faut-il un artisan RGE pour bénéficier des primes VMC ?',
        'r' => 'Oui, pour débloquer la prime CEE (BAR-TH-125 ou BAR-TH-187) et MaPrimeRénov\' pour une VMC, les travaux doivent être réalisés par un artisan certifié RGE (Reconnu Garant de l\'Environnement). Vérifiez la certification sur qualit-enr.org ou faire-france.fr.',
    ],
    [
        'q' => 'Quelle est la durée de vie d\'une VMC ?',
        'r' => 'Une VMC bien entretenue dure 15 à 20 ans. L\'entretien annuel (nettoyage des bouches et filtres, vérification du caisson) est indispensable pour maintenir ses performances et la qualité de l\'air intérieur. Un filtre encrassé peut réduire de 30 à 40% le débit d\'air et augmenter la consommation électrique.',
    ],
    [
        'q' => 'Quand faut-il remplacer sa VMC ?',
        'r' => 'Plusieurs signes indiquent qu\'il est temps de changer sa VMC : bruit anormal du caisson, odeurs persistantes malgré le nettoyage, condensation excessive, ou vétusté du système (plus de 15 ans). Remplacer une vieille VMC autoréglable par une Hygro B est éligible à la prime CEE BAR-TH-187.',
    ],
    [
        'q' => 'Combien de temps prend l\'installation d\'une VMC ?',
        'r' => 'L\'installation d\'une VMC simple flux en rénovation prend 1 à 2 jours selon la configuration du logement. Une VMC double flux nécessite 2 à 4 jours car elle requiert deux réseaux de gaines. Dans le neuf, la pose est intégrée au gros œuvre et prend 1 à 2 jours.',
    ],
    [
        'q' => 'La VMC est-elle obligatoire dans une maison ?',
        'r' => 'Oui, depuis 1982, toute construction neuve doit être équipée d\'un système de ventilation mécanique contrôlée. Pour les logements anciens, une VMC n\'est pas toujours obligatoire mais fortement recommandée pour la qualité de l\'air intérieur et la prévention des problèmes d\'humidité et de moisissures.',
    ],
]);

// ─── Sites du réseau ──────────────────────────────────────────────────────────
define('NETWORK_SITES', [
    ['nom' => 'Annuaire Menuisier',  'url' => 'https://annuaire-menuisier-france.fr',  'emoji' => '🪟'],
    ['nom' => 'Annuaire Couvreur',   'url' => 'https://annuaire-couvreur-france.fr',   'emoji' => '🏠'],
    ['nom' => 'Annuaire Isolation',  'url' => 'https://annuaire-isolation-france.fr',  'emoji' => '🧱'],
]);
