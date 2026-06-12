<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParametreApp;
use App\Models\SeoPage;
use Illuminate\Http\Request;

class SeoController extends Controller
{
    public function index()
    {
        $pages = SeoPage::orderBy('page_slug')->get();

        $global = [
            'ga_measurement_id' => ParametreApp::get('ga_measurement_id'),
            'gsc_verification'  => ParametreApp::get('gsc_verification'),
            'og_image_default'  => ParametreApp::get('og_image_default'),
            'robots_txt_extra'  => ParametreApp::get('robots_txt_extra'),
        ];

        return view('admin.seo', compact('pages', 'global'));
    }

    public function updateGlobal(Request $request)
    {
        $request->validate([
            'ga_measurement_id' => 'nullable|string|max:50',
            'gsc_verification'  => 'nullable|string|max:200',
            'og_image_default'  => 'nullable|string|max:500',
            'robots_txt_extra'  => 'nullable|string|max:2000',
        ]);

        foreach (['ga_measurement_id', 'gsc_verification', 'og_image_default', 'robots_txt_extra'] as $cle) {
            ParametreApp::set($cle, $request->input($cle, ''));
        }

        return back()->with('success', 'Paramètres SEO globaux enregistrés.');
    }

    public function updatePage(Request $request, SeoPage $seoPage)
    {
        $request->validate([
            'meta_title'       => 'nullable|string|max:200',
            'meta_description' => 'nullable|string|max:500',
            'og_title'         => 'nullable|string|max:200',
            'og_description'   => 'nullable|string|max:500',
            'og_image_url'     => 'nullable|string|max:500',
        ]);

        $seoPage->update([
            'meta_title'       => $request->meta_title,
            'meta_description' => $request->meta_description,
            'og_title'         => $request->og_title,
            'og_description'   => $request->og_description,
            'og_image_url'     => $request->og_image_url,
            'noindex'          => $request->boolean('noindex'),
            'nofollow'         => $request->boolean('nofollow'),
        ]);

        return back()->with('success', 'SEO de la page « ' . ucfirst($seoPage->page_slug) . ' » mis à jour.');
    }
}
