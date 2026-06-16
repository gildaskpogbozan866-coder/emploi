<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageLegale extends Model
{
    protected $fillable = ['slug', 'titre', 'contenu'];

    public static function slugs(): array
    {
        return [
            'mentions-legales'                => 'Mentions légales',
            'conditions-generales-utilisation' => "Conditions Générales d'Utilisation",
            'conditions-generales-de-vente'   => 'Conditions Générales de Vente',
            'politique-confidentialite'        => 'Politique de confidentialité',
        ];
    }
}
