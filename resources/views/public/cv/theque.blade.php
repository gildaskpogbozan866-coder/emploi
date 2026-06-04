@extends('layouts.app')
@section('title', 'CVthèque — Emploi Bouge Bénin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/cv/cvtheque.css') }}">
@endsection

@section('content')

{{-- Sous-nav --}}
<div class="cvt-subnav">
  <div class="cvt-subnav__inner">
    <a href="{{ route('cv.public.theque') }}" class="cvt-subnav__link active">Trouver des CV</a>
    <a href="{{ route('cv.public.tarif') }}"  class="cvt-subnav__link">Packs CV</a>
    <a href="{{ route('cv.public.depot') }}"  class="cvt-subnav__link">Déposer un CV</a>
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
            @foreach(['Bénin','Côte d\'Ivoire','Sénégal','Cameroun','Togo','Mali','Burkina Faso'] as $p)
              <label class="cvt-filter-opt">
                <input type="radio" name="pays" value="{{ $p }}" {{ request('pays') === $p ? 'checked' : '' }}
                       onchange="this.form.submit()">
                <span>{{ $p }}</span>
              </label>
            @endforeach
            @if(request('pays'))
              <a href="{{ route('cv.public.theque', request('q') ? ['q' => request('q')] : []) }}" class="cvt-filter-reset">✕ Effacer</a>
            @endif
          </div>
        </div>

        <div class="cvt-filter-actions">
          <button type="submit" class="cvt-filter-apply">Rechercher</button>
          @if(request()->hasAny(['q','pays']))
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

                @if($cv->pays || $cv->ville)
                <div class="cvt-card__row">
                  <span class="cvt-card__label">Localisation :</span>
                  <span class="cvt-card__val">{{ implode(', ', array_filter([$cv->ville, $cv->pays])) }}</span>
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
              @auth
                @if(auth()->user()->hasPermissionTo('view-cvtheque'))
                  <a href="#" class="cvt-card__btn">
                    Voir le profil complet
                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                  </a>
                @else
                  <a href="{{ route('cv.public.tarif') }}" class="cvt-card__btn cvt-card__btn--outline">
                    Débloquer ce profil
                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                  </a>
                @endif
              @else
                <a href="{{ route('auth.connexion') }}" class="cvt-card__btn cvt-card__btn--outline">
                  Se connecter pour voir
                  <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
              @endauth
            </div>
          </div>
        </div>
      @empty
        <div class="cvt-empty">
          <svg width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="#cbd5e1" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
          <p class="cvt-empty__title">Aucun profil trouvé</p>
          <p class="cvt-empty__sub">Essayez d'autres mots-clés ou supprimez les filtres.</p>
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
