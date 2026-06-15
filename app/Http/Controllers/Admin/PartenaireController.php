<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partenaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartenaireController extends Controller
{
    public function index()
    {
        $partenaires = Partenaire::orderBy('ordre')->orderBy('id')->get();
        return view('admin.partenaires.index', compact('partenaires'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom'   => 'required|string|max:100',
            'logo'  => 'required|image|max:2048',
            'lien'  => 'nullable|url|max:255',
            'ordre' => 'nullable|integer|min:0',
        ]);

        $data['logo']  = $request->file('logo')->store('partenaires', 'public');
        $data['ordre'] = $data['ordre'] ?? 0;
        $data['actif'] = true;

        Partenaire::create($data);

        return back()->with('success', 'Partenaire ajouté.');
    }

    public function update(Request $request, Partenaire $partenaire)
    {
        $data = $request->validate([
            'nom'   => 'required|string|max:100',
            'logo'  => 'nullable|image|max:2048',
            'lien'  => 'nullable|url|max:255',
            'ordre' => 'nullable|integer|min:0',
            'actif' => 'nullable|boolean',
        ]);

        if ($request->hasFile('logo')) {
            Storage::disk('public')->delete($partenaire->logo);
            $data['logo'] = $request->file('logo')->store('partenaires', 'public');
        }

        $data['actif'] = $request->boolean('actif');
        $data['ordre'] = $data['ordre'] ?? 0;

        $partenaire->update($data);

        return back()->with('success', 'Partenaire mis à jour.');
    }

    public function destroy(Partenaire $partenaire)
    {
        Storage::disk('public')->delete($partenaire->logo);
        $partenaire->delete();

        return back()->with('success', 'Partenaire supprimé.');
    }
}
