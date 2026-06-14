<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Publicite;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PublicitesSeeder extends Seeder
{
    public function run(): void
    {
        // Annonceur de démo
        $annonceur = User::firstOrCreate(['email' => 'annonceur@demo.com'], [
            'prenom'            => 'Marc',
            'nom'               => 'Publicitaire',
            'password'          => Hash::make('password'),
            'role'              => Role::ANNONCEUR,
            'pays'              => 'Bénin',
            'actif'             => true,
            'email_verified_at' => now(),
        ]);
        $annonceur->syncRoles([Role::ANNONCEUR]);

        Storage::disk('public')->makeDirectory('publicites');

        $ads = [
            ['titre' => 'Boutique Mode Cotonou',       'lien' => 'https://example.com/boutique',  'bg' => [29, 78, 216],  'fg' => [255, 255, 255]],
            ['titre' => 'Formation Digital Marketing',  'lien' => 'https://example.com/formation', 'bg' => [22, 163, 74],  'fg' => [255, 255, 255]],
            ['titre' => 'Restaurant Le Palmier',        'lien' => null,                            'bg' => [217, 119, 6],  'fg' => [255, 255, 255]],
        ];

        foreach ($ads as $ad) {
            $filename = 'publicites/' . \Str::slug($ad['titre']) . '.png';

            if (! Storage::disk('public')->exists($filename)) {
                Storage::disk('public')->put($filename, $this->makePlaceholderPng($ad['titre'], $ad['bg'], $ad['fg']));
            }

            Publicite::firstOrCreate(
                ['titre' => $ad['titre'], 'user_id' => $annonceur->id],
                [
                    'image'      => $filename,
                    'lien'       => $ad['lien'],
                    'statut'     => 'approuve',
                    'date_debut' => null,
                    'date_fin'   => null,
                ]
            );
        }

        $this->command->info('✅ Publicités de démo insérées (annonceur@demo.com / password).');
    }

    private function makePlaceholderPng(string $text, array $bg, array $fg): string
    {
        $w = 400; $h = 300;
        $img = imagecreatetruecolor($w, $h);

        $bgColor = imagecolorallocate($img, $bg[0], $bg[1], $bg[2]);
        $fgColor = imagecolorallocate($img, $fg[0], $fg[1], $fg[2]);

        imagefill($img, 0, 0, $bgColor);

        // Bordure intérieure
        imagerectangle($img, 10, 10, $w - 11, $h - 11, imagecolorallocatealpha($img, $fg[0], $fg[1], $fg[2], 80));

        // Texte centré (police embarquée GD)
        $fontSize  = 4;
        $textWidth = imagefontwidth($fontSize) * strlen($text);
        $x = (int)(($w - $textWidth) / 2);
        $y = (int)(($h - imagefontheight($fontSize)) / 2);
        imagestring($img, $fontSize, $x, $y, $text, $fgColor);

        // Sous-texte
        $sub = 'Emploi Bouge Bénin — Annonce publicitaire';
        $sw  = imagefontwidth(2) * strlen($sub);
        imagestring($img, 2, (int)(($w - $sw) / 2), $y + 24, $sub, $fgColor);

        ob_start();
        imagepng($img);
        $data = ob_get_clean();
        imagedestroy($img);

        return $data;
    }
}
