<?php

namespace App\Http\Controllers\Recruteur;

use App\Enums\StatutCandidature;
use App\Http\Controllers\Controller;
use App\Models\Candidature;
use App\Models\CandidatureHistorique;
use App\Models\Notification;
use App\Notifications\CandidatureStatutNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CandidatureController extends Controller
{
    private function scopedQuery(Request $request)
    {
        $offresIds = Auth::user()->offres()->pluck('id');

        $query = Candidature::whereIn('offre_id', $offresIds)
            ->with(['candidat', 'offre']);

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

        return $query;
    }

    public function index(Request $request)
    {
        $counts = ['toutes' => $this->scopedQuery($request)->count()];
        foreach (StatutCandidature::cases() as $case) {
            $counts[$case->value] = $this->scopedQuery($request)->where('statut', $case->value)->count();
        }

        $query = $this->scopedQuery($request);

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
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

        return view('recruteur.candidatures', compact('candidatures', 'offres', 'counts'));
    }

    public function export(Request $request): StreamedResponse
    {
        $query = $this->scopedQuery($request);

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
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
            'documents.type',
            'candidat.candidatProfil',
            'candidat.competences',
            'candidat.experiences',
            'candidat.formations',
            'candidat.languesCandidats.langue',
            'candidat.languesCandidats.niveau',
            'candidat.typesContrats',
            'candidat.niveauEtude.niveauEtude',
            'candidat.niveauExperience.niveauExperience',
            'candidat.cvs',
        ]);

        if ($candidature->statut === 'envoyee') {
            $candidature->update(['statut' => 'vue']);
            CandidatureHistorique::create([
                'candidature_id' => $candidature->id,
                'recruteur_id'   => Auth::id(),
                'statut'         => 'vue',
                'note'           => null,
            ]);
        }

        $historiques = $candidature->historiques()->with('recruteur')->get();

        return view('recruteur.candidature-detail', compact('candidature', 'historiques'));
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

        // Le champ note est toujours vide dans le formulaire (une note par mise à
        // jour, voir l'historique) : si le recruteur ne retape rien, on ne doit
        // pas écraser la dernière note connue (affichée à l'admin et au candidat)
        // avec du vide.
        $candidature->update([
            'statut'         => $request->statut,
            'note_recruteur' => $request->filled('note_recruteur') ? $request->note_recruteur : $candidature->note_recruteur,
        ]);

        CandidatureHistorique::create([
            'candidature_id' => $candidature->id,
            'recruteur_id'   => Auth::id(),
            'statut'         => $request->statut,
            'note'           => $request->note_recruteur,
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

        $message = $ancienStatut !== $request->statut
            ? 'Statut mis à jour et candidat notifié.'
            : 'Note enregistrée.';

        return redirect()->route('recruteur.candidatures.show', $candidature)->with('success', $message);
    }
}
