<?php

namespace App\Http\Controllers\Admin\Referentiel;

use App\Http\Controllers\Controller;
use App\Models\NiveauExperience;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class NiveauxExperienceController extends Controller
{
    private function viewData(array $extra = []): array
    {
        return array_merge([
            'label'        => "Niveaux d'expérience",
            'singular'     => "niveau d'expérience",
            'hasNom'       => false,
            'hasCode'      => true,
            'hasDesc'      => false,
            'hasOrdre'     => true,
            'countKey'     => 'candidats_count',
            'routeIndex'   => 'admin.niveaux-experience.index',
            'routeCreate'  => 'admin.niveaux-experience.create',
            'routeStore'   => 'admin.niveaux-experience.store',
            'routeEdit'    => 'admin.niveaux-experience.edit',
            'routeUpdate'  => 'admin.niveaux-experience.update',
            'routeDestroy' => 'admin.niveaux-experience.destroy',
        ], $extra);
    }

    public function index()
    {
        return view('admin.referentiels.index', $this->viewData([
            'items' => NiveauExperience::withCount('candidats')->orderBy('ordre')->get(),
        ]));
    }

    public function create()
    {
        return view('admin.referentiels.form', $this->viewData(['item' => null]));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code'    => 'required|string|max:50|unique:niveaux_experience,code',
            'libelle' => 'required|string|max:200',
            'ordre'   => 'required|integer|min:1',
        ]);
        NiveauExperience::create($data);

        return redirect()->route('admin.niveaux-experience.index')->with('success', "Niveau d'expérience ajouté.");
    }

    public function edit(NiveauExperience $niveauExperience)
    {
        return view('admin.referentiels.form', $this->viewData(['item' => $niveauExperience]));
    }

    public function update(Request $request, NiveauExperience $niveauExperience)
    {
        $data = $request->validate([
            'code'    => "required|string|max:50|unique:niveaux_experience,code,{$niveauExperience->id}",
            'libelle' => 'required|string|max:200',
            'ordre'   => 'required|integer|min:1',
        ]);
        $niveauExperience->update($data);

        return redirect()->route('admin.niveaux-experience.index')->with('success', "Niveau d'expérience mis à jour.");
    }

    public function destroy(NiveauExperience $niveauExperience)
    {
        try {
            $niveauExperience->delete();
            return response()->json(['success' => true]);
        } catch (QueryException) {
            return response()->json(['success' => false, 'message' => "Impossible de supprimer : ce niveau est utilisé par des candidats."], 422);
        }
    }
}
