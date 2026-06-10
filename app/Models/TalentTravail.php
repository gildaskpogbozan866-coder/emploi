<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TalentTravail extends Model
{
    protected $table = 'talent_travaux';

    protected $fillable = ['talent_profil_id', 'photo', 'description'];

    public function profil()
    {
        return $this->belongsTo(TalentProfil::class, 'talent_profil_id');
    }
}
