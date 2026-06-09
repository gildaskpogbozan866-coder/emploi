<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use App\Models\Formation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FormationController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'diplome'       => 'required|string|max:200',
            'etablissement' => 'required|string|max:200',
            'domaine'       => 'nullable|string|max:150',
            'date_debut'    => 'required|date|before_or_equal:today',
            'date_fin'      => 'nullable|date|after_or_equal:date_debut',
            'en_cours'      => 'boolean',
            'activites'     => 'nullable|array|max:20',
            'activites.*'   => 'nullable|string|max:500',
        ]);

        $data['candidat_id'] = Auth::id();
        $data['en_cours']    = $request->boolean('en_cours');
        if ($data['en_cours']) {
            $data['date_fin'] = null;
        }
        $data['activites'] = array_values(array_filter($data['activites'] ?? [], fn($a) => trim($a ?? '') !== ''));

        $formation = Formation::create($data);

        return response()->json(['success' => true, 'formation' => $formation], 201);
    }

    public function update(Request $request, Formation $formation)
    {
        $this->authorize('modify', $formation);

        $data = $request->validate([
            'diplome'       => 'required|string|max:200',
            'etablissement' => 'required|string|max:200',
            'domaine'       => 'nullable|string|max:150',
            'date_debut'    => 'required|date|before_or_equal:today',
            'date_fin'      => 'nullable|date|after_or_equal:date_debut',
            'en_cours'      => 'boolean',
            'activites'     => 'nullable|array|max:20',
            'activites.*'   => 'nullable|string|max:500',
        ]);

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
