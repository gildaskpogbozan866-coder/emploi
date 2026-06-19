@extends('layouts.candidat')
@section('title', 'Modifier mon CV')

@section('css')
<link rel="stylesheet" href="{{ asset('css/cv/depot-cv.css') }}">
<link rel="stylesheet" href="{{ asset('css/searchable-select.css') }}">
<style>
#langues-builder { display: flex; flex-direction: column; gap: 8px; margin-bottom: 8px; }
.langue-row { display: flex; gap: 8px; align-items: center; }
.langue-row__select { flex: 1; margin: 0; }
.langue-row__remove {
  flex-shrink: 0; width: 30px; height: 36px;
  background: #fee2e2; border: none; border-radius: 6px;
  color: #dc2626; font-size: 16px; cursor: pointer; transition: background .15s;
}
.langue-row__remove:hover { background: #fca5a5; }
.langue-add-btn {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 7px 14px; border: 1.5px dashed #93c5fd; border-radius: 8px;
  background: #eff6ff; color: #185FA5; font-size: 13px; font-weight: 600;
  cursor: pointer; transition: background .15s;
}
.langue-add-btn:hover { background: #dbeafe; }
</style>
@endsection

@section('sidebar')
@include('candidat._sidebar')
@endsection

@section('content')

<div class="cand-page-header">
  <div class="cand-page-header__left">
    <h1 class="cand-page-header__title">Modifier mon CV</h1>
    <p class="cand-page-header__sub">{{ $cv->titre_poste }}, {{ $cv->pays }}{{ $cv->ville ? ', '.$cv->ville : '' }}</p>
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

      {{-- Photo de profil --}}
      <div style="display:flex;align-items:center;gap:20px;margin-bottom:24px;padding:18px 20px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:12px">
        <div style="position:relative;flex-shrink:0">
          <div style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,#042C53,#185FA5);display:flex;align-items:center;justify-content:center;overflow:hidden;border:2.5px solid #e2e8f0">
            @if($cv->photo)
              <img id="photoPreviewImg" src="{{ asset('storage/'.$cv->photo) }}" alt="" style="width:100%;height:100%;object-fit:cover">
              <span id="photoInitials" style="display:none;color:#fff;font-size:1.4rem;font-weight:800">{{ mb_strtoupper(mb_substr(auth()->user()->prenom, 0, 1)) }}</span>
            @else
              <span id="photoInitials" style="color:#fff;font-size:1.4rem;font-weight:800">{{ mb_strtoupper(mb_substr(auth()->user()->prenom, 0, 1)) }}</span>
              <img id="photoPreviewImg" src="" alt="" style="display:none;width:100%;height:100%;object-fit:cover">
            @endif
          </div>
          <label for="photoInput" style="position:absolute;bottom:0;right:0;width:22px;height:22px;border-radius:50%;background:#185FA5;border:2px solid #fff;display:flex;align-items:center;justify-content:center;cursor:pointer">
            <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="#fff" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><circle cx="12" cy="13" r="3"/></svg>
          </label>
        </div>
        <div>
          <p style="font-size:13.5px;font-weight:700;color:#042C53;margin:0 0 3px">Photo de profil</p>
          <p style="font-size:12.5px;color:#64748b;margin:0 0 8px">JPG, PNG ou WebP · max 2 Mo{{ $cv->photo ? ' · une photo existe déjà' : '' }}</p>
          <label for="photoInput" style="font-size:12.5px;color:#185FA5;font-weight:600;cursor:pointer;text-decoration:underline">{{ $cv->photo ? 'Changer la photo' : 'Choisir une photo' }}</label>
          <span id="photoFileName" style="font-size:12px;color:#94a3b8;margin-left:8px"></span>
        </div>
        <input type="file" id="photoInput" name="photo" accept=".jpg,.jpeg,.png,.webp" style="display:none">
      </div>

      {{-- Poste + Pays --}}
      {{-- <div class="form-row form-row--2">
        <div>
          <label class="field__label" for="edit-titre-poste">Titre du poste visé <span class="req">*</span></label>
          <input class="field__input @error('titre_poste') field--invalid @enderror" type="text" id="edit-titre-poste" name="titre_poste"
            value="{{ old('titre_poste', $cv->titre_poste) }}" required
            placeholder="Ex : Développeur Web, Comptable…">
          @error('titre_poste')<p class="field__server-error">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="field__label" for="edit-pays">Pays <span class="req">*</span></label>
          <select class="field__select @error('pays') field--invalid @enderror" id="edit-pays" name="pays" required data-searchable>
            <option value="">-- Sélectionnez --</option>
            @foreach($paysList as $p)
              <option value="{{ $p }}" {{ old('pays', $cv->pays) === $p ? 'selected' : '' }}>{{ $p }}</option>
            @endforeach
          </select>
          @error('pays')<p class="field__server-error">{{ $message }}</p>@enderror
        </div>
      </div> --}}

      {{-- Ville --}}
      {{-- <div class="form-row form-row--1">
        <div>
          <label class="field__label">Ville</label>
          @include('_partials.villes-select', ['selected' => old('ville', $cv->ville)])
        </div>
      </div> --}}

      {{-- Profil CVthèque --}}
      {{-- <div class="form-section-label">Profil CVthèque</div>

      <div class="form-row form-row--2">
        <div>
          <label class="field__label">Disponibilité</label>
          <select class="field__select" name="disponibilite">
            <option value="">-- Sélectionnez --</option>
            @foreach($disponibilitesList as $d)
              <option value="{{ $d->code }}" {{ old('disponibilite', $cv->disponibilite) === $d->code ? 'selected' : '' }}>{{ $d->libelle }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="field__label">Secteur d'activité</label>
          <select class="field__select" name="secteur">
            <option value="">-- Sélectionnez --</option>
            @foreach($secteursList as $s)
              <option value="{{ $s->libelle }}" {{ old('secteur', $cv->secteur) === $s->libelle ? 'selected' : '' }}>{{ $s->libelle }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div class="form-row form-row--2">
        <div>
          <label class="field__label">Métier / Poste recherché</label>
          <select class="field__select" name="metier">
            <option value="">-- Sélectionnez --</option>
            @foreach($metiersList as $m)
              <option value="{{ $m->nom }}" {{ old('metier', $cv->metier) === $m->nom ? 'selected' : '' }}>{{ $m->nom }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="field__label">Niveau d'expérience</label>
          <select class="field__select" name="niveau_experience">
            <option value="">-- Sélectionnez --</option>
            @foreach($niveauxExpList as $ne)
              <option value="{{ $ne->code }}" {{ old('niveau_experience', $cv->niveau_experience) === $ne->code ? 'selected' : '' }}>{{ $ne->libelle }}</option>
            @endforeach
          </select>
        </div>
      </div> --}}

      {{-- <div class="form-row form-row--2">
        <div>
          <label class="field__label">Niveau d'études</label>
          <select class="field__select" name="niveau_etude">
            <option value="">-- Sélectionnez --</option>
            @foreach($niveauxEtudeList as $ne)
              <option value="{{ $ne->code }}" {{ old('niveau_etude', $cv->niveau_etude) === $ne->code ? 'selected' : '' }}>{{ $ne->libelle }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="field__label">Type de contrat recherché</label>
          <select class="field__select" name="type_contrat">
            <option value="">-- Sélectionnez --</option>
            @foreach($typeContratsList as $tc)
              <option value="{{ $tc->code }}" {{ old('type_contrat', $cv->type_contrat) === $tc->code ? 'selected' : '' }}>{{ $tc->libelle }}</option>
            @endforeach
          </select>
        </div>
      </div> --}}

      {{-- Compétences --}}
      {{-- <div class="form-section-label">Compétences & profil</div>

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
          <div id="langues-builder">
            @php
              $listeLangues = old('langues_ids')
                ? array_map(null, old('langues_ids', []), old('niveaux_ids', []))
                : $languesCandidatActuelles->map(fn($lc) => ['lid' => $lc->langue_id, 'nid' => $lc->niveau_id])->all();
              $isOld = (bool) old('langues_ids');
            @endphp
            @foreach($listeLangues as $li => $pair)
              @php $lid = $isOld ? ($pair[0] ?? null) : ($pair['lid'] ?? null); @endphp
              @php $nid = $isOld ? ($pair[1] ?? null) : ($pair['nid'] ?? null); @endphp
              <div class="langue-row">
                <select name="langues_ids[]" class="field__select langue-row__select" required>
                  <option value="">-- Langue --</option>
                  @foreach($languesList as $l)
                    <option value="{{ $l->id }}" {{ $lid == $l->id ? 'selected' : '' }}>{{ $l->nom }}</option>
                  @endforeach
                </select>
                <select name="niveaux_ids[]" class="field__select langue-row__select" required>
                  <option value="">-- Niveau --</option>
                  @foreach($niveauxLangueList as $nl)
                    <option value="{{ $nl->id }}" {{ $nid == $nl->id ? 'selected' : '' }}>{{ $nl->libelle }} ({{ $nl->code }})</option>
                  @endforeach
                </select>
                <button type="button" class="langue-row__remove" onclick="this.closest('.langue-row').remove()">×</button>
              </div>
            @endforeach
          </div>
          <button type="button" id="add-langue-row" class="langue-add-btn">
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Ajouter une langue
          </button>
          <template id="langue-row-tpl">
            <div class="langue-row">
              <select name="langues_ids[]" class="field__select langue-row__select" required>
                <option value="">-- Langue --</option>
                @foreach($languesList as $l)
                  <option value="{{ $l->id }}">{{ $l->nom }}</option>
                @endforeach
              </select>
              <select name="niveaux_ids[]" class="field__select langue-row__select" required>
                <option value="">-- Niveau --</option>
                @foreach($niveauxLangueList as $nl)
                  <option value="{{ $nl->id }}">{{ $nl->libelle }} ({{ $nl->code }})</option>
                @endforeach
              </select>
              <button type="button" class="langue-row__remove" onclick="this.closest('.langue-row').remove()">×</button>
            </div>
          </template>
        </div>
      </div> --}}

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
            <div class="upload-zone__hint">PDF, DOC, DOCX, JPG, PNG ou WebP, max 5 Mo{{ $cv->fichier_path ? ' (optionnel)' : '' }}</div>
          </div>
          <div class="file-preview" id="filePreview">
            <div class="file-preview__icon">
              <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
              </svg>
            </div>
            <div>
              <div class="file-preview__name" id="previewName">-</div>
              <div class="file-preview__meta" id="previewMeta">-</div>
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
<script src="{{ asset('js/searchable-select.js') }}"></script>
<script>
/* ── Photo preview ───────────────────────────── */
(function () {
  const input    = document.getElementById('photoInput');
  const img      = document.getElementById('photoPreviewImg');
  const initials = document.getElementById('photoInitials');
  const label    = document.getElementById('photoFileName');
  if (!input) return;
  input.addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    label.textContent = file.name;
    const reader = new FileReader();
    reader.onload = e => {
      img.src = e.target.result;
      img.style.display = 'block';
      if (initials) initials.style.display = 'none';
    };
    reader.readAsDataURL(file);
  });
})();

/* ── Fichier CV preview ──────────────────────── */
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
    name.textContent = meta.textContent = '-';
  });
})();

/* ── Langue builder ──────────────────────────── */
document.getElementById('add-langue-row').addEventListener('click', function () {
  const tpl   = document.getElementById('langue-row-tpl');
  const clone = tpl.content.cloneNode(true);
  document.getElementById('langues-builder').appendChild(clone);
});
</script>

@endsection
