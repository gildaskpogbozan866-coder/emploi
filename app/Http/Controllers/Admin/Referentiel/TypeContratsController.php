<?php

namespace App\Http\Controllers\Admin\Referentiel;

use App\Http\Controllers\Controller;
use App\Models\TypeContrat;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class TypeContratsController extends Controller
{
    private function viewData(array $extra = []): array
    {
        return array_merge([
            'label'        => 'Types de contrat',
            'singular'     => 'type de contrat',
            'hasNom'       => false,
            'hasCode'      => true,
            'hasDesc'      => false,
            'hasOrdre'     => false,
            'countKey'     => 'candidats_count',
            'routeIndex'   => 'admin.types-contrat.index',
            'routeCreate'  => 'admin.types-contrat.create',
            'routeStore'   => 'admin.types-contrat.store',
            'routeEdit'    => 'admin.types-contrat.edit',
            'routeUpdate'  => 'admin.types-contrat.update',
            'routeDestroy' => 'admin.types-contrat.destroy',
        ], $extra);
    }

    public function index()
    {
        return view('admin.referentiels.index', $this->viewData([
            'items' => TypeContrat::withCount('candidats')->orderBy('libelle')->get(),
        ]));
    }

    public function create()
    {
        return view('admin.referentiels.form', $this->viewData(['item' => null]));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code'    => 'required|string|max:50|unique:type_contrats,code',
            'libelle' => 'required|string|max:200',
        ]);
        TypeContrat::create($data);

        return redirect()->route('admin.types-contrat.index')->with('success', 'Type de contrat ajouté.');
    }

    public function edit(TypeContrat $typeContrat)
    {
        return view('admin.referentiels.form', $this->viewData(['item' => $typeContrat]));
    }

    public function update(Request $request, TypeContrat $typeContrat)
    {
        $data = $request->validate([
            'code'    => "required|string|max:50|unique:type_contrats,code,{$typeContrat->id}",
            'libelle' => 'required|string|max:200',
        ]);
        $typeContrat->update($data);

        return redirect()->route('admin.types-contrat.index')->with('success', 'Type de contrat mis à jour.');
    }

    public function destroy(TypeContrat $typeContrat)
    {
        try {
            $typeContrat->delete();
            return response()->json(['success' => true]);
        } catch (QueryException) {
            return response()->json(['success' => false, 'message' => 'Impossible de supprimer : ce type de contrat est utilisé par des candidats.'], 422);
        }
    }
}
