@extends('layouts.app')
@section('title', 'Profil CVthèque · ' . ($cv->metier ?? 'Candidat') . ' | Emploi Bouge Bénin')
@section('description', Str::limit($cv->resume ?? 'Profil candidat disponible sur Emploi Bouge Bénin.', 160))

@section('css')
<link rel="stylesheet" href="{{ asset('css/cv/cvtheque.css') }}">
<style>
/* ── Sections structurées du détail CV ── */
.cvd-section { margin-bottom: 28px; }
.cvd-section-title {
  font-size: 11.5px;
  font-weight: 800;
  color: #185FA5;
  text-transform: uppercase;
  letter-spacing: .07em;
  display: flex;
  align-items: center;
  gap: 8px;
  margin: 0 0 14px;
  padding-bottom: 10px;
  border-bottom: 2px solid #e8f0fb;
}
.cvd-section-title svg { flex-shrink: 0; }

/* Résumé */
.cvd-resume {
  background: #f0f6ff;
  border: 1.5px solid #c7dcf8;
  border-radius: 12px;
  padding: 16px 20px;
  font-size: 14px;
  color: #1e3a5f;
  line-height: 1.7;
}

/* Cards expérience / formation */
.cvd-timeline { display: flex; flex-direction: column; gap: 14px; }
.cvd-card {
  background: #f8fafd;
  border: 1.5px solid #e4eaf5;
  border-radius: 12px;
  padding: 16px 18px;
}
.cvd-card-header { display: flex; gap: 12px; align-items: flex-start; }
.cvd-card-icon {
  width: 38px;
  height: 38px;
  border-radius: 10px;
  background: #dbeafe;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.cvd-card-info { flex: 1; min-width: 0; }
.cvd-card-title {
  font-size: 14.5px;
  font-weight: 700;
  color: #1e3a5f;
  line-height: 1.3;
  margin: 0 0 3px;
}
.cvd-card-sub {
  font-size: 13px;
  color: #185FA5;
  font-weight: 600;
  margin: 0 0 8px;
}
.cvd-card-meta {
  display: flex;
  flex-wrap: wrap;
  gap: 6px;
  margin-bottom: 2px;
}
.cvd-card-pill {
  font-size: 11.5px;
  color: #475569;
  background: #f1f5f9;
  border: 1px solid #e2e8f0;
  padding: 3px 9px;
  border-radius: 20px;
  display: flex;
  align-items: center;
  gap: 4px;
}
.cvd-card-missions {
  margin: 10px 0 0;
  padding: 0;
  list-style: none;
  display: flex;
  flex-direction: column;
  gap: 5px;
}
.cvd-card-missions li {
  font-size: 13px;
  color: #475569;
  padding-left: 16px;
  position: relative;
  line-height: 1.55;
}
.cvd-card-missions li::before {
  content: '▸';
  position: absolute;
  left: 0;
  color: #185FA5;
  font-size: 11px;
  top: 2px;
}

/* Compétences */
.cvd-chips { display: flex; flex-wrap: wrap; gap: 8px; }
.cvd-chip {
  background: #eff6ff;
  color: #1e40af;
  font-size: 13px;
  font-weight: 600;
  padding: 6px 14px;
  border-radius: 20px;
  border: 1.5px solid #bfdbfe;
}

/* Langues */
.cvd-lang-list { display: flex; flex-direction: column; gap: 8px; }
.cvd-lang-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 11px 16px;
  background: #f8fafd;
  border: 1.5px solid #e4eaf5;
  border-radius: 10px;
}
.cvd-lang-name {
  font-size: 13.5px;
  font-weight: 700;
  color: #1e3a5f;
  display: flex;
  align-items: center;
  gap: 8px;
}
.cvd-lang-level {
  font-size: 11.5px;
  font-weight: 700;
  color: #185FA5;
  background: #dbeafe;
  padding: 3px 12px;
  border-radius: 20px;
  border: 1px solid #bfdbfe;
}

