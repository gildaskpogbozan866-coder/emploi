<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageLegale extends Model
{
    protected $fillable = ['slug', 'titre', 'contenu'];

    public static function slugs(): array
    {
        return [
            'mentions-legales'       => 'Mentions légales',
            'politique-confidentialite' => 'Politique de confidentialité',
            'cgv'                    => 'Conditions Générales de Vente',
        ];
    }
}
