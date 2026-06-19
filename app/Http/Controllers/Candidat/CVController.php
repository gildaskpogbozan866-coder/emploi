<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use App\Models\CandidatProfil;
use App\Models\Competence;
use App\Models\CV;
use App\Models\Document;
use App\Models\Langue;
use App\Models\LangueCandidat;
use App\Models\NiveauLangue;
use App\Models\TypeDocument;
use App\Models\User;
use App\Notifications\NouveauCVDeposeNotification;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CVController extends Controller
{
    // ── CVthèque publique ─────────────────────────────────
    public function theque(Request $request)
    {
        // ── CVs ──
        $cvQuery = CV::visible()->with('candidat')->latest();

        if ($request->filled('q')) {
            $q = $request->q;
            $cvQuery->where(function ($sq) use ($q) {
                $sq->where('titre_poste', 'like', "%$q%")
                    ->orWhere('competences', 'like', "%$q%")
                    ->orWhere('secteur', 'like', "%$q%");
            });
        }
        if ($request->filled('pays'))             $cvQuery->where('pays', $request->pays);
        if ($request->filled('secteur'))          $cvQuery->where('secteur', 'like', '%' . $request->secteur . '%');
        if ($request->filled('langue'))           $cvQuery->where('langues', 'like', '%' . $request->langue . '%');
        if ($request->filled('metier'))           $cvQuery->where(function ($sq) use ($request) {
            $sq->where('metier', 'like', '%' . $request->metier . '%')
                ->orWhere('titre_poste', 'like', '%' . $request->metier . '%');
        });
        if ($request->filled('niveau_etude'))     $cvQuery->where('niveau_etude', $request->niveau_etude);
        if ($request->filled('type_contrat'))     $cvQuery->where('type_contrat', $request->type_contrat);
        if ($request->filled('niveau_experience')) $cvQuery->where('niveau_experience', $request->niveau_experience);

        $cvResults = $cvQuery->get()->map(function ($cv) {
            $cv->_is_document = false;
            return $cv;
        });

        // ── Documents (diplômes, attestations, certificats…) ──
        $docQuery = Document::with(['user', 'type'])->latest();

        if ($request->filled('q')) {
            $q = $request->q;
            $docQuery->where(function ($sq) use ($q) {
                $sq->where('nom', 'like', "%$q%")
                    ->orWhere('competences', 'like', "%$q%");
            });
        }
        if ($request->filled('pays'))   $docQuery->where('pays', $request->pays);
        if ($request->filled('langue')) $docQuery->where('langues', 'like', '%' . $request->langue . '%');

        $docResults = $docQuery->get()->map(function ($doc) {
            return (object) [
                '_is_document' => true,
                'id'           => $doc->id,
                'titre_poste'  => $doc->nom,
                'photo'        => null,
                'pays'         => $doc->pays,
                'langues'      => $doc->langues,
                'competences'  => $doc->competences,
                'experience'   => $doc->experience,
                'plan'         => null,
                'candidat'     => $doc->user,
                'type_label'   => $doc->type?->nom ?? 'Document',
                'fichier'      => $doc->fichier,
                'created_at'   => $doc->created_at,
            ];
        });

        // ── Fusion & pagination ──
        $merged = $cvResults->concat($docResults)->sortByDesc('created_at')->values();
        $page   = (int) $request->get('page', 1);
        $perPage = 12;
        $cvs = new LengthAwarePaginator(
            $merged->forPage($page, $perPage)->values(),
            $merged->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html'  => view('public.cv._theque_liste', compact('cvs'))->render(),
                'total' => $cvs->total(),
                'pages' => $cvs->lastPage(),
            ]);
        }

        return view('public.cv.theque', compact('cvs'));
    }

    public function documentDetail(Document $document)
    {
        $document->load(['user', 'type']);
        return view('public.cv.document-detail', compact('document'));
    }

    public function candidatDetails(int $id)
    {
       
        $candidat = User::where('id', $id)
            ->where('role', 'candidat')
            ->with([
                'candidatProfil',
                'experiences'   => fn($q) => $q->orderByDesc('en_cours')->orderByDesc('date_debut'),
                'formations'    => fn($q) => $q->orderByDesc('en_cours')->orderByDesc('date_debut'),
                'competences',
                'metiers',
                'niveauExperience.niveauExperience',
                'typesContrats',
                'secteursActivite',
                'languesCandidats.langue',
                'languesCandidats.niveau',
                'attestations',
                'realisations',
                'documents.type',
                'cvs'
            ])
            ->firstOrFail();

        // Sécurité : profil doit exister
        abort_if(is_null($candidat->candidatProfil), 404);

        $libelles = \App\Models\CandidatProfil::libelles();

        return view('public.cv.candidat-detail', compact('candidat', 'libelles'));
    }

    public function detail(CV $cv)
    {
        if (!$cv->visible) {
            abort(404);
        }

        $cv->load('candidat');

        return view('public.cv.detail', compact('cv'));
    }

    public function tarif()
    {
        $packs = \App\Models\CreditCvPack::actif()->orderBy('ordre')->orderBy('credits')->get();
        return view('public.cv.tarif', compact('packs'));
    }

    public function depot()
    {
        if (!Auth::check()) {
            return redirect()->route('auth.connexion')->with('redirect_after', route('cv.public.depot'));
        }

        if (!Auth::user()->hasRole('candidat')) {
            return redirect()->route(match (Auth::user()->role) {
                'recruteur' => 'recruteur.dashboard',
                'admin'     => 'admin.dashboard',
                default     => 'home',
            })->with('error', 'Seuls les candidats peuvent déposer un CV.');
        }

        $user  = Auth::user();
        $quota = $this->cvQuota($user);

        if ($quota['reached']) {
            return redirect()->route('candidat.abonnement.plans')
                ->with('info', "Vous avez atteint la limite de {$quota['limit']} document(s) de votre plan. Passez à un plan supérieur pour en ajouter davantage.");
        }

        $typesDocuments  = TypeDocument::actif()->get();
        $competences     = Competence::orderBy('nom')->pluck('nom');
        $typeCV          = TypeDocument::where('nom', 'like', '%Curriculum Vitae%')->first();
        $typeCVId        = $typeCV?->id ?? 1;
        return view('public.cv.depot', compact('typesDocuments', 'competences', 'quota', 'typeCVId'));
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('auth.connexion');
        }

        if (!Auth::user()->hasRole('candidat')) {
            return redirect()->route(match (Auth::user()->role) {
                'recruteur' => 'recruteur.dashboard',
                'admin'     => 'admin.dashboard',
                default     => 'home',
            })->with('error', 'Seuls les candidats peuvent déposer un CV.');
        }

        $user  = Auth::user();
        $quota = $this->cvQuota($user);

        if ($quota['reached']) {
            return redirect()->route('candidat.abonnement.plans')
                ->with('info', "Vous avez atteint la limite de {$quota['limit']} document(s) de votre plan. Passez à un plan supérieur pour en ajouter davantage.");
        }

        $request->validate([
            'type_document_id'  => 'required|exists:type_documents,id',
            'nom'               => 'required|string|max:200',
            'photo'             => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'pays'              => 'nullable|string|max:100',
            'ville'             => 'nullable|string|max:100',
            'disponibilite'     => 'nullable|string|max:50',
            'secteur'           => 'nullable|string|max:150',
            'metier'            => 'nullable|string|max:150',
            'niveau_experience' => 'nullable|string|max:100',
            'niveau_etude'      => 'nullable|string|max:100',
            'type_contrat'      => 'nullable|string|max:50',
            'competences'       => 'nullable|string',
            'experience'        => 'nullable|string',
            'formation'         => 'nullable|string',
            'langues_ids'       => 'nullable|array',
            'langues_ids.*'     => 'nullable|exists:langues,id',
            'niveaux_ids'       => 'nullable|array',
            'niveaux_ids.*'     => 'nullable|exists:niveaux_langue,id',
            'fichier_path'      => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,webp|max:5120',
        ]);

        $typeCV = TypeDocument::where('nom', 'like', '%Curriculum Vitae%')->first();
        $estCV  = $typeCV && $request->type_document_id == $typeCV->id;

        if ($estCV) {
            $fichierPath = null;
            if ($request->hasFile('fichier_path')) {
                $fichierPath = $request->file('fichier_path')->store('cvs', 'public');
            }

            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('cvs/photos', 'public');
            }

            [$languesTexte, $languesSync] = $this->buildLanguesData(
                $request->input('langues_ids', []),
                $request->input('niveaux_ids', [])
            );

            $cv = CV::create([
                'candidat_id'      => Auth::id(),

                'fichier_path'     => $fichierPath,
                'plan'             => 'gratuit',
                'visible'          => true,
            ]);

            // Sync langues vers la table pivot
            $user->langues()->sync($languesSync);

            // Notifier les admins
            foreach (User::where('role', 'admin')->get() as $admin) {
                try {
                    $admin->notify(new NouveauCVDeposeNotification($cv, $user));
                } catch (\Throwable $e) {
                    Log::warning('Notification admin CV non envoyée', ['error' => $e->getMessage()]);
                }
            }

            // Sync vers le profil utilisateur
            $syncUser = [];
            if ($request->filled('pays'))   $syncUser['pays']   = $request->pays;
            if ($request->filled('metier')) $syncUser['metier'] = $request->metier;
            if ($photoPath)                 $syncUser['avatar'] = $photoPath;
            if (!empty($syncUser))          $user->update($syncUser);

            // Sync seulement les champs compatibles avec candidat_profils
            // Note: disponibilite du formulaire dépôt utilise les codes CVthèque (en_recherche/ouvert/indisponible)
            // qui sont différents de l'ENUM candidat_profils (immediatement/1_mois/etc.) → ne pas synchroniser
            $profilSync = [];
            if ($request->filled('nom'))   $profilSync['titre_professionnel'] = $request->nom;
            if ($request->filled('ville')) $profilSync['ville']               = $request->ville;
            if (!empty($profilSync)) {
                CandidatProfil::updateOrCreate(['user_id' => $user->id], $profilSync);
            }

            return redirect()->route('candidat.cvs')->with('success', 'Votre CV a été publié avec succès !');
        }

        $request->validate([
            'fichier_path' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png,webp|max:5120',
        ]);

        $path = $request->file('fichier_path')->store('candidats/documents', 'public');

        $user->documents()->create([
            'type_document_id' => $request->type_document_id,
            'nom'              => $request->nom,
            'fichier'          => $path,
            'pays'             => $request->pays,
            'ville'            => $request->ville,
            'competences'      => $request->competences,
            'experience'       => $request->experience,
            'formation'        => $request->formation,
            'langues'          => $request->langues,
        ]);

        return redirect()->route('candidat.cvs')->with('success', 'Document ajouté avec succès !');
    }

    // ── Espace candidat ───────────────────────────────────
    public function index()
    {
        $user           = Auth::user();
        $cvs            = $user->cvs()->latest()->get();
        $documents      = $user->documents()->with('type')->latest()->get();
        $typesDocuments = TypeDocument::actif()->get();
        $total          = $cvs->count() + $documents->count();
        $quota          = $this->cvQuota($user, $cvs->count());

        return view('candidat.cvs', compact('cvs', 'documents', 'typesDocuments', 'total', 'quota'));
    }

    public function edit(CV $cv)
    {
        $this->authorize('update', $cv);
        $competences             = Competence::orderBy('nom')->pluck('nom');
        $languesCandidatActuelles = LangueCandidat::where('candidat_id', $cv->candidat_id)
            ->with(['langue', 'niveau'])
            ->get();
        return view('candidat.cv-edit', compact('cv', 'competences', 'languesCandidatActuelles'));
    }

    public function update(Request $request, CV $cv)
    {
        $this->authorize('update', $cv);

        $request->validate([
            'titre_poste'       => 'required|string|max:200',
            'photo'             => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'pays'              => 'required|string|max:100',
            'ville'             => 'nullable|string|max:100',
            'disponibilite'     => 'nullable|string|max:50',
            'secteur'           => 'nullable|string|max:150',
            'metier'            => 'nullable|string|max:150',
            'niveau_experience' => 'nullable|string|max:100',
            'niveau_etude'      => 'nullable|string|max:100',
            'type_contrat'      => 'nullable|string|max:50',
            'competences'       => 'nullable|string',
            'experience'        => 'nullable|string',
            'formation'         => 'nullable|string',
            'langues_ids'       => 'nullable|array',
            'langues_ids.*'     => 'nullable|exists:langues,id',
            'niveaux_ids'       => 'nullable|array',
            'niveaux_ids.*'     => 'nullable|exists:niveaux_langue,id',
            'fichier_path'      => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,webp|max:5120',
        ]);

        [$languesTexte, $languesSync] = $this->buildLanguesData(
            $request->input('langues_ids', []),
            $request->input('niveaux_ids', [])
        );

        $data = $request->only([
            'titre_poste',
            'pays',
            'ville',
            'disponibilite',
            'secteur',
            'metier',
            'niveau_experience',
            'niveau_etude',
            'type_contrat',
            'competences',
            'experience',
            'formation',
        ]);
        $data['langues'] = $languesTexte;

        if ($request->hasFile('photo')) {
            if ($cv->photo) Storage::disk('public')->delete($cv->photo);
            $data['photo'] = $request->file('photo')->store('cvs/photos', 'public');
        }

        if ($request->hasFile('fichier_path')) {
            if ($cv->fichier_path) Storage::disk('public')->delete($cv->fichier_path);
            $data['fichier_path'] = $request->file('fichier_path')->store('cvs', 'public');
        }

        $cv->update($data);

        // Sync langues vers la table pivot
        $user = Auth::user();
        $user->langues()->sync($languesSync);

        // Sync vers le profil utilisateur
        $syncUser  = [];
        if ($request->filled('pays'))   $syncUser['pays']   = $request->pays;
        if ($request->filled('metier')) $syncUser['metier'] = $request->metier;
        if (isset($data['photo']))      $syncUser['avatar'] = $data['photo'];
        if (!empty($syncUser))          $user->update($syncUser);

        $profilSync = [];
        if ($request->filled('titre_poste'))   $profilSync['titre_professionnel'] = $request->titre_poste;
        if ($request->filled('ville'))          $profilSync['ville']               = $request->ville;
        if ($request->filled('disponibilite')) $profilSync['disponibilite']       = $request->disponibilite;
        if (!empty($profilSync)) {
            CandidatProfil::updateOrCreate(['user_id' => $user->id], $profilSync);
        }

        return redirect()->route('candidat.cvs')->with('success', 'CV mis à jour.');
    }

    public function toggleVisibilite(CV $cv)
    {
        $this->authorize('update', $cv);
        $cv->update(['visible' => !$cv->visible]);
        $msg = $cv->visible
            ? 'Votre CV est maintenant visible dans la CVthèque.'
            : 'Votre CV est masqué de la CVthèque.';
        return back()->with('success', $msg);
    }

    public function destroy(CV $cv)
    {
        $this->authorize('delete', $cv);
        $cv->delete();
        return redirect()->route('candidat.cvs')->with('success', 'CV supprimé.');
    }

    // ── Langues helper ────────────────────────────────────
    private function buildLanguesData(array $languesIds, array $niveauxIds): array
    {
        $textes = [];
        $sync   = [];
        // Charger tous les IDs en une seule requête
        $langueMap  = Langue::whereIn('id', array_filter($languesIds))->pluck('nom', 'id');
        $niveauMap  = NiveauLangue::whereIn('id', array_filter($niveauxIds))->pluck('libelle', 'id');

        foreach ($languesIds as $i => $langueId) {
            if (!$langueId) continue;
            $niveauId = $niveauxIds[$i] ?? null;
            if (!$niveauId) continue; // niveau requis (NOT NULL en BDD)
            if (!isset($langueMap[$langueId])) continue;
            $textes[] = $langueMap[$langueId] . ' (' . ($niveauMap[$niveauId] ?? '') . ')';
            $sync[$langueId] = ['niveau_id' => $niveauId];
        }
        return [implode(', ', $textes), $sync];
    }

    // ── Quota helper (compte uniquement les CVs, pas les documents) ──
    private function cvQuota(User $user, ?int $alreadyCountedTotal = null): array
    {
        $abonnement = $user->abonnementActif()->with('plan.features')->first();
        $total      = $alreadyCountedTotal ?? $user->cvs()->count();
        $limit      = $abonnement ? (int) ($abonnement->plan?->getFeature('cv_limit', 1) ?? 1) : 1;
        $unlimited  = $limit === 0;
        return [
            'used'      => $total,
            'limit'     => $limit,
            'unlimited' => $unlimited,
            'reached'   => !$unlimited && $total >= $limit,
            'remaining' => $unlimited ? null : max(0, $limit - $total),
        ];
    }
}
