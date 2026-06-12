<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Article;
use App\Models\Offre;
use App\Models\ParametreApp;

class SitemapController extends Controller
{
    public function sitemap()
    {
        $staticPages = [
            ['url' => route('home'),            'priority' => '1.0', 'changefreq' => 'daily'],
            ['url' => route('offre.list'),       'priority' => '0.9', 'changefreq' => 'hourly'],
            ['url' => route('cv.public.theque'), 'priority' => '0.8', 'changefreq' => 'daily'],
            ['url' => route('blog.list'),        'priority' => '0.7', 'changefreq' => 'daily'],
            ['url' => route('service.list'),     'priority' => '0.6', 'changefreq' => 'weekly'],
            ['url' => route('a-propos'),         'priority' => '0.5', 'changefreq' => 'monthly'],
            ['url' => route('contact'),          'priority' => '0.5', 'changefreq' => 'monthly'],
            ['url' => route('faq'),              'priority' => '0.4', 'changefreq' => 'monthly'],
        ];

        $offres = Offre::active()
            ->select('id', 'updated_at')
            ->orderByDesc('updated_at')
            ->limit(500)
            ->get();

        $articles = Article::publie()
            ->select('slug', 'updated_at')
            ->orderByDesc('updated_at')
            ->limit(200)
            ->get();

        $content = view('public.sitemap', compact('staticPages', 'offres', 'articles'))->render();

        return response($content, 200)->header('Content-Type', 'application/xml');
    }

    public function robots()
    {
        $extra = ParametreApp::get('robots_txt_extra');

        $content = "User-agent: *\n"
            . "Allow: /\n"
            . "Disallow: /admin/\n"
            . "Disallow: /candidat/\n"
            . "Disallow: /recruteur/\n"
            . "Disallow: /auth/\n"
            . "\n"
            . 'Sitemap: ' . route('sitemap') . "\n";

        if ($extra) {
            $content .= "\n" . $extra . "\n";
        }

        return response($content, 200)->header('Content-Type', 'text/plain');
    }
}
