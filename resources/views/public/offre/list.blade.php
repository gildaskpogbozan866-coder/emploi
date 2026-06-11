@extends('layouts.app')
@section('title', 'Offres d\'emploi — Emploi Bouge Bénin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/offre/list-offre.css') }}">
@endsection

@section('content')

{{-- Hero recherche --}}
<div class="ol-hero">
  <h1 class="ol-hero__title">Offres d'emploi au Bénin</h1>
  <p class="ol-hero__sub">{{ $offres->total() }} offre{{ $offres->total() !== 1 ? 's' : '' }} disponible{{ $offres->total() !== 1 ? 's' : '' }} — mise à jour en continu</p>
  <form method="GET" action="{{ route('offre.list') }}" class="ol-hero__search">
    @foreach(request()->except(['q','page']) as $k => $v)
      <input type="hidden" name="{{ $k }}" value="{{ $v }}">
    @endforeach
    <input type="text" name="q" value="{{ request('q') }}"
           placeholder="Titre, entreprise, compétence…"
           class="ol-hero__input" autocomplete="off">
    <button type="submit" class="ol-hero__btn">
      <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="m21 21-4.35-4.35"/></svg>
      Rechercher
    </button>
  </form>
</div>

{{-- Onglets type de contrat --}}
<div class="ol-tabs">
  <a href="{{ route('offre.list', request()->except(['type','page'])) }}"
     class="ol-tab {{ !request('type') ? 'active' : '' }}">Tous</a>
  @foreach(['CDI','CDD','Stage','Bourse','Freelance','Temps partiel'] as $t)
    <a href="{{ route('offre.list', array_merge(request()->except(['type','page']), ['type' => $t])) }}"
       class="ol-tab {{ request('type') === $t ? 'active' : '' }}">{{ $t }}</a>
  @endforeach
</div>

<div class="ol-body">
  <div class="ol-wrap">

    {{-- Sidebar filtres --}}
    <aside class="ol-sidebar">
      <div class="ol-sidebar__head">
        <svg class="ol-sidebar__head-icon" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 0 1 1-1h16a1 1 0 0 1 .78 1.625L14 13.197V19a1 1 0 0 1-1.447.894l-4-2A1 1 0 0 1 8 17v-3.803L3.22 5.625A1 1 0 0 1 3 4z"/></svg>
        <span class="ol-sidebar__head-title">Affiner la recherche</span>
      </div>

      <form method="GET" action="{{ route('offre.list') }}">
        {{-- Préserver q et type (gérés par hero et onglets) --}}
        @if(request('q'))
          <input type="hidden" name="q" value="{{ request('q') }}">
        @endif
        @if(request('type'))
          <input type="hidden" name="type" value="{{ request('type') }}">
        @endif

        {{-- Localisation --}}
        <div class="ol-fgroup">
          <label for="f-loc">Localisation</label>
          <input type="text" id="f-loc" name="localisation"
                 value="{{ request('localisation') }}"
                 placeholder="Cotonou, Porto-Novo…"
                 class="ol-finput">
        </div>

        {{-- Compétence --}}
        <div class="ol-fgroup">
          <label for="f-comp">Compétence</label>
          <select id="f-comp" name="competence" class="ol-fselect">
            <option value="">Toutes les compétences</option>
            @foreach($competences as $comp)
              <option value="{{ $comp->slug }}" {{ request('competence') === $comp->slug ? 'selected' : '' }}>
                {{ $comp->nom }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="ol-sidebar__actions">
          <button type="submit" class="ol-btn-apply">Appliquer les filtres</button>
          @if(request()->hasAny(['localisation','competence']))
            <a href="{{ route('offre.list', request()->only(['q','type'])) }}" class="ol-btn-reset">
              Réinitialiser
            </a>
          @endif
        </div>
      </form>
    </aside>

    {{-- Liste principale --}}
    <div>
      {{-- Barre résultats --}}
      <div class="ol-bar">
        <p class="ol-bar__count">
          <span>{{ $offres->total() }}</span> offre{{ $offres->total() !== 1 ? 's' : '' }}
          @if(request()->hasAny(['q','type','localisation','competence']))
            trouvée{{ $offres->total() !== 1 ? 's' : '' }}
          @else
            disponible{{ $offres->total() !== 1 ? 's' : '' }}
          @endif
        </p>
        <div class="ol-bar__active-filters">
          @if(request('q'))
            <span class="ol-chip">{{ request('q') }}</span>
          @endif
          @if(request('type'))
            <span class="ol-chip">{{ request('type') }}</span>
          @endif
          @if(request('localisation'))
            <span class="ol-chip">{{ request('localisation') }}</span>
          @endif
        </div>
      </div>

      {{-- Offres --}}
      <div class="ol-list">
        @forelse($offres as $offre)
          <a href="{{ route('offre.detail', $offre) }}" class="ol-card">

            {{-- Avatar entreprise --}}
            <div class="ol-card__avatar">
              {{ strtoupper(substr($offre->entreprise, 0, 2)) }}
            </div>

            {{-- Corps --}}
            <div class="ol-card__body">
              <div class="ol-card__title">{{ $offre->titre }}</div>
              <div class="ol-card__company">{{ $offre->entreprise }}</div>
              <p class="ol-card__desc">{{ Str::limit(strip_tags($offre->description), 130) }}</p>
              <div class="ol-card__meta">
                <span class="ol-badge ol-badge--type">{{ $offre->type }}</span>
                <span class="ol-badge ol-badge--loc">
                  <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                  {{ $offre->localisation }}
                </span>
                @if($offre->secteur)
                  <span class="ol-badge ol-badge--secteur">{{ $offre->secteur }}</span>
                @endif
              </div>
            </div>

            {{-- Colonne droite --}}
            <div class="ol-card__right">
              <span class="ol-card__date">{{ $offre->created_at->diffForHumans() }}</span>
              @if($offre->date_limite)
                <span class="ol-card__deadline">Expire {{ $offre->date_limite->format('d/m/Y') }}</span>
              @endif
              <span class="ol-card__cta">Voir l'offre</span>
            </div>

          </a>
        @empty
          <div class="ol-empty">
            <p class="ol-empty__title">Aucune offre ne correspond à vos critères.</p>
            <p class="ol-empty__sub">Essayez d'élargir vos filtres ou consultez toutes les offres.</p>
            <a href="{{ route('offre.list') }}" class="ol-empty__link">Voir toutes les offres</a>
          </div>
        @endforelse
      </div>

      @if($offres->hasPages())
        <div style="margin-top: 28px">
          {{ $offres->withQueryString()->links() }}
        </div>
      @endif
    </div>

  </div>
</div>

@endsection
