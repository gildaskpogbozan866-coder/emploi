<?php

namespace App\Http\Controllers\Recruteur;

use App\Http\Controllers\Controller;
use App\Models\ParametreApp;
use App\Models\RecruteurDocument;
use App\Models\RecruteurDocumentType;
use App\Notifications\NouvelleVerificationRecruteur;
use Illuminate\Http\Request;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class VerificationController extends Controller
{
    public function soumettre()
    {
        if (ParametreApp::get('recruteur_validation_docs', '0') !== '1') {
            return redirect()->route('recruteur.dashboard');
        }

        $user         = auth()->user();
        $verification = $user->recruteurVerification;

        if ($verification?->estApprouve()) {
            return redirect()->route('recruteur.dashboard');
        }

        $types     = RecruteurDocumentType::actifs()->get();
        $existants = RecruteurDocument::where('user_id', $user->id)
                        ->with('type')
                        ->get()
                        ->keyBy('type_id');

        return view('recruteur.verification.soumettre', compact('verification', 'types', 'existants'));
    }

    public function store(Request $request)
    {
        $user  = auth()->user();
        $types = RecruteurDocumentType::actifs()->get();

        // Validation dynamique
        $rules = [];
        foreach ($types as $type) {
            if ($type->accepte_fichier) {
                $rules["fichier_{$type->id}"] = 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120';
            }
            if ($type->accepte_texte) {
                $rules["texte_{$type->id}"] = 'nullable|string|max:500';
            }
        }
        $request->validate($rules);

        // Vérifier que les types requis ont au moins une valeur
        $existants = RecruteurDocument::where('user_id', $user->id)->get()->keyBy('type_id');
        $erreurs   = [];

        foreach ($types as $type) {
            if (!$type->est_requis) continue;

            $aFichier = $request->hasFile("fichier_{$type->id}") || $existants[$type->id]?->fichier;
            $aTexte   = $type->accepte_texte && filled($request->input("texte_{$type->id}"));

            if (!$aFichier && !$aTexte) {
                $erreurs["fichier_{$type->id}"] = "Le document « {$type->nom} » est obligatoire.";
            }
        }

        if ($erreurs) {
            return back()->withErrors($erreurs)->withInput();
        }

        // Sauvegarde
        $basePath = "verifications/{$user->id}";

        foreach ($types as $type) {
            $existant = $existants[$type->id] ?? null;
            $data     = ['user_id' => $user->id, 'type_id' => $type->id];

            if ($type->accepte_fichier && $request->hasFile("fichier_{$type->id}")) {
                if ($existant?->fichier) {
                    Storage::disk('local')->delete($existant->fichier);
                }
                $data['fichier'] = $request->file("fichier_{$type->id}")->store($basePath, 'local');
            }

            if ($type->accepte_texte) {
                $data['texte'] = $request->input("texte_{$type->id}") ?? $existant?->texte;
            }

            RecruteurDocument::updateOrCreate(
                ['user_id' => $user->id, 'type_id' => $type->id],
                $data
            );
        }

        $verification = $user->recruteurVerification()->updateOrCreate(
            ['user_id' => $user->id],
            ['statut' => 'en_attente', 'note_admin' => null, 'reviewed_by' => null, 'reviewed_at' => null]
        );

        $adminEmail = ParametreApp::get('admin_notification_email', config('emploi.admin_notification_email'));
        if ($adminEmail) {
            Notification::route('mail', $adminEmail)
                ->notify(new NouvelleVerificationRecruteur($verification));
        }

        return redirect()->route('recruteur.verification.en-attente');
    }

    public function enAttente()
    {
        if (ParametreApp::get('recruteur_validation_docs', '0') !== '1') {
            return redirect()->route('recruteur.dashboard');
        }

        $verification = auth()->user()->recruteurVerification;

        if ($verification?->estApprouve()) {
            return redirect()->route('recruteur.dashboard');
        }

        return view('recruteur.verification.en-attente');
    }

    public function rejete()
    {
        if (ParametreApp::get('recruteur_validation_docs', '0') !== '1') {
            return redirect()->route('recruteur.dashboard');
        }

        $verification = auth()->user()->recruteurVerification;
        return view('recruteur.verification.rejete', compact('verification'));
    }
}
