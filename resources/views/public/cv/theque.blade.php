@extends('layouts.app')
@section('title', 'CVthèque Bénin | Recrutez des talents béninois | Emploi Bouge Bénin')
@section('description', 'Accédez à la CVthèque de talents au Bénin. Recruteurs : consultez des CV vérifiés de candidats qualifiés à Cotonou et dans tout le Bénin. Déposez votre CV gratuitement.')

@section('jsonld')
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {"@type":"ListItem","position":1,"name":"Accueil","item":"{{ route('home') }}"},
    {"@type":"ListItem","position":2,"name":"CVthèque","item":"{{ route('cv.public.theque') }}"}
  ]
}
</script>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/cv/cvtheque.css') }}">
@endsection

@section('content')

{{-- Page Hero --}}
<section class="section page-hero">
  <div class="container page-hero__inner">
    <span class="badge badge--blue">CVthèque</span>
    <h1 class="page-hero__title">Trouvez le bon profil</h1>
    <p class="page-hero__subtitle">{{ $cvs->total() }} CV{{ $cvs->total() > 1 ? 's' : '' }} de candidats disponibles, mis à jour en continu</p>
  </div>
</section>

{{-- Sous-nav --}}
<div class="cvt-subnav">
  <div class="cvt-subnav__inner">
    <a href="{{ route('cv.public.theque') }}" class="cvt-subnav__link active">Trouver des CV</a>
    <a href="{{ route('cv.public.tarif') }}"  class="cvt-subnav__link">Packs crédits</a>
    @if(!auth()->check() || auth()->user()->hasRole('candidat'))
      <a href="{{ route('cv.public.depot') }}"  class="cvt-subnav__link">Déposer un CV</a>
    @endif
  </div>
</div>

