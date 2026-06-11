<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecteurActivite extends Model
{
    protected $table = 'secteurs_activite';

    protected $fillable = ['code', 'libelle'];

    public function candidats()
    {
        return $this->belongsToMany(User::class, 'secteur_activite_candidat', 'secteur_activite_id', 'candidat_id')
                    ->withTimestamps();
    }
}
