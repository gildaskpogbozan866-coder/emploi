<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CreditCvPack;
use Illuminate\Http\Request;

class CreditCvPackController extends Controller
{
    public function index()
    {
        $packs = CreditCvPack::orderBy('ordre')->orderBy('credits')->get();
        return view('admin.credit-cv-packs.index', compact('packs'));
    }

    public function create()
    {
        return view('admin.credit-cv-packs.form', ['pack' => new CreditCvPack()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        CreditCvPack::create($data);

        return redirect()->route('admin.credit-cv-packs.index')
            ->with('success', 'Pack créé avec succès.');
    }

    public function edit(CreditCvPack $creditCvPack)
    {
        return view('admin.credit-cv-packs.form', ['pack' => $creditCvPack]);
    }

    public function update(Request $request, CreditCvPack $creditCvPack)
    {
        $data = $this->validated($request);
        $creditCvPack->update($data);

        return redirect()->route('admin.credit-cv-packs.index')
            ->with('success', 'Pack mis à jour.');
    }

    public function toggle(CreditCvPack $creditCvPack)
    {
        $creditCvPack->update(['actif' => !$creditCvPack->actif]);
        $label = $creditCvPack->actif ? 'activé' : 'désactivé';

        return back()->with('success', "Pack « {$creditCvPack->label} » {$label}.");
    }

    public function destroy(CreditCvPack $creditCvPack)
    {
        $label = $creditCvPack->label;
        $creditCvPack->delete();

        return redirect()->route('admin.credit-cv-packs.index')
            ->with('success', "Pack « {$label} » supprimé.");
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'label'    => 'required|string|max:100',
            'credits'  => 'required|integer|min:1|max:9999',
            'prix'     => 'required|integer|min:0',
            'badge'    => 'nullable|string|max:60',
            'featured' => 'boolean',
            'actif'    => 'boolean',
            'ordre'    => 'required|integer|min:0|max:255',
        ], messages: [
            'label.required'   => 'Le libellé est obligatoire.',
            'credits.required' => 'Le nombre de crédits est obligatoire.',
            'prix.required'    => 'Le prix est obligatoire.',
        ]);
    }
}
