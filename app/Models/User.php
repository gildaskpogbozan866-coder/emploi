<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'prenom', 'nom', 'email', 'tel', 'pays',
        'role', 'entreprise', 'metier', 'premium',
        'avatar', 'actif', 'email_verified_at',
    ];

    protected $hidden = ['remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'premium'           => 'boolean',
            'actif'             => 'boolean',
        ];
    }

    // ── Accesseurs ──────────────────────────────────────────
    public function getNomCompletAttribute(): string
    {
        return "{$this->prenom} {$this->nom}";
    }

    public function getInitialeAttribute(): string
    {
        return strtoupper(substr($this->prenom, 0, 1));
    }

    // ── Vérifications de rôle (utilise Spatie sous le capot) ─
    public function isAdmin(): bool     { return $this->hasRole('admin'); }
    public function isCandidat(): bool  { return $this->hasRole('candidat'); }
    public function isRecruteur(): bool { return $this->hasRole('recruteur'); }
    public function isTalent(): bool    { return $this->hasRole('talent'); }

    // ── Relations métier ────────────────────────────────────
    public function offres()
    {
        return $this->hasMany(Offre::class, 'recruteur_id');
    }

    public function candidatures()
    {
        return $this->hasMany(Candidature::class, 'candidat_id');
    }

    public function cvs()
    {
        return $this->hasMany(CV::class, 'candidat_id');
    }

    public function talentProfil()
    {
        return $this->hasOne(TalentProfil::class);
    }

    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

    public function articles()
    {
        return $this->hasMany(Article::class, 'auteur_id');
    }

    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }

    public function abonnements()
    {
        return $this->hasMany(Abonnement::class);
    }

    public function abonnementActif()
    {
        return $this->hasOne(Abonnement::class)->where('statut', 'actif')->latest();
    }

    public function alertes()
    {
        return $this->hasMany(Alerte::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function offresSauvegardees()
    {
        return $this->belongsToMany(Offre::class, 'offres_sauvegardees');
    }

    public function cvsFavoris()
    {
        return $this->belongsToMany(CV::class, 'cv_favoris', 'user_id', 'cv_id');
    }
}
