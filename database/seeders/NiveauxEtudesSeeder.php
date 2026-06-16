<?php

namespace Database\Seeders;

use App\Models\NiveauEtude;
use Illuminate\Database\Seeder;

class NiveauxEtudesSeeder extends Seeder
{
    public function run(): void
    {
        $niveaux = [
            ['code' => 'AUCUN',      'libelle' => 'Aucun diplôme exigé',               'ordre' => 0],
            ['code' => 'PRIMAIRE',   'libelle' => 'Niveau primaire (CEP)',              'ordre' => 10],
            ['code' => 'SECONDAIRE', 'libelle' => 'Niveau secondaire (collège)',        'ordre' => 20],
            ['code' => 'BEPC',       'libelle' => 'BEPC / Brevet des collèges',         'ordre' => 25],
            ['code' => 'BAC',        'libelle' => 'Baccalauréat',                       'ordre' => 30],
            ['code' => 'BAC+2',      'libelle' => 'Bac+2 (BTS, DUT, DEUG, HND)',        'ordre' => 40],
            ['code' => 'BAC+3',      'libelle' => 'Bac+3 (Licence, Bachelor)',           'ordre' => 50],
            ['code' => 'BAC+4',      'libelle' => 'Bac+4 (Maîtrise, Master 1)',          'ordre' => 55],
            ['code' => 'BAC+5',      'libelle' => 'Bac+5 (Master 2, MBA, Ingénieur)',    'ordre' => 60],
            ['code' => 'DOCTORAT',   'libelle' => 'Doctorat / PhD',                      'ordre' => 70],
        ];

        // Update existing records first to free up ordre slots before inserting new ones
        $existingCodes = NiveauEtude::pluck('code')->toArray();
        foreach ($niveaux as $niveau) {
            if (in_array($niveau['code'], $existingCodes)) {
                NiveauEtude::where('code', $niveau['code'])->update([
                    'libelle' => $niveau['libelle'],
                    'ordre'   => $niveau['ordre'],
                ]);
            }
        }
        foreach ($niveaux as $niveau) {
            NiveauEtude::firstOrCreate(
                ['code' => $niveau['code']],
                ['libelle' => $niveau['libelle'], 'ordre' => $niveau['ordre']]
            );
        }
    }
}
