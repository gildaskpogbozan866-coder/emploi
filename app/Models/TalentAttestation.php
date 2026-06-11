<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TalentAttestation extends Model
{
    protected $fillable = ['talent_profil_id', 'nom', 'fichier'];

    public function profil()
    {
        return $this->belongsTo(TalentProfil::class, 'talent_profil_id');
    }

    public function getEstImageAttribute(): bool
    {
        return in_array(strtolower(pathinfo($this->fichier, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp']);
    }
}
