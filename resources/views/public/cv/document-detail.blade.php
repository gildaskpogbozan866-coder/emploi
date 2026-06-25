@extends('layouts.app')
@section('title', ($document->type?->nom ?? 'Document') . ' · ' . $document->nom . ' | Emploi Bouge Bénin')
@section('description', Str::limit($document->competences ?? 'Document disponible sur Emploi Bouge Bénin.', 160))

@section('css')
<link rel="stylesheet" href="{{ asset('css/cv/cvtheque.css') }}">
@endsection

@section('content')

{{-- ══════════ HERO ══════════ --}}
<section class="section page-hero">
  <div class="container page-hero__inner">
    <span class="badge badge--blue">{{ $document->type?->nom ?? 'Document' }}</span>
    <h1 class="page-hero__title">{{ $document->nom }}</h1>
    <p class="page-hero__subtitle">
      @if($document->pays){{ $document->pays }}@endif
      @if($document->pays && $document->ville) · @endif
      @if($document->ville){{ $document->ville }}@endif
    </p>
  </div>
</section>

{{-- ══════════ SOUS-NAV ══════════ --}}
<div class="cvt-subnav">
  <div class="cvt-subnav__inner">
    <a href="{{ route('cv.public.theque') }}" class="cvt-subnav__link">Trouver des CV</a>
    <a href="{{ route('cv.public.tarif') }}"  class="cvt-subnav__link">Packs crédits</a>
    @if(!auth()->check() || auth()->user()->hasRole('candidat'))
      <a href="{{ auth()->check() && auth()->user()->hasRole('candidat') ? route('cv.public.depot') : route('auth.inscription').'?role=candidat' }}" class="cvt-subnav__link">Déposer un CV</a>
    @endif
  </div>
</div>

{{-- ══════════ CORPS ══════════ --}}
<div class="cvtd-page">
  <div class="cvtd-wrap">

    <a href="{{ route('cv.public.theque') }}" class="page-back-link">
      <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
      Retour à la CVthèque
    </a>

    {{-- ── CARTE PROFIL ── --}}
    <div class="cvtd-profile">

      {{-- En-tête gradient --}}
      <div class="cvtd-profile__head">
        <div class="cvtd-avatar" style="display:flex;align-items:center;justify-content:center">
          <svg width="30" height="30" fill="none" viewBox="0 0 24 24" stroke="rgba(255,255,255,.85)" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </div>
        <div class="cvtd-head-info">
          <h2 class="cvtd-head-title">{{ $document->nom }}</h2>
          @if($document->pays)
            <p class="cvtd-head-meta">
              <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
              {{ $document->pays }}{{ $document->ville ? ', ' . $document->ville : '' }}
            </p>
          @endif
          <div class="cvtd-head-badges">
            <span class="cvtd-head-chip" style="background:rgba(2,132,199,.2);color:#fff">
              {{ $document->type?->nom ?? 'Document' }}
            </span>
          </div>
        </div>
      </div>

      {{-- Corps --}}
      <div class="cvtd-profile__body">

        @if($document->competences)
        <div class="cvtd-section">
          <p class="cvtd-section-label">
            <span class="cvtd-section-label__icon">
              <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
            </span>
            Compétences
          </p>
          <p class="cvtd-section-text">{{ $document->competences }}</p>
        </div>
        @endif

        @if($document->experience)
        <div class="cvtd-section">
          <p class="cvtd-section-label">
            <span class="cvtd-section-label__icon">
              <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </span>
            Expérience
          </p>
          <p class="cvtd-section-text">{{ $document->experience }}</p>
        </div>
        @endif

        @if($document->formation)
        <div class="cvtd-section">
          <p class="cvtd-section-label">
            <span class="cvtd-section-label__icon">
              <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
            </span>
            Formation
          </p>
          <p class="cvtd-section-text">{{ $document->formation }}</p>
        </div>
        @endif

        @if($document->langues)
        <div class="cvtd-section">
          <p class="cvtd-section-label">
            <span class="cvtd-section-label__icon">
              <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
            </span>
            Langues
          </p>
          <p class="cvtd-section-text">{{ $document->langues }}</p>
        </div>
        @endif

      </div>
    </div>

    {{-- ── COORDONNÉES VERROUILLÉES ── --}}
    <div class="cvtd-locked">
      <div class="cvtd-locked__icon">
        <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#94a3b8" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
      </div>
      <h3 class="cvtd-locked__title">Coordonnées confidentielles</h3>
      <p class="cvtd-locked__desc">
        Le nom, l'email et le téléphone de ce candidat sont protégés.<br>
        @auth
          @if(auth()->user()->hasRole('recruteur'))
            Accédez au profil complet depuis votre espace recruteur CVthèque.
          @else
            Ces informations sont réservées aux recruteurs inscrits.
          @endif
        @else
          Créez un compte recruteur gratuit pour accéder aux profils complets et contacter les candidats.
        @endauth
      </p>
      <div class="cvtd-locked__actions">
        @auth
          @if(auth()->user()->hasRole('recruteur'))
            <a href="{{ route('recruteur.cvtheque') }}" class="cvtd-locked__btn cvtd-locked__btn--blue">
              <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
              Accéder à la CVthèque recruteur
            </a>
          @endif
        @else
          <a href="{{ route('auth.inscription') }}" class="cvtd-locked__btn cvtd-locked__btn--yellow">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            Créer un compte recruteur
          </a>
          <a href="{{ route('auth.connexion') }}" class="cvtd-locked__btn cvtd-locked__btn--blue">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
            Se connecter
          </a>
        @endauth
      </div>
    </div>

  </div>
</div>

@endsection
