<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Commande extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'service_id', 'reference', 'details_demande',
        'fichier_joint', 'montant', 'statut', 'paiement_statut',
        'paiement_methode', 'note_admin', 'fichier_livraison',
    ];

    protected static function booted(): void
    {
        static::creating(function (Commande $commande) {
            $commande->reference = 'CMD-' . strtoupper(Str::random(8));
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function paiements()
    {
        return $this->morphMany(Paiement::class, 'payable');
    }
}
