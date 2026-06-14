@extends('layouts.app')
@section('title', 'Erreur serveur — Emploi Bouge Bénin')

@section('content')
<section class="error-page">
  <div class="error-page__inner">
    <div class="error-page__code">500</div>
    <h1 class="error-page__title">Erreur serveur</h1>
    <p class="error-page__desc">Une erreur inattendue s'est produite. Notre équipe a été notifiée.<br>Veuillez réessayer dans quelques instants.</p>
    <div class="error-page__actions">
      <a href="{{ route('home') }}" class="btn btn--yellow">Retour à l'accueil</a>
      <a href="{{ route('contact') }}" class="btn btn--outline">Nous contacter</a>
    </div>
  </div>
</section>
@endsection
