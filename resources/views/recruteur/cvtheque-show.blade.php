@extends('layouts.recruteur')
@section('title', 'Profil — ' . $cv->titre_poste)

@section('sidebar')
@include('recruteur._sidebar')
@endsection

@section('content')
<div class="rec-topbar">
  <div class="rec-topbar__left">
    <a href="{{ route('recruteur.cvtheque') }}" style="font-size:13px;color:#185FA5;text-decoration:none;display:inline-flex;align-items:center;gap:5px;margin-bottom:8px">
      <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/></svg>
      Retour à la CVthèque
    </a>
    <h1>Profil complet</h1>
  </div>
  <div class="rec-topbar__right">
    <div style="display:flex;align-items:center;gap:8px;background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:10px;padding:8px 16px">
      <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#16a34a" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/></svg>
      <span style="font-size:13px;color:#16a34a;font-weight:700">{{ $credits }} crédit{{ $credits > 1 ? 's' : '' }} restant{{ $credits > 1 ? 's' : '' }}</span>
    </div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 290px;gap:20px;align-items:start">

  {{-- Profil --}}
  <div class="rec-card">
    <div style="background:linear-gradient(135deg,#042C53 0%,#185FA5 100%);padding:28px 24px;display:flex;align-items:center;gap:18px;border-radius:12px 12px 0 0">
      <div style="width:64px;height:64px;border-radius:50%;background:rgba(255,255,255,.15);display:flex;align-items:center;justify-content:center;flex-shrink:0;border:2px solid rgba(255,255,255,.3)">
        <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="rgba(255,255,255,.7)" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
      </div>
      <div>
        <h2 style="margin:0 0 3px;font-size:1.15rem;color:#fff;font-weight:700">{{ $cv->candidat?->prenom }} {{ $cv->candidat?->nom }}</h2>
        <p style="margin:0;font-size:.95rem;color:rgba(255,255,255,.8)">{{ $cv->titre_poste }}</p>
        @if($cv->disponibilite)
        <span style="display:inline-flex;align-items:center;gap:5px;margin-top:8px;background:rgba(255,255,255,.15);border-radius:20px;padding:3px 12px;font-size:11.5px;color:#fff;font-weight:600">
          <span style="width:7px;height:7px;border-radius:50%;background:{{ $cv->disponibilite === 'en_recherche' ? '#4ade80' : ($cv->disponibilite === 'ouvert' ? '#facc15' : '#f87171') }}"></span>
          {{ ['en_recherche' => 'En recherche active', 'ouvert' => 'Ouvert aux opportunités', 'indisponible' => 'Non disponible'][$cv->disponibilite] }}
        </span>
        @endif
      </div>
    </div>

    <div class="rec-card__body">

      {{-- Infos personnelles --}}
      <div style="background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:10px;padding:16px 18px;margin-bottom:22px">
        <p style="font-size:11px;font-weight:800;color:#16a34a;text-transform:uppercase;letter-spacing:.07em;margin:0 0 12px">Informations de contact</p>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px">
          <div>
            <p style="font-size:11px;font-weight:700;color:#6b7a8d;text-transform:uppercase;letter-spacing:.05em;margin:0 0 3px">Nom complet</p>
            <p style="font-size:13.5px;color:#042C53;margin:0;font-weight:600">{{ $cv->candidat?->prenom }} {{ $cv->candidat?->nom }}</p>
          </div>
          @if($cv->ville)
          <div>
            <p style="font-size:11px;font-weight:700;color:#6b7a8d;text-transform:uppercase;letter-spacing:.05em;margin:0 0 3px">Ville</p>
            <p style="font-size:13.5px;color:#042C53;margin:0;font-weight:600">{{ $cv->ville }}</p>
          </div>
          @endif
          @if($cv->candidat?->email)
          <div>
            <p style="font-size:11px;font-weight:700;color:#6b7a8d;text-transform:uppercase;letter-spacing:.05em;margin:0 0 3px">Email</p>
            <p style="font-size:13.5px;color:#042C53;margin:0;font-weight:600">{{ $cv->candidat->email }}</p>
          </div>
          @endif
          @if($cv->candidat?->telephone)
          <div>
            <p style="font-size:11px;font-weight:700;color:#6b7a8d;text-transform:uppercase;letter-spacing:.05em;margin:0 0 3px">Téléphone</p>
            <p style="font-size:13.5px;color:#042C53;margin:0;font-weight:600">{{ $cv->candidat->telephone }}</p>
          </div>
          @endif
          @if($cv->pays)
          <div>
            <p style="font-size:11px;font-weight:700;color:#6b7a8d;text-transform:uppercase;letter-spacing:.05em;margin:0 0 3px">Pays</p>
            <p style="font-size:13.5px;color:#042C53;margin:0;font-weight:600">{{ $cv->pays }}</p>
          </div>
          @endif
        </div>
      </div>

      @if($cv->resume)
      <div style="margin-bottom:20px;padding-bottom:20px;border-bottom:1px solid #f0f2f5">
        <h3 style="font-size:11px;font-weight:800;color:#6b7a8d;text-transform:uppercase;letter-spacing:.07em;margin:0 0 8px">Résumé</h3>
        <p style="font-size:14px;color:#374151;line-height:1.7;margin:0">{{ $cv->resume }}</p>
      </div>
      @endif

      @if($cv->competences)
      <div style="margin-bottom:20px;padding-bottom:20px;border-bottom:1px solid #f0f2f5">
        <h3 style="font-size:11px;font-weight:800;color:#6b7a8d;text-transform:uppercase;letter-spacing:.07em;margin:0 0 8px">Compétences</h3>
        <p style="font-size:14px;color:#374151;line-height:1.7;margin:0">{{ $cv->competences }}</p>
      </div>
      @endif

      @if($cv->experience)
      <div style="margin-bottom:20px;padding-bottom:20px;border-bottom:1px solid #f0f2f5">
        <h3 style="font-size:11px;font-weight:800;color:#6b7a8d;text-transform:uppercase;letter-spacing:.07em;margin:0 0 8px">Expérience</h3>
        <p style="font-size:14px;color:#374151;line-height:1.7;margin:0;white-space:pre-line">{{ $cv->experience }}</p>
      </div>
      @endif

      @if($cv->formation)
      <div style="margin-bottom:20px;padding-bottom:20px;border-bottom:1px solid #f0f2f5">
        <h3 style="font-size:11px;font-weight:800;color:#6b7a8d;text-transform:uppercase;letter-spacing:.07em;margin:0 0 8px">Formation</h3>
        <p style="font-size:14px;color:#374151;line-height:1.7;margin:0;white-space:pre-line">{{ $cv->formation }}</p>
      </div>
      @endif

      @if($cv->langues)
      <div>
        <h3 style="font-size:11px;font-weight:800;color:#6b7a8d;text-transform:uppercase;letter-spacing:.07em;margin:0 0 8px">Langues</h3>
        <p style="font-size:14px;color:#374151;margin:0">{{ $cv->langues }}</p>
      </div>
      @endif

    </div>
  </div>

  {{-- Sidebar actions --}}
  <div style="display:flex;flex-direction:column;gap:12px">

    {{-- Télécharger --}}
    <div class="rec-card" style="border-color:{{ $cv->fichier_path ? '#bae6fd' : '#e2e8f0' }}">
      <div class="rec-card__body">
        <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px">
          <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#042C53" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
          <h3 style="font-size:13px;font-weight:700;color:#042C53;margin:0">Télécharger le CV</h3>
        </div>

        @if($cv->fichier_path)
          @php $ext = strtoupper(pathinfo($cv->fichier_path, PATHINFO_EXTENSION)); @endphp
          <div style="background:#f0f9ff;border-radius:8px;padding:10px 12px;margin-bottom:14px;display:flex;align-items:center;gap:8px">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#0284c7" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span style="font-size:12px;color:#0284c7;font-weight:600">Fichier {{ $ext }} disponible</span>
          </div>
          <p style="font-size:12px;color:#64748b;margin:0 0 12px;line-height:1.55">
            <strong style="color:#dc2626">−1 crédit</strong> par téléchargement.<br>
            Solde actuel : <strong style="color:#042C53">{{ $credits }}</strong> crédit{{ $credits > 1 ? 's' : '' }}.
          </p>
          <form method="POST" action="{{ route('recruteur.cvtheque.telecharger', $cv) }}">
            @csrf
            <button type="submit" style="width:100%;display:flex;align-items:center;justify-content:center;gap:8px;padding:12px 16px;background:linear-gradient(135deg,#042C53,#185FA5);color:#fff;border:none;border-radius:9px;font-weight:700;font-size:14px;cursor:pointer;font-family:inherit;letter-spacing:.01em">
              <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
              Télécharger le CV
            </button>
          </form>

          @if(session('error'))
            <p style="font-size:12px;color:#dc2626;margin:8px 0 0;text-align:center">{{ session('error') }}</p>
          @endif

        @else
          <div style="text-align:center;padding:12px 0">
            <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="#cbd5e1" stroke-width="1.5" style="display:block;margin:0 auto 8px"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <p style="font-size:12.5px;color:#94a3b8;margin:0">Ce candidat n'a pas joint de fichier.</p>
          </div>
        @endif
      </div>
    </div>

    {{-- Acheter plus --}}
    <div class="rec-card">
      <div class="rec-card__body">
        <p style="font-size:12.5px;color:#64748b;margin:0 0 10px;line-height:1.5">Besoin de plus de crédits ?</p>
        <a href="{{ route('cv.public.tarif') }}" class="rec-btn rec-btn--outline" style="width:100%;justify-content:center;text-align:center;display:block">
          Voir les packs crédits
        </a>
      </div>
    </div>

    {{-- Favori --}}
    <div class="rec-card">
      <div class="rec-card__body">
        <form method="POST" action="{{ route('recruteur.cvtheque.favoris', $cv) }}">
          @csrf
          <button type="submit" class="rec-btn rec-btn--outline" style="width:100%;justify-content:center">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="vertical-align:-2px;margin-right:4px"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            Ajouter aux favoris
          </button>
        </form>
      </div>
    </div>

    {{-- Contacter --}}
    @if(auth()->user()->hasPermissionTo('contact-candidats'))
    <div class="rec-card">
      <div class="rec-card__body">
        <a href="{{ route('recruteur.messagerie.initier', $cv->candidat) }}" class="rec-btn rec-btn--outline" style="width:100%;justify-content:center;text-align:center;display:block">
          <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="vertical-align:-2px;margin-right:4px"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
          Envoyer un message
        </a>
      </div>
    </div>
    @endif

  </div>

</div>
@endsection
