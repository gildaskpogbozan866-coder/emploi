<?php

namespace App\Http\Controllers\Public;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\Candidature;
use App\Models\CV;
use App\Models\Document;
use App\Events\CandidatureDeposee;
use App\Models\Offre;
use App\Models\TypeContrat;
use App\Notifications\CandidatureRecueNotification;
use App\Rules\DateLimiteWithinAbonnement;
use App\Services\CvQuotaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class OffreController extends Controller
{
    public function index(Request $request)
    {
        $query = Offre::affichable()->with(['recruteur', 'competences'])->recente();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sq) use ($q) {
                $sq->where('titre', 'like', "%$q%")
                   ->orWhere('entreprise', 'like', "%$q%")
                   ->orWhere('description', 'like', "%$q%");
            });
        }

        if ($request->filled('type')) {
            $types = (array) $request->type;
            $query->whereHas('type', fn($q) => $q->whereIn('code', $types));
        }

        if ($request->filled('localisation')) {
            $locs = (array) $request->localisation;
            $query->where(function ($sq) use ($locs) {
                foreach ($locs as $loc) {
                    $sq->orWhere('localisation', 'like', '%'.$loc.'%');
                }
            });
        }

        if ($request->filled('secteur')) {
            $sects = (array) $request->secteur;
            $query->where(function ($sq) use ($sects) {
                foreach ($sects as $s) {
                    $sq->orWhere('secteur', 'like', '%'.$s.'%');
                }
            });
        }

        if ($request->filled('competence')) {
            $query->whereHas('competences', fn($q) => $q->whereIn('slug', (array) $request->competence));
        }

        if ($request->filled('metier')) {
            $metiers = (array) $request->metier;
            $query->where(function ($sq) use ($metiers) {
                foreach ($metiers as $m) {
                    $sq->orWhereHas('metier', fn($q) => $q->where('nom', 'like', '%'.$m.'%'))
                       ->orWhere('titre', 'like', '%'.$m.'%');
                }
            });
        }

        if ($request->filled('niveau_experience')) {
            $query->whereIn('niveau_experience', (array) $request->niveau_experience);
        }

        if ($request->filled('niveau_etude')) {
            $query->whereIn('niveau_etude', (array) $request->niveau_etude);
        }

        $offres = $query->paginate(12)->withQueryString();

        $competences = \App\Models\Competence::orderBy('nom')->get();

        return view('public.offre.list', compact('offres', 'competences'));
    }

    public function detail(Offre $offre)
    {
        // Offre expirée/close/suspendue : plus aucune information ne doit rester accessible,
        // y compris par URL directe — sauf pour le candidat qui y a déjà postulé, qui doit
        // pouvoir la retrouver depuis son propre espace (historique de candidatures).
        $aDejaPostule = Auth::check()
            && Candidature::where('offre_id', $offre->id)->where('candidat_id', Auth::id())->exists();

        if ($offre->aExpire() && !$aDejaPostule) {
            return redirect()->route('offre.list');
        }

        // Une seule vue par session ; le recruteur propriétaire ne compte pas
        $sessionKey = 'vu_offre_' . $offre->id;
        if (!session()->has($sessionKey) && (int) Auth::id() !== (int) $offre->recruteur_id) {
            $offre->increment('vues');
            session()->put($sessionKey, true);
        }

        $offre->load(['recruteur', 'competences']);
        $aPostule      = $aDejaPostule;
        $estSauvegarde = false;

        if (Auth::check()) {
            $estSauvegarde = Auth::user()->offresSauvegardees()->where('offre_id', $offre->id)->exists();
        }

        $competenceIds = $offre->competences->pluck('id');
        $similaires = Offre::active()
            ->with('recruteur')
            ->where('id', '!=', $offre->id)
            ->where(function ($q) use ($offre, $competenceIds) {
                $q->where('secteur', $offre->secteur)
                  ->orWhere('type_contrat_id', $offre->type_contrat_id)
                  ->orWhere('localisation', 'like', '%' . explode(',', $offre->localisation)[0] . '%');
                if ($competenceIds->isNotEmpty()) {
                    $q->orWhereHas('competences', fn($sq) => $sq->whereIn('competences.id', $competenceIds));
                }
            })
            ->latest()
            ->limit(4)
            ->get();

        return view('public.offre.detail', compact('offre', 'aPostule', 'estSauvegarde', 'similaires'));
    }

    public function postuler(Offre $offre)
    {
        if (!Auth::check()) {
            return redirect()->guest(route('auth.connexion'));
        }

        if (!Auth::user()->hasRole(Role::CANDIDAT)) {
            $dashboard = match (true) {
                Auth::user()->hasRole(Role::RECRUTEUR) => route('recruteur.dashboard'),
                Auth::user()->hasRole(Role::ADMIN)      => route('admin.dashboard'),
                default => route('home'),
            };
            return redirect($dashboard)->with('error', 'Seuls les candidats peuvent postuler à une offre.');
        }

        if ($offre->aExpire()) {
            return redirect()->route('offre.list');
        }

        $aPostule = Candidature::where('offre_id', $offre->id)
            ->where('candidat_id', Auth::id())
            ->exists();

        $cvs       = Auth::user()->cvs()->orderByDesc('created_at')->get();
        $documents = Auth::user()->documents()->with('type')->orderByDesc('created_at')->get();

        $offre->load('typesDocumentsRequis');
        $documentsRequisParType  = $documents->whereIn('type_document_id', $offre->typesDocumentsRequis->pluck('id'))->groupBy('type_document_id');
        // Pièces "libres" proposées dans la section optionnelle : celles dont le type
        // n'est pas déjà couvert par un bloc "requis" dédié, pour ne pas les montrer deux fois.
        $documentsLibres = $documents->whereNotIn('type_document_id', $offre->typesDocumentsRequis->pluck('id'));

        return view('public.offre.postuler', compact('offre', 'aPostule', 'cvs', 'documents', 'documentsRequisParType', 'documentsLibres'));
    }

    public function storerCandidature(Request $request, Offre $offre, CvQuotaService $quotaService)
    {
        if (!Auth::check()) {
            return redirect()->route('auth.connexion');
        }

        if (!Auth::user()->hasRole(Role::CANDIDAT)) {
            return redirect()->route('home')->with('error', 'Seuls les candidats peuvent postuler à une offre.');
        }

        if ($offre->aExpire()) {
            return redirect()->route('offre.list');
        }

        if (Candidature::where('offre_id', $offre->id)->where('candidat_id', Auth::id())->exists()) {
            return back()->with('error_duplicate', true);
        }

        $offre->load('typesDocumentsRequis');

        $rules = [
            'message_motivation'  => 'nullable|string|max:3000',
            'cv_id'                => 'nullable|integer|exists:cvs,id',
            'document_id'          => 'nullable|integer|exists:documents,id',
            'cv_file'              => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'lettre_file'          => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'pieces_ids'           => 'nullable|array',
            'pieces_ids.*'         => 'integer|exists:documents,id',
            'pieces_existantes'    => 'nullable|array',
            'pieces_existantes.*'  => 'nullable|integer|exists:documents,id',
            'pieces_nouvelles'     => 'nullable|array',
            'pieces_nouvelles.*'   => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,webp|max:5120',
        ];

        // Validation conditionnelle selon les exigences de l'offre
        if ($offre->exige_cv) {
            $rules['cv_file'] = [
                function ($attribute, $value, $fail) use ($request) {
                    $hasCv = $request->filled('cv_id') || $request->filled('document_id') || $request->hasFile('cv_file');
                    if (!$hasCv) {
                        $fail('Un CV est requis pour postuler à cette offre.');
                    }
                },
            ];
        }

        if ($offre->exige_lettre) {
            $rules['lettre_file'] = 'required|file|mimes:pdf,doc,docx|max:5120';
        }

        $request->validate($rules, [
            'lettre_file.required' => 'Une lettre de motivation est requise pour postuler à cette offre.',
            'lettre_file.file'     => 'La lettre de motivation doit être un fichier valide.',
            'lettre_file.mimes'    => 'La lettre doit être au format PDF, DOC ou DOCX.',
            'lettre_file.max'      => 'La lettre ne doit pas dépasser 5 Mo.',
        ]);

        // Chaque type de document requis par l'offre doit avoir soit un document
        // existant sélectionné, soit un nouveau fichier téléversé.
        $manquants = [];
        foreach ($offre->typesDocumentsRequis as $typeRequis) {
            $existant = $request->input("pieces_existantes.{$typeRequis->id}");
            $nouveau  = $request->file("pieces_nouvelles.{$typeRequis->id}");
            if (!$existant && !$nouveau) {
                $manquants["pieces_nouvelles.{$typeRequis->id}"] = "Le document « {$typeRequis->nom} » est requis pour postuler à cette offre.";
            }
        }
        if (!empty($manquants)) {
            throw \Illuminate\Validation\ValidationException::withMessages($manquants);
        }

        // Un CV téléversé à la volée (aucun CV/document existant sélectionné) devient
        // un vrai CV dans l'espace du candidat — il compte donc dans le quota de son
        // plan, au même titre qu'un dépôt normal depuis "Mes CV". Sinon la limite de
        // CV du plan serait contournable en passant simplement par une candidature.
        if (!$request->filled('cv_id') && !$request->filled('document_id') && $request->hasFile('cv_file')) {
            $quota = $quotaService->quotaFor(Auth::user());
            if ($quota['reached']) {
                throw ValidationException::withMessages([
                    'cv_file' => "Vous avez atteint la limite de {$quota['limit']} CV de votre plan. Choisissez un de vos CV existants ci-dessus, ou passez à un plan supérieur pour en ajouter un nouveau.",
                ]);
            }
        }

        $cvId          = null;
        $cvPath        = null;
        $lettrePath    = null;
        $nouveauCvId   = null;

        if ($request->filled('cv_id')) {
            $cv   = CV::where('id', $request->cv_id)->where('candidat_id', Auth::id())->first();
            $cvId = $cv?->id;
        } elseif ($request->filled('document_id')) {
            $doc    = Document::where('id', $request->document_id)->where('user_id', Auth::id())->first();
            $cvPath = $doc?->fichier;
        } elseif ($request->hasFile('cv_file')) {
            // Fichier persisté comme un vrai CV dans l'espace du candidat (pas juste
            // rattaché à cette candidature) — masqué par défaut : il lui manque les
            // champs obligatoires du dépôt normal (métier, ville, compétences...),
            // le candidat doit le compléter et le publier lui-même depuis "Mes CV".
            $cv = CV::create([
                'candidat_id'  => Auth::id(),
                'fichier_path' => $request->file('cv_file')->store('cvs', 'public'),
                'plan'         => 'gratuit',
                'visible'      => false,
            ]);
            $cvId        = $cv->id;
            $nouveauCvId = $cv->id;
        }

        if ($request->hasFile('lettre_file')) {
            $lettrePath = $request->file('lettre_file')->store('candidatures/lettres', 'public');
        }

        // Instantané du CV au moment de la candidature : le recruteur doit voir le
        // CV tel qu'il était à l'envoi, pas sa version live si le candidat le modifie
        // ensuite (métier/ville changés, ou fichier remplacé — update() supprime
        // l'ancien fichier physique, donc on en copie une copie dédiée à cette
        // candidature plutôt que de ne garder qu'un chemin qui finirait par pointer
        // dans le vide).
        $cvSnapshot = null;
        if (!empty($cv)) {
            $snapshotFichierPath = null;
            if ($cv->fichier_path && Storage::disk('public')->exists($cv->fichier_path)) {
                $snapshotFichierPath = 'candidatures/cvs-snapshot/' . Str::uuid() . '.' . pathinfo($cv->fichier_path, PATHINFO_EXTENSION);
                Storage::disk('public')->copy($cv->fichier_path, $snapshotFichierPath);
            }
            $cvSnapshot = [
                'metier'        => $cv->metier,
                'ville'         => $cv->ville,
                'fichier_path'  => $snapshotFichierPath,
            ];
        }

        $candidature = Candidature::create([
            'offre_id'           => $offre->id,
            'candidat_id'        => Auth::id(),
            'message_motivation' => $request->message_motivation,
            'cv_id'              => $cvId,
            'cv_path'            => $cvPath,
            'cv_snapshot'        => $cvSnapshot,
            'lettre_path'        => $lettrePath,
        ]);

        // Pièces justificatives : celles librement choisies par le candidat (pieces_ids)
        // + celles couvrant les types requis par l'offre (existantes ou nouvellement
        // téléversées — dans ce dernier cas, le document est aussi créé dans l'espace
        // personnel du candidat comme un dépôt normal, pas seulement attaché ici).
        $piecesIds = [];

        if ($request->filled('pieces_ids')) {
            $piecesIds = Document::where('user_id', Auth::id())
                ->whereIn('id', $request->input('pieces_ids'))
                ->pluck('id')->all();
        }

        foreach ($offre->typesDocumentsRequis as $typeRequis) {
            $existantId = $request->input("pieces_existantes.{$typeRequis->id}");
            $nouveauFile = $request->file("pieces_nouvelles.{$typeRequis->id}");

            if ($existantId) {
                $doc = Document::where('id', $existantId)
                    ->where('user_id', Auth::id())
                    ->where('type_document_id', $typeRequis->id)
                    ->first();
                if ($doc) {
                    $piecesIds[] = $doc->id;
                }
            } elseif ($nouveauFile) {
                $path = $nouveauFile->store('candidats/documents', 'public');
                $doc  = Auth::user()->documents()->create([
                    'type_document_id' => $typeRequis->id,
                    'nom'              => $typeRequis->nom . ' — ' . now()->format('d/m/Y'),
                    'fichier'          => $path,
                ]);
                $piecesIds[] = $doc->id;
            }
        }

        if (!empty($piecesIds)) {
            $candidature->documents()->sync(array_unique($piecesIds));
        }

        $candidat = Auth::user();

        // Notifie le recruteur (email + in-app) via Event → Listener en queue
        event(new CandidatureDeposee($candidature));

        // Email de confirmation au candidat (en queue)
        $candidat->notify(new CandidatureRecueNotification($offre));

        $redirect = redirect()->route('offre.candidature-succes', $offre);
        if ($nouveauCvId) {
            $redirect->with('nouveau_cv_id', $nouveauCvId);
        }

        return $redirect;
    }

    public function candidatureSucces(Offre $offre)
    {
        return view('public.offre.candidature-succes', compact('offre'));
    }

    public function publier()
    {
        if (!Auth::check()) {
            return redirect()->route('auth.connexion')->with('redirect_after', route('offre.publier'));
        }

        if (!Auth::user()->hasRole(Role::RECRUTEUR)) {
            return redirect()->route(match (true) {
                Auth::user()->hasRole(Role::CANDIDAT) => 'candidat.dashboard',
                Auth::user()->hasRole(Role::ADMIN)     => 'admin.dashboard',
                default => 'home',
            })->with('error', 'Seuls les recruteurs peuvent publier des offres. Connectez-vous avec un compte recruteur.');
        }

        return view('public.offre.publier');
    }

    public function storerOffre(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('auth.connexion');
        }

        if (!Auth::user()->hasRole(Role::RECRUTEUR)) {
            return redirect()->route(match (true) {
                Auth::user()->hasRole(Role::CANDIDAT) => 'candidat.dashboard',
                Auth::user()->hasRole(Role::ADMIN)     => 'admin.dashboard',
                default => 'home',
            })->with('error', 'Seuls les recruteurs peuvent publier des offres. Connectez-vous avec un compte recruteur.');
        }

        $request->validate([
            'titre'       => 'required|string|max:200',
            'entreprise'  => 'required|string|max:200',
            'localisation'=> 'required|string|max:200',
            'type'        => ['required', \Illuminate\Validation\Rule::in(TypeContrat::pluck('code')->toArray())],
            'description' => 'required|string',
            'date_limite' => ['nullable', 'date', 'after_or_equal:today', new DateLimiteWithinAbonnement(Auth::user())],
        ]);

        // published_at n'est pas renseigné ici : l'offre est encore en_attente,
        // pas publiée. Il sera renseigné par Admin\OffreController::updateStatut()
        // au moment où elle passe réellement à active.
        $offre = Offre::create([
            ...$request->only(['titre','entreprise','localisation','secteur','description','exigences','date_limite']),
            'type_contrat_id' => TypeContrat::where('code', $request->type)->value('id'),
            'recruteur_id'    => Auth::id(),
            'statut'          => 'en_attente',
        ]);

        return redirect()->route('offre.publiee-succes', $offre);
    }

    public function offrePublieeSucces(Offre $offre)
    {
        return view('public.offre.offre-publiee-succes', compact('offre'));
    }
}
