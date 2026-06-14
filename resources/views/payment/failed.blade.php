@extends('layouts.app')
@section('title', 'Paiement échoué')

@section('content')
<div style="min-height:75vh;background:#f8fafc;display:flex;align-items:center;justify-content:center;padding:40px 20px">
  <div style="text-align:center;max-width:460px">
    <div style="width:80px;height:80px;border-radius:50%;background:#fee2e2;display:flex;align-items:center;justify-content:center;margin:0 auto 24px">
      <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="#dc2626" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
    </div>
    <h1 style="font-size:1.6rem;font-weight:800;color:#042C53;margin:0 0 12px">Paiement échoué</h1>
    <p style="font-size:14px;color:#64748b;margin:0 0 28px;line-height:1.65">
      Votre paiement n'a pas pu être traité. Aucun montant n'a été débité.
    </p>
    @php
      $dashRoute = auth()->user()->hasRole('recruteur')
        ? route('recruteur.dashboard')
        : route('candidat.dashboard');
    @endphp
    <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
      <a href="{{ route('payment.choose', $paiement) }}" style="display:inline-flex;align-items:center;gap:8px;padding:12px 24px;background:#185FA5;color:#fff;border-radius:10px;font-weight:700;font-size:13.5px;text-decoration:none">
        Réessayer
      </a>
      <a href="{{ $dashRoute }}" style="display:inline-flex;align-items:center;gap:8px;padding:12px 24px;background:#f1f5f9;color:#042C53;border-radius:10px;font-weight:700;font-size:13.5px;text-decoration:none">
        Tableau de bord
      </a>
    </div>
    <p style="margin-top:20px;font-size:12px;color:#94a3b8">Référence : <code>{{ $paiement->reference }}</code></p>
  </div>
</div>
@endsection
