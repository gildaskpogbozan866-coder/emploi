@extends('layouts.app')
@section('title', 'Accès refusé — Emploi Bouge Bénin')

@section('content')
<section class="error-page">
  <div class="error-page__inner">
    <div class="error-page__code">403</div>
    <h1 class="error-page__title">Accès non autorisé</h1>
    <p class="error-page__desc">Vous n'avez pas les droits nécessaires pour accéder à cette page.</p>
    <div class="error-page__actions">
      <a href="{{ route('home') }}" class="btn btn--yellow">Retour à l'accueil</a>
      @guest
      <a href="{{ route('auth.connexion') }}" class="btn btn--outline">Se connecter</a>
      @endguest
    </div>
  </div>
</section>
@endsection
