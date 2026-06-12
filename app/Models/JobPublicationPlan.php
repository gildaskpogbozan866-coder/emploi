<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobPublicationPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'duration_days', 'price', 'is_free', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_free'   => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function offres()
    {
        return $this->hasMany(Offre::class, 'publication_plan_id');
    }

    public function isUnlimited(): bool
    {
        return $this->duration_days === null;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
