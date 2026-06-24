<?php

namespace Database\Seeders;

use App\Models\Competence;
use App\Models\Metier;
use Illuminate\Database\Seeder;

class MetiersBeninoiseSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Nouvelles compétences à créer ─────────────────────────────
        $nouvComp = [
            ['nom' => 'Soudure / Métallurgie / Ferronnerie',              'slug' => 'soudure-metallurgie-ferronnerie'],
            ['nom' => 'Mécanique automobile / Diagnostic moteur',         'slug' => 'mecanique-automobile-diagnostic'],
            ['nom' => 'Mécanique moto / Réparation deux-roues',           'slug' => 'mecanique-moto-deux-roues'],
            ['nom' => 'Réparation téléphones / Électronique mobile',      'slug' => 'reparation-telephones-electronique'],
            ['nom' => 'Vulcanisation / Réparation de pneus',              'slug' => 'vulcanisation-reparation-pneus'],
            ['nom' => 'Conduite de moto-taxi (Zémidjan)',                 'slug' => 'conduite-moto-taxi-zemidjan'],
            ['nom' => 'Aide ménagère / Ménage à domicile',                'slug' => 'aide-menagere-menage-a-domicile'],
            ['nom' => 'Gardiennage / Surveillance de nuit',               'slug' => 'gardiennage-surveillance-de-nuit'],
            ['nom' => 'Repassage / Lavage de linge',                      'slug' => 'repassage-lavage-de-linge'],
            ['nom' => 'Vente au marché / Commerce de détail',             'slug' => 'vente-au-marche-commerce-de-detail'],
            ['nom' => 'Agriculture vivrière (igname, maïs, manioc)',      'slug' => 'agriculture-vivriere'],
            ['nom' => 'Pêche artisanale / Filets de pêche',               'slug' => 'peche-artisanale'],
            ['nom' => 'Transformation locale (gari, akassa, huile de palme)', 'slug' => 'transformation-locale-produits-vivriers'],
            ['nom' => 'Boucherie / Découpe de viande',                    'slug' => 'boucherie-decoupe-viande'],
            ['nom' => 'Vente et traitement du poisson',                   'slug' => 'vente-et-traitement-poisson'],
            ['nom' => 'Fabrication de boissons locales (sodabi, koutoukou)', 'slug' => 'fabrication-boissons-locales'],
            ['nom' => 'Fabrication savon / Cosmétiques artisanaux',       'slug' => 'fabrication-savon-cosmetiques'],
            ['nom' => 'Réparation PC / Maintenance informatique',         'slug' => 'reparation-pc-maintenance-informatique'],
            ['nom' => 'Saisie informatique / Traitement de données',      'slug' => 'saisie-informatique'],
            ['nom' => 'Infographie / PAO / Impression numérique',         'slug' => 'infographie-pao'],
            ['nom' => 'Médecine traditionnelle / Pharmacopée africaine',  'slug' => 'medecine-traditionnelle'],
            ['nom' => 'Pompier / Sécurité incendie / Intervention',       'slug' => 'pompier-securite-incendie'],
            ['nom' => 'Agent d\'assurance / Vente de polices',            'slug' => 'agent-assurance-vente-polices'],
            ['nom' => 'Secrétariat de greffe / Procédures judiciaires',   'slug' => 'secretariat-greffe'],
            ['nom' => 'Notariat / Rédaction d\'actes notariés',           'slug' => 'notariat-actes-notaries'],
            ['nom' => 'Décoration intérieure / Tapisserie',               'slug' => 'decoration-interieure-tapisserie'],
            ['nom' => 'Manutention / Port de charges lourdes',            'slug' => 'manutention-port-de-charges'],
            ['nom' => 'Conduite de bus / Transport en commun',            'slug' => 'conduite-bus-transport-commun'],
            ['nom' => 'Livraison à moto / Coursier urbain',               'slug' => 'livraison-moto-coursier'],
            ['nom' => 'Nourrice / Garde d\'enfants à domicile',           'slug' => 'nourrice-garde-enfants'],
            ['nom' => 'Réparation d\'électroménagers / Appareils ménagers', 'slug' => 'reparation-electromenagers'],
            ['nom' => 'Sérigraphie / Impression textile',                 'slug' => 'serigraphie-impression-textile'],
            ['nom' => 'Manutention portuaire / Docker',                   'slug' => 'manutention-portuaire-docker'],
            ['nom' => 'Agent de propreté / Nettoyage',                   'slug' => 'agent-de-proprete-nettoyage'],
            ['nom' => 'Extraction de graviers / Sable / Matériaux',      'slug' => 'extraction-materiaux-construction'],
            ['nom' => 'Forge / Travail du métal artisanal',              'slug' => 'forge-travail-metal-artisanal'],
            ['nom' => 'Imprimerie / Reliure / Reprographie',             'slug' => 'imprimerie-reliure-reprographie'],
            ['nom' => 'Menuiserie plastique / Chaises / Tables artisanales', 'slug' => 'menuiserie-plastique-artisanale'],
            ['nom' => 'Réparation de bicyclettes / Vélos',               'slug' => 'reparation-bicyclettes-velos'],
            ['nom' => 'Travaux de peinture décorative / Enseigne',       'slug' => 'peinture-decorative-enseigne'],
        ];

        foreach ($nouvComp as $c) {
            Competence::firstOrCreate(['slug' => $c['slug']], ['nom' => $c['nom']]);
        }

        // ── 2. Nouveaux métiers + liens ───────────────────────────────────
        $nouveauxMetiers = [

            // ── BTP / Artisans du bâtiment ────────────────────────────────
            [
                'nom'  => 'Maçon / Ferrailleur',
                'slug' => 'macon-ferrailleur',
                'comps' => ['maconnerie-ferraillage', 'beton-arme-calcul-de-structures',
                    'metres-devis-estimation-de-travaux', 'securite-chantier-hse-btp',
                    'soudure-metallurgie-ferronnerie', 'conduite-de-chantier-suivi-de-travaux'],
            ],
            [
                'nom'  => 'Électricien du bâtiment',
                'slug' => 'electricien-du-batiment',
                'comps' => ['electricite-batiment-cablage', 'electricite-rurale-off-grid',
                    'installation-solaire-photovoltaique', 'securite-chantier-hse-btp',
                    'hse-hygiene-securite-environnement'],
            ],
            [
                'nom'  => 'Plombier / Installateur sanitaire',
                'slug' => 'plombier-installateur-sanitaire',
                'comps' => ['plomberie-sanitaire', 'securite-chantier-hse-btp',
                    'metres-devis-estimation-de-travaux', 'forage-hydraulique-villageoise'],
            ],
            [
                'nom'  => 'Peintre en bâtiment / Décorateur',
                'slug' => 'peintre-en-batiment-decorateur',
                'comps' => ['peinture-en-batiment', 'peinture-decorative-enseigne',
                    'decoration-interieure-tapisserie', 'metres-devis-estimation-de-travaux',
                    'securite-chantier-hse-btp'],
            ],
            [
                'nom'  => 'Soudeur / Métallier / Ferronnier',
                'slug' => 'soudeur-metallier-ferronnier',
                'comps' => ['soudure-metallurgie-ferronnerie', 'forge-travail-metal-artisanal',
                    'menuiserie-aluminium-pvc', 'securite-chantier-hse-btp',
                    'hse-hygiene-securite-environnement'],
            ],
            [
                'nom'  => 'Couvreur / Étanchéiste',
                'slug' => 'couvreur-etancheiste',
                'comps' => ['couverture-etancheite-zinguerie', 'securite-chantier-hse-btp',
                    'metres-devis-estimation-de-travaux'],
            ],
            [
                'nom'  => 'Climaticien / Technicien en réfrigération',
                'slug' => 'climaticien-technicien-refrigeration',
                'comps' => ['climatisation-refrigeration', 'electricite-batiment-cablage',
                    'reparation-electromenagers', 'securite-chantier-hse-btp'],
            ],
            [
                'nom'  => 'Menuisier bois / Charpentier',
                'slug' => 'menuisier-bois-charpentier',
                'comps' => ['menuiserie-bois-charpente', 'metres-devis-estimation-de-travaux',
                    'securite-chantier-hse-btp', 'conduite-de-chantier-suivi-de-travaux'],
            ],
            [
                'nom'  => 'Menuisier aluminium / Serrurier',
                'slug' => 'menuisier-aluminium-serrurier',
                'comps' => ['menuiserie-aluminium-pvc', 'soudure-metallurgie-ferronnerie',
                    'metres-devis-estimation-de-travaux', 'securite-chantier-hse-btp'],
            ],

            // ── Mécanique / Réparation ────────────────────────────────────
            [
                'nom'  => 'Mécanicien auto / Garagiste',
                'slug' => 'mecanicien-auto-garagiste',
                'comps' => ['mecanique-automobile-diagnostic', 'electricite-batiment-cablage',
                    'vulcanisation-reparation-pneus', 'hse-hygiene-securite-environnement'],
            ],
            [
                'nom'  => 'Mécanicien moto / Réparateur deux-roues',
                'slug' => 'mecanicien-moto-deux-roues',
                'comps' => ['mecanique-moto-deux-roues', 'mecanique-automobile-diagnostic',
                    'vulcanisation-reparation-pneus', 'reparation-electromenagers'],
            ],
            [
                'nom'  => 'Technicien électroménager / Réparateur',
                'slug' => 'technicien-electromenager-reparateur',
                'comps' => ['reparation-electromenagers', 'climatisation-refrigeration',
                    'electronique-embarquee', 'electricite-batiment-cablage'],
            ],
            [
                'nom'  => 'Technicien informatique / Réparateur téléphones',
                'slug' => 'technicien-informatique-reparateur-telephones',
                'comps' => ['reparation-telephones-electronique', 'reparation-pc-maintenance-informatique',
                    'electronique-embarquee', 'saisie-informatique', 'linux'],
            ],
            [
                'nom'  => 'Vulcanisateur / Réparateur de pneus',
                'slug' => 'vulcanisateur-reparateur-pneus',
                'comps' => ['vulcanisation-reparation-pneus', 'mecanique-automobile-diagnostic',
                    'hse-hygiene-securite-environnement'],
            ],
            [
                'nom'  => 'Réparateur de bicyclettes / Mécanicien vélo',
                'slug' => 'reparateur-bicyclettes-mecanicien-velo',
                'comps' => ['reparation-bicyclettes-velos', 'mecanique-moto-deux-roues',
                    'vulcanisation-reparation-pneus'],
            ],

            // ── Agriculture / Élevage / Pêche ─────────────────────────────
            [
                'nom'  => 'Agriculteur / Cultivateur vivrier',
                'slug' => 'agriculteur-cultivateur-vivrier',
                'comps' => ['agriculture-vivriere', 'phytotechnie-production-vegetale',
                    'maraichage-cultures-legumières-jardinage', 'stockage-post-recolte-silo',
                    'vulgarisation-agricole-conseil-agricole', 'protection-des-plantes-phytosanitaire'],
            ],
            [
                'nom'  => 'Éleveur / Berger (bovins, ovins, caprins)',
                'slug' => 'eleveur-berger-bovins',
                'comps' => ['elevage-bovin-ovin-caprin', 'zootechnie-production-animale',
                    'sante-animale-one-health', 'medecine-veterinaire', 'agriculture-vivriere'],
            ],
            [
                'nom'  => 'Aviculteur / Éleveur de volailles',
                'slug' => 'aviculteur-eleveur-volailles',
                'comps' => ['aviculture-elevage-de-volaille', 'zootechnie-production-animale',
                    'sante-animale-one-health', 'haccp-securite-alimentaire', 'agriculture-vivriere'],
            ],
            [
                'nom'  => 'Pêcheur / Pisciculteur artisanal',
                'slug' => 'pecheur-pisciculteur-artisanal',
                'comps' => ['peche-artisanale', 'pisciculture-aquaculture',
                    'transformation-agroalimentaire-conserverie', 'vente-et-traitement-poisson',
                    'haccp-securite-alimentaire'],
            ],
            [
                'nom'  => 'Maraîcher / Producteur de légumes',
                'slug' => 'maraicher-producteur-legumes',
                'comps' => ['maraichage-cultures-legumières-jardinage', 'irrigation-hydraulique-agricole',
                    'protection-des-plantes-phytosanitaire', 'agriculture-biologique-certification-bio',
                    'stockage-post-recolte-silo', 'vente-au-marche-commerce-de-detail'],
            ],
            [
                'nom'  => 'Apiculteur / Producteur de miel',
                'slug' => 'apiculteur-producteur-miel',
                'comps' => ['apiculture-production-de-miel', 'transformation-agroalimentaire-conserverie',
                    'haccp-securite-alimentaire', 'vente-au-marche-commerce-de-detail'],
            ],
            [
                'nom'  => 'Producteur agricole (anacarde / palmier / coton)',
                'slug' => 'producteur-agricole-anacarde-palmier-coton',
                'comps' => ['culture-du-coton-anacarde-palmier-a-huile', 'phytotechnie-production-vegetale',
                    'stockage-post-recolte-silo', 'transformation-agroalimentaire-conserverie',
                    'agriculture-vivriere'],
            ],
            [
                'nom'  => 'Transformateur alimentaire local (gari, huile de palme, akassa)',
                'slug' => 'transformateur-alimentaire-local',
                'comps' => ['transformation-locale-produits-vivriers', 'transformation-agroalimentaire-conserverie',
                    'haccp-securite-alimentaire', 'hygiene-assainissement-wash',
                    'vente-au-marche-commerce-de-detail'],
            ],
            [
                'nom'  => 'Sylviculteur / Exploitant forestier',
                'slug' => 'sylviculteur-exploitant-forestier',
                'comps' => ['agroforesterie-reboisement', 'agriculture-biologique-certification-bio',
                    'phytotechnie-production-vegetale', 'vulgarisation-agricole-conseil-agricole'],
            ],

            // ── Commerce / Marché ─────────────────────────────────────────
            [
                'nom'  => 'Commerçant / Vendeur au marché',
                'slug' => 'commercant-vendeur-au-marche',
                'comps' => ['vente-au-marche-commerce-de-detail', 'negociation-commerciale',
                    'gestion-de-stock-inventaire', 'mobile-money-wave-orange-money-mtn', 'fidelisation-client'],
            ],
            [
                'nom'  => 'Vendeur ambulant / Revendeur',
                'slug' => 'vendeur-ambulant-revendeur',
                'comps' => ['vente-au-marche-commerce-de-detail', 'negociation-commerciale',
                    'mobile-money-wave-orange-money-mtn', 'communication-orale-et-ecrite'],
            ],
            [
                'nom'  => 'Boutiquier / Épicier',
                'slug' => 'boutiquier-epicier',
                'comps' => ['vente-au-marche-commerce-de-detail', 'gestion-de-stock-inventaire',
                    'mobile-money-wave-orange-money-mtn', 'comptabilite-generale', 'fidelisation-client'],
            ],
            [
                'nom'  => 'Grossiste / Importateur-Exportateur',
                'slug' => 'grossiste-importateur-exportateur',
                'comps' => ['vente-au-marche-commerce-de-detail', 'gestion-de-stock-inventaire',
                    'gestion-dentrepot-wms', 'dedouanement-declaration-en-douane',
                    'negociation-commerciale', 'mobile-money-wave-orange-money-mtn',
                    'comptabilite-generale'],
            ],
            [
                'nom'  => 'Boucher / Charcutier',
                'slug' => 'boucher-charcutier',
                'comps' => ['boucherie-decoupe-viande', 'haccp-securite-alimentaire',
                    'hygiene-assainissement-wash', 'vente-au-marche-commerce-de-detail',
                    'mobile-money-wave-orange-money-mtn'],
            ],
            [
                'nom'  => 'Poissonnier / Vendeur de poisson',
                'slug' => 'poissonnier-vendeur-poisson',
                'comps' => ['vente-et-traitement-poisson', 'haccp-securite-alimentaire',
                    'hygiene-assainissement-wash', 'vente-au-marche-commerce-de-detail',
                    'transformation-agroalimentaire-conserverie'],
            ],
            [
                'nom'  => 'Vendeur de matériaux de construction',
                'slug' => 'vendeur-materiaux-construction',
                'comps' => ['vente-au-marche-commerce-de-detail', 'gestion-de-stock-inventaire',
                    'negociation-commerciale', 'mobile-money-wave-orange-money-mtn',
                    'metres-devis-estimation-de-travaux'],
            ],

            // ── Transport / Logistique ─────────────────────────────────────
            [
                'nom'  => 'Zémidjan / Conducteur de moto-taxi',
                'slug' => 'zemidjan-conducteur-moto-taxi',
                'comps' => ['conduite-moto-taxi-zemidjan', 'transport-de-personnes-vtc-taxi',
                    'mobile-money-wave-orange-money-mtn', 'communication-orale-et-ecrite'],
            ],
            [
                'nom'  => 'Chauffeur de bus / Minibus / Car',
                'slug' => 'chauffeur-bus-minibus-car',
                'comps' => ['conduite-bus-transport-commun', 'conduite-poids-lourds-transport-de-marchandises',
                    'transport-de-personnes-vtc-taxi', 'mecanique-automobile-diagnostic'],
            ],
            [
                'nom'  => 'Livreur à moto / Coursier urbain',
                'slug' => 'livreur-moto-coursier',
                'comps' => ['livraison-moto-coursier', 'conduite-moto-taxi-zemidjan',
                    'mobile-money-wave-orange-money-mtn', 'gestion-de-stock-inventaire'],
            ],
            [
                'nom'  => 'Manutentionnaire / Déménageur',
                'slug' => 'manutentionnaire-demenageur',
                'comps' => ['manutention-port-de-charges', 'gestion-de-stock-inventaire',
                    'conduite-de-chariot-elevateur-caces', 'hse-hygiene-securite-environnement'],
            ],
            [
                'nom'  => 'Docker / Manutentionnaire portuaire',
                'slug' => 'docker-manutentionnaire-portuaire',
                'comps' => ['manutention-portuaire-docker', 'manutention-port-de-charges',
                    'gestion-dentrepot-wms', 'hse-hygiene-securite-environnement',
                    'securite-chantier-hse-btp'],
            ],

            // ── Restauration / Alimentation ───────────────────────────────
            [
                'nom'  => 'Tenancier de maquis / Bar restaurateur',
                'slug' => 'tenancier-maquis-bar',
                'comps' => ['cuisine-arts-culinaires-gastronomie', 'service-en-salle-restauration',
                    'haccp-securite-alimentaire', 'hygiene-assainissement-wash',
                    'vente-au-marche-commerce-de-detail', 'mobile-money-wave-orange-money-mtn',
                    'gestion-de-stock-inventaire'],
            ],
            [
                'nom'  => 'Cantinière / Vendeuse de repas (street food)',
                'slug' => 'cantiniere-vendeuse-repas',
                'comps' => ['cuisine-arts-culinaires-gastronomie', 'haccp-securite-alimentaire',
                    'hygiene-assainissement-wash', 'transformation-locale-produits-vivriers',
                    'vente-au-marche-commerce-de-detail'],
            ],
            [
                'nom'  => 'Boulanger artisanal / Pâtissier de rue',
                'slug' => 'boulanger-artisanal-patissier-de-rue',
                'comps' => ['patisserie-boulangerie', 'haccp-securite-alimentaire',
                    'hygiene-assainissement-wash', 'gestion-de-stock-inventaire',
                    'transformation-locale-produits-vivriers'],
            ],
            [
                'nom'  => 'Fabricant de boissons locales (sodabi, koutoukou)',
                'slug' => 'fabricant-boissons-locales',
                'comps' => ['fabrication-boissons-locales', 'transformation-agroalimentaire-conserverie',
                    'haccp-securite-alimentaire', 'hygiene-assainissement-wash',
                    'vente-au-marche-commerce-de-detail'],
            ],

            // ── Services à la personne ─────────────────────────────────────
            [
                'nom'  => 'Aide ménagère / Employé de maison',
                'slug' => 'aide-menagere-employe-maison',
                'comps' => ['aide-menagere-menage-a-domicile', 'hygiene-assainissement-wash',
                    'repassage-lavage-de-linge', 'nourrice-garde-enfants',
                    'communication-orale-et-ecrite'],
            ],
            [
                'nom'  => 'Balayeur / Agent de propreté / Éboueur',
                'slug' => 'balayeur-agent-proprete-eboueur',
                'comps' => ['agent-de-proprete-nettoyage', 'hygiene-assainissement-wash',
                    'hse-hygiene-securite-environnement', 'gestion-des-dechets-collecte-tri'],
            ],
            [
                'nom'  => 'Lavandière / Repasseuse',
                'slug' => 'lavandiere-repasseuse',
                'comps' => ['repassage-lavage-de-linge', 'hygiene-assainissement-wash',
                    'aide-menagere-menage-a-domicile'],
            ],
            [
                'nom'  => 'Nourrice / Baby-sitter / Garde d\'enfants',
                'slug' => 'nourrice-baby-sitter',
                'comps' => ['nourrice-garde-enfants', 'pedagogie-didactique',
                    'nutrition-dietetique-alimentation', 'soins-infirmiers-nursing',
                    'communication-orale-et-ecrite'],
            ],
            [
                'nom'  => 'Jardinier / Entretien des espaces verts',
                'slug' => 'jardinier-entretien-espaces-verts',
                'comps' => ['maraichage-cultures-legumières-jardinage', 'agroforesterie-reboisement',
                    'irrigation-hydraulique-agricole', 'agent-de-proprete-nettoyage'],
            ],
            [
                'nom'  => 'Gardien / Veilleur de nuit',
                'slug' => 'gardien-veilleur-de-nuit',
                'comps' => ['gardiennage-surveillance-de-nuit', 'hse-hygiene-securite-environnement',
                    'communication-orale-et-ecrite'],
            ],
            [
                'nom'  => 'Agent de nettoyage industriel',
                'slug' => 'agent-de-nettoyage-industriel',
                'comps' => ['agent-de-proprete-nettoyage', 'hygiene-assainissement-wash',
                    'hse-hygiene-securite-environnement', 'gestion-des-dechets-collecte-tri'],
            ],

            // ── Artisanat / Mode / Culture ─────────────────────────────────
            [
                'nom'  => 'Tailleur / Couturier artisanal',
                'slug' => 'tailleur-couturier-artisanal',
                'comps' => ['stylisme-couture-modelisme', 'creation-wax-tissu-africain',
                    'broderie-couture-traditionnelle', 'vente-au-marche-commerce-de-detail'],
            ],
            [
                'nom'  => 'Tisserand / Brodeur traditionnel',
                'slug' => 'tisserand-brodeur-traditionnel',
                'comps' => ['broderie-couture-traditionnelle', 'creation-wax-tissu-africain',
                    'teinture-batik-tie-dye', 'vente-au-marche-commerce-de-detail'],
            ],
            [
                'nom'  => 'Teinturier / Batikeur',
                'slug' => 'teinturier-batikeur',
                'comps' => ['teinture-batik-tie-dye', 'creation-wax-tissu-africain',
                    'broderie-couture-traditionnelle', 'fabrication-savon-cosmetiques',
                    'vente-au-marche-commerce-de-detail'],
            ],
            [
                'nom'  => 'Fabricant de savon / Cosmétiques artisanaux',
                'slug' => 'fabricant-savon-cosmetiques-artisanaux',
                'comps' => ['fabrication-savon-cosmetiques', 'transformation-agroalimentaire-conserverie',
                    'haccp-securite-alimentaire', 'vente-au-marche-commerce-de-detail'],
            ],
            [
                'nom'  => 'Vannier / Artisan (osier, paille, raphia)',
                'slug' => 'vannier-artisan-osier-paille-raphia',
                'comps' => ['vannerie-cordonnerie', 'vente-au-marche-commerce-de-detail',
                    'creation-wax-tissu-africain'],
            ],
            [
                'nom'  => 'Sculpteur / Artiste (bois, bronze, ivoire)',
                'slug' => 'sculpteur-artiste-bois-bronze',
                'comps' => ['poterie-ceramique-sculpture', 'menuiserie-bois-charpente',
                    'forge-travail-metal-artisanal', 'vente-au-marche-commerce-de-detail'],
            ],
            [
                'nom'  => 'Sérigraphe / Imprimeur textile',
                'slug' => 'serigraphe-imprimeur-textile',
                'comps' => ['serigraphie-impression-textile', 'infographie-pao',
                    'creation-wax-tissu-africain', 'photoshop'],
            ],
            [
                'nom'  => 'Tapissier / Décorateur d\'intérieur',
                'slug' => 'tapissier-decorateur-interieur',
                'comps' => ['decoration-interieure-tapisserie', 'menuiserie-bois-charpente',
                    'stylisme-couture-modelisme', 'peinture-en-batiment'],
            ],
            [
                'nom'  => 'Imprimeur / Reprographe',
                'slug' => 'imprimeur-reprographe',
                'comps' => ['imprimerie-reliure-reprographie', 'infographie-pao',
                    'serigraphie-impression-textile', 'photoshop'],
            ],
            [
                'nom'  => 'Forgeron / Artisan métallique traditionnel',
                'slug' => 'forgeron-artisan-metallique',
                'comps' => ['forge-travail-metal-artisanal', 'soudure-metallurgie-ferronnerie',
                    'vente-au-marche-commerce-de-detail'],
            ],
            [
                'nom'  => 'Potier / Céramiste traditionnel',
                'slug' => 'potier-ceramiste-traditionnel',
                'comps' => ['poterie-ceramique-sculpture', 'vente-au-marche-commerce-de-detail'],
            ],

            // ── Éducation / Formation ──────────────────────────────────────
            [
                'nom'  => 'Instituteur / Maître d\'école primaire',
                'slug' => 'instituteur-maitre-ecole-primaire',
                'comps' => ['pedagogie-didactique', 'enseignement-des-mathematiques',
                    'enseignement-du-francais-linguistique', 'enseignement-des-sciences-svt-physique',
                    'enseignement-des-langues-locales-fon-yoruba-dendi-bariba',
                    'evaluation-des-apprentissages', 'alphabetisation-des-adultes'],
            ],
            [
                'nom'  => 'Professeur de collège / Lycée',
                'slug' => 'professeur-college-lycee',
                'comps' => ['pedagogie-didactique', 'enseignement-des-mathematiques',
                    'enseignement-des-sciences-svt-physique', 'enseignement-du-francais-linguistique',
                    'enseignement-de-langlais-tefl', 'evaluation-des-apprentissages',
                    'ingenierie-pedagogique'],
            ],
            [
                'nom'  => 'Directeur d\'école / Proviseur de lycée',
                'slug' => 'directeur-ecole-proviseur',
                'comps' => ['gestion-detablissement-scolaire', 'management-dequipe',
                    'pedagogie-didactique', 'communication-orale-et-ecrite',
                    'redaction-administrative-courriers-officiels',
                    'gestion-budgetaire-budget-previsionnel'],
            ],
            [
                'nom'  => 'Éducateur de jeunes enfants / Jardinière d\'enfants',
                'slug' => 'educateur-jeunes-enfants-jardiniere',
                'comps' => ['pedagogie-didactique', 'nourrice-garde-enfants',
                    'nutrition-dietetique-alimentation', 'communication-orale-et-ecrite',
                    'evaluation-des-apprentissages'],
            ],
            [
                'nom'  => 'Moniteur de conduite / École de conduite',
                'slug' => 'moniteur-de-conduite',
                'comps' => ['conduite-poids-lourds-transport-de-marchandises',
                    'transport-de-personnes-vtc-taxi', 'pedagogie-didactique',
                    'communication-orale-et-ecrite'],
            ],
            [
                'nom'  => 'Formateur professionnel / Instructeur métier',
                'slug' => 'formateur-professionnel-instructeur',
                'comps' => ['formation-professionnelle', 'formation-des-formateurs-train-the-trainer',
                    'pedagogie-didactique', 'ingenierie-pedagogique',
                    'communication-orale-et-ecrite', 'eftp-enseignement-technique-et-professionnel'],
            ],
            [
                'nom'  => 'Enseignant d\'anglais / Professeur de langues',
                'slug' => 'enseignant-anglais-professeur-langues',
                'comps' => ['enseignement-de-langlais-tefl', 'pedagogie-didactique',
                    'enseignement-des-langues-locales-fon-yoruba-dendi-bariba',
                    'communication-orale-et-ecrite', 'evaluation-des-apprentissages'],
            ],

            // ── Santé / Médecine ───────────────────────────────────────────
            [
                'nom'  => 'Tradipraticien / Médecine traditionnelle',
                'slug' => 'tradipraticien-medecine-traditionnelle',
                'comps' => ['medecine-traditionnelle', 'medecine-communautaire-agents-de-sante',
                    'nutrition-dietetique-alimentation', 'hygiene-assainissement-wash'],
            ],
            [
                'nom'  => 'Opticien / Lunetier',
                'slug' => 'opticien-lunetier',
                'comps' => ['ophtalmologie-optique-medicale', 'vente-au-marche-commerce-de-detail',
                    'communication-orale-et-ecrite'],
            ],
            [
                'nom'  => 'Assistant pharmacien / Vendeur en pharmacie',
                'slug' => 'assistant-pharmacien-vendeur-pharmacie',
                'comps' => ['pharmacologie-gestion-medicaments', 'haccp-securite-alimentaire',
                    'communication-orale-et-ecrite', 'vente-au-marche-commerce-de-detail',
                    'hygiene-assainissement-wash'],
            ],
            [
                'nom'  => 'Accoucheuse traditionnelle / Matrone',
                'slug' => 'accoucheuse-traditionnelle-matrone',
                'comps' => ['sage-femme-maieutique', 'soins-infirmiers-nursing',
                    'medecine-communautaire-agents-de-sante', 'hygiene-assainissement-wash',
                    'nutrition-dietetique-alimentation'],
            ],

            // ── Informatique / Numérique ───────────────────────────────────
            [
                'nom'  => 'Technicien informatique / Réparateur PC',
                'slug' => 'technicien-informatique-reparateur-pc',
                'comps' => ['reparation-pc-maintenance-informatique', 'reparation-telephones-electronique',
                    'linux', 'windows-server', 'ubuntu', 'saisie-informatique'],
            ],
            [
                'nom'  => 'Opérateur de saisie / Saisiste',
                'slug' => 'operateur-saisie-saisiste',
                'comps' => ['saisie-informatique', 'word', 'excel', 'google-sheets',
                    'redaction-administrative-courriers-officiels'],
            ],
            [
                'nom'  => 'Infographiste / Graphiste PAO',
                'slug' => 'infographiste-graphiste-pao',
                'comps' => ['infographie-pao', 'canva', 'photoshop', 'illustrator', 'indesign',
                    'serigraphie-impression-textile', 'after-effects'],
            ],
            [
                'nom'  => 'Webmaster / Administrateur de site web',
                'slug' => 'webmaster-administrateur-site-web',
                'comps' => ['wordpress', 'seo-referencement-naturel', 'html', 'css',
                    'javascript', 'reparation-pc-maintenance-informatique', 'google-analytics-ga4'],
            ],

            // ── Médias / Communication ─────────────────────────────────────
            [
                'nom'  => 'Photographe / Vidéaste / Caméraman',
                'slug' => 'photographe-videaste-cameraman',
                'comps' => ['photojournalisme-reportage-photo', 'montage-video-post-production',
                    'prise-de-son-ingenieur-du-son', 'premiere-pro', 'photoshop',
                    'lightroom', 'capcut-montage-video-mobile'],
            ],
            [
                'nom'  => 'Animateur radio / Présentateur TV',
                'slug' => 'animateur-radio-presentateur-tv',
                'comps' => ['journalisme-radio-presentateur-radio', 'presentateur-tv-jt-animateur',
                    'prise-de-son-ingenieur-du-son', 'communication-orale-et-ecrite',
                    'community-management'],
            ],
            [
                'nom'  => 'Technicien son / Ingénieur du son',
                'slug' => 'technicien-son-ingenieur-du-son',
                'comps' => ['prise-de-son-ingenieur-du-son', 'composition-musicale-production-musicale',
                    'montage-video-post-production'],
            ],

            // ── Sécurité / Urgences ────────────────────────────────────────
            [
                'nom'  => 'Pompier / Sapeur-pompier',
                'slug' => 'pompier-sapeur-pompier',
                'comps' => ['pompier-securite-incendie', 'hse-hygiene-securite-environnement',
                    'iso-45001-securite-au-travail-ohsas', 'urgences-reanimation-smur',
                    'soins-infirmiers-nursing'],
            ],

            // ── Finance / Assurance ────────────────────────────────────────
            [
                'nom'  => 'Caissier / Agent de caisse',
                'slug' => 'caissier-agent-de-caisse',
                'comps' => ['comptabilite-generale', 'comptabilite-syscohada',
                    'mobile-money-wave-orange-money-mtn', 'excel',
                    'gestion-budgetaire-budget-previsionnel', 'vente-au-marche-commerce-de-detail'],
            ],
            [
                'nom'  => 'Agent d\'assurance / Courtier en assurance',
                'slug' => 'agent-assurance-courtier',
                'comps' => ['agent-assurance-vente-polices', 'negociation-commerciale',
                    'communication-orale-et-ecrite', 'fidelisation-client',
                    'excel', 'plan-daffaires-business-plan'],
            ],

            // ── Droit / Administration ─────────────────────────────────────
            [
                'nom'  => 'Greffier / Clerc de tribunal',
                'slug' => 'greffier-clerc-tribunal',
                'comps' => ['secretariat-greffe', 'droit-civil', 'droit-penal',
                    'redaction-administrative-courriers-officiels', 'word', 'excel'],
            ],
            [
                'nom'  => 'Huissier de justice',
                'slug' => 'huissier-de-justice',
                'comps' => ['droit-civil', 'contentieux-plaidoirie',
                    'redaction-de-contrats-actes-juridiques', 'droit-ohada',
                    'communication-orale-et-ecrite'],
            ],
            [
                'nom'  => 'Notaire / Clerc de notaire',
                'slug' => 'notaire-clerc-de-notaire',
                'comps' => ['droit-foncier-notariat', 'notariat-actes-notaries',
                    'redaction-de-contrats-actes-juridiques', 'droit-civil',
                    'droit-ohada', 'word'],
            ],
            [
                'nom'  => 'Agent d\'état civil / Officier de mairie',
                'slug' => 'agent-etat-civil-officier-mairie',
                'comps' => ['redaction-administrative-courriers-officiels', 'droit-civil',
                    'secretariat-greffe', 'word', 'communication-orale-et-ecrite'],
            ],

            // ── Tourisme / Hôtellerie ──────────────────────────────────────
            [
                'nom'  => 'Réceptionniste d\'hôtel / Hôtesse d\'accueil',
                'slug' => 'receptionniste-hotel-hotesse-accueil',
                'comps' => ['reception-hoteliere-accueil', 'communication-orale-et-ecrite',
                    'enseignement-de-langlais-tefl', 'fidelisation-client',
                    'mobile-money-wave-orange-money-mtn'],
            ],
            [
                'nom'  => 'Guide touristique / Interprète local',
                'slug' => 'guide-touristique-interprete-local',
                'comps' => ['guide-touristique-interprete', 'enseignement-de-langlais-tefl',
                    'communication-orale-et-ecrite', 'agritourisme-ecotourisme'],
            ],
            [
                'nom'  => 'Barman / Tenancier de bar',
                'slug' => 'barman-tenancier-de-bar',
                'comps' => ['barman-mixologie-cocktails', 'service-en-salle-restauration',
                    'haccp-securite-alimentaire', 'mobile-money-wave-orange-money-mtn',
                    'fidelisation-client'],
            ],

            // ── Extraction / Ressources ────────────────────────────────────
            [
                'nom'  => 'Extracteur de graviers / Sable / Matériaux',
                'slug' => 'extracteur-graviers-sable',
                'comps' => ['extraction-materiaux-construction', 'manutention-port-de-charges',
                    'hse-hygiene-securite-environnement', 'conduite-poids-lourds-transport-de-marchandises'],
            ],

            // ── Énergie / Solaire ──────────────────────────────────────────
            [
                'nom'  => 'Installateur solaire / Technicien photovoltaïque',
                'slug' => 'installateur-solaire-technicien-photovoltaique',
                'comps' => ['installation-solaire-photovoltaique', 'electricite-batiment-cablage',
                    'electricite-rurale-off-grid', 'securite-chantier-hse-btp'],
            ],

            // ── Arts / Culture ─────────────────────────────────────────────
            [
                'nom'  => 'Musicien / Chanteur traditionnel béninois',
                'slug' => 'musicien-chanteur-traditionnel-beninois',
                'comps' => ['chant-musique-traditionnelle-beninoise', 'composition-musicale-production-musicale',
                    'danse-choregraphie', 'prise-de-son-ingenieur-du-son'],
            ],
            [
                'nom'  => 'Danseur / Chorégraphe traditionnel',
                'slug' => 'danseur-choregraphe-traditionnel',
                'comps' => ['danse-choregraphie', 'chant-musique-traditionnelle-beninoise',
                    'scenographie-decoration-de-plateau'],
            ],
        ];

        foreach ($nouveauxMetiers as $data) {
            $metier = Metier::firstOrCreate(
                ['slug' => $data['slug']],
                ['nom'  => $data['nom']]
            );
            $ids = Competence::whereIn('slug', $data['comps'])->pluck('id');
            $metier->competences()->syncWithoutDetaching($ids);
        }

        // ── 3. Lier les métiers existants non encore couverts ─────────────
        $existingLinks = [
            // BTP artisans
            'carreleur-faiencier'          => ['carrelage-faience', 'metres-devis-estimation-de-travaux', 'securite-chantier-hse-btp', 'hse-hygiene-securite-environnement'],
            'artisan-menuisier-ebeniste'   => ['menuiserie-bois-charpente', 'menuiserie-aluminium-pvc', 'metres-devis-estimation-de-travaux', 'vente-au-marche-commerce-de-detail'],

            // Artisans mode/beauté
            'coiffeur-coiffeuse-tresseuse' => ['coiffure-tresses-africaines', 'esthetique-maquillage-nail-art', 'hygiene-assainissement-wash', 'mobile-money-wave-orange-money-mtn'],
            'barbier'                      => ['barbier-coiffure-homme', 'hygiene-assainissement-wash', 'communication-orale-et-ecrite'],
            'bijoutier-joaillier-orfevre'  => ['bijouterie-joaillerie', 'vente-au-marche-commerce-de-detail', 'mobile-money-wave-orange-money-mtn'],
            'cordonnier-maroquinier'       => ['vannerie-cordonnerie', 'stylisme-couture-modelisme', 'vente-au-marche-commerce-de-detail'],

            // Transport
            'chauffeur-vtc-taxi'           => ['transport-de-personnes-vtc-taxi', 'mobile-money-wave-orange-money-mtn', 'communication-orale-et-ecrite'],

            // Mobile money
            'agent-mobile-money-paiement-digital' => ['mobile-money-wave-orange-money-mtn', 'comptabilite-generale', 'communication-orale-et-ecrite', 'vente-au-marche-commerce-de-detail'],

            // Santé
            'biologiste-medical-technicien-de-laboratoire' => ['biologie-medicale-analyses-de-laboratoire', 'soins-infirmiers-nursing', 'haccp-securite-alimentaire', 'hygiene-assainissement-wash'],
            'kinesitherapeute-reeducateur' => ['kinesitherapie-reeducation', 'soins-infirmiers-nursing', 'nutrition-dietetique-alimentation', 'communication-orale-et-ecrite'],
            'dentiste-chirurgien-dentiste' => ['stomatologie-chirurgie-dentaire', 'soins-infirmiers-nursing', 'hygiene-assainissement-wash', 'communication-orale-et-ecrite'],
            'medecin-generaliste'          => ['soins-infirmiers-nursing', 'medecine-communautaire-agents-de-sante', 'pharmacologie-gestion-medicaments', 'urgences-reanimation-smur', 'hygiene-assainissement-wash'],
            'chirurgien'                   => ['urgences-reanimation-smur', 'soins-infirmiers-nursing', 'biologie-medicale-analyses-de-laboratoire', 'hygiene-assainissement-wash'],

            // Administration / Droit
            'agent-des-impots-inspecteur-fiscal' => ['fiscalite-declarations-fiscales', 'comptabilite-syscohada', 'droit-fiscal', 'ohada-droit-comptable-ohada', 'excel', 'word'],
            'agent-transitaire-commissionnaire-en-douane' => ['dedouanement-declaration-en-douane', 'freight-forwarding-transit', 'gestion-dentrepot-wms', 'droit-des-affaires', 'excel'],

            // BTP ingénierie
            'architecte'                   => ['dessin-technique-autocad', 'revit-bim', 'sketchup', 'metres-devis-estimation-de-travaux', 'conduite-de-chantier-suivi-de-travaux', 'beton-arme-calcul-de-structures', 'topographie-geometre'],
        ];

        foreach ($existingLinks as $slug => $compSlugs) {
            $metier = Metier::where('slug', $slug)->first();
            if (!$metier) {
                continue;
            }
            $ids = Competence::whereIn('slug', $compSlugs)->pluck('id');
            $metier->competences()->syncWithoutDetaching($ids);
        }
    }
}
