<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CandidatProfilComplet
{
    public function handle(Request $request, Closure $next)
    {
        $user   = $request->user();
        $profil = $user->candidatProfil;

        $profilComplet = $user->prenom
            && $user->nom
            && $profil
            && $profil->titre_professionnel
            && $profil->ville
            && $profil->disponibilite;

        if (! $profilComplet) {
            return redirect()->route('candidat.profil')
                ->with('warning', 'Complétez votre profil avant de continuer.');
        }

        return $next($request);
    }
}
