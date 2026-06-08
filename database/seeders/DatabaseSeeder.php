<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\User;
use App\Models\Offre;
use App\Models\CV;
use App\Models\Service;
use App\Models\Article;
use App\Models\TalentProfil;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── ÉTAPE 1 : Rôles & Permissions (Spatie) ──────────
        $this->call(RolesAndPermissionsSeeder::class);

        // ── ÉTAPE 2 : Utilisateurs ────────────────────────────
        $admin = User::create([
            'prenom'            => 'Super',
            'nom'               => 'Admin',
            'email'             => 'admin@emploibougebenin.com',
            'password'          => Hash::make('Admin@2026'),
            'role'              => Role::ADMIN,
            'pays'              => 'Bénin',
            'actif'             => true,
            'email_verified_at' => now(),
        ]);
        $admin->assignRole(Role::ADMIN);

        $recruteur1 = User::create([
            'prenom'            => 'Kokou',
            'nom'               => 'Ahonou',
            'email'             => 'recruteur@techbenin.com',
            'password'          => Hash::make('password'),
            'role'              => Role::RECRUTEUR,
            'entreprise'        => 'TechBénin SARL',
            'pays'              => 'Bénin',
            'actif'             => true,
            'email_verified_at' => now(),
        ]);
        $recruteur1->assignRole(Role::RECRUTEUR);

        $recruteur2 = User::create([
            'prenom'            => 'Fatou',
            'nom'               => 'Diallo',
            'email'             => 'rh@senservices.sn',
            'password'          => Hash::make('password'),
            'role'              => Role::RECRUTEUR,
            'entreprise'        => 'SenServices SA',
            'pays'              => 'Sénégal',
            'actif'             => true,
            'email_verified_at' => now(),
        ]);
        $recruteur2->assignRole(Role::RECRUTEUR);

        $candidat1 = User::create([
            'prenom'            => 'Jean-Baptiste',
            'nom'               => 'Kpossou',
            'email'             => 'jb.kpossou@gmail.com',
            'password'          => Hash::make('password'),
            'role'              => Role::CANDIDAT,
            'pays'              => 'Bénin',
            'actif'             => true,
            'email_verified_at' => now(),
        ]);
        $candidat1->assignRole(Role::CANDIDAT);

        $candidat2 = User::create([
            'prenom'            => 'Aïssatou',
            'nom'               => 'Traoré',
            'email'             => 'aissatou.traore@gmail.com',
            'password'          => Hash::make('password'),
            'role'              => Role::CANDIDAT,
            'pays'              => "Côte d'Ivoire",
            'actif'             => true,
            'email_verified_at' => now(),
        ]);
        $candidat2->assignRole(Role::CANDIDAT);

        $talent1 = User::create([
            'prenom'            => 'Moussa',
            'nom'               => 'Diarra',
            'email'             => 'moussa.diarra@gmail.com',
            'password'          => Hash::make('password'),
            'role'              => Role::TALENT,
            'metier'            => 'Développeur Web Full Stack',
            'pays'              => 'Mali',
            'actif'             => true,
            'email_verified_at' => now(),
        ]);
        $talent1->assignRole(Role::TALENT);

        // ── ÉTAPE 3 : Données métier ─────────────────────────
        TalentProfil::create([
            'user_id'     => $talent1->id,
            'metier'      => 'Développeur Web Full Stack',
            'specialite'  => 'PHP / Laravel / Vue.js',
            'pays'        => 'Mali',
            'ville'       => 'Bamako',
            'bio'         => "Développeur passionné avec 5 ans d'expérience. Spécialisé Laravel et Vue.js.",
            'competences' => 'PHP, Laravel, Vue.js, MySQL, Git, REST API',
            'experience'  => '5 ans',
            'plan'        => 'gratuit',
            'visible'     => true,
        ]);

        CV::create([
            'candidat_id' => $candidat1->id,
            'titre_poste' => 'Développeur Web Junior',
            'pays'        => 'Bénin',
            'ville'       => 'Cotonou',
            'competences' => 'HTML, CSS, JavaScript, PHP',
            'experience'  => '1 an',
            'formation'   => 'Licence Informatique - UAC Bénin 2024',
            'plan'        => 'gratuit',
            'visible'     => true,
        ]);

        CV::create([
            'candidat_id' => $candidat2->id,
            'titre_poste' => 'Comptable confirmée',
            'pays'        => "Côte d'Ivoire",
            'ville'       => 'Abidjan',
            'competences' => 'Comptabilité, Excel, Sage, Analyse financière',
            'experience'  => '3 ans',
            'formation'   => "Master CCA - Université d'Abidjan 2022",
            'plan'        => 'gratuit',
            'visible'     => true,
        ]);

        $offresData = [
            ['titre' => 'Développeur Web Full Stack',    'type' => 'CDI',   'secteur' => 'Informatique', 'salaire' => '150 000 - 250 000 FCFA'],
            ['titre' => 'Chargé(e) de Marketing Digital','type' => 'CDD',   'secteur' => 'Marketing',    'salaire' => '120 000 FCFA'],
            ['titre' => 'Stage en Comptabilité',         'type' => 'Stage', 'secteur' => 'Finance',      'salaire' => '50 000 FCFA/mois'],
            ['titre' => 'Responsable Ressources Humaines','type'=> 'CDI',   'secteur' => 'RH',           'salaire' => '200 000 - 300 000 FCFA'],
            ['titre' => 'Bourse de formation Data Science','type'=> 'Bourse','secteur'=> 'Formation',    'salaire' => 'Gratuit + Allocation'],
            ['titre' => 'Commercial terrain B2B',        'type' => 'CDI',   'secteur' => 'Ventes',       'salaire' => 'Fixe + Commissions'],
        ];

        foreach ($offresData as $i => $data) {
            Offre::create([
                'recruteur_id' => ($i % 2 === 0) ? $recruteur1->id : $recruteur2->id,
                'titre'        => $data['titre'],
                'entreprise'   => ($i % 2 === 0) ? 'TechBénin SARL' : 'SenServices SA',
                'localisation' => ($i % 2 === 0) ? 'Cotonou, Bénin' : 'Dakar, Sénégal',
                'type'         => $data['type'],
                'secteur'      => $data['secteur'],
                'salaire'      => $data['salaire'],
                'description'  => "Nous recherchons un(e) {$data['titre']} motivé(e) pour rejoindre notre équipe. Venez contribuer à nos projets dans un environnement stimulant.",
                'statut'       => 'active',
                'date_limite'  => now()->addDays(rand(15, 60)),
            ]);
        }

        $servicesData = [
            ['nom' => 'CV Professionnel + Lettre de Motivation', 'slug' => 'cv-professionnel',
             'description' => 'Nos experts rédigent pour vous un CV percutant et une lettre de motivation convaincante.',
             'details' => 'Analyse du profil · CV ATS-optimisé · Lettre personnalisée · Livraison Word + PDF sous 48h · 1 révision gratuite',
             'prix' => 2500, 'delai' => '48h', 'type' => 'redaction'],
            ['nom' => 'Profil LinkedIn Optimisé', 'slug' => 'linkedin-optimise',
             'description' => 'Optimisez votre présence LinkedIn pour attirer les recruteurs.',
             'details' => 'Audit du profil · Rédaction complète · Mots-clés SEO LinkedIn · Livraison sous 72h',
             'prix' => 3500, 'delai' => '72h', 'type' => 'redaction'],
            ['nom' => 'Coaching Entretien', 'slug' => 'coaching-entretien',
             'description' => 'Préparez-vous à décrocher le poste avec un coaching personnalisé.',
             'details' => '1h session vidéo · Simulation entretien · Feedback détaillé · Plan d\'amélioration',
             'prix' => 5000, 'delai' => 'Sur rendez-vous', 'type' => 'coaching'],
        ];

        foreach ($servicesData as $s) {
            Service::create($s + ['actif' => true]);
        }

        $articlesData = [
            ['titre' => 'Comment rédiger un CV qui attire les recruteurs en 2026',
             'slug'  => 'rediger-cv-2026',
             'extrait' => 'Découvrez les erreurs à éviter et les astuces pour créer un CV percutant adapté au marché africain.',
             'categorie' => 'Conseils CV', 'temps_lecture' => 5],
            ['titre' => 'Les 10 questions les plus fréquentes en entretien',
             'slug'  => 'questions-entretien-frequentes',
             'extrait' => 'Préparez-vous avec nos réponses types et stratégies pour convaincre chaque recruteur.',
             'categorie' => 'Entretien', 'temps_lecture' => 7],
            ['titre' => "Trouver un emploi à distance depuis l'Afrique : guide complet",
             'slug'  => 'emploi-remote-afrique',
             'extrait' => "Plateformes, profils, tarifs — tout ce qu'il faut pour décrocher votre premier job remote.",
             'categorie' => 'Remote', 'temps_lecture' => 6],
        ];

        foreach ($articlesData as $a) {
            Article::create($a + [
                'auteur_id' => $admin->id,
                'contenu'   => '<p>Article complet à rédiger...</p>',
                'statut'    => 'publie',
                'publie_le' => now(),
            ]);
        }

        $this->command->info('✅ Données de démonstration insérées.');
    }
}
