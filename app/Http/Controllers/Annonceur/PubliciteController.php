<?php

namespace App\Http\Controllers\Annonceur;

use App\Http\Controllers\Controller;
use App\Models\JobPublicationPlan;
use App\Models\Notification;
use App\Models\Paiement;
use App\Models\Publicite;
use App\Models\User;
use App\Notifications\NouvellePubliciteAdminNotification;
use App\Notifications\PubliciteSoumiseNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PubliciteController extends Controller
{
    public function index()
    {
        $publicites = Publicite::where('user_id', Auth::id())
            ->where('statut', '!=', 'brouillon')
            ->with('plan')
            ->latest()
            ->get();

        $plans = JobPublicationPlan::where('is_active', true)->orderBy('price')->get();

        return view('annonceur.publicites', compact('publicites', 'plans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre'          => 'required|string|max:191',
            'image'          => 'required|image|mimes:jpg,jpeg,png,webp,gif|max:5120',
            'lien'           => 'nullable|url|max:500',
            'note_annonceur' => 'nullable|string|max:1000',
            'plan_id'        => 'required|integer|exists:job_publication_plans,id',
        ], [
            'image.image'    => 'Le fichier doit être une image (jpg, png, webp, gif).',
            'image.max'      => 'L\'image ne doit pas dépasser 5 Mo.',
            'lien.url'       => 'Le lien doit être une URL valide (commençant par http:// ou https://).',
            'plan_id.required' => 'Veuillez sélectionner un plan de diffusion.',
            'plan_id.exists'   => 'Le plan sélectionné est invalide.',
        ]);

        $plan = JobPublicationPlan::where('is_active', true)->findOrFail($validated['plan_id']);

        $path = $request->file('image')->store('publicites', 'public');

        $publicite = Publicite::create([
            'user_id'        => Auth::id(),
            'plan_id'        => $plan->id,
            'titre'          => $validated['titre'],
            'image'          => $path,
            'lien'           => $validated['lien'] ?? null,
            'note_annonceur' => $validated['note_annonceur'] ?? null,
            'date_debut'     => null,
            'date_fin'       => $plan->duration_days ? now()->addDays($plan->duration_days)->toDateString() : null,
            'statut'         => 'brouillon',
        ]);

        // Plan gratuit → soumission directe sans paiement
        if ($plan->is_free || $plan->price <= 0) {
            $publicite->update(['statut' => 'en_attente']);
            $this->notifierAdmins($publicite);
            $this->notifierAnnonceur($publicite);
            return redirect()->route('annonceur.publicites')
                ->with('success', 'Votre annonce a été soumise gratuitement et est en attente de validation.');
        }

        // Créer le paiement et rediriger vers le gateway
        $paiement = Paiement::create([
            'user_id'      => Auth::id(),
            'montant'      => $plan->price,
            'devise'       => 'XOF',
            'type'         => 'publicite',
            'payable_id'   => $publicite->id,
            'payable_type' => Publicite::class,
            'methode'      => 'en_attente',
            'statut'       => 'en_attente',
            'gateway'      => 'manuel',
        ]);

        return redirect()->route('payment.choose', ['paiement' => $paiement->id]);
    }

    public function destroy(Publicite $publicite)
    {
        abort_if($publicite->user_id !== Auth::id(), 403);

        Storage::disk('public')->delete($publicite->image);
        $publicite->delete();

        return back()->with('success', 'Annonce supprimée.');
    }

    private function notifierAdmins(Publicite $publicite): void
    {
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            try {
                Notification::create([
                    'user_id' => $admin->id,
                    'type'    => 'publicite',
                    'titre'   => 'Nouvelle publicité à valider',
                    'contenu' => Auth::user()->nom_complet . " a soumis une publicité : « {$publicite->titre} ».",
                    'lien'    => route('admin.publicites.show', $publicite),
                ]);
                $admin->notify(new NouvellePubliciteAdminNotification($publicite));
            } catch (\Throwable $e) {
                Log::warning('Notification admin publicite non envoyée', ['error' => $e->getMessage()]);
            }
        }
    }

    private function notifierAnnonceur(Publicite $publicite): void
    {
        try {
            Auth::user()->notify(new PubliciteSoumiseNotification($publicite));
        } catch (\Throwable $e) {
            Log::warning('Notification annonceur publicite non envoyée', ['error' => $e->getMessage()]);
        }
    }
}
