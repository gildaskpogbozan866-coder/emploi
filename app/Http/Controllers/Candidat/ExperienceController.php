<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use App\Models\Experience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExperienceController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'poste'       => 'required|string|max:200',
            'entreprise'  => 'required|string|max:200',
            'lieu'        => 'nullable|string|max:150',
            'secteur'     => 'nullable|string|max:150',
            'date_debut'  => 'required|date|before_or_equal:today',
            'date_fin'    => 'nullable|date|after_or_equal:date_debut',
            'en_cours'    => 'boolean',
            'description' => 'nullable|string|max:2000',
        ]);

        $data['candidat_id'] = Auth::id();
        $data['en_cours']    = $request->boolean('en_cours');
        if ($data['en_cours']) {
            $data['date_fin'] = null;
        }

        $exp = Experience::create($data);

        return response()->json(['success' => true, 'experience' => $exp]);
    }

    public function update(Request $request, Experience $experience)
    {
        $this->authorize('modify', $experience);

        $data = $request->validate([
            'poste'       => 'required|string|max:200',
            'entreprise'  => 'required|string|max:200',
            'lieu'        => 'nullable|string|max:150',
            'secteur'     => 'nullable|string|max:150',
            'date_debut'  => 'required|date|before_or_equal:today',
            'date_fin'    => 'nullable|date|after_or_equal:date_debut',
            'en_cours'    => 'boolean',
            'description' => 'nullable|string|max:2000',
        ]);

        $data['en_cours'] = $request->boolean('en_cours');
        if ($data['en_cours']) {
            $data['date_fin'] = null;
        }

        $experience->update($data);

        return response()->json(['success' => true, 'experience' => $experience->fresh()]);
    }

    public function destroy(Experience $experience)
    {
        $this->authorize('modify', $experience);
        $experience->delete();

        return response()->json(['success' => true]);
    }
}
