<?php

namespace App\Http\Controllers\Admin\Referentiel;

use App\Http\Controllers\Controller;
use App\Models\Disponibilite;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class DisponibilitesController extends Controller
{
    private function viewData(array $extra = []): array
    {
        return array_merge([
            'label'        => 'Disponibilités CVthèque',
            'singular'     => 'disponibilité',
            'hasNom'       => false,
            'hasCode'      => true,
            'hasDesc'      => false,
            'hasOrdre'     => true,
            'countKey'     => null,
            'routeIndex'   => 'admin.disponibilites.index',
            'routeCreate'  => 'admin.disponibilites.create',
            'routeStore'   => 'admin.disponibilites.store',
            'routeEdit'    => 'admin.disponibilites.edit',
            'routeUpdate'  => 'admin.disponibilites.update',
            'routeDestroy' => 'admin.disponibilites.destroy',
        ], $extra);
    }

    public function index()
    {
        return view('admin.referentiels.index', $this->viewData([
            'items' => Disponibilite::orderBy('ordre')->get(),
        ]));
    }

    public function create()
    {
        return view('admin.referentiels.form', $this->viewData(['item' => null]));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code'    => 'required|string|max:50|unique:disponibilites,code',
            'libelle' => 'required|string|max:100',
            'ordre'   => 'required|integer|min:1',
        ]);
        Disponibilite::create($data);
        \Cache::forget('disponibilites_list');

        return redirect()->route('admin.disponibilites.index')->with('success', 'Disponibilité ajoutée.');
    }

    public function edit(Disponibilite $disponibilite)
    {
        return view('admin.referentiels.form', $this->viewData(['item' => $disponibilite]));
    }

    public function update(Request $request, Disponibilite $disponibilite)
    {
        $data = $request->validate([
            'code'    => "required|string|max:50|unique:disponibilites,code,{$disponibilite->id}",
            'libelle' => 'required|string|max:100',
            'ordre'   => 'required|integer|min:1',
        ]);
        $disponibilite->update($data);
        \Cache::forget('disponibilites_list');

        return redirect()->route('admin.disponibilites.index')->with('success', 'Disponibilité mise à jour.');
    }

    public function destroy(Disponibilite $disponibilite)
    {
        try {
            $disponibilite->delete();
            \Cache::forget('disponibilites_list');
            return response()->json(['success' => true]);
        } catch (QueryException) {
            return response()->json(['success' => false, 'message' => 'Impossible de supprimer cette disponibilité.'], 422);
        }
    }
}
