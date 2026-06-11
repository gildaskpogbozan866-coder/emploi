<?php

namespace App\Http\Controllers\Admin\Referentiel;

use App\Http\Controllers\Controller;
use App\Models\NiveauEtude;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class NiveauxEtudeController extends Controller
{
    private function viewData(array $extra = []): array
    {
        return array_merge([
            'label'        => "Niveaux d'étude",
            'singular'     => "niveau d'étude",
            'hasNom'       => false,
            'hasCode'      => true,
            'hasDesc'      => false,
            'hasOrdre'     => true,
            'countKey'     => 'candidats_count',
            'routeIndex'   => 'admin.niveaux-etude.index',
            'routeCreate'  => 'admin.niveaux-etude.create',
            'routeStore'   => 'admin.niveaux-etude.store',
            'routeEdit'    => 'admin.niveaux-etude.edit',
            'routeUpdate'  => 'admin.niveaux-etude.update',
            'routeDestroy' => 'admin.niveaux-etude.destroy',
        ], $extra);
    }

    public function index()
    {
        return view('admin.referentiels.index', $this->viewData([
            'items' => NiveauEtude::withCount('candidats')->orderBy('ordre')->get(),
        ]));
    }

    public function create()
    {
        return view('admin.referentiels.form', $this->viewData(['item' => null]));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code'    => 'required|string|max:50|unique:niveaux_etudes,code',
            'libelle' => 'required|string|max:200',
            'ordre'   => 'required|integer|min:1',
        ]);
        NiveauEtude::create($data);

        return redirect()->route('admin.niveaux-etude.index')->with('success', "Niveau d'étude ajouté.");
    }

    public function edit(NiveauEtude $niveauEtude)
    {
        return view('admin.referentiels.form', $this->viewData(['item' => $niveauEtude]));
    }

    public function update(Request $request, NiveauEtude $niveauEtude)
    {
        $data = $request->validate([
            'code'    => "required|string|max:50|unique:niveaux_etudes,code,{$niveauEtude->id}",
            'libelle' => 'required|string|max:200',
            'ordre'   => 'required|integer|min:1',
        ]);
        $niveauEtude->update($data);

        return redirect()->route('admin.niveaux-etude.index')->with('success', "Niveau d'étude mis à jour.");
    }

    public function destroy(NiveauEtude $niveauEtude)
    {
        try {
            $niveauEtude->delete();
            return response()->json(['success' => true]);
        } catch (QueryException) {
            return response()->json(['success' => false, 'message' => "Impossible de supprimer : ce niveau est utilisé par des candidats."], 422);
        }
    }
}
