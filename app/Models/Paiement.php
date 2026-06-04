<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'reference', 'montant', 'devise',
        'type', 'payable_id', 'payable_type',
        'methode', 'statut', 'notes',
    ];

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

    public function payable()
    {
        return $this->morphTo();
    }
}
