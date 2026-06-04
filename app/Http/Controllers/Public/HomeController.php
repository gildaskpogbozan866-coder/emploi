<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Offre;
use App\Models\CV;
use App\Models\TalentProfil;
use App\Models\Article;

class HomeController extends Controller
{
    public function index()
    {
        $offres  = Offre::active()->recente()->with('recruteur')->limit(8)->get();
        $cvs     = CV::visible()->with('candidat')->latest()->limit(6)->get();
        $talents = TalentProfil::visible()->with('user')->latest()->limit(6)->get();
        $articles = Article::publie()->latest('publie_le')->limit(3)->get();

        return view('public.index', compact('offres', 'cvs', 'talents', 'articles'));
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
        return view('public.faq');
    }
}
