<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CV extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cvs';

    protected $fillable = [
        'candidat_id', 'ville', 'competences',
        'experience', 'formation', 'langues', 'fichier_path',
        'photo', 'plan', 'visible', 'vues', 'resume',
        'metier', 'niveau_etude', 'type_contrat', 'niveau_experience',
        'publie_le', 'vu_admin',
    ];

    protected function casts(): array
    {
        return [
            'visible'   => 'boolean',
            'vu_admin'  => 'boolean',
            'publie_le' => 'datetime',
        ];
    }

    public function candidat()
    {
        return $this->belongsTo(User::class, 'candidat_id');
    }

    public function scopeVisible($query)
    {
        return $query->where('visible', true)->whereNotNull('publie_le');
    }

    /**
     * Mêmes champs que ceux rendus obligatoires au dépôt (CVController::store()) —
     * un CV créé par un autre biais (ex. upload à la volée pendant une candidature)
     * ne doit pouvoir être publié en CVthèque qu'une fois ces champs renseignés.
     */
    public function estComplet(): bool
    {
        return filled($this->metier)
            && filled($this->ville)
            && filled($this->resume)
            && filled($this->competences)
            && filled($this->experience)
            && filled($this->formation)
            && filled($this->langues)
            && filled($this->niveau_etude)
            && filled($this->niveau_experience)
            && filled($this->type_contrat)
            && filled($this->fichier_path);
    }
}