<div class="cvt-page">
  <div class="cvt-layout">

    {{-- SIDEBAR --}}
    <aside class="cvt-sidebar">
      <form method="GET" action="{{ route('cv.public.theque') }}" id="filterForm">

        <div class="cvt-filter-search">
          <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35"/></svg>
          <input type="text" name="q" value="{{ request('q') }}" placeholder="Poste, compétence…" autocomplete="off">
        </div>

        <div class="cvt-filter-item">
          <button type="button" class="cvt-filter-btn" data-target="f-pays">
            <span class="cvt-filter-btn__label">Pays</span>
            <span class="cvt-filter-btn__icon">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            </span>
          </button>
          <div class="cvt-filter-body {{ request('pays') ? 'open' : '' }}" id="f-pays">
            @foreach($paysList->reject(fn($p) => $p === 'Autre') as $p)
              <label class="cvt-filter-opt">
                <input type="radio" name="pays" value="{{ $p }}" {{ request('pays') === $p ? 'checked' : '' }}
                       onchange="this.form.submit()">
                <span>{{ $p }}</span>
              </label>
            @endforeach
            @if(request('pays'))
              <a href="{{ route('cv.public.theque', request('q') ? ['q' => request('q')] : []) }}" class="cvt-filter-reset"><svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" style="display:inline-block;vertical-align:-1px"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg> Effacer</a>
            @endif
          </div>
        </div>

        {{-- Disponibilité --}}
        <div class="cvt-filter-item">
          <button type="button" class="cvt-filter-btn" data-target="f-dispo">
            <span class="cvt-filter-btn__label">Disponibilité</span>
            <span class="cvt-filter-btn__icon">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            </span>
          </button>
          <div class="cvt-filter-body {{ request('disponibilite') ? 'open' : '' }}" id="f-dispo">
            @foreach($disponibilitesList as $d)
              <label class="cvt-filter-opt">
                <input type="radio" name="disponibilite" value="{{ $d->code }}"
                       {{ request('disponibilite') === $d->code ? 'checked' : '' }}
                       onchange="this.form.submit()">
                <span style="display:inline-flex;align-items:center;gap:6px">
                  <span style="width:8px;height:8px;border-radius:50%;background:{{ $d->couleur }};flex-shrink:0"></span>
                  {{ $d->libelle }}
                </span>
              </label>
            @endforeach
            @if(request('disponibilite'))
              <a href="{{ route('cv.public.theque', array_filter(request()->only(['q','pays','secteur','langue']))) }}" class="cvt-filter-reset"><svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" style="display:inline-block;vertical-align:-1px"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg> Effacer</a>
            @endif
          </div>
        </div>

        {{-- Secteur d'activité --}}
        <div class="cvt-filter-item">
          <button type="button" class="cvt-filter-btn" data-target="f-secteur">
            <span class="cvt-filter-btn__label">Secteur d'activité</span>
            <span class="cvt-filter-btn__icon">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            </span>
          </button>
          <div class="cvt-filter-body {{ request('secteur') ? 'open' : '' }}" id="f-secteur">
            <select name="secteur" onchange="this.form.submit()" style="width:100%;padding:7px 10px;border:1.5px solid #e2e8f0;border-radius:7px;font-family:inherit;font-size:13px;color:#042C53;background:#fff;outline:none">
              <option value="">Tous les secteurs</option>
              @foreach($secteursList as $s)
                <option value="{{ $s->libelle }}" {{ request('secteur') === $s->libelle ? 'selected' : '' }}>{{ $s->libelle }}</option>
              @endforeach
            </select>
          </div>
        </div>

        {{-- Langue --}}
        <div class="cvt-filter-item">
          <button type="button" class="cvt-filter-btn" data-target="f-langue">
            <span class="cvt-filter-btn__label">Langue</span>
            <span class="cvt-filter-btn__icon">
              <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            </span>
          </button>
          <div class="cvt-filter-body {{ request('langue') ? 'open' : '' }}" id="f-langue">
            @foreach($languesList as $l)
              <label class="cvt-filter-opt">
                <input type="radio" name="langue" value="{{ $l->nom }}"
                       {{ request('langue') === $l->nom ? 'checked' : '' }}
                       onchange="this.form.submit()">
                <span>{{ $l->nom }}</span>
              </label>
            @endforeach
            @if(request('langue'))
              <a href="{{ route('cv.public.theque', array_filter(request()->only(['q','pays','disponibilite','secteur']))) }}" class="cvt-filter-reset"><svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" style="display:inline-block;vertical-align:-1px"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg> Effacer</a>
            @endif
          </div>
        </div>

        {{-- Métier --}}
        <div class="cvt-filter-item">
          <button type="button" class="cvt-filter-btn" data-target="f-metier">
            <span class="cvt-filter-btn__label">Métier</span>
            <span class="cvt-filter-btn__icon"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></span>
          </button>
          <div class="cvt-filter-body {{ request('metier') ? 'open' : '' }}" id="f-metier">
            <select name="metier" onchange="this.form.submit()" style="width:100%;padding:7px 10px;border:1.5px solid #e2e8f0;border-radius:7px;font-family:inherit;font-size:13px;color:#042C53;background:#fff;outline:none">
              <option value="">Tous les métiers</option>
              @foreach($metiersList as $m)
                <option value="{{ $m->nom }}" {{ request('metier') === $m->nom ? 'selected' : '' }}>{{ $m->nom }}</option>
              @endforeach
            </select>
          </div>
        </div>

        {{-- Niveau d'études --}}
        <div class="cvt-filter-item">
          <button type="button" class="cvt-filter-btn" data-target="f-etude">
            <span class="cvt-filter-btn__label">Niveau d'études</span>
            <span class="cvt-filter-btn__icon"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></span>
          </button>
          <div class="cvt-filter-body {{ request('niveau_etude') ? 'open' : '' }}" id="f-etude">
            @foreach($niveauxEtudeList as $ne)
              <label class="cvt-filter-opt">
                <input type="radio" name="niveau_etude" value="{{ $ne->code }}"
                       {{ request('niveau_etude') === $ne->code ? 'checked' : '' }}
                       onchange="this.form.submit()">
                <span>{{ $ne->libelle }}</span>
              </label>
            @endforeach
            @if(request('niveau_etude'))
              <a href="{{ route('cv.public.theque', array_filter(request()->only(['q','pays','disponibilite','secteur','langue','metier','type_contrat','niveau_experience']))) }}" class="cvt-filter-reset"><svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" style="display:inline-block;vertical-align:-1px"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg> Effacer</a>
            @endif
          </div>
        </div>

        {{-- Type de contrat recherché --}}
        <div class="cvt-filter-item">
          <button type="button" class="cvt-filter-btn" data-target="f-contrat">
            <span class="cvt-filter-btn__label">Type de contrat</span>
            <span class="cvt-filter-btn__icon"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></span>
          </button>
          <div class="cvt-filter-body {{ request('type_contrat') ? 'open' : '' }}" id="f-contrat">
            @foreach($typeContratsList as $tc)
              <label class="cvt-filter-opt">
                <input type="radio" name="type_contrat" value="{{ $tc->code }}"
                       {{ request('type_contrat') === $tc->code ? 'checked' : '' }}
                       onchange="this.form.submit()">
                <span>{{ $tc->libelle }}</span>
              </label>
            @endforeach
            @if(request('type_contrat'))
              <a href="{{ route('cv.public.theque', array_filter(request()->only(['q','pays','disponibilite','secteur','langue','metier','niveau_etude','niveau_experience']))) }}" class="cvt-filter-reset"><svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" style="display:inline-block;vertical-align:-1px"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg> Effacer</a>
            @endif
          </div>
        </div>

        {{-- Niveau d'expérience --}}
        <div class="cvt-filter-item">
          <button type="button" class="cvt-filter-btn" data-target="f-exp">
            <span class="cvt-filter-btn__label">Expérience</span>
            <span class="cvt-filter-btn__icon"><svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg></span>
          </button>
          <div class="cvt-filter-body {{ request('niveau_experience') ? 'open' : '' }}" id="f-exp">
            @foreach($niveauxExpList as $ne)
              <label class="cvt-filter-opt">
                <input type="radio" name="niveau_experience" value="{{ $ne->code }}"
                       {{ request('niveau_experience') === $ne->code ? 'checked' : '' }}
                       onchange="this.form.submit()">
                <span>{{ $ne->libelle }}</span>
              </label>
            @endforeach
            @if(request('niveau_experience'))
              <a href="{{ route('cv.public.theque', array_filter(request()->only(['q','pays','disponibilite','secteur','langue','metier','niveau_etude','type_contrat']))) }}" class="cvt-filter-reset"><svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" style="display:inline-block;vertical-align:-1px"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg> Effacer</a>
            @endif
          </div>
        </div>

        <div class="cvt-filter-actions">
          <button type="submit" class="cvt-filter-apply">Rechercher</button>
          @if(request()->hasAny(['q','pays','disponibilite','secteur','langue','metier','niveau_etude','type_contrat','niveau_experience']))
            <a href="{{ route('cv.public.theque') }}" class="cvt-filter-clear">Tout effacer</a>
          @endif
        </div>

      </form>
    </aside>

    {{-- CONTENU PRINCIPAL --}}
    <div class="cvt-main">

      <div class="cvt-info-bar">
        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="color:#185FA5;flex-shrink:0;"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <span>Les coordonnées complètes sont accessibles avec un <a href="{{ route('cv.public.tarif') }}">pack CVthèque</a>.</span>
      </div>

      <div class="cvt-count-bar">
        <span class="cvt-count-bar__title">
          {{ $cvs->total() }} profil{{ $cvs->total() > 1 ? 's' : '' }}
          @if(request('q')) · <em>"{{ request('q') }}"</em>@endif
          @if(request('pays')) · <em>{{ request('pays') }}</em>@endif
        </span>
        <form method="GET" action="{{ route('cv.public.theque') }}" class="cvt-count-bar__search">
          <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35"/></svg>
          <input type="text" name="q" value="{{ request('q') }}" placeholder="Rechercher un profil, compétence…">
          @if(request('pays'))<input type="hidden" name="pays" value="{{ request('pays') }}">@endif
        </form>
      </div>

      {{-- Cards CV --}}
      <div class="cvt-list">
      @forelse($cvs as $cv)
        <div class="cvt-card">
          <div class="cvt-card__inner">
            <div class="cvt-card__body">

              {{-- Photo / Avatar --}}
              <div class="cvt-card__photo">
                @if($cv->photo)
                  <img src="{{ asset('storage/' . $cv->photo) }}" alt="" style="width:100%;height:100%;object-fit:cover;">
                @else
                  <span style="font-family:var(--font-body);font-size:1.6rem;font-weight:700;color:#185FA5;">
                    {{ mb_strtoupper(mb_substr($cv->candidat?->prenom ?? '?', 0, 1)) }}
                  </span>
                @endif
              </div>

              {{-- Infos --}}
              <div class="cvt-card__info">
                <div class="cvt-card__row">
                  <span class="cvt-card__label">Poste :</span>
                  <span class="cvt-card__val">{{ $cv->titre_poste }}</span>
                  @if($cv->plan === 'premium')
                    <span class="cvt-card__premium-badge">Premium</span>
                  @endif
                </div>

                @if($cv->pays)
                <div class="cvt-card__row">
                  <span class="cvt-card__label">Pays :</span>
                  <span class="cvt-card__val">{{ $cv->pays }}</span>
                </div>
                @endif

                @if($cv->langues)
                <div class="cvt-card__row">
                  <span class="cvt-card__label">Langues :</span>
                  <span class="cvt-card__val">{{ $cv->langues }}</span>
                </div>
                @endif

                @if($cv->competences)
                <div class="cvt-card__row">
                  <span class="cvt-card__label">Compétences :</span>
                  <span class="cvt-card__val">{{ Str::limit($cv->competences, 90) }}</span>
                </div>
                @endif

                @if($cv->experience)
                <div class="cvt-card__row">
                  <span class="cvt-card__label">Expérience :</span>
                  <span class="cvt-card__val">{{ Str::limit($cv->experience, 80) }}</span>
                </div>
                @endif
              </div>

            </div>

            <div class="cvt-card__footer">
              <a href="{{ route('cv.public.detail', $cv) }}" class="cvt-card__btn">
                Voir ce CV
                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
              </a>
            </div>
          </div>
        </div>
      @empty
        @php
          if (request('metier')) {
            $emptyTitle = 'Aucun profil "' . request('metier') . '" pour l\'instant';
            $emptySub   = 'Ce métier est très recherché ! De nouveaux candidats s\'inscrivent chaque semaine. Revenez bientôt ou élargissez votre recherche.';
          } elseif (request('q')) {
            $emptyTitle = 'Aucun résultat pour « ' . request('q') . ' »';
            $emptySub   = 'Ce poste ou cette compétence n\'est pas encore très représenté(e), mais notre base grandit chaque jour. Essayez un mot-clé proche !';
          } elseif (request('secteur')) {
            $emptyTitle = 'Pas encore de profils en ' . request('secteur');
            $emptySub   = 'Ce secteur est en plein essor sur Emploi Bouge Bénin ! Des candidats de ce domaine rejoignent la plateforme régulièrement. Revenez bientôt.';
          } elseif (request('pays')) {
            $emptyTitle = 'Aucun candidat au ' . request('pays') . ' pour l\'instant';
            $emptySub   = 'Notre communauté s\'étend rapidement dans ce pays. En attendant, explorez les profils d\'autres pays ou invitez des talents à s\'inscrire.';
          } elseif (request('disponibilite')) {
            $libDispo = $disponibilitesList->firstWhere('code', request('disponibilite'));
            $emptyTitle = 'Aucun candidat "' . ($libDispo?->libelle ?? request('disponibilite')) . '" en ce moment';
            $emptySub   = 'Les disponibilités changent souvent ! Revenez dans quelques jours ou consultez les profils avec d\'autres statuts de disponibilité.';
          } elseif (request('langue')) {
            $emptyTitle = 'Aucun profil parlant ' . request('langue') . ' trouvé';
            $emptySub   = 'Rare et précieux ! Ce profil linguistique rejoindra bientôt la plateforme. En attendant, explorez les profils multilingues disponibles.';
          } elseif (request('niveau_etude')) {
            $libEtude = $niveauxEtudeList->firstWhere('code', request('niveau_etude'));
            $emptyTitle = 'Aucun profil avec ce niveau d\'études pour l\'instant';
            $emptySub   = 'Les candidats de niveau ' . ($libEtude?->libelle ?? request('niveau_etude')) . ' sont très demandés ! Modifiez le filtre pour découvrir plus de profils, ou revenez bientôt.';
          } elseif (request('type_contrat')) {
            $libContrat = $typeContratsList->firstWhere('code', request('type_contrat'));
            $emptyTitle = 'Aucun candidat cherchant un ' . ($libContrat?->libelle ?? request('type_contrat'));
            $emptySub   = 'De nombreux candidats sont ouverts à ce type de contrat. Déposez votre offre et les bons profils viendront à vous !';
          } elseif (request('niveau_experience')) {
            $libExp = $niveauxExpList->firstWhere('code', request('niveau_experience'));
            $emptyTitle = 'Aucun profil avec ce niveau d\'expérience pour l\'instant';
            $emptySub   = 'Les talents de niveau ' . ($libExp?->libelle ?? request('niveau_experience')) . ' sont très recherchés. Publiez une offre et attirez directement les meilleurs profils !';
          } else {
            $emptyTitle = 'La CVthèque se remplit chaque jour !';
            $emptySub   = 'Soyez parmi les premiers recruteurs à découvrir les nouveaux talents. Revenez bientôt ou déposez une offre pour attirer les candidats.';
          }
        @endphp
        <div class="cvt-empty">
          <svg width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="#cbd5e1" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
          <p class="cvt-empty__title">{{ $emptyTitle }}</p>
          <p class="cvt-empty__sub">{{ $emptySub }}</p>
          <a href="{{ route('cv.public.theque') }}" class="cvt-card__btn" style="display:inline-flex;margin-top:16px;">Voir tous les profils</a>
        </div>
      @endforelse
      </div>

      {{-- Pagination --}}
      @if($cvs->hasPages())
        <div class="cvt-pagination">
          {{ $cvs->withQueryString()->links() }}
        </div>
      @endif

    </div>
  </div>
</div>

@endsection

@section('scripts')
<script>
document.querySelectorAll('.cvt-filter-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    const body = document.getElementById(btn.dataset.target);
    if (!body) return;
    const open = body.classList.toggle('open');
    const icon = btn.querySelector('.cvt-filter-btn__icon');
    if (icon) icon.style.transform = open ? 'rotate(45deg)' : '';
  });
});
</script>
@endsection
