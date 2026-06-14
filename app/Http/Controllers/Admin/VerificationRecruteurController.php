<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RecruteurDocument;
use App\Models\RecruteurVerification;
use Illuminate\Http\Request;
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

        return redirect()->route('admin.verifications.show', $verification)
            ->with('success', 'Compte recruteur approuvé. L\'entreprise a maintenant accès au dashboard.');
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

        return redirect()->route('admin.verifications.show', $verification)
            ->with('success', 'Dossier rejeté. Le recruteur en sera informé.');
    }

    public function servirDocument(RecruteurDocument $document)
    {
        abort_if(!$document->fichier, 404);
        abort_if(!Storage::disk('local')->exists($document->fichier), 404);

        return Storage::disk('local')->response($document->fichier);
    }
}
