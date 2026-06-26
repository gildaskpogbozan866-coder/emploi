@extends('layouts.app')
@section('title', $article->titre . ' | Blog Emploi Bénin, Emploi Bouge Bénin')
@section('description', Str::limit(strip_tags($article->extrait ?? ''), 160))
@section('canonical', route('blog.detail', $article->slug))
@section('og_type', 'article')
@section('og_title', $article->titre)
@section('og_description', Str::limit(strip_tags($article->extrait ?? ''), 160))
@if($article->image) @section('og_image', asset('storage/' . $article->image)) @endif

@section('jsonld')
@php
$articleSchema = [
  '@context'         => 'https://schema.org',
  '@type'            => 'Article',
  'headline'         => $article->titre,
  'description'      => Str::limit(strip_tags($article->extrait ?? ''), 160),
  'url'              => route('blog.detail', $article->slug),
  'datePublished'    => $article->publie_le?->toIso8601String(),
  'dateModified'     => $article->updated_at->toIso8601String(),
  'inLanguage'       => 'fr-FR',
  'author'           => ['@type' => 'Organization', 'name' => 'Emploi Bouge Bénin', 'url' => route('home')],
  'publisher'        => [
    '@type' => 'Organization',
    'name'  => 'Emploi Bouge Bénin',
    'logo'  => ['@type' => 'ImageObject', 'url' => asset('images/Logo.png')],
  ],
  'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => route('blog.detail', $article->slug)],
];
if ($article->image) {
  $articleSchema['image'] = ['@type' => 'ImageObject', 'url' => asset('storage/' . $article->image)];
}
$breadcrumb = [
  '@context'        => 'https://schema.org',
  '@type'           => 'BreadcrumbList',
  'itemListElement' => [
    ['@type' => 'ListItem', 'position' => 1, 'name' => 'Accueil', 'item' => route('home')],
    ['@type' => 'ListItem', 'position' => 2, 'name' => 'Blog',    'item' => route('blog.list')],
    ['@type' => 'ListItem', 'position' => 3, 'name' => $article->titre, 'item' => route('blog.detail', $article->slug)],
  ],
];
@endphp
<script type="application/ld+json">{!! json_encode($articleSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
<script type="application/ld+json">{!! json_encode($breadcrumb, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}</script>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/blog/detail-blog.css') }}">
@endsection

@section('content')

{{-- ══════════════════════ HERO ══════════════════════ --}}
<section class="article-hero">
  <div class="container">
    <div class="article-hero__inner">

      <nav class="breadcrumb">
        <a href="{{ route('home') }}" class="breadcrumb__link">Accueil</a>
        <span class="breadcrumb__sep">/</span>
        <a href="{{ route('blog.list') }}" class="breadcrumb__link">Blog</a>
        <span class="breadcrumb__sep">/</span>
        <span class="breadcrumb__current">{{ Str::limit($article->titre, 48) }}</span>
      </nav>

      @if($article->categorie)
        <span class="article-hero__tag">
          <svg width="10" height="10" fill="currentColor" viewBox="0 0 24 24"><path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/><line x1="7" y1="7" x2="7.01" y2="7"/></svg>
          {{ $article->categorie }}
        </span>
      @endif

      <h1 class="article-hero__title">{{ $article->titre }}</h1>

      @if($article->extrait)
        <p class="article-hero__excerpt">{{ strip_tags($article->extrait) }}</p>
      @endif

      <div class="article-hero__meta">
        <div class="article-hero__author">
          <div class="article-hero__avatar">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 21v-2a4 4 0 00-4-4H9a4 4 0 00-4 4v2M12 11a4 4 0 100-8 4 4 0 000 8z"/>
            </svg>
          </div>
          <div>
            <span class="article-hero__author-name">Emploi Bouge Bénin</span>
            <time class="article-hero__date">{{ $article->publie_le?->format('d M Y') }}</time>
          </div>
        </div>
        <div class="article-hero__stats">
          <span class="article-hero__read-time">
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"/></svg>
            {{ $article->temps_lecture }} min de lecture
          </span>
          <span class="article-hero__read-time">
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            {{ number_format($article->vues) }} lectures
          </span>
        </div>
      </div>

    </div>
  </div>
</section>

{{-- ══════════════════════ IMAGE COVER ══════════════════════ --}}
@if($article->image)
<div class="article-cover-wrap">
  <div class="article-cover">
    <img src="{{ asset('storage/' . $article->image) }}"
         alt="{{ $article->titre }}"
         class="article-cover__img">
  </div>
</div>
@endif

{{-- ══════════════════════ CONTENU + SIDEBAR ══════════════════════ --}}
<section class="section article-layout" style="{{ $article->image ? 'padding-top:48px' : '' }}">
  <div class="container">
    <div class="article-layout__inner">

      {{-- ── ARTICLE ── --}}
      <main>
        <div class="article-content">
          {!! $article->contenu !!}
        </div>

        {{-- ── CTA double : Déposer + Commander ── --}}
        @if(!auth()->check() || auth()->user()->hasRole('candidat'))
        <div class="article-cta-cv">
          <div class="article-cta-cv__icon">
            <svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
          </div>
          <div class="article-cta-cv__body">
            <p class="article-cta-cv__title">Boostez votre recherche d'emploi au Bénin</p>
            <p class="article-cta-cv__sub">Déposez votre CV gratuitement pour être visible des recruteurs, ou faites rédiger un CV professionnel par nos experts.</p>
          </div>
          <div class="article-cta-cv__actions">
            <a href="{{ auth()->check() && auth()->user()->hasRole('candidat') ? route('cv.public.depot') : route('auth.inscription').'?role=candidat' }}"
               class="article-cta-cv__btn article-cta-cv__btn--primary">
              <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
              </svg>
              Déposer mon CV
            </a>
            <a href="{{ route('service.list') }}"
               class="article-cta-cv__btn article-cta-cv__btn--secondary">
              <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
              </svg>
              Commander mon CV
            </a>
          </div>
        </div>
        @endif

        {{-- ── Partager ── --}}
        @php
          $shareUrl  = urlencode(route('blog.detail', $article->slug));
          $shareText = urlencode($article->titre . ' | Emploi Bouge Bénin');
        @endphp
        <div class="article-share">
          <span class="article-share__label">Partager</span>
          <a href="https://www.facebook.com/sharer/sharer.php?u={{ $shareUrl }}"
             target="_blank" rel="noopener" class="article-share__btn article-share__btn--fb">
            <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/></svg>
            Facebook
          </a>
          <a href="https://wa.me/?text={{ $shareText }}%20{{ $shareUrl }}"
             target="_blank" rel="noopener" class="article-share__btn article-share__btn--wa">
            <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            WhatsApp
          </a>
          <a href="https://twitter.com/intent/tweet?text={{ $shareText }}&url={{ $shareUrl }}"
             target="_blank" rel="noopener" class="article-share__btn"
             style="background:#1da1f2;color:#fff">
            <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z"/></svg>
            Twitter
          </a>
        </div>

        <div class="article-nav">
          <a href="{{ route('blog.list') }}" class="article-nav__back">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
            Retour au blog
          </a>
        </div>
      </main>

      {{-- ── SIDEBAR ── --}}
      <aside class="article-sidebar">

        {{-- CTA double CV sidebar --}}
        <div class="sidebar-card sidebar-card--cv">
          <p class="sidebar-card__title">Votre CV, votre carrière</p>
          <p style="font-size:13px;color:#92400e;line-height:1.6;margin:0 0 14px">
            Déposez votre CV gratuitement pour être visible, ou commandez un CV professionnel rédigé par nos experts.
          </p>
          <a href="{{ auth()->check() && auth()->user()->hasRole('candidat') ? route('cv.public.depot') : route('auth.inscription').'?role=candidat' }}"
             class="sidebar-cv-btn">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
            </svg>
            Déposer mon CV
          </a>
          <a href="{{ route('service.list') }}"
             class="sidebar-cv-btn sidebar-cv-btn--order">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
              <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
            </svg>
            Commander mon CV
          </a>
        </div>

        {{-- CTA Services --}}
        <div class="sidebar-card sidebar-card--cta">
          <p class="sidebar-card__title">Nos services carrière</p>
          <p class="sidebar-card__text">CV professionnel, lettre de motivation et coaching, rédigés par nos experts et livrés en 30min à 1h.</p>
          <a href="{{ route('service.list') }}" class="sidebar-card__btn">
            Découvrir nos services →
          </a>
        </div>

        {{-- Articles suggérés --}}
        @if($suggestions->count())
        <div class="sidebar-card">
          <p class="sidebar-card__title">Articles similaires</p>
          <ul class="sidebar-articles">
            @foreach($suggestions as $sug)
            <li class="sidebar-article">
              <a href="{{ route('blog.detail', $sug->slug) }}" class="sidebar-article__link">
                {{ $sug->titre }}
              </a>
              <time class="sidebar-article__date">{{ $sug->publie_le?->format('d M Y') }}</time>
            </li>
            @endforeach
          </ul>
        </div>
        @endif

        {{-- CTA Offres --}}
        <div class="sidebar-card" style="background:#f0f7ff;border-color:#bfdbfe">
          <p class="sidebar-card__title" style="color:#042C53">Chercher un emploi</p>
          <p style="font-size:13px;color:#475569;line-height:1.6;margin:0 0 14px">Des centaines d'offres vérifiées au Bénin, mises à jour chaque semaine.</p>
          <a href="{{ route('offre.list') }}"
             style="display:inline-flex;align-items:center;gap:6px;background:linear-gradient(135deg,#042C53,#185FA5);color:#F5C842;font-size:13px;font-weight:700;padding:10px 18px;border-radius:10px;text-decoration:none;transition:opacity 0.2s"
             onmouseover="this.style.opacity='.85'" onmouseout="this.style.opacity='1'">
            Voir les offres →
          </a>
        </div>

      </aside>

    </div>
  </div>
</section>

@endsection
