@extends('layouts.recruteur')
@section('title', 'Paramètres')

@section('sidebar')
@include('recruteur._sidebar')
@endsection

@section('content')
<div class="rec-topbar">
  <div class="rec-topbar__left">
    <h1>Paramètres du compte</h1>
    <p>Sécurité et gestion de votre compte recruteur</p>
  </div>
</div>

@include('partials._statut-compte')

{{-- Informations non modifiables --}}
<div class="rec-card" style="max-width:560px;margin-bottom:20px">
  <div class="rec-card__head">
    <span class="rec-card__title">Informations de connexion</span>
  </div>
  <div class="rec-card__body">
    <p style="font-size:13px;color:#6b7280;margin:0 0 10px">Votre adresse e-mail est votre identifiant de connexion. Elle ne peut pas être modifiée.</p>
    <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:12px 16px;font-size:14px;color:#374151">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:middle;margin-right:8px;color:#6b7280"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
      {{ auth()->user()->email }}
    </div>
  </div>
</div>

{{-- Mot de passe --}}
<div class="rec-card" style="max-width:560px;margin-bottom:20px">
  <div class="rec-card__head">
    <span class="rec-card__title">Changer le mot de passe</span>
  </div>
  <div class="rec-card__body">

    @if(session('mdp_success'))
      <div style="background:#d1fae5;border:1px solid #6ee7b7;border-radius:8px;padding:10px 14px;font-size:.85rem;color:#065f46;margin-bottom:16px">
        {{ session('mdp_success') }}
      </div>
    @endif

    <form method="POST" action="{{ route('auth.changer-mot-de-passe.store') }}">
      @csrf

      <div class="rec-form-group" style="margin-bottom:14px">
        <label>Mot de passe actuel <span style="color:#e53e3e">*</span></label>
        <input type="password" name="mot_de_passe_actuel" placeholder="••••••••" autocomplete="current-password"
               style="{{ $errors->has('mot_de_passe_actuel') ? 'border-color:#e53e3e' : '' }}">
        @error('mot_de_passe_actuel')<small style="color:#e53e3e">{{ $message }}</small>@enderror
      </div>

      <div class="rec-form-grid" style="margin-bottom:18px">
        <div class="rec-form-group">
          <label>Nouveau mot de passe <span style="color:#e53e3e">*</span></label>
          <input type="password" name="password" placeholder="Min. 8 caractères" autocomplete="new-password"
                 style="{{ $errors->has('password') ? 'border-color:#e53e3e' : '' }}">
          @error('password')<small style="color:#e53e3e">{{ $message }}</small>@enderror
        </div>
        <div class="rec-form-group">
          <label>Confirmer <span style="color:#e53e3e">*</span></label>
          <input type="password" name="password_confirmation" placeholder="Répétez" autocomplete="new-password">
        </div>
      </div>

      <button type="submit" class="rec-btn rec-btn--yellow">Enregistrer le nouveau mot de passe</button>
    </form>
  </div>
</div>

{{-- Zone danger --}}
<div class="rec-card" style="max-width:560px;border-color:#fca5a5">
  <div class="rec-card__head" style="border-color:#fca5a5">
    <span class="rec-card__title" style="color:#c53030">Zone de danger</span>
  </div>
  <div class="rec-card__body">
    <p style="font-size:13.5px;color:#64748b;margin:0 0 16px;line-height:1.6">La suppression de votre compte est irréversible. Toutes vos offres, candidatures reçues et données seront définitivement supprimées.</p>
    <button type="button" onclick="alert('Pour supprimer votre compte recruteur, contactez : support@emploibouge.bj')"
            class="rec-btn rec-btn--danger">
      Supprimer mon compte recruteur
    </button>
  </div>
</div>
@endsection
