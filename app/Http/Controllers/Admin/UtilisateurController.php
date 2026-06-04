<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UtilisateurController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(20);
        return view('admin.utilisateurs.list', compact('users'));
    }

    public function candidats(Request $request)
    {
        $query = User::where('role', 'candidat')->with('cvs')->latest();
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sq) use ($q) {
                $sq->where('prenom', 'like', "%$q%")
                   ->orWhere('nom', 'like', "%$q%")
                   ->orWhere('email', 'like', "%$q%");
            });
        }
        $candidats = $query->paginate(20)->withQueryString();
        return view('admin.utilisateurs.candidats', compact('candidats'));
    }

    public function recruteurs(Request $request)
    {
        $query = User::where('role', 'recruteur')->withCount('offres')->latest();
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sq) use ($q) {
                $sq->where('prenom', 'like', "%$q%")
                   ->orWhere('nom', 'like', "%$q%")
                   ->orWhere('entreprise', 'like', "%$q%");
            });
        }
        $recruteurs = $query->paginate(20)->withQueryString();
        return view('admin.utilisateurs.recruteurs', compact('recruteurs'));
    }

    public function showCandidat(User $user)
    {
        $user->load(['cvs', 'candidatures.offre', 'abonnements']);
        return view('admin.utilisateurs.detail-candidat', compact('user'));
    }

    public function showRecruteur(User $user)
    {
        $user->load(['offres' => fn ($q) => $q->withCount('candidatures')]);
        return view('admin.utilisateurs.detail-recruteur', compact('user'));
    }

    public function toggleStatut(Request $request, User $user)
    {
        if ($user->role === 'admin') {
            return back()->withErrors(['Impossible de suspendre un administrateur.']);
        }
        $user->update(['actif' => !$user->actif]);
        $action = $user->actif ? 'réactivé' : 'suspendu';
        return back()->with('success', "Compte {$action} avec succès.");
    }

    public function destroy(User $user)
    {
        if ($user->role === 'admin') {
            return back()->withErrors(['Impossible de supprimer un administrateur.']);
        }
        $user->delete();
        return redirect()->route('admin.utilisateurs.candidats')->with('success', 'Compte supprimé.');
    }
}
