<?php

namespace App\Http\Controllers\Recruteur;

use App\Http\Controllers\Controller;
use App\Models\Offre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OffreController extends Controller
{
    private function getPlanLimite(): int
    {
        $plan = Auth::user()->abonnementActif?->plan;
        return match($plan) {
            'premium_50' => 9999,
            'premium_30' => 10,
            default      => 0, // pas d'abonnement = pas de publication
        };
    }

    private function verifierQuota(): ?string
    {
        $limite        = $this->getPlanLimite();
        $offresActives = Auth::user()->offres()->whereIn('statut', ['active', 'en_attente'])->count();

        if ($limite === 0) {
            return 'Vous devez souscrire à un abonnement pour publier des offres.';
        }
        if ($offresActives >= $limite) {
            $label = $limite === 9999 ? 'illimité' : $limite;
            return "Votre abonnement est limité à {$label} offre(s) active(s). Passez au plan Illimité pour publier davantage.";
        }
        return null;
    }

    public function index()
    {
        $offres = Auth::user()->offres()
            ->withCount('candidatures')
            ->latest()
            ->paginate(15);

        return view('recruteur.offres', compact('offres'));
    }

    public function create()
    {
        $erreurQuota = $this->verifierQuota();
        if ($erreurQuota) {
            return redirect()->route('recruteur.abonnement')
                ->with('info', $erreurQuota);
        }
        return view('recruteur.offre-create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre'        => 'required|string|max:200',
            'entreprise'   => 'required|string|max:200',
            'localisation' => 'required|string|max:200',
            'type'         => 'required|in:CDI,CDD,Stage,Bourse,Freelance,Temps partiel',
            'description'  => 'required|string|min:50',
            'date_limite'  => 'nullable|date|after_or_equal:today',
        ]);

        $erreurQuota = $this->verifierQuota();
        if ($erreurQuota) {
            return redirect()->route('recruteur.abonnement')
                ->withErrors(['plan' => $erreurQuota]);
        }

        Offre::create([
            ...$request->only(['titre','entreprise','localisation','type','secteur','salaire','description','competences','exigences','date_limite']),
            'recruteur_id' => Auth::id(),
            'statut'       => 'en_attente',
        ]);

        return redirect()->route('recruteur.offres')
            ->with('success', 'Offre soumise — elle sera activée après validation admin.');
    }

    public function edit(Offre $offre)
    {
        $this->authorize('update', $offre);
        return view('recruteur.offre-edit', compact('offre'));
    }

    public function update(Request $request, Offre $offre)
    {
        $this->authorize('update', $offre);

        $request->validate([
            'titre'        => 'required|string|max:200',
            'localisation' => 'required|string|max:200',
            'type'         => 'required|in:CDI,CDD,Stage,Bourse,Freelance,Temps partiel',
            'description'  => 'required|string|min:50',
        ]);

        $offre->update($request->only(['titre','localisation','type','secteur','salaire','description','competences','exigences','date_limite']));

        return redirect()->route('recruteur.offres')->with('success', 'Offre mise à jour.');
    }

    public function destroy(Offre $offre)
    {
        $this->authorize('delete', $offre);
        $offre->delete();
        return redirect()->route('recruteur.offres')->with('success', 'Offre supprimée.');
    }
}
