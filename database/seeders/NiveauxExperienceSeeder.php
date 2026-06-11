<?php

namespace Database\Seeders;

use App\Models\NiveauExperience;
use Illuminate\Database\Seeder;

class NiveauxExperienceSeeder extends Seeder
{
    public function run(): void
    {
        $niveaux = [
            ['code' => 'SANS',      'libelle' => 'Sans expérience',      'ordre' => 1],
            ['code' => 'JUNIOR',    'libelle' => 'Junior (moins d\'1 an)', 'ordre' => 2],
            ['code' => '1_2_ANS',   'libelle' => '1 à 2 ans',            'ordre' => 3],
            ['code' => '3_5_ANS',   'libelle' => '3 à 5 ans',            'ordre' => 4],
            ['code' => '5_10_ANS',  'libelle' => '5 à 10 ans',           'ordre' => 5],
            ['code' => 'PLUS_10',   'libelle' => 'Plus de 10 ans',        'ordre' => 6],
        ];

        foreach ($niveaux as $niveau) {
            NiveauExperience::firstOrCreate(
                ['code' => $niveau['code']],
                ['libelle' => $niveau['libelle'], 'ordre' => $niveau['ordre']]
            );
        }
    }
}
