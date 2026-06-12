<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentSetting;
use Illuminate\Http\Request;

class PaymentSettingsController extends Controller
{
    public function index()
    {
        $gateways = [
            'fedapay' => PaymentSetting::firstOrCreate(
                ['gateway' => 'fedapay'],
                ['env' => 'sandbox', 'is_active' => false]
            ),
            'kkiapay' => PaymentSetting::firstOrCreate(
                ['gateway' => 'kkiapay'],
                ['env' => 'sandbox', 'is_active' => false]
            ),
        ];
        return view('admin.payment-settings', compact('gateways'));
    }

    public function update(Request $request, string $gateway)
    {
        abort_if(!in_array($gateway, ['fedapay', 'kkiapay']), 404);

        $request->validate([
            'env'            => 'required|in:sandbox,live',
            'public_key'     => 'nullable|string|max:500',
            'secret_key'     => 'nullable|string|max:1000',
            'webhook_secret' => 'nullable|string|max:500',
            'is_active'      => 'boolean',
        ]);

        $setting = PaymentSetting::firstOrCreate(['gateway' => $gateway]);

        $data = [
            'env'       => $request->env,
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->filled('public_key')) {
            $data['public_key'] = $request->public_key;
        }
        if ($request->filled('secret_key')) {
            $data['secret_key'] = $request->secret_key;
        }
        if ($request->filled('webhook_secret')) {
            $data['webhook_secret'] = $request->webhook_secret;
        }

        $setting->update($data);

        return back()->with('success', ucfirst($gateway) . ' mis à jour avec succès.');
    }

    public function export(Request $request)
    {
        $request->validate([
            'statut'    => 'nullable|in:en_attente,confirme,echec,rembourse',
            'gateway'   => 'nullable|in:fedapay,kkiapay,manuel',
            'categorie' => 'nullable|string',
            'date_from' => 'nullable|date',
            'date_to'   => 'nullable|date',
        ]);

        $query = \App\Models\Paiement::with(['user', 'abonnement.plan'])->latest();

        if ($request->filled('statut'))    $query->where('statut', $request->statut);
        if ($request->filled('gateway'))   $query->where('gateway', $request->gateway);
        if ($request->filled('date_from')) $query->whereDate('created_at', '>=', $request->date_from);
        if ($request->filled('date_to'))   $query->whereDate('created_at', '<=', $request->date_to);
        if ($request->filled('categorie')) {
            if ($request->categorie === 'cv_credits')  $query->where('type', 'cv_credits');
            elseif ($request->categorie === 'abonnement') $query->whereNotNull('subscription_id');
        }

        $paiements = $query->get();

        $filename = 'paiements-' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($paiements) {
            $fh = fopen('php://output', 'w');
            // BOM UTF-8 pour Excel
            fputs($fh, "\xEF\xBB\xBF");
            fputcsv($fh, ['Référence', 'Utilisateur', 'Email', 'Type', 'Gateway', 'Montant', 'Frais', 'Statut', 'Date'], ';');
            foreach ($paiements as $p) {
                fputcsv($fh, [
                    $p->reference,
                    ($p->user->prenom ?? '') . ' ' . ($p->user->nom ?? ''),
                    $p->user->email ?? '',
                    $p->type,
                    $p->gateway,
                    $p->montant,
                    $p->gateway_fees,
                    $p->statut,
                    $p->created_at->format('d/m/Y H:i'),
                ], ';');
            }
            fclose($fh);
        };

        return response()->stream($callback, 200, $headers);
    }
}
