<?php

namespace Database\Seeders;

use App\Models\TypeContrat;
use Illuminate\Database\Seeder;

class TypeContratsSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['code' => 'CDI',          'libelle' => 'CDI — Contrat à Durée Indéterminée'],
            ['code' => 'CDD',          'libelle' => 'CDD — Contrat à Durée Déterminée'],
            ['code' => 'STAGE',        'libelle' => 'Stage'],
            ['code' => 'ALTERNANCE',   'libelle' => 'Alternance / Apprentissage'],
            ['code' => 'FREELANCE',    'libelle' => 'Freelance / Mission'],
            ['code' => 'INTERIM',      'libelle' => 'Intérim'],
            ['code' => 'TEMPS_PARTIEL','libelle' => 'Temps partiel'],
            ['code' => 'BOURSE',       'libelle' => 'Bourse / Formation rémunérée'],
            ['code' => 'CONSULTING',   'libelle' => 'Consulting'],
            ['code' => 'VIE',          'libelle' => 'VIE — Volontariat International en Entreprise'],
        ];

        foreach ($types as $type) {
            TypeContrat::firstOrCreate(
                ['code' => $type['code']],
                ['libelle' => $type['libelle']]
            );
        }
    }
}
