<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    protected $fillable = ['categorie', 'question', 'reponse', 'ordre', 'actif'];

    protected $casts = ['actif' => 'boolean'];

    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }
}
