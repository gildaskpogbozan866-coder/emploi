<?php

namespace App\Http\Controllers\Recruteur;

use App\Http\Controllers\Controller;
use App\Models\CV;
use App\Models\CvConsultation;
use App\Models\CvDownload;
use App\Models\Document;
use App\Models\Paiement;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CvthequeController extends Controller
{
    public function index(Request $request)
    {
        // Cet espace ne montre plus que les CV déjà achetés (consultés/téléchargés) —
        // parcourir de nouveaux profils se fait désormais sur la CVthèque publique,
        // qui reconnaît un recruteur déjà passé par ici et lui montre les infos
        // complètes sans dupliquer toute la recherche/filtres ici.
        $consultationsDates = CvConsultation::where('recruteur_id', Auth::id())->pluck('created_at', 'cv_id');
        $dejaConsultesIds    = $consultationsDates->keys()->toArray();

        $cvQuery = CV::visible()->whereIn('id', $dejaConsultesIds)->with('candidat')->latest();

        if ($request->filled('q')) {
            $q = $request->q;
            $cvQuery->where(function ($sq) use ($q) {
                $sq->where('competences', 'like', "%$q%")
                   ->orWhere('metier', 'like', "%$q%");
            });
        }

        $cvs = $cvQuery->paginate(16)->withQueryString();

        $favorisCvIds = Auth::user()->cvsFavoris()->pluck('cvs.id')->toArray();
        $credits      = Auth::user()->cv_credits;

        $cvStats = [
            'credits_restants' => $credits,
            'cvs_consultes'    => $consultationsDates->count(),
            'cvs_telecharges'  => CvDownload::where('recruteur_id', Auth::id())->count(),
        ];

        $paiementsCredits = Paiement::where('user_id', Auth::id())
            ->where('type', 'cv_credits')
            ->where('statut', 'confirme')
            ->latest()
            ->limit(5)
            ->get();

        return view('recruteur.cvtheque', compact(
            'cvs', 'favorisCvIds', 'dejaConsultesIds', 'consultationsDates', 'credits', 'cvStats', 'paiementsCredits'
        ));
    }

    public function show(CV $cv)
    {
        if (!$cv->visible || is_null($cv->publie_le)) {
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

        CvConsultation::firstOrCreate(['recruteur_id' => $user->id, 'cv_id' => $cv->id]);

        return view('recruteur.cvtheque-show', compact('cv', 'credits'));
    }

    public function telecharger(CV $cv)
    {
        if (!$cv->visible || is_null($cv->publie_le)) {
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
        $slug     = Str::slug($cv->metier ?: 'cv');
        $filename = $slug . '-cv.' . $ext;

        $fullPath = Storage::disk('public')->path($cv->fichier_path);

        return response()->download($fullPath, $filename, [
            'Content-Type'        => Storage::disk('public')->mimeType($cv->fichier_path),
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function telechargerPdf(CV $cv)
    {
        if (!$cv->visible || is_null($cv->publie_le)) {
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
                $sq->where('competences', 'like', "%$q%")
                   ->orWhere('metier', 'like', "%$q%");
            });
        }

        $cvs     = $query->paginate(16)->withQueryString();
        $credits = Auth::user()->cv_credits;

        return view('recruteur.cvtheque-favoris', compact('cvs', 'favorisCvIds', 'credits'));
    }
}
