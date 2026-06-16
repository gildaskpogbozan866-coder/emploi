<?php

namespace App\Http\Controllers\Admin\Referentiel;

use App\Http\Controllers\Controller;
use App\Models\Region;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class RegionsController extends Controller
{
    private function viewData(array $extra = []): array
    {
        return array_merge([
            'label'        => 'Régions / Localisations',
            'singular'     => 'région',
            'hasNom'       => true,
            'hasCode'      => false,
            'hasDesc'      => false,
            'hasOrdre'     => true,
            'countKey'     => null,
            'routeIndex'   => 'admin.regions.index',
            'routeCreate'  => 'admin.regions.create',
            'routeStore'   => 'admin.regions.store',
            'routeEdit'    => 'admin.regions.edit',
            'routeUpdate'  => 'admin.regions.update',
            'routeDestroy' => 'admin.regions.destroy',
        ], $extra);
    }

    public function index()
    {
        return view('admin.referentiels.index', $this->viewData([
            'items' => Region::orderBy('ordre')->orderBy('nom')->get(),
        ]));
    }

    public function create()
    {
        return view('admin.referentiels.form', $this->viewData(['item' => null]));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nom'   => 'required|string|max:100|unique:regions,nom',
            'ordre' => 'required|integer|min:1',
        ]);
        Region::create($data);
        \Cache::forget('regions_list');

        return redirect()->route('admin.regions.index')->with('success', 'Région ajoutée.');
    }

    public function edit(Region $region)
    {
        return view('admin.referentiels.form', $this->viewData(['item' => $region]));
    }

    public function update(Request $request, Region $region)
    {
        $data = $request->validate([
            'nom'   => "required|string|max:100|unique:regions,nom,{$region->id}",
            'ordre' => 'required|integer|min:1',
        ]);
        $region->update($data);
        \Cache::forget('regions_list');

        return redirect()->route('admin.regions.index')->with('success', 'Région mise à jour.');
    }

    public function destroy(Region $region)
    {
        try {
            $region->delete();
            \Cache::forget('regions_list');
            return response()->json(['success' => true]);
        } catch (QueryException) {
            return response()->json(['success' => false, 'message' => 'Impossible de supprimer cette région.'], 422);
        }
    }
}
