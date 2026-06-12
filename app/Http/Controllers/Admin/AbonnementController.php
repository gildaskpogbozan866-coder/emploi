<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Abonnement;
use Illuminate\Http\Request;

class AbonnementController extends Controller
{
    public function index(Request $request)
    {
        $query = Abonnement::with(['user', 'plan'])->latest('starts_at');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->whereHas('user', fn ($sq) => $sq
                ->where('prenom', 'like', "%$q%")
                ->orWhere('nom',   'like', "%$q%")
                ->orWhere('email', 'like', "%$q%")
            );
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('target_type')) {
            $query->whereHas('plan', fn ($q) => $q->where('target_type', $request->target_type));
        }

        $abonnements = $query->paginate(20)->withQueryString();

        $stats = [
            'total_actif'   => Abonnement::actif()->count(),
            'premium_actif' => Abonnement::actif()
                                         ->whereHas('plan', fn ($q) => $q->where('is_free', false))
                                         ->count(),
            'gratuit_actif' => Abonnement::actif()
                                         ->whereHas('plan', fn ($q) => $q->where('is_free', true))
                                         ->count(),
        ];

        return view('admin.abonnements', compact('abonnements', 'stats'));
    }
}
