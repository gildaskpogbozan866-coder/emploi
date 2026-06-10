<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NiveauLangue extends Model
{
    protected $table = 'niveaux_langue';

    protected $fillable = ['code', 'libelle', 'ordre'];

    public function languesCandidats()
    {
        return $this->hasMany(LangueCandidat::class, 'niveau_id');
    }

    public static function ordered()
    {
        return static::orderBy('ordre')->get();
    }
}
