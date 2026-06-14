<?php

namespace App\Http\Controllers\Annonceur;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Publicite;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PubliciteController extends Controller
{
    public function index()
    {
        $publicites = Publicite::where('user_id', Auth::id())->latest()->get();
        return view('annonceur.publicites', compact('publicites'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre'           => 'required|string|max:191',
            'image'           => 'required|image|mimes:jpg,jpeg,png,webp,gif|max:5120',
            'lien'            => 'nullable|url|max:500',
            'note_annonceur'  => 'nullable|string|max:1000',
            'date_debut'      => 'nullable|date',
            'date_fin'        => 'nullable|date|after_or_equal:date_debut',
        ], [
            'image.image'    => 'Le fichier doit être une image (jpg, png, webp, gif).',
            'image.max'      => 'L\'image ne doit pas dépasser 5 Mo.',
            'lien.url'       => 'Le lien doit être une URL valide (commençant par http:// ou https://).',
            'date_fin.after_or_equal' => 'La date de fin doit être postérieure ou égale à la date de début.',
        ]);

        $path = $request->file('image')->store('publicites', 'public');

        Publicite::create([
            'user_id'        => Auth::id(),
            'titre'          => $validated['titre'],
            'image'          => $path,
            'lien'           => $validated['lien'] ?? null,
            'note_annonceur' => $validated['note_annonceur'] ?? null,
            'date_debut'     => $validated['date_debut'] ?? null,
            'date_fin'       => $validated['date_fin'] ?? null,
            'statut'         => 'en_attente',
        ]);

        // Notifier les admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type'    => 'publicite',
                'titre'   => 'Nouvelle annonce à modérer',
                'contenu' => Auth::user()->nom_complet . " a soumis une annonce : « {$validated['titre']} ».",
                'lien'    => route('admin.publicites.index'),
            ]);
        }

        return back()->with('success', 'Votre annonce a été soumise et est en attente de validation par l\'équipe.');
    }

    public function destroy(Publicite $publicite)
    {
        abort_if($publicite->user_id !== Auth::id(), 403);

        Storage::disk('public')->delete($publicite->image);
        $publicite->delete();

        return back()->with('success', 'Annonce supprimée.');
    }
}
