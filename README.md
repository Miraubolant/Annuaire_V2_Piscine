# Annuaire des communes françaises

Données fusionnées pour les 34 976 communes de France métropolitaine et d'outre-mer.

## Structure des fichiers

Un fichier JSON par département dans le dossier `output/`, nommé `{code_dept}.json` (ex: `75.json`, `971.json`).

```
output/
├── 01.json   → Ain (391 communes)
├── 02.json   → Aisne (797 communes)
├── ...
├── 75.json   → Paris (1 commune)
├── ...
├── 971.json  → Guadeloupe
├── 972.json  → Martinique
├── 973.json  → Guyane
├── 974.json  → La Réunion
└── 976.json  → Mayotte
```

**109 fichiers** — 101 départements + 8 collectivités d'outre-mer (975, 977, 978, 984, 986, 987, 988, 989).

---

## Structure JSON

```json
{
  "departement": {
    "code": "54",
    "nom": "Meurthe-et-Moselle",
    "slug": "meurthe-et-moselle",
    "population_totale": 733481
  },
  "region": {
    "id": 7,
    "code": "44",
    "nom": "Grand Est",
    "slug": "grand-est"
  },
  "voisins": [
    { "dep_code": "08", "dep_nom": "Ardennes", "dep_slug": "ardennes" }
  ],
  "communes_count": 591,
  "communes": [ ... ]
}
```

---

## Structure d'une commune

```json
{
  "code_insee": "54001",
  "nom": "Abaucourt",
  "nom_a": null,
  "nom_de": null,
  "slug": "abaucourt",
  "code_postal": "54610",
  "population": 330,
  "superficie_km2": 792.68,
  "latitude": 48.8903,
  "longitude": 6.2655,
  "zonage_abc": "C",
  "reclassement_abc": "Non",
  "codes_postaux": [
    { "code_postal": "54610", "libelle_acheminement": "ABAUCOURT" }
  ],
  "villes_proches": [
    {
      "code_insee": "54313",
      "nom": "Létricourt",
      "slug": "letricourt",
      "code_postal": "54610",
      "population": 224,
      "superficie_km2": 730.7,
      "latitude": 48.8772,
      "longitude": 6.2844,
      "distance_km": 2.01
    }
  ]
}
```

### Description des champs

| Champ | Type | Description |
|---|---|---|
| `code_insee` | string | Identifiant officiel INSEE (clé primaire unique) |
| `nom` | string | Nom standard de la commune |
| `nom_a` | string\|null | Nom avec article (ex: "de Paris") |
| `nom_de` | string\|null | Nom avec préposition "de" |
| `slug` | string | Version URL du nom (sans accents) |
| `code_postal` | string | Code postal principal |
| `population` | integer | Population de la commune |
| `superficie_km2` | float | Superficie en km² |
| `latitude` | float | Latitude du centre (WGS84) |
| `longitude` | float | Longitude du centre (WGS84) |
| `zonage_abc` | string\|null | Zonage immobilier ABC (A, A bis, B1, B2, C) |
| `reclassement_abc` | string\|null | Reclassement au 5 sept. 2025 (Oui/Non) |
| `codes_postaux` | array | Tous les codes postaux de la commune |
| `villes_proches` | array | Jusqu'à 20 communes proches avec distance en km |
| `logement` | object\|null | Données logement INSEE RP 2022 (voir détail ci-dessous) |
| `aides_etat` | object | Zonages et dispositifs d'aides de l'État (voir détail ci-dessous) |

### Détail de l'objet `logement` (INSEE RP 2022)

```json
"logement": {
  "logements_total": 1542,
  "residences_principales": 1411,
  "logements_vacants": 89,
  "residences_secondaires": 42,
  "maisons": 1256,
  "appartements": 284,
  "proprietaires": 1092,
  "locataires": 298,
  "hlm": 143,
  "construits_avant_1919": 110,
  "construits_1919_1945": 56,
  "construits_1946_1970": 117,
  "construits_1971_1990": 361,
  "construits_1991_2005": 342,
  "construits_2006_2019": 264,
  "chauffage_electrique": 723,
  "chauffage_fioul": 58,
  "chauffage_gaz_ville": 421,
  "chauffage_gaz_bouteille": 16,
  "chauffage_autre": 191,
  "menages": 1411,
  "logements_avant_1990": 644
}
```

