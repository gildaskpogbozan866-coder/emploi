<?php

namespace App\Http\Controllers\Recruteur;

use App\Http\Controllers\Controller;
use App\Models\Competence;
use App\Models\Offre;
use App\Models\ParametreApp;
use App\Models\TypeContrat;
use App\Notifications\NouvelleOffreCreee;
use App\Services\AlerteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class OffreController extends Controller
{
    private function infoMiseEnAvant(): array
    {
        $abonnement = Auth::user()->abonnementActif()->with('plan.features')->first();

        if (!$abonnement) {
            return ['limite' => 0, 'utilisees' => 0, 'disponible' => false];
        }

        $limitValue = $abonnement->plan?->getFeature('featured_jobs');

        // Feature non définie = illimité
        if ($limitValue === null) {
            return ['limite' => null, 'utilisees' => 0, 'disponible' => true];
        }

        // 0 = fonctionnalité désactivée sur ce plan
        if ((int) $limitValue === 0) {
            return ['limite' => 0, 'utilisees' => 0, 'disponible' => false];
        }

        $limite    = (int) $limitValue;
        $utilisees = Auth::user()->offres()->where('premium', true)->count();

        return ['limite' => $limite, 'utilisees' => $utilisees, 'disponible' => $utilisees < $limite];
    }

    private function verifierQuota(): ?string
    {
        $abonnement = Auth::user()->abonnementActif()->with('plan.features')->first();

        if (!$abonnement) {
            return 'Vous devez souscrire à un abonnement pour publier des offres.';
        }

        $limitValue = $abonnement->plan?->getFeature('job_post_limit');

        // Feature non définie = illimité
        if ($limitValue === null) {
            return null;
        }

        // 0 = publication d'offres désactivée sur ce plan
        if ((int) $limitValue === 0) {
            return "Votre plan « {$abonnement->plan->name} » ne permet pas de publier des offres. Souscrivez un plan supérieur.";
        }

        $limite    = (int) $limitValue;
        $utilisees = Auth::user()->offres()
                         ->where('created_at', '>=', $abonnement->starts_at)
                         ->count();

        if ($utilisees >= $limite) {
            return "Vous avez utilisé {$utilisees}/{$limite} offres de votre plan « {$abonnement->plan->name} ». Renouvelez votre abonnement pour continuer à publier.";
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

        $offres          = $query->paginate(15)->withQueryString();
        $miseEnAvantInfo = $this->infoMiseEnAvant();
        $typeContrats    = TypeContrat::orderBy('libelle')->get();

        return view('recruteur.offres', compact('offres', 'miseEnAvantInfo', 'typeContrats'));
    }

    public function create()
    {
        $erreurQuota = $this->verifierQuota();
        if ($erreurQuota) {
            $route = Auth::user()->abonnementActif()->exists()
                ? 'recruteur.abonnement.plans'
                : 'recruteur.abonnement';
            return redirect()->route($route)->with('error', $erreurQuota);
        }
        $typeContrats = TypeContrat::orderBy('libelle')->get();
        return view('recruteur.offre-create', compact('typeContrats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre'        => 'required|string|max:200',
            'entreprise'   => 'required|string|max:200',
            'localisation' => 'required|string|max:200',
            'type'         => 'required|exists:type_contrats,code',
            'description'  => 'required|string|min:50',
            'date_limite'  => 'nullable|date|after_or_equal:today',
            'fichier'      => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $erreurQuota = $this->verifierQuota();
        if ($erreurQuota) {
            $route = Auth::user()->abonnementActif()->exists()
                ? 'recruteur.abonnement.plans'
                : 'recruteur.abonnement';
            return redirect()->route($route)->with('error', $erreurQuota);
        }

        $fichier = $request->hasFile('fichier')
            ? $request->file('fichier')->store('offres/fichiers', 'public')
            : null;

        $offre = Offre::create([
            ...$request->only(['titre','entreprise','localisation','type','salaire','description','exigences','date_limite']),
            'recruteur_id' => Auth::id(),
            'statut'       => 'active',
            'fichier'      => $fichier,
            'secteur'      => $request->input('secteur', []),
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
        $typeContrats = TypeContrat::orderBy('libelle')->get();
        return view('recruteur.offre-edit', compact('offre', 'typeContrats'));
    }

    public function update(Request $request, Offre $offre)
    {
        $this->authorize('update', $offre);

        $request->validate([
            'titre'        => 'required|string|max:200',
            'entreprise'   => 'required|string|max:200',
            'localisation' => 'required|string|max:200',
            'type'         => 'required|exists:type_contrats,code',
            'description'  => 'required|string|min:50',
            'fichier'      => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $data = array_merge(
            $request->only(['titre','entreprise','localisation','type','salaire','description','exigences','date_limite']),
            ['secteur' => $request->input('secteur', [])]
        );

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

    public function mettreEnAvant(Offre $offre)
    {
        $this->authorize('update', $offre);

        // Déjà en avant → on retire sans vérifier le quota
        if ($offre->premium) {
            $offre->update(['premium' => false]);
            return back()->with('success', '« ' . $offre->titre . ' » retirée de la mise en avant.');
        }

        // Vérifier le quota avant d'activer
        $info = $this->infoMiseEnAvant();

        if (!$info['disponible']) {
            $msg = $info['limite'] === 0
                ? 'Votre abonnement ne permet pas de mettre des offres en avant. Souscrivez un plan avec cette fonctionnalité.'
                : "Limite atteinte ({$info['utilisees']}/{$info['limite']}). Retirez d'abord une offre mise en avant pour en promouvoir une autre.";
            return back()->with('error', $msg);
        }

        $offre->update(['premium' => true]);
        return back()->with('success', '« ' . $offre->titre . ' » est maintenant mise en avant.');
    }

    public function dupliquer(Offre $offre)
    {
        $this->authorize('update', $offre);

        $erreurQuota = $this->verifierQuota();
        if ($erreurQuota) {
            $route = Auth::user()->abonnementActif()->exists()
                ? 'recruteur.abonnement.plans'
                : 'recruteur.abonnement';
            return redirect()->route($route)->with('error', $erreurQuota);
        }

        $copie = $offre->replicate(['vues']);
        $copie->titre   = $offre->titre . ' (copie)';
        $copie->statut  = 'active';
        $copie->vues    = 0;
        $copie->fichier = null;
        $copie->premium = false;
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
