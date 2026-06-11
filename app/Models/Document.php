<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = ['user_id', 'type_document_id', 'nom', 'fichier', 'pays', 'ville', 'competences', 'experience', 'formation', 'langues'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function type()
    {
        return $this->belongsTo(TypeDocument::class, 'type_document_id');
    }

    public function estImage(): bool
    {
        return in_array(strtolower(pathinfo($this->fichier, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'webp']);
    }
}
