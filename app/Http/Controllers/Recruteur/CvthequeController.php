<?php

namespace App\Http\Controllers\Recruteur;

use App\Http\Controllers\Controller;
use App\Models\CV;
use App\Models\CvDownload;
use App\Models\Disponibilite;
use App\Models\Document;
use App\Models\Langue;
use App\Models\Metier;
use App\Models\NiveauEtude;
use App\Models\NiveauExperience;
use App\Models\SecteurActivite;
use App\Models\TypeContrat;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CvthequeController extends Controller
{
    public function index(Request $request)
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
        if ($request->filled('pays'))              $cvQuery->where('pays', $request->pays);
        if ($request->filled('disponibilite'))     $cvQuery->where('disponibilite', $request->disponibilite);
        if ($request->filled('secteur'))           $cvQuery->where('secteur', 'like', '%'.$request->secteur.'%');
        if ($request->filled('langue'))            $cvQuery->where('langues', 'like', '%'.$request->langue.'%');
        if ($request->filled('metier'))            $cvQuery->where(function ($sq) use ($request) {
            $sq->where('metier', 'like', '%'.$request->metier.'%')
               ->orWhere('titre_poste', 'like', '%'.$request->metier.'%');
        });
        if ($request->filled('niveau_etude'))      $cvQuery->where('niveau_etude', $request->niveau_etude);
        if ($request->filled('type_contrat'))      $cvQuery->where('type_contrat', $request->type_contrat);
        if ($request->filled('niveau_experience')) $cvQuery->where('niveau_experience', $request->niveau_experience);

        $cvResults = $cvQuery->get()->map(function ($cv) {
            $cv->_is_document = false;
            return $cv;
        });

        // ── Documents (diplômes, attestations, certificats…) ──
        // Ignorés si un filtre CV-spécifique est actif (disponibilite, secteur, niveau_etude, type_contrat, niveau_experience)
        $skipDocs = $request->hasAny(['disponibilite', 'secteur', 'niveau_etude', 'type_contrat', 'niveau_experience']);

        $docResults = collect();
        if (!$skipDocs) {
            $docQuery = Document::with(['user', 'type'])->latest();
            if ($request->filled('q')) {
                $q = $request->q;
                $docQuery->where(function ($sq) use ($q) {
                    $sq->where('nom', 'like', "%$q%")
                       ->orWhere('competences', 'like', "%$q%");
                });
            }
            if ($request->filled('pays'))   $docQuery->where('pays', $request->pays);
            if ($request->filled('langue')) $docQuery->where('langues', 'like', '%'.$request->langue.'%');
            if ($request->filled('metier')) $docQuery->where('nom', 'like', '%'.$request->metier.'%');

            $docResults = $docQuery->get()->map(function ($doc) {
                return (object) [
                    '_is_document'  => true,
                    'id'            => $doc->id,
                    'titre_poste'   => $doc->nom,
                    'photo'         => null,
                    'pays'          => $doc->pays,
                    'ville'         => $doc->ville,
                    'langues'       => $doc->langues,
                    'competences'   => $doc->competences,
                    'experience'    => $doc->experience,
                    'plan'          => null,
                    'disponibilite' => null,
                    'secteur'       => null,
                    'vues'          => 0,
                    'candidat'      => $doc->user,
                    'type_label'    => $doc->type?->nom ?? 'Document',
                    'fichier'       => $doc->fichier,
                    'created_at'    => $doc->created_at,
                ];
            });
        }

        // ── Fusion & pagination ──
        $merged = $cvResults->concat($docResults)->sortByDesc('created_at')->values();
        $page   = (int) $request->get('page', 1);
        $perPage = 16;
        $cvs = new LengthAwarePaginator(
            $merged->forPage($page, $perPage)->values(),
            $merged->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $favorisCvIds = Auth::user()->cvsFavoris()->pluck('cvs.id')->toArray();
        $credits      = Auth::user()->cv_credits;

        $paysList           = DB::table('pays')->where('actif', true)->orderBy('ordre')->pluck('nom');
        $disponibilitesList = Disponibilite::actifs()->get();
        $secteursList       = SecteurActivite::orderBy('libelle')->get();
        $languesList        = Langue::orderBy('nom')->get();
        $metiersList        = Metier::orderBy('nom')->get();
        $niveauxEtudeList   = NiveauEtude::orderBy('ordre')->get();
        $typeContratsList   = TypeContrat::orderBy('libelle')->get();
        $niveauxExpList     = NiveauExperience::orderBy('ordre')->get();

        return view('recruteur.cvtheque', compact(
            'cvs', 'favorisCvIds', 'credits',
            'paysList', 'disponibilitesList', 'secteursList', 'languesList',
            'metiersList', 'niveauxEtudeList', 'typeContratsList', 'niveauxExpList'
        ));
    }

    public function show(CV $cv)
    {
        if (!$cv->visible) {
            abort(404);
        }

        $user = Auth::user();

        if ($user->cv_credits <= 0) {
            return redirect()->route('cv.public.tarif')
                ->with('info', 'Achetez des crédits CVthèque pour débloquer les informations personnelles et télécharger ce CV.');
        }

        $cv->increment('vues');
        $cv->load('candidat');
        $credits = $user->cv_credits;

        return view('recruteur.cvtheque-show', compact('cv', 'credits'));
    }

    public function telecharger(CV $cv)
    {
        if (!$cv->visible) {
            abort(404);
        }

        $user = Auth::user();

        // Vérifier que le fichier existe avant de toucher aux crédits
        if (!$cv->fichier_path || !Storage::disk('public')->exists($cv->fichier_path)) {
            return back()->with('error', 'Ce CV n\'a pas de fichier joint.');
        }

        // Décrémenter atomiquement : UPDATE users SET cv_credits = cv_credits - 1
        // WHERE id = ? AND cv_credits > 0 — retourne 0 si déjà à 0
        $affected = DB::table('users')
            ->where('id', $user->id)
            ->where('cv_credits', '>', 0)
            ->decrement('cv_credits');

        if ($affected === 0) {
            return redirect()->route('cv.public.tarif')
                ->with('info', 'Vous n\'avez plus de crédits. Achetez un pack pour télécharger des CVs.');
        }

        // Journaliser le téléchargement
        CvDownload::create([
            'recruteur_id' => $user->id,
            'cv_id'        => $cv->id,
        ]);

        // Construire un nom de fichier propre avec l'extension d'origine
        $ext      = strtolower(pathinfo($cv->fichier_path, PATHINFO_EXTENSION));
        $slug     = Str::slug($cv->titre_poste ?: 'cv');
        $filename = $slug . '-cv.' . $ext;

        $fullPath = Storage::disk('public')->path($cv->fichier_path);

        return response()->download($fullPath, $filename, [
            'Content-Type'        => Storage::disk('public')->mimeType($cv->fichier_path),
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function telechargerPdf(CV $cv)
    {
        if (!$cv->visible) {
            abort(404);
        }

        $user = Auth::user();

        $affected = DB::table('users')
            ->where('id', $user->id)
            ->where('cv_credits', '>', 0)
            ->decrement('cv_credits');

        if ($affected === 0) {
            return redirect()->route('cv.public.tarif')
                ->with('info', 'Vous n\'avez plus de crédits. Achetez un pack pour télécharger des CVs.');
        }

        CvDownload::create([
            'recruteur_id' => $user->id,
            'cv_id'        => $cv->id,
        ]);

        $cv->load('candidat');

        $pdf = Pdf::loadView('pdf.cv', compact('cv'))
            ->setPaper('a4', 'portrait');

        $slug     = Str::slug(($cv->candidat?->prenom ?? '') . '-' . ($cv->candidat?->nom ?? '') ?: 'candidat');
        $filename = 'fiche-' . $slug . '.pdf';

        return $pdf->download($filename);
    }

    public function toggleFavoris(CV $cv)
    {
        $user = Auth::user();

        if ($user->cvsFavoris()->where('cv_id', $cv->id)->exists()) {
            $user->cvsFavoris()->detach($cv->id);
            $message = 'CV retiré de vos favoris.';
        } else {
            $user->cvsFavoris()->attach($cv->id);
            $message = 'CV ajouté à vos favoris.';
        }

        return back()->with('success', $message);
    }

    public function showDocument(Document $document)
    {
        $user = Auth::user();

        if ($user->cv_credits <= 0) {
            return redirect()->route('cv.public.tarif')
                ->with('info', 'Achetez des crédits CVthèque pour débloquer les informations personnelles et télécharger ce document.');
        }

        $document->load(['user', 'type']);
        $credits = $user->cv_credits;

        return view('recruteur.cvtheque-document-show', compact('document', 'credits'));
    }

    public function telechargerDocument(Document $document)
    {
        $user = Auth::user();

        if (!$document->fichier || !Storage::disk('public')->exists($document->fichier)) {
            return back()->with('error', 'Ce document n\'a pas de fichier joint.');
        }

        $affected = DB::table('users')
            ->where('id', $user->id)
            ->where('cv_credits', '>', 0)
            ->decrement('cv_credits');

        if ($affected === 0) {
            return redirect()->route('cv.public.tarif')
                ->with('info', 'Vous n\'avez plus de crédits. Achetez un pack pour télécharger des documents.');
        }

        $ext      = strtolower(pathinfo($document->fichier, PATHINFO_EXTENSION));
        $slug     = Str::slug($document->nom ?: 'document');
        $filename = $slug . '.' . $ext;
        $fullPath = Storage::disk('public')->path($document->fichier);

        return response()->download($fullPath, $filename, [
            'Content-Type'        => Storage::disk('public')->mimeType($document->fichier),
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function favoris(Request $request)
    {
        $favorisCvIds = Auth::user()->cvsFavoris()->pluck('cvs.id')->toArray();
        $query = CV::visible()->with('candidat')->whereIn('id', $favorisCvIds)->latest();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sq) use ($q) {
                $sq->where('titre_poste', 'like', "%$q%")
                   ->orWhere('competences', 'like', "%$q%");
            });
        }

        $cvs     = $query->paginate(16)->withQueryString();
        $credits = Auth::user()->cv_credits;

        return view('recruteur.cvtheque-favoris', compact('cvs', 'favorisCvIds', 'credits'));
    }
}
