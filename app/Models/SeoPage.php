<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeoPage extends Model
{
    protected $table    = 'seo_pages';
    protected $fillable = [
        'page_slug', 'meta_title', 'meta_description',
        'og_title', 'og_description', 'og_image_url',
        'noindex', 'nofollow',
    ];
    protected $casts = [
        'noindex'  => 'boolean',
        'nofollow' => 'boolean',
    ];

    public static function forSlug(string $slug): ?self
    {
        return static::where('page_slug', $slug)->first();
    }
}
