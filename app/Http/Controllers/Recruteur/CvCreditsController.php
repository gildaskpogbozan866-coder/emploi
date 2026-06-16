<?php

namespace App\Http\Controllers\Recruteur;

use App\Http\Controllers\Controller;
use App\Models\CreditCvPack;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CvCreditsController extends Controller
{
    public function index()
    {
        $user      = Auth::user();
        $credits   = $user->cv_credits;
        $packs     = CreditCvPack::actif()->orderBy('ordre')->orderBy('credits')->get();
        $historique = Paiement::where('user_id', $user->id)
            ->where('type', 'cv_credits')
            ->latest()
            ->get();

        return view('recruteur.cv-credits', compact('credits', 'packs', 'historique'));
    }

    public function confirm(Request $request)
    {
        $packId = (int) $request->query('pack');
        $pack   = CreditCvPack::actif()->findOrFail($packId);

        return view('recruteur.cv-credits-confirm', compact('pack'));
    }

    public function store(Request $request)
    {
        $request->validate(['pack_id' => 'required|integer|exists:credit_cv_packs,id']);

        $pack = CreditCvPack::actif()->findOrFail($request->pack_id);

        $paiement = Paiement::create([
            'user_id'    => Auth::id(),
            'montant'    => $pack->prix,
            'devise'     => 'XOF',
            'type'       => 'cv_credits',
            'credits_cv' => $pack->credits,
            'methode'    => 'en_attente',
            'statut'     => 'en_attente',
            'gateway'    => 'manuel',
        ]);

        return redirect()->route('payment.choose', ['paiement' => $paiement->id]);
    }
}
