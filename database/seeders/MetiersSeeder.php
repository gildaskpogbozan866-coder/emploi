<?php

namespace Database\Seeders;

use App\Models\Metier;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MetiersSeeder extends Seeder
{
    public function run(): void
    {
        $metiers = [

            // ── Tech & Développement ───────────────────────────────────────
            ['nom' => 'Développeur Full Stack'],
            ['nom' => 'Développeur Backend'],
            ['nom' => 'Développeur Frontend'],
            ['nom' => 'Développeur Mobile (iOS / Android / Flutter)'],
            ['nom' => 'Développeur WordPress / CMS'],
            ['nom' => 'Développeur E-commerce (Shopify / WooCommerce)'],
            ['nom' => 'Architecte Logiciel / Solutions Architect'],
            ['nom' => 'DevOps / SRE (Site Reliability Engineer)'],
            ['nom' => 'Ingénieur Cloud (AWS / Azure / GCP)'],
            ['nom' => 'Administrateur Système et Réseaux'],
            ['nom' => 'Ingénieur Cybersécurité / Pentesteur'],
            ['nom' => 'Ingénieur IoT / Systèmes embarqués'],
            ['nom' => 'Développeur Blockchain / Web3'],
            ['nom' => 'Technicien Informatique / Support IT'],
            ['nom' => 'Ingénieur QA / Testeur Logiciel'],
            ['nom' => 'Directeur Technique (CTO)'],
            ['nom' => 'Product Manager / Chef de Produit Digital'],
            ['nom' => 'Scrum Master'],
            ['nom' => 'Product Owner'],

            // ── Data & IA ──────────────────────────────────────────────────
            ['nom' => 'Data Analyst'],
            ['nom' => 'Data Scientist'],
            ['nom' => 'Data Engineer'],
            ['nom' => 'Business Intelligence (BI) Analyst'],
            ['nom' => 'Ingénieur Machine Learning / IA'],
            ['nom' => 'Ingénieur NLP / Traitement du langage'],
            ['nom' => 'Responsable Data / Chief Data Officer (CDO)'],

            // ── Design & UX ────────────────────────────────────────────────
            ['nom' => 'UX Designer / Designer UX'],
            ['nom' => 'UI Designer / Designer Interface'],
            ['nom' => 'Designer Graphique / Graphiste'],
            ['nom' => 'Motion Designer / Animateur 2D-3D'],
            ['nom' => 'Directeur Artistique'],
            ['nom' => 'Illustrateur / Bande Dessinée'],
            ['nom' => 'Photographe Professionnel'],
            ['nom' => 'Vidéaste / Caméraman / Réalisateur'],
            ['nom' => 'Monteur Vidéo / Post-production'],

            // ── Finance & Comptabilité ─────────────────────────────────────
            ['nom' => 'Comptable'],
            ['nom' => 'Expert-Comptable'],
            ['nom' => 'Auditeur Interne'],
            ['nom' => 'Auditeur Externe / Commissaire aux Comptes'],
            ['nom' => 'Contrôleur de Gestion'],
            ['nom' => 'Directeur Financier (CFO)'],
            ['nom' => 'Analyste Financier'],
            ['nom' => 'Trésorier / Gestionnaire de Trésorerie'],
            ['nom' => 'Analyste de Crédit / Gestionnaire de Risques'],
            ['nom' => 'Agent de Microfinance / Chargé de Prêts'],
            ['nom' => 'Fiscaliste / Conseiller Fiscal'],
            ['nom' => 'Agent Mobile Money / Paiement Digital'],
            ['nom' => 'Gestionnaire de Patrimoine / Conseiller Financier'],
            ['nom' => 'Trader / Analyste Marchés Financiers'],

            // ── Commerce & Ventes ──────────────────────────────────────────
            ['nom' => 'Commercial / Chargé de Développement Commercial'],
            ['nom' => 'Responsable Commercial / Directeur des Ventes'],
            ['nom' => 'Key Account Manager (KAM) / Grand Compte'],
            ['nom' => 'Agent Commercial / Représentant'],
            ['nom' => 'Acheteur / Responsable Achats'],
            ['nom' => 'Responsable Import-Export'],
            ['nom' => 'Chargé de Clientèle / Account Manager'],
            ['nom' => 'Vendeur / Conseiller de Vente'],
            ['nom' => 'Merchandiser / Animateur Commercial'],
            ['nom' => 'Télévendeur / Chargé de Prospection'],

            // ── Marketing & Communication ──────────────────────────────────
            ['nom' => 'Responsable Marketing'],
            ['nom' => 'Chargé de Marketing Digital'],
            ['nom' => 'Community Manager'],
            ['nom' => 'Content Creator / Créateur de Contenu'],
            ['nom' => 'Chargé SEO / SEA / Traffic Manager'],
            ['nom' => 'Influenceur / Créateur Digital'],
            ['nom' => 'Brand Manager / Responsable de Marque'],
            ['nom' => 'Chargé de Communication'],
            ['nom' => 'Chargé de Relations Publiques / RP'],
            ['nom' => 'Chargé d\'Événementiel / Event Manager'],
            ['nom' => 'Rédacteur Web / Copywriter'],
            ['nom' => 'Responsable Communication Institutionnelle'],

            // ── Ressources Humaines ────────────────────────────────────────
            ['nom' => 'Chargé de Recrutement / Talent Acquisition'],
            ['nom' => 'Responsable RH / DRH'],
            ['nom' => 'Gestionnaire de Paie'],
            ['nom' => 'Chargé de Formation / Responsable Développement RH'],
            ['nom' => 'Consultant RH / Coach Professionnel'],
            ['nom' => 'Chargé GPEC / Gestion des Emplois et Compétences'],
            ['nom' => 'Responsable Relations Sociales'],

            // ── Gestion de Projet & Stratégie ─────────────────────────────
            ['nom' => 'Chef de Projet'],
            ['nom' => 'PMO / Responsable Bureau de Projet'],
            ['nom' => 'Business Analyst / MOA-AMOA'],
            ['nom' => 'Consultant en Management / Stratégie'],
            ['nom' => 'Chargé de Planification / Planificateur'],
            ['nom' => 'Responsable Qualité / Ingénieur Qualité'],
            ['nom' => 'Responsable HSE (Hygiène Sécurité Environnement)'],
            ['nom' => 'Lean Manager / Responsable Amélioration Continue'],

            // ── Droit & Juridique ──────────────────────────────────────────
            ['nom' => 'Avocat'],
            ['nom' => 'Notaire'],
            ['nom' => 'Juriste d\'Entreprise'],
            ['nom' => 'Magistrat / Juge'],
            ['nom' => 'Greffier'],
            ['nom' => 'Huissier de Justice'],
            ['nom' => 'Conseiller Juridique / Compliance Officer'],
            ['nom' => 'Médiateur / Arbitre'],
            ['nom' => 'Expert en Propriété Intellectuelle'],

            // ── Santé & Médical ────────────────────────────────────────────
            ['nom' => 'Médecin Généraliste'],
            ['nom' => 'Médecin Spécialiste (Cardiologue, Pédiatre…)'],
            ['nom' => 'Chirurgien'],
            ['nom' => 'Anesthésiste-Réanimateur'],
            ['nom' => 'Infirmier / Infirmière'],
            ['nom' => 'Sage-Femme / Maïeuticien'],
            ['nom' => 'Pharmacien'],
            ['nom' => 'Dentiste / Chirurgien Dentiste'],
            ['nom' => 'Opticien / Ophtalmologue'],
            ['nom' => 'Kinésithérapeute / Rééducateur'],
            ['nom' => 'Aide-Soignant / Agent de Santé Communautaire'],
            ['nom' => 'Biologiste Médical / Technicien de Laboratoire'],
            ['nom' => 'Épidémiologiste / Santé Publique'],
            ['nom' => 'Nutritionniste / Diététicien'],
            ['nom' => 'Psychologue / Psychiatre'],
            ['nom' => 'Médecin du Travail'],
            ['nom' => 'Médecin Vétérinaire'],
            ['nom' => 'Technicien en Radiologie / Imagerie Médicale'],
            ['nom' => 'Responsable Hygiène Hospitalière'],

            // ── Éducation & Formation ──────────────────────────────────────
            ['nom' => 'Enseignant Primaire / Instituteur'],
            ['nom' => 'Professeur (Collège / Lycée)'],
            ['nom' => 'Enseignant-Chercheur / Maître de Conférences'],
            ['nom' => 'Formateur Professionnel'],
            ['nom' => 'Directeur d\'École / Proviseur'],
            ['nom' => 'Conseiller Pédagogique / Inspecteur de l\'Éducation'],
            ['nom' => 'Ingénieur Pédagogique / Concepteur E-learning'],
            ['nom' => 'Tuteur / Soutien Scolaire'],
            ['nom' => 'Animateur d\'Alphabétisation'],
            ['nom' => 'Orientateur Scolaire / Conseiller d\'Orientation'],

            // ── ONG & Développement International ─────────────────────────
            ['nom' => 'Chargé de Projet ONG / Coordinateur de Programme'],
            ['nom' => 'Chargé de Suivi-Évaluation (M&E)'],
            ['nom' => 'Fundraiser / Chargé de Mobilisation de Ressources'],
            ['nom' => 'Expert en Genre / Inclusion / Protection'],
            ['nom' => 'Logisticien Humanitaire'],
            ['nom' => 'Consultant Développement International'],
            ['nom' => 'Chargé de Plaidoyer / Advocacy'],
            ['nom' => 'Expert WASH / Eau et Assainissement'],
            ['nom' => 'Nutritionniste Humanitaire / Sécurité Alimentaire'],

            // ── Administration & Secteur Public ───────────────────────────
            ['nom' => 'Secrétaire de Direction / Assistant de Direction'],
            ['nom' => 'Fonctionnaire / Agent de l\'État'],
            ['nom' => 'Chargé de Mission / Conseiller Technique'],
            ['nom' => 'Douanier / Inspecteur des Douanes'],
            ['nom' => 'Agent des Impôts / Inspecteur Fiscal'],
            ['nom' => 'Diplomate / Attaché d\'Ambassade'],
            ['nom' => 'Militaire / Officier des Forces Armées'],
            ['nom' => 'Policier / Officier de Police'],
            ['nom' => 'Sapeur-Pompier / Secouriste'],
            ['nom' => 'Responsable Passation de Marchés Publics'],
            ['nom' => 'Planificateur / Chargé de Planification du Développement'],

            // ── Agriculture & Environnement ────────────────────────────────
            ['nom' => 'Ingénieur Agronome'],
            ['nom' => 'Technicien Agricole / Conseiller Agricole'],
            ['nom' => 'Ingénieur en Génie Rural / Hydraulique Agricole'],
            ['nom' => 'Éleveur / Technicien Zootechnicien'],
            ['nom' => 'Pêcheur / Technicien en Aquaculture'],
            ['nom' => 'Ingénieur Forestier / Sylviculteur'],
            ['nom' => 'Ingénieur Agroalimentaire / Transformateur'],
            ['nom' => 'Vulgarisateur Agricole / Agent de Terrain'],
            ['nom' => 'Environnementaliste / Expert en Biodiversité'],
            ['nom' => 'Ingénieur Eau et Assainissement (WASH)'],
            ['nom' => 'Responsable RSE / Développement Durable'],
            ['nom' => 'Expert Changement Climatique / Carbone'],

            // ── BTP & Génie Civil ──────────────────────────────────────────
            ['nom' => 'Architecte'],
            ['nom' => 'Ingénieur Génie Civil / Ingénieur Structure'],
            ['nom' => 'Conducteur de Travaux / Ingénieur Chantier'],
            ['nom' => 'Chef de Chantier / Maître d\'Œuvre'],
            ['nom' => 'Topographe / Géomètre-Expert'],
            ['nom' => 'Ingénieur VRD / Réseaux et Voirie'],
            ['nom' => 'Ingénieur en Géotechnique / Études de Sol'],
            ['nom' => 'Dessinateur-Projeteur / Technicien CAO/DAO'],
            ['nom' => 'Électricien Bâtiment'],
            ['nom' => 'Plombier / Technicien Sanitaire'],
            ['nom' => 'Menuisier Bois / Menuisier Aluminium'],
            ['nom' => 'Maçon / Chef Maçon'],
            ['nom' => 'Peintre en Bâtiment / Enduiseur'],
            ['nom' => 'Carreleur / Faïencier'],
            ['nom' => 'Soudeur / Ferronnier / Chaudronnier'],
            ['nom' => 'Technicien Climatisation / Froid Industriel'],
            ['nom' => 'Responsable Immobilier / Promoteur Immobilier'],
            ['nom' => 'Gestionnaire de Copropriété / Property Manager'],

            // ── Industrie & Production ─────────────────────────────────────
            ['nom' => 'Ingénieur de Production / Responsable de Production'],
            ['nom' => 'Ingénieur Qualité / Responsable Qualité Industrie'],
            ['nom' => 'Technicien de Maintenance Industrielle'],
            ['nom' => 'Technicien Électrotechnicien / Électromécanicien'],
            ['nom' => 'Mécanicien Industriel / Mécanicien de Précision'],
            ['nom' => 'Opérateur de Production / Agent de Fabrication'],
            ['nom' => 'Ingénieur Chimiste / Ingénieur Process'],
            ['nom' => 'Ingénieur en Génie Industriel / Organisation'],
            ['nom' => 'Technicien HSE Industrie'],

            // ── Transport & Logistique ─────────────────────────────────────
            ['nom' => 'Chauffeur Poids Lourd / Transporteur'],
            ['nom' => 'Chauffeur VTC / Taxi'],
            ['nom' => 'Logisticien / Responsable Logistique'],
            ['nom' => 'Responsable Entrepôt / Magasinier Chef'],
            ['nom' => 'Agent Transitaire / Commissionnaire en Douane'],
            ['nom' => 'Gestionnaire de Flotte / Fleet Manager'],
            ['nom' => 'Pilote d\'Avion Professionnel'],
            ['nom' => 'Agent de Fret / Freight Forwarder'],
            ['nom' => 'Responsable Supply Chain'],

            // ── Énergie ────────────────────────────────────────────────────
            ['nom' => 'Technicien Solaire / Installateur Photovoltaïque'],
            ['nom' => 'Ingénieur en Énergies Renouvelables'],
            ['nom' => 'Électricien Industriel / Technicien Électrique'],
            ['nom' => 'Ingénieur Électricien / Ingénieur Énergie'],
            ['nom' => 'Technicien Forage / Hydraulicien Rural'],
            ['nom' => 'Ingénieur Pétrole / Géologue'],

            // ── Tourisme & Hôtellerie ──────────────────────────────────────
            ['nom' => 'Réceptionniste Hôtelier / Agent d\'Accueil'],
            ['nom' => 'Gouvernante d\'Hôtel / Responsable Hébergement'],
            ['nom' => 'Serveur / Maître d\'Hôtel'],
            ['nom' => 'Chef Cuisinier / Cuisinier'],
            ['nom' => 'Pâtissier / Boulanger'],
            ['nom' => 'Barman / Mixologue'],
            ['nom' => 'Guide Touristique / Accompagnateur de Voyage'],
            ['nom' => 'Agent de Voyage / Tour Opérateur'],
            ['nom' => 'Directeur d\'Hôtel / Responsable Établissement'],
            ['nom' => 'Organisateur d\'Événements / Wedding Planner'],

            // ── Médias, Presse & Audiovisuel ──────────────────────────────
            ['nom' => 'Journaliste / Reporter'],
            ['nom' => 'Journaliste Radio / Animateur Radio'],
            ['nom' => 'Présentateur TV / Journaliste Plateau'],
            ['nom' => 'Rédacteur en Chef / Chef de Rubrique'],
            ['nom' => 'Ingénieur du Son / Technicien Son'],
            ['nom' => 'Caméraman / Technicien Image'],
            ['nom' => 'Monteur Vidéo / Réalisateur'],
            ['nom' => 'Photographe de Presse / Photojournaliste'],

            // ── Mode, Beauté & Artisanat ───────────────────────────────────
            ['nom' => 'Styliste / Couturier / Créateur de Mode'],
            ['nom' => 'Modéliste / Tailleur'],
            ['nom' => 'Tisserand / Créateur Wax Africain'],
            ['nom' => 'Coiffeur / Coiffeuse / Tresseuse'],
            ['nom' => 'Barbier'],
            ['nom' => 'Esthéticien(ne) / Maquilleur(se)'],
            ['nom' => 'Bijoutier / Joaillier / Orfèvre'],
            ['nom' => 'Potier / Céramiste / Sculpteur'],
            ['nom' => 'Artisan Menuisier / Ébéniste'],
            ['nom' => 'Cordonnier / Maroquinier'],
            ['nom' => 'Teinturier / Artisan Batik / Tie-dye'],
            ['nom' => 'Vannier / Tisseur de Paniers'],

            // ── Sécurité & Gardiennage ─────────────────────────────────────
            ['nom' => 'Agent de Sécurité / Vigile'],
            ['nom' => 'Responsable Sécurité / Chief Security Officer (CSO)'],
            ['nom' => 'Technicien Vidéosurveillance / CCTV'],

            // ── Services & Divers ──────────────────────────────────────────
            ['nom' => 'Secrétaire / Assistant(e) Administratif(ve)'],
            ['nom' => 'Standardiste / Hôtesse d\'Accueil'],
            ['nom' => 'Traducteur / Interprète'],
            ['nom' => 'Agent de Call Center / Téléconseiller'],
            ['nom' => 'Agent de Nettoyage / Chef d\'Équipe Propreté'],
            ['nom' => 'Responsable Service Client'],
            ['nom' => 'Entrepreneur / Dirigeant de Startup'],
        ];

        foreach ($metiers as $data) {
            Metier::firstOrCreate(
                ['slug' => Str::slug($data['nom'])],
                [
                    'nom'         => $data['nom'],
                    'description' => $data['description'] ?? null,
                ]
            );
        }
    }
}
