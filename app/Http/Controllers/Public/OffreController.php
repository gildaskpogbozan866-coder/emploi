<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\CV;
use App\Models\Candidature;
use App\Models\Notification;
use App\Models\Offre;
use App\Notifications\CandidatureRecueNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OffreController extends Controller
{
    public function index(Request $request)
    {
        $query = Offre::active()->with(['recruteur', 'competences'])->recente();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sq) use ($q) {
                $sq->where('titre', 'like', "%$q%")
                   ->orWhere('entreprise', 'like', "%$q%")
                   ->orWhere('description', 'like', "%$q%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('localisation')) {
            $query->where('localisation', 'like', '%' . $request->localisation . '%');
        }

        if ($request->filled('secteur')) {
            $query->where('secteur', $request->secteur);
        }

        if ($request->filled('competence')) {
            $query->whereHas('competences', fn($q) => $q->where('slug', $request->competence));
        }

        $offres = $query->paginate(12)->withQueryString();

        $competences = \App\Models\Competence::orderBy('nom')->get();

        return view('public.offre.list', compact('offres', 'competences'));
    }

    public function detail(Offre $offre)
    {
        // Une seule vue par session ; le recruteur propriétaire ne compte pas
        $sessionKey = 'vu_offre_' . $offre->id;
        if (!session()->has($sessionKey) && Auth::id() !== $offre->recruteur_id) {
            $offre->increment('vues');
            session()->put($sessionKey, true);
        }

        $offre->load(['recruteur', 'competences']);
        $aPostule      = false;
        $estSauvegarde = false;

        if (Auth::check()) {
            $aPostule      = Candidature::where('offre_id', $offre->id)->where('candidat_id', Auth::id())->exists();
            $estSauvegarde = Auth::user()->offresSauvegardees()->where('offre_id', $offre->id)->exists();
        }

        $competenceIds = $offre->competences->pluck('id');
        $similaires = Offre::active()
            ->with('recruteur')
            ->where('id', '!=', $offre->id)
            ->where(function ($q) use ($offre, $competenceIds) {
                $q->where('secteur', $offre->secteur)
                  ->orWhere('type', $offre->type)
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

        if (!Auth::user()->hasRole('candidat')) {
            $dashboard = match (Auth::user()->role) {
                'recruteur' => route('recruteur.dashboard'),
                'admin'     => route('admin.dashboard'),
                default     => route('home'),
            };
            return redirect($dashboard)->with('error', 'Seuls les candidats peuvent postuler à une offre.');
        }

        $aPostule = Candidature::where('offre_id', $offre->id)
            ->where('candidat_id', Auth::id())
            ->exists();

        $cvs = Auth::user()->cvs()->orderByDesc('created_at')->get();

        return view('public.offre.postuler', compact('offre', 'aPostule', 'cvs'));
    }

    public function storerCandidature(Request $request, Offre $offre)
    {
        if (!Auth::check()) {
            return redirect()->route('auth.connexion');
        }

        if (!Auth::user()->hasRole('candidat')) {
            return redirect()->route('home')->with('error', 'Seuls les candidats peuvent postuler à une offre.');
        }

        if (Candidature::where('offre_id', $offre->id)->where('candidat_id', Auth::id())->exists()) {
            return back()->with('error_duplicate', true);
        }

        $request->validate([
            'message_motivation' => 'nullable|string|max:3000',
            'cv_id'              => 'nullable|integer|exists:cvs,id',
            'cv_file'            => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        // S'assurer que le CV sélectionné appartient au candidat
        $cvId = null;
        if ($request->filled('cv_id')) {
            $cv = CV::where('id', $request->cv_id)->where('candidat_id', Auth::id())->first();
            $cvId = $cv?->id;
        }

        // Upload fichier seulement si aucun CV profil sélectionné
        $cvPath = null;
        if (!$cvId && $request->hasFile('cv_file')) {
            $cvPath = $request->file('cv_file')->store('candidatures', 'public');
        }

        $candidature = Candidature::create([
            'offre_id'           => $offre->id,
            'candidat_id'        => Auth::id(),
            'message_motivation' => $request->message_motivation,
            'cv_id'              => $cvId,
            'cv_path'            => $cvPath,
        ]);

        $candidat = Auth::user();

        // Notification in-app au recruteur
        Notification::create([
            'user_id' => $offre->recruteur_id,
            'type'    => 'candidature',
            'titre'   => 'Nouvelle candidature reçue',
            'contenu' => $candidat->nom_complet . ' a postulé pour « ' . $offre->titre . ' ».',
            'lien'    => route('recruteur.candidatures.show', $candidature),
        ]);

        // Email de confirmation au candidat
        $candidat->notify(new CandidatureRecueNotification($offre));

        return redirect()->route('offre.candidature-succes', $offre);
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

        if (!Auth::user()->hasRole('recruteur')) {
            return redirect()->route(match(Auth::user()->role) {
                'candidat' => 'candidat.dashboard',
                'admin'    => 'admin.dashboard',
                default    => 'home',
            })->with('error', 'Seuls les recruteurs peuvent publier des offres. Connectez-vous avec un compte recruteur.');
        }

        return view('public.offre.publier');
    }

    public function storerOffre(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('auth.connexion');
        }

        if (!Auth::user()->hasRole('recruteur')) {
            return redirect()->route(match(Auth::user()->role) {
                'candidat' => 'candidat.dashboard',
                'admin'    => 'admin.dashboard',
                default    => 'home',
            })->with('error', 'Seuls les recruteurs peuvent publier des offres. Connectez-vous avec un compte recruteur.');
        }

        $request->validate([
            'titre'       => 'required|string|max:200',
            'entreprise'  => 'required|string|max:200',
            'localisation'=> 'required|string|max:200',
            'type'        => 'required|in:CDI,CDD,Stage,Bourse,Freelance,Temps partiel',
            'description' => 'required|string',
            'date_limite' => 'nullable|date|after_or_equal:today',
        ]);

        $offre = Offre::create([
            ...$request->only(['titre','entreprise','localisation','type','secteur','salaire','description','competences','exigences','date_limite']),
            'recruteur_id' => Auth::id(),
            'statut'       => 'en_attente',
        ]);

        return redirect()->route('offre.publiee-succes', $offre);
    }

    public function offrePublieeSucces(Offre $offre)
    {
        return view('public.offre.offre-publiee-succes', compact('offre'));
    }
}
