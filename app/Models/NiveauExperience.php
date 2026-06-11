<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NiveauExperience extends Model
{
    protected $table = 'niveaux_experience';

    protected $fillable = ['code', 'libelle', 'ordre'];

    public function candidats()
    {
        return $this->hasMany(NiveauExperienceCandidat::class);
    }
}
