@extends('layouts.app')
@section('title', $service->nom.' — Emploi Bouge Bénin')

@section('content')
<section class="section" style="background:#f8fafc;min-height:60vh">
  <div class="container" style="max-width:900px">

    <a href="{{ route('service.list') }}" class="page-back-link">
      <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
      Retour aux services
    </a>

    <div class="pub-detail-layout">

      {{-- Contenu principal --}}
      <div class="pub-card">
        <span class="badge badge--yellow" style="margin-bottom:16px">Service</span>
        <h1 class="pub-card__title">{{ $service->nom }}</h1>

        @if($service->description)
          <p class="pub-card__desc">{{ $service->description }}</p>
        @endif

        @if($service->details)
          <div class="pub-card__section">
            <h3 class="pub-card__section-title">Ce qui est inclus</h3>
            <div class="pub-card__checklist">
              @foreach(explode("\n", $service->details) as $line)
                @if(trim($line))
                  <p class="pub-card__check-item">
                    <span class="pub-card__check-icon">✓</span> {{ trim($line) }}
                  </p>
                @endif
              @endforeach
            </div>
          </div>
        @endif

        @if($service->delai)
          <div class="pub-tag-row">
            <span class="pub-tag">⏱ Livraison : {{ $service->delai }}</span>
          </div>
        @endif
      </div>

      {{-- Colonne prix --}}
      <div class="pub-sidebar">
        <div class="pub-price-card">
          <p class="pub-price-card__label">Prix</p>
          <p class="pub-price-card__amount">{{ number_format($service->prix, 0, ',', ' ') }}</p>
          <p class="pub-price-card__currency">FCFA tout compris</p>

          @if($service->delai)
            <div class="pub-price-card__delay">
              <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              Livré sous <strong>{{ $service->delai }}</strong>
            </div>
          @endif

          <a href="{{ route('service.commande', $service) }}" class="btn btn--yellow" style="width:100%;justify-content:center;margin-top:4px">
            Commander maintenant
          </a>
          <p class="pub-price-card__guarantee">
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="#16a34a" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            Satisfaction garantie · 1 révision offerte
          </p>
        </div>
      </div>

    </div>
  </div>
</section>
@endsection
