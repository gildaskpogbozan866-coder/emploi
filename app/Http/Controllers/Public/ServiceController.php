<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Concerns\VerifiesRecaptcha;
use App\Http\Controllers\Controller;
use App\Models\Commande;
use App\Models\Paiement;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
{
    use VerifiesRecaptcha;

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
            'details_demande' => 'nullable|string|max:5000',
            'fichier_joint'   => 'nullable|file|mimes:pdf,doc,docx,txt,jpg,jpeg,png|max:10240',
        ];

        // Un client connecté a déjà ces informations sur son compte (champs en
        // lecture seule côté formulaire) — on ne les redemande, ni ne les fait
        // confiance depuis la requête, que pour un invité.
        if (!Auth::check()) {
            $rules['email_contact'] = 'required|email:rfc,dns|max:191';
            $rules['prenom']        = 'required|string|max:100';
            $rules['nom']           = 'required|string|max:100';
            $rules['telephone']     = 'required|string|max:30';
        }

        $request->validate($rules);

        // Le reCAPTCHA ne protège que le passage invité — un utilisateur
        // connecté est déjà identifié, pas besoin de lui ajouter une friction.
        if (!Auth::check() && !$this->recaptchaValide($request)) {
            return back()->withErrors(['recaptcha' => 'Vérification anti-robot échouée. Veuillez cocher la case et réessayer.'])->withInput();
        }

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
            'email_contact'   => Auth::check() ? Auth::user()->email : $request->email_contact,
            'prenom'          => Auth::check() ? Auth::user()->prenom : $request->prenom,
            'nom'             => Auth::check() ? Auth::user()->nom : $request->nom,
            'telephone'       => Auth::check() ? Auth::user()->tel : $request->telephone,
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
