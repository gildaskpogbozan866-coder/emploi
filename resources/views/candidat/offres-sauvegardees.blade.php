@extends('layouts.candidat')
@section('title', 'Offres sauvegardées')

@section('sidebar')
@include('candidat._sidebar')
@endsection

@section('content')
<div class="cand-page-header">
  <div class="cand-page-header__left">
    <h1 class="cand-page-header__title">Offres sauvegardées</h1>
    <p class="cand-page-header__sub">Les offres que vous avez mis de côté pour postuler plus tard</p>
  </div>
  <div class="cand-page-header__actions">
    <a href="{{ route('offre.list') }}" class="cand-btn cand-btn--yellow">Parcourir les offres</a>
  </div>
</div>

@include('partials._search-bar', [
  'route'       => 'candidat.offres-sauvegardees',
  'placeholder' => 'Rechercher par titre ou entreprise…',
  'filters'     => [
    ['name' => 'type', 'label' => 'Tous les types', 'options' => array_combine(['CDI','CDD','Stage','Bourse','Freelance','Temps partiel'],['CDI','CDD','Stage','Bourse','Freelance','Temps partiel'])],
  ],
])

@forelse($offres as $offre)
  <div class="cand-card" style="margin-bottom:14px;display:flex;align-items:flex-start;justify-content:space-between;gap:16px;flex-wrap:wrap">
    <div style="flex:1;min-width:0">
      <h3 style="font-size:15px;font-weight:700;color:#042C53;margin:0 0 4px">
        <a href="{{ route('offre.detail', $offre) }}" style="color:inherit;text-decoration:none">{{ $offre->titre }}</a>
      </h3>
      <p style="color:#185FA5;font-size:14px;margin:0 0 10px;font-weight:600">{{ $offre->entreprise }}</p>
      <div style="display:flex;flex-wrap:wrap;gap:6px">
        <span class="cand-badge cand-badge--blue">{{ $offre->type }}</span>
        <span class="cand-badge cand-badge--gray">{{ $offre->localisation }}</span>
        @if($offre->salaire)<span class="cand-badge cand-badge--green">{{ $offre->salaire }}</span>@endif
      </div>
    </div>
    <div style="display:flex;flex-direction:column;align-items:flex-end;gap:8px;flex-shrink:0">
      <a href="{{ route('offre.postuler', $offre) }}" class="cand-btn cand-btn--yellow cand-btn--sm">Postuler</a>
      <form method="POST" action="{{ route('candidat.offres-sauvegardees.toggle', $offre) }}">
        @csrf
        <button type="submit" class="cand-btn cand-btn--outline cand-btn--sm">Retirer ✕</button>
      </form>
      <p style="font-size:11px;color:#94a3b8;margin:0">Sauvegardée {{ $offre->pivot?->created_at?->diffForHumans() ?? '' }}</p>
    </div>
  </div>
@empty
  <div class="cand-card">
    <div class="cand-empty">
      <div class="cand-empty__icon">
        <svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"/></svg>
      </div>
      <p class="cand-empty__title">Aucune offre sauvegardée</p>
      <p class="cand-empty__text">Sauvegardez des offres pour les retrouver facilement et postuler quand vous êtes prêt.</p>
      <a href="{{ route('offre.list') }}" class="cand-btn cand-btn--primary">Parcourir les offres</a>
    </div>
  </div>
@endforelse

@if($offres->hasPages())
  <div style="margin-top:20px">{{ $offres->links() }}</div>
@endif
@endsection
