<?php
// ─── Identité site ────────────────────────────────────────────────────────────
define('SITE_NAME',    'Annuaire Pisciniste');
define('SITE_URL',     rtrim(getenv('SITE_URL') ?: 'https://annuaire-pisciniste-france.fr', '/'));
define('SITE_YEAR',    date('Y'));
define('METIER',       'pisciniste');
define('METIER_PLURIEL', 'piscinistes');
define('METIER_CAP',   'Pisciniste');
define('NICHE_KEY',    'piscine');
define('NICHE_DIR',    'artisans-piscine');
define('DATA_DIR',     __DIR__ . '/output');

// ─── ViteUnDevis ─────────────────────────────────────────────────────────────
define('VUD_PARTENAIRE_ID', 2372);
define('VUD_CATEGORIE_ID',  44);
define('VUD_BASE_URL',      'https://www.viteundevis.com');
define('VUD_DEVIS_URL',     'https://www.viteundevis.com/devis-0-44-devis_piscine.php');

// ─── Modèles de services piscine ─────────────────────────────────────────────
define('MODELES', [
    // Construction
    ['slug' => 'construction-piscine-beton',     'nom' => 'Construction piscine béton',              'emoji' => '🏗️',  'vud_cat' => 44],
    ['slug' => 'construction-piscine-coque',     'nom' => 'Construction piscine coque polyester',    'emoji' => '🏊',  'vud_cat' => 44],
    ['slug' => 'piscine-hors-sol',               'nom' => 'Piscine hors-sol et semi-enterrée',       'emoji' => '💧', 'vud_cat' => 44],
    // Rénovation
    ['slug' => 'renovation-piscine',             'nom' => 'Rénovation et réfection piscine',         'emoji' => '🔧', 'vud_cat' => 44],
    ['slug' => 'liner-revetement-piscine',       'nom' => 'Liner et revêtement piscine',             'emoji' => '🎨', 'vud_cat' => 44],
    ['slug' => 'carrelage-piscine',              'nom' => 'Carrelage et enduit piscine',             'emoji' => '🪨', 'vud_cat' => 44],
    // Traitement & équipements
    ['slug' => 'traitement-eau-piscine',         'nom' => 'Traitement et qualité de l\'eau',         'emoji' => '🧪', 'vud_cat' => 44],
    ['slug' => 'electrolyseur-sel-piscine',      'nom' => 'Électrolyseur au sel',                    'emoji' => '⚡', 'vud_cat' => 44],
    ['slug' => 'pompe-chaleur-piscine',          'nom' => 'Pompe à chaleur piscine',                 'emoji' => '🌡️', 'vud_cat' => 44],
    // Entretien & sécurité
    ['slug' => 'entretien-hivernage-piscine',    'nom' => 'Entretien et hivernage piscine',          'emoji' => '🧹', 'vud_cat' => 44],
    ['slug' => 'abri-securite-piscine',          'nom' => 'Abri et sécurité piscine',               'emoji' => '🛡️',  'vud_cat' => 44],
    // Diagnostic
    ['slug' => 'diagnostic-piscine',             'nom' => 'Diagnostic et bilan piscine',             'emoji' => '📋', 'vud_cat' => 44],
]);

