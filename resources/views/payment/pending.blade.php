@extends('layouts.app')
@section('title', 'Paiement en attente')

@section('content')
<div style="min-height:75vh;background:#f8fafc;display:flex;align-items:center;justify-content:center;padding:40px 20px">
  <div style="text-align:center;max-width:480px">
    <div style="width:80px;height:80px;border-radius:50%;background:#fef9c3;display:flex;align-items:center;justify-content:center;margin:0 auto 24px">
      <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="#ca8a04" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    </div>
    <h1 style="font-size:1.6rem;font-weight:800;color:#042C53;margin:0 0 12px">Demande enregistrée</h1>
    <p style="font-size:14px;color:#64748b;margin:0 0 16px;line-height:1.65">
      Votre demande de paiement de <strong style="color:#042C53">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</strong> a bien été reçue.
    </p>
    <div style="background:#fffbeb;border:1.5px solid #fde68a;border-radius:12px;padding:16px 20px;margin-bottom:28px;text-align:left">
      <p style="font-size:13.5px;color:#92400e;margin:0;line-height:1.6">
        <strong>Que se passe-t-il maintenant ?</strong><br>
        Un conseiller vous contactera dans les <strong>24 heures</strong> pour finaliser le paiement. Dès réception, vos crédits ou abonnement seront activés automatiquement.
      </p>
    </div>
    <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
      <a href="{{ route('recruteur.paiements') }}" style="display:inline-flex;align-items:center;gap:8px;padding:12px 24px;background:#185FA5;color:#fff;border-radius:10px;font-weight:700;font-size:13.5px;text-decoration:none">
        Mes paiements
      </a>
      <a href="{{ route('recruteur.dashboard') }}" style="display:inline-flex;align-items:center;gap:8px;padding:12px 24px;background:#f1f5f9;color:#042C53;border-radius:10px;font-weight:700;font-size:13.5px;text-decoration:none">
        Tableau de bord
      </a>
    </div>
    <p style="margin-top:20px;font-size:12px;color:#94a3b8">Référence : <code>{{ $paiement->reference }}</code></p>
  </div>
</div>
@endsection
