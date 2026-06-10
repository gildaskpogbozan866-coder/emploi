<?php

namespace App\Http\Controllers\Admin\Referentiel;

use App\Http\Controllers\Controller;
use App\Models\SecteurActivite;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class SecteursActiviteController extends Controller
{
    private function viewData(array $extra = []): array
    {
        return array_merge([
            'label'        => "Secteurs d'activité",
            'singular'     => "secteur d'activité",
            'hasNom'       => false,
            'hasCode'      => true,
            'hasDesc'      => false,
            'hasOrdre'     => false,
            'countKey'     => 'candidats_count',
            'routeIndex'   => 'admin.secteurs-activite.index',
            'routeCreate'  => 'admin.secteurs-activite.create',
            'routeStore'   => 'admin.secteurs-activite.store',
            'routeEdit'    => 'admin.secteurs-activite.edit',
            'routeUpdate'  => 'admin.secteurs-activite.update',
            'routeDestroy' => 'admin.secteurs-activite.destroy',
        ], $extra);
    }

    public function index()
    {
        return view('admin.referentiels.index', $this->viewData([
            'items' => SecteurActivite::withCount('candidats')->orderBy('libelle')->get(),
        ]));
    }

    public function create()
    {
        return view('admin.referentiels.form', $this->viewData(['item' => null]));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code'    => 'required|string|max:50|unique:secteurs_activite,code',
            'libelle' => 'required|string|max:200',
        ]);
        SecteurActivite::create($data);

        return redirect()->route('admin.secteurs-activite.index')->with('success', "Secteur d'activité ajouté.");
    }

    public function edit(SecteurActivite $secteurActivite)
    {
        return view('admin.referentiels.form', $this->viewData(['item' => $secteurActivite]));
    }

    public function update(Request $request, SecteurActivite $secteurActivite)
    {
        $data = $request->validate([
            'code'    => "required|string|max:50|unique:secteurs_activite,code,{$secteurActivite->id}",
            'libelle' => 'required|string|max:200',
        ]);
        $secteurActivite->update($data);

        return redirect()->route('admin.secteurs-activite.index')->with('success', "Secteur d'activité mis à jour.");
    }

    public function destroy(SecteurActivite $secteurActivite)
    {
        try {
            $secteurActivite->delete();
            return redirect()->route('admin.secteurs-activite.index')->with('success', "Secteur d'activité supprimé.");
        } catch (QueryException) {
            return redirect()->route('admin.secteurs-activite.index')
                ->with('error', "Impossible de supprimer : ce secteur est utilisé par des candidats.");
        }
    }
}
