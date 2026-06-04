@extends('layouts.auth')
@section('title', 'Compte confirmé — Emploi Bouge Bénin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/compte-confirme.css') }}">
@endsection

@section('content')
@php
  $user = auth()->user();
  $role = $user?->getRoleNames()->first() ?? 'candidat';
@endphp

<div class="auth-page">

  {{-- Panneau gauche --}}
  <div class="auth-panel">
    <a href="{{ route('home') }}" class="auth-panel__logo">
      <span class="auth-panel__logo-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
      </span>
      <span class="auth-panel__logo-text">Emploi Bouge Bénin</span>
    </a>

    <div class="auth-panel__body">
      <div class="auth-panel__tag">Bienvenue !</div>
      <h2 class="auth-panel__title">
        Votre compte est<br>maintenant <span>actif</span>.
      </h2>
      <p class="auth-panel__desc">
        Vous faites désormais partie de la communauté Emploi Bouge Bénin.
        Explorez les offres, déposez votre CV ou recrutez les meilleurs talents.
      </p>
      <div class="auth-panel__perks">
        <div class="auth-panel__perk">
          <span class="auth-panel__perk-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
          </span>
          Profil vérifié et sécurisé
        </div>
        <div class="auth-panel__perk">
          <span class="auth-panel__perk-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
          </span>
          Accès complet à la plateforme
        </div>
        <div class="auth-panel__perk">
          <span class="auth-panel__perk-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
          </span>
          Notifications d'offres activées
        </div>
      </div>
    </div>

    <div class="auth-panel__footer">© {{ date('Y') }} Emploi Bouge Bénin</div>
  </div>

  {{-- Panneau droit --}}
  <div class="auth-form-panel">
    <div class="auth-form-wrap">

      <div class="cc-success-icon">
        <svg width="44" height="44" fill="none" viewBox="0 0 24 24" stroke="#fff" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
        <div class="cc-success-ring cc-ring1"></div>
        <div class="cc-success-ring cc-ring2"></div>
      </div>

      <h1 class="auth-form-wrap__title">Compte confirmé<br>avec succès !</h1>
      <p class="auth-form-wrap__sub">
        Bienvenue, <strong>{{ $user?->prenom }}</strong>. Votre espace personnel est prêt.
      </p>

      @if($user)
      <div class="cc-user-badge">
        <div class="cc-user-badge__avatar">{{ mb_strtoupper(mb_substr($user->prenom, 0, 1)) }}</div>
        <div>
          <div class="cc-user-badge__name">{{ $user->prenom }} {{ $user->nom }}</div>
          <div class="cc-user-badge__role">
            {{ match($role) {
              'recruteur' => 'Recruteur / Entreprise',
              'talent'    => 'Talent',
              'admin'     => 'Administrateur',
              default     => 'Candidat',
            } }}
          </div>
        </div>
      </div>
      @endif

      <div class="cc-label">Accédez à votre espace</div>

      <div class="cc-dashboards">

        @if(in_array($role, ['candidat', 'admin']))
        <a href="{{ route('candidat.dashboard') }}" class="cc-dash-btn {{ $role === 'candidat' ? 'cc-dash-btn--active' : '' }}">
          <div class="cc-dash-btn__icon cc-dash-btn__icon--candidat">
            <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
          </div>
          <div class="cc-dash-btn__info">
            <div class="cc-dash-btn__title">Espace Candidat</div>
            <div class="cc-dash-btn__sub">Postule aux offres, dépose ton CV</div>
          </div>
          <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
        @endif

        @if(in_array($role, ['recruteur', 'admin']))
        <a href="{{ route('recruteur.dashboard') }}" class="cc-dash-btn {{ $role === 'recruteur' ? 'cc-dash-btn--active' : '' }}">
          <div class="cc-dash-btn__icon cc-dash-btn__icon--recruteur">
            <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg>
          </div>
          <div class="cc-dash-btn__info">
            <div class="cc-dash-btn__title">Espace Recruteur</div>
            <div class="cc-dash-btn__sub">Publie des offres, accède aux CV</div>
          </div>
          <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
        @endif

        @if(in_array($role, ['talent', 'admin']))
        <a href="{{ route('talent.dashboard') }}" class="cc-dash-btn {{ $role === 'talent' ? 'cc-dash-btn--active' : '' }}">
          <div class="cc-dash-btn__icon cc-dash-btn__icon--talent">
            <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
          </div>
          <div class="cc-dash-btn__info">
            <div class="cc-dash-btn__title">Espace Talent</div>
            <div class="cc-dash-btn__sub">Gère ton profil compétence</div>
          </div>
          <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
        @endif

        @if($role === 'admin')
        <a href="{{ route('admin.dashboard') }}" class="cc-dash-btn cc-dash-btn--active">
          <div class="cc-dash-btn__icon" style="background:linear-gradient(135deg,#7c3aed,#4c1d95)">
            <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
          </div>
          <div class="cc-dash-btn__info">
            <div class="cc-dash-btn__title">Panneau Admin</div>
            <div class="cc-dash-btn__sub">Gestion complète de la plateforme</div>
          </div>
          <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
        @endif

      </div>

      <p class="cc-home-link">
        <a href="{{ route('home') }}">
          <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
          Retour à l'accueil
        </a>
      </p>

    </div>
  </div>

</div>
@endsection
