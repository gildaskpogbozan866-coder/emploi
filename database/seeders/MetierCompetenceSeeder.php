<?php

namespace Database\Seeders;

use App\Models\Competence;
use App\Models\Metier;
use Illuminate\Database\Seeder;

class MetierCompetenceSeeder extends Seeder
{
    public function run(): void
    {
        $associations = [

            // ── Tech ────────────────────────────────────────────────────
            'developpeur-full-stack' => [
                'php', 'laravel', 'javascript', 'vue-js', 'react',
                'mysql', 'postgresql', 'git', 'docker', 'rest-api',
                'html', 'css', 'node-js', 'ci-cd',
            ],
            'developpeur-backend' => [
                'php', 'laravel', 'symfony', 'python', 'django',
                'mysql', 'postgresql', 'redis', 'git', 'docker',
                'node-js', 'rest-api', 'graphql', 'ci-cd',
            ],
            'developpeur-frontend' => [
                'javascript', 'typescript', 'vue-js', 'react', 'angular',
                'next-js', 'nuxt-js', 'html', 'css', 'bootstrap',
                'tailwind-css', 'git', 'jquery',
            ],
            'data-analyst' => [
                'python', 'r', 'excel', 'power-bi', 'tableau',
                'mysql', 'postgresql', 'machine-learning', 'data-analysis',
                'statistiques', 'pandas', 'numpy', 'google-sheets',
                'google-analytics',
            ],

            // ── Finance / Comptabilité ───────────────────────────────────
            'comptable' => [
                'comptabilite-generale', 'comptabilite-syscohada',
                'comptabilite-analytique', 'comptabilite-publique',
                'fiscalite-declarations-fiscales', 'sage', 'excel',
                'google-sheets', 'tresorerie-cash-management',
                'gestion-budgetaire-budget-previsionnel',
                'ohada-droit-comptable-ohada', 'reporting',
                'finance-dentreprise', 'consolidation-des-comptes',
            ],
            'controleur-de-gestion' => [
                'controle-de-gestion', 'comptabilite-analytique',
                'gestion-budgetaire-budget-previsionnel', 'audit-interne',
                'modelisation-financiere-excel-avance', 'excel',
                'power-bi', 'reporting', 'ohada-droit-comptable-ohada',
                'finance-dentreprise', 'sage',
            ],
            'auditeur-interne' => [
                'audit-interne', 'audit-externe', 'controle-de-gestion',
                'comptabilite-generale', 'comptabilite-syscohada',
                'fiscalite-declarations-fiscales', 'ohada-droit-comptable-ohada',
                'reporting', 'excel', 'gestion-budgetaire-budget-previsionnel',
            ],
            'analyste-de-credit-gestionnaire-de-risques' => [
                'analyse-financiere', 'comptabilite-syscohada',
                'microfinance-gestion-de-portefeuille-credit',
                'ohada-droit-comptable-ohada', 'excel',
                'modelisation-financiere-excel-avance',
                'mobile-money-wave-orange-money-mtn',
                'plan-daffaires-business-plan', 'reporting',
            ],
            'agent-de-microfinance-charge-de-prets' => [
                'microfinance-gestion-de-portefeuille-credit',
                'mobile-money-wave-orange-money-mtn',
                'comptabilite-syscohada', 'ohada-droit-comptable-ohada',
                'excel', 'plan-daffaires-business-plan',
                'negociation-commerciale', 'communication-orale-et-ecrite',
            ],

            // ── Commerce / Vente ─────────────────────────────────────────
            'commercial-charge-de-developpement-commercial' => [
                'negociation-commerciale', 'prospection-commerciale',
                'vente-btob', 'vente-btoc', 'closing-signature',
                'gestion-de-portefeuille-clients',
                'crm-gestion-de-la-relation-client', 'fidelisation-client',
                'communication-orale-et-ecrite', 'excel', 'reporting',
            ],
            'agent-commercial-representant' => [
                'negociation-commerciale', 'prospection-commerciale',
                'vente-btoc', 'commerce-de-terrain',
                'distribution-mise-en-rayon', 'merchandising',
                'closing-signature', 'fidelisation-client',
                'mobile-money-wave-orange-money-mtn',
                'communication-orale-et-ecrite',
            ],

            // ── Marketing / Communication ────────────────────────────────
            'charge-de-marketing-digital' => [
                'seo-referencement-naturel', 'meta-ads-facebook-ads',
                'google-analytics-ga4', 'community-management',
                'content-marketing-strategie-de-contenu',
                'email-marketing-newsletter', 'tiktok-marketing',
                'linkedin-marketing', 'copywriting', 'canva',
                'social-media-marketing', 'redaction-web-blog',
            ],
            'community-manager' => [
                'community-management', 'social-media-marketing',
                'copywriting', 'canva', 'tiktok-marketing',
                'content-marketing-strategie-de-contenu',
                'redaction-web-blog', 'google-analytics-ga4',
                'email-marketing-newsletter', 'meta-ads-facebook-ads',
            ],
            'charge-de-communication' => [
                'communication-orale-et-ecrite', 'relations-presse-rp',
                'copywriting', 'canva', 'social-media-marketing',
                'redaction-web-blog', 'community-management',
                'content-marketing-strategie-de-contenu',
                'photoshop', 'indesign',
            ],

            // ── Ressources Humaines ──────────────────────────────────────
            'charge-de-recrutement-talent-acquisition' => [
                'recrutement-talent-acquisition',
                'gestion-de-la-paie-bulletin-de-salaire',
                'droit-du-travail', 'sirh-logiciels-rh', 'gpec-gepp',
                'onboarding-integration-des-collaborateurs',
                'evaluation-des-performances-entretiens-annuels',
                'formation-professionnelle', 'politique-de-remuneration',
                'communication-orale-et-ecrite', 'excel',
            ],
            'rh' => [
                'recrutement-talent-acquisition', 'formation-professionnelle',
                'gestion-de-la-paie-bulletin-de-salaire', 'droit-du-travail',
                'sirh-logiciels-rh', 'gpec-gepp',
                'onboarding-integration-des-collaborateurs',
                'evaluation-des-performances-entretiens-annuels',
                'management-dequipe', 'reporting', 'excel',
            ],

            // ── Droit ────────────────────────────────────────────────────
            'avocat' => [
                'droit-des-affaires', 'droit-ohada', 'droit-civil',
                'droit-penal', 'droit-fiscal', 'droit-du-travail',
                'conseil-juridique-compliance',
                'redaction-de-contrats-actes-juridiques',
                'contentieux-plaidoirie', 'mediation-resolution-de-conflits',
            ],

            // ── Gestion de Projet / ONG ──────────────────────────────────
            'chef-de-projet' => [
                'scrum', 'agile', 'kanban', 'gestion-de-projet', 'pmp',
                'leadership', 'communication-orale-et-ecrite', 'reporting',
                'excel', 'jira', 'trello',
                'gestion-budgetaire-budget-previsionnel',
            ],
            'charge-de-projet-ong-coordinateur-de-programme' => [
                'gestion-de-projet', 'reporting',
                'gestion-budgetaire-budget-previsionnel',
                'passation-de-marches-publics-armp',
                'redaction-administrative-courriers-officiels',
                'communication-orale-et-ecrite', 'excel', 'word',
                'management-dequipe',
            ],
            'charge-de-suivi-evaluation-me' => [
                'gestion-de-projet', 'reporting', 'excel', 'google-sheets',
                'power-bi', 'gestion-budgetaire-budget-previsionnel',
                'redaction-administrative-courriers-officiels',
                'communication-orale-et-ecrite',
            ],

            // ── Santé ────────────────────────────────────────────────────
            'infirmier-infirmiere' => [
                'soins-infirmiers-nursing', 'sage-femme-maieutique',
                'medecine-communautaire-agents-de-sante',
                'urgences-reanimation-smur',
                'hygiene-assainissement-wash',
                'nutrition-dietetique-alimentation',
                'biologie-medicale-analyses-de-laboratoire',
                'pediatrie-neonatologie',
                'gynecologie-obstetrique-maternite',
            ],
            'aide-soignant-agent-de-sante-communautaire' => [
                'soins-infirmiers-nursing',
                'medecine-communautaire-agents-de-sante',
                'hygiene-assainissement-wash',
                'nutrition-dietetique-alimentation',
                'urgences-reanimation-smur',
            ],

            // ── BTP / Architecture ────────────────────────────────────────
            'architecte' => [
                'dessin-technique-autocad', 'revit-bim', 'sketchup',
                'metres-devis-estimation-de-travaux',
                'conduite-de-chantier-suivi-de-travaux',
                'beton-arme-calcul-de-structures',
                'topographie-geometre',
            ],
            'conducteur-de-travaux-ingenieur-chantier' => [
                'conduite-de-chantier-suivi-de-travaux',
                'metres-devis-estimation-de-travaux',
                'beton-arme-calcul-de-structures',
                'topographie-geometre',
                'electricite-batiment-cablage',
                'securite-chantier-hse-btp',
                'dessin-technique-autocad',
                'management-dequipe',
            ],
            'chef-de-chantier-maitre-doeuvre' => [
                'conduite-de-chantier-suivi-de-travaux',
                'maconnerie-ferraillage',
                'beton-arme-calcul-de-structures',
                'metres-devis-estimation-de-travaux',
                'plomberie-sanitaire',
                'electricite-batiment-cablage',
                'peinture-en-batiment',
                'securite-chantier-hse-btp',
                'management-dequipe',
            ],

            // ── Transport / Logistique ────────────────────────────────────
            'chauffeur-poids-lourd-transporteur' => [
                'conduite-poids-lourds-transport-de-marchandises',
                'gestion-de-stock-inventaire',
                'gestion-dentrepot-wms',
                'gestionnaire-de-flotte',
            ],

            // ── Restauration ──────────────────────────────────────────────
            'chef-cuisinier-cuisinier' => [
                'cuisine-arts-culinaires-gastronomie',
                'patisserie-boulangerie',
                'hygiene-assainissement-wash',
                'haccp-securite-alimentaire',
                'service-en-salle-restauration',
                'management-dequipe',
            ],

            // ── Sécurité ──────────────────────────────────────────────────
            'agent-de-securite-vigile' => [
                'hse-hygiene-securite-environnement',
                'iso-45001-securite-au-travail-ohsas',
                'communication-orale-et-ecrite',
                'management-dequipe',
            ],

            // ── Secrétariat / Administration ──────────────────────────────
            'secretaire-de-direction-assistant-de-direction' => [
                'redaction-administrative-courriers-officiels',
                'communication-orale-et-ecrite',
                'excel', 'word',
                'management-dequipe',
                'gestion-budgetaire-budget-previsionnel',
            ],
        ];

        foreach ($associations as $metierSlug => $competenceSlugs) {
            $metier = Metier::where('slug', $metierSlug)->first();
            if (!$metier) {
                continue;
            }

            $competenceIds = Competence::whereIn('slug', $competenceSlugs)->pluck('id');
            $metier->competences()->syncWithoutDetaching($competenceIds);
        }
    }
}
