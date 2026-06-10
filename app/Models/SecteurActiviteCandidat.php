<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecteurActiviteCandidat extends Model
{
    protected $table = 'secteur_activite_candidat';

    protected $fillable = ['candidat_id', 'secteur_activite_id'];

    public function candidat()
    {
        return $this->belongsTo(User::class, 'candidat_id');
    }

    public function secteurActivite()
    {
        return $this->belongsTo(SecteurActivite::class);
    }
}
