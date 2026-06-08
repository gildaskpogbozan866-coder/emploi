@extends('layouts.candidat')
@section('title', 'Paramètres')

@section('sidebar')
@include('candidat._sidebar')
@endsection

@section('content')
<div class="cand-page-header">
  <div class="cand-page-header__left">
    <h1 class="cand-page-header__title">Paramètres du compte</h1>
    <p class="cand-page-header__sub">Sécurité et gestion de votre compte</p>
  </div>
</div>

@include('partials._statut-compte')

{{-- Informations non modifiables --}}
<div class="cand-card" style="max-width:560px;margin-bottom:18px">
  <div class="cand-card__head">
    <h2 class="cand-card__title">Informations de connexion</h2>
  </div>
  <div style="padding:16px 0 4px">
    <p style="font-size:13px;color:#6b7280;margin:0 0 10px">Votre adresse e-mail est votre identifiant de connexion. Elle ne peut pas être modifiée.</p>
    <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:12px 16px;font-size:14px;color:#374151">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:middle;margin-right:8px;color:#6b7280"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
      {{ auth()->user()->email }}
    </div>
  </div>
</div>

{{-- Mot de passe --}}
<div class="cand-card" style="max-width:560px;margin-bottom:18px">
  <div class="cand-card__head">
    <h2 class="cand-card__title">Changer le mot de passe</h2>
  </div>

  @if(session('mdp_success'))
    <div style="background:#d1fae5;border:1px solid #6ee7b7;border-radius:8px;padding:10px 14px;font-size:.85rem;color:#065f46;margin-bottom:16px">
      {{ session('mdp_success') }}
    </div>
  @endif

  <form method="POST" action="{{ route('auth.changer-mot-de-passe.store') }}">
    @csrf

    <div class="cand-form-group">
      <label class="cand-form-label">Mot de passe actuel <span class="req">*</span></label>
      <input class="cand-form-input @error('mot_de_passe_actuel') cand-form-input--error @enderror"
             type="password" name="mot_de_passe_actuel" placeholder="••••••••" autocomplete="current-password">
      @error('mot_de_passe_actuel')<p style="color:#e53e3e;font-size:12px;margin-top:4px">{{ $message }}</p>@enderror
    </div>

    <div class="cand-form-grid">
      <div class="cand-form-group">
        <label class="cand-form-label">Nouveau mot de passe <span class="req">*</span></label>
        <input class="cand-form-input @error('password') cand-form-input--error @enderror"
               type="password" name="password" placeholder="Min. 8 caractères" autocomplete="new-password">
        @error('password')<p style="color:#e53e3e;font-size:12px;margin-top:4px">{{ $message }}</p>@enderror
      </div>
      <div class="cand-form-group">
        <label class="cand-form-label">Confirmer <span class="req">*</span></label>
        <input class="cand-form-input" type="password" name="password_confirmation"
               placeholder="Répétez" autocomplete="new-password">
      </div>
    </div>

    <div class="cand-form-actions">
      <button type="submit" class="cand-btn cand-btn--primary">Enregistrer le nouveau mot de passe</button>
    </div>
  </form>
</div>

{{-- Zone danger --}}
<div class="cand-card" style="max-width:560px;border-color:#fca5a5">
  <div class="cand-card__head" style="border-color:#fca5a5">
    <h2 class="cand-card__title" style="color:#c53030">Zone de danger</h2>
  </div>
  <p style="font-size:13.5px;color:#64748b;margin:0 0 16px;line-height:1.6">La suppression de votre compte est irréversible. Toutes vos données (candidatures, CVs, messages) seront définitivement supprimées.</p>
  <button type="button" onclick="alert('Pour supprimer votre compte, contactez notre support : support@emploibouge.bj')"
          class="cand-btn cand-btn--danger">
    Supprimer mon compte
  </button>
</div>
@endsection
