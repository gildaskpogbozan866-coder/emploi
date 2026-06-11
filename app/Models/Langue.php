<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Langue extends Model
{
    use HasFactory;

    protected $table = 'langues';

    protected $fillable = ['nom'];

    public function candidats()
    {
        return $this->belongsToMany(User::class, 'langues_candidat', 'langue_id', 'candidat_id')
                    ->withPivot('niveau_id')
                    ->withTimestamps();
    }

    public function niveaux()
    {
        return $this->belongsToMany(NiveauLangue::class, 'langues_candidat', 'langue_id', 'niveau_id');
    }
}
