@extends('layouts.app')
@section('title', 'Emploi Bouge Bénin — Offres d\'emploi, stages, bourses au Bénin')
@section('description', 'Plateforme emploi au Bénin. Trouvez des offres vérifiées, déposez votre CV, valorisez vos compétences.')

@section('css')
<link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')

{{-- ═══════════════════════════════════════════
     HERO
═══════════════════════════════════════════ --}}
<section id="accueil" class="hero">
  <div class="hero-container">

    <div class="hero-left">
      <h1 class="hero-title">
        Le pont entre les <span class="accent">recruteurs</span> et les <span class="accent">talents</span>
      </h1>
      <p class="hero-sub">
        Les recruteurs diffusent leurs annonces, les candidats trouvent
        <strong>emploi</strong>, <strong>stage</strong>, <strong>bourse</strong>,
        <strong>freelance</strong> ou mettent en avant leurs <strong>compétences</strong> — peu importe le profil.<br>
        Informations vérifiées, plateforme intuitive, mises à jour en continu.
      </p>

      <div class="hs2-wrap">
        <div class="hs2-card">
          <div class="hs2-row">
            <div class="hs2-input-wrap">
              <svg class="hs2-input-icon" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
              <input type="text" id="heroSearch" class="hs2-input" placeholder="Titre de poste, compétences, entreprise…" autocomplete="off" />
            </div>
            <button class="hs2-btn" id="heroSearchBtn">
              <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
              <span>Rechercher</span>
            </button>
          </div>
        </div>
      </div>

      <div class="hero-actions">
        <a href="{{ route('offre.list') }}" class="btn-primary">
          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
          Parcourir les offres
        </a>
        @if(!auth()->check() || auth()->user()->hasRole('candidat'))
          <a href="{{ route('cv.public.depot') }}" class="btn-hero-cv">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
            Déposer mon CV
          </a>
        @endif
        @if(!auth()->check() || auth()->user()->hasRole('recruteur'))
          <a href="{{ route('offre.publier') }}" class="btn-hero-outline">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Publier une annonce
          </a>
        @endif
      </div>
    </div>

    <div class="hero-right">
      <div class="hero-visual">
        <svg viewBox="0 0 460 420" xmlns="http://www.w3.org/2000/svg" class="hero-svg">
          <circle cx="230" cy="210" r="185" fill="#EBF4FD" opacity="0.55"/>
          <circle cx="230" cy="210" r="138" fill="#378ADD" opacity="0.05"/>
          <rect x="55" y="292" width="350" height="13" rx="6.5" fill="#185FA5" opacity="0.18"/>
          <rect x="95"  y="303" width="11" height="38" rx="4" fill="#378ADD" opacity="0.18"/>
          <rect x="354" y="303" width="11" height="38" rx="4" fill="#378ADD" opacity="0.18"/>
          <rect x="138" y="208" width="184" height="118" rx="10" fill="#042C53"/>
          <rect x="146" y="216" width="168" height="103" rx="6" fill="#185FA5"/>
          <rect x="153" y="223" width="154" height="89" rx="4" fill="#EBF4FD"/>
          <rect x="153" y="223" width="154" height="17" rx="4" fill="#378ADD"/>
          <text x="230" y="234" text-anchor="middle" font-family="Jost,sans-serif" font-size="6.5" font-weight="600" fill="white">Tableau de bord recruteur</text>
          <rect x="158" y="245" width="64" height="32" rx="4" fill="white"/>
          <circle cx="170" cy="256" r="7" fill="#378ADD" opacity="0.25"/>
          <rect x="180" y="251" width="33" height="4" rx="2" fill="#042C53" opacity="0.55"/>
          <rect x="180" y="258" width="24" height="3" rx="1.5" fill="#A0AEC0"/>
          <rect x="162" y="268" width="18" height="5" rx="2.5" fill="#F5C842"/>
          <text x="171" y="272" text-anchor="middle" font-family="Jost,sans-serif" font-size="4.5" font-weight="700" fill="#6B4800">CDI</text>
          <rect x="228" y="245" width="64" height="32" rx="4" fill="white"/>
          <circle cx="240" cy="256" r="7" fill="#F5C842" opacity="0.4"/>
          <rect x="250" y="251" width="33" height="4" rx="2" fill="#042C53" opacity="0.55"/>
          <rect x="250" y="258" width="24" height="3" rx="1.5" fill="#A0AEC0"/>
          <rect x="232" y="268" width="22" height="5" rx="2.5" fill="#378ADD"/>
          <text x="243" y="272" text-anchor="middle" font-family="Jost,sans-serif" font-size="4.5" font-weight="700" fill="white">Stage</text>
          <rect x="130" y="254" width="60" height="76" rx="5" fill="white" stroke="#378ADD" stroke-width="1.5"/>
          <rect x="130" y="254" width="60" height="18" rx="5" fill="#378ADD"/>
          <text x="160" y="266" text-anchor="middle" font-family="Jost,sans-serif" font-size="6" font-weight="700" fill="white">CV</text>
          <rect x="136" y="278" width="36" height="3.5" rx="1.5" fill="#042C53" opacity="0.6"/>
          <rect x="136" y="285" width="46" height="3" rx="1.5" fill="#A0AEC0"/>
          <rect x="136" y="291" width="40" height="3" rx="1.5" fill="#A0AEC0"/>
          <circle cx="178" cy="316" r="9" fill="#38A169"/>
          <path d="M172 316 L176 320 L184 310" stroke="white" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
          <polygon points="422,125 425,136 436,136 427,143 430,154 422,147 414,154 417,143 408,136 419,136" fill="#F5C842" opacity="0.85"/>
          <circle cx="230" cy="72" r="7" fill="#F5C842" opacity="0.45"/>
        </svg>
      </div>
    </div>

  </div>
