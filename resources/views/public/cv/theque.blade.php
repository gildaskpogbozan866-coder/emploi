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
    @if(!auth()->check() || auth()->user()->hasRole(\App\Enums\Role::CANDIDAT))
      <a href="{{ auth()->check() && auth()->user()->hasRole(\App\Enums\Role::CANDIDAT) ? route('cv.public.depot') : route('auth.inscription').'?role=candidat' }}"  class="cvt-subnav__link">Déposer un CV</a>
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
                       onchange="cvtFilter()">
                <span>{{ $p }}</span>
              </label>
            @endforeach
            @if(request('pays'))
              <a href="{{ route('cv.public.theque', request('q') ? ['q' => request('q')] : []) }}" class="cvt-filter-reset"><svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" style="display:inline-block;vertical-align:-1px"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg> Effacer</a>
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
            <select name="secteur" onchange="cvtFilter()" style="width:100%;padding:7px 10px;border:1.5px solid #e2e8f0;border-radius:7px;font-family:inherit;font-size:13px;color:#042C53;background:#fff;outline:none">
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
                       onchange="cvtFilter()">
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
            <select name="metier" onchange="cvtFilter()" style="width:100%;padding:7px 10px;border:1.5px solid #e2e8f0;border-radius:7px;font-family:inherit;font-size:13px;color:#042C53;background:#fff;outline:none">
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
                       onchange="cvtFilter()">
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
                       onchange="cvtFilter()">
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
                       onchange="cvtFilter()">
                <span>{{ $ne->libelle }}</span>
              </label>
            @endforeach
            @if(request('niveau_experience'))
              <a href="{{ route('cv.public.theque', array_filter(request()->only(['q','pays','disponibilite','secteur','langue','metier','niveau_etude','type_contrat']))) }}" class="cvt-filter-reset"><svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" style="display:inline-block;vertical-align:-1px"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg> Effacer</a>
            @endif
          </div>
        </div>

        <div class="cvt-filter-actions">
          @if(request()->hasAny(['q','pays','secteur','langue','metier','niveau_etude','type_contrat','niveau_experience']))
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
        <span class="cvt-count-bar__title" id="cvt-count">
          {{ $cvs->total() }} profil{{ $cvs->total() > 1 ? 's' : '' }}
        </span>
        <div class="cvt-count-bar__search">
          <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35"/></svg>
          <input type="text" id="cvt-q-top" value="{{ request('q') }}" placeholder="Rechercher un profil, compétence…">
        </div>
      </div>

      {{-- Spinner chargement --}}
      <div id="cvt-loading" style="display:none;text-align:center;padding:48px 0">
        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#185FA5" stroke-width="2.5" style="animation:cvt-spin 0.8s linear infinite">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 2v4m0 12v4M4.93 4.93l2.83 2.83m8.48 8.48 2.83 2.83M2 12h4m12 0h4M4.93 19.07l2.83-2.83m8.48-8.48 2.83-2.83"/>
        </svg>
        <p style="margin-top:10px;font-size:13.5px;color:#64748b">Chargement des profils…</p>
      </div>

      {{-- Résultats (initial server-side, puis AJAX) --}}
      <div id="cvt-results">
        @include('public.cv._theque_liste', ['cvs' => $cvs])
      </div>

    </div>
  </div>
</div>

@endsection

@section('scripts')
<style>
@keyframes cvt-spin { to { transform: rotate(360deg); } }
</style>
<script>
/* ── Filtres accordion ───────────────────────────── */
document.querySelectorAll('.cvt-filter-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    const body = document.getElementById(btn.dataset.target);
    if (!body) return;
    const open = body.classList.toggle('open');
    const icon = btn.querySelector('.cvt-filter-btn__icon');
    if (icon) icon.style.transform = open ? 'rotate(45deg)' : '';
  });
});

/* ── Filtres AJAX dynamiques ─────────────────────── */
const form      = document.getElementById('filterForm');
const results   = document.getElementById('cvt-results');
const loading   = document.getElementById('cvt-loading');
const countEl   = document.getElementById('cvt-count');
const heroSub   = document.querySelector('.page-hero__subtitle');

/* Synchronise le champ de recherche du header → sidebar */
const qTop = document.getElementById('cvt-q-top');
const qSide = form ? form.querySelector('input[name="q"]') : null;
if (qTop && qSide) {
  qTop.addEventListener('input', () => { qSide.value = qTop.value; cvtFilter(); });
}
if (qSide && qTop) {
  qSide.addEventListener('input', () => { qTop.value = qSide.value; });
}

let debounceTimer = null;

function cvtFilter() {
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(doFetch, 280);
}

function doFetch() {
  const params = new URLSearchParams(new FormData(form));

  // Mettre à jour l'URL sans recharger la page
  const newUrl = '{{ route("cv.public.theque") }}?' + params.toString();
  history.replaceState(null, '', newUrl);

  results.style.opacity = '0.4';
  loading.style.display = 'block';

  fetch('{{ route("cv.public.theque") }}?' + params.toString(), {
    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
  })
  .then(r => r.json())
  .then(data => {
    results.innerHTML = data.html;
    results.style.opacity = '1';
    loading.style.display = 'none';

    // Mettre à jour compteur
    const n = data.total;
    if (countEl) countEl.textContent = n + ' profil' + (n > 1 ? 's' : '');
    if (heroSub) heroSub.textContent = n + ' CV' + (n > 1 ? 's' : '') + ' de candidats disponibles, mis à jour en continu';

    // Réattacher la pagination (liens classiques)
    results.querySelectorAll('.cvt-pagination a').forEach(a => {
      a.addEventListener('click', e => {
        e.preventDefault();
        const url = new URL(a.href);
        url.searchParams.forEach((v, k) => {
          const el = form.querySelector('[name="' + k + '"]');
          if (el) el.value = v;
        });
        const page = url.searchParams.get('page');
        if (page) fetchPage(page);
      });
    });
  })
  .catch(() => { results.style.opacity = '1'; loading.style.display = 'none'; });
}

function fetchPage(page) {
  const params = new URLSearchParams(new FormData(form));
  params.set('page', page);
  history.replaceState(null, '', '{{ route("cv.public.theque") }}?' + params.toString());
  results.style.opacity = '0.4';
  loading.style.display = 'block';
  fetch('{{ route("cv.public.theque") }}?' + params.toString(), {
    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
  })
  .then(r => r.json())
  .then(data => {
    results.innerHTML = data.html;
    results.style.opacity = '1';
    loading.style.display = 'none';
    window.scrollTo({ top: results.offsetTop - 80, behavior: 'smooth' });
  })
  .catch(() => { results.style.opacity = '1'; loading.style.display = 'none'; });
}

/* ── Champ recherche sidebar déclenche AJAX ──────── */
if (qSide) qSide.addEventListener('input', cvtFilter);

/* ── Selects déclenchent immédiatement ──────────── */
if (form) form.querySelectorAll('select').forEach(sel => sel.addEventListener('change', cvtFilter));
</script>
@endsection
