<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Metier extends Model
{
    protected $fillable = ['nom', 'slug', 'description'];

    public function competences()
    {
        return $this->belongsToMany(Competence::class, 'metier_competence')
                    ->withTimestamps();
    }

    public function candidats()
    {
        return $this->belongsToMany(User::class, 'candidat_metier', 'metier_id', 'candidat_id')
                    ->withTimestamps();
    }
}
