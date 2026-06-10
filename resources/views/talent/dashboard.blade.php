@extends('layouts.candidat')
@section('title', 'Mon espace Talent')


@section('sidebar')
@include('candidat._sidebar')
@endsection
@section('content')
<div class="cand-page-header">
  <div class="cand-page-header__left">
    <h1 class="cand-page-header__title">Bonjour, {{ auth()->user()->prenom }}</h1>
    <p class="cand-page-header__sub">Bienvenue dans votre espace Talent.</p>
  </div>
  <div class="cand-page-header__actions">
    <a href="{{ route('talent.profil') }}" class="cand-btn cand-btn--primary" style="background:#38A169;border-color:#38A169">
      <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
      Mon profil
    </a>
  </div>
</div>

{{-- Bannière profil incomplet --}}
@if($stats['completion'] < 60)
<div style="background:#fffbeb;border:1px solid #fde68a;border-radius:12px;padding:16px 20px;display:flex;align-items:center;gap:14px;flex-wrap:wrap;margin-bottom:20px">
  <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#d97706" stroke-width="1.8" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
  <div style="flex:1">
    @if(!$profil || !$profil->metier)
      <p style="font-weight:700;color:#92400e;margin:0 0 2px;font-size:14px">Complétez votre profil pour être visible par les recruteurs</p>
      <p style="font-size:13px;color:#78350f;margin:0">Ajoutez votre métier, vos compétences et votre disponibilité.</p>
    @else
      <p style="font-weight:700;color:#92400e;margin:0 0 2px;font-size:14px">Votre profil est complété à {{ $stats['completion'] }}%</p>
      <p style="font-size:13px;color:#78350f;margin:0">Complétez-le pour augmenter vos chances d'être contacté.</p>
    @endif
  </div>
  <a href="{{ route('talent.profil.edit') }}" style="padding:8px 18px;background:#d97706;color:#fff;border-radius:8px;font-weight:600;font-size:13.5px;text-decoration:none;white-space:nowrap">
    Compléter mon profil →
  </a>
</div>
@endif

{{-- Stats --}}
<div class="cand-stats">
  <div class="cand-stat">
    <div class="cand-stat__icon cand-stat__icon--green" style="--stat-color:#38A169">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#38A169" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
    </div>
    <div>
      <div class="cand-stat__val">{{ $stats['vues_profil'] }}</div>
      <div class="cand-stat__label">Vues du profil</div>
    </div>
  </div>

  <div class="cand-stat">
    <div class="cand-stat__icon" style="background:#f0fdf4">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#38A169" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
    </div>
    <div>
      <div class="cand-stat__val" style="color:{{ $stats['completion'] >= 80 ? '#38A169' : ($stats['completion'] >= 50 ? '#d97706' : '#ef4444') }}">{{ $stats['completion'] }}%</div>
      <div class="cand-stat__label">Profil complété</div>
    </div>
  </div>

  <div class="cand-stat">
    <div class="cand-stat__icon cand-stat__icon--{{ $stats['plan'] === 'premium' ? 'yellow' : 'blue' }}">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="{{ $stats['plan'] === 'premium' ? '#b45309' : '#378ADD' }}" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
    </div>
    <div>
      <div class="cand-stat__val">{{ ucfirst($stats['plan']) }}</div>
      <div class="cand-stat__label">Plan actuel</div>
    </div>
  </div>
</div>

{{-- Actions rapides --}}
<div class="cand-card">
  <div class="cand-card__head">
    <h2 class="cand-card__title">Actions rapides</h2>
  </div>
  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:12px">
    @php
    $actions = [
      ['href' => route('talent.profil'),       'label' => 'Mon profil',       'icon' => '<path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>'],
      ['href' => route('offre.list'),           'label' => 'Voir les offres',  'icon' => '<circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>'],
      ['href' => route('talent.messagerie'),    'label' => 'Messagerie',       'icon' => '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>'],
      ['href' => route('talent.abonnement'),    'label' => 'Mon abonnement',   'icon' => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>'],
    ];
    @endphp
    @foreach($actions as $action)
    <a href="{{ $action['href'] }}" style="display:flex;flex-direction:column;align-items:center;gap:10px;background:#f8fafc;border:1.5px solid #e2e6ed;border-radius:10px;padding:18px 14px;text-decoration:none;color:#042C53;font-size:13px;font-weight:600;transition:border-color .2s,box-shadow .2s" onmouseover="this.style.borderColor='#38A169';this.style.boxShadow='0 2px 12px rgba(56,161,105,.12)'" onmouseout="this.style.borderColor='#e2e6ed';this.style.boxShadow='none'">
      <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#38A169" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">{!! $action['icon'] !!}</svg>
      {{ $action['label'] }}
    </a>
    @endforeach
  </div>
</div>

{{-- Premium CTA si gratuit --}}
@if($stats['plan'] !== 'premium')
<div style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);border:1px solid #bbf7d0;border-radius:12px;padding:18px 22px;display:flex;align-items:center;gap:16px;flex-wrap:wrap">
  <div style="flex:1">
    <p style="font-weight:700;color:#042C53;margin:0 0 3px">★ Passez au Premium — 3 000 FCFA/mois</p>
    <p style="font-size:13px;color:#64748b;margin:0">Profil mis en avant · Coordonnées visibles · Badge Premium</p>
  </div>
  <a href="{{ route('talent.abonnement') }}" style="padding:9px 20px;background:#38A169;color:#fff;border-radius:8px;font-weight:700;font-size:13.5px;text-decoration:none;white-space:nowrap">
    Passer au Premium
  </a>
</div>
@endif

@endsection
