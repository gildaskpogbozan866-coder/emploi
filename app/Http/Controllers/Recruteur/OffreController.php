<?php

namespace App\Http\Controllers\Recruteur;

use App\Http\Controllers\Controller;
use App\Models\Competence;
use App\Models\Offre;
use App\Models\ParametreApp;
use App\Notifications\NouvelleOffreCreee;
use App\Services\AlerteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        // Paiement non encore actif : quota désactivé.
        // Mettre FACTURATION_ACTIVE=true dans .env pour réactiver.
        if (!config('app.facturation_active', false)) {
            return null;
        }

        $limite        = $this->getPlanLimite();
        $offresActives = Auth::user()->offres()->where('statut', 'active')->count();

        if ($limite === 0) {
            return 'Vous devez souscrire à un abonnement pour publier des offres.';
        }
        if ($offresActives >= $limite) {
            $label = $limite === 9999 ? 'illimité' : $limite;
            return "Votre abonnement est limité à {$label} offre(s) active(s). Passez au plan Illimité pour publier davantage.";
        }
        return null;
    }

    public function index(Request $request)
    {
        $query = Auth::user()->offres()->withCount('candidatures')->latest();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(fn($sq) => $sq->where('titre', 'like', "%$q%")->orWhere('entreprise', 'like', "%$q%"));
        }
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $offres = $query->paginate(15)->withQueryString();

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
            'fichier'      => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $erreurQuota = $this->verifierQuota();
        if ($erreurQuota) {
            return redirect()->route('recruteur.abonnement')
                ->withErrors(['plan' => $erreurQuota]);
        }

        $fichier = $request->hasFile('fichier')
            ? $request->file('fichier')->store('offres/fichiers', 'public')
            : null;

        $offre = Offre::create([
            ...$request->only(['titre','entreprise','localisation','type','secteur','salaire','description','exigences','date_limite']),
            'recruteur_id' => Auth::id(),
            'statut'       => 'active',
            'fichier'      => $fichier,
        ]);

        $offre->competences()->sync($this->syncCompetences($request->input('competences', [])));
        $offre->load(['recruteur', 'competences']);

        app(AlerteService::class)->notifierImmediat($offre);

        $adminEmail = ParametreApp::get('admin_notification_email', config('emploi.admin_notification_email'));
        if ($adminEmail) {
            Notification::route('mail', $adminEmail)->notify(new NouvelleOffreCreee($offre));
        }

        return redirect()->route('recruteur.offres')
            ->with('success', 'Votre offre est publiée et visible immédiatement.');
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
            'fichier'      => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $data = $request->only(['titre','entreprise','localisation','type','secteur','salaire','description','exigences','date_limite']);

        if ($request->hasFile('fichier')) {
            if ($offre->fichier) Storage::disk('public')->delete($offre->fichier);
            $data['fichier'] = $request->file('fichier')->store('offres/fichiers', 'public');
        } elseif ($request->boolean('_supprimer_fichier') && $offre->fichier) {
            Storage::disk('public')->delete($offre->fichier);
            $data['fichier'] = null;
        }

        $offre->update($data);
        $offre->competences()->sync($this->syncCompetences($request->input('competences', [])));

        return redirect()->route('recruteur.offres')->with('success', 'Offre mise à jour.');
    }

    private function syncCompetences(array $noms): array
    {
        return collect($noms)
            ->filter()
            ->map(fn($nom) => Competence::firstOrCreate(
                ['slug' => Str::slug($nom)],
                ['nom'  => $nom]
            )->id)
            ->unique()
            ->values()
            ->all();
    }

    public function cloturer(Offre $offre)
    {
        $this->authorize('update', $offre);
        $offre->update(['statut' => 'clos']);
        return back()->with('success', 'Offre clôturée — elle n\'est plus visible par les candidats.');
    }

    public function dupliquer(Offre $offre)
    {
        $this->authorize('update', $offre);

        $copie = $offre->replicate(['vues']);
        $copie->titre   = $offre->titre . ' (copie)';
        $copie->statut  = 'active';
        $copie->vues    = 0;
        $copie->fichier = null;
        $copie->save();

        $copie->competences()->sync($offre->competences->pluck('id')->all());

        return redirect()->route('recruteur.offres.edit', $copie)
            ->with('success', 'Offre dupliquée — modifiez-la avant de la publier.');
    }

    public function stats(Offre $offre)
    {
        $this->authorize('update', $offre);

        $offre->load('competences');
        $candidatures = $offre->candidatures()->with('candidat')->latest()->get();

        $parStatut = $candidatures->groupBy('statut')->map->count();

        return view('recruteur.offre-stats', compact('offre', 'candidatures', 'parStatut'));
    }

    public function destroy(Offre $offre)
    {
        $this->authorize('delete', $offre);
        if ($offre->fichier) Storage::disk('public')->delete($offre->fichier);
        $offre->delete();
        return redirect()->route('recruteur.offres')->with('success', 'Offre supprimée.');
    }
}
