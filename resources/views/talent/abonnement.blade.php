@extends('layouts.dashboard')
@section('title', 'Mon abonnement Talent')
@section('space-label', 'Espace Talent')

@section('sidebar')
<a href="{{ route('home') }}" class="dash-sidebar__logo">
  <span>Emploi Bouge</span><small>Bénin · Talent</small>
</a>
<div class="dash-sidebar__user">
  <div class="dash-sidebar__avatar">{{ auth()->user()->initiale }}</div>
  <div class="dash-sidebar__info">
    <div class="dash-sidebar__name">{{ auth()->user()->nom_complet }}</div>
    <div class="dash-sidebar__role">{{ auth()->user()->metier ?? 'Talent' }}</div>
  </div>
</div>
<ul class="dash-nav">
  <li class="dash-nav__item {{ request()->routeIs('talent.dashboard') ? 'active' : '' }}">
    <a href="{{ route('talent.dashboard') }}">Tableau de bord</a>
  </li>
  <li class="dash-nav__item {{ request()->routeIs('talent.profil*') ? 'active' : '' }}">
    <a href="{{ route('talent.profil') }}">Mon profil</a>
  </li>
  <li class="dash-nav__item {{ request()->routeIs('talent.messagerie*') ? 'active' : '' }}">
    <a href="{{ route('talent.messagerie') }}">Messagerie</a>
  </li>
  <li class="dash-nav__item active">
    <a href="{{ route('talent.abonnement') }}">Abonnement Premium</a>
  </li>
  <li class="dash-nav__item {{ request()->routeIs('talent.parametres*') ? 'active' : '' }}">
    <a href="{{ route('talent.parametres') }}">Paramètres</a>
  </li>
</ul>
@endsection

@section('content')
<div class="dash-content">
  <div class="dash-content__header">
    <h1 class="dash-content__title">Mon abonnement Talent</h1>
    <p style="color:#6b7a8d;margin:0">Boostez la visibilité de votre profil auprès des recruteurs</p>
  </div>

  {{-- Plan actuel --}}
  <div style="background:{{ $abonnement && $abonnement->plan === 'premium' ? 'linear-gradient(135deg,#021e3a 0%,#185FA5 100%)' : '#fff' }};border:1px solid {{ $abonnement && $abonnement->plan === 'premium' ? 'transparent' : '#e2e8f0' }};border-radius:14px;padding:26px;margin-bottom:24px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:14px">
    <div>
      <p style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:{{ $abonnement && $abonnement->plan === 'premium' ? 'rgba(255,255,255,.55)' : '#94a3b8' }};margin:0 0 6px">Plan actuel</p>
      <h2 style="font-size:1.8rem;font-weight:800;margin:0;color:{{ $abonnement && $abonnement->plan === 'premium' ? '#fff' : '#042C53' }}">
        {{ $abonnement && $abonnement->plan === 'premium' ? '★ Premium' : 'Gratuit' }}
      </h2>
      @if($abonnement && $abonnement->expire_le)
        <p style="font-size:13px;margin:6px 0 0;color:{{ $abonnement && $abonnement->plan === 'premium' ? 'rgba(255,255,255,.7)' : '#64748b' }}">Expire le {{ $abonnement->expire_le->format('d/m/Y') }}</p>
      @endif
    </div>
    @if($abonnement && $abonnement->plan === 'premium')
      <div style="background:rgba(255,255,255,.15);border-radius:12px;padding:14px 20px;text-align:center">
        <p style="font-size:1.5rem;font-weight:800;margin:0;color:#fff">3 000 FCFA</p>
        <p style="font-size:12px;margin:2px 0 0;color:rgba(255,255,255,.65)">/ 30 jours</p>
      </div>
    @endif
  </div>

  {{-- Plans --}}
  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:16px;margin-bottom:28px">
    @foreach($plans as $key => $plan)
    <div style="background:#fff;border:2px solid {{ ($abonnement && $abonnement->plan === $key) ? '#185FA5' : '#e2e8f0' }};border-radius:14px;padding:24px;position:relative">
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
      <form method="POST" action="{{ route('talent.abonnement.store') }}">
        @csrf
        <input type="hidden" name="plan" value="{{ $key }}">
        <button type="submit" style="width:100%;padding:10px 18px;border-radius:8px;font-weight:700;font-size:13.5px;cursor:pointer;border:{{ $key === 'premium' ? 'none' : '1.5px solid #cbd5e0' }};background:{{ $key === 'premium' ? '#F5C842' : 'transparent' }};color:{{ $key === 'premium' ? '#042C53' : '#475569' }}">
          {{ $key === 'premium' ? '★ Passer au Premium' : 'Rester sur le gratuit' }}
        </button>
      </form>
      @else
        <button disabled style="width:100%;padding:10px 18px;border-radius:8px;font-weight:700;font-size:13.5px;background:#185FA5;color:#fff;border:none;opacity:.65">Plan actuel ✓</button>
      @endif
    </div>
    @endforeach
  </div>

  <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:18px 22px">
    <p style="font-size:13px;color:#64748b;margin:0">
      <strong style="color:#042C53">Pourquoi passer au Premium ?</strong>
      Votre profil apparaîtra en tête des résultats de recherche des recruteurs, vos coordonnées seront directement visibles et vous bénéficierez d'un badge Premium ★ valorisant.
    </p>
  </div>
</div>
@endsection
