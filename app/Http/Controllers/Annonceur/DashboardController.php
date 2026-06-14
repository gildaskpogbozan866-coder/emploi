<?php

namespace App\Http\Controllers\Annonceur;

use App\Http\Controllers\Controller;
use App\Models\Publicite;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total'      => Publicite::where('user_id', Auth::id())->count(),
            'en_attente' => Publicite::where('user_id', Auth::id())->where('statut', 'en_attente')->count(),
            'approuve'   => Publicite::where('user_id', Auth::id())->where('statut', 'approuve')->count(),
            'rejete'     => Publicite::where('user_id', Auth::id())->where('statut', 'rejete')->count(),
        ];

        $dernieres = Publicite::where('user_id', Auth::id())->latest()->take(5)->get();

        return view('annonceur.dashboard', compact('stats', 'dernieres'));
    }
}
