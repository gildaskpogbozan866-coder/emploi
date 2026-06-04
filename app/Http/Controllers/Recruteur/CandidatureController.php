<?php

namespace App\Http\Controllers\Recruteur;

use App\Http\Controllers\Controller;
use App\Models\Candidature;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CandidatureController extends Controller
{
    public function index(Request $request)
    {
        $offresIds = Auth::user()->offres()->pluck('id');

        $query = Candidature::whereIn('offre_id', $offresIds)
            ->with(['candidat', 'offre'])
            ->latest();

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('offre_id')) {
            $query->where('offre_id', $request->offre_id);
        }

        $candidatures = $query->paginate(20)->withQueryString();
        $offres       = Auth::user()->offres()->pluck('titre', 'id');

        return view('recruteur.candidatures', compact('candidatures', 'offres'));
    }

    public function show(Candidature $candidature)
    {
        abort_if(!Auth::user()->offres()->where('id', $candidature->offre_id)->exists(), 403);
        $candidature->load(['candidat.cvs', 'offre']);

        // Marquer comme vue si elle vient d'être ouverte
        if ($candidature->statut === 'envoyee') {
            $candidature->update(['statut' => 'vue']);
        }

        return view('recruteur.candidature-detail', compact('candidature'));
    }

    public function updateStatut(Request $request, Candidature $candidature)
    {
        abort_if(!Auth::user()->offres()->where('id', $candidature->offre_id)->exists(), 403);

        $request->validate([
            'statut'        => 'required|in:envoyee,vue,retenue,refusee,entretien',
            'note_recruteur'=> 'nullable|string|max:1000',
        ]);

        $candidature->update([
            'statut'         => $request->statut,
            'note_recruteur' => $request->note_recruteur,
        ]);

        // Notifier le candidat
        Notification::create([
            'user_id' => $candidature->candidat_id,
            'type'    => 'candidature',
            'titre'   => 'Statut de candidature mis à jour',
            'contenu' => "Votre candidature pour « {$candidature->offre->titre} » a été marquée comme : " . ucfirst($request->statut),
            'lien'    => route('candidat.candidatures.detail', $candidature),
        ]);

        return back()->with('success', 'Statut mis à jour et candidat notifié.');
    }
}
