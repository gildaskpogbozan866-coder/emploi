<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServicesSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            // ── Services existants (mis à jour) ────────────────────
            [
                'nom'         => 'CV Professionnel',
                'slug'        => 'cv-professionnel',
                'description' => 'Nos experts rédigent pour vous un CV percutant, optimisé pour décrocher des entretiens.',
                'details'     => "Analyse complète de votre profil et de vos objectifs\nCV structuré et optimisé pour passer les filtres ATS\nLivraison en Word & PDF, prêt à l'emploi\n1 révision gratuite incluse",
                'prix'        => 2500,
                'delai'       => '30min à 1h',
                'type'        => 'redaction',
                'devise'      => 'XOF',
            ],
            [
                'nom'         => 'Profil LinkedIn Optimisé',
                'slug'        => 'linkedin-optimise',
                'description' => 'Transformez votre profil LinkedIn en aimant à recruteurs. Rédaction complète, mots-clés SEO et photo de profil conseillée.',
                'details'     => "Audit complet de votre profil actuel\nRédaction du titre accrocheur et du résumé\nOptimisation de chaque section avec mots-clés recruteurs\nConseils sur la photo de profil et la bannière\nLivraison du profil optimisé sous 24h",
                'prix'        => 3500,
                'delai'       => '24h',
                'type'        => 'redaction',
                'devise'      => 'XOF',
            ],
            [
                'nom'         => 'Coaching Entretien',
                'slug'        => 'coaching-entretien',
                'description' => 'Session de coaching personnalisée pour vous préparer à décrocher le poste. Simulation d\'entretien et plan d\'amélioration détaillé.',
                'details'     => "Session vidéo d'1 heure avec un expert RH\nSimulation d'entretien réaliste et immersive\nFeedback détaillé sur votre posture et vos réponses\nPlan d'amélioration personnalisé\nConseils sur la négociation salariale",
                'prix'        => 5000,
                'delai'       => 'Sur rendez-vous',
                'type'        => 'coaching',
                'devise'      => 'XOF',
            ],

            // ── Nouveaux services digitaux ──────────────────────────
            [
                'nom'         => 'Création de sites web',
                'slug'        => 'creation-sites-web',
                'description' => 'Votre site web professionnel conçu de A à Z : e-commerce, vitrine ou plateforme. Design moderne, rapide et optimisé pour convertir vos visiteurs en clients.',
                'details'     => "Design moderne et responsive (mobile, tablette, ordinateur)\nE-commerce, vitrine ou plateforme professionnelle\nOptimisation SEO de base incluse\nFormulaire de contact et intégrations réseaux sociaux\nFormation à l'utilisation de votre site\n1 mois de support technique offert après livraison",
                'prix'        => 0,
                'delai'       => 'Sur devis',
                'type'        => 'digital',
                'devise'      => 'XOF',
            ],
            [
                'nom'         => 'Gestion des réseaux sociaux',
                'slug'        => 'gestion-reseaux-sociaux',
                'description' => 'Confiez-nous vos pages Facebook, Instagram et LinkedIn. Calendrier éditorial, création de contenus et engagement communautaire : on s\'occupe de tout.',
                'details'     => "Audit et stratégie personnalisée de vos réseaux sociaux\nCréation du calendrier éditorial mensuel\nRédaction et design des publications (posts, stories, reels)\nGestion des commentaires et messages\nRapport de performance mensuel détaillé\nPublicités sponsorisées en option",
                'prix'        => 0,
                'delai'       => 'Sur devis',
                'type'        => 'digital',
                'devise'      => 'XOF',
            ],
            [
                'nom'         => 'Marketing digital et publicité',
                'slug'        => 'marketing-digital',
                'description' => 'Facebook Ads, Instagram Ads, Google Ads : nous créons et gérons vos campagnes pour maximiser votre visibilité, attirer plus de clients et booster vos ventes.',
                'details'     => "Audit de votre présence digitale et de la concurrence\nCréation des visuels et textes publicitaires\nCiblage précis de votre audience idéale\nGestion et optimisation des campagnes en temps réel\nRapport de résultats hebdomadaire\nConseils stratégiques pour maximiser le retour sur investissement",
                'prix'        => 0,
                'delai'       => 'Sur devis',
                'type'        => 'digital',
                'devise'      => 'XOF',
            ],
            [
                'nom'         => 'Référencement Google (SEO)',
                'slug'        => 'referencement-seo',
                'description' => 'Apparaissez en première page de Google ! Audit SEO, optimisation technique et rédaction de contenu : nous améliorons votre visibilité durablement, sans payer par clic.',
                'details'     => "Audit SEO complet de votre site web\nOptimisation technique (vitesse, structure, balises)\nRecherche des mots-clés stratégiques de votre secteur\nRédaction de contenus optimisés pour Google\nCréation de liens entrants (backlinks)\nSuivi mensuel du positionnement sur Google",
                'prix'        => 0,
                'delai'       => 'Sur devis',
                'type'        => 'digital',
                'devise'      => 'XOF',
            ],
            [
                'nom'         => "Développement d'applications web",
                'slug'        => 'developpement-applications',
                'description' => 'Applications web sur mesure, dashboards, CRM, plateformes métier et systèmes de gestion. Des solutions techniques parfaitement adaptées à vos besoins spécifiques.',
                'details'     => "Analyse et conception fonctionnelle de votre projet\nDéveloppement sur mesure avec les dernières technologies\nInterface intuitive et design soigné\nBase de données sécurisée et performante\nTests et recette qualité rigoureux\nFormation et documentation complète livrée",
                'prix'        => 0,
                'delai'       => 'Sur devis',
                'type'        => 'digital',
                'devise'      => 'XOF',
            ],
            [
                'nom'         => 'Formation en informatique',
                'slug'        => 'formation-informatique',
                'description' => 'Maîtrisez les outils essentiels du numérique : Word, Excel, PowerPoint, Internet et plus encore. Cours individuels ou en groupe, adaptés à votre niveau.',
                'details'     => "Formation sur Word, Excel, PowerPoint et Internet\nNiveau débutant, intermédiaire ou avancé\nCours individuels (à votre rythme) ou en groupe\nSupports de cours fournis et exercices pratiques\nAttestation de formation remise à la fin\nSuivi post-formation pendant 1 mois",
                'prix'        => 0,
                'delai'       => 'Sur devis',
                'type'        => 'formation',
                'devise'      => 'XOF',
            ],
            [
                'nom'         => 'Création de CV et documents administratifs',
                'slug'        => 'creation-cv-documents',
                'description' => 'CV professionnel ATS-friendly, profil LinkedIn soigné et tous vos documents administratifs. Livrés en 30min à 1h.',
                'details'     => "CV professionnel structuré et optimisé ATS\nOptimisation du profil LinkedIn\nDemandes de stage, lettres de recommandation\nDocuments administratifs divers\nLivraison en Word & PDF, 1 révision gratuite",
                'prix'        => 2500,
                'delai'       => '30min à 1h',
                'type'        => 'redaction',
                'devise'      => 'XOF',
            ],
            [
                'nom'         => 'Accompagnement digital des entreprises',
                'slug'        => 'accompagnement-digital',
                'description' => 'Stratégie digitale, présence en ligne, outils et formation : nous accompagnons les entreprises et entrepreneurs dans leur transformation numérique complète.',
                'details'     => "Audit digital complet de votre entreprise\nÉlaboration de la stratégie digitale sur mesure\nMise en place des outils et processus numériques\nFormation de vos équipes aux outils digitaux\nSuivi mensuel et ajustement de la stratégie\nConseils continus de notre équipe d'experts",
                'prix'        => 0,
                'delai'       => 'Sur devis',
                'type'        => 'consulting',
                'devise'      => 'XOF',
            ],
        ];

        foreach ($services as $s) {
            Service::updateOrCreate(
                ['slug' => $s['slug']],
                array_merge($s, ['actif' => true])
            );
        }

        $this->command->info('✅ ' . count($services) . ' services insérés/mis à jour.');
    }
}
