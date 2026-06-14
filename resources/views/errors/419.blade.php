@extends('layouts.app')
@section('title', 'Session expirée — Emploi Bouge Bénin')

@section('content')
<section class="error-page">
  <div class="error-page__inner">
    <svg width="56" height="56" fill="none" viewBox="0 0 24 24" stroke="#D97706" stroke-width="1.5" style="margin-bottom:16px">
      <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
    </svg>
    <div class="error-page__code" style="color:#D97706">419</div>
    <h1 class="error-page__title">Session expirée</h1>
    <p class="error-page__desc">
      Votre session a expiré.<br>
      Reconnectez-vous et réessayez.
    </p>
    <div class="error-page__actions">
      @auth
        <button onclick="history.back()" class="btn btn--yellow">Retour et réessayer</button>
      @else
        <a href="{{ route('auth.connexion') }}" class="btn btn--yellow">Se reconnecter</a>
      @endauth
      <a href="{{ route('home') }}" class="btn btn--outline">Accueil</a>
    </div>
  </div>
</section>
@endsection
