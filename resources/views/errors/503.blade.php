@extends('layouts.app')
@section('title', 'Site en maintenance — Emploi Bouge Bénin')

@section('content')
<section class="error-page">
  <div class="error-page__inner">
    <svg width="56" height="56" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="1.5" style="margin-bottom:16px">
      <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
      <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
    </svg>
    <div class="error-page__code" style="color:#185FA5">503</div>
    <h1 class="error-page__title">Site en maintenance</h1>
    <p class="error-page__desc">
      La plateforme est temporairement indisponible pour une mise à jour.<br>
      Nous serons de retour très bientôt. Merci de votre patience.
    </p>
    <div class="error-page__actions">
      <a href="{{ url('/') }}" class="btn btn--yellow">Réessayer</a>
    </div>
  </div>
</section>
@endsection
