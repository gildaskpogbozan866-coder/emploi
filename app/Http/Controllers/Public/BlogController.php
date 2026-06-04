<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::publie()->with('auteur')->latest('publie_le');

        if ($request->filled('categorie')) {
            $query->where('categorie', $request->categorie);
        }

        $articles = $query->paginate(9)->withQueryString();

        return view('public.blog.list', compact('articles'));
    }

    public function detail(Article $article)
    {
        if ($article->statut !== 'publie') {
            abort(404);
        }

        $article->increment('vues');
        $article->load('auteur');

        $suggestions = Article::publie()
            ->where('id', '!=', $article->id)
            ->where('categorie', $article->categorie)
            ->latest('publie_le')
            ->limit(3)
            ->get();

        return view('public.blog.detail', compact('article', 'suggestions'));
    }
}