</section>

{{-- ═══════════════════════════════════════════
     DOUBLE CTA
═══════════════════════════════════════════ --}}
<section class="split-cta-section">
  <div class="split-cta-inner">
    <span class="split-cta-eyebrow">
      <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
      Par où commencer ?
    </span>
    <h2 class="split-cta-title">Candidat ou recruteur ?</h2>
    <p class="split-cta-sub">Que vous cherchiez un emploi, un stage, une bourse ou que vous souhaitiez mettre en avant vos compétences — une seule plateforme pour tout.</p>
    <div class="split-cta-btns">
      @if(!auth()->check() || auth()->user()->hasRole('candidat'))
        <a href="{{ route('cv.public.depot') }}" class="split-cta-btn split-cta-btn--candidat">
          <span class="split-cta-btn__icon"><svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></span>
          <span class="split-cta-btn__title">Je cherche du travail</span>
          <span class="split-cta-btn__sub">CDI · CDD · Stage · Bourse · Freelance · Compétences</span>
        </a>
      @endif
      @if(!auth()->check() || auth()->user()->hasRole('recruteur'))
        <a href="{{ route('offre.publier') }}" class="split-cta-btn split-cta-btn--recruteur">
          <span class="split-cta-badge">Recommandé</span>
          <span class="split-cta-btn__icon"><svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></span>
          <span class="split-cta-btn__title">Je recrute</span>
          <span class="split-cta-btn__sub">Publiez une annonce, accédez aux CV et profils</span>
        </a>
      @endif
    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════
     OBSTACLES
═══════════════════════════════════════════ --}}
<section class="section section--blue-bg">
  <div class="container">
    <div class="section-header section-header--center">
      <h2 class="section-title section-title--white">Obstacles rencontrés par les jeunes Africains</h2>
      <p class="section-subtitle section-subtitle--white">Quatre principaux défis limitent l'accès aux opportunités en Afrique</p>
    </div>
    <div class="cards-grid cards-grid--2col">
      @foreach([
        ['M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'Informations peu fiables sur les opportunités', 'Opportunités non vérifiées, sources obsolètes, données dispersées.'],
        ['M13 10V3L4 14h7v7l9-11h-7z', 'Démarches administratives complexes', 'Procédures longues, exigences floues, risques d\'erreur.'],
        ['M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-4a4 4 0 100-8 4 4 0 000 8z', 'Ressources hors contexte africain', 'Contenus inadaptés aux réalités socio-économiques locales.'],
        ['M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'Manque de réseau et d\'accompagnement', 'Pas de mentorat, isolement, conseils génériques.'],
      ] as [$path, $titre, $desc])
      <div class="obstacle-card">
        <div class="card-icon-circle">
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $path }}"/>
          </svg>
        </div>
        <div class="card-body">
          <h3 class="card-title">{{ $titre }}</h3>
          <p class="card-desc">{{ $desc }}</p>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════
     NOS SERVICES
═══════════════════════════════════════════ --}}
<section id="services" class="section services-section">
  <div class="container">
    <div class="section-header section-header--center">
      <span class="badge badge--yellow">Nos services</span>
      <h2 class="section-title">Nos services d'<span class="text-accent">accompagnement</span></h2>
      <p class="section-subtitle">Tout ce dont vous avez besoin pour décrocher votre opportunité professionnelle.</p>
    </div>

    <div class="formation-section">
      <article class="formation-card">
        <div class="formation-card__left">
          <span class="formation-card__tag">Service n°1 — Très demandé</span>
          <h3 class="formation-card__title">
            CV Professionnel <em>&amp; Lettre de Motivation</em><br>
            <span style="font-size:0.75em;color:#64748b;font-weight:600;">Arrêtez de postuler dans le vide.</span>
          </h3>
          <p class="formation-card__hook">
            Un recruteur décide en <strong>7 secondes</strong>. Nos experts rédigent pour vous un CV percutant et une lettre convaincante <strong>adaptés au marché africain</strong>.
          </p>
          <ul class="formation-card__features">
            @foreach(['Analyse complète de votre profil et de vos objectifs professionnels','CV structuré, moderne et optimisé pour passer les filtres ATS','Lettre de motivation personnalisée et convaincante','Livraison Word &amp; PDF prêt à l\'emploi sous 48h','1 révision gratuite incluse après livraison'] as $f)
            <li class="formation-card__feature">
              <span class="formation-card__check"><svg width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span>
              {!! $f !!}
            </li>
            @endforeach
          </ul>
          <div class="formation-card__actions">
            <a href="{{ route('service.commande', 'cv-professionnel') }}" class="fc-btn-primary">
              Je veux mon CV professionnel
              <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
            <a href="{{ route('service.detail', 'cv-professionnel') }}" class="fc-btn-outline">Voir les détails</a>
          </div>
        </div>
        <div class="formation-card__right">
          <span class="formation-card__badge-hot">+500 commandes</span>
          <div class="formation-card__price-box">
            <span class="formation-card__price-label">Seulement</span>
            <span class="formation-card__price">2 500</span>
            <span class="formation-card__currency">FCFA tout compris</span>
          </div>
          <div class="formation-card__delivery">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Livré sous 48h
          </div>
          <div class="formation-card__guarantee">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            Satisfaction garantie · 1 révision offerte
          </div>
        </div>
      </article>
    </div>

    <div class="services-cta">
      <a href="{{ route('service.list') }}" class="btn btn--yellow">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        Découvrir tous nos services
      </a>
    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════
     DERNIÈRES OFFRES
