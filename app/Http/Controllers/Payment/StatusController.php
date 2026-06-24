<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Paiement;

class StatusController extends Controller
{
    private function verifyAccess(Paiement $paiement): void
    {
        if (auth()->check()) {
            abort_if($paiement->user_id !== auth()->id(), 403);
            return;
        }

        $token = request('token') ?? session('pay_access_' . $paiement->id);
        abort_if($token !== $paiement->reference, 403);

        session(['pay_access_' . $paiement->id => $paiement->reference]);
    }

    public function success(Paiement $paiement)
    {
        $this->verifyAccess($paiement);
        $paiement->load('abonnement.plan');
        return view('payment.success', compact('paiement'));
    }

    public function failed(Paiement $paiement)
    {
        $this->verifyAccess($paiement);
        return view('payment.failed', compact('paiement'));
    }

    public function pending(Paiement $paiement)
    {
        $this->verifyAccess($paiement);
        return view('payment.pending', compact('paiement'));
    }
}
