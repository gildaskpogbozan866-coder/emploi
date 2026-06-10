<?php

namespace App\Http\Controllers\Admin\Referentiel;

use App\Http\Controllers\Controller;
use App\Models\Competence;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CompetencesController extends Controller
{
    private function viewData(array $extra = []): array
    {
        return array_merge([
            'label'        => 'Compétences',
            'singular'     => 'compétence',
            'hasNom'       => true,
            'hasCode'      => false,
            'hasDesc'      => false,
            'hasOrdre'     => false,
            'countKey'     => 'candidats_count',
            'routeIndex'   => 'admin.competences.index',
            'routeCreate'  => 'admin.competences.create',
            'routeStore'   => 'admin.competences.store',
            'routeEdit'    => 'admin.competences.edit',
            'routeUpdate'  => 'admin.competences.update',
            'routeDestroy' => 'admin.competences.destroy',
        ], $extra);
    }

    public function index()
    {
        return view('admin.referentiels.index', $this->viewData([
            'items' => Competence::withCount('candidats')->orderBy('nom')->get(),
        ]));
    }

    public function create()
    {
        return view('admin.referentiels.form', $this->viewData(['item' => null]));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:200|unique:competences,nom',
        ]);
        $data['slug'] = Str::slug($data['nom']);
        Competence::create($data);

        return redirect()->route('admin.competences.index')->with('success', 'Compétence ajoutée.');
    }

    public function edit(Competence $competence)
    {
        return view('admin.referentiels.form', $this->viewData(['item' => $competence]));
    }

    public function update(Request $request, Competence $competence)
    {
        $data = $request->validate([
            'nom' => "required|string|max:200|unique:competences,nom,{$competence->id}",
        ]);
        $data['slug'] = Str::slug($data['nom']);
        $competence->update($data);

        return redirect()->route('admin.competences.index')->with('success', 'Compétence mise à jour.');
    }

    public function destroy(Competence $competence)
    {
        try {
            $competence->delete();
            return redirect()->route('admin.competences.index')->with('success', 'Compétence supprimée.');
        } catch (QueryException) {
            return redirect()->route('admin.competences.index')
                ->with('error', 'Impossible de supprimer : cette compétence est utilisée par des candidats.');
        }
    }
}
