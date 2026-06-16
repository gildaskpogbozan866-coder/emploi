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
@endsection

@section('content')

{{-- Hero recherche --}}
<div class="ol-hero">
  <h1 class="ol-hero__title">Offres d'emploi au Bénin</h1>
  <p class="ol-hero__sub">{{ $offres->total() }} offre{{ $offres->total() !== 1 ? 's' : '' }} disponible{{ $offres->total() !== 1 ? 's' : '' }}, mise à jour en continu</p>
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
  @foreach($typeContratsList as $tc)
    @php
      $tabLabel = Str::before(Str::before($tc->libelle, ' — '), ' / ');
    @endphp
    <a href="{{ route('offre.list', array_merge(request()->except(['type','page']), ['type' => $tc->code])) }}"
       class="ol-tab {{ request('type') === $tc->code ? 'active' : '' }}">{{ $tabLabel }}</a>
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

        
        {{-- Métier --}}
        <div class="ol-fgroup">
          <label for="f-metier">Métier</label>
          <select id="f-metier" name="metier" class="ol-fselect" onchange="this.form.submit()">
            <option value="">Tous les métiers</option>
            @foreach($metiersList as $m)
              <option value="{{ $m->nom }}" {{ request('metier') === $m->nom ? 'selected' : '' }}>{{ $m->nom }}</option>
            @endforeach
          </select>
        </div>

        {{-- Niveau d'expérience --}}
        <div class="ol-fgroup">
          <label for="f-exp">Niveau d'expérience</label>
          <select id="f-exp" name="niveau_experience" class="ol-fselect" onchange="this.form.submit()">
            <option value="">Toute expérience</option>
            @foreach($niveauxExpList as $ne)
              <option value="{{ $ne->code }}" {{ request('niveau_experience') === $ne->code ? 'selected' : '' }}>{{ $ne->libelle }}</option>
            @endforeach
          </select>
        </div>

        {{-- Niveau d'études --}}
        <div class="ol-fgroup">
          <label for="f-etude">Niveau d'études</label>
          <select id="f-etude" name="niveau_etude" class="ol-fselect" onchange="this.form.submit()">
            <option value="">Tous niveaux</option>
            @foreach($niveauxEtudeList as $ne)
              <option value="{{ $ne->code }}" {{ request('niveau_etude') === $ne->code ? 'selected' : '' }}>{{ $ne->libelle }}</option>
            @endforeach
          </select>
        </div>

        {{-- Région / Localisation --}}
        <div class="ol-fgroup">
          <label for="f-loc">Région / Ville</label>
          <select id="f-loc" name="localisation" class="ol-fselect" onchange="this.form.submit()">
            <option value="">Toutes les régions</option>
            @foreach($regionsList as $r)
              <option value="{{ $r->nom }}" {{ request('localisation') === $r->nom ? 'selected' : '' }}>{{ $r->nom }}</option>
            @endforeach
          </select>
        </div>

        {{-- Secteur d'activité --}}
        <div class="ol-fgroup">
          <label for="f-secteur">Secteur d'activité</label>
          <select id="f-secteur" name="secteur" class="ol-fselect" onchange="this.form.submit()">
            <option value="">Tous les secteurs</option>
            @foreach($secteursList as $s)
              <option value="{{ $s->libelle }}" {{ request('secteur') === $s->libelle ? 'selected' : '' }}>{{ $s->libelle }}</option>
            @endforeach
          </select>
        </div>

        {{-- Compétence --}}
        <div class="ol-fgroup">
          <label for="f-comp">Compétence</label>
          <select id="f-comp" name="competence" class="ol-fselect" onchange="this.form.submit()">
            <option value="">Toutes les compétences</option>
            @foreach($competences as $comp)
              <option value="{{ $comp->slug }}" {{ request('competence') === $comp->slug ? 'selected' : '' }}>
                {{ $comp->nom }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="ol-sidebar__actions">
          @if(request()->hasAny(['localisation','secteur','competence','metier','niveau_experience','niveau_etude']))
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
          @if(request('type'))
            <span class="ol-chip">{{ request('type') }}</span>
          @endif
          @if(request('localisation'))
            <span class="ol-chip">{{ request('localisation') }}</span>
          @endif
          @if(request('secteur'))
            <span class="ol-chip">{{ request('secteur') }}</span>
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
                  <span class="ol-badge ol-badge--secteur">{{ is_array($offre->secteur) ? implode(', ', $offre->secteur) : $offre->secteur }}</span>
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
          @php
            if (request('metier')) {
              $emptyTitle = 'Aucune offre de "' . request('metier') . '" pour l\'instant';
              $emptySub   = 'Ce métier est très demandé ! De nouvelles offres arrivent chaque jour. Déposez votre CV dès maintenant pour être contacté directement par les recruteurs.';
            } elseif (request('q')) {
              $emptyTitle = 'Aucune offre pour « ' . request('q') . ' »';
              $emptySub   = 'Cette opportunité n\'est pas encore publiée, mais de nouvelles offres arrivent chaque jour. Activez une alerte pour ne pas la manquer !';
            } elseif (request('type')) {
              $emptyTitle = 'Aucun ' . request('type') . ' disponible pour l\'instant';
              $emptySub   = 'De nouvelles offres de ce type sont publiées régulièrement. Revenez bientôt ou consultez toutes les offres disponibles.';
            } elseif (request('localisation')) {
              $emptyTitle = 'Aucune offre à ' . request('localisation') . ' pour l\'instant';
              $emptySub   = 'Cette ville est en pleine croissance économique ! Revenez bientôt ou explorez les villes voisines — de nombreuses offres sont ouvertes à la mobilité.';
            } elseif (request('secteur')) {
              $emptyTitle = 'Pas encore d\'offres en ' . request('secteur');
              $emptySub   = 'Ce secteur recrute activement au Bénin ! Soyez parmi les premiers à candidater dès la publication. Déposez votre CV pour être visible.';
            } elseif (request('niveau_experience')) {
              $libExp = $niveauxExpList->firstWhere('code', request('niveau_experience'));
              $emptyTitle = 'Aucune offre pour ce niveau d\'expérience pour l\'instant';
              $emptySub   = 'Votre profil de niveau ' . ($libExp?->libelle ?? '') . ' mérite d\'être vu ! Déposez votre CV et laissez les recruteurs venir directement à vous.';
            } elseif (request('niveau_etude')) {
              $libEtude = $niveauxEtudeList->firstWhere('code', request('niveau_etude'));
              $emptyTitle = 'Aucune offre correspondant à ce niveau d\'études pour l\'instant';
              $emptySub   = 'Des offres pour le niveau ' . ($libEtude?->libelle ?? '') . ' arrivent régulièrement. Déposez votre CV pour être parmi les premiers contactés !';
            } elseif (request('competence')) {
              $emptyTitle = 'Aucune offre pour cette compétence pour l\'instant';
              $emptySub   = 'Cette compétence est précieuse et très recherchée par les recruteurs. Déposez votre CV et mettez-la en avant — les offres ne tarderont pas !';
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
