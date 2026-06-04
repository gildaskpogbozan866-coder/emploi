<?php

namespace App\Http\Controllers\Recruteur;

use App\Http\Controllers\Controller;
use App\Models\CV;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CvthequeController extends Controller
{
    public function index(Request $request)
    {
        $query = CV::visible()->with('candidat')->latest();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sq) use ($q) {
                $sq->where('titre_poste', 'like', "%$q%")
                   ->orWhere('competences', 'like', "%$q%");
            });
        }

        if ($request->filled('pays')) {
            $query->where('pays', $request->pays);
        }

        $cvs          = $query->paginate(16)->withQueryString();
        $favorisCvIds = Auth::user()->cvsFavoris()->pluck('cvs.id')->toArray();

        return view('recruteur.cvtheque', compact('cvs', 'favorisCvIds'));
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
}
