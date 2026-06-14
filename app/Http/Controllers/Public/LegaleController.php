<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\PageLegale;
use Illuminate\Http\Request;

class LegaleController extends Controller
{
    public function show(string $slug)
    {
        $slugsValides = array_keys(PageLegale::slugs());

        if (! in_array($slug, $slugsValides)) {
            abort(404);
        }

        $page = PageLegale::firstOrNew(['slug' => $slug], [
            'titre'   => PageLegale::slugs()[$slug],
            'contenu' => null,
        ]);

        return view('public.legale', compact('page'));
    }
}
