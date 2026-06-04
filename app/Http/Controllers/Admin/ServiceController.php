<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::withCount('commandes')->latest()->get();
        return view('admin.services.list', compact('services'));
    }

    public function create()
    {
        return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom'         => 'required|string|max:200',
            'description' => 'required|string',
            'prix'        => 'required|integer|min:0',
            'delai'       => 'nullable|string|max:50',
            'type'        => 'nullable|string|max:50',
        ]);

        Service::create([
            'nom'         => $request->nom,
            'slug'        => Str::slug($request->nom),
            'description' => $request->description,
            'details'     => $request->details,
            'prix'        => $request->prix,
            'delai'       => $request->delai,
            'type'        => $request->type,
            'actif'       => $request->boolean('actif', true),
        ]);

        return redirect()->route('admin.services.list')->with('success', 'Service créé.');
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'nom'         => 'required|string|max:200',
            'description' => 'required|string',
            'prix'        => 'required|integer|min:0',
        ]);

        $service->update($request->only(['nom','description','details','prix','delai','type']) + ['actif' => $request->boolean('actif')]);

        return redirect()->route('admin.services.list')->with('success', 'Service mis à jour.');
    }

    public function commandes(Request $request)
    {
        $commandes = Commande::with(['user', 'service'])->latest()->paginate(20);
        return view('admin.services.commandes', compact('commandes'));
    }

    public function showCommande(Commande $commande)
    {
        $commande->load(['user', 'service']);
        return view('admin.services.commande-detail', compact('commande'));
    }

    public function updateStatut(Request $request, Commande $commande)
    {
        $request->validate(['statut' => 'required|in:en_attente,en_cours,livree,annulee']);
        $commande->update(['statut' => $request->statut]);
        return back()->with('success', 'Statut de la commande mis à jour.');
    }
}