// ─── Aides nationales ────────────────────────────────────────────────────────
define('AIDES_NATIONALES', [
    'tva_10' => [
        'nom'         => 'TVA à 10 %',
        'code'        => 'TVA 10%',
        'description' => 'Taux de TVA réduit à 10 % applicable aux travaux d\'aménagement d\'une piscine dans les logements de plus de 2 ans (rénovation, liner, équipements).',
        'montant'     => 'Réduction de 10 % sur le montant TTC des travaux éligibles',
        'conditions'  => 'Logement achevé depuis plus de 2 ans, travaux de rénovation ou réfection',
        'travaux'     => ['renovation-piscine', 'liner-revetement-piscine', 'carrelage-piscine', 'traitement-eau-piscine'],
        'url'         => 'https://www.impots.gouv.fr/particulier/questions/jai-fait-des-travaux-dans-mon-logement-quelle-tva-sappliquer',
    ],
    'credit_impot_pac' => [
        'nom'         => 'Crédit d\'impôt PAC piscine',
        'code'        => 'CITE PAC',
        'description' => 'Crédit d\'impôt pour l\'installation d\'une pompe à chaleur dédiée au chauffage de piscine, dans le cadre d\'une rénovation énergétique globale.',
        'montant'     => 'Jusqu\'à 30 % du montant des équipements selon conditions',
        'conditions'  => 'PAC réversible, logement de résidence principale, revenus sous plafond',
        'travaux'     => ['pompe-chaleur-piscine'],
        'url'         => 'https://www.impots.gouv.fr/particulier/les-credits-dimpot',
    ],
    'eco_ptz_travaux' => [
        'nom'         => 'Éco-PTZ travaux',
        'code'        => 'Éco-PTZ',
        'description' => 'Prêt à taux zéro pour financer les travaux d\'accessibilité ou d\'aménagement extérieur incluant une piscine thérapeutique, dans le cadre d\'une rénovation globale.',
        'montant'     => 'Jusqu\'à 50 000 € remboursable sur 20 ans sans intérêts',
        'conditions'  => 'Rénovation globale de résidence principale, combiné à d\'autres travaux énergétiques',
        'travaux'     => ['construction-piscine-beton', 'renovation-piscine'],
        'url'         => 'https://www.ecologie.gouv.fr/leco-pret-taux-zero-leco-ptz',
    ],
    'financement_pisciniste' => [
        'nom'         => 'Financement pisciniste',
        'code'        => 'Financement',
        'description' => 'La plupart des piscinistes proposent des solutions de financement (prêt travaux, crédit affecté) avec des taux négociés, permettant d\'étaler le coût sur plusieurs années.',
        'montant'     => 'De 5 000 € à 100 000 € selon le projet, taux variables',
        'conditions'  => 'Dossier de financement auprès du pisciniste ou de sa banque partenaire',
        'travaux'     => ['construction-piscine-beton', 'construction-piscine-coque', 'piscine-hors-sol'],
        'url'         => 'https://www.federation-des-professionnels-de-la-piscine.com',
    ],
]);

// ─── Types de piscines (remplace les zones climatiques) ──────────────────────
define('ZONES_CLIMATIQUES', [
    'beton' => [
        'label'       => 'Piscine en béton',
        'description' => 'La plus durable et personnalisable : forme libre, profondeur sur mesure, revêtement carrelage ou liner. Durée de vie 30 à 50 ans. Budget moyen 30 000 à 60 000 €.',
        'cee_bonus'   => false,
        'couleur'     => 'blue',
    ],
    'coque' => [
        'label'       => 'Piscine coque polyester',
        'description' => 'Installation rapide (1 à 2 semaines), formes prédéfinies, entretien facile. Résistante aux mouvements de terrain. Budget moyen 15 000 à 35 000 €.',
        'cee_bonus'   => false,
        'couleur'     => 'cyan',
    ],
    'hors-sol' => [
        'label'       => 'Piscine hors-sol',
        'description' => 'Solution économique et modulable : acier, bois ou résine. Pas de travaux de terrassement. Idéale pour les petits budgets ou espaces réduits. Dès 2 000 €.',
        'cee_bonus'   => false,
        'couleur'     => 'orange',
    ],
]);

// ─── Aides dispositifs urbains ────────────────────────────────────────────────
define('AIDES_QPV', [
    'nom'         => 'Quartier Prioritaire de la Ville (QPV)',
    'description' => 'Cette commune est classée en Quartier Prioritaire de la Ville. Des aides renforcées peuvent être disponibles pour les travaux d\'aménagement extérieur.',
    'avantages'   => ['Accompagnement ANRU possible', 'Aides locales à l\'aménagement', 'TVA à 10% sur les travaux éligibles'],
]);

define('AIDES_ACV', [
    'nom'         => 'Action Cœur de Ville',
    'description' => 'Cette commune bénéficie du programme Action Cœur de Ville, favorisant la réhabilitation et les aménagements extérieurs en centre-ville.',
    'avantages'   => ['Aides à la réhabilitation', 'Accompagnement personnalisé', 'Subventions locales potentielles'],
]);

define('AIDES_PVD', [
    'nom'         => 'Petites Villes de Demain',
    'description' => 'Cette commune participe au programme Petites Villes de Demain, favorisant les aménagements et la qualité de vie des habitants.',
    'avantages'   => ['Subventions aménagement extérieur', 'Ingénierie de projet financée', 'Partenariats locaux renforcés'],
]);

