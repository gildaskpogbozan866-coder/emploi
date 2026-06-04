<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Signalement;
use Illuminate\Http\Request;

class SignalementController extends Controller
{
    public function index(Request $request)
    {
        $query = Signalement::with('user')->latest();

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $signalements = $query->paginate(20)->withQueryString();
        return view('admin.signalements.list', compact('signalements'));
    }

    public function show(Signalement $signalement)
    {
        $signalement->load('user');
        return view('admin.signalements.detail', compact('signalement'));
    }

    public function updateStatut(Request $request, Signalement $signalement)
    {
        $request->validate([
            'statut'     => 'required|in:en_attente,traite,rejete',
            'note_admin' => 'nullable|string|max:500',
        ]);

        $signalement->update([
            'statut'     => $request->statut,
            'note_admin' => $request->note_admin,
        ]);

        return back()->with('success', 'Signalement traité.');
    }
}
