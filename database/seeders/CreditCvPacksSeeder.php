<?php

namespace Database\Seeders;

use App\Models\CreditCvPack;
use Illuminate\Database\Seeder;

class CreditCvPacksSeeder extends Seeder
{
    public function run(): void
    {
        $packs = [
            ['label' => '5 crédits',  'credits' => 5,  'prix' => 5000,  'badge' => null,               'featured' => false, 'actif' => true, 'ordre' => 1],
            ['label' => '10 crédits', 'credits' => 10, 'prix' => 9000,  'badge' => null,               'featured' => false, 'actif' => true, 'ordre' => 2],
            ['label' => '25 crédits', 'credits' => 25, 'prix' => 20000, 'badge' => 'Populaire',        'featured' => false, 'actif' => true, 'ordre' => 3],
            ['label' => '50 crédits', 'credits' => 50, 'prix' => 35000, 'badge' => 'Meilleure valeur', 'featured' => true,  'actif' => true, 'ordre' => 4],
        ];

        foreach ($packs as $pack) {
            CreditCvPack::updateOrCreate(
                ['credits' => $pack['credits']],
                $pack
            );
        }
    }
}
