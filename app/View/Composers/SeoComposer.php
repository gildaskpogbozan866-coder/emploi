<?php

namespace App\View\Composers;

use App\Models\ParametreApp;
use App\Models\SeoPage;
use Illuminate\View\View;

class SeoComposer
{
    private const ROUTE_MAP = [
        'home'             => 'home',
        'offre.list'       => 'offres',
        'cv.public.theque' => 'cvs',
        'blog.list'        => 'blog',
        'a-propos'         => 'apropos',
        'contact'          => 'contact',
        'service.list'     => 'services',
        'faq'              => 'faq',
    ];

    public function compose(View $view): void
    {
        $routeName = request()->route()?->getName() ?? '';
        $slug      = self::ROUTE_MAP[$routeName] ?? null;
        $page      = $slug ? SeoPage::forSlug($slug) : null;
        $siteName  = config('app.name', 'Emploi Bouge Bénin');

        $seo = [
            'meta_title'       => $page?->meta_title ?: $siteName,
            'meta_description' => $page?->meta_description ?: 'Plateforme emploi au Bénin — offres, CV et recrutement.',
            'og_title'         => $page?->og_title ?: $page?->meta_title ?: $siteName,
            'og_description'   => $page?->og_description ?: $page?->meta_description ?: 'Plateforme emploi au Bénin.',
            'og_image'         => $page?->og_image_url ?: ParametreApp::get('og_image_default'),
            'robots'           => ($page?->noindex ? 'noindex' : 'index') . ', ' . ($page?->nofollow ? 'nofollow' : 'follow'),
            'canonical'        => url()->current(),
            'ga_id'            => ParametreApp::get('ga_measurement_id'),
            'gsc_verification' => ParametreApp::get('gsc_verification'),
        ];

        $view->with('seo', $seo);
    }
}
