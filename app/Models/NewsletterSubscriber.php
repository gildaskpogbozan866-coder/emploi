<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NewsletterSubscriber extends Model
{
    protected $fillable = ['email', 'prenom', 'token', 'statut', 'source'];

    protected $attributes = ['statut' => 'actif'];

    protected static function booted(): void
    {
        static::creating(function (self $sub) {
            if (empty($sub->token)) {
                $sub->token = Str::random(64);
            }
        });
    }

    public function scopeActifs($query)
    {
        return $query->where('statut', 'actif');
    }

    public function scopeRecents($query, int $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
