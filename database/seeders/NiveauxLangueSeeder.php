<?php

namespace Database\Seeders;

use App\Models\NiveauLangue;
use Illuminate\Database\Seeder;

class NiveauxLangueSeeder extends Seeder
{
    public function run(): void
    {
        $niveaux = [
            ['code' => 'A1',    'libelle' => 'Débutant',                'ordre' => 1],
            ['code' => 'A2',    'libelle' => 'Élémentaire',             'ordre' => 2],
            ['code' => 'B1',    'libelle' => 'Intermédiaire',           'ordre' => 3],
            ['code' => 'B2',    'libelle' => 'Intermédiaire supérieur', 'ordre' => 4],
            ['code' => 'C1',    'libelle' => 'Avancé',                  'ordre' => 5],
            ['code' => 'C2',    'libelle' => 'Maîtrise',                'ordre' => 6],
            ['code' => 'NATIF', 'libelle' => 'Langue natale',           'ordre' => 7],
        ];

        foreach ($niveaux as $niveau) {
            NiveauLangue::firstOrCreate(['code' => $niveau['code']], $niveau);
        }
    }
}
