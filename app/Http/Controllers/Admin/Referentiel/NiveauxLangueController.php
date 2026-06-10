<?php

namespace App\Http\Controllers\Admin\Referentiel;

use App\Http\Controllers\Controller;
use App\Models\NiveauLangue;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class NiveauxLangueController extends Controller
{
    private function viewData(array $extra = []): array
    {
        return array_merge([
            'label'        => 'Niveaux de langue',
            'singular'     => 'niveau de langue',
            'hasNom'       => false,
            'hasCode'      => true,
            'hasDesc'      => false,
            'hasOrdre'     => true,
            'countKey'     => 'langues_candidats_count',
            'routeIndex'   => 'admin.niveaux-langue.index',
            'routeCreate'  => 'admin.niveaux-langue.create',
            'routeStore'   => 'admin.niveaux-langue.store',
            'routeEdit'    => 'admin.niveaux-langue.edit',
            'routeUpdate'  => 'admin.niveaux-langue.update',
            'routeDestroy' => 'admin.niveaux-langue.destroy',
        ], $extra);
    }

    public function index()
    {
        return view('admin.referentiels.index', $this->viewData([
            'items' => NiveauLangue::withCount('languesCandidats')->orderBy('ordre')->get(),
        ]));
    }

    public function create()
    {
        return view('admin.referentiels.form', $this->viewData(['item' => null]));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code'    => 'required|string|max:50|unique:niveaux_langue,code',
            'libelle' => 'required|string|max:200',
            'ordre'   => 'required|integer|min:1',
        ]);
        NiveauLangue::create($data);

        return redirect()->route('admin.niveaux-langue.index')->with('success', 'Niveau de langue ajouté.');
    }

    public function edit(NiveauLangue $niveauLangue)
    {
        return view('admin.referentiels.form', $this->viewData(['item' => $niveauLangue]));
    }

    public function update(Request $request, NiveauLangue $niveauLangue)
    {
        $data = $request->validate([
            'code'    => "required|string|max:50|unique:niveaux_langue,code,{$niveauLangue->id}",
            'libelle' => 'required|string|max:200',
            'ordre'   => 'required|integer|min:1',
        ]);
        $niveauLangue->update($data);

        return redirect()->route('admin.niveaux-langue.index')->with('success', 'Niveau de langue mis à jour.');
    }

    public function destroy(NiveauLangue $niveauLangue)
    {
        try {
            $niveauLangue->delete();
            return redirect()->route('admin.niveaux-langue.index')->with('success', 'Niveau de langue supprimé.');
        } catch (QueryException) {
            return redirect()->route('admin.niveaux-langue.index')
                ->with('error', 'Impossible de supprimer : ce niveau est utilisé par des candidats.');
        }
    }
}
