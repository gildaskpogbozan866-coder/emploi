@extends('layouts.app')
@section('title', 'Packs crédits CVthèque — Emploi Bouge Bénin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/cv/cvtheque.css') }}">
@endsection

@section('content')

{{-- Page Hero --}}
<section class="section page-hero">
  <div class="container page-hero__inner">
    <span class="badge badge--blue">CVthèque</span>
    <h1 class="page-hero__title">Packs crédits CVthèque</h1>
    <p class="page-hero__subtitle">Chaque crédit débloque les coordonnées complètes d'un candidat et son CV en téléchargement. Les crédits ne s'expirent jamais.</p>
  </div>
</section>

{{-- Sous-nav --}}
<div class="cvt-subnav">
  <div class="cvt-subnav__inner">
    <a href="{{ route('cv.public.theque') }}" class="cvt-subnav__link">Trouver des CV</a>
    <a href="{{ route('cv.public.tarif') }}"  class="cvt-subnav__link active">Packs crédits</a>
    @if(!auth()->check() || auth()->user()->hasRole('candidat'))
      <a href="{{ route('cv.public.depot') }}" class="cvt-subnav__link">Déposer un CV</a>
    @endif
  </div>
</div>

<section class="section" style="background:#f2f4f7">
  <div class="container" style="max-width:940px">

    {{-- Info --}}
    <div class="cvt-tarif-info">
      <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="flex-shrink:0"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      <p>1 crédit = déblocage des infos personnelles + téléchargement d'<strong>1 CV</strong>. Retélécharger le même CV coûte à nouveau 1 crédit.</p>
    </div>

    {{-- Packs --}}
    @php
    $packs = [
        ['credits' => 5,  'prix' => '5 000',  'unit' => '1 000 FCFA / CV', 'badge' => null,              'featured' => false],
        ['credits' => 10, 'prix' => '9 000',  'unit' => '900 FCFA / CV',   'badge' => null,              'featured' => false],
        ['credits' => 25, 'prix' => '20 000', 'unit' => '800 FCFA / CV',   'badge' => 'Populaire',       'featured' => false],
        ['credits' => 50, 'prix' => '35 000', 'unit' => '700 FCFA / CV',   'badge' => 'Meilleure valeur','featured' => true],
    ];
    @endphp

    <div class="cvt-tarif-grid">
      @foreach($packs as $pack)
      <div class="cvt-tarif-card {{ $pack['featured'] ? 'cvt-tarif-card--featured' : '' }}">
        @if($pack['badge'])
          <div class="cvt-tarif-card__badge">{{ $pack['badge'] }}</div>
        @endif
        <div class="cvt-tarif-card__credits">{{ $pack['credits'] }}</div>
        <div class="cvt-tarif-card__label">crédits</div>
        <div class="cvt-tarif-card__price">{{ $pack['prix'] }} FCFA</div>
        <div class="cvt-tarif-card__unit">{{ $pack['unit'] }}</div>

        @auth
          @if(auth()->user()->hasRole('recruteur'))
            <a href="{{ route('recruteur.cv-credits.confirm', ['credits' => $pack['credits']]) }}"
               class="btn {{ $pack['featured'] ? 'btn--yellow' : 'btn--blue' }} cvt-tarif-card__cta">
              Acheter ce pack
            </a>
          @else
            <span class="cvt-tarif-card__disabled">Réservé aux recruteurs</span>
          @endif
        @else
          <a href="{{ route('auth.inscription') }}"
             class="btn {{ $pack['featured'] ? 'btn--yellow' : 'btn--blue' }} cvt-tarif-card__cta">
            Créer un compte recruteur
          </a>
        @endauth
      </div>
      @endforeach
    </div>

    {{-- Paiement --}}
    <p class="cvt-tarif-footer">Paiement sécurisé via Mobile Money (MTN, Moov) ou carte bancaire · Les crédits sont valables à vie</p>

  </div>
</section>

@endsection
