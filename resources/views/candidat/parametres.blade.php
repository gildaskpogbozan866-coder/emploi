@extends('layouts.candidat')
@section('title', 'Paramètres')

@section('sidebar')
@include('candidat._sidebar')
@endsection

@section('content')
<div class="cand-page-header">
  <div class="cand-page-header__left">
    <h1 class="cand-page-header__title">Paramètres du compte</h1>
    <p class="cand-page-header__sub">Gérez votre adresse e-mail et vos préférences</p>
  </div>
</div>

<div class="cand-card" style="max-width:560px;margin-bottom:18px">
  <div class="cand-card__head">
    <h2 class="cand-card__title">Identifiants de connexion</h2>
  </div>
  <form method="POST" action="{{ route('candidat.parametres.update') }}">
    @csrf @method('PUT')

    <div class="cand-form-group">
      <label class="cand-form-label">Adresse e-mail <span class="req">*</span></label>
      <input class="cand-form-input {{ $errors->has('email') ? 'border-red-400' : '' }}"
             type="email" name="email" value="{{ old('email', $user->email) }}" required>
      @error('email')<p style="color:#e53e3e;font-size:12px;margin-top:4px">{{ $message }}</p>@enderror
    </div>

    <div class="cand-form-group">
      <label class="cand-form-label">Téléphone</label>
      <input class="cand-form-input" type="tel" name="tel" value="{{ old('tel', $user->tel) }}" placeholder="+229 01 00 00 00">
    </div>

    <div style="background:#fffbeb;border:1px solid #fcd34d;border-radius:8px;padding:12px 16px;margin-bottom:16px;font-size:13px;color:#92400e">
      ⚠ La modification de votre e-mail nécessitera une vérification par code OTP lors de votre prochaine connexion.
    </div>

    <div class="cand-form-actions">
      <button type="submit" class="cand-btn cand-btn--primary">Sauvegarder</button>
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
