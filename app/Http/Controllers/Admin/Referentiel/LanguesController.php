<?php

namespace App\Http\Controllers\Admin\Referentiel;

use App\Http\Controllers\Controller;
use App\Models\Langue;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class LanguesController extends Controller
{
    private function viewData(array $extra = []): array
    {
        return array_merge([
            'label'        => 'Langues',
            'singular'     => 'langue',
            'hasNom'       => true,
            'hasCode'      => false,
            'hasDesc'      => false,
            'hasOrdre'     => false,
            'countKey'     => 'candidats_count',
            'routeIndex'   => 'admin.langues.index',
            'routeCreate'  => 'admin.langues.create',
            'routeStore'   => 'admin.langues.store',
            'routeEdit'    => 'admin.langues.edit',
            'routeUpdate'  => 'admin.langues.update',
            'routeDestroy' => 'admin.langues.destroy',
        ], $extra);
    }

    public function index()
    {
        return view('admin.referentiels.index', $this->viewData([
            'items' => Langue::withCount('candidats')->orderBy('nom')->get(),
        ]));
    }

    public function create()
    {
        return view('admin.referentiels.form', $this->viewData(['item' => null]));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom' => 'required|string|max:100|unique:langues,nom',
        ]);
        Langue::create($data);

        return redirect()->route('admin.langues.index')->with('success', 'Langue ajoutée.');
    }

    public function edit(Langue $langue)
    {
        return view('admin.referentiels.form', $this->viewData(['item' => $langue]));
    }

    public function update(Request $request, Langue $langue)
    {
        $data = $request->validate([
            'nom' => "required|string|max:100|unique:langues,nom,{$langue->id}",
        ]);
        $langue->update($data);

        return redirect()->route('admin.langues.index')->with('success', 'Langue mise à jour.');
    }

    public function destroy(Langue $langue)
    {
        try {
            $langue->delete();
            return response()->json(['success' => true]);
        } catch (QueryException) {
            return response()->json(['success' => false, 'message' => 'Impossible de supprimer : cette langue est utilisée par des candidats.'], 422);
        }
    }
}
