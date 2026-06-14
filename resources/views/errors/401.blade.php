@extends('layouts.app')
@section('title', 'Non authentifié — Emploi Bouge Bénin')

@section('content')
<section class="error-page">
  <div class="error-page__inner">
    <svg width="56" height="56" fill="none" viewBox="0 0 24 24" stroke="#64748b" stroke-width="1.5" style="margin-bottom:16px">
      <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
    </svg>
    <div class="error-page__code">401</div>
    <h1 class="error-page__title">Authentification requise</h1>
    <p class="error-page__desc">
      Vous devez être connecté pour accéder à cette page.
    </p>
    <div class="error-page__actions">
      <a href="{{ route('auth.connexion') }}" class="btn btn--yellow">Se connecter</a>
      <a href="{{ route('home') }}" class="btn btn--outline">Accueil</a>
    </div>
  </div>
</section>
@endsection
