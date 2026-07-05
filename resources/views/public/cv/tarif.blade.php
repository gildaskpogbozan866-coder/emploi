@extends('layouts.app')
@section('title', 'Packs crédits CVthèque | Emploi Bouge Bénin')

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
    @if(!auth()->check() || auth()->user()->hasRole(\App\Enums\Role::CANDIDAT))
      <a href="{{ auth()->check() && auth()->user()->hasRole(\App\Enums\Role::CANDIDAT) ? route('cv.public.depot') : route('auth.inscription').'?role=candidat' }}" class="cvt-subnav__link">Déposer un CV</a>
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
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:14px;margin-bottom:28px">

      @forelse($packs as $pack)
      <div style="border:2px solid {{ $pack->featured ? 'transparent' : ($pack->badge ? '#93c5fd' : '#c9dcf0') }};border-radius:14px;padding:22px 16px;text-align:center;position:relative;background:{{ $pack->featured ? 'linear-gradient(145deg,#042C53,#185FA5)' : ($pack->badge ? '#f0f7ff' : '#f5f9ff') }};box-shadow:{{ $pack->featured ? '0 8px 28px rgba(4,44,83,.3)' : '0 2px 10px rgba(4,44,83,.07)' }}">
        @if($pack->badge)
          <div style="position:absolute;top:-11px;left:50%;transform:translateX(-50%);background:#F5C842;color:#042C53;font-size:10px;font-weight:800;padding:2px 12px;border-radius:20px;white-space:nowrap;text-transform:uppercase">{{ $pack->badge }}</div>
        @endif
        <p style="font-size:2.4rem;font-weight:900;color:{{ $pack->featured ? '#F5C842' : '#042C53' }};margin:0;line-height:1">{{ $pack->credits }}</p>
        <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:{{ $pack->featured ? 'rgba(255,255,255,.5)' : '#94a3b8' }};margin:2px 0 14px">crédits</p>
        <p style="font-size:1.2rem;font-weight:800;color:{{ $pack->featured ? '#fff' : '#042C53' }};margin:0 0 2px">{{ number_format($pack->prix, 0, ',', ' ') }} FCFA</p>
        <p style="font-size:11px;color:{{ $pack->featured ? 'rgba(255,255,255,.45)' : '#94a3b8' }};margin:0 0 18px">{{ number_format($pack->prixParCredit(), 0, ',', ' ') }} FCFA / crédit</p>

        @auth
          @if(auth()->user()->hasRole(\App\Enums\Role::RECRUTEUR))
            <a href="{{ route('recruteur.cv-credits.confirm', ['pack' => $pack->id]) }}"
               style="display:block;padding:9px 14px;background:{{ $pack->featured ? '#F5C842' : '#185FA5' }};color:{{ $pack->featured ? '#042C53' : '#fff' }};border-radius:8px;font-weight:700;font-size:13px;text-decoration:none">
              Acheter ce pack
            </a>
          @else
            <span style="display:block;padding:9px 14px;background:#f1f5f9;color:#94a3b8;border-radius:8px;font-weight:700;font-size:12px">Réservé aux recruteurs</span>
          @endif
        @else
          <a href="{{ route('auth.inscription') }}"
             style="display:block;padding:9px 14px;background:{{ $pack->featured ? '#F5C842' : '#185FA5' }};color:{{ $pack->featured ? '#042C53' : '#fff' }};border-radius:8px;font-weight:700;font-size:13px;text-decoration:none">
            Créer un compte recruteur
          </a>
        @endauth
      </div>
      @empty
        <p style="color:#94a3b8;font-size:13.5px;padding:20px 0;grid-column:1/-1;text-align:center">Aucun pack disponible pour le moment.</p>
      @endforelse

    </div>

    {{-- Paiement --}}
    <p class="cvt-tarif-footer">Paiement sécurisé via Mobile Money (MTN, Moov) ou carte bancaire · Les crédits sont valables à vie</p>

  </div>
</section>

@endsection
