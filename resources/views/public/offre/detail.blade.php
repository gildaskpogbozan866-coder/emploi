@extends('layouts.app')
@section('title', $offre->titre . ' — ' . $offre->entreprise)

@section('css')
<link rel="stylesheet" href="{{ asset('css/offre/detail-offre.css') }}">
@endsection

@section('content')
<section class="section" style="padding-top:40px">
  <div class="container">
    <a href="{{ route('offre.list') }}" style="display:inline-flex;align-items:center;gap:6px;color:#185FA5;font-size:.9rem;margin-bottom:24px;text-decoration:none">
      <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg> Retour aux offres
    </a>

    <div class="offre-detail-layout">

      {{-- Corps principal --}}
      <div class="offre-detail-main">
        <div class="offre-detail-header">
          <div class="offre-detail-logo">{{ strtoupper(substr($offre->entreprise, 0, 2)) }}</div>
          <div>
            <h1 class="offre-detail-title">{{ $offre->titre }}</h1>
            <p class="offre-detail-company">{{ $offre->entreprise }}</p>
            <div class="offre-detail-tags">
              <span class="tag tag--type">{{ $offre->type }}</span>
              @if($offre->secteur)<span class="tag">{{ $offre->secteur }}</span>@endif
              <span class="tag">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                {{ $offre->localisation }}
              </span>
              @if($offre->salaire)<span class="tag tag--green">{{ $offre->salaire }}</span>@endif
            </div>
          </div>
        </div>

        <div class="offre-detail-section">
          <h2>Description du poste</h2>
          <div class="offre-detail-content">{!! nl2br(e($offre->description)) !!}</div>
        </div>

        @if($offre->competences)
        <div class="offre-detail-section">
          <h2>Compétences requises</h2>
          <div class="offre-detail-content">{!! nl2br(e($offre->competences)) !!}</div>
        </div>
        @endif

        @if($offre->exigences)
        <div class="offre-detail-section">
          <h2>Exigences</h2>
          <div class="offre-detail-content">{!! nl2br(e($offre->exigences)) !!}</div>
        </div>
        @endif
      </div>

      {{-- Sidebar --}}
      <aside class="offre-detail-aside">
        <div class="offre-aside-card">
          @if($aPostule)
            <div style="background:#e6f9f0;border:1px solid #38A169;border-radius:10px;padding:14px;text-align:center;margin-bottom:16px">
              <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#38A169" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
              <p style="color:#276749;font-weight:600;margin-top:6px">Vous avez déjà postulé</p>
            </div>
          @else
            <a href="{{ route('offre.postuler', $offre) }}" class="btn btn--yellow" style="width:100%;text-align:center;display:block;padding:14px">
              Postuler à cette offre
            </a>
          @endif

          <div class="offre-aside-info">
            <div class="offre-aside-row">
              <span class="offre-aside-label">Type</span>
              <span>{{ $offre->type }}</span>
            </div>
            <div class="offre-aside-row">
              <span class="offre-aside-label">Localisation</span>
              <span>{{ $offre->localisation }}</span>
            </div>
            @if($offre->salaire)
            <div class="offre-aside-row">
              <span class="offre-aside-label">Rémunération</span>
              <span>{{ $offre->salaire }}</span>
            </div>
            @endif
            @if($offre->date_limite)
            <div class="offre-aside-row">
              <span class="offre-aside-label">Date limite</span>
              <span>{{ $offre->date_limite->format('d/m/Y') }}</span>
            </div>
            @endif
            <div class="offre-aside-row">
              <span class="offre-aside-label">Publiée</span>
              <span>{{ $offre->created_at->diffForHumans() }}</span>
            </div>
            <div class="offre-aside-row">
              <span class="offre-aside-label">Vues</span>
              <span>{{ $offre->vues }}</span>
            </div>
          </div>

          @auth
          <form method="POST" action="{{ route('candidat.offres-sauvegardees.toggle', $offre) }}" style="margin-top:12px">
            @csrf
            <button type="submit" class="btn btn--outline" style="width:100%">
              <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display:inline-block;vertical-align:-2px"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg> Sauvegarder l'offre
            </button>
          </form>
          @endauth
        </div>

        {{-- Signaler --}}
        @auth
        <div style="margin-top:12px;text-align:center">
          <a href="#" style="font-size:.78rem;color:#94a3b8">Signaler cette offre</a>
        </div>
        @endauth
      </aside>

    </div>
  </div>
</section>
@endsection
