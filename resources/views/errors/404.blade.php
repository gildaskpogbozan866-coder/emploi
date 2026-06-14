@extends('layouts.app')
@section('title', 'Page introuvable — Emploi Bouge Bénin')

@section('content')
<section class="error-page">
  <div class="error-page__inner">
    <div class="error-page__code">404</div>
    <h1 class="error-page__title">Page introuvable</h1>
    <p class="error-page__desc">La page que vous cherchez n'existe plus ou a été déplacée.</p>
    <div class="error-page__actions">
      <a href="{{ route('home') }}" class="btn btn--yellow">Retour à l'accueil</a>
      <a href="{{ route('offre.list') }}" class="btn btn--outline">Voir les offres</a>
    </div>
  </div>
</section>
@endsection
