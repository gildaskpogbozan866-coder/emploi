@extends('layouts.auth')
@section('title', 'Connexion — Emploi Bouge Bénin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/connexion.css') }}">
@endsection

@section('content')
<div class="auth-page">

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
      <div class="auth-panel__tag">Connexion sécurisée</div>
      <h2 class="auth-panel__title">Bon retour parmi nous !</h2>
      <p class="auth-panel__desc">Connectez-vous à votre espace avec votre adresse e-mail et votre mot de passe.</p>
      <div class="auth-panel__perks">
        <div class="auth-panel__perk"><span class="auth-panel__perk-icon">✓</span> Accès rapide à votre tableau de bord</div>
        <div class="auth-panel__perk"><span class="auth-panel__perk-icon">✓</span> Données sécurisées et chiffrées</div>
        <div class="auth-panel__perk"><span class="auth-panel__perk-icon">✓</span> Connexion maintenue sur cet appareil</div>
      </div>
    </div>
    <div class="auth-panel__footer">© {{ date('Y') }} Emploi Bouge Bénin</div>
  </div>

  <div class="auth-form-panel">
    <div class="auth-form-wrap">
      <a href="{{ route('home') }}" class="auth-form-wrap__back">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        Retour à l'accueil
      </a>

      <h1 class="auth-form-wrap__title">Se connecter</h1>
      <p class="auth-form-wrap__sub">Entrez vos identifiants pour accéder à votre espace.</p>

      @if(session('success'))
        <div style="background:#d1fae5;border:1px solid #6ee7b7;border-radius:8px;padding:10px 14px;font-size:.85rem;color:#065f46;margin-bottom:16px">
          {{ session('success') }}
        </div>
      @endif

      @error('credentials')
        <div style="background:#fef2f2;border:1px solid #fca5a5;border-radius:8px;padding:12px 16px;font-size:.88rem;color:#991b1b;margin-bottom:18px;display:flex;align-items:center;gap:10px">
          <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="flex-shrink:0"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          {{ $message }}
        </div>
      @enderror

      @error('session')
        <div style="background:#fffbeb;border:1px solid #fcd34d;border-radius:8px;padding:12px 16px;font-size:.88rem;color:#92400e;margin-bottom:18px;display:flex;align-items:center;gap:10px">
          <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
          {{ $message }}
        </div>
      @enderror

      <form class="aform" method="POST" action="{{ route('auth.connexion.store') }}">
        @csrf

        <div class="aform__field">
          <label class="aform__label" for="email">Adresse e-mail</label>
          <input class="aform__input"
                 type="email" id="email" name="email"
                 value="{{ old('email') }}"
                 placeholder="vous@exemple.com" required autocomplete="email" />
        </div>

        <div class="aform__field">
          <label class="aform__label" for="password">
            Mot de passe
            <a href="{{ route('auth.mot-de-passe-oublie') }}" style="float:right;font-weight:400;font-size:.8rem;color:#185FA5">Mot de passe oublié ?</a>
          </label>
          <input class="aform__input"
                 type="password" id="password" name="password"
                 placeholder="••••••••" required autocomplete="current-password" />
        </div>

        <label class="aform__check" style="margin-bottom:18px">
          <input type="checkbox" name="remember" value="1" />
          Se souvenir de moi
        </label>

        <button type="submit" class="aform__submit">Se connecter</button>

        <p class="aform__switch">
          Pas encore de compte ?
          <a href="{{ route('auth.inscription') }}">Créer un compte</a>
        </p>
      </form>
    </div>
  </div>

</div>
@endsection
