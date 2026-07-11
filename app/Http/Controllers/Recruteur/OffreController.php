<?php

namespace App\Http\Controllers\Recruteur;

use App\Http\Controllers\Controller;
use App\Models\Competence;
use App\Models\Metier;
use App\Models\NiveauEtude;
use App\Models\NiveauExperience;
use App\Models\Offre;
use App\Models\ParametreApp;
use App\Models\TypeContrat;
use App\Models\TypeDocument;
use App\Notifications\NouvelleOffreCreee;
use App\Jobs\NotifierAlertesOffreJob;
use App\Rules\DateLimiteWithinAbonnement;
use App\Services\JobPostQuotaService;
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
        return app(JobPostQuotaService::class)->quotaFor(Auth::user())['message'];
    }

    public function index(Request $request)
    {
        $query = Auth::user()->offres()
            ->withCount([
                'candidatures',
                'candidatures as candidatures_nouvelles_count' => fn($q) => $q->where('statut', 'envoyee'),
            ])
            ->latest();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(fn($sq) => $sq->where('titre', 'like', "%$q%")->orWhere('entreprise', 'like', "%$q%"));
        }
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        if ($request->filled('type')) {
            $query->whereHas('type', fn($q) => $q->where('code', $request->type));
        }
        if ($request->filled('salaire_min')) {
            $query->where('salaire_max', '>=', (int) $request->salaire_min);
        }
        if ($request->filled('salaire_max')) {
            $query->where('salaire_min', '<=', (int) $request->salaire_max);
        }

        $tri = $request->input('tri', 'recent');
        $query->reorder()->orderBy(match($tri) {
            'salaire_asc'        => 'salaire_min',
            'salaire_desc'       => 'salaire_min',
            'candidatures_desc'  => 'candidatures_count',
            'date_limite'        => 'date_limite',
            default              => 'created_at',
        }, match($tri) {
            'salaire_asc'  => 'asc',
            'date_limite'  => 'asc',
            default        => 'desc',
        });

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
        $typeContrats    = TypeContrat::orderBy('libelle')->get();
        $metiers         = Metier::orderBy('nom')->get();
        $niveauxExp      = NiveauExperience::orderBy('ordre')->get();
        $niveauxEtude    = NiveauEtude::orderBy('ordre')->get();
        $typesDocuments  = $this->typesDocumentsSelectionnables();
        return view('recruteur.offre-create', compact('typeContrats', 'metiers', 'niveauxExp', 'niveauxEtude', 'typesDocuments'));
    }

    /** Types de documents proposables comme "pièce requise" sur une offre — le CV a déjà son propre toggle exige_cv. */
    private function typesDocumentsSelectionnables()
    {
        return TypeDocument::actif()->where('nom', 'not like', '%Curriculum Vitae%')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre'             => 'required|string|max:200',
            'entreprise'        => 'required|string|max:200',
            'localisation'      => 'required|string|max:200',
            'type'              => 'required|exists:type_contrats,id',
            'description'       => 'required|string|min:50',
            'date_limite'       => ['nullable', 'date', 'after_or_equal:today', new DateLimiteWithinAbonnement(Auth::user())],
            'salaire_min'       => 'nullable|integer|min:0',
            'salaire_max'       => 'nullable|integer|min:0|gte:salaire_min',
            'fichier'           => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'logo'              => 'nullable|file|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'metier_id'         => 'nullable|exists:metiers,id',
            'niveau_experience' => 'nullable|exists:niveaux_experience,code',
            'niveau_etude'      => 'nullable|exists:niveaux_etudes,code',
            'types_documents_requis'   => 'nullable|array',
            'types_documents_requis.*' => 'integer|exists:type_documents,id',
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

        $logo = $request->hasFile('logo')
            ? $request->file('logo')->store('offres/logos', 'public')
            : null;

        $offre = Offre::create([
            ...$request->only(['titre','entreprise','localisation','salaire_min','salaire_max','description','exigences','date_limite','metier_id','niveau_experience','niveau_etude']),
            'recruteur_id'    => Auth::id(),
            'statut'          => 'active',
            'published_at'    => now(),
            'fichier'         => $fichier,
            'logo'            => $logo,
            'secteur'         => $request->input('secteur', []),
            'type_contrat_id' => $request->type,
            'exige_cv'        => $request->boolean('exige_cv'),
            'exige_lettre'    => $request->boolean('exige_lettre'),
        ]);

        $offre->competences()->sync($this->syncCompetences($request->input('competences', [])));
        $offre->typesDocumentsRequis()->sync($request->input('types_documents_requis', []));
        $offre->load(['recruteur', 'competences']);

        NotifierAlertesOffreJob::dispatch($offre);

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
        $typeContrats    = TypeContrat::orderBy('libelle')->get();
        $metiers         = Metier::orderBy('nom')->get();
        $niveauxExp      = NiveauExperience::orderBy('ordre')->get();
        $niveauxEtude    = NiveauEtude::orderBy('ordre')->get();
        $typesDocuments  = $this->typesDocumentsSelectionnables();
        $offre->load('typesDocumentsRequis');
        return view('recruteur.offre-edit', compact('offre', 'typeContrats', 'metiers', 'niveauxExp', 'niveauxEtude', 'typesDocuments'));
    }

    public function update(Request $request, Offre $offre)
    {
        $this->authorize('update', $offre);

        $request->validate([
            'titre'             => 'required|string|max:200',
            'entreprise'        => 'required|string|max:200',
            'localisation'      => 'required|string|max:200',
            'type'              => 'required|exists:type_contrats,id',
            'description'       => 'required|string|min:50',
            'date_limite'       => ['nullable', 'date', 'after_or_equal:today', new DateLimiteWithinAbonnement(Auth::user())],
            'salaire_min'       => 'nullable|integer|min:0',
            'salaire_max'       => 'nullable|integer|min:0|gte:salaire_min',
            'fichier'           => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'logo'              => 'nullable|file|mimes:jpg,jpeg,png,webp,svg|max:2048',
            'metier_id'         => 'nullable|exists:metiers,id',
            'niveau_experience' => 'nullable|exists:niveaux_experience,code',
            'niveau_etude'      => 'nullable|exists:niveaux_etudes,code',
            'types_documents_requis'   => 'nullable|array',
            'types_documents_requis.*' => 'integer|exists:type_documents,id',
        ]);

        $data = array_merge(
            $request->only(['titre','entreprise','localisation','salaire_min','salaire_max','description','exigences','date_limite','metier_id','niveau_experience','niveau_etude']),
            [
                'secteur'         => $request->input('secteur', []),
                'type_contrat_id' => $request->type,
                'exige_cv'        => $request->boolean('exige_cv'),
                'exige_lettre'    => $request->boolean('exige_lettre'),
            ]
        );

        if ($request->hasFile('fichier')) {
            if ($offre->fichier) Storage::disk('public')->delete($offre->fichier);
            $data['fichier'] = $request->file('fichier')->store('offres/fichiers', 'public');
        } elseif ($request->boolean('_supprimer_fichier') && $offre->fichier) {
            Storage::disk('public')->delete($offre->fichier);
            $data['fichier'] = null;
        }

        if ($request->hasFile('logo')) {
            if ($offre->logo) Storage::disk('public')->delete($offre->logo);
            $data['logo'] = $request->file('logo')->store('offres/logos', 'public');
        } elseif ($request->boolean('_supprimer_logo') && $offre->logo) {
            Storage::disk('public')->delete($offre->logo);
            $data['logo'] = null;
        }

        $offre->update($data);
        $offre->competences()->sync($this->syncCompetences($request->input('competences', [])));
        $offre->typesDocumentsRequis()->sync($request->input('types_documents_requis', []));

        return redirect()->route('recruteur.offres')->with('success', 'Offre mise à jour.');
    }

    private function syncCompetences(array $noms): array
    {
        // Recherche par `nom` (la valeur saisie par le recruteur) et non par
        // `slug` recalculé : certaines compétences existantes ont un slug
        // seedé manuellement qui ne correspond plus à Str::slug($nom), ce qui
        // faisait échouer la recherche et déclenchait une violation de la
        // contrainte unique sur `nom` à l'insertion.
        return collect($noms)
            ->filter()
            ->map(fn($nom) => Competence::firstOrCreate(
                ['nom'  => $nom],
                ['slug' => Str::slug($nom)]
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
        $copie->typesDocumentsRequis()->sync($offre->typesDocumentsRequis->pluck('id')->all());

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
