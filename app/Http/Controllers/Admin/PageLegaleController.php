<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageLegale;
use Illuminate\Http\Request;

class PageLegaleController extends Controller
{
    public function index()
    {
        $pages = collect(PageLegale::slugs())->map(function ($titre, $slug) {
            return PageLegale::firstOrNew(['slug' => $slug], ['titre' => $titre, 'contenu' => null]);
        });

        return view('admin.legales.index', compact('pages'));
    }

    public function edit(string $slug)
    {
        $slugsValides = array_keys(PageLegale::slugs());

        if (! in_array($slug, $slugsValides)) {
            abort(404);
        }

        $page = PageLegale::firstOrNew(['slug' => $slug], [
            'titre'   => PageLegale::slugs()[$slug],
            'contenu' => null,
        ]);

        return view('admin.legales.edit', compact('page', 'slug'));
    }

    public function update(Request $request, string $slug)
    {
        $slugsValides = array_keys(PageLegale::slugs());

        if (! in_array($slug, $slugsValides)) {
            abort(404);
        }

        $validated = $request->validate([
            'titre'   => 'required|string|max:150',
            'contenu' => 'nullable|string',
        ]);

        PageLegale::updateOrCreate(
            ['slug' => $slug],
            $validated
        );

        return redirect()
            ->route('admin.legales.index')
            ->with('success', 'Page mise à jour avec succès.');
    }
}
