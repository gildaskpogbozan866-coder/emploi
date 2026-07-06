<?php

namespace App\Http\Controllers\Candidat;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\CandidatProfil;
use App\Models\Competence;
use App\Models\CV;
use App\Models\CvConsultation;
use App\Models\Langue;
use App\Models\LangueCandidat;
use App\Models\NiveauEtude;
use App\Models\NiveauExperience;
use App\Models\NiveauLangue;
use App\Models\TypeContrat;
use App\Models\TypeDocument;
use App\Models\User;
use App\Notifications\NouveauCVDeposeNotification;
use App\Services\CvQuotaService;
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
        $cvQuery = CV::visible()->whereHas('candidat', fn($q) => $q->where('actif', true))->with('candidat')->latest();

        if ($request->filled('q')) {
            $q = $request->q;
            $cvQuery->where(function ($sq) use ($q) {
                $sq->where('competences', 'like', "%$q%")
                    ->orWhere('metier', 'like', "%$q%")
                    ->orWhere('resume', 'like', "%$q%");
            });
        }
        if ($request->filled('langue'))           $cvQuery->where('langues', 'like', '%' . $request->langue . '%');
        if ($request->filled('metier'))           $cvQuery->where('metier', 'like', '%' . $request->metier . '%');
        if ($request->filled('niveau_etude'))     $cvQuery->where('niveau_etude', $request->niveau_etude);
        if ($request->filled('type_contrat'))     $cvQuery->where('type_contrat', $request->type_contrat);
        if ($request->filled('niveau_experience')) $cvQuery->where('niveau_experience', $request->niveau_experience);

        $cvResults = $cvQuery->get()->map(function ($cv) {
            $cv->_is_document = false;
            return $cv;
        });

        // Les documents (diplômes, attestations…) ne sont pas des CV : ils
        // n'ont aucun mécanisme de consentement/visibilité et ne doivent pas
        // apparaître dans une recherche publique. Rien n'est supprimé côté
        // données — ils restent consultables par leur propriétaire et l'admin.
        $merged = $cvResults->sortByDesc('created_at')->values();
        $page   = (int) $request->get('page', 1);
        $perPage = 12;
        $cvs = new LengthAwarePaginator(
            $merged->forPage($page, $perPage)->values(),
            $merged->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $dejaAchetesIds = [];
        if (Auth::check() && Auth::user()->hasRole(Role::RECRUTEUR)) {
            $dejaAchetesIds = CvConsultation::where('recruteur_id', Auth::id())->pluck('cv_id')->toArray();
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html'  => view('public.cv._theque_liste', compact('cvs', 'dejaAchetesIds'))->render(),
                'total' => $cvs->total(),
                'pages' => $cvs->lastPage(),
            ]);
        }

        return view('public.cv.theque', compact('cvs', 'dejaAchetesIds'));
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

        // Sécurité : le profil doit exister ET le candidat doit avoir publié
        // au moins un CV visible — remplir son profil seul ne suffit pas à
        // apparaître publiquement.
        abort_if(is_null($candidat->candidatProfil), 404);
        abort_if(
            !$candidat->cvs->contains(fn ($cv) => $cv->visible && $cv->publie_le !== null),
            404
        );

        $libelles = \App\Models\CandidatProfil::libelles();

        return view('public.cv.candidat-detail', compact('candidat', 'libelles'));
    }

    public function detail(CV $cv)
    {
        if (!$cv->visible || is_null($cv->publie_le)) {
            abort(404);
        }

        $cv->load('candidat');

        $debloque = false;
        if (Auth::check() && Auth::user()->hasRole(Role::RECRUTEUR)) {
            $recruteur = Auth::user();
            $dejaAchete = CvConsultation::where('recruteur_id', $recruteur->id)->where('cv_id', $cv->id)->exists();

            if ($dejaAchete) {
                $debloque = true;
            } elseif ($recruteur->cv_credits > 0) {
                CvConsultation::firstOrCreate(['recruteur_id' => $recruteur->id, 'cv_id' => $cv->id]);
                $debloque = true;
            }
        }

        return view('public.cv.detail', compact('cv', 'debloque'));
    }

    public function tarif()
    {
        $packs = \App\Models\CreditCvPack::actif()->orderBy('ordre')->orderBy('credits')->get();
        return view('public.cv.tarif', compact('packs'));
    }

    public function depot(CvQuotaService $quotaService)
    {
        if (!Auth::check()) {
            return redirect()->route('auth.connexion')->with('redirect_after', route('cv.public.depot'));
        }

        if (!Auth::user()->hasRole(Role::CANDIDAT)) {
            return redirect()->route(match (true) {
                Auth::user()->hasRole(Role::RECRUTEUR) => 'recruteur.dashboard',
                Auth::user()->hasRole(Role::ADMIN)      => 'admin.dashboard',
                default => 'home',
            })->with('error', 'Seuls les candidats peuvent déposer un CV.');
        }

        $user  = Auth::user();
        // Le quota ne limite que les CV — ne pas bloquer l'accès au formulaire,
        // qui sert aussi à déposer des documents (diplôme, attestation...) non
        // concernés par cette limite. Le blocage réel se fait dans store().
        $quota = $quotaService->quotaFor($user);

        $typesDocuments    = TypeDocument::actif()->get();
        $competences       = Competence::orderBy('nom')->pluck('nom');
        $typeCV            = TypeDocument::where('nom', 'like', '%Curriculum Vitae%')->first();
        $typeCVId          = $typeCV?->id ?? 1;
        $languesList       = Langue::orderBy('nom')->get();
        $niveauxLangueList = NiveauLangue::orderBy('libelle')->get();
        $niveauxEtude      = NiveauEtude::orderBy('ordre')->get();
        $niveauxExperience = NiveauExperience::orderBy('ordre')->get();
        $typesContrats     = TypeContrat::orderBy('libelle')->get();

        return view('public.cv.depot', compact(
            'typesDocuments', 'competences', 'quota', 'typeCVId',
            'languesList', 'niveauxLangueList', 'niveauxEtude', 'niveauxExperience', 'typesContrats'
        ));
    }

    public function store(Request $request, CvQuotaService $quotaService)
    {
        if (!Auth::check()) {
            return redirect()->route('auth.connexion');
        }

        if (!Auth::user()->hasRole(Role::CANDIDAT)) {
            return redirect()->route(match (true) {
                Auth::user()->hasRole(Role::RECRUTEUR) => 'recruteur.dashboard',
                Auth::user()->hasRole(Role::ADMIN)      => 'admin.dashboard',
                default => 'home',
            })->with('error', 'Seuls les candidats peuvent déposer un CV.');
        }

        $user   = Auth::user();
        $typeCV = TypeDocument::where('nom', 'like', '%Curriculum Vitae%')->first();
        $estCV  = $typeCV && $request->type_document_id == $typeCV->id;

        // Le quota du plan ne limite que le nombre de CV — les autres types de
        // documents (diplôme, attestation...) ne doivent jamais être bloqués par lui.
        if ($estCV) {
            $quota = $quotaService->quotaFor($user);
            if ($quota['reached']) {
                return redirect()->route('candidat.abonnement.plans')
                    ->with('info', "Vous avez atteint la limite de {$quota['limit']} CV de votre plan. Passez à un plan supérieur pour en ajouter davantage.");
            }
        }

        // Niveau d'études, niveau d'expérience, compétences et type de contrat
        // ne sont obligatoires que pour un CV — un dépôt de diplôme/attestation/etc.
        // n'a pas à renseigner un "poste visé" ou des compétences.
        $request->validate([
            'prenom'              => 'required|string|max:100',
            'nom_famille'         => 'required|string|max:100',
            'tel'                 => 'required|string|max:30',
            'disponibilite'       => 'required|in:immediatement,1_mois,2_mois,3_mois,plus_3_mois',
            'type_document_id'    => 'required|exists:type_documents,id',
            'nom'                 => 'required|string|max:200',
            'resume'              => [$estCV ? 'required' : 'nullable', 'string', 'max:2000'],
            'photo'               => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'pays'                => [$estCV ? 'required' : 'nullable', 'string', 'max:100'],
            'ville'               => 'required|string|max:100',
            'types_contrat_ids'   => [$estCV ? 'required' : 'nullable', 'array', $estCV ? 'min:1' : 'nullable'],
            'types_contrat_ids.*' => $estCV ? 'required|exists:type_contrats,id' : 'nullable|exists:type_contrats,id',
            'niveau_etude_id'     => [$estCV ? 'required' : 'nullable', 'exists:niveaux_etudes,id'],
            'niveau_experience_id'=> [$estCV ? 'required' : 'nullable', 'exists:niveaux_experience,id'],
            'competences'         => $estCV ? 'required|string' : 'nullable|string',
            'experience'          => $estCV ? 'required|string' : 'nullable|string',
            'formation'           => $estCV ? 'required|string' : 'nullable|string',
            'langues_ids'         => [$estCV ? 'required' : 'nullable', 'array', $estCV ? 'min:1' : 'nullable'],
            'langues_ids.*'       => $estCV ? 'required|exists:langues,id' : 'nullable|exists:langues,id',
            'niveaux_ids'         => [$estCV ? 'required' : 'nullable', 'array', $estCV ? 'min:1' : 'nullable'],
            'niveaux_ids.*'       => $estCV ? 'required|exists:niveaux_langue,id' : 'nullable|exists:niveaux_langue,id',
            'fichier_path'        => 'required|file|mimes:pdf|max:5120',
        ], [
            'prenom.required'           => 'Le prénom est obligatoire.',
            'nom_famille.required'      => 'Le nom de famille est obligatoire.',
            'tel.required'              => 'Le numéro de téléphone est obligatoire.',
            'disponibilite.required'    => 'La disponibilité est obligatoire.',
            'ville.required'            => 'La ville est obligatoire.',
            'resume.required'           => 'Le résumé / présentation est obligatoire.',
            'pays.required'             => 'Le pays est obligatoire.',
            'types_contrat_ids.required' => 'Sélectionnez au moins un type de contrat souhaité.',
            'types_contrat_ids.min'      => 'Sélectionnez au moins un type de contrat souhaité.',
            'niveau_etude_id.required'   => 'Le niveau d\'études est obligatoire.',
            'niveau_experience_id.required' => 'Le niveau d\'expérience est obligatoire.',
            'competences.required'      => 'Indiquez au moins une compétence.',
            'experience.required'       => 'Ajoutez au moins une expérience professionnelle.',
            'formation.required'        => 'Ajoutez au moins une formation.',
            'langues_ids.required'      => 'Ajoutez au moins une langue.',
            'langues_ids.min'           => 'Ajoutez au moins une langue.',
            'niveaux_ids.required'      => 'Précisez le niveau de chaque langue ajoutée.',
            'niveaux_ids.min'           => 'Précisez le niveau de chaque langue ajoutée.',
            'fichier_path.required'     => 'Le fichier PDF est obligatoire.',
            'fichier_path.mimes'        => 'Le fichier doit être au format PDF.',
        ]);

        // Fichier obligatoire pour tous les types
        $fichierPath = $request->file('fichier_path')->store('cvs', 'public');

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('cvs/photos', 'public');
        }

        // Conversion IDs → texte pour stockage
        [$typeContratTexte, $niveauEtudeTexte, $niveauExpTexte] = $this->convertirChampsCvTexte(
            $request->input('types_contrat_ids', []),
            $request->input('niveau_etude_id'),
            $request->input('niveau_experience_id')
        );

        [$languesTexte, $languesSync] = $this->buildLanguesData(
            $request->input('langues_ids', []),
            $request->input('niveaux_ids', [])
        );

        if ($estCV) {
            $cv = CV::create([
                'candidat_id'       => Auth::id(),
                'fichier_path'      => $fichierPath,
                'photo'             => $photoPath ?: $user->avatar,
                'plan'              => 'gratuit',
                'visible'           => true,
                'publie_le'         => now(),
                'ville'             => $request->ville,
                'metier'            => $request->nom,
                'resume'            => $request->resume,
                'competences'       => $request->competences,
                'experience'        => $request->experience,
                'formation'         => $request->formation,
                'langues'           => $languesTexte ?: null,
                'niveau_etude'      => $niveauEtudeTexte ?: null,
                'type_contrat'      => $typeContratTexte ?: null,
                'niveau_experience' => $niveauExpTexte ?: null,
            ]);

            if (!empty($languesSync)) {
                $user->langues()->sync($languesSync);
            }

            $syncUser = [
                'prenom' => $request->prenom,
                'nom'    => $request->nom_famille,
                'tel'    => $request->tel,
            ];
            if ($request->filled('pays')) $syncUser['pays']   = $request->pays;
            if ($photoPath)               $syncUser['avatar'] = $photoPath;
            $user->update($syncUser);

            $profilSync = [
                'titre_professionnel' => $request->nom,
                'disponibilite'       => $request->disponibilite,
                'ville'               => $request->ville,
            ];
            if ($request->filled('resume')) $profilSync['bio'] = $request->resume;
            CandidatProfil::updateOrCreate(['user_id' => $user->id], $profilSync);

            foreach (User::role('admin')->get() as $admin) {
                try {
                    $admin->notify(new NouveauCVDeposeNotification($cv, $user));
                } catch (\Throwable $e) {
                    Log::warning('Notification admin CV non envoyée', ['error' => $e->getMessage()]);
                }
            }

            // Notification quota restant
            $quotaApres = $quotaService->quotaFor($user);
            $successMsg = 'Votre CV a été publié avec succès dans la CVthèque !';
            if (!$quotaApres['unlimited']) {
                if ($quotaApres['remaining'] === 0) {
                    $successMsg .= ' Vous avez atteint la limite de votre plan.';
                } elseif ($quotaApres['remaining'] > 0) {
                    $successMsg .= " Il vous reste {$quotaApres['remaining']} dépôt(s) disponible(s) sur votre plan.";
                }
            }

            return redirect()->route('candidat.cvs')->with('success', $successMsg);
        }

        // Document non-CV
        $user->documents()->create([
            'type_document_id' => $request->type_document_id,
            'nom'              => $request->nom,
            'fichier'          => $fichierPath,
            'pays'             => $request->pays,
            'ville'            => $request->ville,
            'competences'      => $request->competences,
            'experience'       => $request->experience,
            'formation'        => $request->formation,
            'langues'          => $languesTexte ?: null,
        ]);

        // Sync infos personnelles
        $syncUser = [
            'prenom' => $request->prenom,
            'nom'    => $request->nom_famille,
            'tel'    => $request->tel,
        ];
        if ($request->filled('pays')) $syncUser['pays'] = $request->pays;
        if ($photoPath)               $syncUser['avatar'] = $photoPath;
        $user->update($syncUser);

        CandidatProfil::updateOrCreate(['user_id' => $user->id], [
            'titre_professionnel' => $request->nom,
            'disponibilite'       => $request->disponibilite,
            'ville'               => $request->ville,
        ]);

        return redirect()->route('candidat.cvs')->with('success', 'Document ajouté avec succès à votre espace candidat.');
    }

    // ── Espace candidat ───────────────────────────────────
    public function index(CvQuotaService $quotaService)
    {
        $user           = Auth::user();
        $cvs            = $user->cvs()->latest()->get();
        $documents      = $user->documents()->with('type')->latest()->get();
        $typesDocuments = TypeDocument::actif()->get();
        $total          = $cvs->count() + $documents->count();
        $quota          = $quotaService->quotaFor($user);

        return view('candidat.cvs', compact('cvs', 'documents', 'typesDocuments', 'total', 'quota'));
    }

    public function edit(CV $cv)
    {
        $this->authorize('update', $cv);
        $competences             = Competence::orderBy('nom')->pluck('nom');
        $languesCandidatActuelles = LangueCandidat::where('candidat_id', $cv->candidat_id)
            ->with(['langue', 'niveau'])
            ->get();
        $languesList       = Langue::orderBy('nom')->get();
        $niveauxLangueList = NiveauLangue::orderBy('libelle')->get();
        $niveauxEtude      = NiveauEtude::orderBy('ordre')->get();
        $niveauxExperience = NiveauExperience::orderBy('ordre')->get();
        $typesContrats     = TypeContrat::orderBy('libelle')->get();

        // Le CV ne stocke que le libellé texte (pas l'ID) pour ces champs — on
        // remonte aux IDs correspondants pour pré-cocher/pré-sélectionner le formulaire.
        $typesContratSelectionnes = $cv->type_contrat
            ? $typesContrats->whereIn('libelle', array_map('trim', explode(',', $cv->type_contrat)))->pluck('id')->all()
            : [];
        $niveauEtudeSelectionne      = $niveauxEtude->firstWhere('libelle', $cv->niveau_etude)?->id;
        $niveauExperienceSelectionne = $niveauxExperience->firstWhere('libelle', $cv->niveau_experience)?->id;

        return view('candidat.cv-edit', compact(
            'cv', 'competences', 'languesCandidatActuelles',
            'languesList', 'niveauxLangueList', 'niveauxEtude', 'niveauxExperience', 'typesContrats',
            'typesContratSelectionnes', 'niveauEtudeSelectionne', 'niveauExperienceSelectionne'
        ));
    }

    public function update(Request $request, CV $cv)
    {
        $this->authorize('update', $cv);

        $request->validate([
            'photo'                => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'ville'                => 'required|string|max:100',
            'metier'               => 'required|string|max:150',
            'resume'               => 'required|string|max:2000',
            'types_contrat_ids'    => 'required|array|min:1',
            'types_contrat_ids.*'  => 'required|exists:type_contrats,id',
            'niveau_etude_id'      => 'required|exists:niveaux_etudes,id',
            'niveau_experience_id' => 'required|exists:niveaux_experience,id',
            'competences'          => 'required|string',
            'experience'           => 'required|string',
            'formation'            => 'required|string',
            'langues_ids'          => 'required|array|min:1',
            'langues_ids.*'        => 'required|exists:langues,id',
            'niveaux_ids'          => 'required|array|min:1',
            'niveaux_ids.*'        => 'required|exists:niveaux_langue,id',
            'fichier_path'         => 'nullable|file|mimes:pdf|max:5120',
        ], [
            'ville.required'             => 'La ville est obligatoire.',
            'metier.required'            => 'L\'intitulé / poste visé est obligatoire.',
            'resume.required'            => 'Le résumé / présentation est obligatoire.',
            'types_contrat_ids.required' => 'Sélectionnez au moins un type de contrat souhaité.',
            'types_contrat_ids.min'      => 'Sélectionnez au moins un type de contrat souhaité.',
            'niveau_etude_id.required'   => 'Le niveau d\'études est obligatoire.',
            'niveau_experience_id.required' => 'Le niveau d\'expérience est obligatoire.',
            'competences.required'      => 'Indiquez au moins une compétence.',
            'experience.required'       => 'Ajoutez au moins une expérience professionnelle.',
            'formation.required'        => 'Ajoutez au moins une formation.',
            'langues_ids.required'      => 'Ajoutez au moins une langue.',
            'langues_ids.min'           => 'Ajoutez au moins une langue.',
            'niveaux_ids.required'      => 'Précisez le niveau de chaque langue ajoutée.',
            'niveaux_ids.min'           => 'Précisez le niveau de chaque langue ajoutée.',
            'fichier_path.mimes'        => 'Le fichier doit être au format PDF.',
        ]);

        [$typeContratTexte, $niveauEtudeTexte, $niveauExpTexte] = $this->convertirChampsCvTexte(
            $request->input('types_contrat_ids', []),
            $request->input('niveau_etude_id'),
            $request->input('niveau_experience_id')
        );

        [$languesTexte, $languesSync] = $this->buildLanguesData(
            $request->input('langues_ids', []),
            $request->input('niveaux_ids', [])
        );

        $data = $request->only(['ville', 'metier', 'resume', 'competences', 'experience', 'formation']);
        $data['type_contrat']      = $typeContratTexte ?: null;
        $data['niveau_etude']      = $niveauEtudeTexte ?: null;
        $data['niveau_experience'] = $niveauExpTexte ?: null;
        $data['langues']           = $languesTexte;

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
        if ($request->filled('metier')) $syncUser['metier'] = $request->metier;
        if (isset($data['photo']))      $syncUser['avatar'] = $data['photo'];
        if (!empty($syncUser))          $user->update($syncUser);

        $profilSync = [];
        if ($request->filled('ville')) $profilSync['ville'] = $request->ville;
        if (!empty($profilSync)) {
            CandidatProfil::updateOrCreate(['user_id' => $user->id], $profilSync);
        }

        return redirect()->route('candidat.cvs')->with('success', 'CV mis à jour.');
    }

    public function toggleVisibilite(CV $cv)
    {
        $this->authorize('update', $cv);
        $devientVisible = !$cv->visible;

        if ($devientVisible && !$cv->estComplet()) {
            return back()->with('error', 'Complétez votre CV (métier, ville, résumé, compétences, expérience, formation, langues, photo...) avant de le rendre visible dans la CVthèque.');
        }

        $data = ['visible' => $devientVisible];
        // publie_le conditionne aussi l'apparition en CVthèque (CV::scopeVisible) —
        // un CV jamais passé par le dépôt normal (ex. créé pendant une candidature)
        // ne l'a pas encore ; on le renseigne ici pour que "Rendre visible" publie
        // vraiment, au lieu de rester un bouton sans effet réel.
        if ($devientVisible && !$cv->publie_le) {
            $data['publie_le'] = now();
        }
        $cv->update($data);
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

    // ── Conversion IDs de référentiels → texte stocké sur le CV ──
    private function convertirChampsCvTexte(array $typesContratIds, ?int $niveauEtudeId, ?int $niveauExperienceId): array
    {
        $typeContratTexte = '';
        if (!empty($typesContratIds)) {
            $typeContratTexte = TypeContrat::whereIn('id', $typesContratIds)->pluck('libelle')->join(', ');
        }

        $niveauEtudeTexte = $niveauEtudeId ? (NiveauEtude::find($niveauEtudeId)?->libelle ?? '') : '';
        $niveauExpTexte   = $niveauExperienceId ? (NiveauExperience::find($niveauExperienceId)?->libelle ?? '') : '';

        return [$typeContratTexte, $niveauEtudeTexte, $niveauExpTexte];
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
}