/* Badges en-tête */
.cvd-head-badges { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 10px; }
.cvd-head-badge {
  font-size: 11px;
  font-weight: 700;
  padding: 4px 11px;
  border-radius: 20px;
  background: rgba(255,255,255,.18);
  color: #fff;
  border: 1px solid rgba(255,255,255,.3);
  white-space: nowrap;
}
</style>
@endsection

@section('content')
@php
  // ── Parse expériences ──
  $expBlocks = [];
  if ($cv->experience) {
    foreach (preg_split('/\n\n+/', trim($cv->experience)) as $blk) {
      $lines = explode("\n", trim($blk));
      if (!trim($lines[0])) continue;
      $header   = trim($lines[0]);
      $missions = [];
      if (isset($lines[1]) && str_starts_with(trim($lines[1]), 'Missions : ')) {
        $missions = array_values(array_filter(array_map('trim', explode(',', substr(trim($lines[1]), 11)))));
      }
      $parts  = explode(' | ', $header);
      $first  = array_shift($parts);
      $poste  = trim($first);
      $entrep = '';
      if (str_contains($first, ' — ')) {
        [$poste, $entrep] = explode(' — ', $first, 2);
        $poste  = trim($poste);
        $entrep = trim($entrep);
      }
      $periode = '';
      if (count($parts) > 0) {
        $last = end($parts);
        if (str_contains($last, '→') || str_contains($last, 'en cours')) {
          $periode = array_pop($parts);
        }
      }
      $lieu    = count($parts) ? trim(array_shift($parts)) : '';
      $secteur = count($parts) ? trim(array_shift($parts)) : '';
      $expBlocks[] = compact('poste', 'entrep', 'lieu', 'secteur', 'periode', 'missions');
    }
  }

  // ── Parse formations ──
  $formBlocks = [];
  if ($cv->formation) {
    foreach (preg_split('/\n\n+/', trim($cv->formation)) as $blk) {
      $lines = explode("\n", trim($blk));
      if (!trim($lines[0])) continue;
      $header    = trim($lines[0]);
      $activites = [];
      if (isset($lines[1]) && str_starts_with(trim($lines[1]), 'Activités : ')) {
        $activites = array_values(array_filter(array_map('trim', explode(',', substr(trim($lines[1]), 12)))));
      }
      $parts  = explode(' | ', $header);
      $first  = array_shift($parts);
      $diplome = trim($first);
      $etabl   = '';
      if (str_contains($first, ' — ')) {
        [$diplome, $etabl] = explode(' — ', $first, 2);
        $diplome = trim($diplome);
        $etabl   = trim($etabl);
      }
      $periode = '';
      if (count($parts) > 0) {
        $last = end($parts);
        if (str_contains($last, '→') || str_contains($last, 'en cours')) {
          $periode = array_pop($parts);
        }
      }
      $domaine = count($parts) ? trim(array_shift($parts)) : '';
      $formBlocks[] = compact('diplome', 'etabl', 'domaine', 'periode', 'activites');
    }
  }

  // ── Compétences ──
  $competencesList = $cv->competences
    ? array_values(array_filter(array_map('trim', explode(',', $cv->competences))))
    : [];

  // ── Langues ──
  $languesItems = [];
  if ($cv->langues) {
    foreach (explode(',', $cv->langues) as $l) {
      $l = trim($l);
      if (!$l) continue;
      if (preg_match('/^(.+?)\s*\((.+?)\)$/', $l, $m)) {
        $languesItems[] = ['nom' => trim($m[1]), 'niveau' => trim($m[2])];
      } else {
        $languesItems[] = ['nom' => $l, 'niveau' => ''];
      }
    }
  }
@endphp

{{-- ══════════ HERO ══════════ --}}
<section class="section page-hero">
  <div class="container page-hero__inner">
    <span class="badge badge--blue">CVthèque</span>
    <h1 class="page-hero__title">{{ $cv->metier ?? 'Profil candidat' }}</h1>
    @if($cv->ville)
      <p class="page-hero__subtitle">
        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="vertical-align:-1px"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><circle cx="12" cy="11" r="3"/></svg>
        {{ $cv->ville }}
      </p>
    @endif
  </div>
