<?php

namespace App\Http\Controllers\Talent;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user   = Auth::user();
        $profil = $user->talentProfil;

        $stats = [
            'profil_cree'  => $profil ? 1 : 0,
            'vues_profil'  => $profil?->vues ?? 0,
            'plan'         => $profil?->plan ?? 'gratuit',
        ];

        return view('talent.dashboard', compact('user', 'profil', 'stats'));
    }
}
