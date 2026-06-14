<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecruteurDocumentType extends Model
{
    protected $table = 'recruteur_document_types';

    protected $fillable = [
        'nom', 'description', 'accepte_fichier', 'accepte_texte',
        'est_requis', 'ordre', 'est_actif',
    ];

    protected function casts(): array
    {
        return [
            'accepte_fichier' => 'boolean',
            'accepte_texte'   => 'boolean',
            'est_requis'      => 'boolean',
            'est_actif'       => 'boolean',
        ];
    }

    public function documents()
    {
        return $this->hasMany(RecruteurDocument::class, 'type_id');
    }

    public static function actifs()
    {
        return static::where('est_actif', true)->orderBy('ordre')->orderBy('id');
    }
}
