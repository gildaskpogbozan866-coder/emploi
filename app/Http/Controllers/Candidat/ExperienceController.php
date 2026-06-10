<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use App\Http\Requests\Candidat\ExperienceRequest;
use App\Models\Experience;
use Illuminate\Support\Facades\Auth;

class ExperienceController extends Controller
{
    public function store(ExperienceRequest $request)
    {
        $data = $request->all();

        $data['candidat_id'] = Auth::id();
        $data['en_cours']    = $request->boolean('en_cours');
        if ($data['en_cours']) {
            $data['date_fin'] = null;
        }
        $data['missions'] = array_values(array_filter($data['missions'] ?? [], fn($m) => trim($m ?? '') !== ''));

        $exp = Experience::create($data);

        return response()->json(['success' => true, 'experience' => $exp], 201);
    }

    public function update(ExperienceRequest $request, Experience $experience)
    {
        $this->authorize('modify', $experience);

        $data = $request->all();

        $data['en_cours'] = $request->boolean('en_cours');
        if ($data['en_cours']) {
            $data['date_fin'] = null;
        }
        $data['missions'] = array_values(array_filter($data['missions'] ?? [], fn($m) => trim($m ?? '') !== ''));

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
