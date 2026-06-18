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
    <button type="button" onclick="document.getElementById('modal-delete-compte').style.display='flex'"
            class="rec-btn rec-btn--danger">
      Supprimer mon compte recruteur
    </button>
  </div>
</div>

{{-- Modal confirmation suppression --}}
<div id="modal-delete-compte"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.55);z-index:9999;align-items:center;justify-content:center;padding:20px">
  <div style="background:#fff;border-radius:16px;padding:32px 28px;max-width:440px;width:100%;box-shadow:0 20px 60px rgba(0,0,0,.25)">
    <div style="width:52px;height:52px;border-radius:50%;background:#fef2f2;border:2px solid #fca5a5;display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
      <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#ef4444" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
    </div>
    <h3 style="font-family:var(--font-body);font-size:17px;font-weight:800;color:#042C53;text-align:center;margin:0 0 8px">Supprimer votre compte ?</h3>
    <p style="font-family:var(--font-body);font-size:13px;color:#64748b;text-align:center;line-height:1.6;margin:0 0 20px">
      Cette action est <strong>irréversible</strong>. Toutes vos offres, candidatures reçues, abonnements et données seront définitivement supprimés.
    </p>
    <p style="font-family:var(--font-body);font-size:13px;font-weight:700;color:#374151;margin:0 0 8px">
      Tapez <code style="background:#f1f5f9;padding:2px 6px;border-radius:4px;color:#ef4444">SUPPRIMER</code> pour confirmer :
    </p>
    <form method="POST" action="{{ route('recruteur.compte.delete') }}">
      @csrf
      @method('DELETE')
      <input type="text" name="confirmation" id="confirm-input" autocomplete="off"
             placeholder="SUPPRIMER"
             style="width:100%;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:10px;font-family:var(--font-body);font-size:14px;margin-bottom:6px;box-sizing:border-box;outline:none"
             oninput="document.getElementById('btn-delete-confirm').disabled = this.value !== 'SUPPRIMER'">
      @error('confirmation')
        <p style="font-size:12px;color:#ef4444;margin:0 0 10px">{{ $message }}</p>
      @enderror
      <div style="display:flex;gap:10px;margin-top:16px">
        <button type="button"
                onclick="document.getElementById('modal-delete-compte').style.display='none';document.getElementById('confirm-input').value='';document.getElementById('btn-delete-confirm').disabled=true"
                style="flex:1;padding:11px;border:1.5px solid #e2e8f0;border-radius:10px;background:#fff;font-family:var(--font-body);font-size:14px;font-weight:600;color:#374151;cursor:pointer">
          Annuler
        </button>
        <button type="submit" id="btn-delete-confirm" disabled
                style="flex:1;padding:11px;border:none;border-radius:10px;background:#ef4444;font-family:var(--font-body);font-size:14px;font-weight:700;color:#fff;cursor:pointer;opacity:.5;transition:opacity .2s"
                onmouseover="if(!this.disabled)this.style.opacity='1'"
                onmouseout="if(!this.disabled)this.style.opacity='.9'"
                onclick="this.style.opacity='.7'">
          Supprimer définitivement
        </button>
      </div>
    </form>
  </div>
</div>

<script>
// Activer le bouton quand le champ est correct
document.getElementById('btn-delete-confirm').disabled = true;
// Fermer la modal en cliquant en dehors
document.getElementById('modal-delete-compte').addEventListener('click', function(e) {
  if (e.target === this) {
    this.style.display = 'none';
    document.getElementById('confirm-input').value = '';
    document.getElementById('btn-delete-confirm').disabled = true;
    document.getElementById('btn-delete-confirm').style.opacity = '.5';
  }
});
// Synchroniser l'opacité du bouton avec son état
document.getElementById('confirm-input').addEventListener('input', function() {
  const btn = document.getElementById('btn-delete-confirm');
  btn.style.opacity = this.value === 'SUPPRIMER' ? '1' : '.5';
});
@if($errors->has('confirmation'))
document.getElementById('modal-delete-compte').style.display = 'flex';
@endif
</script>
@endsection