// ─── FAQ homepage ─────────────────────────────────────────────────────────────
define('FAQ_ACCUEIL', [
    [
        'q' => 'Comment trouver un pisciniste qualifié près de chez moi ?',
        'r' => 'Utilisez notre moteur de recherche en saisissant le nom de votre ville. Vous obtiendrez la liste des piscinistes professionnels dans votre commune, avec leurs avis clients et coordonnées. Demandez ensuite 3 devis pour comparer les prix et les solutions proposées.',
    ],
    [
        'q' => 'Quel est le prix d\'une piscine construite par un pisciniste ?',
        'r' => 'Le prix varie selon le type : une piscine coque coûte entre 15 000 et 35 000 €, une piscine béton entre 30 000 et 60 000 €. Une piscine hors-sol commence à 2 000 €. Le budget total inclut terrassement, équipements (pompe, filtration, chauffage) et finitions.',
    ],
    [
        'q' => 'Quelle est la différence entre une piscine béton et une piscine coque ?',
        'r' => 'La piscine béton est entièrement personnalisable (forme, taille, profondeur) et dure 30 à 50 ans. La piscine coque polyester est posée en 1 à 2 semaines sur des formes prédéfinies, plus rapide et moins chère. Le béton offre plus de liberté, la coque est plus simple à entretenir.',
    ],
    [
        'q' => 'Faut-il un permis de construire pour une piscine ?',
        'r' => 'Oui, au-delà de 10 m², une déclaration préalable de travaux est obligatoire. Au-delà de 100 m² ou pour une piscine couverte de plus de 1,80 m de haut, un permis de construire est requis. Votre pisciniste vous guide dans ces démarches administratives.',
    ],
    [
        'q' => 'Quelles obligations de sécurité pour une piscine ?',
        'r' => 'Depuis 2004, toute piscine enterrée ou semi-enterrée doit être équipée d\'un dispositif de sécurité normalisé : alarme (NF P90-307), barrière (NF P90-306), couverture (NF P90-308) ou abri. L\'absence de dispositif est passible d\'une amende de 45 000 €.',
    ],
    [
        'q' => 'Comment entretenir une piscine toute l\'année ?',
        'r' => 'L\'entretien comprend : le nettoyage hebdomadaire (robot ou aspirateur), le contrôle de l\'eau 2 à 3 fois par semaine (pH, chlore ou sel), le nettoyage du filtre mensuel, et l\'hivernage en fin de saison. Un pisciniste peut réaliser un contrat d\'entretien annuel de 300 à 800 €.',
    ],
    [
        'q' => 'Quelle est la meilleure solution de chauffage pour une piscine ?',
        'r' => 'La pompe à chaleur est la solution la plus économique à l\'usage (COP de 4 à 6, soit 4 à 6 kWh de chaleur pour 1 kWh électrique). Elle chauffe l\'eau à 28-30 °C même si la température extérieure est de 10 °C. Comptez 3 000 à 8 000 € selon la puissance.',
    ],
    [
        'q' => 'Qu\'est-ce que l\'électrolyse au sel et est-ce mieux que le chlore ?',
        'r' => 'L\'électrolyseur au sel génère du chlore naturel à partir du sel dissous dans l\'eau, évitant d\'acheter des produits chimiques. L\'eau est plus douce pour les yeux et la peau. Le coût est plus élevé à l\'installation (1 500 à 3 000 €) mais réduit les frais d\'entretien de 50 à 70%.',
    ],
    [
        'q' => 'Combien de temps dure la construction d\'une piscine ?',
        'r' => 'Une piscine coque est posée en 1 à 2 semaines (terrassement + pose + raccordements). Une piscine béton prend 4 à 12 semaines selon la complexité. Comptez ensuite 28 jours supplémentaires pour la mise en eau et l\'équilibrage de l\'eau avant de pouvoir nager.',
    ],
    [
        'q' => 'Puis-je installer une piscine en copropriété ou lotissement ?',
        'r' => 'En copropriété, l\'accord de l\'assemblée générale est requis. En lotissement, vérifiez le règlement de lotissement et le PLU (Plan Local d\'Urbanisme) de votre commune : certains interdisent les piscines ou imposent des distances aux limites de propriété. Votre pisciniste peut vous conseiller.',
    ],
]);

// ─── Sites du réseau ──────────────────────────────────────────────────────────
define('NETWORK_SITES', [
    ['nom' => 'Annuaire Menuisier',  'url' => 'https://annuaire-menuisier-france.fr',  'emoji' => '🪟'],
    ['nom' => 'Annuaire Couvreur',   'url' => 'https://annuaire-couvreur-france.fr',   'emoji' => '🏠'],
    ['nom' => 'Annuaire Isolation',  'url' => 'https://annuaire-isolation-france.fr',  'emoji' => '🧱'],
    ['nom' => 'Annuaire VMC',        'url' => 'https://annuaire-vmc-france.fr',        'emoji' => '💨'],
]);
