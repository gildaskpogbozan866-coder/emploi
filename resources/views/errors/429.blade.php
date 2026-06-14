@extends('layouts.app')
@section('title', 'Trop de tentatives — Emploi Bouge Bénin')

@section('content')
<section class="error-page">
  <div class="error-page__inner">
    <svg width="56" height="56" fill="none" viewBox="0 0 24 24" stroke="#D97706" stroke-width="1.5" style="margin-bottom:16px">
      <circle cx="12" cy="12" r="10"/>
      <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"/>
    </svg>
    <div class="error-page__code" style="color:#D97706">429</div>
    <h1 class="error-page__title">Trop de tentatives</h1>
    <p class="error-page__desc">
      Vous avez effectué trop de requêtes en peu de temps.<br>
      Veuillez patienter quelques instants avant de réessayer.
    </p>
    <div class="error-page__actions">
      <button onclick="history.back()" class="btn btn--yellow">Retour en arrière</button>
      <a href="{{ route('home') }}" class="btn btn--outline">Accueil</a>
    </div>
  </div>
</section>
@endsection
