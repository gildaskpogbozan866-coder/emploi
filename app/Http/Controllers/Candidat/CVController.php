<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use App\Models\CV;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CVController extends Controller
{
    // ── CVthèque publique ─────────────────────────────────
    public function theque(Request $request)
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

        $cvs = $query->paginate(12)->withQueryString();
        return view('public.cv.theque', compact('cvs'));
    }

    public function tarif()
    {
        return view('public.cv.tarif');
    }

    public function depot()
    {
        if (!Auth::check()) {
            return redirect()->route('auth.connexion')->with('redirect_after', route('cv.public.depot'));
        }
        return view('public.cv.depot');
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('auth.connexion');
        }

        $request->validate([
            'titre_poste' => 'required|string|max:200',
            'pays'        => 'required|string|max:100',
            'ville'       => 'nullable|string|max:100',
            'competences' => 'nullable|string',
            'experience'  => 'nullable|string',
            'formation'   => 'nullable|string',
            'langues'     => 'nullable|string',
            'fichier_path'=> 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        $fichierPath = null;
        if ($request->hasFile('fichier_path')) {
            $fichierPath = $request->file('fichier_path')->store('cvs', 'public');
        }

        CV::create([
            'candidat_id' => Auth::id(),
            'titre_poste' => $request->titre_poste,
            'pays'        => $request->pays,
            'ville'       => $request->ville,
            'competences' => $request->competences,
            'experience'  => $request->experience,
            'formation'   => $request->formation,
            'langues'     => $request->langues,
            'fichier_path'=> $fichierPath,
            'plan'        => 'gratuit',
            'visible'     => true,
        ]);

        return redirect()->route('candidat.cvs')->with('success', 'Votre CV a été publié avec succès !');
    }

    // ── Espace candidat ───────────────────────────────────
    public function index()
    {
        $cvs = Auth::user()->cvs()->latest()->get();
        return view('candidat.cvs', compact('cvs'));
    }

    public function edit(CV $cv)
    {
        $this->authorize('update', $cv);
        return view('candidat.cv-edit', compact('cv'));
    }

    public function update(Request $request, CV $cv)
    {
        $this->authorize('update', $cv);

        $request->validate([
            'titre_poste' => 'required|string|max:200',
            'pays'        => 'required|string|max:100',
        ]);

        $cv->update($request->only(['titre_poste','pays','ville','competences','experience','formation','langues']));

        return redirect()->route('candidat.cvs')->with('success', 'CV mis à jour.');
    }

    public function destroy(CV $cv)
    {
        $this->authorize('delete', $cv);
        $cv->delete();
        return redirect()->route('candidat.cvs')->with('success', 'CV supprimé.');
    }
}
