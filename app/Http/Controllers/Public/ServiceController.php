<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Paiement;
use App\Models\Service;
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
        if (!Auth::check()) {
            return redirect()->route('auth.connexion')
                ->with('error', 'Connectez-vous pour passer une commande.');
        }

        $request->validate([
            'details_demande' => 'required|string|min:20',
            'fichier_joint'   => 'nullable|file|mimes:pdf,doc,docx,txt|max:10240',
        ]);

        $fichierPath = null;
        if ($request->hasFile('fichier_joint')) {
            $fichierPath = $request->file('fichier_joint')->store('commandes', 'public');
        }

        $commande = Commande::create([
            'user_id'         => Auth::id(),
            'service_id'      => $service->id,
            'details_demande' => $request->details_demande,
            'fichier_joint'   => $fichierPath,
            'montant'         => $service->prix,
            'statut'          => 'en_attente',
            'paiement_statut' => 'non_paye',
        ]);

        $service->increment('nb_commandes');

        $paiement = Paiement::create([
            'user_id'      => Auth::id(),
            'montant'      => $service->prix,
            'devise'       => 'XOF',
            'type'         => 'service',
            'statut'       => 'en_attente',
            'payable_id'   => $commande->id,
            'payable_type' => Commande::class,
        ]);

        return redirect()->route('payment.choose', $paiement);
    }

    public function succes()
    {
        return view('public.service.succes');
    }
}
