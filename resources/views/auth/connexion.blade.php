@extends('layouts.auth')
@section('title', 'Connexion | Emploi Bouge Bénin')

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
        <div class="auth-panel__perk"><span class="auth-panel__perk-icon"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span> Accès rapide à votre tableau de bord</div>
        <div class="auth-panel__perk"><span class="auth-panel__perk-icon"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span> Données sécurisées et chiffrées</div>
        <div class="auth-panel__perk"><span class="auth-panel__perk-icon"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span> Connexion maintenue sur cet appareil</div>
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

      {{-- Google OAuth (masqué si identifiants non configurés) --}}
      @if(config('services.google.client_id'))
      <a href="{{ route('auth.google') }}" class="google-btn">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" style="flex-shrink:0">
          <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
          <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
          <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
          <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
        </svg>
        Continuer avec Google
      </a>

      <div class="auth-divider"><span>ou</span></div>
      @endif

      <form class="aform" method="POST" action="{{ route('auth.connexion.store') }}">
        @csrf

        <div class="aform__field">
          <label class="aform__label" for="email">Adresse e-mail</label>
          <input class="aform__input @error('email') aform__input--error @enderror"
                 type="email" id="email" name="email"
                 value="{{ old('email') }}"
                 placeholder="vous@exemple.com" required autocomplete="email" />
          @error('email')<p class="field__server-error">{{ $message }}</p>@enderror
        </div>

        <div class="aform__field">
          <label class="aform__label" for="password">
            Mot de passe
            <a href="{{ route('auth.mot-de-passe-oublie') }}" style="float:right;font-weight:400;font-size:.8rem;color:#185FA5">Mot de passe oublié ?</a>
          </label>
          <input class="aform__input @error('password') aform__input--error @enderror"
                 type="password" id="password" name="password"
                 placeholder="••••••••" required autocomplete="current-password" />
          @error('password')<p class="field__server-error">{{ $message }}</p>@enderror
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
