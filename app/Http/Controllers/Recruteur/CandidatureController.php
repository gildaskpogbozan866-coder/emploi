<?php

namespace App\Http\Controllers\Recruteur;

use App\Http\Controllers\Controller;
use App\Models\Candidature;
use App\Models\Notification;
use App\Notifications\CandidatureStatutNotification;
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
        $candidature->load(['candidat', 'offre', 'cv']);

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

        $candidature->load('offre', 'candidat');

        $candidature->update([
            'statut'         => $request->statut,
            'note_recruteur' => $request->note_recruteur,
        ]);

        $statutLabel = [
            'envoyee'   => 'Envoyée',
            'vue'       => 'Vue',
            'retenue'   => 'Retenue',
            'entretien' => 'Entretien',
            'refusee'   => 'Refusée',
        ][$request->statut] ?? ucfirst($request->statut);

        // Notification in-app
        Notification::create([
            'user_id' => $candidature->candidat_id,
            'type'    => 'candidature',
            'titre'   => 'Statut de candidature mis à jour',
            'contenu' => "Votre candidature pour « {$candidature->offre->titre} » est maintenant : {$statutLabel}.",
            'lien'    => route('candidat.candidatures.detail', $candidature),
        ]);

        // Email au candidat
        $candidature->candidat->notify(new CandidatureStatutNotification(
            $candidature,
            $request->statut,
            $request->note_recruteur,
        ));

        return redirect()->route('recruteur.candidatures')->with('success', 'Statut mis à jour et candidat notifié par e-mail.');
    }
}
