@extends('layouts.auth')
@section('title', 'Changer mon mot de passe — Emploi Bouge Bénin')

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
      <div class="auth-panel__tag">Sécurité du compte</div>
      <h2 class="auth-panel__title">Changez votre<br><span>mot de passe</span>.</h2>
      <p class="auth-panel__desc">Utilisez un mot de passe long et unique que vous n'utilisez nulle part ailleurs.</p>
      <div class="auth-panel__perks">
        <div class="auth-panel__perk"><span class="auth-panel__perk-icon"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span> Minimum 8 caractères</div>
        <div class="auth-panel__perk"><span class="auth-panel__perk-icon"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span> Mélangez lettres, chiffres et symboles</div>
        <div class="auth-panel__perk"><span class="auth-panel__perk-icon"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span> Ne réutilisez pas un ancien mot de passe</div>
      </div>
    </div>
    <div class="auth-panel__footer">© {{ date('Y') }} Emploi Bouge Bénin</div>
  </div>

  <div class="auth-form-panel">
    <div class="auth-form-wrap">

      <a href="javascript:history.back()" class="auth-form-wrap__back">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        Retour
      </a>

      <h1 class="auth-form-wrap__title">Changer mon<br>mot de passe</h1>
      <p class="auth-form-wrap__sub">Connecté en tant que <strong>{{ auth()->user()->email }}</strong>.</p>

      @if(session('success'))
        <div style="background:#d1fae5;border:1px solid #6ee7b7;border-radius:8px;padding:12px 16px;font-size:.88rem;color:#065f46;margin-bottom:20px;display:flex;align-items:center;gap:10px">
          <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
          {{ session('success') }}
        </div>
      @endif

      <form class="aform" method="POST" action="{{ route('auth.changer-mot-de-passe.store') }}">
        @csrf

        <div class="aform__field">
          <label class="aform__label" for="mot_de_passe_actuel">Mot de passe actuel</label>
          <input class="aform__input @error('mot_de_passe_actuel') aform__input--error @enderror"
                 type="password" id="mot_de_passe_actuel" name="mot_de_passe_actuel"
                 placeholder="Votre mot de passe actuel" required autocomplete="current-password" />
          @error('mot_de_passe_actuel')
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
          <label class="aform__label" for="password_confirmation">Confirmer le nouveau mot de passe</label>
          <input class="aform__input"
                 type="password" id="password_confirmation" name="password_confirmation"
                 placeholder="Répétez le nouveau mot de passe" required autocomplete="new-password" />
        </div>

        <button type="submit" class="aform__submit">Enregistrer le nouveau mot de passe</button>
      </form>

    </div>
  </div>

</div>
@endsection
