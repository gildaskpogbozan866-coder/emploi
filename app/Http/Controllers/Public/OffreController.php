<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Offre;
use App\Models\Candidature;
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

        $aPostule = Candidature::where('offre_id', $offre->id)
            ->where('candidat_id', Auth::id())
            ->exists();

        return view('public.offre.postuler', compact('offre', 'aPostule'));
    }

    // Alias utilisé par la route offre.postuler.store (POST)
    public function storerCandidature(Request $request, Offre $offre)
    {
        if (!Auth::check()) {
            return redirect()->route('auth.connexion');
        }

        $request->validate([
            'message_motivation' => 'nullable|string|max:3000',
            'cv_file'            => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $cvPath = null;
        if ($request->hasFile('cv_file')) {
            $cvPath = $request->file('cv_file')->store('candidatures', 'public');
        }

        Candidature::firstOrCreate(
            ['offre_id' => $offre->id, 'candidat_id' => Auth::id()],
            ['message_motivation' => $request->message_motivation, 'cv_path' => $cvPath]
        );

        return redirect()->route('offre.candidature-succes', $offre);
    }

    public function candidatureSucces(Offre $offre)
    {
        return view('public.offre.candidature-succes', compact('offre'));
    }

    public function publier()
    {
        if (!Auth::check()) {
            return redirect()->route('auth.connexion');
        }
        return view('public.offre.publier');
    }

    public function storerOffre(Request $request)
    {
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
