<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageVisit extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'session_id', 'url', 'page', 'device_type',
        'browser', 'os', 'country', 'city',
        'ip_hash', 'user_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
