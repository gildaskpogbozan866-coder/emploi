<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Abonnement extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'plan_id', 'starts_at', 'ends_at', 'status', 'auto_renew',
    ];

    protected function casts(): array
    {
        return [
            'starts_at'  => 'datetime',
            'ends_at'    => 'datetime',
            'auto_renew' => 'boolean',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class, 'subscription_id');
    }

    public function estActif(): bool
    {
        return $this->status === 'active'
            && $this->starts_at->isPast()
            && ($this->ends_at === null || $this->ends_at->isFuture());
    }

    /**
     * État réel pour l'affichage — distinct de la colonne status brute :
     * un abonnement programmé pour prendre le relais plus tard (starts_at
     * dans le futur, cf. AbonnementController::souscrire()) ou arrivé à
     * échéance (ends_at passé) garde status='active' en base tant que rien
     * ne l'a explicitement annulé, mais ne doit pas s'afficher comme actif.
     */
    public function etat(): string
    {
        if ($this->status !== 'active') {
            return $this->status;
        }
        if ($this->starts_at->isFuture()) {
            return 'programme';
        }
        if ($this->ends_at !== null && $this->ends_at->isPast()) {
            return 'expire';
        }
        return 'active';
    }

    public function scopeActif($query)
    {
        return $query->where('status', 'active')
                     ->where('starts_at', '<=', now())
                     ->where(fn ($q) => $q->whereNull('ends_at')->orWhere('ends_at', '>', now()));
    }
}
