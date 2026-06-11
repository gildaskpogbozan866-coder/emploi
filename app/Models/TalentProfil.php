<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TalentProfil extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'metier', 'specialite', 'pays', 'ville', 'bio',
        'competences', 'experience', 'annees_experience', 'langues',
        'photo', 'portfolio_url', 'disponibilite', 'types_contrat',
        'plan', 'visible', 'vues',
    ];

    protected function casts(): array
    {
        return [
            'visible'       => 'boolean',
            'competences'   => 'array',
            'langues'       => 'array',
            'types_contrat' => 'array',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function experiences()
    {
        return $this->hasMany(TalentExperience::class, 'talent_profil_id')->orderByDesc('date_debut');
    }

    public function formations()
    {
        return $this->hasMany(TalentFormation::class, 'talent_profil_id')->orderByDesc('annee_obtention');
    }

    public function attestations()
    {
        return $this->hasMany(TalentAttestation::class, 'talent_profil_id')->latest();
    }

    public function travaux()
    {
        return $this->hasMany(TalentTravail::class, 'talent_profil_id')->latest();
    }

    public function favorisParRecruteurs()
    {
        return $this->belongsToMany(User::class, 'talent_favoris', 'talent_id', 'recruteur_id');
    }

    public function scopeVisible($query)
    {
        return $query->where('visible', true);
    }

    public function getProfilCompletionAttribute(): int
    {
        $score = 0;
        if ($this->photo)                                                     $score += 10;
        if ($this->specialite)                                                $score += 10;
        if ($this->bio)                                                       $score += 10;
        if (is_array($this->competences) && count($this->competences) >= 3)  $score += 20;
        if ($this->annees_experience !== null)                                $score += 10;
        if ($this->disponibilite)                                             $score += 15;
        if (!empty($this->types_contrat))                                     $score += 10;
        if ($this->portfolio_url)                                             $score += 5;
        if (is_array($this->langues) && count($this->langues) >= 1)          $score += 10;
        return $score;
    }
}
