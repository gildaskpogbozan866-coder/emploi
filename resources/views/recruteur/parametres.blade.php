@extends('layouts.recruteur')
@section('title', 'Paramètres')

@section('sidebar')
@include('recruteur._sidebar')
@endsection

@section('content')
<div class="rec-topbar">
  <div class="rec-topbar__left">
    <h1>Paramètres du compte</h1>
    <p>Gérez votre adresse e-mail et vos identifiants de connexion</p>
  </div>
</div>

<div class="rec-card" style="max-width:560px;margin-bottom:20px">
  <div class="rec-card__head">
    <span class="rec-card__title">Identifiants de connexion</span>
  </div>
  <div class="rec-card__body">
    <form method="POST" action="{{ route('recruteur.parametres.update') }}">
      @csrf @method('PUT')

      <div class="rec-form-group" style="margin-bottom:16px">
        <label>Adresse e-mail <span style="color:#e53e3e">*</span></label>
        <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
        @error('email')<small style="color:#e53e3e">{{ $message }}</small>@enderror
      </div>

      <div class="rec-form-group" style="margin-bottom:16px">
        <label>Téléphone</label>
        <input type="tel" name="tel" value="{{ old('tel', $user->tel) }}" placeholder="+229 01 00 00 00">
      </div>

      <div style="background:#fffbeb;border:1px solid #fcd34d;border-radius:8px;padding:12px 16px;margin-bottom:18px;font-size:13px;color:#92400e">
        ⚠ La modification de votre e-mail nécessitera une vérification par code OTP lors de votre prochaine connexion.
      </div>

      <button type="submit" class="rec-btn rec-btn--primary">Sauvegarder les modifications</button>
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
