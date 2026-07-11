@extends('layouts.app')
@section('title', 'Offres d\'emploi au Bénin, CDI, CDD, Stage, Bourse à Cotonou | Emploi Bouge Bénin')
@section('description', 'Consultez toutes les offres d\'emploi au Bénin : CDI, CDD, stages, bourses et freelance à Cotonou et dans tout le Bénin. Annonces vérifiées, mise à jour quotidienne.')
@section('og_title', 'Offres d\'emploi au Bénin, Annonces CDI, CDD, Stages | Emploi Bouge Bénin')
@section('og_description', 'Parcourez les offres d\'emploi au Bénin. Trouvez un CDI, CDD, stage ou bourse à Cotonou et dans tout le Bénin. Candidature en ligne.')

@section('jsonld')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {"@type":"ListItem","position":1,"name":"Accueil","item":"{{ route('home') }}"},
    {"@type":"ListItem","position":2,"name":"Offres d'emploi","item":"{{ route('offre.list') }}"}
  ]
}
</script>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/offre/list-offre.css') }}">
<link rel="stylesheet" href="{{ asset('css/searchable-select.css') }}">
<style>
/* Accordéon */
.ol-acc{border-bottom:1px solid #eef1f6}
.ol-acc:last-child{border-bottom:none}
.ol-acc__head{display:flex;align-items:center;gap:8px;width:100%;background:none;border:none;padding:12px 18px;cursor:pointer;font-size:13px;font-weight:700;color:#374151;text-align:left;transition:background .12s,color .12s}
.ol-acc__head:hover{background:#f5f9ff;color:#185FA5}
.ol-acc__head>span:first-child{flex:1}
.ol-acc__badge{background:#185FA5;color:#fff;border-radius:99px;padding:1px 8px;font-size:11px;font-weight:700;flex-shrink:0;line-height:1.6}
.ol-acc__chevron{transition:transform .2s;color:#b0bac8;flex-shrink:0}
.ol-acc.open>.ol-acc__head{background:#eef6ff;color:#185FA5}
.ol-acc.open>.ol-acc__head .ol-acc__chevron{transform:rotate(180deg);color:#185FA5}
.ol-acc__inner{display:none;padding:0 18px 14px}
.ol-acc.open .ol-acc__inner{display:block}
/* Recherche dans accordéon */
.ol-acc__search{margin-bottom:8px;position:relative}
.ol-acc__search-icon{position:absolute;left:9px;top:50%;transform:translateY(-50%);color:#94a3b8;pointer-events:none}
.ol-acc__search input{width:100%;padding:7px 28px 7px 30px;border:1.5px solid #e2e8f0;border-radius:8px;font-size:12.5px;color:#042C53;outline:none;box-sizing:border-box;font-family:inherit;background:#f8fafc;transition:border-color .15s,background .15s}
.ol-acc__search input:focus{border-color:#185FA5;background:#fff;box-shadow:0 0 0 3px rgba(24,95,165,.08)}
.ol-acc__search-clear{position:absolute;right:7px;top:50%;transform:translateY(-50%);background:none;border:none;color:#94a3b8;cursor:pointer;font-size:15px;width:18px;height:18px;display:none;align-items:center;justify-content:center;border-radius:50%;padding:0;line-height:1}
.ol-acc__search-clear:hover{background:#e2e8f0;color:#042C53}
.ol-acc__empty{font-size:12px;color:#94a3b8;font-style:italic;padding:6px 4px;margin:0;display:none}
/* Liste de cases */
.ol-acc__list{max-height:200px;overflow-y:auto;display:flex;flex-direction:column;gap:2px}
.ol-acc__list::-webkit-scrollbar{width:4px}
.ol-acc__list::-webkit-scrollbar-track{background:#f1f5f9}
.ol-acc__list::-webkit-scrollbar-thumb{background:#cbd5e1;border-radius:2px}
.ol-acc__list::-webkit-scrollbar-thumb:hover{background:#94a3b8}
.ol-acc__list--short{max-height:none;overflow:visible}
/* Checkbox items */
.ol-chk{display:flex;align-items:center;gap:9px;padding:6px 8px;cursor:pointer;border-radius:8px;transition:background .12s}
.ol-chk:hover{background:#f0f7ff}
.ol-chk:has(input:checked){background:#e8f3ff}
.ol-chk input[type=checkbox]{width:15px;height:15px;flex-shrink:0;cursor:pointer;accent-color:#185FA5}
.ol-chk__label{font-size:13px;color:#4b5563;line-height:1.3;flex:1}
.ol-chk input:checked~.ol-chk__label{color:#185FA5;font-weight:700}
</style>
@endsection

@section('content')

{{-- Hero recherche --}}
<div class="ol-hero">
  <h1 class="ol-hero__title">Offres d'emploi au Bénin</h1>
  <p class="ol-hero__sub">{{ $offres->total() }} offre{{ $offres->total() !== 1 ? 's' : '' }} disponible{{ $offres->total() !== 1 ? 's' : '' }}, mise à jour en continu</p>
  <form method="GET" action="{{ route('offre.list') }}" class="ol-hero__search">
    @foreach(request()->except(['q','page']) as $k => $v)
      @foreach((array)$v as $item)
        <input type="hidden" name="{{ $k }}{{ is_array($v) ? '[]' : '' }}" value="{{ $item }}">
      @endforeach
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


<div class="ol-body">
  <div class="ol-wrap">

    {{-- Bouton toggle filtres (mobile uniquement) --}}
    <button class="ol-filter-toggle" id="ol-filter-toggle" aria-expanded="false">
      <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 0 1 1-1h16a1 1 0 0 1 .78 1.625L14 13.197V19a1 1 0 0 1-1.447.894l-4-2A1 1 0 0 1 8 17v-3.803L3.22 5.625A1 1 0 0 1 3 4z"/></svg>
      <span id="ol-toggle-label">Afficher les filtres</span>
      @php $hasFilters = request()->hasAny(['type','metier','niveau_experience','niveau_etude','localisation','secteur','competence']); @endphp
      @if($hasFilters)
        <span style="margin-left:auto;background:#185FA5;color:#fff;border-radius:99px;padding:2px 8px;font-size:11px">Actifs</span>
      @endif
    </button>

    {{-- Sidebar filtres --}}
    <aside class="ol-sidebar{{ $hasFilters ? ' open' : '' }}" id="ol-sidebar">
      @php
        $totalActiveFilters = count((array)request('type',[]))
          + count((array)request('metier',[]))
          + count((array)request('niveau_experience',[]))
          + count((array)request('niveau_etude',[]))
          + count((array)request('localisation',[]))
          + count((array)request('secteur',[]))
          + count((array)request('competence',[]));
      @endphp
      <div class="ol-sidebar__head">
        <svg class="ol-sidebar__head-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 4a1 1 0 0 1 1-1h16a1 1 0 0 1 .78 1.625L14 13.197V19a1 1 0 0 1-1.447.894l-4-2A1 1 0 0 1 8 17v-3.803L3.22 5.625A1 1 0 0 1 3 4z"/></svg>
        <span class="ol-sidebar__head-title">Affiner la recherche</span>
        @if($totalActiveFilters > 0)
          <span class="ol-sidebar__head-count">{{ $totalActiveFilters }}</span>
        @endif
      </div>

      <form method="GET" action="{{ route('offre.list') }}" id="filter-form">
        @if(request('q'))<input type="hidden" name="q" value="{{ request('q') }}">@endif

        @php
          $activeTypes    = (array) request('type', []);
          $activeMetiers  = (array) request('metier', []);
          $activeExp      = (array) request('niveau_experience', []);
          $activeEtude    = (array) request('niveau_etude', []);
          $activeLocs     = (array) request('localisation', []);
          $activeSecteurs = (array) request('secteur', []);
          $activeComps    = (array) request('competence', []);
        @endphp

        {{-- Type de contrat --}}
        <div class="ol-acc {{ count($activeTypes) ? 'open' : '' }}">
          <button type="button" class="ol-acc__head" onclick="toggleAcc(this)">
            <span>Type de contrat</span>
            @if(count($activeTypes))<span class="ol-acc__badge">{{ count($activeTypes) }}</span>@endif
            <svg class="ol-acc__chevron" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="ol-acc__inner">
            <div class="ol-acc__list ol-acc__list--short">
              @foreach($typeContratsList as $tc)
                <label class="ol-chk">
                  <input type="checkbox" name="type[]" value="{{ $tc->code }}" {{ in_array($tc->code, $activeTypes) ? 'checked' : '' }} onchange="this.form.submit()">
                  <span class="ol-chk__label">{{ $tc->libelle }}</span>
                </label>
              @endforeach
            </div>
          </div>
        </div>

        {{-- Métier --}}
        <div class="ol-acc {{ count($activeMetiers) ? 'open' : '' }}">
          <button type="button" class="ol-acc__head" onclick="toggleAcc(this)">
            <span>Métier</span>
            @if(count($activeMetiers))<span class="ol-acc__badge">{{ count($activeMetiers) }}</span>@endif
            <svg class="ol-acc__chevron" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="ol-acc__inner">
            <div class="ol-acc__search">
              <svg class="ol-acc__search-icon" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="m21 21-4.35-4.35"/></svg>
              <input type="text" placeholder="Rechercher un métier…" oninput="filterChk(this)">
              <button type="button" class="ol-acc__search-clear" onclick="clearSearch(this)" tabindex="-1">&times;</button>
            </div>
            <div class="ol-acc__list">
              @foreach($metiersList as $m)
                <label class="ol-chk">
                  <input type="checkbox" name="metier[]" value="{{ $m->nom }}" {{ in_array($m->nom, $activeMetiers) ? 'checked' : '' }} onchange="this.form.submit()">
                  <span class="ol-chk__label">{{ $m->nom }}</span>
                </label>
              @endforeach
            </div>
            <p class="ol-acc__empty">Aucun résultat</p>
          </div>
        </div>

        {{-- Niveau d'expérience --}}
        <div class="ol-acc {{ count($activeExp) ? 'open' : '' }}">
          <button type="button" class="ol-acc__head" onclick="toggleAcc(this)">
            <span>Expérience</span>
            @if(count($activeExp))<span class="ol-acc__badge">{{ count($activeExp) }}</span>@endif
            <svg class="ol-acc__chevron" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="ol-acc__inner">
            <div class="ol-acc__list ol-acc__list--short">
              @foreach($niveauxExpList as $ne)
                <label class="ol-chk">
                  <input type="checkbox" name="niveau_experience[]" value="{{ $ne->code }}" {{ in_array($ne->code, $activeExp) ? 'checked' : '' }} onchange="this.form.submit()">
                  <span class="ol-chk__label">{{ $ne->libelle }}</span>
                </label>
              @endforeach
            </div>
          </div>
        </div>

        {{-- Niveau d'études --}}
        <div class="ol-acc {{ count($activeEtude) ? 'open' : '' }}">
          <button type="button" class="ol-acc__head" onclick="toggleAcc(this)">
            <span>Niveau d'études</span>
            @if(count($activeEtude))<span class="ol-acc__badge">{{ count($activeEtude) }}</span>@endif
            <svg class="ol-acc__chevron" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="ol-acc__inner">
            <div class="ol-acc__list ol-acc__list--short">
              @foreach($niveauxEtudeList as $ne)
                <label class="ol-chk">
                  <input type="checkbox" name="niveau_etude[]" value="{{ $ne->code }}" {{ in_array($ne->code, $activeEtude) ? 'checked' : '' }} onchange="this.form.submit()">
                  <span class="ol-chk__label">{{ $ne->libelle }}</span>
                </label>
              @endforeach
            </div>
          </div>
        </div>

        {{-- Région / Ville --}}
        <div class="ol-acc {{ count($activeLocs) ? 'open' : '' }}">
          <button type="button" class="ol-acc__head" onclick="toggleAcc(this)">
            <span>Région / Ville</span>
            @if(count($activeLocs))<span class="ol-acc__badge">{{ count($activeLocs) }}</span>@endif
            <svg class="ol-acc__chevron" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="ol-acc__inner">
            <div class="ol-acc__search">
              <svg class="ol-acc__search-icon" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="m21 21-4.35-4.35"/></svg>
              <input type="text" placeholder="Rechercher une ville…" oninput="filterChk(this)">
              <button type="button" class="ol-acc__search-clear" onclick="clearSearch(this)" tabindex="-1">&times;</button>
            </div>
            <div class="ol-acc__list">
              @foreach($regionsList as $r)
                <label class="ol-chk">
                  <input type="checkbox" name="localisation[]" value="{{ $r->nom }}" {{ in_array($r->nom, $activeLocs) ? 'checked' : '' }} onchange="this.form.submit()">
                  <span class="ol-chk__label">{{ $r->nom }}</span>
                </label>
              @endforeach
            </div>
            <p class="ol-acc__empty">Aucun résultat</p>
          </div>
        </div>

        {{-- Secteur d'activité --}}
        <div class="ol-acc {{ count($activeSecteurs) ? 'open' : '' }}">
          <button type="button" class="ol-acc__head" onclick="toggleAcc(this)">
            <span>Secteur d'activité</span>
            @if(count($activeSecteurs))<span class="ol-acc__badge">{{ count($activeSecteurs) }}</span>@endif
            <svg class="ol-acc__chevron" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="ol-acc__inner">
            <div class="ol-acc__search">
              <svg class="ol-acc__search-icon" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="m21 21-4.35-4.35"/></svg>
              <input type="text" placeholder="Rechercher un secteur…" oninput="filterChk(this)">
              <button type="button" class="ol-acc__search-clear" onclick="clearSearch(this)" tabindex="-1">&times;</button>
            </div>
            <div class="ol-acc__list">
              @foreach($secteursList as $s)
                <label class="ol-chk">
                  <input type="checkbox" name="secteur[]" value="{{ $s->libelle }}" {{ in_array($s->libelle, $activeSecteurs) ? 'checked' : '' }} onchange="this.form.submit()">
                  <span class="ol-chk__label">{{ $s->libelle }}</span>
                </label>
              @endforeach
            </div>
            <p class="ol-acc__empty">Aucun résultat</p>
          </div>
        </div>

        {{-- Compétence --}}
        <div class="ol-acc {{ count($activeComps) ? 'open' : '' }}">
          <button type="button" class="ol-acc__head" onclick="toggleAcc(this)">
            <span>Compétence</span>
            @if(count($activeComps))<span class="ol-acc__badge">{{ count($activeComps) }}</span>@endif
            <svg class="ol-acc__chevron" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
          </button>
          <div class="ol-acc__inner">
            <div class="ol-acc__search">
              <svg class="ol-acc__search-icon" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="m21 21-4.35-4.35"/></svg>
              <input type="text" placeholder="Rechercher une compétence…" oninput="filterChk(this)">
              <button type="button" class="ol-acc__search-clear" onclick="clearSearch(this)" tabindex="-1">&times;</button>
            </div>
            <div class="ol-acc__list">
              @foreach($competences as $comp)
                <label class="ol-chk">
                  <input type="checkbox" name="competence[]" value="{{ $comp->slug }}" {{ in_array($comp->slug, $activeComps) ? 'checked' : '' }} onchange="this.form.submit()">
                  <span class="ol-chk__label">{{ $comp->nom }}</span>
                </label>
              @endforeach
            </div>
            <p class="ol-acc__empty">Aucun résultat</p>
          </div>
        </div>

        @if($hasFilters)
          <div class="ol-sidebar__actions">
            <a href="{{ route('offre.list', request()->only(['q'])) }}" class="ol-btn-reset">
              <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/></svg>
              Effacer les filtres
            </a>
          </div>
        @endif
      </form>
    </aside>

    {{-- Liste principale --}}
    <div>
      {{-- Barre résultats --}}
      <div class="ol-bar">
        <p class="ol-bar__count">
          <span>{{ $offres->total() }}</span> offre{{ $offres->total() !== 1 ? 's' : '' }}
          @if(request()->hasAny(['q','type','localisation','secteur','competence','metier','niveau_experience','niveau_etude']))
            trouvée{{ $offres->total() !== 1 ? 's' : '' }}
          @else
            disponible{{ $offres->total() !== 1 ? 's' : '' }}
          @endif
        </p>
        <div class="ol-bar__active-filters">
          @if(request('q'))
            <span class="ol-chip">{{ request('q') }}</span>
          @endif
          @foreach($activeTypes as $v)    @if($v)<span class="ol-chip">{{ $v }}</span>@endif @endforeach
          @foreach($activeMetiers as $v)  @if($v)<span class="ol-chip">{{ $v }}</span>@endif @endforeach
          @foreach($activeLocs as $v)     @if($v)<span class="ol-chip">{{ $v }}</span>@endif @endforeach
          @foreach($activeSecteurs as $v) @if($v)<span class="ol-chip">{{ $v }}</span>@endif @endforeach
          @foreach($activeExp as $v)      @if($v)<span class="ol-chip">{{ $v }}</span>@endif @endforeach
          @foreach($activeEtude as $v)    @if($v)<span class="ol-chip">{{ $v }}</span>@endif @endforeach
          @foreach($activeComps as $v)    @if($v)<span class="ol-chip">{{ $v }}</span>@endif @endforeach
        </div>
      </div>

      {{-- Offres --}}
      <div class="ol-list">
        @forelse($offres as $offre)
          @php $estExpiree = $offre->aExpire(); @endphp
          <{{ $estExpiree ? 'div' : 'a' }}
             @if(!$estExpiree) href="{{ route('offre.detail', $offre) }}" @endif
             class="ol-card" style="display:block;background:{{ $estExpiree ? '#e7ebf1' : '#fff' }};border:1.5px {{ $estExpiree ? 'dashed #c3ccd6' : 'solid #e8edf3' }};border-radius:16px;padding:20px 22px;text-decoration:none;transition:box-shadow .18s,transform .18s;box-shadow:{{ $estExpiree ? 'none' : '0 2px 8px rgba(4,44,83,.06)' }};{{ $estExpiree ? 'filter:grayscale(.45);cursor:default' : '' }}"
             @if(!$estExpiree)
             onmouseover="this.style.boxShadow='0 8px 28px rgba(4,44,83,.13)';this.style.transform='translateY(-2px)'"
             onmouseout="this.style.boxShadow='0 2px 8px rgba(4,44,83,.06)';this.style.transform='none'"
             @endif>

            {{-- En-tête : logo + titre + entreprise + date --}}
            <div style="display:flex;align-items:flex-start;gap:14px;margin-bottom:12px">
              {{-- Logo ou initiales --}}
              <div style="flex-shrink:0;width:52px;height:52px;border-radius:12px;background:linear-gradient(135deg,#e8f0fe,#dbeafe);border:1.5px solid #c7d7f8;display:flex;align-items:center;justify-content:center;overflow:hidden">
                @if($offre->logo)
                  <img src="{{ asset('storage/' . $offre->logo) }}" alt="{{ $offre->entreprise }}" style="width:100%;height:100%;object-fit:contain;padding:4px">
                @else
                  <span style="font-size:1.15rem;font-weight:800;color:#185FA5;letter-spacing:-1px">{{ strtoupper(substr($offre->entreprise, 0, 2)) }}</span>
                @endif
              </div>
              {{-- Titre + entreprise --}}
              <div style="flex:1;min-width:0">
                <div style="font-size:15px;font-weight:700;color:#042C53;line-height:1.3;margin-bottom:3px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $offre->titre }}</div>
                <div style="font-size:12.5px;color:#185FA5;font-weight:600">{{ $offre->entreprise }}</div>
              </div>
              {{-- Date --}}
              <div style="flex-shrink:0;font-size:11px;color:#94a3b8;white-space:nowrap">{{ $offre->created_at->diffForHumans() }}</div>
            </div>

            {{-- Description --}}
            <p style="font-size:13px;color:#64748b;line-height:1.6;margin:0 0 14px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">{{ Str::limit(strip_tags($offre->description), 120) }}</p>

            {{-- Badges + CTA --}}
            <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap">
              @if($offre->type)
                <span style="font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe">{{ $offre?->type?->libelle }}</span>
              @endif
              <span style="font-size:11px;font-weight:600;padding:3px 10px;border-radius:20px;background:#f1f5f9;color:#475569;display:inline-flex;align-items:center;gap:4px">
                <svg width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                {{ $offre->localisation }}
              </span>
              @if($offre->salaireFormate())
                <span style="font-size:11px;font-weight:600;padding:3px 10px;border-radius:20px;background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0">{{ $offre->salaireFormate() }}</span>
              @endif
              @if($estExpiree)
                <span style="font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;background:#fff;color:#64748b;border:1px solid #c3ccd6;margin-left:auto">
                  Expirée{{ $offre->date_limite ? ' ' . $offre->date_limite->diffForHumans() : '' }}
                </span>
              @else
                @if($offre->date_limite)
                  <span style="font-size:11px;color:#f59e0b;font-weight:600;margin-left:auto">Expire {{ $offre->date_limite->format('d/m') }}</span>
                @endif
                <span style="font-size:12px;font-weight:700;color:#185FA5;margin-left:{{ $offre->date_limite ? '4px' : 'auto' }};display:inline-flex;align-items:center;gap:3px">
                  Voir l'offre
                  <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </span>
              @endif
            </div>

          </{{ $estExpiree ? 'div' : 'a' }}>
        @empty
          @php
            $str = fn($v) => implode(', ', array_filter((array)$v));
            if (request('metier')) {
              $emptyTitle = 'Aucune offre de "' . $str(request('metier')) . '" pour l\'instant';
              $emptySub   = 'Ce métier est très demandé ! De nouvelles offres arrivent chaque jour. Déposez votre CV dès maintenant pour être contacté directement par les recruteurs.';
            } elseif (request('q')) {
              $emptyTitle = 'Aucune offre pour « ' . request('q') . ' »';
              $emptySub   = 'Cette opportunité n\'est pas encore publiée, mais de nouvelles offres arrivent chaque jour. Activez une alerte pour ne pas la manquer !';
            } elseif (request('type')) {
              $emptyTitle = 'Aucun ' . $str(request('type')) . ' disponible pour l\'instant';
              $emptySub   = 'De nouvelles offres de ce type sont publiées régulièrement. Revenez bientôt ou consultez toutes les offres disponibles.';
            } elseif (request('localisation')) {
              $emptyTitle = 'Aucune offre à ' . $str(request('localisation')) . ' pour l\'instant';
              $emptySub   = 'Cette ville est en pleine croissance économique ! Revenez bientôt ou explorez les villes voisines, de nombreuses offres sont ouvertes à la mobilité.';
            } elseif (request('secteur')) {
              $emptyTitle = 'Pas encore d\'offres en ' . $str(request('secteur'));
              $emptySub   = 'Ce secteur recrute activement au Bénin ! Soyez parmi les premiers à candidater dès la publication. Déposez votre CV pour être visible.';
            } elseif (request('niveau_experience')) {
              $emptyTitle = 'Aucune offre pour ce niveau d\'expérience pour l\'instant';
              $emptySub   = 'Votre profil mérite d\'être vu ! Déposez votre CV et laissez les recruteurs venir directement à vous.';
            } elseif (request('niveau_etude')) {
              $emptyTitle = 'Aucune offre correspondant à ce niveau d\'études pour l\'instant';
              $emptySub   = 'Des offres pour votre niveau d\'études arrivent régulièrement. Déposez votre CV pour être parmi les premiers contactés !';
            } elseif (request('competence')) {
              $emptyTitle = 'Aucune offre pour cette compétence pour l\'instant';
              $emptySub   = 'Cette compétence est précieuse et très recherchée par les recruteurs. Déposez votre CV et mettez-la en avant : les offres ne tarderont pas !';
            } else {
              $emptyTitle = 'De nouvelles offres arrivent chaque jour !';
              $emptySub   = 'Notre plateforme est en pleine croissance. Revenez bientôt ou déposez votre CV pour être visible auprès de tous les recruteurs.';
            }
          @endphp
          <div class="ol-empty">
            <p class="ol-empty__title">{{ $emptyTitle }}</p>
            <p class="ol-empty__sub">{{ $emptySub }}</p>
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

@section('scripts')
<script>
(function(){
  var btn = document.getElementById('ol-filter-toggle');
  var sidebar = document.getElementById('ol-sidebar');
  var label = document.getElementById('ol-toggle-label');
  if (!btn || !sidebar) return;
  btn.addEventListener('click', function(){
    var open = sidebar.classList.toggle('open');
    btn.setAttribute('aria-expanded', open);
    label.textContent = open ? 'Masquer les filtres' : 'Afficher les filtres';
  });
})();

function toggleAcc(btn) {
  btn.closest('.ol-acc').classList.toggle('open');
}

function filterChk(input) {
  var q = input.value.toLowerCase();
  var inner = input.closest('.ol-acc__inner');
  var list  = inner.querySelector('.ol-acc__list');
  var empty = inner.querySelector('.ol-acc__empty');
  var clear = inner.querySelector('.ol-acc__search-clear');
  var visible = 0;

  list.querySelectorAll('.ol-chk').forEach(function(chk) {
    var match = chk.querySelector('.ol-chk__label').textContent.toLowerCase().includes(q);
    chk.style.display = match ? '' : 'none';
    if (match) visible++;
  });

  if (clear) clear.style.display = q ? 'flex' : 'none';
  if (empty) empty.style.display = (q && visible === 0) ? 'block' : 'none';
}

function clearSearch(btn) {
  var inner = btn.closest('.ol-acc__inner');
  var input = inner.querySelector('input[type=text]');
  input.value = '';
  filterChk(input);
  input.focus();
}
</script>
@endsection