| Champ | Description |
|---|---|
| `logements_total` | Nombre total de logements |
| `residences_principales` | Résidences principales (ménages) |
| `logements_vacants` | Logements inoccupés |
| `residences_secondaires` | Résidences secondaires + logements occasionnels |
| `maisons` | Maisons individuelles |
| `appartements` | Appartements |
| `proprietaires` | Résidences principales occupées par leur propriétaire |
| `locataires` | Résidences principales occupées par des locataires |
| `hlm` | Dont HLM loués vides |
| `construits_avant_1919` | Logements construits avant 1919 |
| `construits_1919_1945` | Logements construits entre 1919 et 1945 |
| `construits_1946_1970` | Logements construits entre 1946 et 1970 |
| `construits_1971_1990` | Logements construits entre 1971 et 1990 |
| `construits_1991_2005` | Logements construits entre 1991 et 2005 |
| `construits_2006_2019` | Logements construits entre 2006 et 2019 |
| `chauffage_electrique` | Logements avec chauffage principal électrique |
| `chauffage_fioul` | Logements avec chauffage principal au fioul |
| `chauffage_gaz_ville` | Logements avec gaz de ville / réseau de chaleur |
| `chauffage_gaz_bouteille` | Logements avec gaz en bouteilles ou citerne |
| `chauffage_autre` | Autres combustibles (bois, charbon…) |
| `menages` | Nombre de ménages |
| `logements_avant_1990` | **Potentiel rénovation** : somme des logements construits avant 1990 |

---

## Détail de l'objet `aides_etat`

```json
"aides_etat": {
  "zone_climatique": "H1",
  "qpv": false,
  "action_coeur_de_ville": false,
  "petites_villes_de_demain": true
}
```

| Champ | Type | Description |
|---|---|---|
| `zone_climatique` | string\|null | Zone climatique RT2012/RE2020 : `H1` (nord/montagne), `H2` (tempéré), `H3` (méditerranéen). `null` pour les DOM/TOM. Conditionne les montants MaPrimeRénov', CEE et Éco-PTZ |
| `qpv` | boolean | La commune contient au moins un Quartier Prioritaire de la Politique de la Ville (QPV 2024). Ouvre droit à un bonus MaPrimeRénov' de 10 % pour les travaux de rénovation énergétique |
| `action_coeur_de_ville` | boolean | Commune labellisée Action Cœur de Ville (244 villes moyennes). Éligible au dispositif Denormandie (réduction d'impôt sur achat + rénovation de logement ancien), à des OPAH spécifiques et au programme ORT |
| `petites_villes_de_demain` | boolean | Commune lauréate du programme Petites Villes de Demain — ANCT (1 645 communes < 20 000 hab.). Accès à une ingénierie et des financements dédiés pour la rénovation du bâti ancien et la revitalisation des centres-bourgs |

### Aides non géographiques (valables sur toute la France)

Ces aides s'appliquent indépendamment de la localisation de la commune :

| Aide | Travaux concernés | Condition principale |
|---|---|---|
| **MaPrimeRénov'** | Isolation, PAC, chaudière bois, VMC, fenêtres… | Logement > 15 ans, revenus du ménage |
| **CEE** (Certificats d'Économies d'Énergie) | Isolation, chauffage, ventilation, fenêtres | Via un artisan RGE certifié |
| **Éco-PTZ** | Bouquet de travaux énergétiques | Sans condition de revenus |
| **TVA 5,5 %** | Travaux d'amélioration énergétique | Logement > 2 ans |
| **TVA 10 %** | Autres travaux de rénovation | Logement > 2 ans |
| **MaPrimeAdapt'** | Adaptation au vieillissement / handicap | Personnes > 70 ans ou en situation de handicap |
| **PTZ** | Achat de résidence principale neuve ou ancienne rénovée | Zonage ABC (déjà présent dans `zonage_abc`) |

---

## Sources d'origine

| Source | Données apportées |
|---|---|
| `villes/*.json` | Données principales, coordonnées, villes proches |
| `departements/*.json` | Structure département, région, voisins |
| `Code_Postal.csv` | Libellés d'acheminement postaux |
| `liste-des-communes-zonage-abc-*.csv` | Zonage immobilier ABC |
| INSEE Dossier complet (data.gouv.fr) | Données logement RP 2022 (logements, construction, chauffage…) |
| Base SIRENE (data.gouv.fr, avril 2026) | Comptages artisans BTP actifs par commune et par métier |
| INSEE FiLoSoFi 2020 (data.gouv.fr) | Revenu médian, % ménages imposés, taux de pauvreté |
| Zonage RT2012/RE2020 (Arrêté 26/10/2010) | Zones climatiques H1/H2/H3 par département |
| QPV 2024 (opendata.caissedesdepots.fr) | Quartiers Prioritaires de la Politique de la Ville au 1er janvier 2024 |
| Action Cœur de Ville (opendata.caissedesdepots.fr) | Programme national pour les villes moyennes (244 communes) |
| Petites Villes de Demain — ANCT (opendata.caissedesdepots.fr) | Programme de revitalisation pour communes < 20 000 hab. (1 645 communes) |

