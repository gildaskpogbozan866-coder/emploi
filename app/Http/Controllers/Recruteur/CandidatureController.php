<?php

namespace App\Http\Controllers\Recruteur;

use App\Enums\StatutCandidature;
use App\Http\Controllers\Controller;
use App\Models\Candidature;
use App\Models\Notification;
use App\Notifications\CandidatureStatutNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CandidatureController extends Controller
{
    public function index(Request $request)
    {
        $offresIds = Auth::user()->offres()->pluck('id');

        $query = Candidature::whereIn('offre_id', $offresIds)
            ->with(['candidat', 'offre']);

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        if ($request->filled('offre_id')) {
            $query->where('offre_id', $request->offre_id);
        }

        if ($request->filled('q')) {
            $q = $request->q;
            $query->whereHas('candidat', fn($sq) =>
                $sq->where('prenom', 'like', "%$q%")
                   ->orWhere('nom', 'like', "%$q%")
                   ->orWhere('email', 'like', "%$q%")
            );
        }

        $tri = $request->input('tri', 'recent');
        match($tri) {
            'ancien'  => $query->oldest(),
            'statut'  => $query->orderBy('statut'),
            'nom'     => $query->join('users', 'users.id', '=', 'candidatures.candidat_id')
                               ->orderBy('users.nom')->select('candidatures.*'),
            default   => $query->latest(),
        };

        $candidatures = $query->paginate(20)->withQueryString();
        $offres       = Auth::user()->offres()->pluck('titre', 'id');

        return view('recruteur.candidatures', compact('candidatures', 'offres'));
    }

    public function export(Request $request): StreamedResponse
    {
        $offresIds = Auth::user()->offres()->pluck('id');

        $query = Candidature::whereIn('offre_id', $offresIds)
            ->with(['candidat', 'offre']);

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        if ($request->filled('offre_id')) {
            $query->where('offre_id', $request->offre_id);
        }
        if ($request->filled('q')) {
            $q = $request->q;
            $query->whereHas('candidat', fn($sq) =>
                $sq->where('prenom', 'like', "%$q%")
                   ->orWhere('nom', 'like', "%$q%")
                   ->orWhere('email', 'like', "%$q%")
            );
        }

        $tri = $request->input('tri', 'recent');
        match($tri) {
            'ancien'  => $query->oldest(),
            'statut'  => $query->orderBy('statut'),
            'nom'     => $query->join('users', 'users.id', '=', 'candidatures.candidat_id')
                               ->orderBy('users.nom')->select('candidatures.*'),
            default   => $query->latest(),
        };

        $candidatures = $query->get();
        $filename = 'candidatures_' . now()->format('Y-m-d') . '.csv';

        return response()->streamDownload(function () use ($candidatures) {
            $handle = fopen('php://output', 'w');
            // BOM pour Excel (UTF-8)
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, ['Prénom', 'Nom', 'Email', 'Téléphone', 'Offre', 'Statut', 'CV joint', 'Date candidature'], ';');

            foreach ($candidatures as $c) {
                $statut = StatutCandidature::tryFrom($c->statut)?->label() ?? ucfirst($c->statut);
                fputcsv($handle, [
                    $c->candidat->prenom ?? '',
                    $c->candidat->nom    ?? '',
                    $c->candidat->email  ?? '',
                    $c->candidat->telephone ?? '',
                    $c->offre->titre     ?? '',
                    $statut,
                    $c->cv_id ? 'Oui' : 'Non',
                    $c->created_at->format('d/m/Y H:i'),
                ], ';');
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    public function show(Candidature $candidature)
    {
        $this->authorize('view', $candidature);

        $candidature->load([
            'offre',
            'cv',
            'candidat.candidatProfil',
            'candidat.competences',
            'candidat.experiences',
            'candidat.formations',
            'candidat.languesCandidats.langue',
            'candidat.languesCandidats.niveau',
            'candidat.typesContrats',
            'candidat.niveauEtude.niveauEtude',
            'candidat.niveauExperience.niveauExperience',
            'candidat.documents.type',
            'candidat.cvs',
        ]);

        if ($candidature->statut === 'envoyee') {
            $candidature->update(['statut' => 'vue']);
        }

        return view('recruteur.candidature-detail', compact('candidature'));
    }

    public function updateStatut(Request $request, Candidature $candidature)
    {
        $candidature->load('offre', 'candidat');
        $this->authorize('updateStatut', $candidature);

        $values = implode(',', array_column(StatutCandidature::cases(), 'value'));
        $request->validate([
            'statut'        => "required|in:{$values}",
            'note_recruteur'=> 'nullable|string|max:1000',
        ]);

        $ancienStatut = $candidature->statut;

        $candidature->update([
            'statut'         => $request->statut,
            'note_recruteur' => $request->note_recruteur,
        ]);

        if ($ancienStatut !== $request->statut) {
            $statutLabel = StatutCandidature::from($request->statut)->label();

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
        }

        return redirect()->route('recruteur.candidatures.show', $candidature)->with('success', 'Statut mis à jour.');
    }
}
