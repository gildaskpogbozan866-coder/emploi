<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom', 'slug', 'description', 'details',
        'prix', 'devise', 'delai', 'type', 'actif', 'nb_commandes',
    ];

    protected function casts(): array
    {
        return [
            'actif' => 'boolean',
        ];
    }

    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
