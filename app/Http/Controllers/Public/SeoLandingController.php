<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Offre;
use App\Models\Article;

class SeoLandingController extends Controller
{
    private function offresRecentes(array $filters = [], int $limit = 8)
    {
        $query = Offre::active()->with('recruteur')->recente();
        foreach ($filters as $col => $val) {
            $query->where($col, 'like', "%$val%");
        }
        return $query->limit($limit)->get();
    }

    public function emploiCotonou()
    {
        $offres   = $this->offresRecentes(['localisation' => 'Cotonou']);
        $articles = Article::publie()->latest('publie_le')->limit(3)->get();
        return view('public.seo.emploi-cotonou', compact('offres', 'articles'));
    }

    public function recrutementBenin()
    {
        $offres   = $this->offresRecentes();
        $articles = Article::publie()->latest('publie_le')->limit(3)->get();
        return view('public.seo.recrutement-benin', compact('offres', 'articles'));
    }

    public function stageBenin()
    {
        $offres   = $this->offresRecentes(['type' => 'Stage']);
        $articles = Article::publie()->latest('publie_le')->limit(3)->get();
        return view('public.seo.stage-benin', compact('offres', 'articles'));
    }
}
