<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Offre;
use App\Models\CV;
use App\Models\Article;
use App\Models\Faq;
use App\Models\Plan;

class HomeController extends Controller
{
    public function index()
    {
        $offres   = Offre::active()->recente()->with('recruteur')->limit(8)->get();
        $cvs      = CV::visible()->with('candidat')->latest()->limit(6)->get();
        $articles = Article::publie()->latest('publie_le')->limit(3)->get();

        $plansCandidats  = Plan::where('is_active', true)
            ->whereIn('target_type', ['candidat', 'both'])
            ->with('features')->orderBy('price')->get();
        $plansRecruteurs = Plan::where('is_active', true)
            ->whereIn('target_type', ['recruteur', 'both'])
            ->with('features')->orderBy('price')->get();
        $plansAnnonceurs = Plan::where('is_active', true)
            ->where('target_type', 'annonceur')
            ->with('features')->orderBy('price')->get();

        return view('public.index', compact('offres', 'cvs', 'articles', 'plansCandidats', 'plansRecruteurs', 'plansAnnonceurs'));
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
