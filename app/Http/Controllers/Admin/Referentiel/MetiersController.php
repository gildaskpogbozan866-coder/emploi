<?php

namespace App\Http\Controllers\Admin\Referentiel;

use App\Http\Controllers\Controller;
use App\Models\Metier;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MetiersController extends Controller
{
    private function viewData(array $extra = []): array
    {
        return array_merge([
            'label'        => 'Métiers',
            'singular'     => 'métier',
            'hasNom'       => true,
            'hasCode'      => false,
            'hasDesc'      => true,
            'hasOrdre'     => false,
            'countKey'     => 'candidats_count',
            'routeIndex'   => 'admin.metiers.index',
            'routeCreate'  => 'admin.metiers.create',
            'routeStore'   => 'admin.metiers.store',
            'routeEdit'    => 'admin.metiers.edit',
            'routeUpdate'  => 'admin.metiers.update',
            'routeDestroy' => 'admin.metiers.destroy',
        ], $extra);
    }

    public function index()
    {
        return view('admin.referentiels.index', $this->viewData([
            'items' => Metier::withCount('candidats')->orderBy('nom')->get(),
        ]));
    }

    public function create()
    {
        return view('admin.referentiels.form', $this->viewData(['item' => null]));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom'         => 'required|string|max:200|unique:metiers,nom',
            'description' => 'nullable|string|max:500',
        ]);
        $data['slug'] = Str::slug($data['nom']);
        Metier::create($data);

        return redirect()->route('admin.metiers.index')->with('success', 'Métier ajouté.');
    }

    public function edit(Metier $metier)
    {
        return view('admin.referentiels.form', $this->viewData(['item' => $metier]));
    }

    public function update(Request $request, Metier $metier)
    {
        $data = $request->validate([
            'nom'         => "required|string|max:200|unique:metiers,nom,{$metier->id}",
            'description' => 'nullable|string|max:500',
        ]);
        $data['slug'] = Str::slug($data['nom']);
        $metier->update($data);

        return redirect()->route('admin.metiers.index')->with('success', 'Métier mis à jour.');
    }

    public function destroy(Metier $metier)
    {
        try {
            $metier->delete();
            return redirect()->route('admin.metiers.index')->with('success', 'Métier supprimé.');
        } catch (QueryException) {
            return redirect()->route('admin.metiers.index')
                ->with('error', 'Impossible de supprimer : ce métier est utilisé par des candidats.');
        }
    }
}
