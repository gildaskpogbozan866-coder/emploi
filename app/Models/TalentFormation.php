<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TalentFormation extends Model
{
    protected $fillable = [
        'talent_profil_id', 'diplome', 'etablissement',
        'annee_obtention', 'description',
    ];

    public function profil()
    {
        return $this->belongsTo(TalentProfil::class, 'talent_profil_id');
    }
}
