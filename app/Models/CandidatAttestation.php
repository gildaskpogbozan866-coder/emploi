<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidatAttestation extends Model
{
    protected $fillable = ['user_id', 'nom', 'fichier'];

    public function candidat()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getEstImageAttribute(): bool
    {
        return in_array(strtolower(pathinfo($this->fichier, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp']);
    }
}
