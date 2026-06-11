<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TalentExperience extends Model
{
    protected $fillable = [
        'talent_profil_id', 'poste', 'employeur',
        'date_debut', 'date_fin', 'en_cours', 'description',
    ];

    protected $casts = ['en_cours' => 'boolean'];

    public function profil()
    {
        return $this->belongsTo(TalentProfil::class, 'talent_profil_id');
    }

    public function getPeriodeAttribute(): string
    {
        $fin = $this->en_cours ? "Aujourd'hui" : ($this->date_fin ?? '');
        return $this->date_debut . ($fin ? ' — ' . $fin : '');
    }
}
