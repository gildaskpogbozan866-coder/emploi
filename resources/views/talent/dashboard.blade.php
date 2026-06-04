@extends('layouts.dashboard')
@section('title', 'Mon espace Talent')
@section('space-label', 'Espace Talent')

@section('sidebar')
<a href="{{ route('home') }}" class="dash-sidebar__logo">
  <span>Emploi Bouge</span><small>Bénin · Talent</small>
</a>
<div class="dash-sidebar__user">
  <div class="dash-sidebar__avatar">{{ auth()->user()->initiale }}</div>
  <div class="dash-sidebar__info">
    <div class="dash-sidebar__name">{{ auth()->user()->nom_complet }}</div>
    <div class="dash-sidebar__role">{{ auth()->user()->metier ?? 'Talent' }}</div>
  </div>
</div>
<ul class="dash-nav">
  <li class="dash-nav__item {{ request()->routeIs('talent.dashboard') ? 'active' : '' }}">
    <a href="{{ route('talent.dashboard') }}">Tableau de bord</a>
  </li>
  <li class="dash-nav__item {{ request()->routeIs('talent.profil*') ? 'active' : '' }}">
    <a href="{{ route('talent.profil') }}">Mon profil</a>
  </li>
  <li class="dash-nav__item {{ request()->routeIs('talent.messagerie*') ? 'active' : '' }}">
    <a href="{{ route('talent.messagerie') }}">Messagerie</a>
  </li>
  <li class="dash-nav__item {{ request()->routeIs('talent.abonnement*') ? 'active' : '' }}">
    <a href="{{ route('talent.abonnement') }}">Abonnement Premium</a>
  </li>
  <li class="dash-nav__item {{ request()->routeIs('talent.parametres*') ? 'active' : '' }}">
    <a href="{{ route('talent.parametres') }}">Paramètres</a>
  </li>
</ul>
@endsection

@section('content')
<div class="dash-content">
  <div class="dash-content__header">
    <h1 class="dash-content__title">Bonjour, {{ auth()->user()->prenom }} 👋</h1>
  </div>

  <div class="dash-stats">
    <div class="dash-stat-card">
      <div class="dash-stat-card__num">{{ $stats['vues_profil'] }}</div>
      <div class="dash-stat-card__label">Vues de votre profil</div>
    </div>
    <div class="dash-stat-card dash-stat-card--{{ $stats['plan'] === 'premium' ? 'yellow' : 'blue' }}">
      <div class="dash-stat-card__num">{{ ucfirst($stats['plan']) }}</div>
      <div class="dash-stat-card__label">Plan actuel</div>
    </div>
  </div>

  @if(!$profil)
    <div class="dash-empty" style="margin-top:32px">
      <p>Vous n'avez pas encore créé votre profil Talent.</p>
      <a href="{{ route('talent.profil.create') }}" class="btn btn--yellow">Créer mon profil</a>
    </div>
  @else
    <div class="dash-section">
      <h2 class="dash-section__title">Mon profil Talent</h2>
      <div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:24px">
        <p><strong>Métier :</strong> {{ $profil->metier }}</p>
        <p><strong>Pays :</strong> {{ $profil->pays }}</p>
        <p><strong>Plan :</strong> {{ ucfirst($profil->plan) }}</p>
        <p><strong>Vues :</strong> {{ $profil->vues }}</p>
        <a href="{{ route('talent.profil.edit') }}" class="btn btn--blue" style="margin-top:16px;display:inline-block">Modifier mon profil</a>
      </div>
    </div>
  @endif
</div>
@endsection
