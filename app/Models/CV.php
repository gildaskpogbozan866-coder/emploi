<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CV extends Model
{
    use HasFactory;

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
        return $query->where('visible', true);
    }
}
