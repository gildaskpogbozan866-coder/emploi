<?php

namespace App\Http\Controllers\Recruteur;

use App\Http\Controllers\Controller;
use App\Models\Paiement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaiementController extends Controller
{
    public function index(Request $request)
    {
        $query = Paiement::where('user_id', Auth::id())->with('abonnement.plan')->latest();

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        if ($request->filled('type')) {
            if ($request->type === 'abonnement') {
                $query->whereNotNull('subscription_id');
            } elseif ($request->type === 'cv_credits') {
                $query->where('type', 'cv_credits');
            }
        }
        if ($request->filled('gateway')) {
            $query->where('gateway', $request->gateway);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $paiements = $query->paginate(15)->withQueryString();

        $stats = [
            'total_paye'   => Paiement::where('user_id', Auth::id())->where('statut', 'confirme')->sum('montant'),
            'nb_confirme'  => Paiement::where('user_id', Auth::id())->where('statut', 'confirme')->count(),
            'nb_attente'   => Paiement::where('user_id', Auth::id())->where('statut', 'en_attente')->count(),
        ];

        return view('recruteur.paiements', compact('paiements', 'stats'));
    }

    public function export(Request $request)
    {
        $query = Paiement::where('user_id', Auth::id())->with('abonnement.plan')->latest();

        if ($request->filled('statut'))    $query->where('statut', $request->statut);
        if ($request->filled('type')) {
            if ($request->type === 'abonnement') $query->whereNotNull('subscription_id');
            elseif ($request->type === 'cv_credits') $query->where('type', 'cv_credits');
        }
        if ($request->filled('date_from')) $query->whereDate('created_at', '>=', $request->date_from);
        if ($request->filled('date_to'))   $query->whereDate('created_at', '<=', $request->date_to);

        $paiements = $query->get();
        $filename  = 'mes-paiements-' . now()->format('Y-m-d') . '.csv';

        return response()->stream(function () use ($paiements) {
            $fh = fopen('php://output', 'w');
            fputs($fh, "\xEF\xBB\xBF");
            fputcsv($fh, ['Référence', 'Type', 'Gateway', 'Montant (FCFA)', 'Statut', 'Date'], ';');
            foreach ($paiements as $p) {
                fputcsv($fh, [
                    $p->reference,
                    $p->type === 'cv_credits'
                        ? "Crédits CVthèque ({$p->credits_cv})"
                        : ($p->abonnement?->plan?->name ?? $p->type),
                    ucfirst($p->gateway),
                    $p->montant,
                    match($p->statut) {
                        'confirme'   => 'Confirmé',
                        'en_attente' => 'En attente',
                        'echec'      => 'Échec',
                        'rembourse'  => 'Remboursé',
                        default      => $p->statut,
                    },
                    $p->created_at->format('d/m/Y H:i'),
                ], ';');
            }
            fclose($fh);
        }, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ]);
    }
}
