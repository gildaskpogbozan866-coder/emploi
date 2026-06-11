<?php

namespace App\Http\Controllers\Candidat;

use App\Http\Controllers\Controller;
use App\Models\Competence;
use App\Models\CV;
use App\Models\Langue;
use App\Models\TypeDocument;
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

        $user  = Auth::user();
        $total = $user->cvs()->count() + $user->documents()->count();

        if ($total >= 1 && !$user->estPremium()) {
            return redirect()->route('candidat.abonnement')
                ->with('info', 'Vous avez atteint la limite du plan gratuit (1 document). Passez au Premium pour en ajouter davantage.');
        }

        $typesDocuments = TypeDocument::actif()->get();
        $competences    = Competence::orderBy('nom')->pluck('nom');
        $langues        = Langue::orderBy('nom')->pluck('nom');
        return view('public.cv.depot', compact('typesDocuments', 'competences', 'langues'));
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('auth.connexion');
        }

        $user  = Auth::user();
        $total = $user->cvs()->count() + $user->documents()->count();

        if ($total >= 1 && !$user->estPremium()) {
            return redirect()->route('candidat.abonnement')
                ->with('info', 'Vous avez atteint la limite du plan gratuit (1 document). Passez au Premium pour en ajouter davantage.');
        }

        $request->validate([
            'type_document_id' => 'required|exists:type_documents,id',
            'nom'              => 'required|string|max:200',
            'pays'             => 'nullable|string|max:100',
            'ville'            => 'nullable|string|max:100',
            'competences'      => 'nullable|string',
            'experience'       => 'nullable|string',
            'formation'        => 'nullable|string',
            'langues'          => 'nullable|string',
            'fichier_path'     => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,webp|max:5120',
        ]);

        $typeCV = TypeDocument::where('nom', 'like', '%Curriculum Vitae%')->first();
        $estCV  = $typeCV && $request->type_document_id == $typeCV->id;

        if ($estCV) {
            $fichierPath = null;
            if ($request->hasFile('fichier_path')) {
                $fichierPath = $request->file('fichier_path')->store('cvs', 'public');
            }

            CV::create([
                'candidat_id' => Auth::id(),
                'titre_poste' => $request->nom,
                'pays'        => $request->pays,
                'ville'       => $request->ville,
                'competences' => $request->competences,
                'experience'  => $request->experience,
                'formation'   => $request->formation,
                'langues'     => $request->langues,
                'fichier_path'=> $fichierPath,
                'plan'        => 'gratuit',
                'visible'     => false,
            ]);

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
        $estPremium     = $user->estPremium();
        $total          = $cvs->count() + $documents->count();

        return view('candidat.cvs', compact('cvs', 'documents', 'typesDocuments', 'estPremium', 'total'));
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
            'titre_poste'  => 'required|string|max:200',
            'pays'         => 'required|string|max:100',
            'ville'        => 'nullable|string|max:100',
            'competences'  => 'nullable|string',
            'experience'   => 'nullable|string',
            'formation'    => 'nullable|string',
            'langues'      => 'nullable|string',
            'fichier_path' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,webp|max:5120',
        ]);

        $data = $request->only(['titre_poste','pays','ville','competences','experience','formation','langues']);

        if ($request->hasFile('fichier_path')) {
            if ($cv->fichier_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($cv->fichier_path);
            }
            $data['fichier_path'] = $request->file('fichier_path')->store('cvs', 'public');
        }

        $cv->update($data);

        return redirect()->route('candidat.cvs')->with('success', 'CV mis à jour.');
    }

    public function destroy(CV $cv)
    {
        $this->authorize('delete', $cv);
        $cv->delete();
        return redirect()->route('candidat.cvs')->with('success', 'CV supprimé.');
    }
}
