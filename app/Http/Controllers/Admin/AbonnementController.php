<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\Abonnement;
use App\Models\Notification;
use App\Models\Plan;
use App\Models\User;
use App\Notifications\AbonnementActiveNotification;
use App\Services\AbonnementSchedulingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

    /**
     * Page dédiée au formulaire d'offre manuelle d'abonnement.
     */
    public function create()
    {
        $plansDisponibles = Plan::where('is_active', true)->orderBy('target_type')->orderBy('price')->get();

        return view('admin.abonnements-offrir', compact('plansDisponibles'));
    }

    /**
     * Activation manuelle par l'admin : offre un abonnement à n'importe quel
     * utilisateur sans passer par Paiement/PaymentConfirmed (ce chemin n'a,
     * par définition, aucun paiement associé). Réutilise la même logique de
     * chaînage de dates que le flux payant normal (AbonnementSchedulingService)
     * pour ne jamais écraser un abonnement déjà en cours.
     */
    public function store(Request $request)
    {
        $request->validate([
            'email'   => 'required|email|exists:users,email',
            'plan_id' => 'required|exists:plans,id',
        ]);

        $user = User::where('email', $request->email)->firstOrFail();
        $plan = Plan::with('features')->findOrFail($request->plan_id);

        if ($plan->target_type !== 'both' && ! $user->hasRole($plan->target_type)) {
            return redirect()->route('admin.abonnements.create')->withErrors([
                'plan_id' => "Le plan « {$plan->name} » est réservé aux comptes {$plan->target_type} — {$user->email} n'a pas ce rôle.",
            ])->withInput();
        }

        $startsAt = app(AbonnementSchedulingService::class)->dateDebut($user);
        $endsAt   = $plan->duration_days ? $startsAt->copy()->addDays($plan->duration_days) : null;

        $abonnement = Abonnement::create([
            'user_id'    => $user->id,
            'plan_id'    => $plan->id,
            'status'     => 'active',
            'starts_at'  => $startsAt,
            'ends_at'    => $endsAt,
            'auto_renew' => false,
        ]);

        try {
            Notification::create([
                'user_id' => $user->id,
                'type'    => 'commande',
                'titre'   => 'Abonnement activé',
                'contenu' => "L'administration a activé votre abonnement {$plan->name}."
                    . ($endsAt ? " Valable jusqu'au " . $endsAt->format('d/m/Y') . '.' : ''),
                'lien' => match (true) {
                    $user->hasRole(Role::RECRUTEUR) => route('recruteur.abonnement'),
                    $user->hasRole(Role::ANNONCEUR) => route('annonceur.dashboard'),
                    default                          => route('candidat.abonnement'),
                },
            ]);
        } catch (\Throwable $e) {
            Log::warning('Notification in-app abonnement (admin) non créée', ['error' => $e->getMessage()]);
        }

        try {
            $user->notify(new AbonnementActiveNotification($abonnement));
        } catch (\Throwable $e) {
            Log::warning('Email AbonnementActive (admin) non envoyé', ['error' => $e->getMessage()]);
        }

        return redirect()->route('admin.abonnements')
            ->with('success', "Abonnement « {$plan->name} » activé pour {$user->email}.");
    }
}
