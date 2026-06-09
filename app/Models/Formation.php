<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Formation extends Model
{
    protected $fillable = [
        'candidat_id', 'diplome', 'etablissement', 'domaine',
        'date_debut', 'date_fin', 'en_cours', 'description', 'ordre',
    ];

    protected function casts(): array
    {
        return [
            'date_debut' => 'date',
            'date_fin'   => 'date',
            'en_cours'   => 'boolean',
        ];
    }

    public function candidat()
    {
        return $this->belongsTo(User::class, 'candidat_id');
    }
}
