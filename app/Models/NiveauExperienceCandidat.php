<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NiveauExperienceCandidat extends Model
{
    protected $table = 'niveau_experience_candidat';

    protected $fillable = ['candidat_id', 'niveau_experience_id'];

    public function candidat()
    {
        return $this->belongsTo(User::class, 'candidat_id');
    }

    public function niveauExperience()
    {
        return $this->belongsTo(NiveauExperience::class);
    }
}
