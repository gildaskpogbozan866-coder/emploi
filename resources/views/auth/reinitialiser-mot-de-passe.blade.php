@extends('layouts.auth')
@section('title', 'Nouveau mot de passe — Emploi Bouge Bénin')

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
      <div class="auth-panel__tag">Nouveau mot de passe</div>
      <h2 class="auth-panel__title">
        Choisissez un<br>nouveau <span>mot de passe</span>.
      </h2>
      <p class="auth-panel__desc">
        Votre nouveau mot de passe doit contenir au moins 8 caractères. Choisissez quelque chose de mémorable mais difficile à deviner.
      </p>
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

      <h1 class="auth-form-wrap__title">Nouveau<br>mot de passe</h1>
      <p class="auth-form-wrap__sub">Choisissez un mot de passe sécurisé d'au moins 8 caractères.</p>

      <form class="aform" method="POST" action="{{ route('auth.reinitialiser.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="aform__field">
          <label class="aform__label" for="email">Adresse e-mail</label>
          <input class="aform__input @error('email') aform__input--error @enderror"
                 type="email" id="email" name="email"
                 value="{{ old('email', $email) }}"
                 placeholder="vous@exemple.com" required autocomplete="email" />
          @error('email')
            <p class="aform__error">{{ $message }}</p>
          @enderror
        </div>

        <div class="aform__field">
          <label class="aform__label" for="password">Nouveau mot de passe</label>
          <input class="aform__input @error('password') aform__input--error @enderror"
                 type="password" id="password" name="password"
                 placeholder="Min. 8 caractères" required autocomplete="new-password" />
          @error('password')
            <p class="aform__error">{{ $message }}</p>
          @enderror
        </div>

        <div class="aform__field">
          <label class="aform__label" for="password_confirmation">Confirmer le mot de passe</label>
          <input class="aform__input"
                 type="password" id="password_confirmation" name="password_confirmation"
                 placeholder="Répétez le mot de passe" required autocomplete="new-password" />
        </div>

        <button type="submit" class="aform__submit">Enregistrer le nouveau mot de passe</button>
      </form>

    </div>
  </div>

</div>
@endsection
