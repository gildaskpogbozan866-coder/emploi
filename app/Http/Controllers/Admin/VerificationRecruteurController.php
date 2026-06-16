<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\RecruteurDocument;
use App\Models\RecruteurVerification;
use App\Notifications\VerificationApprouveeNotification;
use App\Notifications\VerificationRejeteNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class VerificationRecruteurController extends Controller
{
    public function index(Request $request)
    {
        $query = RecruteurVerification::with('user')
            ->orderByRaw("FIELD(statut, 'en_attente', 'rejete', 'approuve')")
            ->latest();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->whereHas('user', fn($sq) => $sq->where('prenom', 'like', "%$q%")
                ->orWhere('nom', 'like', "%$q%")
                ->orWhere('entreprise', 'like', "%$q%")
                ->orWhere('email', 'like', "%$q%"));
        }
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $counts = [
            'en_attente' => RecruteurVerification::where('statut', 'en_attente')->count(),
            'approuve'   => RecruteurVerification::where('statut', 'approuve')->count(),
            'rejete'     => RecruteurVerification::where('statut', 'rejete')->count(),
        ];

        $verifications = $query->paginate(20)->withQueryString();

        return view('admin.verifications.index', compact('verifications', 'counts'));
    }

    public function show(RecruteurVerification $verification)
    {
        $verification->load('user', 'reviewedBy');
        $documents = RecruteurDocument::where('user_id', $verification->user_id)
                        ->with('type')
                        ->get()
                        ->sortBy('type.ordre');

        return view('admin.verifications.show', compact('verification', 'documents'));
    }

    public function approuver(RecruteurVerification $verification)
    {
        $verification->update([
            'statut'      => 'approuve',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'note_admin'  => null,
        ]);

        $recruteur = $verification->user;

        // Notification in-app au recruteur
        try {
            Notification::create([
                'user_id' => $recruteur->id,
                'type'    => 'message',
                'titre'   => 'Dossier approuvé',
                'contenu' => 'Votre dossier de vérification a été approuvé. Vous avez maintenant accès à toutes les fonctionnalités recruteur.',
                'lien'    => route('recruteur.dashboard'),
            ]);
        } catch (\Throwable $e) {
            Log::warning('Notification in-app approbation non créée', ['error' => $e->getMessage()]);
        }

        // Email au recruteur
        try {
            $recruteur->notify(new VerificationApprouveeNotification($verification));
        } catch (\Throwable $e) {
            Log::warning('Email VerificationApprouvee non envoyé', ['error' => $e->getMessage()]);
        }

        return redirect()->route('admin.verifications.show', $verification)
            ->with('success', 'Compte recruteur approuvé. Le recruteur a été notifié par e-mail.');
    }

    public function rejeter(Request $request, RecruteurVerification $verification)
    {
        $request->validate([
            'note_admin' => 'required|string|max:1000',
        ], [
            'note_admin.required' => 'Veuillez indiquer le motif du rejet.',
        ]);

        $verification->update([
            'statut'      => 'rejete',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'note_admin'  => $request->note_admin,
        ]);

        $recruteur = $verification->user;

        // Notification in-app au recruteur
        try {
            Notification::create([
                'user_id' => $recruteur->id,
                'type'    => 'message',
                'titre'   => 'Dossier non approuvé',
                'contenu' => "Votre dossier de vérification n'a pas été approuvé. Motif : {$request->note_admin}",
                'lien'    => route('recruteur.verification'),
            ]);
        } catch (\Throwable $e) {
            Log::warning('Notification in-app rejet non créée', ['error' => $e->getMessage()]);
        }

        // Email au recruteur
        try {
            $recruteur->notify(new VerificationRejeteNotification($verification, $request->note_admin));
        } catch (\Throwable $e) {
            Log::warning('Email VerificationRejete non envoyé', ['error' => $e->getMessage()]);
        }

        return redirect()->route('admin.verifications.show', $verification)
            ->with('success', 'Dossier rejeté. Le recruteur a été notifié par e-mail.');
    }

    public function servirDocument(RecruteurDocument $document)
    {
        abort_if(!$document->fichier, 404);
        abort_if(!Storage::disk('local')->exists($document->fichier), 404);

        return Storage::disk('local')->response($document->fichier);
    }
}