═══════════════════════════════════════════ --}}
<section id="offres" class="section offres-section">
  <div class="container">
    <div class="offres-header">
      <div>
        <span class="badge badge--yellow">Opportunités</span>
        <h2 class="section-title" style="margin-top:10px">Dernières offres publiées</h2>
        <p class="section-subtitle">Les offres les plus récentes, mises à jour en continu.</p>
      </div>
      <a href="{{ route('offre.list') }}" class="btn btn--blue offres-header__cta" style="color: #F5C842;">
        Voir toutes les offres
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
      </a>
    </div>

    <div class="oic-grid">
      @forelse($offres as $offre)
        @php $typeKey = strtolower(str_replace([' ', '-'], '', $offre->type)); @endphp
        <a href="{{ route('offre.detail', $offre) }}" class="oic-card">

          <div class="oic-card__head">
            <div class="oic-card__avatar">
              {{ strtoupper(substr($offre->entreprise, 0, 2)) }}
            </div>
            <div class="oic-card__info">
              <div class="oic-card__title">{{ $offre->titre }}</div>
              <div class="oic-card__company">{{ $offre->entreprise }}</div>
            </div>
          </div>

          <div class="oic-card__loc">
            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><circle cx="12" cy="11" r="3" stroke-linecap="round"/></svg>
            {{ $offre->localisation }}
          </div>

          <div>
            <span class="oic-badge oic-badge--{{ $typeKey }}">{{ $offre->type }}</span>
          </div>

          <div class="oic-card__footer">
            <span class="oic-card__date">{{ $offre->created_at->diffForHumans() }}</span>
            <span class="oic-card__cta">
              Voir l'offre
              <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </span>
          </div>

        </a>
      @empty
        <p style="color:#64748b;padding:32px 0;text-align:center">Aucune offre publiée pour l'instant.</p>
      @endforelse
    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════
     DERNIERS CV
═══════════════════════════════════════════ --}}
<section class="cv-section">
  <div class="container">
    <div class="offres-header">
      <div>
        <span class="badge badge--yellow">CV récents</span>
        <h2 class="section-title" style="margin-top:10px">Derniers CV déposés</h2>
        <p class="section-subtitle">Des candidats actifs — emploi, stage, bourse ou freelance.</p>
      </div>
      <a href="{{ route('cv.public.theque') }}" class="btn btn--blue offres-header__cta" style="color: #F5C842;">
        Accéder aux CV
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
      </a>
    </div>

    @php $cvBgs = ['#dbeafe','#fce7f3','#d1fae5','#fef9c3','#ede9fe','#dcfce7']; @endphp

    <div class="lo-list" id="cvGrid">
      @foreach($cvs as $j => $cv)
      <div class="cvt-card">
        <div class="cvt-card__inner">
          <div class="cvt-card__id">Profil n°{{ str_pad($cv->id, 8, '0', STR_PAD_LEFT) }}</div>
          <div class="cvt-card__body">
            <div class="cvt-card__photo" style="background:{{ $cvBgs[$j % count($cvBgs)] }}">
              <svg class="cvt-card__photo-icon" xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
                <path d="M12 12c2.7 0 4.8-2.15 4.8-4.8S14.7 2.4 12 2.4 7.2 4.55 7.2 7.2 9.3 12 12 12zm0 2.4c-3.21 0-9.6 1.61-9.6 4.8v2.4h19.2v-2.4c0-3.19-6.39-4.8-9.6-4.8z"/>
              </svg>
            </div>
            <div class="cvt-card__info">
              <div class="cvt-card__row">
                <span class="cvt-card__label">Secteur :</span>
                <span class="cvt-card__val">{{ $cv->titre_poste }} · {{ $cv->pays }}{{ $cv->ville ? ' — '.$cv->ville : '' }}</span>
              </div>
              @if($cv->experience)
              <div class="cvt-card__row">
                <span class="cvt-card__label">Expérience :</span>
                <span class="cvt-card__val">{{ $cv->experience }}</span>
              </div>
              @endif
              @if($cv->formation)
              <div class="cvt-card__formation-name">{{ Str::limit($cv->formation, 60) }}</div>
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
                <span class="cvt-card__val">{{ Str::limit($cv->competences, 80) }}</span>
              </div>
              @endif
            </div>
          </div>
          <div class="cvt-card__footer">
            <a href="{{ route('cv.public.theque') }}" class="cvt-card__btn">
              Voir le CV
              <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════
     WHATSAPP
