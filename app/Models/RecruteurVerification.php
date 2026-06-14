<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecruteurVerification extends Model
{
    protected $fillable = [
        'user_id', 'statut', 'note_admin', 'reviewed_by', 'reviewed_at',
    ];

    protected function casts(): array
    {
        return ['reviewed_at' => 'datetime'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function documents()
    {
        return $this->hasMany(RecruteurDocument::class, 'user_id', 'user_id')->with('type');
    }

    public function estApprouve(): bool { return $this->statut === 'approuve'; }
    public function estEnAttente(): bool { return $this->statut === 'en_attente'; }
    public function estRejete(): bool    { return $this->statut === 'rejete'; }
}
