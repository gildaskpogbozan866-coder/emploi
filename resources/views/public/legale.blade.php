@extends('layouts.app')
@section('title', $page->titre . ' — Emploi Bouge Bénin')

@section('css')
<style>
/* ── Page légale ─────────────────────────────── */
.legale-layout {
  display: grid;
  grid-template-columns: 220px 1fr;
  gap: 32px;
  align-items: start;
}
.legale-nav {
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 14px;
  padding: 8px;
  position: sticky;
  top: 90px;
}
.legale-nav__title {
  font-size: 11px;
  font-weight: 700;
  color: #94a3b8;
  text-transform: uppercase;
  letter-spacing: .07em;
  padding: 10px 12px 6px;
}
.legale-nav__link {
  display: flex;
  align-items: center;
  gap: 8px;
  padding: 10px 12px;
  border-radius: 8px;
  font-size: 13.5px;
  font-weight: 500;
  color: #475569;
  text-decoration: none;
  transition: background .15s, color .15s;
}
.legale-nav__link:hover { background: #f0f7ff; color: #185FA5; }
.legale-nav__link.active { background: #EBF4FD; color: #042C53; font-weight: 700; }
.legale-nav__link svg { flex-shrink: 0; opacity: .6; }
.legale-nav__link.active svg { opacity: 1; }

.legale-card {
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 16px;
  padding: 44px 52px;
}
.legale-card__date {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  font-size: 12.5px;
  color: #94a3b8;
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 20px;
  padding: 4px 12px;
  margin-bottom: 28px;
}
.legale-empty {
  text-align: center;
  padding: 70px 20px;
  color: #94a3b8;
}
.legale-empty svg { margin: 0 auto 16px; display: block; opacity: .4; }

/* Contenu rendu par Summernote */
.legale-content { font-size: .93rem; line-height: 1.85; color: #374151; }
.legale-content h2 { font-size: 1.15rem; font-weight: 700; color: #042C53; margin: 32px 0 10px; padding-bottom: 8px; border-bottom: 2px solid #EBF4FD; }
.legale-content h3 { font-size: 1rem; font-weight: 700; color: #042C53; margin: 22px 0 8px; }
.legale-content h4 { font-size: .9rem; font-weight: 700; color: #374151; margin: 16px 0 6px; }
.legale-content p  { margin: 0 0 14px; }
.legale-content ul, .legale-content ol { padding-left: 22px; margin: 0 0 14px; }
.legale-content li { margin-bottom: 6px; }
.legale-content strong { color: #1e293b; }
.legale-content a { color: #185FA5; text-decoration: underline; }
.legale-content blockquote { border-left: 3px solid #185FA5; margin: 16px 0; padding: 10px 18px; background: #f0f7ff; border-radius: 0 8px 8px 0; color: #374151; }
.legale-content table { width: 100%; border-collapse: collapse; margin: 16px 0; font-size: .88rem; }
.legale-content th, .legale-content td { border: 1px solid #e2e8f0; padding: 8px 12px; text-align: left; }
.legale-content th { background: #f8fafc; font-weight: 700; color: #042C53; }

@media (max-width: 820px) {
  .legale-layout { grid-template-columns: 1fr; }
  .legale-nav { position: static; display: flex; flex-wrap: wrap; gap: 4px; padding: 6px; }
  .legale-nav__title { display: none; }
  .legale-card { padding: 28px 22px; }
}
</style>
@endsection

@section('content')
<section class="section page-hero">
  <div class="container page-hero__inner">
    <span class="badge badge--blue">Légal</span>
    <h1 class="page-hero__title">{{ $page->titre }}</h1>
  </div>
</section>

<section class="section" style="background:#f8fafc">
  <div class="container" style="max-width:980px">
    <div class="legale-layout">

      {{-- Sidebar navigation --}}
      <nav class="legale-nav">
        <p class="legale-nav__title">Pages légales</p>
        @foreach(\App\Models\PageLegale::slugs() as $s => $titre)
        <a href="{{ route('legale.show', $s) }}"
           class="legale-nav__link {{ $s === $page->slug ? 'active' : '' }}">
          <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
          </svg>
          {{ $titre }}
        </a>
        @endforeach
      </nav>

      {{-- Contenu principal --}}
      <div>
        <div class="legale-card">

          <div class="legale-card__date">
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            Dernière mise à jour :
            {{ ($page->exists && $page->updated_at) ? $page->updated_at->format('d/m/Y') : date('d/m/Y') }}
          </div>

          @if($page->contenu)
            <div class="legale-content">{!! $page->contenu !!}</div>
          @else
            <div class="legale-empty">
              <svg width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
              </svg>
              <p>Ce contenu sera bientôt disponible.</p>
            </div>
          @endif

        </div>

        <div style="margin-top:20px;text-align:center">
          <p style="font-size:13px;color:#94a3b8">
            Des questions ?
            <a href="{{ route('contact') }}" style="color:#185FA5;font-weight:600;text-decoration:none">Contactez-nous</a>
          </p>
        </div>
      </div>

    </div>
  </div>
</section>
@endsection
