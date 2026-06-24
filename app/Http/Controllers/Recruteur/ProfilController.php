<?php

namespace App\Http\Controllers\Recruteur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProfilController extends Controller
{
    public function edit()
    {
        return view('recruteur.profil', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'prenom'     => 'required|string|max:100',
            'nom'        => 'required|string|max:100',
            'entreprise' => 'nullable|string|max:200',
            'tel'        => 'nullable|string|max:30',
            'pays'       => 'nullable|string|max:100',
            'avatar'     => 'nullable|image|mimes:jpeg,jpg,png,webp,gif|max:2048',
        ]);

        $data = $request->only(['prenom','nom','entreprise','tel','pays']);

        if ($request->hasFile('avatar')) {
            $user = Auth::user();
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }
            $data['avatar'] = $request->file('avatar')->store('avatars', 'public');
        }

        Auth::user()->update($data);
        return back()->with('success', 'Profil mis à jour.');
    }

    public function parametres()
    {
        return view('recruteur.parametres', ['user' => Auth::user()]);
    }

    public function updateParametres(Request $request)
    {
        $request->validate([
            'tel' => 'nullable|string|max:20',
        ]);

        Auth::user()->update($request->only(['tel']));
        return back()->with('success', 'Paramètres mis à jour.');
    }

    public function deleteAccount(Request $request)
    {
        $request->validate([
            'confirmation' => ['required', 'in:SUPPRIMER'],
        ], [
            'confirmation.required' => 'Veuillez taper SUPPRIMER pour confirmer.',
            'confirmation.in'       => 'Le mot de confirmation est incorrect. Tapez exactement SUPPRIMER.',
        ]);

        $user = Auth::user();
        $userId = $user->id;

        // Supprimer l'avatar du stockage (hors transaction)
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        DB::transaction(function () use ($userId) {
            // 1. Candidatures liées aux offres du recruteur
            $offreIds = DB::table('offres')->where('recruteur_id', $userId)->pluck('id');
            if ($offreIds->isNotEmpty()) {
                DB::table('candidatures')->whereIn('offre_id', $offreIds)->delete();
            }

            // 2. Offres
            DB::table('offres')->where('recruteur_id', $userId)->delete();

            // 3. Vérification et documents recruteur
            DB::table('recruteur_documents')->where('user_id', $userId)->delete();
            DB::table('recruteur_verifications')->where('user_id', $userId)->delete();

            // 4. Messagerie : conversations et messages
            $convIds = DB::table('conversations')
                ->where('user1_id', $userId)
                ->orWhere('user2_id', $userId)
                ->pluck('id');
            if ($convIds->isNotEmpty()) {
                DB::table('messages')->whereIn('conversation_id', $convIds)->delete();
                DB::table('conversations')->whereIn('id', $convIds)->delete();
            }
            DB::table('messages')->where('expediteur_id', $userId)->delete();

            // 5. Tables pivots
            DB::table('cv_favoris')->where('user_id', $userId)->delete();
            DB::table('offres_sauvegardees')->where('user_id', $userId)->delete();

            // 6. CV downloads (recruteur_id)
            DB::table('cv_downloads')->where('recruteur_id', $userId)->delete();

            // 7. Signalements
            DB::table('signalements')->where('user_id', $userId)->delete();

            // 8. Finances
            DB::table('abonnements')->where('user_id', $userId)->delete();
            DB::table('paiements')->where('user_id', $userId)->delete();
            DB::table('commandes')->where('user_id', $userId)->delete();

            // 9. Alertes et notifications
            DB::table('alertes')->where('user_id', $userId)->delete();
            DB::table('notifications')->where('user_id', $userId)->delete();

            // 10. Page visits
            DB::table('page_visits')->where('user_id', $userId)->delete();

            // 11. Publicités et articles
            DB::table('publicites')->where('user_id', $userId)->delete();
            DB::table('articles')->where('auteur_id', $userId)->delete();

            // 12. Rôles et permissions Spatie
            DB::table('model_has_roles')->where('model_id', $userId)->delete();
            DB::table('model_has_permissions')->where('model_id', $userId)->delete();

            // 13. Supprimer le compte
            DB::table('users')->where('id', $userId)->delete();
        });

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Votre compte a été supprimé définitivement. Vous pouvez vous réinscrire à tout moment.');
    }
}
