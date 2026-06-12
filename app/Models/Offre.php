<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offre extends Model
{
    use HasFactory;

    protected $fillable = [
        'recruteur_id', 'titre', 'entreprise', 'localisation',
        'type', 'secteur', 'salaire', 'description',
        'exigences', 'date_limite', 'fichier', 'statut', 'premium', 'vues',
        'publication_plan_id', 'published_at', 'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'date_limite'  => 'date',
            'premium'      => 'boolean',
            'published_at' => 'datetime',
            'expires_at'   => 'datetime',
            'secteur'      => 'array',
        ];
    }

    public function recruteur()
    {
        return $this->belongsTo(User::class, 'recruteur_id');
    }

    public function candidatures()
    {
        return $this->hasMany(Candidature::class);
    }

    public function competences()
    {
        return $this->belongsToMany(Competence::class, 'offre_competence');
    }

    public function sauvegardeursPar()
    {
        return $this->belongsToMany(User::class, 'offres_sauvegardees');
    }

    public function publicationPlan()
    {
        return $this->belongsTo(JobPublicationPlan::class, 'publication_plan_id');
    }

    public function scopeActive($query)
    {
        return $query->where('statut', 'active');
    }

    public function scopeRecente($query)
    {
        return $query->orderByDesc('created_at');
    }

    /** Filtre les offres dont la durée de publication n'a pas expiré. */
    public function scopeNonExpiree($query)
    {
        return $query->where(fn ($q) => $q->whereNull('expires_at')->orWhere('expires_at', '>', now()));
    }
}
