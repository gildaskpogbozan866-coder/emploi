<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NiveauEtude extends Model
{
    protected $table = 'niveaux_etudes';

    protected $fillable = ['code', 'libelle', 'ordre'];

    public function candidats()
    {
        return $this->hasMany(NiveauEtudeCandidat::class);
    }
}