═══════════════════════════════════════════ --}}
<section class="section whatsapp-section">
  <div class="container">
    <div class="whatsapp-card">
      <div class="whatsapp-card__icon-wrap">
        <span class="whatsapp-pulse" aria-hidden="true"></span>
        <div class="whatsapp-card__icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" fill="currentColor">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
          </svg>
        </div>
      </div>
      <div class="whatsapp-card__content">
        <h2 class="whatsapp-card__title">Rejoignez notre communauté WhatsApp</h2>
        <p class="whatsapp-card__text">Recevez les dernières opportunités en temps réel et échangez avec des centaines de jeunes ambitieux.</p>
        <a href="https://whatsapp.com/channel/0029VbCGlUo5q08ZH1bnm11F" target="_blank" rel="noopener noreferrer" class="btn btn--whatsapp">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347"/></svg>
          Rejoindre notre chaîne WhatsApp
        </a>
      </div>
      <div class="whatsapp-stats">
        <div class="whatsapp-stat">
          <span class="whatsapp-stat__num">2 500+</span>
          <span class="whatsapp-stat__label">Membres actifs</span>
        </div>
        <div class="whatsapp-stat__divider"></div>
        <div class="whatsapp-stat">
          <span class="whatsapp-stat__num">Emploi</span>
          <span class="whatsapp-stat__label">Nouvelles offres</span>
        </div>
        <div class="whatsapp-stat__divider"></div>
        <div class="whatsapp-stat">
          <span class="whatsapp-stat__num">Gratuit</span>
          <span class="whatsapp-stat__label">Accès libre</span>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════
     BLOG
═══════════════════════════════════════════ --}}
<section class="section home-blog-section">
  <div class="container">
    <div class="home-blog__header">
      <div class="home-blog__header-left">
        <span class="badge badge--yellow">Blog &amp; Conseils</span>
        <h2 class="section-title" style="margin-top:10px">Derniers articles</h2>
        <p class="section-subtitle">Conseils carrière, guides pratiques et opportunités pour réussir sur le marché africain.</p>
      </div>
      <a href="{{ route('blog.list') }}" class="home-blog__see-all">
        Tous les articles
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
      </a>
    </div>

    <div class="articles-grid">
      @foreach($articles as $article)
      <article class="article-card">
        <a href="{{ route('blog.detail', $article) }}" class="article-card__cover" tabindex="-1">
          <span class="article-card__category">{{ $article->categorie }}</span>
          @if($article->image)
            <img src="{{ asset('storage/'.$article->image) }}" alt="{{ $article->titre }}" class="card__img" loading="lazy"/>
          @else
            <img src="https://placehold.co/600x340/EEF4FF/2563eb?text={{ urlencode($article->categorie ?? 'Blog') }}" alt="{{ $article->titre }}" class="card__img" loading="lazy"/>
          @endif
        </a>
        <div class="article-card__body">
          <div class="article-card__meta">
            <span class="article-card__date">{{ $article->publie_le?->format('d M Y') }}</span>
            <span class="article-card__dot"></span>
            <span class="article-card__read">{{ $article->temps_lecture }} min</span>
            <span class="article-card__dot"></span>
            <span class="article-card__views">
              <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              <span class="blog-read-count" data-init="{{ $article->vues }}">{{ number_format($article->vues) }}</span> lectures
            </span>
          </div>
          <h3 class="article-card__title">{{ $article->titre }}</h3>
          <p class="article-card__text">{{ $article->extrait }}</p>
          <a href="{{ route('blog.detail', $article) }}" class="article-card__link">
            Lire l'article
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
          </a>
        </div>
      </article>
      @endforeach
    </div>

    <div class="articles-cta">
      <a href="{{ route('blog.list') }}" class="btn btn--outline-blue" style="color: #F5C842;">
        Voir tous les articles
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
      </a>
    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════
     ANNONCEURS
