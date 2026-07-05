@extends('layouts.app')
@section('title', 'Commander | ' . $service->nom)

@section('content')
<style>
.cmd-page {
  background: #f0f4f8;
  min-height: 80vh;
  padding: 48px 16px 72px;
}
.cmd-wrap {
  max-width: 660px;
  margin: 0 auto;
}
.cmd-back {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  color: #185FA5;
  font-size: 13.5px;
  margin-bottom: 24px;
  text-decoration: none;
  font-weight: 600;
}
/* Récap service */
.cmd-recap {
  background: linear-gradient(135deg, #042C53, #185FA5);
  border-radius: 16px;
  padding: 22px 24px;
  margin-bottom: 24px;
  display: flex;
  align-items: center;
  gap: 18px;
  flex-wrap: wrap;
}
.cmd-recap__icon {
  width: 52px; height: 52px;
  border-radius: 12px;
  background: rgba(245,200,66,0.18);
  border: 1.5px solid rgba(245,200,66,0.35);
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.cmd-recap__body { flex: 1; min-width: 0; }
.cmd-recap__label {
  font-size: 11px; font-weight: 700;
  color: rgba(255,255,255,.5);
  text-transform: uppercase; letter-spacing: .08em;
  margin: 0 0 4px;
}
.cmd-recap__nom {
  font-weight: 800; color: #fff;
  font-size: 1rem; margin: 0 0 4px;
}
.cmd-recap__delai {
  font-size: 12px; color: rgba(255,255,255,.6);
  margin: 0; display: flex; align-items: center; gap: 4px;
}
.cmd-recap__prix {
  text-align: right; flex-shrink: 0;
}
.cmd-recap__prix-label {
  font-size: 11px; color: rgba(255,255,255,.5);
  margin: 0 0 2px; text-transform: uppercase; letter-spacing: .06em;
}
.cmd-recap__prix-value {
  font-size: 1.7rem; font-weight: 900;
  color: #F5C842; margin: 0;
  letter-spacing: -0.02em; line-height: 1;
}
.cmd-recap__prix-value span {
  font-size: .85rem; font-weight: 600; margin-left: 3px;
}
/* Erreurs */
.cmd-errors {
  background: #fef2f2;
  border: 1.5px solid #fecaca;
  border-radius: 12px;
  padding: 16px 20px;
  margin-bottom: 20px;
}
.cmd-errors__title {
  font-weight: 700; color: #dc2626;
  margin: 0 0 8px; font-size: 13.5px;
  display: flex; align-items: center; gap: 6px;
}
.cmd-errors ul { margin: 0; padding-left: 18px; color: #dc2626; font-size: 13px; line-height: 1.8; }
/* Card formulaire */
.cmd-card {
  background: #fff;
  border: 1.5px solid #e2e8f0;
  border-radius: 18px;
  overflow: hidden;
}
.cmd-card__head {
  padding: 28px 28px 0;
}
.cmd-card__title {
  font-size: 1.2rem; font-weight: 800;
  color: #042C53; margin: 0 0 6px;
}
.cmd-card__sub {
  color: #64748b; font-size: 13.5px;
  margin: 0 0 24px; line-height: 1.55;
}
.cmd-card__body {
  padding: 0 28px 24px;
  display: flex; flex-direction: column; gap: 20px;
}
.cmd-card__foot {
  border-top: 1.5px solid #f1f5f9;
  padding: 20px 28px;
}
/* Champ */
.cmd-field label {
  display: block;
  font-size: 13.5px; font-weight: 700;
  color: #374151; margin-bottom: 8px;
}
.cmd-field label span { font-weight: 400; color: #94a3b8; font-size: 12px; }
.cmd-field__required { color: #e53e3e !important; font-weight: 700 !important; font-size: 13px !important; }
.cmd-input-wrap { position: relative; }
.cmd-input-icon {
  position: absolute; left: 14px; top: 50%;
  transform: translateY(-50%); color: #94a3b8; pointer-events: none;
}
.cmd-input {
  width: 100%;
  padding: 13px 16px 13px 42px;
  border: 1.5px solid #d1d5db;
  border-radius: 10px;
  font-size: 14px; font-family: inherit;
  color: #1e293b; box-sizing: border-box;
  outline: none; transition: border-color .2s, box-shadow .2s;
}
.cmd-input:focus {
  border-color: #185FA5;
  box-shadow: 0 0 0 3px rgba(24,95,165,.1);
}
.cmd-input--error { border-color: #e53e3e; }
.cmd-field__hint {
  font-size: 12px; color: #64748b;
  margin: 6px 0 0; display: flex; align-items: center; gap: 4px;
}
.cmd-field__err {
  color: #dc2626; font-size: 12.5px;
  margin: 6px 0 0; display: flex; align-items: center; gap: 4px;
}
/* Textarea */
.cmd-textarea {
  width: 100%;
  padding: 14px 16px;
  border: 1.5px solid #d1d5db;
  border-radius: 10px;
  font-size: 14px; font-family: inherit;
  color: #1e293b; resize: vertical;
  box-sizing: border-box; line-height: 1.65;
  outline: none; transition: border-color .2s, box-shadow .2s;
  min-height: 120px;
}
.cmd-textarea:focus {
  border-color: #185FA5;
  box-shadow: 0 0 0 3px rgba(24,95,165,.1);
}
/* Zone fichier */
.cmd-file-zone {
  display: flex; align-items: center; gap: 16px;
  padding: 18px 20px;
  border: 2px dashed #cbd5e0;
  border-radius: 12px; cursor: pointer;
  background: #fafafa; transition: all .2s;
}
.cmd-file-zone:hover {
  border-color: #185FA5; background: #f0f7ff;
}
.cmd-file-zone--active {
  border-color: #185FA5; background: #f0f7ff;
}
.cmd-file-icon {
  width: 44px; height: 44px;
  border-radius: 10px; background: #dbeafe;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.cmd-file-text { flex: 1; min-width: 0; }
.cmd-file-name {
  font-size: 14px; font-weight: 600;
  color: #185FA5; margin: 0;
  overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
}
.cmd-file-hint { font-size: 12px; color: #94a3b8; margin: 3px 0 0; }
.cmd-file-info {
  display: none;
  margin-top: 8px; padding: 10px 14px;
  background: #f0f7ff; border: 1px solid #bfdbfe;
  border-radius: 8px; align-items: center;
  justify-content: space-between; gap: 10px;
}
.cmd-file-info--show { display: flex; }
.cmd-file-info span {
  font-size: 13px; font-weight: 600; color: #185FA5;
  overflow: hidden; text-overflow: ellipsis; white-space: nowrap;
}
.cmd-file-clear {
  background: none; border: none; color: #94a3b8;
  cursor: pointer; padding: 0; flex-shrink: 0;
}
/* Alerte livraison */
.cmd-alert {
  background: #fffbeb; border: 1px solid #fde68a;
  border-radius: 10px; padding: 14px 18px;
  margin-bottom: 20px;
  display: flex; gap: 10px; align-items: flex-start;
}
.cmd-alert p { font-size: 13px; color: #92400e; margin: 0; line-height: 1.6; }
/* Boutons */
.cmd-btns { display: flex; gap: 12px; flex-wrap: wrap; }
.cmd-btn-submit {
  flex: 1; min-width: 200px;
  padding: 15px 24px;
  background: #F5C842; color: #042C53;
  border: none; border-radius: 12px;
  font-weight: 800; font-size: 15px;
  cursor: pointer; font-family: inherit;
  display: flex; align-items: center;
  justify-content: center; gap: 8px;
  transition: background .2s, transform .1s;
}
.cmd-btn-submit:hover { background: #e0a800; transform: translateY(-1px); }
.cmd-btn-cancel {
  padding: 15px 20px;
  background: #f1f5f9; color: #374151;
  border-radius: 12px; font-weight: 600;
  font-size: 14px; text-decoration: none;
  display: inline-flex; align-items: center;
  white-space: nowrap;
}
.cmd-footer-note {
  text-align: center; font-size: 12px;
  color: #94a3b8; margin-top: 20px;
  display: flex; align-items: center;
  justify-content: center; gap: 5px;
}

/* ── RESPONSIVE ── */
@media (max-width: 600px) {
  .cmd-page { padding: 28px 12px 56px; }
  .cmd-recap { padding: 16px; gap: 12px; }
  .cmd-recap__icon { width: 42px; height: 42px; }
  .cmd-recap__nom { font-size: .9rem; }
  .cmd-recap__prix-value { font-size: 1.4rem; }
  .cmd-card__head { padding: 20px 16px 0; }
  .cmd-card__body { padding: 0 16px 20px; gap: 16px; }
  .cmd-card__foot { padding: 16px; }
  .cmd-card__title { font-size: 1.05rem; }
  .cmd-file-zone { padding: 14px; gap: 12px; }
  .cmd-file-icon { width: 38px; height: 38px; }
  .cmd-btn-submit { font-size: 14px; padding: 14px 18px; min-width: 0; }
  .cmd-btn-cancel { font-size: 13px; padding: 14px 16px; }
}

@media (max-width: 400px) {
  .cmd-recap { flex-direction: column; }
  .cmd-recap__prix { text-align: left; }
  .cmd-btns { flex-direction: column; }
  .cmd-btn-cancel { justify-content: center; }
}
</style>

<div class="cmd-page">
  <div class="cmd-wrap">

    <a href="{{ route('service.detail', $service) }}" class="cmd-back">
      <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
      </svg>
      Retour
    </a>

    {{-- Récap service --}}
    <div class="cmd-recap">
      <div class="cmd-recap__icon">
        <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#F5C842" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
      </div>
      <div class="cmd-recap__body">
        <p class="cmd-recap__label">Service sélectionné</p>
        <p class="cmd-recap__nom">{{ $service->nom }}</p>
        @if($service->delai)
          <p class="cmd-recap__delai">
            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 6v6l4 2"/></svg>
            Livré sous {{ $service->delai }}
          </p>
        @endif
      </div>
      <div class="cmd-recap__prix">
        <p class="cmd-recap__prix-label">À payer</p>
        <p class="cmd-recap__prix-value">
          {{ number_format($service->prix, 0, ',', ' ') }}<span>FCFA</span>
        </p>
      </div>
    </div>

    {{-- Description + Détails du service --}}
    @if($service->description || $service->details)
    <div style="background:#fff;border:1.5px solid #e2e8f0;border-radius:14px;padding:20px 24px;margin-bottom:20px">
      @if($service->description)
      <p style="font-size:14px;color:#374151;line-height:1.7;margin:0 0 {{ $service->details ? '14px' : '0' }}">
        {{ $service->description }}
      </p>
      @endif
      @if($service->details)
      <div style="border-top:{{ $service->description ? '1px solid #f1f5f9' : 'none' }};padding-top:{{ $service->description ? '14px' : '0' }}">
        <p style="font-size:12px;font-weight:700;color:#185FA5;text-transform:uppercase;letter-spacing:.07em;margin:0 0 8px">Détails</p>
        <p style="font-size:13.5px;color:#4b5563;line-height:1.75;margin:0;white-space:pre-line">{{ $service->details }}</p>
      </div>
      @endif
    </div>
    @endif

    {{-- Erreurs --}}
    @if($errors->any())
      <div class="cmd-errors">
        <p class="cmd-errors__title">
          <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          Veuillez corriger les erreurs :
        </p>
        <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
      </div>
    @endif

    {{-- Formulaire --}}
    <div class="cmd-card">
      <div class="cmd-card__head">
        <h1 class="cmd-card__title">Passer votre commande</h1>
        <p class="cmd-card__sub">Remplissez les informations ci-dessous. Notre équipe vous contactera rapidement.</p>
      </div>

      <form method="POST" action="{{ route('service.commande.store', $service) }}" enctype="multipart/form-data">
        @csrf
        <div class="cmd-card__body">

          {{-- Vos informations --}}
          <div class="cmd-field">
            <label style="margin-bottom:12px">Vos informations @auth<span style="font-weight:400;color:#94a3b8;font-size:12px">— déjà sur votre compte</span>@endauth</label>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px">
              <div>
                <label for="prenom" style="font-size:12.5px;margin-bottom:6px">Prénom @unless(auth()->check())<span class="cmd-field__required">*</span>@endunless</label>
                <input id="prenom" type="text" name="prenom"
                       @auth readonly @else required @endauth
                       value="{{ old('prenom', auth()->user()->prenom ?? '') }}"
                       placeholder="Votre prénom"
                       class="cmd-input {{ $errors->has('prenom') ? 'cmd-input--error' : '' }}"
                       style="padding-left:16px;{{ auth()->check() ? 'background:#f8fafc;color:#64748b' : '' }}">
                @error('prenom')<p class="cmd-field__err">{{ $message }}</p>@enderror
              </div>
              <div>
                <label for="nom" style="font-size:12.5px;margin-bottom:6px">Nom @unless(auth()->check())<span class="cmd-field__required">*</span>@endunless</label>
                <input id="nom" type="text" name="nom"
                       @auth readonly @else required @endauth
                       value="{{ old('nom', auth()->user()->nom ?? '') }}"
                       placeholder="Votre nom"
                       class="cmd-input {{ $errors->has('nom') ? 'cmd-input--error' : '' }}"
                       style="padding-left:16px;{{ auth()->check() ? 'background:#f8fafc;color:#64748b' : '' }}">
                @error('nom')<p class="cmd-field__err">{{ $message }}</p>@enderror
              </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
              <div>
                <label for="telephone" style="font-size:12.5px;margin-bottom:6px">Téléphone @unless(auth()->check())<span class="cmd-field__required">*</span>@endunless</label>
                <input id="telephone" type="tel" name="telephone"
                       @auth readonly @else required @endauth
                       value="{{ old('telephone', auth()->user()->tel ?? '') }}"
                       placeholder="+229 90 00 00 00"
                       class="cmd-input {{ $errors->has('telephone') ? 'cmd-input--error' : '' }}"
                       style="padding-left:16px;{{ auth()->check() ? 'background:#f8fafc;color:#64748b' : '' }}">
                @error('telephone')<p class="cmd-field__err">{{ $message }}</p>@enderror
              </div>
              <div>
                <label for="email_contact" style="font-size:12.5px;margin-bottom:6px">Email @unless(auth()->check())<span class="cmd-field__required">*</span>@endunless</label>
                <input id="email_contact" type="email" name="email_contact"
                       @auth readonly @else required @endauth
                       value="{{ old('email_contact', auth()->user()->email ?? '') }}"
                       placeholder="votre@email.com"
                       class="cmd-input {{ $errors->has('email_contact') ? 'cmd-input--error' : '' }}"
                       style="padding-left:16px;{{ auth()->check() ? 'background:#f8fafc;color:#64748b' : '' }}">
                @error('email_contact')<p class="cmd-field__err">{{ $message }}</p>@enderror
              </div>
            </div>
            <p class="cmd-field__hint">
              <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
              Votre commande livrée sera envoyée à cet email, notre équipe pourra vous joindre à ce numéro.
            </p>
          </div>

          {{-- Fichier joint --}}
          <div class="cmd-field">
            <label>
              Ajouter votre ancien CV ou un document
              <span>— optionnel</span>
            </label>
            <label id="fileZone" class="cmd-file-zone">
              <div class="cmd-file-icon">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
              </div>
              <div class="cmd-file-text">
                <p id="fileLabel" class="cmd-file-name">Appuyer pour ajouter votre ancien CV</p>
                <p class="cmd-file-hint">PDF, DOC, DOCX, image · 10 Mo max</p>
              </div>
              <input type="file" name="fichier_joint"
                     accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png"
                     style="display:none" onchange="handleFile(this)">
            </label>
            <div id="fileInfo" class="cmd-file-info">
              <span id="fileName"></span>
              <button type="button" class="cmd-file-clear" onclick="clearFile()" title="Supprimer">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
              </button>
            </div>
            @error('fichier_joint')
              <p class="cmd-field__err">{{ $message }}</p>
            @enderror
          </div>

          {{-- Détails --}}
          <div class="cmd-field">
            <label>
              Informations supplémentaires
              <span>— optionnel</span>
            </label>
            <textarea name="details_demande" class="cmd-textarea"
                      placeholder="Décrivez votre situation, le poste visé, vos attentes particulières…">{{ old('details_demande') }}</textarea>
            @error('details_demande')
              <p class="cmd-field__err">{{ $message }}</p>
            @enderror
          </div>

        </div>

        <div class="cmd-card__foot">
          <div class="cmd-alert">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#92400e" stroke-width="2" style="flex-shrink:0;margin-top:1px">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
            </svg>
            <p>Après paiement, votre commande est traitée et livrée sous <strong>{{ $service->delai ?? '1h' }}</strong> par email.</p>
          </div>
          <div class="cmd-btns">
            <button type="submit" class="cmd-btn-submit">
              <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
              Continuer vers le paiement
            </button>
            <a href="{{ route('service.list') }}" class="cmd-btn-cancel">Annuler</a>
          </div>
        </div>
      </form>
    </div>

    <p class="cmd-footer-note">
      <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
      Paiement 100 % sécurisé · Données cryptées
    </p>

  </div>
</div>

<script>
function handleFile(input) {
  const file = input.files[0];
  if (!file) return;
  document.getElementById('fileLabel').textContent = file.name;
  document.getElementById('fileName').textContent  = file.name;
  document.getElementById('fileInfo').classList.add('cmd-file-info--show');
  document.getElementById('fileZone').classList.add('cmd-file-zone--active');
}
function clearFile() {
  document.querySelector('input[name="fichier_joint"]').value = '';
  document.getElementById('fileLabel').textContent = 'Appuyer pour ajouter votre ancien CV';
  document.getElementById('fileInfo').classList.remove('cmd-file-info--show');
  document.getElementById('fileZone').classList.remove('cmd-file-zone--active');
}
</script>
@endsection
