<?php

namespace Database\Seeders;

use App\Models\SecteurActivite;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SecteursActiviteSeeder extends Seeder
{
    public function run(): void
    {
        $secteurs = [

            // ── Agriculture & Pêche ─────────────────────────────────────
            ['code' => 'AGRICULTURE',        'libelle' => 'Agriculture / Cultures vivrières'],
            ['code' => 'MARAICHAGE',         'libelle' => 'Maraîchage / Horticulture'],
            ['code' => 'ELEVAGE',            'libelle' => 'Élevage / Zootechnie / Aviculture'],
            ['code' => 'PECHE',              'libelle' => 'Pêche / Aquaculture / Pisciculture'],
            ['code' => 'SYLVICULTURE',       'libelle' => 'Sylviculture / Foresterie / Exploitation du bois'],
            ['code' => 'AGROALIMENTAIRE',    'libelle' => 'Agroalimentaire / Industries alimentaires'],
            ['code' => 'TABAC',              'libelle' => 'Tabac / Culture du coton'],

            // ── Industries & Production ─────────────────────────────────
            ['code' => 'INDUSTRIE',          'libelle' => 'Industrie manufacturière / Production'],
            ['code' => 'TEXTILE',            'libelle' => 'Textile / Habillement / Confection'],
            ['code' => 'CUIR',               'libelle' => 'Cuir / Chaussures / Maroquinerie'],
            ['code' => 'IMPRIMERIE',         'libelle' => 'Imprimerie / Édition / Papier / Carton'],
            ['code' => 'CHIMIE',             'libelle' => 'Chimie / Pétrochimie / Plastiques / Caoutchouc'],
            ['code' => 'PHARMACIE',          'libelle' => 'Pharmacie / Biotechnologie / Sciences du vivant'],
            ['code' => 'COSMETIQUE',         'libelle' => 'Cosmétiques / Produits de beauté / Hygiène'],
            ['code' => 'METALLURGIE',        'libelle' => 'Métallurgie / Sidérurgie / Fonderie / Soudure'],
            ['code' => 'MECANIQUE',          'libelle' => 'Mécanique / Machines industrielles / Maintenance'],
            ['code' => 'ELECTRONIQUE',       'libelle' => 'Électronique / Électrotechnique / Génie électrique'],
            ['code' => 'AUTOMOBILE',         'libelle' => 'Automobile / Équipements de transport / Garage'],
            ['code' => 'AERONAUTIQUE',       'libelle' => 'Aéronautique / Spatial / Drones'],
            ['code' => 'NAVAL',              'libelle' => 'Naval / Construction navale / Ports'],
            ['code' => 'MATERIAUX',          'libelle' => 'Matériaux de construction / Ciment / Céramique / Verre'],
            ['code' => 'MINES',              'libelle' => 'Extraction minière / Carrières / Ressources naturelles'],
            ['code' => 'PETROLE',            'libelle' => 'Pétrole / Gaz / Raffinerie / Hydrocarbures'],
            ['code' => 'ENERGIE',            'libelle' => 'Énergie électrique / Production et distribution'],

            // ── BTP & Immobilier ────────────────────────────────────────
            ['code' => 'BTP',                'libelle' => 'Bâtiment et travaux publics (BTP)'],
            ['code' => 'GENIE_CIVIL',        'libelle' => 'Génie civil / Infrastructure / Routes / Ponts'],
            ['code' => 'ARCHITECTURE',       'libelle' => 'Architecture / Urbanisme / Aménagement du territoire'],
            ['code' => 'IMMOBILIER',         'libelle' => 'Immobilier / Promotion immobilière / Foncier'],
            ['code' => 'DECORATION',         'libelle' => 'Décoration intérieure / Design d\'intérieur'],
            ['code' => 'TOPOGRAPHIE',        'libelle' => 'Topographie / Géodésie / Cartographie'],

            // ── Commerce & Distribution ─────────────────────────────────
            ['code' => 'COMMERCE',           'libelle' => 'Commerce de détail / Boutiques'],
            ['code' => 'COMMERCE_GROS',      'libelle' => 'Commerce de gros / Négoce / Distribution'],
            ['code' => 'GRANDE_DISTRIB',     'libelle' => 'Grande distribution / Supermarchés / Hypermarchés'],
            ['code' => 'ECOMMERCE',          'libelle' => 'E-commerce / Vente en ligne / Marketplace'],
            ['code' => 'IMPORT_EXPORT',      'libelle' => 'Import / Export / Commerce international'],
            ['code' => 'REPRESENTATION',     'libelle' => 'Vente / Représentation commerciale / Distribution'],

            // ── Technologies & Numérique ────────────────────────────────
            ['code' => 'INFORMATIQUE',       'libelle' => 'Informatique / Développement logiciel / Applications'],
            ['code' => 'IA',                 'libelle' => 'Intelligence artificielle / Machine learning / Data science'],
            ['code' => 'CYBERSECURITE',      'libelle' => 'Cybersécurité / Sécurité informatique'],
            ['code' => 'CLOUD',              'libelle' => 'Cloud computing / Hébergement / Infrastructure IT'],
            ['code' => 'BIG_DATA',           'libelle' => 'Big data / Analytique / Business intelligence'],
            ['code' => 'JEUX_VIDEO',         'libelle' => 'Jeux vidéo / Divertissement numérique / Animation 3D'],
            ['code' => 'UX_UI',              'libelle' => 'Design UX/UI / Graphisme digital / Webdesign'],
            ['code' => 'STARTUP',            'libelle' => 'Startups / Innovation tech / Entrepreneuriat digital'],
            ['code' => 'ERP_CRM',            'libelle' => 'Systèmes d\'information / ERP / CRM / Intégration'],
            ['code' => 'RESEAUX_IT',         'libelle' => 'Réseaux / Infrastructure informatique / Télématique'],

            // ── Télécommunications ──────────────────────────────────────
            ['code' => 'TELECOM',            'libelle' => 'Télécommunications / Opérateurs mobiles / Téléphonie'],
            ['code' => 'INTERNET',           'libelle' => 'Internet / FAI / Connectivité / Fibre optique'],

            // ── Finance & Assurance ─────────────────────────────────────
            ['code' => 'BANQUE',             'libelle' => 'Banque / Services financiers / Crédit'],
            ['code' => 'MICROFINANCE',       'libelle' => 'Microfinance / IMF / Tontines / Épargne'],
            ['code' => 'ASSURANCE',          'libelle' => 'Assurance / Réassurance / Mutuelles'],
            ['code' => 'BOURSE',             'libelle' => 'Marchés financiers / Bourse / Investissement / Capital-risque'],
            ['code' => 'FINTECH',            'libelle' => 'Fintech / Paiement mobile / Mobile money'],
            ['code' => 'COMPTABILITE',       'libelle' => 'Comptabilité / Audit / Expertise comptable / Fiscalité'],
            ['code' => 'PATRIMOINE',         'libelle' => 'Gestion de patrimoine / Conseils financiers'],
            ['code' => 'FINANCE',            'libelle' => 'Finance / Contrôle de gestion / Trésorerie'],

            // ── Santé & Médical ─────────────────────────────────────────
            ['code' => 'SANTE',              'libelle' => 'Médecine générale / Hôpitaux / Cliniques'],
            ['code' => 'PHARMACIE_OFFICINE', 'libelle' => 'Pharmacie / Officine / Parapharmacie'],
            ['code' => 'MED_TRAD',           'libelle' => 'Médecine traditionnelle / Phytothérapie'],
            ['code' => 'SANTE_PUBLIQUE',     'libelle' => 'Santé publique / Épidémiologie / Prévention'],
            ['code' => 'DENTISTERIE',        'libelle' => 'Dentisterie / Orthodontie / Stomatologie'],
            ['code' => 'OPHTALMOLOGIE',      'libelle' => 'Ophtalmologie / Optique / Audiologie'],
            ['code' => 'KINE',               'libelle' => 'Kinésithérapie / Rééducation / Orthopédie'],
            ['code' => 'LABO_MED',           'libelle' => 'Biologie médicale / Laboratoires d\'analyses'],
            ['code' => 'VETERINAIRE',        'libelle' => 'Médecine vétérinaire / Soins animaux'],
            ['code' => 'INFIRMIER',          'libelle' => 'Infirmier / Aide-soignant / Sage-femme / Paramédical'],
            ['code' => 'MATERIEL_MED',       'libelle' => 'Matériel médical / Équipements de santé / Imagerie'],

            // ── Social & Humanitaire ────────────────────────────────────
            ['code' => 'ACTION_SOCIALE',     'libelle' => 'Action sociale / Travail social / Aide à la personne'],
            ['code' => 'ONG',                'libelle' => 'ONG / Associations / Humanitaire'],
            ['code' => 'COOPERATION',        'libelle' => 'Coopération internationale / Développement / Bailleurs'],
            ['code' => 'PROTECTION',         'libelle' => 'Protection de l\'enfance / Famille / Genre'],
            ['code' => 'HANDICAP',           'libelle' => 'Handicap / Inclusion / Accessibilité'],

            // ── Éducation & Formation ───────────────────────────────────
            ['code' => 'EDUCATION',          'libelle' => 'Enseignement primaire / Éducation de base / Alphabétisation'],
            ['code' => 'SECONDAIRE',         'libelle' => 'Enseignement secondaire / Lycées / Collèges'],
            ['code' => 'SUPERIEUR',          'libelle' => 'Enseignement supérieur / Universités / Grandes écoles'],
            ['code' => 'FORMATION_PRO',      'libelle' => 'Formation professionnelle / Apprentissage / CFA'],
            ['code' => 'ELEARNING',          'libelle' => 'E-learning / MOOC / Formation en ligne / EdTech'],
            ['code' => 'PETITE_ENFANCE',     'libelle' => 'Petite enfance / Crèche / Garderie / Pré-scolaire'],
            ['code' => 'BIBLIOTHEQUE',       'libelle' => 'Bibliothèques / Centres documentaires / Archives'],

            // ── Recherche & Innovation ──────────────────────────────────
            ['code' => 'RECHERCHE',          'libelle' => 'Recherche scientifique / Laboratoires / R&D'],
            ['code' => 'THINK_TANK',         'libelle' => 'Think tanks / Études / Conseil en stratégie'],

            // ── Transport & Logistique ──────────────────────────────────
            ['code' => 'TRANSPORT',          'libelle' => 'Transport routier / Chauffeur / Mobilité urbaine'],
            ['code' => 'FERROVIAIRE',        'libelle' => 'Transport ferroviaire / Rail'],
            ['code' => 'AVIATION',           'libelle' => 'Transport aérien / Aviation / Aéroports'],
            ['code' => 'MARITIME',           'libelle' => 'Transport maritime / Fluvial / Ports / Logistique portuaire'],
            ['code' => 'LOGISTIQUE',         'libelle' => 'Logistique / Supply chain / Entrepôts / Douane'],
            ['code' => 'LIVRAISON',          'libelle' => 'Messagerie / Livraison / Coursier / Last mile'],
            ['code' => 'FLOTTE',             'libelle' => 'Gestion de flotte / Location de véhicules'],

            // ── Énergie & Environnement ─────────────────────────────────
            ['code' => 'SOLAIRE',            'libelle' => 'Énergie solaire / Photovoltaïque'],
            ['code' => 'HYDRAULIQUE',        'libelle' => 'Énergie hydraulique / Barrages / Irrigation'],
            ['code' => 'EOLIEN',             'libelle' => 'Énergie éolienne / Énergies renouvelables'],
            ['code' => 'BIOMASSE',           'libelle' => 'Biomasse / Biogaz / Bioénergie / Biocarburants'],
            ['code' => 'DECHETS',            'libelle' => 'Gestion des déchets / Recyclage / Propreté urbaine'],
            ['code' => 'EAU',                'libelle' => 'Eau et assainissement / WASH / Hydraulique'],
            ['code' => 'ENVIRONNEMENT',      'libelle' => 'Environnement / Écologie / Biodiversité / Climat'],
            ['code' => 'RSE',                'libelle' => 'Développement durable / RSE / Carbone / Impact social'],

            // ── Tourisme & Hôtellerie ───────────────────────────────────
            ['code' => 'HOTELLERIE',         'libelle' => 'Hôtellerie / Hébergement / Auberges'],
            ['code' => 'RESTAURATION',       'libelle' => 'Restauration / Café / Fast-food / Traiteur'],
            ['code' => 'TOURISME',           'libelle' => 'Tourisme / Agences de voyage / Écotourisme'],
            ['code' => 'EVENEMENTIEL',       'libelle' => 'Événementiel / Organisation de conférences / Mariages'],
            ['code' => 'LOISIRS',            'libelle' => 'Loisirs / Parcs d\'attraction / Récréation'],

            // ── Médias & Communication ──────────────────────────────────
            ['code' => 'PRESSE',             'libelle' => 'Presse / Journalisme / Reportage / Web médias'],
            ['code' => 'RADIO',              'libelle' => 'Radio / Podcasting / Streaming audio'],
            ['code' => 'TELEVISION',         'libelle' => 'Télévision / Audiovisuel / Streaming vidéo'],
            ['code' => 'CINEMA',             'libelle' => 'Cinéma / Vidéo / Production audiovisuelle / Publicité'],
            ['code' => 'MUSIQUE',            'libelle' => 'Musique / Spectacle vivant / Scène / Divertissement'],
            ['code' => 'ARTS',               'libelle' => 'Arts plastiques / Beaux-arts / Galeries / Patrimoine'],
            ['code' => 'PHOTOGRAPHIE',       'libelle' => 'Photographie / Retouche / Image'],
            ['code' => 'EDITION',            'libelle' => 'Édition / Librairie / Presse écrite / Documentation'],
            ['code' => 'RP',                 'libelle' => 'Relations publiques / Communication institutionnelle'],
            ['code' => 'PUBLICITE',          'libelle' => 'Publicité / Agences créatives / Branding'],
            ['code' => 'MARKETING',          'libelle' => 'Marketing digital / SEO / Réseaux sociaux / Influence'],

            // ── Mode & Beauté ───────────────────────────────────────────
            ['code' => 'MODE',               'libelle' => 'Mode / Stylisme / Couture / Créateurs africains'],
            ['code' => 'COIFFURE',           'libelle' => 'Coiffure / Barbier / Tresses / Soins capillaires'],
            ['code' => 'ESTHETIQUE',         'libelle' => 'Esthétique / Spa / Bien-être / Massage'],
            ['code' => 'BIJOUTERIE',         'libelle' => 'Bijouterie / Joaillerie / Orfèvrerie'],

            // ── Administration & Secteur public ────────────────────────
            ['code' => 'ADMINISTRATION',     'libelle' => 'Administration publique / Fonction publique'],
            ['code' => 'COLLECTIVITES',      'libelle' => 'Collectivités locales / Mairies / Conseils communaux'],
            ['code' => 'DIPLOMATIE',         'libelle' => 'Diplomatie / Affaires étrangères / Relations internationales'],
            ['code' => 'JURIDIQUE',          'libelle' => 'Justice / Droit / Notariat / Magistrature'],
            ['code' => 'POLICE',             'libelle' => 'Police / Gendarmerie / Sécurité publique'],
            ['code' => 'DEFENSE',            'libelle' => 'Forces armées / Défense nationale / Militaire'],
            ['code' => 'DOUANES',            'libelle' => 'Douanes / Fiscalité / Impôts / Trésor public'],
            ['code' => 'POLITIQUE',          'libelle' => 'Politique / Partis / Élus / Gouvernance'],

            // ── Services aux entreprises ────────────────────────────────
            ['code' => 'CONSEIL',            'libelle' => 'Conseil en management / Stratégie / Transformation'],
            ['code' => 'RH',                 'libelle' => 'Ressources humaines / Recrutement / GPEC'],
            ['code' => 'INTERIM',            'libelle' => 'Intérim / Agences d\'emploi / Outsourcing RH'],
            ['code' => 'FACILITY',           'libelle' => 'Facility management / Maintenance / Multiservices'],
            ['code' => 'NETTOYAGE',          'libelle' => 'Nettoyage / Entretien / Propreté / Hygiène'],
            ['code' => 'SECURITE',           'libelle' => 'Sécurité privée / Gardiennage / Surveillance'],
            ['code' => 'TRADUCTION',         'libelle' => 'Traduction / Interprétariat / Localisation'],
            ['code' => 'CALL_CENTER',        'libelle' => 'Service client / Centre d\'appels / Call center / BPO'],
            ['code' => 'SECRETARIAT',        'libelle' => 'Secrétariat / Assistanat / Office management'],

            // ── Artisanat ───────────────────────────────────────────────
            ['code' => 'ARTISANAT',          'libelle' => 'Artisanat / Métiers manuels / Arts et métiers'],
            ['code' => 'POTERIE',            'libelle' => 'Poterie / Céramique artisanale / Sculpture'],
            ['code' => 'MENUISERIE',         'libelle' => 'Menuiserie / Ébénisterie / Charpente'],
            ['code' => 'PLOMBERIE',          'libelle' => 'Plomberie / Sanitaire / Génie thermique'],
            ['code' => 'ELECTRICITE',        'libelle' => 'Électricité bâtiment / Installations électriques'],
            ['code' => 'CLIMATISATION',      'libelle' => 'Climatisation / Réfrigération / Froid industriel'],

            // ── Sport & Loisirs ─────────────────────────────────────────
            ['code' => 'SPORT',              'libelle' => 'Sport professionnel / Clubs sportifs / Fédérations'],
            ['code' => 'FITNESS',            'libelle' => 'Fitness / Salle de sport / Coaching sportif / Yoga'],
            ['code' => 'SPORT_NAUTIQUE',     'libelle' => 'Sports nautiques / Surf / Plongée / Voile'],

            // ── Religion & Divers ───────────────────────────────────────
            ['code' => 'RELIGION',           'libelle' => 'Religion / Cultes / Affaires religieuses / Théologie'],
            ['code' => 'AUTRE',              'libelle' => 'Autre / Non classifié'],
        ];

        foreach ($secteurs as $s) {
            SecteurActivite::firstOrCreate(
                ['code' => $s['code']],
                ['libelle' => $s['libelle']]
            );
        }
    }
}
