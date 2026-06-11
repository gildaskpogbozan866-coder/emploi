<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Candidat\FormationRequest;
use App\Models\Formation;
use Illuminate\Support\Facades\Auth;

class FormationController extends Controller
{
    public function store(FormationRequest $request)
    {
        $data = $request->all();

        $data['candidat_id'] = Auth::id();
        $data['en_cours']    = $request->boolean('en_cours');
        if ($data['en_cours']) {
            $data['date_fin'] = null;
        }
        $data['activites'] = array_values(array_filter($data['activites'] ?? [], fn($a) => trim($a ?? '') !== ''));

        $formation = Formation::create($data);

        return response()->json(['success' => true, 'formation' => $formation], 201);
    }

    public function update(FormationRequest $request, Formation $formation)
    {
        $this->authorize('modify', $formation);

        $data = $request->all();

        $data['en_cours'] = $request->boolean('en_cours');
        if ($data['en_cours']) {
            $data['date_fin'] = null;
        }
        $data['activites'] = array_values(array_filter($data['activites'] ?? [], fn($a) => trim($a ?? '') !== ''));

        $formation->update($data);

        return response()->json(['success' => true, 'formation' => $formation->fresh()]);
    }

    public function destroy(Formation $formation)
    {
        $this->authorize('modify', $formation);
        $formation->delete();

        return response()->json(['success' => true]);
    }
}
