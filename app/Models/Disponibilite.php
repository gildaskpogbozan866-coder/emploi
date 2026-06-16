<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disponibilite extends Model
{
    protected $fillable = ['code', 'libelle', 'couleur', 'ordre', 'actif'];

    protected $casts = ['actif' => 'boolean'];

    public function scopeActifs($query)
    {
        return $query->where('actif', true)->orderBy('ordre');
    }
}
