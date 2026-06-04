<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Abonnement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'plan', 'type', 'prix', 'statut', 'debut_le', 'expire_le',
    ];

    protected function casts(): array
    {
        return [
            'debut_le'  => 'datetime',
            'expire_le' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function paiements()
    {
        return $this->morphMany(Paiement::class, 'payable');
    }

    public function estActif(): bool
    {
        return $this->statut === 'actif'
            && ($this->expire_le === null || $this->expire_le->isFuture());
    }
}
