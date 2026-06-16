@extends('layouts.app')
@section('title', $offre->titre . ', ' . $offre->entreprise . ' | Emploi Bouge Bénin')
@section('description', Str::limit(strip_tags($offre->description ?? ''), 160))
@section('canonical', route('offre.detail', $offre))
@section('og_type', 'article')
@section('og_title', $offre->titre . ', ' . $offre->entreprise)
@section('og_description', Str::limit(strip_tags($offre->description ?? ''), 160))
@section('og_url', route('offre.detail', $offre))

@section('jsonld')
@php
$jobPosting = [
    '@context'       => 'https://schema.org',
    '@type'          => 'JobPosting',
    'title'          => $offre->titre,
    'description'    => strip_tags($offre->description ?? ''),
    'identifier'     => ['@type' => 'PropertyValue', 'name' => 'Emploi Bouge Bénin', 'value' => $offre->id],
    'url'            => route('offre.detail', $offre),
    'datePosted'     => $offre->created_at->toDateString(),
    'dateModified'   => $offre->updated_at->toDateString(),
    'employmentType' => strtoupper(str_replace([' ', '-'], '_', $offre->type ?? 'OTHER')),
    'jobLocationType'=> 'TELECOMMUTE',
    'hiringOrganization' => [
        '@type'  => 'Organization',
        'name'   => $offre->entreprise,
        'sameAs' => route('home'),
    ],
    'jobLocation' => [
        '@type'   => 'Place',
        'address' => [
            '@type'           => 'PostalAddress',
            'addressLocality' => $offre->localisation ?? 'Cotonou',
            'addressRegion'   => $offre->localisation ?? 'Littoral',
            'addressCountry'  => 'BJ',
        ],
    ],
    'applicantLocationRequirements' => ['@type' => 'Country', 'name' => 'Bénin'],
];
if ($offre->date_limite) {
    $jobPosting['validThrough'] = \Carbon\Carbon::parse($offre->date_limite)->toIso8601String();
}
if ($offre->salaire) {
    $jobPosting['baseSalary'] = [
        '@type'    => 'MonetaryAmount',
        'currency' => 'XOF',
        'value'    => ['@type' => 'QuantitativeValue', 'value' => $offre->salaire, 'unitText' => 'MONTH'],
    ];
}
$breadcrumb = [
    '@context'        => 'https://schema.org',
    '@type'           => 'BreadcrumbList',
    'itemListElement' => [
        ['@type' => 'ListItem', 'position' => 1, 'name' => 'Accueil',          'item' => route('home')],
        ['@type' => 'ListItem', 'position' => 2, 'name' => 'Offres d\'emploi', 'item' => route('offre.list')],
        ['@type' => 'ListItem', 'position' => 3, 'name' => $offre->titre,      'item' => route('offre.detail', $offre)],
    ],
];
@endphp
<script type="application/ld+json">{!! json_encode($jobPosting, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
<script type="application/ld+json">{!! json_encode($breadcrumb, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/offre/detail-offre.css') }}">
@endsection

@section('content')

{{-- ═══════════════════ HERO ═══════════════════ --}}
<div class="od-hero">
  <div class="od-hero__inner">
    <a href="{{ route('offre.list') }}" class="od-hero__back">
      <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/></svg>
      Retour aux offres
    </a>
    <div class="od-hero__head">
      <div class="od-hero__avatar">{{ strtoupper(substr($offre->entreprise, 0, 2)) }}</div>
      <div class="od-hero__info">
        <h1 class="od-hero__title">{{ $offre->titre }}</h1>
        <p class="od-hero__company">{{ $offre->entreprise }}</p>
        <div class="od-hero__badges">
          @if($offre->type)
            <span class="od-badge od-badge--type">{{ $offre->type }}</span>
          @endif
          @if($offre->localisation)
            <span class="od-badge od-badge--loc">
              <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              {{ $offre->localisation }}
            </span>
          @endif
          @foreach((array)$offre->secteur as $s)
            @if($s)<span class="od-badge od-badge--sect">{{ $s }}</span>@endif
          @endforeach
          @if($offre->salaire)
            <span class="od-badge od-badge--sal">{{ $offre->salaire }}</span>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

{{-- ═══════════════════ BODY ═══════════════════ --}}
<div class="od-body">
  <div class="od-wrap">

    {{-- ─── COLONNE PRINCIPALE ─── --}}
    <div class="od-main">

      {{-- Fichier annexe --}}
      @if($offre->fichier)
      <div class="od-card">
        <div class="od-card__body--tight">
          <a href="{{ Storage::url($offre->fichier) }}" target="_blank" rel="noopener" class="od-attachment">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="12" y1="18" x2="12" y2="12"/><line x1="9" y1="15" x2="15" y2="15"/></svg>
            Télécharger l'annonce officielle (PDF)
          </a>
        </div>
      </div>
      @endif

      {{-- Description --}}
      <div class="od-card">
        <div class="od-card__head">
          <svg class="od-card__head-icon" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
          <span class="od-card__head-title">Description du poste</span>
        </div>
        <div class="od-card__body">
          @php $descIsHtml = str_starts_with(ltrim($offre->description ?? ''), '<'); @endphp
          <div class="od-prose">{!! $descIsHtml ? $offre->description : nl2br(e($offre->description)) !!}</div>
        </div>
      </div>

      {{-- Compétences --}}
      @if($offre->competences->isNotEmpty())
      <div class="od-card">
        <div class="od-card__head">
          <svg class="od-card__head-icon" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
          <span class="od-card__head-title">Compétences requises</span>
        </div>
        <div class="od-card__body">
          <div class="od-skills">
            @foreach($offre->competences as $comp)
              <span class="od-skill">{{ $comp->nom }}</span>
            @endforeach
          </div>
        </div>
      </div>
      @endif

      {{-- Exigences --}}
      @if($offre->exigences)
      <div class="od-card">
        <div class="od-card__head">
          <svg class="od-card__head-icon" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
          <span class="od-card__head-title">Exigences du poste</span>
        </div>
        <div class="od-card__body">
          <div class="od-prose">{!! nl2br(e($offre->exigences)) !!}</div>
        </div>
      </div>
      @endif

    </div>

    {{-- ─── SIDEBAR ─── --}}
    <aside class="od-sidebar">

      {{-- CTA : postuler / sauvegarder --}}
      <div class="od-cta-card">
        @if($aPostule)
          <div class="od-already">
            <div class="od-already__icon">
              <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="od-already__text">Vous avez déjà postulé</p>
          </div>
        @elseif($offre->statut !== 'active')
          <div class="od-expired">
            <p class="od-expired__text">Cette offre n'est plus disponible</p>
          </div>
        @elseif(Auth::check() && !Auth::user()->hasRole('candidat'))
          {{-- Recruteur / admin : pas de bouton postuler --}}
        @else
          <a href="{{ route('offre.postuler', $offre) }}" class="od-btn-postuler">
            <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
            Postuler à cette offre
          </a>
        @endif

        @auth
        @if(Auth::user()->hasRole('candidat'))
        <form method="POST" action="{{ route('candidat.offres-sauvegardees.toggle', $offre) }}">
          @csrf
          @if($estSauvegarde)
            <button type="submit" class="od-btn-save od-btn-save--active">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
              Retirer des favoris
            </button>
          @else
            <button type="submit" class="od-btn-save">
              <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
              Sauvegarder l'offre
            </button>
          @endif
        </form>
        @endif
        @endauth
      </div>

      {{-- Détails de l'offre --}}
      <div class="od-info-card">
        <div class="od-info-card__head">
          <span class="od-info-card__title">Détails de l'offre</span>
        </div>

        @if($offre->type)
        <div class="od-info-row">
          <div class="od-info-row__icon">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg>
          </div>
          <div>
            <p class="od-info-row__label">Type de contrat</p>
            <p class="od-info-row__value">{{ $offre->type }}</p>
          </div>
        </div>
        @endif

        @if($offre->localisation)
        <div class="od-info-row">
          <div class="od-info-row__icon">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
          </div>
          <div>
            <p class="od-info-row__label">Localisation</p>
            <p class="od-info-row__value">{{ $offre->localisation }}</p>
          </div>
        </div>
        @endif

        @if($offre->salaire)
        <div class="od-info-row">
          <div class="od-info-row__icon">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          </div>
          <div>
            <p class="od-info-row__label">Rémunération</p>
            <p class="od-info-row__value">{{ $offre->salaire }}</p>
          </div>
        </div>
        @endif

        @if(!empty($offre->niveau_experience))
        <div class="od-info-row">
          <div class="od-info-row__icon">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
          </div>
          <div>
            <p class="od-info-row__label">Expérience requise</p>
            <p class="od-info-row__value">
              @if(is_object($offre->niveauExperience ?? null))
                {{ $offre->niveauExperience->libelle }}
              @else
                {{ $offre->niveau_experience }}
              @endif
            </p>
          </div>
        </div>
        @endif

        @if(!empty($offre->niveau_etude))
        <div class="od-info-row">
          <div class="od-info-row__icon">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
          </div>
          <div>
            <p class="od-info-row__label">Niveau d'études</p>
            <p class="od-info-row__value">
              @if(is_object($offre->niveauEtude ?? null))
                {{ $offre->niveauEtude->libelle }}
              @else
                {{ $offre->niveau_etude }}
              @endif
            </p>
          </div>
        </div>
        @endif

        <div class="od-info-row">
          <div class="od-info-row__icon">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
          </div>
          <div>
            <p class="od-info-row__label">Publiée</p>
            <p class="od-info-row__value">{{ $offre->created_at->diffForHumans() }}</p>
          </div>
        </div>

        @if($offre->date_limite)
        @php $isExpiringSoon = \Carbon\Carbon::parse($offre->date_limite)->diffInDays(now()) <= 5 && now()->lt($offre->date_limite); @endphp
        <div class="od-info-row">
          <div class="od-info-row__icon">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
          </div>
          <div>
            <p class="od-info-row__label">Date limite</p>
            <p class="od-info-row__value">{{ \Carbon\Carbon::parse($offre->date_limite)->format('d/m/Y') }}</p>
          </div>
        </div>
        @if($isExpiringSoon)
        <div class="od-deadline-warn">
          <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#c2410c" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
          <p class="od-deadline-warn__text">Expire bientôt !</p>
        </div>
        @endif
        @endif

        <div class="od-info-row">
          <div class="od-info-row__icon">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
          </div>
          <div>
            <p class="od-info-row__label">Vues</p>
            <p class="od-info-row__value">{{ number_format($offre->vues, 0, ',', ' ') }}</p>
          </div>
        </div>
      </div>

      {{-- Partager --}}
      @php
        $shareUrl  = urlencode(route('offre.detail', $offre));
        $shareText = urlencode("Offre d'emploi : {$offre->titre} chez {$offre->entreprise}");
      @endphp
      <div class="od-share-card">
        <p class="od-share-label">Partager cette offre</p>
        <div class="od-share-btns">
          <a href="https://wa.me/?text={{ $shareText }}%20{{ $shareUrl }}" target="_blank" rel="noopener"
             title="WhatsApp" class="od-share-btn od-share-btn--wa">
            <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
          </a>
          <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ $shareUrl }}" target="_blank" rel="noopener"
             title="LinkedIn" class="od-share-btn od-share-btn--li">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
          </a>
          <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}" target="_blank" rel="noopener"
             title="Facebook" class="od-share-btn od-share-btn--fb">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
          </a>
          <a href="https://twitter.com/intent/tweet?text={{ $shareText }}&url={{ $shareUrl }}" target="_blank" rel="noopener"
             title="X / Twitter" class="od-share-btn od-share-btn--x">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.73-8.835L1.254 2.25H8.08l4.253 5.622zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
          </a>
          <button type="button" id="btn-copy-link" data-url="{{ route('offre.detail', $offre) }}"
                  title="Copier le lien" class="od-share-btn od-share-btn--cp">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
          </button>
        </div>
      </div>

    </aside>
  </div>
</div>

{{-- ═══════════════════ OFFRES SIMILAIRES ═══════════════════ --}}
@if($similaires->isNotEmpty())
<div class="od-similaires">
  <div class="od-similaires__inner">
    <h2 class="od-similaires__title">Offres similaires</h2>
    <div class="od-sim-grid">
      @foreach($similaires as $s)
      <a href="{{ route('offre.detail', $s) }}" class="od-sim-card">
        <div class="od-sim-card__head">
          <div class="od-sim-card__avatar">{{ strtoupper(substr($s->entreprise, 0, 2)) }}</div>
          <div style="min-width:0">
            <p class="od-sim-card__title">{{ $s->titre }}</p>
            <p class="od-sim-card__company">{{ $s->entreprise }}</p>
          </div>
        </div>
        <div class="od-sim-card__meta">
          @if($s->type)<span class="od-sim-badge od-sim-badge--type">{{ $s->type }}</span>@endif
          @if($s->localisation)<span class="od-sim-badge od-sim-badge--loc">{{ $s->localisation }}</span>@endif
        </div>
        <p class="od-sim-card__date">{{ $s->created_at->diffForHumans() }}</p>
      </a>
      @endforeach
    </div>
  </div>
</div>
@endif

@endsection

@section('scripts')
<script>
document.getElementById('btn-copy-link')?.addEventListener('click', function () {
  navigator.clipboard.writeText(this.dataset.url).then(() => {
    const orig = this.innerHTML;
    this.innerHTML = '<svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#16a34a" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>';
    this.style.background = '#dcfce7';
    this.style.borderColor = '#86efac';
    setTimeout(() => { this.innerHTML = orig; this.style.background = '#f1f5f9'; this.style.borderColor = '#e2e8f0'; }, 2000);
  });
});
</script>
@endsection
