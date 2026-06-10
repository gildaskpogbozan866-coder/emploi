<?php

namespace App\Models;

use App\Notifications\ReinitialisationMotDePasse;
use App\Notifications\VerificationEmailFr;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use App\Models\CandidatProfil;
use App\Models\Experience;
use App\Models\Formation;
use App\Models\CompetenceCandidat;
use App\Models\LangueCandidat;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'prenom', 'nom', 'email', 'password', 'tel', 'pays',
        'role', 'entreprise', 'metier', 'premium',
        'avatar', 'actif', 'email_verified_at',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'premium'           => 'boolean',
            'actif'             => 'boolean',
        ];
    }

    // ── Notifications françaises ────────────────────────────
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerificationEmailFr());
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ReinitialisationMotDePasse($token));
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
    /** Vrai si le candidat a renseigné un profil pro (ex-talent). */
    public function isTalent(): bool    { return $this->talentProfil()->exists(); }

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

    public function recruteurVerification()
    {
        return $this->hasOne(RecruteurVerification::class);
    }

    // ── Relations profil candidat ───────────────────────────
    public function candidatProfil()
    {
        return $this->hasOne(CandidatProfil::class);
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class, 'candidat_id')
                    ->orderByDesc('en_cours')
                    ->orderByDesc('date_debut');
    }

    public function formations()
    {
        return $this->hasMany(Formation::class, 'candidat_id')
                    ->orderByDesc('en_cours')
                    ->orderByDesc('date_debut');
    }

    public function competences()
    {
        return $this->hasMany(CompetenceCandidat::class, 'candidat_id');
    }

    public function langues()
    {
        return $this->hasMany(LangueCandidat::class, 'candidat_id');
    }

    public function getProfilCompletionAttribute(): int
    {
        $score = 0;
        if ($this->avatar)                                      $score += 10;
        if ($this->candidatProfil?->titre_professionnel)        $score += 10;
        if ($this->candidatProfil?->bio)                        $score += 10;
        if ($this->candidatProfil?->disponibilite)              $score += 5;
        if ($this->candidatProfil?->types_contrat)              $score += 5;
        if ($this->candidatProfil?->ville)                      $score += 5;
        if ($this->relationLoaded('experiences')
            ? $this->experiences->count() > 0
            : $this->experiences()->exists())                   $score += 25;
        if ($this->relationLoaded('formations')
            ? $this->formations->count() > 0
            : $this->formations()->exists())                    $score += 15;
        if ($this->relationLoaded('competences')
            ? $this->competences->count() >= 3
            : $this->competences()->count() >= 3)               $score += 10;
        if ($this->relationLoaded('langues')
            ? $this->langues->count() > 0
            : $this->langues()->exists())                       $score += 5;
        return $score;
    }
}
