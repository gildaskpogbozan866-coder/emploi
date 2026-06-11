<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LangueCandidat extends Model
{
    protected $table = 'langues_candidat';

    protected $fillable = ['candidat_id', 'langue_id', 'niveau_id'];

    public function candidat()
    {
        return $this->belongsTo(User::class, 'candidat_id');
    }

    public function langue()
    {
        return $this->belongsTo(Langue::class, 'langue_id');
    }

    public function niveau()
    {
        return $this->belongsTo(NiveauLangue::class, 'niveau_id');
    }
}
