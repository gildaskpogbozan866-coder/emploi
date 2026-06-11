<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidatRealisation extends Model
{
    const MAX_PAR_CANDIDAT = 8;

    protected $fillable = ['user_id', 'titre', 'photo', 'description', 'ordre'];

    public function candidat()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
