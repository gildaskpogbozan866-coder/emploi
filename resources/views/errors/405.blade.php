@extends('layouts.app')
@section('title', 'Méthode non autorisée — Emploi Bouge Bénin')

@section('content')
<section class="error-page">
  <div class="error-page__inner">
    <svg width="56" height="56" fill="none" viewBox="0 0 24 24" stroke="#DC2626" stroke-width="1.5" style="margin-bottom:16px">
      <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
    </svg>
    <div class="error-page__code" style="color:#DC2626">405</div>
    <h1 class="error-page__title">Méthode non autorisée</h1>
    <p class="error-page__desc">
      Cette action n'est pas autorisée sur cette ressource.<br>
      Si vous avez suivi un lien, il est peut-être obsolète.
    </p>
    <div class="error-page__actions">
      <button onclick="history.back()" class="btn btn--yellow">Retour en arrière</button>
      <a href="{{ route('home') }}" class="btn btn--outline">Accueil</a>
    </div>
  </div>
</section>
@endsection
