@extends('layouts.app')
@section('title', 'Offres d\'emploi — Emploi Bouge Bénin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/offre/list-offre.css') }}">
@endsection

@section('content')
<section class="page-hero">
  <div class="container">
    <h1 class="page-hero__title">Offres d'emploi</h1>
    <p class="page-hero__sub">{{ $offres->total() }} offres disponibles — mises à jour en continu</p>
  </div>
</section>

<section class="section">
  <div class="container">
    <div class="offre-layout">

      {{-- Filtres --}}
      <aside class="offre-filters">
        <form method="GET" action="{{ route('offre.list') }}">
          <div class="filter-group">
            <label>Recherche</label>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Titre, entreprise…" class="filter-input">
          </div>
          <div class="filter-group">
            <label>Type de contrat</label>
            <select name="type" class="filter-input">
              <option value="">Tous</option>
              @foreach(['CDI','CDD','Stage','Bourse','Freelance','Temps partiel'] as $t)
                <option value="{{ $t }}" {{ request('type') === $t ? 'selected' : '' }}>{{ $t }}</option>
              @endforeach
            </select>
          </div>
          <div class="filter-group">
            <label>Localisation</label>
            <input type="text" name="localisation" value="{{ request('localisation') }}" placeholder="Cotonou, Bénin…" class="filter-input">
          </div>
          <button type="submit" class="btn btn--blue" style="width:100%">Filtrer</button>
          @if(request()->hasAny(['q','type','localisation','secteur']))
            <a href="{{ route('offre.list') }}" class="btn btn--outline" style="width:100%;text-align:center;margin-top:8px">Réinitialiser</a>
          @endif
        </form>
      </aside>

      {{-- Liste des offres --}}
      <div class="offre-list">
        @forelse($offres as $offre)
          <article class="offre-card">
            <div class="offre-card__header">
              <div class="offre-card__logo-wrap">
                <div class="offre-card__logo">{{ strtoupper(substr($offre->entreprise, 0, 2)) }}</div>
              </div>
              <div class="offre-card__meta">
                <h2 class="offre-card__title">
                  <a href="{{ route('offre.detail', $offre) }}">{{ $offre->titre }}</a>
                </h2>
                <p class="offre-card__company">{{ $offre->entreprise }}</p>
                <p class="offre-card__loc">
                  <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                  {{ $offre->localisation }}
                </p>
              </div>
              <div class="offre-card__tags">
                <span class="tag tag--type">{{ $offre->type }}</span>
                @if($offre->secteur)
                  <span class="tag tag--secteur">{{ $offre->secteur }}</span>
                @endif
              </div>
            </div>
            <p class="offre-card__desc">{{ Str::limit($offre->description, 150) }}</p>
            <div class="offre-card__footer">
              <span class="offre-card__date">{{ $offre->created_at->diffForHumans() }}</span>
              @if($offre->date_limite)
                <span class="offre-card__deadline">Expire le {{ $offre->date_limite->format('d/m/Y') }}</span>
              @endif
              <a href="{{ route('offre.postuler', $offre) }}" class="btn btn--yellow offre-card__btn">Postuler</a>
            </div>
          </article>
        @empty
          <div class="empty-state">
            <p>Aucune offre ne correspond à vos critères.</p>
            <a href="{{ route('offre.list') }}" class="btn btn--blue">Voir toutes les offres</a>
          </div>
        @endforelse

        {{-- Pagination --}}
        <div style="margin-top:32px">
          {{ $offres->links() }}
        </div>
      </div>

    </div>
  </div>
</section>
@endsection
