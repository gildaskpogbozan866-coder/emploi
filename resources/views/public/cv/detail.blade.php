@extends('layouts.app')
@section('title', 'Profil CVthèque — ' . $cv->titre_poste . ' — Emploi Bouge Bénin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/cv/cvtheque.css') }}">
@endsection

@section('content')

{{-- Sous-nav --}}
<div class="cvt-subnav">
  <div class="cvt-subnav__inner">
    <a href="{{ route('cv.public.theque') }}" class="cvt-subnav__link">Trouver des CV</a>
    <a href="{{ route('cv.public.tarif') }}"  class="cvt-subnav__link">Packs crédits</a>
    @if(!auth()->check() || auth()->user()->hasRole('candidat'))
      <a href="{{ route('cv.public.depot') }}" class="cvt-subnav__link">Déposer un CV</a>
    @endif
  </div>
</div>

<section class="section" style="background:#f2f4f7;min-height:60vh">
  <div class="container" style="max-width:780px">

    <a href="{{ route('cv.public.theque') }}" class="page-back-link">
      <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
      Retour à la CVthèque
    </a>

    <div class="pub-card">

      {{-- En-tête professionnel — visible par tous --}}
      <div style="background:linear-gradient(135deg,#042C53 0%,#185FA5 100%);padding:32px 28px;display:flex;align-items:center;gap:20px">
        <div style="width:70px;height:70px;border-radius:50%;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;border:2px solid rgba(255,255,255,.3)">
          <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="rgba(255,255,255,.7)" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        </div>
        <div>
          <h1 style="margin:0 0 4px;font-size:1.25rem;color:#fff;font-weight:700">{{ $cv->titre_poste }}</h1>
          @if($cv->pays)
            <p style="margin:0 0 4px;font-size:13.5px;color:rgba(255,255,255,.75)">
              <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="vertical-align:-1px;margin-right:3px"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
              {{ $cv->pays }}
            </p>
          @endif
          @if($cv->disponibilite)
          <span style="display:inline-flex;align-items:center;gap:5px;margin-top:6px;background:rgba(255,255,255,.15);border-radius:20px;padding:3px 12px;font-size:11.5px;color:#fff;font-weight:600">
            <span style="width:7px;height:7px;border-radius:50%;background:{{ $cv->disponibilite === 'en_recherche' ? '#4ade80' : ($cv->disponibilite === 'ouvert' ? '#facc15' : '#f87171') }}"></span>
            {{ ['en_recherche' => 'En recherche active', 'ouvert' => 'Ouvert aux opportunités', 'indisponible' => 'Non disponible'][$cv->disponibilite] }}
          </span>
          @endif
        </div>
      </div>

      {{-- Détails professionnels — visibles par tous --}}
      <div style="padding:28px">

        @if($cv->secteur)
        <div style="display:inline-flex;align-items:center;gap:6px;background:#f0f9ff;border-radius:6px;padding:4px 12px;margin-bottom:20px">
          <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="#0284c7" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
          <span style="font-size:12.5px;color:#0284c7;font-weight:600">{{ $cv->secteur }}</span>
        </div>
        @endif

        @if($cv->resume)
        <div style="margin-bottom:22px;padding-bottom:22px;border-bottom:1px solid #f0f2f5">
          <h2 style="font-size:11.5px;font-weight:800;color:#6b7a8d;text-transform:uppercase;letter-spacing:.07em;margin:0 0 8px">Résumé</h2>
          <p style="font-size:14px;color:#374151;line-height:1.7;margin:0">{{ $cv->resume }}</p>
        </div>
        @endif

        @if($cv->competences)
        <div style="margin-bottom:22px;padding-bottom:22px;border-bottom:1px solid #f0f2f5">
          <h2 style="font-size:11.5px;font-weight:800;color:#6b7a8d;text-transform:uppercase;letter-spacing:.07em;margin:0 0 8px">Compétences</h2>
          <p style="font-size:14px;color:#374151;line-height:1.7;margin:0">{{ $cv->competences }}</p>
        </div>
        @endif

        @if($cv->experience)
        <div style="margin-bottom:22px;padding-bottom:22px;border-bottom:1px solid #f0f2f5">
          <h2 style="font-size:11.5px;font-weight:800;color:#6b7a8d;text-transform:uppercase;letter-spacing:.07em;margin:0 0 8px">Expérience</h2>
          <p style="font-size:14px;color:#374151;line-height:1.7;margin:0;white-space:pre-line">{{ $cv->experience }}</p>
        </div>
        @endif

        @if($cv->formation)
        <div style="margin-bottom:22px;padding-bottom:22px;border-bottom:1px solid #f0f2f5">
          <h2 style="font-size:11.5px;font-weight:800;color:#6b7a8d;text-transform:uppercase;letter-spacing:.07em;margin:0 0 8px">Formation</h2>
          <p style="font-size:14px;color:#374151;line-height:1.7;margin:0;white-space:pre-line">{{ $cv->formation }}</p>
        </div>
        @endif

        @if($cv->langues)
        <div style="margin-bottom:22px;padding-bottom:22px;border-bottom:1px solid #f0f2f5">
          <h2 style="font-size:11.5px;font-weight:800;color:#6b7a8d;text-transform:uppercase;letter-spacing:.07em;margin:0 0 8px">Langues</h2>
          <p style="font-size:14px;color:#374151;margin:0">{{ $cv->langues }}</p>
        </div>
        @endif

        {{-- ══════════ BLOC INFORMATIONS PERSONNELLES ══════════ --}}
        @if($canSeePersonalInfo)
          {{-- Recruteur avec crédits : infos débloquées --}}
          <div style="background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:12px;padding:20px 22px;margin-bottom:20px">
            <div style="display:flex;align-items:center;gap:8px;margin-bottom:14px">
              <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#16a34a" stroke-width="2" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
              <span style="font-size:13px;font-weight:700;color:#16a34a">Informations personnelles débloquées</span>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
              <div>
                <p style="font-size:11px;font-weight:700;color:#6b7a8d;text-transform:uppercase;letter-spacing:.06em;margin:0 0 3px">Nom complet</p>
                <p style="font-size:14px;color:#042C53;margin:0;font-weight:600">{{ $cv->candidat?->prenom }} {{ $cv->candidat?->nom }}</p>
              </div>
              @if($cv->ville)
              <div>
                <p style="font-size:11px;font-weight:700;color:#6b7a8d;text-transform:uppercase;letter-spacing:.06em;margin:0 0 3px">Ville</p>
                <p style="font-size:14px;color:#042C53;margin:0;font-weight:600">{{ $cv->ville }}</p>
              </div>
              @endif
              @if($cv->candidat?->email)
              <div>
                <p style="font-size:11px;font-weight:700;color:#6b7a8d;text-transform:uppercase;letter-spacing:.06em;margin:0 0 3px">Email</p>
                <p style="font-size:14px;color:#042C53;margin:0;font-weight:600">{{ $cv->candidat->email }}</p>
              </div>
              @endif
              @if($cv->candidat?->telephone)
              <div>
                <p style="font-size:11px;font-weight:700;color:#6b7a8d;text-transform:uppercase;letter-spacing:.06em;margin:0 0 3px">Téléphone</p>
                <p style="font-size:14px;color:#042C53;margin:0;font-weight:600">{{ $cv->candidat->telephone }}</p>
              </div>
              @endif
            </div>
          </div>

          {{-- Télécharger --}}
          @if($cv->fichier_path)
          <form method="POST" action="{{ route('recruteur.cvtheque.telecharger', $cv) }}" style="margin-bottom:16px">
            @csrf
            <button type="submit" style="width:100%;display:flex;align-items:center;justify-content:center;gap:10px;padding:13px 20px;background:linear-gradient(135deg,#042C53,#185FA5);color:#fff;border:none;border-radius:10px;font-weight:700;font-size:14.5px;cursor:pointer;font-family:inherit">
              <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
              Télécharger ce CV — 1 crédit
            </button>
          </form>
          @endif

        @else
          {{-- Infos personnelles verrouillées --}}
          <div style="border:2px dashed #e2e8f0;border-radius:14px;padding:28px 24px;text-align:center;background:#fafafa">
            <div style="width:52px;height:52px;border-radius:50%;background:#f1f5f9;display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
              <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#94a3b8" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </div>
            <h3 style="font-size:15px;font-weight:700;color:#042C53;margin:0 0 8px">Informations personnelles masquées</h3>
            <p style="font-size:13.5px;color:#64748b;margin:0 0 20px;line-height:1.6">
              @guest
                Le nom, la ville, l'email et le téléphone de ce candidat sont confidentiels.<br>
                Créez un compte recruteur et achetez des crédits pour y accéder.
              @elseif(auth()->user()->hasRole('candidat'))
                Ces informations sont accessibles uniquement aux recruteurs disposant de crédits CVthèque.
              @else
                Vous n'avez plus de crédits. Achetez un pack pour débloquer ce profil et télécharger le CV.
              @endguest
            </p>
            @if(!auth()->check() || auth()->user()->hasRole('candidat'))
              @guest
                <a href="{{ route('auth.inscription') }}" style="display:inline-flex;align-items:center;gap:8px;padding:11px 24px;background:#185FA5;color:#fff;border-radius:8px;font-weight:700;font-size:13.5px;text-decoration:none">
                  Créer un compte recruteur
                </a>
              @else
                <p style="font-size:12.5px;color:#94a3b8;margin:0">Vous devez être connecté en tant que <strong>recruteur</strong>.</p>
              @endguest
            @else
              <a href="{{ route('cv.public.tarif') }}" style="display:inline-flex;align-items:center;gap:8px;padding:11px 24px;background:#185FA5;color:#fff;border-radius:8px;font-weight:700;font-size:13.5px;text-decoration:none">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                Acheter des crédits CVthèque
              </a>
            @endif
          </div>
        @endif

      </div>
    </div>
  </div>
</section>

@endsection