═══════════════════════════════════════════ --}}
<section class="section" style="background:linear-gradient(135deg,#042C53 0%,#185FA5 100%);padding:72px 0">
  <div class="container">
    <div class="annonceur-grid">

      {{-- Texte gauche --}}
      <div>
        <span style="display:inline-flex;align-items:center;gap:7px;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#F5C842;margin-bottom:16px">
          <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
          Pour les entreprises
        </span>
        <h2 style="font-size:2rem;font-weight:800;color:#fff;line-height:1.25;margin:0 0 18px">
          Vous avez un message à faire passer ?<br>
          <em style="font-style:normal;color:#F5C842">La plateforme est aussi pour les annonceurs.</em>
        </h2>
        <p style="font-size:14.5px;color:rgba(255,255,255,.75);line-height:1.7;margin:0 0 28px">
          Qu'il s'agisse de promouvoir une marque employeur, de diffuser une campagne ou de cibler des candidats qualifiés au Bénin — Emploi Bouge met votre message devant la bonne audience.
        </p>
        <div style="display:flex;flex-wrap:wrap;gap:12px">
          <a href="{{ route('auth.inscription') }}?role=annonceur"
             style="display:inline-flex;align-items:center;gap:8px;background:#F5C842;color:#042C53;padding:13px 26px;border-radius:10px;font-weight:800;font-size:14px;text-decoration:none">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Créer un compte annonceur
          </a>
          <a href="{{ route('contact') }}"
             style="display:inline-flex;align-items:center;gap:8px;background:rgba(255,255,255,.12);color:#fff;border:1.5px solid rgba(255,255,255,.3);padding:13px 26px;border-radius:10px;font-weight:700;font-size:14px;text-decoration:none">
            Nous contacter
          </a>
        </div>
      </div>

      {{-- Cartes avantages --}}
      <div class="annonceur-cards">

        <div class="annonceur-card">
          <div class="annonceur-card__icon">
            {{-- Mégaphone --}}
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#F5C842" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
          </div>
          <p class="annonceur-card__title">Visibilité ciblée</p>
          <p class="annonceur-card__desc">Votre message vu par des milliers de candidats actifs et de recruteurs.</p>
        </div>

        <div class="annonceur-card">
          <div class="annonceur-card__icon">
            {{-- Graphique barres --}}
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#F5C842" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
          </div>
          <p class="annonceur-card__title">Formats variés</p>
          <p class="annonceur-card__desc">Bannières, mises en avant sponsorisées, emplacements premium.</p>
        </div>

        <div class="annonceur-card">
          <div class="annonceur-card__icon">
            {{-- Cible --}}
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#F5C842" stroke-width="2"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
          </div>
          <p class="annonceur-card__title">Audience qualifiée</p>
          <p class="annonceur-card__desc">Professionnels en recherche active, employeurs, étudiants.</p>
        </div>

        <div class="annonceur-card">
          <div class="annonceur-card__icon">
            {{-- Éclair / rapidité --}}
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#F5C842" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
          </div>
          <p class="annonceur-card__title">Mise en ligne rapide</p>
          <p class="annonceur-card__desc">Campagne active en moins de 24h après validation.</p>
        </div>

      </div>

    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════
     PLANS INFORMATIFS
