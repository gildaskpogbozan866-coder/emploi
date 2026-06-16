<?php

namespace Database\Seeders;

use App\Models\Disponibilite;
use Illuminate\Database\Seeder;

class DisponibilitesSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['code' => 'en_recherche', 'libelle' => 'En recherche active',    'couleur' => '#4ade80', 'ordre' => 1],
            ['code' => 'ouvert',       'libelle' => 'Ouvert aux opportunités', 'couleur' => '#facc15', 'ordre' => 2],
            ['code' => 'indisponible', 'libelle' => 'Non disponible',          'couleur' => '#f87171', 'ordre' => 3],
        ];

        foreach ($items as $item) {
            Disponibilite::firstOrCreate(['code' => $item['code']], [
                'libelle' => $item['libelle'],
                'couleur' => $item['couleur'],
                'ordre'   => $item['ordre'],
                'actif'   => true,
            ]);
        }
    }
}
