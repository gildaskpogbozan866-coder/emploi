<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::with('auteur')->latest();

        if ($request->filled('q')) {
            $query->where('titre', 'like', '%' . $request->q . '%');
        }
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $articles = $query->paginate(20)->withQueryString();
        return view('admin.blog.list', compact('articles'));
    }

    public function create()
    {
        return view('admin.blog.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre'         => 'required|string|max:300',
            'extrait'       => 'nullable|string|max:500',
            'contenu'       => 'required|string',
            'categorie'     => 'nullable|string|max:100',
            'temps_lecture' => 'nullable|integer|min:1|max:60',
            'statut'        => 'required|in:brouillon,publie',
            'image'         => 'nullable|file|image|max:3072',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('blog', 'public');
        }

        Article::create([
            'auteur_id'     => Auth::id(),
            'titre'         => $request->titre,
            'slug'          => Str::slug($request->titre),
            'extrait'       => $request->extrait,
            'contenu'       => $request->contenu,
            'categorie'     => $request->categorie,
            'temps_lecture' => $request->temps_lecture ?? 5,
            'statut'        => $request->statut,
            'image'         => $imagePath,
            'publie_le'     => $request->statut === 'publie' ? now() : null,
        ]);

        return redirect()->route('admin.blog.list')->with('success', 'Article créé avec succès.');
    }

    public function edit(Article $article)
    {
        return view('admin.blog.edit', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        $request->validate([
            'titre'         => 'required|string|max:300',
            'extrait'       => 'nullable|string|max:500',
            'contenu'       => 'required|string',
            'categorie'     => 'nullable|string|max:100',
            'temps_lecture' => 'nullable|integer|min:1|max:60',
            'statut'        => 'required|in:brouillon,publie,archive',
            'image'         => 'nullable|file|image|max:3072',
        ]);

        $data = $request->only(['titre','extrait','contenu','categorie','temps_lecture','statut']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('blog', 'public');
        }

        if ($request->statut === 'publie' && !$article->publie_le) {
            $data['publie_le'] = now();
        }

        $article->update($data);

        return redirect()->route('admin.blog.list')->with('success', 'Article mis à jour.');
    }

    public function destroy(Article $article)
    {
        $article->delete();
        return redirect()->route('admin.blog.list')->with('success', 'Article supprimé.');
    }
}
