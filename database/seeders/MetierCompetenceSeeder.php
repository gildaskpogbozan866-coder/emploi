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
            'comptable' => [
                'excel', 'google-sheets', 'comptabilite-generale', 'fiscalite',
                'audit', 'sage', 'analyse-financiere', 'tresorerie',
                'controle-de-gestion', 'reporting',
            ],
            'commercial' => [
                'negociation', 'communication', 'excel', 'reporting',
                'social-media-marketing', 'google-analytics', 'presentation',
                'email-marketing',
            ],
            'chef-de-projet' => [
                'scrum', 'agile', 'kanban', 'gestion-de-projet', 'pmp',
                'leadership', 'communication', 'reporting',
                'excel', 'jira', 'trello',
            ],
            'data-analyst' => [
                'python', 'r', 'excel', 'power-bi', 'tableau',
                'mysql', 'postgresql', 'machine-learning', 'data-analysis',
                'statistiques', 'pandas', 'numpy', 'google-sheets',
                'google-analytics',
            ],
            'administrateur-systeme' => [
                'linux', 'bash', 'windows-server', 'docker', 'kubernetes',
                'cybersecurite', 'reseaux-tcp-ip', 'cisco', 'active-directory',
                'nginx', 'apache', 'aws', 'azure', 'vpn', 'pare-feu', 'ci-cd',
            ],
            'marketing-digital' => [
                'seo', 'google-ads', 'meta-ads', 'social-media-marketing',
                'content-marketing', 'email-marketing', 'google-analytics',
                'copywriting', 'canva', 'photoshop', 'illustrator',
            ],
            'rh' => [
                'recrutement', 'formation-professionnelle', 'gestion-de-la-paie',
                'droit-du-travail', 'sirh', 'communication', 'excel',
                'leadership', 'reporting',
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
