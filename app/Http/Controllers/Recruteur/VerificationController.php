<?php

namespace App\Http\Controllers\Recruteur;

use App\Http\Controllers\Controller;
use App\Models\ParametreApp;
use App\Notifications\NouvelleVerificationRecruteur;
use Illuminate\Http\Request;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;

class VerificationController extends Controller
{
    public function soumettre()
    {
        $verification = auth()->user()->recruteurVerification;

        if ($verification?->estApprouve()) {
            return redirect()->route('recruteur.dashboard');
        }

        return view('recruteur.verification.soumettre', compact('verification'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'carte_biometrique' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'cip'               => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'ifu_fichier'       => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'ifu_numero'        => 'nullable|string|max:50',
            'rccm_fichier'      => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'rccm_numero'       => 'nullable|string|max:100',
        ], [
            'carte_biometrique.mimes' => 'Format accepté : PDF, JPG, PNG (max 5 Mo).',
            'cip.mimes'               => 'Format accepté : PDF, JPG, PNG (max 5 Mo).',
        ]);

        $user     = auth()->user();
        $existing = $user->recruteurVerification;

        // Au moins une pièce d'identité (biométrique OU CIP)
        $aIdentite = $request->hasFile('carte_biometrique')
                  || $request->hasFile('cip')
                  || $existing?->carte_biometrique
                  || $existing?->cip;

        if (! $aIdentite) {
            return back()
                ->withErrors(['carte_biometrique' => 'Veuillez fournir votre carte biométrique ou votre CIP.'])
                ->withInput();
        }

        // Au moins un justificatif d'entreprise (IFU ou RCCM)
        $aEntreprise = $request->hasFile('ifu_fichier')
                    || filled($request->ifu_numero)
                    || $request->hasFile('rccm_fichier')
                    || filled($request->rccm_numero)
                    || $existing?->ifu_fichier
                    || $existing?->ifu_numero
                    || $existing?->rccm_fichier
                    || $existing?->rccm_numero;

        if (! $aEntreprise) {
            return back()
                ->withErrors(['ifu_numero' => 'Veuillez fournir au moins un IFU ou un RCCM (numéro ou document).'])
                ->withInput();
        }

        $basePath = "verifications/{$user->id}";

        $data = [
            'user_id'     => $user->id,
            'statut'      => 'en_attente',
            'note_admin'  => null,
            'reviewed_by' => null,
            'reviewed_at' => null,
            'ifu_numero'  => $request->ifu_numero,
            'rccm_numero' => $request->rccm_numero,
        ];

        foreach (['carte_biometrique', 'cip', 'ifu_fichier', 'rccm_fichier'] as $field) {
            if ($request->hasFile($field)) {
                if ($existing?->$field) {
                    Storage::disk('local')->delete($existing->$field);
                }
                $data[$field] = $request->file($field)->store($basePath, 'local');
            } else {
                $data[$field] = $existing?->$field;
            }
        }

        $verification = $user->recruteurVerification()->updateOrCreate(['user_id' => $user->id], $data);

        // Alerter l'admin par email
        $adminEmail = ParametreApp::get('admin_notification_email', config('emploi.admin_notification_email'));
        if ($adminEmail) {
            Notification::route('mail', $adminEmail)
                ->notify(new NouvelleVerificationRecruteur($verification));
        }

        return redirect()->route('recruteur.verification.en-attente');
    }

    public function enAttente()
    {
        $verification = auth()->user()->recruteurVerification;

        if ($verification?->estApprouve()) {
            return redirect()->route('recruteur.dashboard');
        }

        return view('recruteur.verification.en-attente');
    }

    public function rejete()
    {
        $verification = auth()->user()->recruteurVerification;
        return view('recruteur.verification.rejete', compact('verification'));
    }
}
