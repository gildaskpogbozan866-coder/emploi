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
        <h1 class="cmd-card__title">Vos informations</h1>
        <p class="cmd-card__sub">Remplissez les champs ci-dessous. <strong>Même sans expérience ni CV existant</strong>, notre équipe crée votre CV de A à Z.</p>
      </div>

      <form method="POST" action="{{ route('service.commande.store', $service) }}" enctype="multipart/form-data">
        @csrf
        <div class="cmd-card__body">

          {{-- Bannière rassurante --}}
          <div style="display:flex;align-items:flex-start;gap:12px;background:#eff6ff;border:1.5px solid #bfdbfe;border-radius:12px;padding:14px 18px">
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#1d4ed8" stroke-width="2" style="flex-shrink:0;margin-top:1px"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 8v4m0 4h.01"/></svg>
            <p style="margin:0;font-size:13.5px;color:#1e40af;line-height:1.6">
              <strong>Jamais fait de CV ?</strong> Pas de panique — remplissez simplement les champs ci-dessous et notre équipe s'occupe de tout le reste.
            </p>
          </div>

          {{-- Email invité --}}
          @guest
          <div class="cmd-field">
            <label for="email_contact">
              Adresse email de livraison <span class="cmd-field__required">*</span>
            </label>
            <div class="cmd-input-wrap">
              <span class="cmd-input-icon">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
              </span>
              <input id="email_contact" type="email" name="email_contact" required
                     placeholder="votre@email.com"
                     value="{{ old('email_contact') }}"
                     class="cmd-input {{ $errors->has('email_contact') ? 'cmd-input--error' : '' }}">
            </div>
            @error('email_contact')
              <p class="cmd-field__err">
                <svg width="12" height="12" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                {{ $message }}
              </p>
            @enderror
            <p class="cmd-field__hint">
              <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
              Votre CV terminé sera envoyé à cette adresse.
            </p>
          </div>
          @endguest

          {{-- Section : Informations personnelles --}}
          <div style="border-top:1.5px solid #f1f5f9;padding-top:20px">
            <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin:0 0 16px">Vos informations personnelles</p>

            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px;margin-bottom:16px">
              <div class="cmd-field" style="margin:0">
                <label for="prenom_client">Prénom <span class="cmd-field__required">*</span></label>
                <input id="prenom_client" type="text" name="prenom_client" required
                       placeholder="Votre prénom"
                       value="{{ old('prenom_client', auth()->user()?->prenom) }}"
                       class="cmd-input {{ $errors->has('prenom_client') ? 'cmd-input--error' : '' }}"
                       style="padding-left:16px">
                @error('prenom_client')<p class="cmd-field__err">{{ $message }}</p>@enderror
              </div>
              <div class="cmd-field" style="margin:0">
                <label for="nom_client">Nom <span class="cmd-field__required">*</span></label>
                <input id="nom_client" type="text" name="nom_client" required
                       placeholder="Votre nom de famille"
                       value="{{ old('nom_client', auth()->user()?->nom) }}"
                       class="cmd-input {{ $errors->has('nom_client') ? 'cmd-input--error' : '' }}"
                       style="padding-left:16px">
                @error('nom_client')<p class="cmd-field__err">{{ $message }}</p>@enderror
              </div>
            </div>

            <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:16px">
              <div class="cmd-field" style="margin:0">
                <label for="tel_client">Téléphone</label>
                <div class="cmd-input-wrap">
                  <span class="cmd-input-icon">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                  </span>
                  <input id="tel_client" type="tel" name="tel_client"
                         placeholder="+229 97 00 00 00"
                         value="{{ old('tel_client', auth()->user()?->tel) }}"
                         class="cmd-input">
                </div>
              </div>
              <div class="cmd-field" style="margin:0">
                <label for="ville_client">Ville</label>
                <div class="cmd-input-wrap">
                  <span class="cmd-input-icon">
                    <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                  </span>
                  <input id="ville_client" type="text" name="ville_client"
                         placeholder="Ex : Cotonou, Porto-Novo…"
                         value="{{ old('ville_client') }}"
                         class="cmd-input">
                </div>
              </div>
            </div>
          </div>

          {{-- Section : Parcours --}}
          <div style="border-top:1.5px solid #f1f5f9;padding-top:20px">
            <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin:0 0 16px">Votre parcours</p>

            {{-- Poste visé --}}
            <div class="cmd-field">
              <label for="poste_vise">
                Poste / Métier visé <span class="cmd-field__required">*</span>
              </label>
              <div class="cmd-input-wrap">
                <span class="cmd-input-icon">
                  <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path stroke-linecap="round" d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>
                </span>
                <input id="poste_vise" type="text" name="poste_vise" required
                       placeholder="Ex : Comptable, Développeur web, Infirmier, Secrétaire…"
                       value="{{ old('poste_vise') }}"
                       class="cmd-input {{ $errors->has('poste_vise') ? 'cmd-input--error' : '' }}">
              </div>
              @error('poste_vise')<p class="cmd-field__err">{{ $message }}</p>@enderror
            </div>

            {{-- Niveau d'études --}}
            <div class="cmd-field">
              <label for="niveau_etudes">Niveau d'études</label>
              <select id="niveau_etudes" name="niveau_etudes"
                      class="cmd-input" style="padding-left:16px;cursor:pointer;appearance:auto">
                <option value="">-- Sélectionner votre niveau --</option>
                @foreach(['Sans diplôme','BEPC / Brevet','BAC','BTS / DUT','Licence (Bac+3)','Master (Bac+5)','Doctorat'] as $niv)
                  <option value="{{ $niv }}" {{ old('niveau_etudes') === $niv ? 'selected' : '' }}>{{ $niv }}</option>
                @endforeach
              </select>
            </div>

            {{-- Expériences --}}
            <div class="cmd-field">
              <label for="experiences">
                Vos expériences
                <span>— même courtes ou informelles</span>
              </label>
              <textarea id="experiences" name="experiences" class="cmd-textarea" rows="5"
                        placeholder="Décrivez vos expériences, même courtes ou informelles. Exemples :

• 2023-2024 · Stagiaire comptable à la mairie de Cotonou
• 2022 · Vendeur de téléphones au marché Dantokpa
• J'ai aidé dans la boutique de ma famille pendant 2 ans

Vous n'avez aucune expérience ? Écrivez simplement : Aucune expérience pour l'instant.">{{ old('experiences') }}</textarea>
              <p class="cmd-field__hint">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                Plus vous donnez de détails, meilleur sera votre CV.
              </p>
            </div>

            {{-- Compétences --}}
            <div class="cmd-field">
              <label for="competences">Vos compétences</label>
              <textarea id="competences" name="competences" class="cmd-textarea" rows="3"
                        placeholder="Ex : Maîtrise d'Excel, notions d'anglais, permis de conduire moto/voiture, travail en équipe, débrouillard, vente…">{{ old('competences') }}</textarea>
            </div>

            {{-- Infos supplémentaires --}}
            <div class="cmd-field" style="margin-bottom:0">
              <label for="details_supplementaires">
                Informations supplémentaires
                <span>— optionnel</span>
              </label>
              <textarea id="details_supplementaires" name="details_supplementaires" class="cmd-textarea" rows="2"
                        placeholder="Le poste précis pour lequel vous postulez, des souhaits particuliers pour votre CV…">{{ old('details_supplementaires') }}</textarea>
            </div>
          </div>

          {{-- Section : Fichier existant --}}
          <div style="border-top:1.5px solid #f1f5f9;padding-top:20px">
            <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#94a3b8;margin:0 0 6px">Vous avez déjà un CV ou un brouillon ?</p>
            <p style="font-size:13px;color:#64748b;margin:0 0 14px">Optionnel — nos experts peuvent aussi créer votre CV entièrement depuis zéro sans fichier.</p>
            <div class="cmd-field" style="margin:0">
              <label id="fileZone" class="cmd-file-zone">
                <div class="cmd-file-icon">
                  <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                  </svg>
                </div>
                <div class="cmd-file-text">
                  <p id="fileLabel" class="cmd-file-name">Ajouter un fichier existant (facultatif)</p>
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
