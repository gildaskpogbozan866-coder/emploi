<?php

namespace App\Http\Controllers\Recruteur;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CvCreditsController extends Controller
{
    const PACKS = [
        5  => ['credits' => 5,  'prix' => 5000,  'label' => '5 crédits'],
        10 => ['credits' => 10, 'prix' => 9000,  'label' => '10 crédits'],
        25 => ['credits' => 25, 'prix' => 20000, 'label' => '25 crédits'],
        50 => ['credits' => 50, 'prix' => 35000, 'label' => '50 crédits'],
    ];

    public function index()
    {
        $user     = Auth::user();
        $credits  = $user->cv_credits;
        $historique = Paiement::where('user_id', $user->id)
            ->where('type', 'cv_credits')
            ->latest()
            ->get();

        return view('recruteur.cv-credits', compact('credits', 'historique'));
    }

    public function confirm(Request $request)
    {
        $credits = (int) $request->query('credits', 5);
        if (!array_key_exists($credits, self::PACKS)) $credits = 5;
        $pack = self::PACKS[$credits];
        return view('recruteur.cv-credits-confirm', compact('pack'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'credits' => 'required|integer|in:5,10,25,50',
        ]);

        $credits = (int) $request->credits;
        $pack    = self::PACKS[$credits];

        $paiement = Paiement::create([
            'user_id'    => Auth::id(),
            'montant'    => $pack['prix'],
            'devise'     => 'XOF',
            'type'       => 'cv_credits',
            'credits_cv' => $pack['credits'],
            'methode'    => 'en_attente',
            'statut'     => 'en_attente',
            'gateway'    => 'manuel',
        ]);

        return redirect()->route('payment.choose', ['paiement' => $paiement->id]);
    }
}
