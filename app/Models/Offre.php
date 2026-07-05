<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offre extends Model
{
    use HasFactory;

    protected $fillable = [
        'recruteur_id', 'titre', 'entreprise', 'logo', 'localisation',
        'type_contrat_id', 'secteur', 'salaire_min', 'salaire_max', 'description',
        'exigences', 'date_limite', 'fichier', 'statut', 'premium', 'vues',
        'publication_plan_id', 'published_at', 'expires_at',
        'niveau_experience', 'niveau_etude', 'metier_id',
        'exige_cv', 'exige_lettre',
    ];

    protected function casts(): array
    {
        return [
            'date_limite'   => 'date',
            'premium'       => 'boolean',
            'exige_cv'      => 'boolean',
            'exige_lettre'  => 'boolean',
            'published_at'  => 'datetime',
            'expires_at'    => 'datetime',
            'secteur'       => 'array',
        ];
    }

    public function recruteur()
    {
        return $this->belongsTo(User::class, 'recruteur_id');
    }
    public function type()
    {
        return $this->belongsTo(TypeContrat::class, 'type_contrat_id');
    }

    public function metier()
    {
        return $this->belongsTo(Metier::class);
    }

    public function candidatures()
    {
        return $this->hasMany(Candidature::class);
    }

    public function competences()
    {
        return $this->belongsToMany(Competence::class, 'offre_competence');
    }

    public function typesDocumentsRequis()
    {
        return $this->belongsToMany(TypeDocument::class, 'offre_type_document');
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

    public function salaireFormate(): ?string
    {
        $fmt = fn($v) => number_format($v, 0, ',', ' ');

        if ($this->salaire_min && $this->salaire_max) {
            return $fmt($this->salaire_min).' – '.$fmt($this->salaire_max).' FCFA';
        }
        if ($this->salaire_min) {
            return 'À partir de '.$fmt($this->salaire_min).' FCFA';
        }
        if ($this->salaire_max) {
            return 'Jusqu\'à '.$fmt($this->salaire_max).' FCFA';
        }
        return null;
    }
}
