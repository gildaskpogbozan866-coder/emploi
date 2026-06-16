<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CreditCvPack extends Model
{
    protected $fillable = ['label', 'credits', 'prix', 'badge', 'featured', 'actif', 'ordre'];

    protected $casts = [
        'featured' => 'boolean',
        'actif'    => 'boolean',
        'credits'  => 'integer',
        'prix'     => 'integer',
    ];

    public function scopeActif($query)
    {
        return $query->where('actif', true);
    }

    public function prixParCredit(): int
    {
        return $this->credits > 0 ? (int) round($this->prix / $this->credits) : 0;
    }
}