═══════════════════════════════════════════ --}}
<section class="section" style="background:#f8fafc;padding:80px 0">
  <div class="container">
    <div class="section-header section-header--center" style="margin-bottom:48px">
      <span class="badge badge--yellow">Abonnements</span>
      <h2 class="section-title" style="margin-top:10px">Des plans pour chaque profil</h2>
      <p class="section-subtitle">Accès gratuit ou premium — à vous de choisir selon vos besoins.</p>
    </div>

    {{-- Toggle candidat / recruteur --}}
    <div style="display:flex;justify-content:center;margin-bottom:36px">
      <div style="display:inline-flex;background:#e2e8f0;border-radius:10px;padding:4px;gap:4px">
        <button onclick="showPlans('candidat')" id="tab-candidat"
                style="padding:9px 24px;border-radius:8px;border:none;font-size:13.5px;font-weight:700;cursor:pointer;background:#042C53;color:#fff;transition:all .2s">
          Candidats
        </button>
        <button onclick="showPlans('recruteur')" id="tab-recruteur"
                style="padding:9px 24px;border-radius:8px;border:none;font-size:13.5px;font-weight:700;cursor:pointer;background:transparent;color:#64748b;transition:all .2s">
          Recruteurs
        </button>
        <button onclick="showPlans('annonceur')" id="tab-annonceur"
                style="padding:9px 24px;border-radius:8px;border:none;font-size:13.5px;font-weight:700;cursor:pointer;background:transparent;color:#64748b;transition:all .2s">
          Annonceurs
        </button>
      </div>
    </div>

    {{-- Plans candidats --}}
    <div id="plans-candidat" style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:20px;max-width:760px;margin:0 auto">
      @foreach($plansCandidats as $plan)
      @php
        $isPop = !$plan->is_free;
        $features = $plan->features->keyBy('feature_key');
        $cvLimit    = $features->get('cv_limit')?->feature_value ?? '1';
        $applyLimit = $features->get('job_apply_limit')?->feature_value ?? '10';
        $featured   = (int)($features->get('featured_profile')?->feature_value ?? 0) > 0;
      @endphp
      <div style="background:#fff;border:2px solid {{ $isPop ? '#185FA5' : '#e2e8f0' }};border-radius:16px;padding:28px 24px;position:relative;{{ $isPop ? 'box-shadow:0 8px 32px rgba(24,95,165,.15)' : '' }}">
        @if($isPop)
          <span style="position:absolute;top:-12px;left:50%;transform:translateX(-50%);background:#185FA5;color:#fff;font-size:11px;font-weight:700;padding:3px 14px;border-radius:20px;white-space:nowrap">Plus populaire</span>
        @endif
        <p style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:{{ $isPop ? '#185FA5' : '#94a3b8' }};margin:0 0 6px">{{ $plan->name }}</p>
        <div style="margin-bottom:20px">
          @if($plan->is_free)
            <span style="font-size:2rem;font-weight:800;color:#042C53">Gratuit</span>
          @else
            <span style="font-size:2rem;font-weight:800;color:#042C53">{{ number_format($plan->price, 0, ',', ' ') }}</span>
            <span style="font-size:14px;color:#64748b;font-weight:600"> FCFA{{ $plan->duration_days ? ' / '.$plan->duration_days.'j' : '' }}</span>
          @endif
        </div>
        <ul style="list-style:none;padding:0;margin:0 0 24px;display:flex;flex-direction:column;gap:10px">
          <li style="display:flex;align-items:center;gap:10px;font-size:13.5px;color:#374151">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="{{ $isPop ? '#185FA5' : '#22c55e' }}" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            {{ $cvLimit === '0' ? 'Documents illimités' : $cvLimit.' document(s) max' }}
          </li>
          <li style="display:flex;align-items:center;gap:10px;font-size:13.5px;color:#374151">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="{{ $isPop ? '#185FA5' : '#22c55e' }}" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            {{ $applyLimit >= 100 ? 'Candidatures illimitées' : $applyLimit.' candidatures / mois' }}
          </li>
          @if($featured)
          <li style="display:flex;align-items:center;gap:10px;font-size:13.5px;color:#374151">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="{{ $isPop ? '#185FA5' : '#22c55e' }}" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            Profil mis en avant dans la CVthèque
          </li>
          @endif
          <li style="display:flex;align-items:center;gap:10px;font-size:13.5px;color:#374151">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="{{ $isPop ? '#185FA5' : '#22c55e' }}" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            Accès aux offres d'emploi
          </li>
        </ul>
        <a href="{{ route('auth.inscription') }}"
           style="display:block;text-align:center;padding:11px;border-radius:9px;font-weight:700;font-size:13.5px;text-decoration:none;background:{{ $isPop ? '#185FA5' : '#f1f5f9' }};color:{{ $isPop ? '#fff' : '#042C53' }}">
          {{ $plan->is_free ? 'Commencer gratuitement' : 'Choisir ce plan' }}
        </a>
      </div>
      @endforeach
    </div>

    {{-- Plans recruteurs --}}
    <div id="plans-recruteur" style="display:none;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:20px;max-width:760px;margin:0 auto">
      @foreach($plansRecruteurs as $plan)
      @php
        $isPop = !$plan->is_free;
        $features = $plan->features->keyBy('feature_key');
        $postLimit   = $features->get('job_post_limit')?->feature_value ?? '2';
        $candSearch  = (int)($features->get('candidate_search')?->feature_value ?? 0) > 0;
        $featuredJ   = $features->get('featured_jobs')?->feature_value ?? '0';
      @endphp
      <div style="background:#fff;border:2px solid {{ $isPop ? '#185FA5' : '#e2e8f0' }};border-radius:16px;padding:28px 24px;position:relative;{{ $isPop ? 'box-shadow:0 8px 32px rgba(24,95,165,.15)' : '' }}">
        @if($isPop)
          <span style="position:absolute;top:-12px;left:50%;transform:translateX(-50%);background:#185FA5;color:#fff;font-size:11px;font-weight:700;padding:3px 14px;border-radius:20px;white-space:nowrap">Recommandé</span>
        @endif
        <p style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:{{ $isPop ? '#185FA5' : '#94a3b8' }};margin:0 0 6px">{{ $plan->name }}</p>
        <div style="margin-bottom:20px">
          @if($plan->is_free)
            <span style="font-size:2rem;font-weight:800;color:#042C53">Gratuit</span>
          @else
            <span style="font-size:2rem;font-weight:800;color:#042C53">{{ number_format($plan->price, 0, ',', ' ') }}</span>
            <span style="font-size:14px;color:#64748b;font-weight:600"> FCFA{{ $plan->duration_days ? ' / '.$plan->duration_days.'j' : '' }}</span>
          @endif
        </div>
        <ul style="list-style:none;padding:0;margin:0 0 24px;display:flex;flex-direction:column;gap:10px">
          <li style="display:flex;align-items:center;gap:10px;font-size:13.5px;color:#374151">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="{{ $isPop ? '#185FA5' : '#22c55e' }}" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            {{ $postLimit }} offre{{ (int)$postLimit > 1 ? 's' : '' }} d'emploi max
          </li>
          <li style="display:flex;align-items:center;gap:10px;font-size:13.5px;color:#374151">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="{{ $candSearch ? ($isPop ? '#185FA5' : '#22c55e') : '#cbd5e1' }}" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $candSearch ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12' }}"/></svg>
            <span style="color:{{ $candSearch ? '#374151' : '#94a3b8' }}">Recherche de candidats {{ $candSearch ? 'incluse' : 'non incluse' }}</span>
          </li>
          @if((int)$featuredJ > 0)
          <li style="display:flex;align-items:center;gap:10px;font-size:13.5px;color:#374151">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="{{ $isPop ? '#185FA5' : '#22c55e' }}" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            {{ $featuredJ }} offre{{ (int)$featuredJ > 1 ? 's' : '' }} mise{{ (int)$featuredJ > 1 ? 's' : '' }} en avant
          </li>
          @endif
          <li style="display:flex;align-items:center;gap:10px;font-size:13.5px;color:#374151">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="{{ $isPop ? '#185FA5' : '#22c55e' }}" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            Tableau de bord recruteur
          </li>
        </ul>
        <a href="{{ route('auth.inscription') }}"
           style="display:block;text-align:center;padding:11px;border-radius:9px;font-weight:700;font-size:13.5px;text-decoration:none;background:{{ $isPop ? '#185FA5' : '#f1f5f9' }};color:{{ $isPop ? '#fff' : '#042C53' }}">
          {{ $plan->is_free ? 'Démarrer gratuitement' : 'Choisir ce plan' }}
        </a>
      </div>
      @endforeach
    </div>

    {{-- Plans annonceurs --}}
    <div id="plans-annonceur" style="display:none;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:20px;max-width:900px;margin:0 auto">
      @foreach($plansAnnonceurs as $plan)
      @php
        $isPop = $plan->price > 0 && !$plansAnnonceurs->last()->is_free && $plan->price === $plansAnnonceurs->where('is_free', false)->min('price');
        $features    = $plan->features->keyBy('feature_key');
        $annLimit    = $features->get('annonce_limit')?->feature_value ?? '1';
        $displayDays = $features->get('display_days')?->feature_value ?? '7';
        $priority    = (int)($features->get('priority_display')?->feature_value ?? 0) > 0;
      @endphp
      <div style="background:#fff;border:2px solid {{ $priority ? '#185FA5' : '#e2e8f0' }};border-radius:16px;padding:28px 24px;position:relative;{{ $priority ? 'box-shadow:0 8px 32px rgba(24,95,165,.15)' : '' }}">
        @if($priority)
          <span style="position:absolute;top:-12px;left:50%;transform:translateX(-50%);background:#185FA5;color:#fff;font-size:11px;font-weight:700;padding:3px 14px;border-radius:20px;white-space:nowrap">Meilleure visibilité</span>
        @endif
        <p style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:{{ $priority ? '#185FA5' : '#94a3b8' }};margin:0 0 6px">{{ $plan->name }}</p>
        <div style="margin-bottom:20px">
          @if($plan->is_free)
            <span style="font-size:2rem;font-weight:800;color:#042C53">Gratuit</span>
          @else
            <span style="font-size:2rem;font-weight:800;color:#042C53">{{ number_format($plan->price, 0, ',', ' ') }}</span>
            <span style="font-size:14px;color:#64748b;font-weight:600"> FCFA{{ $plan->duration_days ? ' / '.$plan->duration_days.'j' : '' }}</span>
          @endif
        </div>
        <ul style="list-style:none;padding:0;margin:0 0 24px;display:flex;flex-direction:column;gap:10px">
          <li style="display:flex;align-items:center;gap:10px;font-size:13.5px;color:#374151">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="{{ $priority ? '#185FA5' : '#22c55e' }}" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            {{ $annLimit }} annonce{{ (int)$annLimit > 1 ? 's' : '' }} active{{ (int)$annLimit > 1 ? 's' : '' }} max
          </li>
          <li style="display:flex;align-items:center;gap:10px;font-size:13.5px;color:#374151">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="{{ $priority ? '#185FA5' : '#22c55e' }}" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            Diffusion pendant {{ $displayDays }} jours
          </li>
          <li style="display:flex;align-items:center;gap:10px;font-size:13.5px;color:#374151">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="{{ $priority ? '#185FA5' : ($priority ? '#22c55e' : '#cbd5e1') }}" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $priority ? 'M5 13l4 4L19 7' : 'M6 18L18 6M6 6l12 12' }}"/></svg>
            <span style="color:{{ $priority ? '#374151' : '#94a3b8' }}">Affichage prioritaire {{ $priority ? 'inclus' : 'non inclus' }}</span>
          </li>
          <li style="display:flex;align-items:center;gap:10px;font-size:13.5px;color:#374151">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="{{ $priority ? '#185FA5' : '#22c55e' }}" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            Tableau de bord annonceur
          </li>
        </ul>
        <a href="{{ route('auth.inscription') }}"
           style="display:block;text-align:center;padding:11px;border-radius:9px;font-weight:700;font-size:13.5px;text-decoration:none;background:{{ $priority ? '#185FA5' : '#f1f5f9' }};color:{{ $priority ? '#fff' : '#042C53' }}">
          {{ $plan->is_free ? 'Essayer gratuitement' : 'Choisir ce plan' }}
        </a>
      </div>
      @endforeach
    </div>

    <p style="text-align:center;font-size:12.5px;color:#94a3b8;margin-top:28px">
      Tarifs indicatifs — les plans exacts sont visibles après connexion selon votre profil.
    </p>
  </div>
