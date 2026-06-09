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
      @if($abonnement && $abonnement->plan === 'premium')
        <svg width="16" height="16" fill="#F5C842" viewBox="0 0 24 24" style="display:inline-block;vertical-align:-2px"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg> Premium
      @else
        Gratuit
      @endif
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
          <span style="color:#38A169;flex-shrink:0;margin-top:1px;display:flex"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span> {{ $f }}
        </li>
      @endforeach
    </ul>
    @if(!($abonnement && $abonnement->plan === $key))
    <form method="POST" action="{{ route('candidat.abonnement.store') }}">
      @csrf
      <input type="hidden" name="plan" value="{{ $key }}">
      <input type="hidden" name="methode" value="mobile_money">
      <button type="submit" class="cand-btn {{ $key === 'premium' ? 'cand-btn--yellow' : 'cand-btn--outline' }}" style="width:100%;justify-content:center">
        @if($key === 'premium')
          <svg width="13" height="13" fill="currentColor" viewBox="0 0 24 24" style="display:inline-block;vertical-align:-1px"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg> Passer au Premium
        @else
          Rester sur le gratuit
        @endif
      </button>
    </form>
    @else
      <button disabled class="cand-btn cand-btn--primary" style="width:100%;justify-content:center;opacity:.6">Plan actuel <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-1px"><polyline points="20 6 9 17 4 12"/></svg></button>
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
