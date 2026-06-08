<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RecruteurApprouve
{
    public function handle(Request $request, Closure $next)
    {
        $verification = $request->user()->recruteurVerification;

        if (! $verification) {
            return redirect()->route('recruteur.verification');
        }

        if ($verification->estEnAttente()) {
            return redirect()->route('recruteur.verification.en-attente');
        }

        if ($verification->estRejete()) {
            return redirect()->route('recruteur.verification.rejete');
        }

        return $next($request);
    }
}
