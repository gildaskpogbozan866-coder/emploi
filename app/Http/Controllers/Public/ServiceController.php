<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Mail\CommandeConfirmationMail;
use App\Mail\NouvelleCommandeMail;
use App\Models\Commande;
use App\Models\Paiement;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

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
        $rules = [
            'prenom_client'           => 'required|string|max:100',
            'nom_client'              => 'required|string|max:100',
            'tel_client'              => 'nullable|string|max:30',
            'ville_client'            => 'nullable|string|max:100',
            'poste_vise'              => 'required|string|max:200',
            'niveau_etudes'           => 'nullable|string|max:50',
            'experiences'             => 'nullable|string|max:3000',
            'competences'             => 'nullable|string|max:1000',
            'details_supplementaires' => 'nullable|string|max:1000',
            'fichier_joint'           => 'nullable|file|mimes:pdf,doc,docx,txt,jpg,jpeg,png|max:10240',
        ];

        if (!Auth::check()) {
            $rules['email_contact'] = 'required|email:rfc,dns|max:191';
        }

        $request->validate($rules, [
            'prenom_client.required' => 'Le prénom est obligatoire.',
            'nom_client.required'    => 'Le nom est obligatoire.',
            'poste_vise.required'    => 'Le poste ou métier visé est obligatoire.',
        ]);

        // Assemble les infos dans un texte structuré pour le champ details_demande
        $parts = [];
        $parts[] = '=== INFORMATIONS DU CLIENT ===';
        $parts[] = 'Nom complet : ' . trim($request->prenom_client . ' ' . $request->nom_client);
        if ($request->tel_client)   $parts[] = 'Téléphone : ' . $request->tel_client;
        if ($request->ville_client) $parts[] = 'Ville : ' . $request->ville_client;

        $parts[] = '';
        $parts[] = '=== PARCOURS ===';
        $parts[] = 'Poste visé : ' . $request->poste_vise;
        if ($request->niveau_etudes) $parts[] = 'Niveau d\'études : ' . $request->niveau_etudes;

        if ($request->experiences) {
            $parts[] = '';
            $parts[] = 'Expériences :';
            $parts[] = $request->experiences;
        }

        if ($request->competences) {
            $parts[] = '';
            $parts[] = 'Compétences :';
            $parts[] = $request->competences;
        }

        if ($request->details_supplementaires) {
            $parts[] = '';
            $parts[] = 'Informations supplémentaires :';
            $parts[] = $request->details_supplementaires;
        }

        $fichierPath = null;
        if ($request->hasFile('fichier_joint')) {
            $fichierPath = $request->file('fichier_joint')->store('commandes', 'public');
        }

        $commande = Commande::create([
            'user_id'         => Auth::id(),
            'service_id'      => $service->id,
            'details_demande' => implode("\n", $parts),
            'fichier_joint'   => $fichierPath,
            'montant'         => $service->prix,
            'statut'          => 'en_attente',
            'paiement_statut' => 'non_paye',
            'email_contact'   => Auth::check() ? Auth::user()->email : $request->email_contact,
        ]);

        $service->increment('nb_commandes');

        $clientInfo = [
            'nom_complet'             => trim($request->prenom_client . ' ' . $request->nom_client),
            'tel'                     => $request->tel_client,
            'ville'                   => $request->ville_client,
            'poste_vise'              => $request->poste_vise,
            'niveau_etudes'           => $request->niveau_etudes,
            'experiences'             => $request->experiences,
            'competences'             => $request->competences,
            'details_supplementaires' => $request->details_supplementaires,
        ];

        try {
            Mail::to(config('mail.from.address'))
                ->send(new NouvelleCommandeMail($commande, $clientInfo));

            Mail::to($commande->email_contact)
                ->send(new CommandeConfirmationMail($commande, $clientInfo));
        } catch (\Exception $e) {
            logger()->error('Commande mail failed: ' . $e->getMessage(), ['commande' => $commande->reference]);
        }

        $paiement = Paiement::create([
            'user_id'      => Auth::id(),
            'montant'      => $service->prix,
            'devise'       => 'XOF',
            'type'         => 'service',
            'statut'       => 'en_attente',
            'payable_id'   => $commande->id,
            'payable_type' => Commande::class,
        ]);

        $params = ['paiement' => $paiement->id];
        if (!Auth::check()) {
            $params['token'] = $paiement->reference;
        }

        return redirect()->route('payment.choose', $params);
    }

    public function succes()
    {
        return view('public.service.succes');
    }
}
