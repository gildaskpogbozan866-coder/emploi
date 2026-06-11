@extends('layouts.candidat')
@section('title', 'Modifier mon CV')

@section('css')
<link rel="stylesheet" href="{{ asset('css/cv/depot-cv.css') }}">
@endsection

@section('sidebar')
@include('candidat._sidebar')
@endsection

@section('content')

<div class="cand-page-header">
  <div class="cand-page-header__left">
    <h1 class="cand-page-header__title">Modifier mon CV</h1>
    <p class="cand-page-header__sub">{{ $cv->titre_poste }} — {{ $cv->pays }}{{ $cv->ville ? ', '.$cv->ville : '' }}</p>
  </div>
  <div class="cand-page-header__actions">
    <a href="{{ route('candidat.cvs') }}" class="cand-btn cand-btn--outline">
      <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
      Retour
    </a>
  </div>
</div>

@if($errors->any())
<div class="depot-errors" style="max-width:680px;margin-bottom:20px">
  <strong>Veuillez corriger les erreurs suivantes :</strong>
  <ul>@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
</div>
@endif

<div class="depot-card" style="max-width:680px">
  <div class="depot-card__head">
    <span class="depot-card__head-title">Informations du CV</span>
  </div>
  <div class="depot-card__body">
    <form method="POST" action="{{ route('candidat.cvs.update', $cv) }}" enctype="multipart/form-data">
      @csrf @method('PUT')

      {{-- Poste + Pays --}}
      <div class="form-row form-row--2">
        <div>
          <label class="field__label">Titre du poste visé <span class="req">*</span></label>
          <input class="field__input" type="text" name="titre_poste"
            value="{{ old('titre_poste', $cv->titre_poste) }}" required
            placeholder="Ex : Développeur Web, Comptable…">
        </div>
        <div>
          <label class="field__label">Pays <span class="req">*</span></label>
          <select class="field__select" name="pays" required>
            <option value="">-- Sélectionnez --</option>
            @foreach(['Bénin','Côte d\'Ivoire','Sénégal','Cameroun','Togo','Mali','Burkina Faso','Niger','Guinée','Congo','Madagascar','Autre'] as $p)
              <option value="{{ $p }}" {{ old('pays', $cv->pays) === $p ? 'selected' : '' }}>{{ $p }}</option>
            @endforeach
          </select>
        </div>
      </div>

      {{-- Ville --}}
      <div class="form-row form-row--1">
        <div>
          <label class="field__label">Ville</label>
          <input class="field__input" type="text" name="ville"
            value="{{ old('ville', $cv->ville) }}" placeholder="Cotonou, Abidjan, Dakar…">
        </div>
      </div>

      {{-- Compétences --}}
      <div class="form-section-label">Compétences & profil</div>

      <div class="form-row form-row--1">
        <div>
          <label class="field__label">Compétences principales</label>
          <textarea class="field__textarea" name="competences" rows="3"
            placeholder="Ex : PHP, Laravel, MySQL, React, Design graphique…">{{ old('competences', $cv->competences) }}</textarea>
        </div>
      </div>

      <div class="form-row form-row--1">
        <div>
          <label class="field__label">Expérience professionnelle</label>
          <textarea class="field__textarea" name="experience" rows="4"
            placeholder="Décrivez vos expériences (poste, entreprise, durée, missions…)">{{ old('experience', $cv->experience) }}</textarea>
        </div>
      </div>

      <div class="form-row form-row--1">
        <div>
          <label class="field__label">Formation</label>
          <textarea class="field__textarea" name="formation" rows="3"
            placeholder="Diplôme, école, année d'obtention…">{{ old('formation', $cv->formation) }}</textarea>
        </div>
      </div>

      <div class="form-row form-row--1">
        <div>
          <label class="field__label">Langues</label>
          <input class="field__input" type="text" name="langues"
            value="{{ old('langues', $cv->langues) }}"
            placeholder="Ex : Français (courant), Anglais (intermédiaire)…">
        </div>
      </div>

      {{-- Fichier --}}
      <div class="form-section-label">Fichier CV</div>

      @if($cv->fichier_path)
      <div style="display:flex;align-items:center;gap:10px;padding:10px 14px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:8px;margin-bottom:14px">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#38A169" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        <span style="font-size:13px;color:#374151;flex:1">Fichier actuel :
          <a href="{{ asset('storage/'.$cv->fichier_path) }}" target="_blank" style="color:#185FA5;font-weight:600">voir le fichier</a>
        </span>
        <span style="font-size:12px;color:#94a3b8">Remplacez-le ci-dessous si nécessaire</span>
      </div>
      @endif

      <div class="form-row form-row--1">
        <div>
          <div class="upload-zone" id="uploadZone">
            <input type="file" id="cvFile" name="fichier_path" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.webp">
            <div class="upload-zone__icon">
              <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
              </svg>
            </div>
            <div class="upload-zone__title">Glissez votre fichier ici ou <span>cliquez pour charger</span></div>
            <div class="upload-zone__hint">PDF, DOC, DOCX, JPG, PNG ou WebP — max 5 Mo{{ $cv->fichier_path ? ' (optionnel)' : '' }}</div>
          </div>
          <div class="file-preview" id="filePreview">
            <div class="file-preview__icon">
              <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
              </svg>
            </div>
            <div>
              <div class="file-preview__name" id="previewName">—</div>
              <div class="file-preview__meta" id="previewMeta">—</div>
            </div>
            <button type="button" class="file-preview__remove" id="removeFile">✕</button>
          </div>
        </div>
      </div>

      <div style="display:flex;gap:10px;margin-top:24px">
        <button type="submit" class="cand-btn cand-btn--yellow" style="flex:1;justify-content:center">
          <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
          </svg>
          Enregistrer les modifications
        </button>
        <a href="{{ route('candidat.cvs') }}" class="cand-btn cand-btn--outline">Annuler</a>
      </div>

    </form>
  </div>
</div>

@endsection

@section('scripts')
<script>
(function () {
  const input   = document.getElementById('cvFile');
  const zone    = document.getElementById('uploadZone');
  const preview = document.getElementById('filePreview');
  const name    = document.getElementById('previewName');
  const meta    = document.getElementById('previewMeta');
  const remove  = document.getElementById('removeFile');

  function showFile(file) {
    if (!file) return;
    zone.classList.add('has-file');
    preview.classList.add('visible');
    name.textContent = file.name;
    meta.textContent = (file.size / 1024 / 1024).toFixed(2) + ' Mo · ' + file.name.split('.').pop().toUpperCase();
  }

  input.addEventListener('change', () => showFile(input.files[0]));
  zone.addEventListener('dragover',  e => { e.preventDefault(); zone.classList.add('has-file'); });
  zone.addEventListener('dragleave', () => { if (!input.files[0]) zone.classList.remove('has-file'); });
  zone.addEventListener('drop', e => {
    e.preventDefault();
    if (e.dataTransfer.files[0]) { input.files = e.dataTransfer.files; showFile(e.dataTransfer.files[0]); }
  });
  remove.addEventListener('click', () => {
    input.value = '';
    zone.classList.remove('has-file');
    preview.classList.remove('visible');
    name.textContent = meta.textContent = '—';
  });
})();
</script>
@endsection
