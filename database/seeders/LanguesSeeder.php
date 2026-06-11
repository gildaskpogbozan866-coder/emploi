<?php

namespace Database\Seeders;

use App\Models\Langue;
use Illuminate\Database\Seeder;

class LanguesSeeder extends Seeder
{
    public function run(): void
    {
        $langues = [
            // Langues officielles / internationales
            'Français',
            'Anglais',
            'Arabe',
            'Espagnol',
            'Portugais',
            'Allemand',
            'Italien',
            'Chinois (Mandarin)',
            'Russe',
            'Japonais',
            // Langues d'Afrique de l'Ouest
            'Fon',
            'Yoruba',
            'Dendi',
            'Bariba',
            'Mina (Gén)',
            'Haoussa',
            'Wolof',
            'Bambara',
            'Dioula',
            'Twi',
            'Éwé',
            'Fulfuldé',
            'Mooré',
        ];

        foreach ($langues as $nom) {
            Langue::firstOrCreate(['nom' => $nom]);
        }
    }
}
