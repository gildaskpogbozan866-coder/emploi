<?php

namespace Database\Seeders;

use App\Models\NiveauExperience;
use Illuminate\Database\Seeder;

class NiveauxExperienceSeeder extends Seeder
{
    public function run(): void
    {
        $niveaux = [
            ['code' => 'SANS',       'libelle' => 'Sans expérience',             'ordre' => 10],
            ['code' => 'MOINS_6M',   'libelle' => 'Moins de 6 mois',             'ordre' => 15],
            ['code' => 'JUNIOR',     'libelle' => 'Junior (6 mois à 1 an)',       'ordre' => 20],
            ['code' => '1_2_ANS',    'libelle' => '1 à 2 ans',                   'ordre' => 30],
            ['code' => '2_3_ANS',    'libelle' => '2 à 3 ans',                   'ordre' => 35],
            ['code' => '3_5_ANS',    'libelle' => '3 à 5 ans',                   'ordre' => 40],
            ['code' => '5_10_ANS',   'libelle' => '5 à 10 ans',                  'ordre' => 50],
            ['code' => 'PLUS_10',    'libelle' => 'Plus de 10 ans',               'ordre' => 60],
        ];

        // Update existing records first to free up ordre slots before inserting new ones
        $existingCodes = NiveauExperience::pluck('code')->toArray();
        foreach ($niveaux as $niveau) {
            if (in_array($niveau['code'], $existingCodes)) {
                NiveauExperience::where('code', $niveau['code'])->update([
                    'libelle' => $niveau['libelle'],
                    'ordre'   => $niveau['ordre'],
                ]);
            }
        }
        foreach ($niveaux as $niveau) {
            NiveauExperience::firstOrCreate(
                ['code' => $niveau['code']],
                ['libelle' => $niveau['libelle'], 'ordre' => $niveau['ordre']]
            );
        }
    }
}
