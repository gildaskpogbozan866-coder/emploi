<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'subscription_id', 'reference', 'transaction_reference',
        'montant', 'devise', 'type', 'payable_id', 'payable_type',
        'methode', 'statut', 'notes', 'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'paid_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Paiement $p) {
            $p->reference = 'PAY-' . strtoupper(Str::random(10));
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /** Abonnement directement lié à ce paiement (via FK). */
    public function abonnement()
    {
        return $this->belongsTo(Abonnement::class, 'subscription_id');
    }

    /** Relation polymorphique générique (utilisée pour Commande et autres). */
    public function payable()
    {
        return $this->morphTo();
    }
}
