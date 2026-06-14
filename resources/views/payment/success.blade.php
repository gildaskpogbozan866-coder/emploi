@extends('layouts.app')
@section('title', 'Paiement confirmé')

@section('content')
<div style="min-height:75vh;background:#f8fafc;display:flex;align-items:center;justify-content:center;padding:40px 20px">
  <div style="text-align:center;max-width:460px">
    <div style="width:80px;height:80px;border-radius:50%;background:#dcfce7;display:flex;align-items:center;justify-content:center;margin:0 auto 24px">
      <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="#16a34a" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
    </div>
    <h1 style="font-size:1.6rem;font-weight:800;color:#042C53;margin:0 0 12px">Paiement confirmé !</h1>
    <p style="font-size:14px;color:#64748b;margin:0 0 8px;line-height:1.65">
      Votre paiement de <strong style="color:#042C53">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</strong> a été reçu.
    </p>
    @if($paiement->type === 'cv_credits')
      <p style="font-size:14px;color:#16a34a;font-weight:600;margin:0 0 28px">
        {{ $paiement->credits_cv }} crédit{{ $paiement->credits_cv > 1 ? 's' : '' }} CVthèque ajouté{{ $paiement->credits_cv > 1 ? 's' : '' }} à votre compte.
      </p>
    @elseif($paiement->abonnement?->plan)
      <p style="font-size:14px;color:#16a34a;font-weight:600;margin:0 0 28px">
        Votre abonnement <strong>{{ $paiement->abonnement->plan->name }}</strong> est maintenant actif.
      </p>
    @else
      <p style="margin:0 0 28px"></p>
    @endif
    @php
      $isRec = auth()->user()->hasRole('recruteur');
      $dashRoute     = $isRec ? route('recruteur.dashboard') : route('candidat.dashboard');
      $paiementsRoute = $isRec ? route('recruteur.paiements') : route('candidat.paiements');
    @endphp
    <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
      <a href="{{ $dashRoute }}" style="display:inline-flex;align-items:center;gap:8px;padding:12px 24px;background:#185FA5;color:#fff;border-radius:10px;font-weight:700;font-size:13.5px;text-decoration:none">
        Tableau de bord
      </a>
      <a href="{{ $paiementsRoute }}" style="display:inline-flex;align-items:center;gap:8px;padding:12px 24px;background:#f1f5f9;color:#042C53;border-radius:10px;font-weight:700;font-size:13.5px;text-decoration:none">
        Mes paiements
      </a>
    </div>
    <p style="margin-top:20px;font-size:12px;color:#94a3b8">Référence : <code>{{ $paiement->reference }}</code></p>
  </div>
</div>
@endsection
