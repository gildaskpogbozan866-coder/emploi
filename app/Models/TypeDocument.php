<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeDocument extends Model
{
    protected $fillable = ['nom', 'description', 'actif', 'ordre'];

    protected $casts = ['actif' => 'boolean'];

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function scopeActif($query)
    {
        return $query->where('actif', true)->orderBy('ordre');
    }
}
