@extends('layouts.app')
@section('title', 'Blog & Conseils — Emploi Bouge Bénin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/blog/blog.css') }}">
@endsection

@section('content')
<section class="page-hero">
  <div class="container">
    <h1 class="page-hero__title">Blog &amp; Conseils</h1>
    <p class="page-hero__sub">Guides pratiques, conseils carrière et actualités pour réussir sur le marché africain.</p>
  </div>
</section>

<section class="section">
  <div class="container">

    {{-- Filtres catégories --}}
    <div class="blog-categories">
      <a href="{{ route('blog.list') }}" class="blog-cat {{ !request('categorie') ? 'active' : '' }}">Tous</a>
      @foreach(['Conseils CV', 'Entretien', 'Remote', 'Emploi', 'Formation', 'Entrepreneuriat'] as $cat)
        <a href="{{ route('blog.list', ['categorie' => $cat]) }}"
           class="blog-cat {{ request('categorie') === $cat ? 'active' : '' }}">
          {{ $cat }}
        </a>
      @endforeach
    </div>

    <div class="articles-grid" style="margin-top:32px">
      @forelse($articles as $article)
        <article class="article-card">
          <a href="{{ route('blog.detail', $article) }}" class="article-card__cover">
            <span class="article-card__category">{{ $article->categorie }}</span>
            @if($article->image)
              <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->titre }}" class="card__img" loading="lazy" />
            @else
              <div style="background:#EEF4FF;height:200px;display:flex;align-items:center;justify-content:center;color:#185FA5"><svg width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path stroke-linecap="round" stroke-linejoin="round" d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg></div>
            @endif
          </a>
          <div class="article-card__body">
            <div class="article-card__meta">
              <span class="article-card__date">{{ $article->publie_le?->format('d M Y') }}</span>
              <span class="article-card__dot"></span>
              <span class="article-card__read">{{ $article->temps_lecture }} min</span>
              <span class="article-card__dot"></span>
              <span class="article-card__views">{{ number_format($article->vues) }} lectures</span>
            </div>
            <h3 class="article-card__title">{{ $article->titre }}</h3>
            <p class="article-card__text">{{ $article->extrait }}</p>
            <a href="{{ route('blog.detail', $article) }}" class="article-card__link">
              Lire l'article →
            </a>
          </div>
        </article>
      @empty
        <div class="empty-state" style="grid-column:1/-1">
          <p>Aucun article pour l'instant.</p>
        </div>
      @endforelse
    </div>

    <div style="margin-top:40px">{{ $articles->links() }}</div>
  </div>
</section>
@endsection
