<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecruteurVerification extends Model
{
    protected $fillable = [
        'user_id', 'statut',
        'carte_biometrique', 'cip',
        'ifu_fichier', 'ifu_numero',
        'rccm_fichier', 'rccm_numero',
        'note_admin', 'reviewed_by', 'reviewed_at',
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

    public function estApprouve(): bool { return $this->statut === 'approuve'; }
    public function estEnAttente(): bool { return $this->statut === 'en_attente'; }
    public function estRejete(): bool    { return $this->statut === 'rejete'; }

    // Retourne 'image' ou 'pdf' pour un champ fichier donné
    public function typeDocument(string $field): string
    {
        if (! $this->$field) return '';
        $ext = strtolower(pathinfo($this->$field, PATHINFO_EXTENSION));
        return in_array($ext, ['jpg', 'jpeg', 'png', 'webp']) ? 'image' : 'pdf';
    }
}
