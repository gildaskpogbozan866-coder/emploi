<?php

namespace Database\Seeders;

use App\Models\Competence;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CompetencesSeeder extends Seeder
{
    public function run(): void
    {
        $competences = [
            // ── Développement Backend ──────────────────────────────────────
            'PHP', 'Laravel', 'Symfony', 'CodeIgniter',
            'Python', 'Django', 'Flask', 'FastAPI',
            'Java', 'Spring Boot',
            'Ruby on Rails',
            'C#', 'Node.js',

            // ── Développement Frontend ─────────────────────────────────────
            'JavaScript', 'TypeScript',
            'Vue.js', 'React', 'Angular', 'Next.js', 'Nuxt.js',
            'HTML', 'CSS', 'Bootstrap', 'Tailwind CSS', 'jQuery',

            // ── Mobile ─────────────────────────────────────────────────────
            'Flutter', 'React Native', 'Swift', 'Kotlin',

            // ── Bases de données ───────────────────────────────────────────
            'MySQL', 'PostgreSQL', 'MongoDB', 'Redis', 'SQLite', 'Oracle',

            // ── API & Intégrations ─────────────────────────────────────────
            'REST API', 'GraphQL',

            // ── DevOps & Cloud ─────────────────────────────────────────────
            'Git', 'Docker', 'Kubernetes', 'CI/CD',
            'AWS', 'Azure', 'GCP', 'Firebase',
            'Nginx', 'Apache',

            // ── Administration Système ─────────────────────────────────────
            'Linux', 'Bash', 'Windows Server',
            'Active Directory', 'Cisco', 'Réseaux TCP/IP', 'VPN',
            'Cybersécurité', 'Pare-feu',

            // ── Data & Business Intelligence ───────────────────────────────
            'Excel', 'Google Sheets', 'Power BI', 'Tableau',
            'R', 'Machine Learning', 'Deep Learning',
            'TensorFlow', 'PyTorch',
            'Data Analysis', 'Statistiques', 'Pandas', 'NumPy',

            // ── Gestion de Projet ──────────────────────────────────────────
            'Scrum', 'Agile', 'Kanban',
            'Gestion de projet', 'PMP', 'PRINCE2',
            'Jira', 'Trello', 'Reporting',

            // ── Marketing Digital ──────────────────────────────────────────
            'SEO', 'Google Ads', 'Meta Ads',
            'Social Media Marketing', 'Content Marketing', 'Email Marketing',
            'Google Analytics', 'Copywriting',
            'Canva', 'Photoshop', 'Illustrator',

            // ── Finance & Comptabilité ─────────────────────────────────────
            'Comptabilité générale', 'Fiscalité', 'Audit', 'Sage',
            'Contrôle de gestion', "Finance d'entreprise",
            'Analyse financière', 'Trésorerie',

            // ── Ressources Humaines ────────────────────────────────────────
            'Recrutement', 'Formation professionnelle',
            'Gestion de la paie', 'Droit du travail', 'SIRH',

            // ── Soft Skills ────────────────────────────────────────────────
            'Leadership', "Management d'équipe",
            'Communication', 'Négociation', 'Présentation',
        ];

        foreach ($competences as $nom) {
            Competence::firstOrCreate(
                ['slug' => Str::slug($nom)],
                ['nom'  => $nom]
            );
        }
    }
}
