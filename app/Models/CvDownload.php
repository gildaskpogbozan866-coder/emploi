<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CvDownload extends Model
{
    public $timestamps = false;

    protected $fillable = ['recruteur_id', 'cv_id'];

    protected $casts = ['downloaded_at' => 'datetime'];

    public function recruteur()
    {
        return $this->belongsTo(User::class, 'recruteur_id');
    }

    public function cv()
    {
        return $this->belongsTo(CV::class, 'cv_id');
    }
}
