<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompetenceCandidat extends Model
{
    protected $table = 'competence_candidat';

    protected $fillable = ['candidat_id', 'competence_id', 'annees_experience'];

    public function candidat()
    {
        return $this->belongsTo(User::class, 'candidat_id');
    }

    public function competence()
    {
        return $this->belongsTo(Competence::class);
    }
}
