<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Paiement;

class StatusController extends Controller
{
    public function success(Paiement $paiement)
    {
        abort_if($paiement->user_id !== auth()->id(), 403);
        $paiement->load('abonnement.plan');
        return view('payment.success', compact('paiement'));
    }

    public function failed(Paiement $paiement)
    {
        abort_if($paiement->user_id !== auth()->id(), 403);
        return view('payment.failed', compact('paiement'));
    }

    public function pending(Paiement $paiement)
    {
        abort_if($paiement->user_id !== auth()->id(), 403);
        return view('payment.pending', compact('paiement'));
    }
}