</section>

<script>
function showPlans(type) {
  ['candidat','recruteur','annonceur'].forEach(function(t) {
    var grid = document.getElementById('plans-' + t);
    var tab  = document.getElementById('tab-' + t);
    if (grid) grid.style.display  = (t === type) ? 'grid' : 'none';
    if (tab)  tab.style.background = (t === type) ? '#042C53' : 'transparent';
    if (tab)  tab.style.color      = (t === type) ? '#fff' : '#64748b';
  });
}
</script>

{{-- ═══════════════════════════════════════════
     NEWSLETTER
═══════════════════════════════════════════ --}}
<section class="newsletter">
  <div class="container">
    <div class="newsletter__inner">
      <div class="newsletter__content">
        <span class="newsletter__badge">
          <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
          Newsletter
        </span>
        <h2 class="newsletter__title">Ne ratez aucune<br><span>opportunité d'emploi</span></h2>
        <p class="newsletter__sub">Recevez chaque semaine les meilleures offres d'emploi au Bénin, des conseils carrière exclusifs et les actualités du marché du travail.</p>
        <div class="newsletter__stats">
          <div class="newsletter__stat">
            <div class="newsletter__stat-icon">
              <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <div class="newsletter__stat-text">
              <span class="newsletter__stat-num">2 000+</span>
              <span class="newsletter__stat-label">Abonnés actifs</span>
            </div>
          </div>
          <div class="newsletter__stat">
            <div class="newsletter__stat-icon">
              <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div class="newsletter__stat-text">
              <span class="newsletter__stat-num">Chaque semaine</span>
              <span class="newsletter__stat-label">Nouvelles offres</span>
            </div>
          </div>
        </div>
      </div>
      <div class="newsletter__form-side">
        <form class="newsletter__form" action="{{ route('contact.envoyer') }}" method="POST">
          @csrf
          <input type="hidden" name="type" value="newsletter">
          <label class="newsletter__form-label">Votre adresse email</label>
          <div class="newsletter__form-group">
            <input type="email" name="email" class="newsletter__input" placeholder="exemple@email.com" required>
            <button type="submit" class="newsletter__btn">
              <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
              S'abonner gratuitement
            </button>
          </div>
          <p class="newsletter__privacy">Zéro spam. Désabonnement en un clic.</p>
        </form>
      </div>
    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════
     WIDGET PUBLICITÉS (coin bas-droite)
