<?php

namespace Database\Seeders;

use App\Models\Pays;
use Illuminate\Database\Seeder;

class PaysSeeder extends Seeder
{
    public function run(): void
    {
        $pays = [
            // Afrique de l'Ouest — priorité haute (1–20)
            ['nom' => 'Bénin',            'code' => 'BJ', 'ordre' => 1],
            ['nom' => 'Togo',             'code' => 'TG', 'ordre' => 2],
            ['nom' => 'Côte d\'Ivoire',   'code' => 'CI', 'ordre' => 3],
            ['nom' => 'Sénégal',          'code' => 'SN', 'ordre' => 4],
            ['nom' => 'Cameroun',         'code' => 'CM', 'ordre' => 5],
            ['nom' => 'Mali',             'code' => 'ML', 'ordre' => 6],
            ['nom' => 'Burkina Faso',     'code' => 'BF', 'ordre' => 7],
            ['nom' => 'Niger',            'code' => 'NE', 'ordre' => 8],
            ['nom' => 'Guinée',           'code' => 'GN', 'ordre' => 9],
            ['nom' => 'Ghana',            'code' => 'GH', 'ordre' => 10],
            ['nom' => 'Nigeria',          'code' => 'NG', 'ordre' => 11],
            // Afrique centrale
            ['nom' => 'Congo',            'code' => 'CG', 'ordre' => 20],
            ['nom' => 'RD Congo',         'code' => 'CD', 'ordre' => 21],
            ['nom' => 'Gabon',            'code' => 'GA', 'ordre' => 22],
            // Afrique de l'Est & Sud
            ['nom' => 'Madagascar',       'code' => 'MG', 'ordre' => 30],
            ['nom' => 'Rwanda',           'code' => 'RW', 'ordre' => 31],
            ['nom' => 'Kenya',            'code' => 'KE', 'ordre' => 32],
            // Europe / Diaspora
            ['nom' => 'France',           'code' => 'FR', 'ordre' => 50],
            ['nom' => 'Belgique',         'code' => 'BE', 'ordre' => 51],
            ['nom' => 'Canada',           'code' => 'CA', 'ordre' => 52],
            // Catch-all
            ['nom' => 'Autre',            'code' => null, 'ordre' => 99],
        ];

        foreach ($pays as $p) {
            Pays::firstOrCreate(['nom' => $p['nom']], [
                'code'  => $p['code'],
                'ordre' => $p['ordre'],
                'actif' => true,
            ]);
        }
    }
}
