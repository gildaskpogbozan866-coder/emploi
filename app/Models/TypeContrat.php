<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TypeContrat extends Model
{
    protected $table = 'type_contrats';

    protected $fillable = ['code', 'libelle'];

    public function candidats()
    {
        return $this->belongsToMany(User::class, 'type_contrat_candidat', 'type_contrat_id', 'candidat_id')
                    ->withTimestamps();
    }
}
