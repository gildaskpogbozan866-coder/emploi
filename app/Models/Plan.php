<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'description', 'target_type',
        'price', 'currency', 'duration_days', 'is_free', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_free'   => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function features()
    {
        return $this->hasMany(PlanFeature::class);
    }

    public function abonnements()
    {
        return $this->hasMany(Abonnement::class);
    }

    /** Retourne la valeur d'une fonctionnalité par sa clé. */
    public function getFeature(string $key, mixed $default = null): mixed
    {
        return $this->features->firstWhere('feature_key', $key)?->feature_value ?? $default;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForCandidat($query)
    {
        return $query->whereIn('target_type', ['candidat', 'both']);
    }

    public function scopeForRecruteur($query)
    {
        return $query->whereIn('target_type', ['recruteur', 'both']);
    }
}
