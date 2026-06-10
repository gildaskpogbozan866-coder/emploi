<?php

namespace Database\Seeders;

use App\Models\NiveauEtude;
use Illuminate\Database\Seeder;

class NiveauxEtudesSeeder extends Seeder
{
    public function run(): void
    {
        $niveaux = [
            ['code' => 'PRIMAIRE',   'libelle' => 'Niveau primaire',            'ordre' => 1],
            ['code' => 'SECONDAIRE', 'libelle' => 'Niveau secondaire (collège)', 'ordre' => 2],
            ['code' => 'BAC',        'libelle' => 'Baccalauréat',               'ordre' => 3],
            ['code' => 'BAC+2',      'libelle' => 'Bac+2 (BTS, DUT, DEUG)',     'ordre' => 4],
            ['code' => 'BAC+3',      'libelle' => 'Bac+3 (Licence, Bachelor)',   'ordre' => 5],
            ['code' => 'BAC+5',      'libelle' => 'Bac+5 (Master, MBA, Ingénieur)', 'ordre' => 6],
            ['code' => 'DOCTORAT',   'libelle' => 'Doctorat (PhD)',              'ordre' => 7],
        ];

        foreach ($niveaux as $niveau) {
            NiveauEtude::firstOrCreate(
                ['code' => $niveau['code']],
                ['libelle' => $niveau['libelle'], 'ordre' => $niveau['ordre']]
            );
        }
    }
}
