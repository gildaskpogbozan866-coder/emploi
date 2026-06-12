@extends('layouts.app')
@section('title', 'Packs crédits CVthèque — Emploi Bouge Bénin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/cv/cvtheque.css') }}">
@endsection

@section('content')

<div class="cvt-subnav">
  <div class="cvt-subnav__inner">
    <a href="{{ route('cv.public.theque') }}" class="cvt-subnav__link">Trouver des CV</a>
    <a href="{{ route('cv.public.tarif') }}"  class="cvt-subnav__link active">Packs crédits</a>
    @if(!auth()->check() || auth()->user()->hasRole('candidat'))
      <a href="{{ route('cv.public.depot') }}" class="cvt-subnav__link">Déposer un CV</a>
    @endif
  </div>
</div>

<section style="padding:60px 20px 80px;background:#f8fafc;min-height:70vh">
  <div style="max-width:880px;margin:0 auto">

    {{-- Titre --}}
    <div style="text-align:center;margin-bottom:48px">
      <div style="width:56px;height:56px;border-radius:16px;background:linear-gradient(135deg,#042C53,#185FA5);display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
        <svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="#fff" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
      </div>
      <h1 style="font-size:2rem;font-weight:800;color:#042C53;margin:0 0 12px">Packs crédits CVthèque</h1>
      <p style="font-size:1rem;color:#64748b;max-width:500px;margin:0 auto;line-height:1.65">
        Chaque crédit vous permet de <strong>débloquer les informations personnelles</strong> d'un candidat et de <strong>télécharger son CV</strong>.<br>
        Les crédits ne s'expirent pas.
      </p>
    </div>

    {{-- Info --}}
    <div style="background:#fff;border:1.5px solid #bae6fd;border-radius:12px;padding:14px 20px;display:flex;align-items:center;gap:12px;margin-bottom:36px">
      <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#0284c7" stroke-width="2" style="flex-shrink:0"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
      <p style="font-size:13.5px;color:#0369a1;margin:0">
        1 crédit = déblocage des infos personnelles + téléchargement d'<strong>1 CV</strong>. Retélécharger le même CV coûte à nouveau 1 crédit.
      </p>
    </div>

    {{-- Packs --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(195px,1fr));gap:16px;margin-bottom:40px">

      @php
      $packs = [
          ['credits' => 5,  'prix' => '5 000',  'unit' => '1 000 / CV', 'badge' => null,           'dark' => false],
          ['credits' => 10, 'prix' => '9 000',  'unit' => '900 / CV',   'badge' => null,           'dark' => false],
          ['credits' => 25, 'prix' => '20 000', 'unit' => '800 / CV',   'badge' => 'Populaire',    'dark' => false],
          ['credits' => 50, 'prix' => '35 000', 'unit' => '700 / CV',   'badge' => 'Meilleure valeur', 'dark' => true],
      ];
      @endphp

      @foreach($packs as $pack)
      <div style="background:{{ $pack['dark'] ? 'linear-gradient(145deg,#042C53,#185FA5)' : '#fff' }};border:2px solid {{ $pack['dark'] ? 'transparent' : ($pack['badge'] === 'Populaire' ? '#93c5fd' : '#e2e8f0') }};border-radius:16px;padding:26px 20px;display:flex;flex-direction:column;align-items:center;text-align:center;position:relative">
        @if($pack['badge'])
        <div style="position:absolute;top:-12px;left:50%;transform:translateX(-50%);background:#F5C842;color:#042C53;font-size:10.5px;font-weight:800;padding:3px 14px;border-radius:20px;text-transform:uppercase;white-space:nowrap;letter-spacing:.04em">{{ $pack['badge'] }}</div>
        @endif

        <p style="font-size:2.6rem;font-weight:900;color:{{ $pack['dark'] ? '#F5C842' : '#042C53' }};margin:0;line-height:1">{{ $pack['credits'] }}</p>
        <p style="font-size:13px;color:{{ $pack['dark'] ? 'rgba(255,255,255,.65)' : '#94a3b8' }};margin:2px 0 16px;font-weight:600;text-transform:uppercase;letter-spacing:.05em">crédits</p>

        <p style="font-size:1.35rem;font-weight:800;color:{{ $pack['dark'] ? '#fff' : '#042C53' }};margin:0 0 4px">{{ $pack['prix'] }} FCFA</p>
        <p style="font-size:11.5px;color:{{ $pack['dark'] ? 'rgba(255,255,255,.5)' : '#94a3b8' }};margin:0 0 20px">{{ $pack['unit'] }}</p>

        @auth
          @if(auth()->user()->hasRole('recruteur'))
            <a href="{{ route('recruteur.cv-credits.confirm', ['credits' => $pack['credits']]) }}"
               style="display:block;width:100%;box-sizing:border-box;text-align:center;padding:10px 16px;background:{{ $pack['dark'] ? '#F5C842' : '#185FA5' }};color:{{ $pack['dark'] ? '#042C53' : '#fff' }};border-radius:8px;font-weight:700;font-size:13px;text-decoration:none">
              Acheter ce pack
            </a>
          @else
            <p style="font-size:12px;color:{{ $pack['dark'] ? 'rgba(255,255,255,.6)' : '#94a3b8' }};margin:0">Réservé aux recruteurs</p>
          @endif
        @else
          <a href="{{ route('auth.inscription') }}"
             style="display:block;width:100%;box-sizing:border-box;text-align:center;padding:10px 16px;background:{{ $pack['dark'] ? '#F5C842' : '#185FA5' }};color:{{ $pack['dark'] ? '#042C53' : '#fff' }};border-radius:8px;font-weight:700;font-size:13px;text-decoration:none">
            Créer un compte recruteur
          </a>
        @endauth
      </div>
      @endforeach

    </div>

    {{-- Paiement --}}
    <div style="text-align:center">
      <p style="font-size:13px;color:#94a3b8;margin:0 0 8px">Paiement sécurisé via Mobile Money (MTN, Moov) ou carte bancaire.</p>
      <p style="font-size:12px;color:#cbd5e1;margin:0">Les crédits sont valables à vie et ne s'expirent jamais.</p>
    </div>

  </div>
</section>

@endsection
