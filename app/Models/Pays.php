<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pays extends Model
{
    protected $fillable = ['nom', 'code', 'ordre', 'actif'];

    protected $casts = ['actif' => 'boolean'];

    public function scopeActifs($query)
    {
        return $query->where('actif', true)->orderBy('ordre')->orderBy('nom');
    }
}
