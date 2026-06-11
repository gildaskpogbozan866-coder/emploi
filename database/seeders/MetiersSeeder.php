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
            ['nom' => 'Développeur Full Stack',  'description' => 'Conception et développement d\'applications web côté frontend et backend.'],
            ['nom' => 'Développeur Backend',     'description' => 'Développement de la logique serveur, des API et des bases de données.'],
            ['nom' => 'Développeur Frontend',    'description' => 'Création d\'interfaces utilisateur web interactives et responsives.'],
            ['nom' => 'Comptable',               'description' => 'Gestion de la comptabilité générale, fiscalité et reporting financier.'],
            ['nom' => 'Commercial',              'description' => 'Développement commercial, prospection et gestion de la relation client.'],
            ['nom' => 'Chef de Projet',          'description' => 'Planification, coordination et pilotage de projets pluridisciplinaires.'],
            ['nom' => 'Data Analyst',            'description' => 'Collecte, analyse et visualisation de données pour la prise de décision.'],
            ['nom' => 'Administrateur Système',  'description' => 'Administration des infrastructures serveurs, réseaux et systèmes d\'exploitation.'],
            ['nom' => 'Marketing Digital',       'description' => 'Stratégies de communication et acquisition digitale (SEO, SEA, Social Media).'],
            ['nom' => 'RH',                      'description' => 'Gestion des ressources humaines : recrutement, formation, paie et droit social.'],
        ];

        foreach ($metiers as $data) {
            Metier::firstOrCreate(
                ['slug' => Str::slug($data['nom'])],
                ['nom' => $data['nom'], 'description' => $data['description']]
            );
        }
    }
}
