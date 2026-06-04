@extends('layouts.candidat')
@section('title', 'Mon abonnement')

@section('sidebar')
@include('candidat._sidebar')
@endsection

@section('content')
<div class="cand-page-header">
  <div class="cand-page-header__left">
    <h1 class="cand-page-header__title">Mon abonnement</h1>
    <p class="cand-page-header__sub">Gérez votre plan et accédez à plus de fonctionnalités</p>
  </div>
</div>

{{-- Plan actuel --}}
<div style="background:{{ $abonnement && $abonnement->plan === 'premium' ? 'linear-gradient(135deg,#021e3a 0%,#185FA5 100%)' : '#fff' }};border:1px solid {{ $abonnement && $abonnement->plan === 'premium' ? 'transparent' : '#e2e6ed' }};border-radius:14px;padding:26px;margin-bottom:22px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px">
  <div>
    <p style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:{{ $abonnement && $abonnement->plan === 'premium' ? 'rgba(255,255,255,.6)' : '#94a3b8' }};margin:0 0 6px">Plan actuel</p>
    <h2 style="font-size:1.8rem;font-weight:800;margin:0;color:{{ $abonnement && $abonnement->plan === 'premium' ? '#fff' : '#042C53' }}">
      {{ $abonnement && $abonnement->plan === 'premium' ? '★ Premium' : 'Gratuit' }}
    </h2>
    @if($abonnement && $abonnement->expire_le)
      <p style="font-size:13px;margin:6px 0 0;color:{{ $abonnement && $abonnement->plan === 'premium' ? 'rgba(255,255,255,.7)' : '#64748b' }}">Expire le {{ $abonnement->expire_le->format('d/m/Y') }}</p>
    @endif
  </div>
  @if($abonnement && $abonnement->plan === 'premium')
    <div style="background:rgba(255,255,255,.15);border-radius:12px;padding:14px 20px;text-align:center">
      <p style="font-size:1.5rem;font-weight:800;margin:0;color:#fff">5 000 FCFA</p>
      <p style="font-size:12px;margin:2px 0 0;color:rgba(255,255,255,.65)">/ 30 jours</p>
    </div>
  @endif
</div>

{{-- Plans --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:16px;margin-bottom:28px">
  @foreach($plans as $key => $plan)
  <div style="background:#fff;border:2px solid {{ ($abonnement && $abonnement->plan === $key) ? '#185FA5' : '#e2e6ed' }};border-radius:14px;padding:24px;position:relative">
    @if($key === 'premium')
      <div style="position:absolute;top:-12px;left:50%;transform:translateX(-50%);background:#F5C842;color:#042C53;font-size:11px;font-weight:800;padding:3px 16px;border-radius:20px;text-transform:uppercase;letter-spacing:.05em;white-space:nowrap">Recommandé</div>
    @endif
    <h3 style="font-size:1.1rem;font-weight:700;color:#042C53;margin:0 0 6px">{{ $plan['label'] }}</h3>
    <p style="font-size:1.5rem;font-weight:800;color:{{ $key === 'premium' ? '#185FA5' : '#94a3b8' }};margin:0 0 4px">
      {{ $plan['prix'] > 0 ? number_format($plan['prix'],0,',',' ').' FCFA' : 'Gratuit' }}
    </p>
    @if($plan['prix'] > 0)<p style="font-size:12px;color:#94a3b8;margin:0 0 16px">par mois</p>@else<p style="margin:0 0 16px"></p>@endif
    <ul style="list-style:none;padding:0;margin:0 0 20px;display:flex;flex-direction:column;gap:8px">
      @foreach($plan['features'] as $f)
        <li style="font-size:13.5px;color:#475569;display:flex;align-items:flex-start;gap:8px">
          <span style="color:#38A169;font-weight:700;flex-shrink:0;margin-top:1px">✓</span> {{ $f }}
        </li>
      @endforeach
    </ul>
    @if(!($abonnement && $abonnement->plan === $key))
    <form method="POST" action="{{ route('candidat.abonnement.store') }}">
      @csrf
      <input type="hidden" name="plan" value="{{ $key }}">
      <input type="hidden" name="methode" value="mobile_money">
      <button type="submit" class="cand-btn {{ $key === 'premium' ? 'cand-btn--yellow' : 'cand-btn--outline' }}" style="width:100%;justify-content:center">
        {{ $key === 'premium' ? '★ Passer au Premium' : 'Rester sur le gratuit' }}
      </button>
    </form>
    @else
      <button disabled class="cand-btn cand-btn--primary" style="width:100%;justify-content:center;opacity:.6">Plan actuel ✓</button>
    @endif
  </div>
  @endforeach
</div>

{{-- Historique --}}
@if($historique->isNotEmpty())
<div class="cand-card">
  <div class="cand-card__head">
    <h2 class="cand-card__title">Historique des abonnements</h2>
  </div>
  <div class="cand-table-wrap">
    <table class="cand-table">
      <thead>
        <tr><th>Plan</th><th>Prix</th><th>Début</th><th>Expiration</th><th>Statut</th></tr>
      </thead>
      <tbody>
        @foreach($historique as $ab)
        <tr>
          <td style="font-weight:600">{{ ucfirst($ab->plan) }}</td>
          <td>{{ $ab->prix > 0 ? number_format($ab->prix,0,',',' ').' FCFA' : 'Gratuit' }}</td>
          <td style="color:#6b7a8d">{{ $ab->debut_le?->format('d/m/Y') ?? '—' }}</td>
          <td style="color:#6b7a8d">{{ $ab->expire_le?->format('d/m/Y') ?? 'Illimité' }}</td>
          <td><span class="cand-badge cand-badge--{{ $ab->statut === 'actif' ? 'green' : 'gray' }}">{{ ucfirst($ab->statut) }}</span></td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
</div>
@endif
@endsection
