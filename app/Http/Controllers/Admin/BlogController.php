<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
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

    public function genererIA(Request $request)
    {
        $request->validate([
            'titre'     => 'required|string|max:300',
            'categorie' => 'nullable|string|max:100',
            'extrait'   => 'nullable|string|max:500',
        ]);

        $apiKey = env('ANTHROPIC_API_KEY');
        if (empty($apiKey)) {
            return response()->json(['error' => 'Clé API Anthropic non configurée. Ajoutez ANTHROPIC_API_KEY dans le fichier .env'], 400);
        }

        $titre     = $request->titre;
        $categorie = $request->categorie ?: 'Emploi';
        $extrait   = $request->extrait   ?: '';

        $prompt = <<<EOT
Tu es un rédacteur expert en contenu professionnel pour un blog emploi au Bénin (Afrique de l'Ouest).

Rédige un article de blog complet, structuré et engageant en français sur le sujet suivant :
- **Titre** : {$titre}
- **Catégorie** : {$categorie}
- **Résumé/Extrait** : {$extrait}

INSTRUCTIONS IMPORTANTES :
- Rédige en HTML propre, utilisable directement dans un éditeur WYSIWYG
- Structure avec des balises <h2>, <h3>, <p>, <ul>, <li>, <strong>, <em>
- Longueur : 600 à 900 mots
- Ton : professionnel mais accessible, adapté au contexte béninois/africain
- Commence directement par un paragraphe d'introduction (PAS de balise <h1>, le titre est déjà dans le formulaire)
- Inclus 3 à 4 sections avec des sous-titres <h2>
- Ajoute des conseils pratiques concrets sous forme de listes <ul>
- Termine par une conclusion engageante avec un appel à l'action
- N'inclus PAS de CSS inline ni de balises <html>, <head>, <body>, <style>
- Retourne UNIQUEMENT le contenu HTML de l'article, rien d'autre
EOT;

        try {
            $response = Http::timeout(60)->withHeaders([
                'x-api-key'         => $apiKey,
                'anthropic-version' => '2023-06-01',
                'Content-Type'      => 'application/json',
            ])->post('https://api.anthropic.com/v1/messages', [
                'model'      => 'claude-haiku-4-5-20251001',
                'max_tokens' => 2048,
                'messages'   => [
                    ['role' => 'user', 'content' => $prompt],
                ],
            ]);

            if ($response->failed()) {
                return response()->json(['error' => 'Erreur API : ' . $response->status()], 500);
            }

            $data    = $response->json();
            $contenu = $data['content'][0]['text'] ?? '';

            if (empty($contenu)) {
                return response()->json(['error' => 'Réponse vide de l\'IA'], 500);
            }

            return response()->json(['contenu' => $contenu]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur : ' . $e->getMessage()], 500);
        }
    }
}
