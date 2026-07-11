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

    /** Offres à montrer dans les listings publics : actives + expirées (jamais vides), les actives en tête. */
    public function scopeAffichable($query)
    {
        return $query->whereIn('statut', ['active', 'expiree'])
            ->orderByRaw("statut = 'expiree' OR (date_limite IS NOT NULL AND date_limite < ?)", [now()->toDateString()]);
    }

    /**
     * Vrai si l'offre n'est plus postulable : statut non actif, ou date_limite dépassée.
     * Ne pas se fier uniquement à `statut` — il ne passe à 'expiree' qu'une fois par jour
     * via la commande planifiée `offres:expirer` (voir routes/console.php), donc entre le
     * dépassement réel de la date et le prochain passage du cron, `statut` reste 'active'
     * en base alors que l'offre a déjà dépassé sa date limite.
     */
    public function aExpire(): bool
    {
        // date_limite est un jour inclusif (comme offres:expirer : whereDate('date_limite', '<', today())) —
        // l'offre reste valable jusqu'à la fin de ce jour-là, pas depuis son minuit.
        return $this->statut !== 'active'
            || ($this->date_limite !== null && $this->date_limite->lt(now()->startOfDay()));
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
