<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Offre;
use App\Models\CV;
use App\Models\Document;
use App\Models\Article;
use App\Models\Faq;
use App\Models\Partenaire;
use App\Models\Plan;

class HomeController extends Controller
{
    public function index()
    {
        $offres   = Offre::active()->recente()->with('recruteur')->limit(8)->get();
        $articles = Article::publie()->latest('publie_le')->limit(3)->get();

        $cvsRaw  = CV::visible()->with('candidat')->latest()->limit(6)->get()
            ->map(function ($cv) { $cv->_is_document = false; return $cv; });
        $docsRaw = Document::with(['user', 'type'])->latest()->limit(6)->get()
            ->map(function ($doc) {
                return (object) [
                    '_is_document' => true,
                    'id'           => $doc->id,
                    'titre_poste'  => $doc->nom,
                    'pays'         => $doc->pays,
                    'ville'        => $doc->ville,
                    'experience'   => $doc->experience,
                    'formation'    => $doc->formation,
                    'langues'      => $doc->langues,
                    'competences'  => $doc->competences,
                    'candidat'     => $doc->user,
                    'type_label'   => $doc->type?->nom ?? 'Document',
                    'created_at'   => $doc->created_at,
                ];
            });
        $cvs = $cvsRaw->concat($docsRaw)->sortByDesc('created_at')->take(6)->values();

        $plansCandidats  = Plan::where('is_active', true)
            ->whereIn('target_type', ['candidat', 'both'])
            ->with('features')->orderBy('price')->get();
        $plansRecruteurs = Plan::where('is_active', true)
            ->whereIn('target_type', ['recruteur', 'both'])
            ->with('features')->orderBy('price')->get();
        $plansAnnonceurs = Plan::where('is_active', true)
            ->where('target_type', 'annonceur')
            ->with('features')->orderBy('price')->get();

        $partenaires = Partenaire::actifs()->get();

        return view('public.index', compact('offres', 'cvs', 'articles', 'plansCandidats', 'plansRecruteurs', 'plansAnnonceurs', 'partenaires'));
    }

    public function aPropos()
    {
        return view('public.a-propos');
    }

    public function contact()
    {
        return view('public.contact');
    }

    public function faq()
    {
        $faqs = Faq::actif()->orderBy('categorie')->orderBy('ordre')->get()->groupBy('categorie');
        return view('public.faq', compact('faqs'));
    }
}
