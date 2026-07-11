<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidatureHistorique extends Model
{
    const UPDATED_AT = null;

    protected $fillable = ['candidature_id', 'recruteur_id', 'statut', 'note'];

    public function candidature()
    {
        return $this->belongsTo(Candidature::class);
    }

    public function recruteur()
    {
        return $this->belongsTo(User::class, 'recruteur_id');
    }
}
