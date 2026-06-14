@extends('layouts.app')
@section('title', 'FAQ — Emploi Bouge Bénin')

@section('content')
<section class="section page-hero">
  <div class="container page-hero__inner">
    <span class="badge badge--blue">FAQ</span>
    <h1 class="page-hero__title">Questions fréquentes</h1>
    <p class="page-hero__subtitle">Tout ce que vous devez savoir sur Emploi Bouge Bénin.</p>
  </div>
</section>

<section class="section">
  <div class="container" style="max-width:760px">

    @forelse($faqs as $categorie => $questions)
    <div style="margin-bottom:36px">
      <h2 style="font-size:1.1rem;font-weight:700;color:#042C53;margin-bottom:14px;padding-bottom:8px;border-bottom:2px solid #EBF4FD">{{ $categorie }}</h2>
      <div style="display:flex;flex-direction:column;gap:2px">
        @foreach($questions as $faq)
        <details style="background:#fff;border:1px solid #e2e8f0;border-radius:10px;overflow:hidden">
          <summary style="padding:14px 18px;font-weight:600;color:#042C53;cursor:pointer;font-size:.92rem;list-style:none;display:flex;justify-content:space-between;align-items:center">
            {{ $faq->question }}
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="flex-shrink:0;transition:transform .2s">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
          </summary>
          <div class="faq-reponse">{!! $faq->reponse !!}</div>
        </details>
        @endforeach
      </div>
    </div>
    @empty
    <p style="color:#64748b;text-align:center;padding:40px 0">Aucune question disponible pour l'instant.</p>
    @endforelse

    <div style="background:#042C53;border-radius:14px;padding:32px 24px;text-align:center;margin-top:40px">
      <p style="font-weight:700;color:#fff;margin:0 0 8px;font-size:1.05rem">Vous n'avez pas trouvé votre réponse ?</p>
      <p style="color:rgba(255,255,255,.7);margin:0 0 20px;font-size:.9rem">Notre équipe est disponible pour vous aider sous 24h.</p>
      <a href="{{ route('contact') }}" class="btn btn--yellow">Nous contacter</a>
    </div>

  </div>
</section>
@endsection
