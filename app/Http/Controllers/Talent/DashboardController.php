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
            'vues_profil' => $profil?->vues ?? 0,
            'completion'  => $profil?->profil_completion ?? 0,
            'plan'        => $profil?->plan ?? 'gratuit',
        ];

        return view('talent.dashboard', compact('user', 'profil', 'stats'));
    }
}