═══════════════════════════════════════════ --}}
<div id="pub-widget" style="display:none;position:fixed;bottom:20px;right:20px;z-index:9990;width:280px;max-width:calc(100vw - 32px);filter:drop-shadow(0 8px 24px rgba(0,0,0,.22))">

  {{-- Corps de l'annonce (zone pliable) --}}
  <div id="pub-body" style="background:#fff;border-radius:14px 14px 0 0;overflow:hidden;transition:max-height .3s ease">
    <a id="pub-link" href="#" target="_blank" rel="noopener noreferrer sponsored"
       style="display:block;background:#000;aspect-ratio:4/3;overflow:hidden">
      <img id="pub-img" src="" alt="" style="width:100%;height:100%;object-fit:cover;display:block">
    </a>
    <div style="padding:8px 12px 10px;display:flex;justify-content:space-between;align-items:center">
      <span id="pub-titre" style="font-size:12.5px;font-weight:600;color:#1e293b;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;max-width:190px"></span>
      <span style="font-size:10px;color:#94a3b8;flex-shrink:0;margin-left:6px;text-transform:uppercase;letter-spacing:.04em">Annonce</span>
    </div>
  </div>

  {{-- Barre de contrôle toujours visible --}}
  <div style="background:#042C53;border-radius:0 0 14px 14px;padding:7px 12px;display:flex;align-items:center;justify-content:space-between">
    <span style="font-size:11px;font-weight:700;color:#F5C842;letter-spacing:.06em;text-transform:uppercase">
      <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="#F5C842" stroke-width="2.5" style="display:inline-block;vertical-align:-2px;margin-right:4px"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
      Publicité
    </span>
    <button id="pub-toggle" onclick="togglePub()"
            title="Réduire / Agrandir"
            style="background:rgba(255,255,255,.12);border:none;border-radius:6px;cursor:pointer;padding:4px 8px;display:flex;align-items:center;gap:5px;color:#e2e8f0;font-size:11px;font-weight:600">
      <svg id="pub-chevron" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="18 15 12 9 6 15"/></svg>
      Réduire
    </button>
  </div>

</div>
@endsection

@section('scripts')
<script src="{{ asset('js/index.js') }}" defer></script>
<script>
(function () {
  let pubs = [], idx = 0, timer = null;
  let folded = sessionStorage.getItem('pub_folded') === '1';

  const widget  = document.getElementById('pub-widget');
  const body    = document.getElementById('pub-body');
  const imgEl   = document.getElementById('pub-img');
  const linkEl  = document.getElementById('pub-link');
  const titreEl = document.getElementById('pub-titre');

  function show(pub) {
    imgEl.src   = pub.image_url;
    imgEl.alt   = pub.titre;
    titreEl.textContent = pub.titre;
    linkEl.href = pub.lien || '#';
    linkEl.style.pointerEvents = pub.lien ? '' : 'none';
    widget.style.display = 'block';
    applyFolded(folded);
  }

  function applyFolded(state) {
    const chevron = document.getElementById('pub-chevron');
    const btn     = document.getElementById('pub-toggle');
    if (state) {
      body.style.display     = 'none';
      chevron.setAttribute('points', '6 9 12 15 18 9');
      btn.innerHTML = '<svg id="pub-chevron" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg> Agrandir';
    } else {
      body.style.display     = 'block';
      chevron.setAttribute('points', '18 15 12 9 6 15');
      btn.innerHTML = '<svg id="pub-chevron" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="18 15 12 9 6 15"/></svg> Réduire';
    }
  }

  window.togglePub = function () {
    folded = !folded;
    sessionStorage.setItem('pub_folded', folded ? '1' : '0');
    applyFolded(folded);
  };

  function next() {
    if (!pubs.length) return;
    idx = (idx + 1) % pubs.length;
    show(pubs[idx]);
  }

  function startRotation() {
    clearInterval(timer);
    if (pubs.length > 1) timer = setInterval(next, 60000);
  }

  fetch('/api/publicites/actives')
    .then(r => r.ok ? r.json() : null)
    .then(data => {
      if (!data || !data.length) return;
      pubs = data;
      idx  = 0;
      show(pubs[0]);
      startRotation();
    })
    .catch(() => {});
})();
</script>
@endsection