</section>

{{-- ══════════ SOUS-NAV ══════════ --}}
<div class="cvt-subnav">
  <div class="cvt-subnav__inner">
    <a href="{{ route('cv.public.theque') }}" class="cvt-subnav__link">Trouver des CV</a>
    <a href="{{ route('cv.public.tarif') }}"  class="cvt-subnav__link">Packs crédits</a>
    @if(!auth()->check() || auth()->user()->hasRole(\App\Enums\Role::CANDIDAT))
      <a href="{{ auth()->check() && auth()->user()->hasRole(\App\Enums\Role::CANDIDAT) ? route('cv.public.depot') : route('auth.inscription').'?role=candidat' }}" class="cvt-subnav__link">Déposer un CV</a>
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
        <div class="cvtd-avatar" style="position:relative;overflow:hidden">
          @if($cv->photo)
            <img src="{{ asset('storage/' . $cv->photo) }}" alt="{{ $cv->metier ?? 'Candidat' }}" style="filter:blur(7px);transform:scale(1.1)">
            <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;background:rgba(4,44,83,.25)">
              <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="rgba(255,255,255,.9)" stroke-width="2.2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            </div>
          @else
            <svg width="30" height="30" fill="none" viewBox="0 0 24 24" stroke="rgba(255,255,255,.7)" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
          @endif
        </div>
        <div class="cvtd-head-info">
          <h2 class="cvtd-head-title">{{ $cv->metier ?? 'Profil candidat' }}</h2>
          @if($cv->ville)
            <p class="cvtd-head-meta">
              <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
              {{ $cv->ville }}
            </p>
          @endif
          <div class="cvd-head-badges">
            @if($cv->plan === 'premium')
              <span class="cvd-head-badge" style="background:rgba(245,200,66,.25);border-color:rgba(245,200,66,.4)">
                <svg width="10" height="10" viewBox="0 0 24 24" fill="#F5C842" style="display:inline-block;vertical-align:-1px;margin-right:3px"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                Premium
              </span>
            @endif
            @if($cv->niveau_experience)
              <span class="cvd-head-badge">{{ $cv->niveau_experience }}</span>
            @endif
            @if($cv->niveau_etude)
              <span class="cvd-head-badge">{{ $cv->niveau_etude }}</span>
            @endif
            @if($cv->type_contrat)
              @foreach(explode(',', $cv->type_contrat) as $tc)
                @if(trim($tc))
                  <span class="cvd-head-badge">{{ trim($tc) }}</span>
                @endif
              @endforeach
            @endif
          </div>
        </div>
      </div>

      {{-- Corps structuré --}}
      <div class="cvtd-profile__body">

        {{-- Résumé --}}
        @if($cv->resume)
        <div class="cvd-section">
          <div class="cvd-section-title">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Résumé professionnel
          </div>
          <div class="cvd-resume">{{ $cv->resume }}</div>
        </div>
        @endif

        {{-- Expériences --}}
        @if(count($expBlocks) > 0)
        <div class="cvd-section">
          <div class="cvd-section-title">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>
            Expériences professionnelles
          </div>
          <div class="cvd-timeline">
            @foreach($expBlocks as $exp)
            <div class="cvd-card">
              <div class="cvd-card-header">
                <div class="cvd-card-icon">
                  <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>
                </div>
                <div class="cvd-card-info">
                  <div class="cvd-card-title">{{ $exp['poste'] }}</div>
                  @if($exp['entrep'])
                    <div class="cvd-card-sub">{{ $exp['entrep'] }}</div>
                  @endif
                  <div class="cvd-card-meta">
                    @if($exp['periode'])
                      <span class="cvd-card-pill">
                        <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        {{ $exp['periode'] }}
                      </span>
                    @endif
                    @if($exp['lieu'])
                      <span class="cvd-card-pill">
                        <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                        {{ $exp['lieu'] }}
                      </span>
                    @endif
                    @if($exp['secteur'])
                      <span class="cvd-card-pill">{{ $exp['secteur'] }}</span>
                    @endif
                  </div>
                </div>
              </div>
              @if(count($exp['missions']) > 0)
                <ul class="cvd-card-missions">
                  @foreach($exp['missions'] as $mission)
                    <li>{{ $mission }}</li>
                  @endforeach
                </ul>
              @endif
            </div>
            @endforeach
          </div>
        </div>
        @elseif($cv->experience)
        <div class="cvd-section">
          <div class="cvd-section-title">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>
            Expériences professionnelles
          </div>
          <div class="cvd-resume" style="white-space:pre-wrap">{{ $cv->experience }}</div>
        </div>
        @endif

        {{-- Formations --}}
        @if(count($formBlocks) > 0)
        <div class="cvd-section">
          <div class="cvd-section-title">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
            Formations & Diplômes
          </div>
          <div class="cvd-timeline">
            @foreach($formBlocks as $form)
            <div class="cvd-card">
              <div class="cvd-card-header">
                <div class="cvd-card-icon" style="background:#fef3c7">
                  <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#d97706" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
                </div>
                <div class="cvd-card-info">
                  <div class="cvd-card-title">{{ $form['diplome'] }}</div>
                  @if($form['etabl'])
                    <div class="cvd-card-sub" style="color:#d97706">{{ $form['etabl'] }}</div>
                  @endif
                  <div class="cvd-card-meta">
                    @if($form['periode'])
                      <span class="cvd-card-pill">
                        <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                        {{ $form['periode'] }}
                      </span>
                    @endif
                    @if($form['domaine'])
                      <span class="cvd-card-pill">{{ $form['domaine'] }}</span>
                    @endif
                  </div>
                </div>
              </div>
              @if(count($form['activites']) > 0)
                <ul class="cvd-card-missions">
                  @foreach($form['activites'] as $activite)
                    <li>{{ $activite }}</li>
                  @endforeach
                </ul>
              @endif
            </div>
            @endforeach
          </div>
        </div>
        @elseif($cv->formation)
        <div class="cvd-section">
          <div class="cvd-section-title">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
            Formations & Diplômes
          </div>
          <div class="cvd-resume" style="white-space:pre-wrap">{{ $cv->formation }}</div>
        </div>
        @endif

        {{-- Compétences --}}
        @if(count($competencesList) > 0)
        <div class="cvd-section">
          <div class="cvd-section-title">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
            Compétences
          </div>
          <div class="cvd-chips">
            @foreach($competencesList as $comp)
              <span class="cvd-chip">{{ $comp }}</span>
            @endforeach
          </div>
        </div>
        @endif

        {{-- Langues --}}
        @if(count($languesItems) > 0)
        <div class="cvd-section">
          <div class="cvd-section-title">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
            Langues
          </div>
          <div class="cvd-lang-list">
            @foreach($languesItems as $lang)
            <div class="cvd-lang-item">
              <span class="cvd-lang-name">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#94a3b8" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg>
                {{ $lang['nom'] }}
              </span>
              @if($lang['niveau'])
                <span class="cvd-lang-level">{{ $lang['niveau'] }}</span>
              @endif
            </div>
            @endforeach
          </div>
        </div>
        @endif

      </div>{{-- /body --}}
    </div>{{-- /profile --}}

    {{-- ── COORDONNÉES VERROUILLÉES ── --}}
    <div class="cvtd-locked">
      <div class="cvtd-locked__icon">
        <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#94a3b8" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
      </div>
      <h3 class="cvtd-locked__title">Coordonnées confidentielles</h3>
      <p class="cvtd-locked__desc">
        Le nom, l'email et le téléphone de ce candidat sont protégés.<br>
        @auth
          @if(auth()->user()->hasRole(\App\Enums\Role::RECRUTEUR))
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
          @if(auth()->user()->hasRole(\App\Enums\Role::RECRUTEUR))
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
