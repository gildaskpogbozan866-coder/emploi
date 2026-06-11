@extends('layouts.candidat')
@section('title', 'Modifier le document')

@section('css')
<link rel="stylesheet" href="{{ asset('css/cv/depot-cv.css') }}">
@endsection

@section('sidebar')
@include('candidat._sidebar')
@endsection

@section('content')

<div class="cand-page-header">
  <div class="cand-page-header__left">
    <h1 class="cand-page-header__title">Modifier le document</h1>
    <p class="cand-page-header__sub">{{ $document->nom }} — {{ $document->type->nom }}</p>
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
    <span class="depot-card__head-title">Informations du document</span>
  </div>
  <div class="depot-card__body">
    <form method="POST" action="{{ route('candidat.documents.update', $document) }}" enctype="multipart/form-data">
      @csrf @method('PUT')

      {{-- Type --}}
      <div class="form-row form-row--1">
        <div>
          <label class="field__label">Type de document <span class="req">*</span></label>
          <select class="field__select" name="type_document_id" required>
            <option value="">-- Choisissez --</option>
            @foreach($typesDocuments as $type)
              <option value="{{ $type->id }}"
                {{ old('type_document_id', $document->type_document_id) == $type->id ? 'selected' : '' }}>
                {{ $type->nom }}
              </option>
            @endforeach
          </select>
        </div>
      </div>

      {{-- Intitulé --}}
      <div class="form-row form-row--1">
        <div>
          <label class="field__label">Intitulé <span class="req">*</span></label>
          <input class="field__input" type="text" name="nom" required
            value="{{ old('nom', $document->nom) }}"
            placeholder="Ex : Développeur Web Full Stack, Licence en Droit — UAC 2023…">
          <p style="font-size:12px;color:#94a3b8;margin:4px 0 0">Pour un CV : indiquez le poste visé. Pour un diplôme ou certificat : indiquez le nom du document.</p>
        </div>
      </div>

      {{-- Détails complémentaires --}}
      <div class="form-section-label" style="margin-top:20px">
        Détails complémentaires
        <span style="font-size:11px;font-weight:400;color:#94a3b8;margin-left:6px">— utile pour les CV, ignorez si autre document</span>
      </div>

      <div class="form-row form-row--2">
        <div>
          <label class="field__label">Pays</label>
          <select class="field__select" name="pays">
            <option value="">-- Sélectionnez --</option>
            @foreach(['Bénin','Côte d\'Ivoire','Sénégal','Cameroun','Togo','Mali','Burkina Faso','Niger','Guinée','Congo','Madagascar','Autre'] as $p)
              <option value="{{ $p }}" {{ old('pays', $document->pays) === $p ? 'selected' : '' }}>{{ $p }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="field__label">Ville</label>
          <input class="field__input" type="text" name="ville"
            value="{{ old('ville', $document->ville) }}" placeholder="Cotonou, Abidjan, Dakar…">
        </div>
      </div>

      <div class="form-row form-row--1">
        <div>
          <label class="field__label">Compétences principales</label>
          <textarea class="field__textarea" name="competences" rows="3"
            placeholder="Ex : PHP, Laravel, MySQL, React…">{{ old('competences', $document->competences) }}</textarea>
        </div>
      </div>

      <div class="form-row form-row--1">
        <div>
          <label class="field__label">Expérience professionnelle</label>
          <textarea class="field__textarea" name="experience" rows="4"
            placeholder="Décrivez vos expériences (poste, entreprise, durée, missions…)">{{ old('experience', $document->experience) }}</textarea>
        </div>
      </div>

      <div class="form-row form-row--1">
        <div>
          <label class="field__label">Formation</label>
          <textarea class="field__textarea" name="formation" rows="3"
            placeholder="Diplôme, école, année d'obtention…">{{ old('formation', $document->formation) }}</textarea>
        </div>
      </div>

      <div class="form-row form-row--1">
        <div>
          <label class="field__label">Langues</label>
          <input class="field__input" type="text" name="langues"
            value="{{ old('langues', $document->langues) }}"
            placeholder="Ex : Français (courant), Anglais (intermédiaire)…">
        </div>
      </div>

      {{-- Fichier --}}
      <div class="form-section-label" style="margin-top:20px">Fichier</div>

      <div style="display:flex;align-items:center;gap:10px;padding:10px 14px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:8px;margin-bottom:14px">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#38A169" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        <span style="font-size:13px;color:#374151;flex:1">Fichier actuel :
          <a href="{{ asset('storage/'.$document->fichier) }}" target="_blank" style="color:#185FA5;font-weight:600">voir le fichier</a>
        </span>
        <span style="font-size:12px;color:#94a3b8">Remplacez-le ci-dessous si nécessaire</span>
      </div>

      <div class="form-row form-row--1">
        <div>
          <div class="upload-zone" id="uploadZone">
            <input type="file" id="docFile" name="fichier" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.webp">
            <div class="upload-zone__icon">
              <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
              </svg>
            </div>
            <div class="upload-zone__title">Glissez votre fichier ici ou <span>cliquez pour charger</span></div>
            <div class="upload-zone__hint">PDF, DOC, DOCX, JPG, PNG ou WebP — max 5 Mo (optionnel)</div>
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
  const input   = document.getElementById('docFile');
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
