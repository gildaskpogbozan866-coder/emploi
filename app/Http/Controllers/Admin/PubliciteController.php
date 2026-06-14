<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Publicite;
use App\Models\User;
use App\Notifications\PubliciteApprouveeNotification;
use App\Notifications\PubliciteRejeteNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PubliciteController extends Controller
{
    public function index(Request $request)
    {
        $query = Publicite::with('user')->latest();

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $counts = [
            'en_attente' => Publicite::where('statut', 'en_attente')->count(),
            'approuve'   => Publicite::where('statut', 'approuve')->count(),
            'rejete'     => Publicite::where('statut', 'rejete')->count(),
        ];

        $publicites = $query->paginate(20)->withQueryString();

        return view('admin.publicites.index', compact('publicites', 'counts'));
    }

    public function show(Publicite $publicite)
    {
        $publicite->load('user');
        return view('admin.publicites.show', compact('publicite'));
    }

    public function approuver(Publicite $publicite)
    {
        $publicite->update(['statut' => 'approuve', 'note_admin' => null]);

        $annonceur = $publicite->user;

        Notification::create([
            'user_id' => $annonceur->id,
            'type'    => 'publicite',
            'titre'   => 'Annonce approuvée',
            'contenu' => "Votre annonce « {$publicite->titre} » a été approuvée et est maintenant visible sur la plateforme.",
            'lien'    => route('annonceur.publicites'),
        ]);

        $annonceur->notify(new PubliciteApprouveeNotification($publicite));

        return back()->with('success', 'Annonce approuvée. L\'annonceur en a été notifié.');
    }

    public function rejeter(Request $request, Publicite $publicite)
    {
        $request->validate([
            'note_admin' => 'required|string|max:1000',
        ], ['note_admin.required' => 'Veuillez indiquer le motif du rejet.']);

        $publicite->update(['statut' => 'rejete', 'note_admin' => $request->note_admin]);

        $annonceur = $publicite->user;

        Notification::create([
            'user_id' => $annonceur->id,
            'type'    => 'publicite',
            'titre'   => 'Annonce rejetée',
            'contenu' => "Votre annonce « {$publicite->titre} » a été rejetée. Motif : {$request->note_admin}",
            'lien'    => route('annonceur.publicites'),
        ]);

        $annonceur->notify(new PubliciteRejeteNotification($publicite, $request->note_admin));

        return back()->with('success', 'Annonce rejetée. L\'annonceur en a été notifié.');
    }

    public function destroy(Publicite $publicite)
    {
        Storage::disk('public')->delete($publicite->image);
        $publicite->delete();

        return redirect()->route('admin.publicites.index')->with('success', 'Annonce supprimée.');
    }
}
