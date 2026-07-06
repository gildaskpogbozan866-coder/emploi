@extends('layouts.auth')
@section('title', 'Mot de passe oublié | Emploi Bouge Bénin')

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
      <div class="auth-panel__tag">Réinitialisation</div>
      <h2 class="auth-panel__title">
        Récupérez l'accès<br>à votre <span>compte</span>.
      </h2>
      <p class="auth-panel__desc">
        Entrez votre adresse e-mail et nous vous enverrons un lien pour créer un nouveau mot de passe.
      </p>
      <div class="auth-panel__steps">
        <div class="auth-step">
          <span class="auth-step__num">1</span>
          <span class="auth-step__label">Entrez votre adresse e-mail</span>
        </div>
        <div class="auth-step">
          <span class="auth-step__num">2</span>
          <span class="auth-step__label">Recevez le lien par e-mail</span>
        </div>
        <div class="auth-step">
          <span class="auth-step__num">3</span>
          <span class="auth-step__label">Choisissez un nouveau mot de passe</span>
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

      <h1 class="auth-form-wrap__title">Mot de passe<br>oublié ?</h1>
      <p class="auth-form-wrap__sub">
        Pas de panique. Indiquez votre adresse e-mail et nous vous enverrons un lien de réinitialisation.
      </p>

      @if(session('success'))
        <div style="background:#d1fae5;border:1px solid #6ee7b7;border-radius:8px;padding:12px 16px;font-size:.85rem;color:#065f46;margin-bottom:20px;display:flex;align-items:center;gap:10px">
          <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
          {{ session('success') }}
        </div>
      @endif

      <form class="aform" method="POST" action="{{ route('auth.mot-de-passe-oublie.store') }}">
        @csrf
        <div class="aform__field">
          <label class="aform__label" for="email">Adresse e-mail</label>
          <input class="aform__input @error('email') aform__input--error @enderror"
                 type="email" id="email" name="email"
                 value="{{ old('email') }}"
                 placeholder="vous@exemple.bj" required autocomplete="email" />
          @error('email')
            <p class="aform__error">{{ $message }}</p>
          @enderror
        </div>

        @if($recaptchaActif)
          <div class="g-recaptcha" data-sitekey="{{ $recaptchaSiteKey }}" style="margin-bottom:16px"></div>
        @endif
        @error('recaptcha')
          <p class="aform__error">{{ $message }}</p>
        @enderror

        <button type="submit" class="aform__submit">Envoyer le lien de réinitialisation</button>

        <p class="aform__switch">
          Vous vous souvenez de votre mot de passe ?
          <a href="{{ route('auth.connexion') }}">Se connecter</a>
        </p>
      </form>

    </div>
  </div>

</div>
@endsection
