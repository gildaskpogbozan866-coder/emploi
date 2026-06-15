<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partenaire extends Model
{
    protected $fillable = ['nom', 'logo', 'lien', 'ordre', 'actif'];

    protected $casts = ['actif' => 'boolean'];

    public function scopeActifs($query)
    {
        return $query->where('actif', true)->orderBy('ordre')->orderBy('id');
    }
}
