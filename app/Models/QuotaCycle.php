<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuotaCycle extends Model
{
    protected $fillable = [
        'user_id', 'quota_key', 'cycle_starts_at', 'cycle_ends_at',
    ];

    protected function casts(): array
    {
        return [
            'cycle_starts_at' => 'datetime',
            'cycle_ends_at'   => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
