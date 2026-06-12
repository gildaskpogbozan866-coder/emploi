<?php

namespace App\Http\Controllers\Recruteur;

use App\Http\Controllers\Controller;
use App\Models\CV;
use App\Models\CvDownload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CvthequeController extends Controller
{
    public function index(Request $request)
    {
        $query = CV::visible()->with('candidat')->latest();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sq) use ($q) {
                $sq->where('titre_poste', 'like', "%$q%")
                   ->orWhere('competences', 'like', "%$q%")
                   ->orWhere('secteur', 'like', "%$q%");
            });
        }

        if ($request->filled('pays')) {
            $query->where('pays', $request->pays);
        }

        if ($request->filled('disponibilite')) {
            $query->where('disponibilite', $request->disponibilite);
        }

        $cvs          = $query->paginate(16)->withQueryString();
        $favorisCvIds = Auth::user()->cvsFavoris()->pluck('cvs.id')->toArray();
        $credits      = Auth::user()->cv_credits;

        return view('recruteur.cvtheque', compact('cvs', 'favorisCvIds', 'credits'));
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
