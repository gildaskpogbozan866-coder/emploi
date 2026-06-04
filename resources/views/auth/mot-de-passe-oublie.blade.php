@extends('layouts.auth')
@section('title', 'Accès magique — Emploi Bouge Bénin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/mot-de-passe-oublie.css') }}">
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
      <div class="auth-panel__tag">Accès magique</div>
      <h2 class="auth-panel__title">
        Reconnectez-vous<br>sans <span>mot de passe</span>.
      </h2>
      <p class="auth-panel__desc">
        Entrez votre e-mail — un code OTP à 6 chiffres vous sera envoyé
        pour accéder instantanément à votre espace.
      </p>
      <div class="auth-panel__steps">
        <div class="auth-step">
          <span class="auth-step__num">1</span>
          <span class="auth-step__label">Entrez votre adresse e-mail</span>
        </div>
        <div class="auth-step">
          <span class="auth-step__num">2</span>
          <span class="auth-step__label">Recevez votre code OTP</span>
        </div>
        <div class="auth-step">
          <span class="auth-step__num">3</span>
          <span class="auth-step__label">Accédez à votre espace</span>
        </div>
      </div>
    </div>

    <div class="auth-panel__footer">© {{ date('Y') }} Emploi Bouge Bénin</div>
  </div>

  <div class="auth-form-panel">
    <div class="auth-form-wrap">

      <a href="{{ route('auth.connexion') }}" class="auth-form-wrap__back">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        Retour à la connexion
      </a>

      <h1 class="auth-form-wrap__title">Accès<br>magique</h1>
      <p class="auth-form-wrap__sub">
        Entrez l'e-mail lié à votre compte pour recevoir un code OTP de connexion.
      </p>

      @if(session('otp_debug'))
        <div style="background:#fff3cd;border:1px solid #ffc107;border-radius:8px;padding:10px 14px;font-size:.82rem;margin-bottom:16px">
          <strong>⚠ Dev — Code OTP :</strong> <code style="font-size:1.2em;letter-spacing:4px;font-weight:700">{{ session('otp_debug') }}</code>
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

        <button type="submit" class="aform__submit">Envoyer mon code OTP</button>

        <p class="aform__switch">
          Vous avez déjà un code ?
          <a href="{{ route('auth.verification-email') }}">Saisir le code</a>
        </p>
        <p class="aform__switch" style="margin-top:8px;">
          Pas encore de compte ?
          <a href="{{ route('auth.inscription') }}">Créer un compte gratuitement</a>
        </p>
      </form>

    </div>
  </div>

</div>
@endsection
