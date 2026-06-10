<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NiveauEtudeCandidat extends Model
{
    protected $table = 'niveau_etude_candidat';

    protected $fillable = ['candidat_id', 'niveau_etude_id'];

    public function candidat()
    {
        return $this->belongsTo(User::class, 'candidat_id');
    }

    public function niveauEtude()
    {
        return $this->belongsTo(NiveauEtude::class);
    }
}
