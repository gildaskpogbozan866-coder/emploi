<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Competence extends Model
{
    protected $fillable = ['nom', 'slug'];

    public function metiers()
    {
        return $this->belongsToMany(Metier::class, 'metier_competence')
                    ->withTimestamps();
    }

    public function candidats()
    {
        return $this->belongsToMany(User::class, 'competence_candidat', 'competence_id', 'candidat_id')
                    ->withPivot('annees_experience')
                    ->withTimestamps();
    }
}
