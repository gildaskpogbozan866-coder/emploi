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
      <p class="auth-panel__desc">Connectez-vous à votre espace en quelques secondes grâce au code OTP.</p>
      <div class="auth-panel__perks">
        <div class="auth-panel__perk"><span class="auth-panel__perk-icon">✓</span> Aucun mot de passe à retenir</div>
        <div class="auth-panel__perk"><span class="auth-panel__perk-icon">✓</span> Code envoyé par e-mail</div>
        <div class="auth-panel__perk"><span class="auth-panel__perk-icon">✓</span> Connexion sécurisée</div>
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
      <p class="auth-form-wrap__sub">Entrez votre e-mail — vous recevrez un code OTP à 6 chiffres.</p>

      @if(session('otp_debug'))
        <div style="background:#fff3cd;border:1px solid #ffc107;border-radius:8px;padding:10px 14px;font-size:.82rem;margin-bottom:16px">
          <strong>⚠ Mode dev — Code OTP :</strong> <code style="font-size:1.1em;letter-spacing:2px">{{ session('otp_debug') }}</code>
        </div>
      @endif

      <form class="aform" method="POST" action="{{ route('auth.connexion.otp') }}">
        @csrf
        <div class="aform__field">
          <label class="aform__label" for="email">Adresse e-mail</label>
          <input class="aform__input @error('email') aform__input--error @enderror"
                 type="email" id="email" name="email"
                 value="{{ old('email') }}"
                 placeholder="vous@exemple.com" required autocomplete="email" />
          @error('email')
            <p class="aform__error">{{ $message }}</p>
          @enderror
        </div>

        <button type="submit" class="aform__submit">Envoyer le code OTP</button>

        <p class="aform__switch">
          Pas encore de compte ?
          <a href="{{ route('auth.inscription') }}">Créer un compte</a>
        </p>
      </form>
    </div>
  </div>

</div>
@endsection
