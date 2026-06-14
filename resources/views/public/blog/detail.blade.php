@extends('layouts.app')
@section('title', $article->titre . ' — Blog Emploi Bouge Bénin')
@section('description', $article->extrait)

@section('css')
<link rel="stylesheet" href="{{ asset('css/blog/detail-blog.css') }}">
@endsection

@section('content')
<article class="section" style="padding-top:40px">
  <div class="container" style="max-width:820px">

    <a href="{{ route('blog.list') }}" style="display:inline-flex;align-items:center;gap:6px;color:#185FA5;font-size:.9rem;margin-bottom:28px;text-decoration:none"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg> Retour au blog</a>

    <header class="article-detail-header">
      @if($article->categorie)
        <span class="article-card__category" style="margin-bottom:16px;display:inline-block">{{ $article->categorie }}</span>
      @endif
      <h1 style="font-size:2rem;color:#042C53;line-height:1.3;margin-bottom:16px">{{ $article->titre }}</h1>

      <div style="display:flex;align-items:center;gap:16px;color:#64748b;font-size:.88rem;margin-bottom:32px">
        <span>Par <strong>{{ config('app.name') }}</strong></span>
        <span>·</span>
        <span>{{ $article->publie_le?->format('d M Y') }}</span>
        <span>·</span>
        <span>{{ $article->temps_lecture }} min de lecture</span>
        <span>·</span>
        <span>{{ number_format($article->vues) }} lectures</span>
      </div>

      @if($article->image)
        <div style="width:100%;height:420px;border-radius:16px;overflow:hidden;margin-bottom:32px">
          <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->titre }}"
               style="width:100%;height:100%;object-fit:cover">
        </div>
      @endif
    </header>

    <div class="article-detail-body" style="font-size:1.05rem;line-height:1.85;color:#334155">
      {!! $article->contenu !!}
    </div>

    <footer style="margin-top:48px;padding-top:24px;border-top:1px solid #e2e8f0">
      <div style="display:flex;gap:12px;flex-wrap:wrap">
        <a href="{{ route('blog.list') }}" class="btn btn--outline"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg> Tous les articles</a>
        <a href="{{ route('service.list') }}" class="btn btn--yellow">Nos services <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></a>
      </div>
    </footer>

  </div>
</article>
@endsection
