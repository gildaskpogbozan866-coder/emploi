<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = Faq::orderBy('categorie')->orderBy('ordre')->get()
            ->groupBy('categorie');

        return view('admin.faqs.index', compact('faqs'));
    }

    public function create()
    {
        $categories = Faq::distinct()->pluck('categorie')->sort()->values();
        return view('admin.faqs.form', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'categorie' => 'required|string|max:80',
            'question'  => 'required|string|max:300',
            'reponse'   => 'required|string',
            'ordre'     => 'nullable|integer|min:0',
            'actif'     => 'boolean',
        ]);

        $validated['actif'] = $request->boolean('actif', true);
        $validated['ordre'] = $validated['ordre'] ?? 0;

        Faq::create($validated);

        return redirect()->route('admin.faqs.index')
            ->with('success', 'Question ajoutée avec succès.');
    }

    public function edit(Faq $faq)
    {
        $categories = Faq::distinct()->pluck('categorie')->sort()->values();
        return view('admin.faqs.form', compact('faq', 'categories'));
    }

    public function update(Request $request, Faq $faq)
    {
        $validated = $request->validate([
            'categorie' => 'required|string|max:80',
            'question'  => 'required|string|max:300',
            'reponse'   => 'required|string',
            'ordre'     => 'nullable|integer|min:0',
            'actif'     => 'boolean',
        ]);

        $validated['actif'] = $request->boolean('actif', true);
        $validated['ordre'] = $validated['ordre'] ?? 0;

        $faq->update($validated);

        return redirect()->route('admin.faqs.index')
            ->with('success', 'Question mise à jour.');
    }

    public function destroy(Faq $faq)
    {
        $faq->delete();

        return redirect()->route('admin.faqs.index')
            ->with('success', 'Question supprimée.');
    }

    public function toggleActif(Faq $faq)
    {
        $faq->update(['actif' => ! $faq->actif]);

        return back()->with('success', $faq->actif ? 'Question activée.' : 'Question masquée.');
    }
}
