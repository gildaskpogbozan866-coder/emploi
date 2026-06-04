<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Commande;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::where('actif', true)->orderBy('prix')->get();
        return view('public.service.list', compact('services'));
    }

    public function detail(Service $service)
    {
        return view('public.service.detail', compact('service'));
    }

    public function commander(Service $service)
    {
        return view('public.service.commande', compact('service'));
    }

    public function storerCommande(Request $request, Service $service)
    {
        $request->validate([
            'details_demande' => 'required|string|min:20',
            'fichier_joint'   => 'nullable|file|mimes:pdf,doc,docx,txt|max:10240',
        ]);

        $fichierPath = null;
        if ($request->hasFile('fichier_joint')) {
            $fichierPath = $request->file('fichier_joint')->store('commandes', 'public');
        }

        Commande::create([
            'user_id'         => Auth::id(),
            'service_id'      => $service->id,
            'details_demande' => $request->details_demande,
            'fichier_joint'   => $fichierPath,
            'montant'         => $service->prix,
            'statut'          => 'en_attente',
            'paiement_statut' => 'non_paye',
        ]);

        $service->increment('nb_commandes');

        return redirect()->route('service.succes')->with('service', $service->nom);
    }

    public function succes()
    {
        return view('public.service.succes');
    }
}
