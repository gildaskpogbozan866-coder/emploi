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

/* ── CV Builders (exp + formation) ─────────────── */
.form-section-sub {
  font-size: 12px; font-weight: 700; color: #185FA5;
  text-transform: uppercase; letter-spacing: .06em;
  margin: 20px 0 10px; display: flex; align-items: center; gap: 8px;
}
.form-section-sub::after { content: ''; flex: 1; height: 1px; background: #e2e8f0; }
.cv-builder { display: flex; flex-direction: column; gap: 12px; margin-bottom: 10px; }
.cv-block { border: 1.5px solid #e2e8f0; border-radius: 12px; background: #fafbfc; overflow: hidden; }
.cv-block__head {
  display: flex; align-items: center; justify-content: space-between;
  padding: 10px 14px; background: #f1f5f9; border-bottom: 1px solid #e2e8f0;
}
.cv-block__num { font-size: 12px; font-weight: 700; color: #185FA5; }
.cv-block__del {
  width: 26px; height: 26px; border-radius: 6px; border: none;
  background: #fee2e2; color: #dc2626; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: background .15s;
}
.cv-block__del:hover { background: #fca5a5; }
.cv-block__body { padding: 14px; display: flex; flex-direction: column; gap: 12px; }
.builder-add-btn {
  display: inline-flex; align-items: center; gap: 7px;
  padding: 9px 16px; border: 1.5px dashed #93c5fd; border-radius: 9px;
  background: #eff6ff; color: #185FA5; font-size: 13px; font-weight: 600;
  cursor: pointer; transition: background .15s; margin-bottom: 4px;
}
.builder-add-btn:hover { background: #dbeafe; }

/* ── Inline tag builder (missions / activités) ── */
.inline-tag-builder { display: flex; gap: 8px; align-items: flex-start; margin-top: 6px; }
.inline-tag-box {
  flex: 1; min-height: 42px; padding: 6px 10px;
  border: 1px solid #e2e8f0; border-radius: 8px; background: #fff;
  display: flex; flex-wrap: wrap; gap: 5px; align-items: center; cursor: text;
  transition: border-color .15s;
}
.inline-tag-box:focus-within { border-color: #185FA5; box-shadow: 0 0 0 3px rgba(24,95,165,.1); }
.inline-tag-chip {
  display: inline-flex; align-items: center; gap: 4px;
  padding: 3px 8px; background: #eff6ff; border: 1px solid #bfdbfe;
  border-radius: 20px; font-size: 12px; font-weight: 600; color: #1e40af;
}
.inline-tag-chip button { background: none; border: none; cursor: pointer; color: #60a5fa; font-size: 13px; line-height: 1; padding: 0; }
.inline-tag-chip button:hover { color: #dc2626; }
.inline-tag-text { border: none; outline: none; font-size: 13px; color: #1e293b; min-width: 140px; flex: 1; background: transparent; font-family: inherit; }
.inline-tag-addbtn {
  flex-shrink: 0; padding: 8px 13px;
  background: #042C53; color: #F5C842; font-size: 12.5px; font-weight: 700;
  border: none; border-radius: 8px; cursor: pointer; white-space: nowrap;
  transition: background .15s;
}
.inline-tag-addbtn:hover { background: #185FA5; color: #fff; }
.field__hint { font-size: 11.5px; color: #94a3b8; margin: 4px 0 0; }
</style>
@endsection

@section('sidebar')
@include('candidat._sidebar')
@endsection

@section('content')

<div class="cand-page-header">
  <div class="cand-page-header__left">
    <h1 class="cand-page-header__title">Modifier mon CV</h1>
    <p class="cand-page-header__sub">{{ $cv->metier }}{{ $cv->ville ? ' — '.$cv->ville : '' }}</p>
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

@if($cv->experience || $cv->formation)
<div style="max-width:680px;margin-bottom:16px;background:#fffbeb;border:1.5px solid #fcd34d;border-radius:10px;padding:12px 16px;font-size:13px;color:#92400e">
  Vos expériences/formations existantes ont été reconstituées automatiquement en blocs ci-dessous — vérifiez et corrigez si un champ (lieu/secteur/domaine) est mal placé.
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
          <p style="font-size:13.5px;font-weight:700;color:#042C53;margin:0 0 3px">Photo de profil <span style="font-weight:400;color:#64748b;font-size:11px">optionnel</span></p>
          <p style="font-size:12.5px;color:#64748b;margin:0 0 8px">JPG, PNG ou WebP · max 2 Mo{{ $cv->photo ? ' · une photo existe déjà' : '' }}</p>
          <label for="photoInput" style="font-size:12.5px;color:#185FA5;font-weight:600;cursor:pointer;text-decoration:underline">{{ $cv->photo ? 'Changer la photo' : 'Choisir une photo' }}</label>
          <span id="photoFileName" style="font-size:12px;color:#94a3b8;margin-left:8px"></span>
          @error('photo')<p class="field__server-error">{{ $message }}</p>@enderror
        </div>
        <input type="file" id="photoInput" name="photo" accept=".jpg,.jpeg,.png,.webp" style="display:none">
      </div>

      {{-- Poste + Ville --}}
      <div class="form-row form-row--2">
        <div>
          <label class="field__label" for="edit-metier">Intitulé / Poste visé <span class="req">*</span></label>
          <input class="field__input @error('metier') field--invalid @enderror" type="text" id="edit-metier" name="metier"
            value="{{ old('metier', $cv->metier) }}" required
            placeholder="Ex : Comptable, Infirmier, Juriste…">
          @error('metier')<p class="field__server-error">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="field__label" for="edit-ville">Ville <span class="req">*</span></label>
          <input class="field__input @error('ville') field--invalid @enderror" type="text" id="edit-ville" name="ville"
            value="{{ old('ville', $cv->ville) }}" required
            placeholder="Ex : Cotonou, Abomey-Calavi, Porto-Novo…">
          @error('ville')<p class="field__server-error">{{ $message }}</p>@enderror
        </div>
      </div>

      {{-- Résumé --}}
      <div class="form-row form-row--1">
        <div>
          <label class="field__label">Résumé / Présentation <span class="req">*</span></label>
          <textarea class="field__textarea @error('resume') field--invalid @enderror" name="resume" rows="4" required
            placeholder="Présentez votre parcours, vos compétences clés et vos objectifs professionnels…">{{ old('resume', $cv->resume) }}</textarea>
          @error('resume')<p class="field__server-error">{{ $message }}</p>@enderror
        </div>
      </div>

      {{-- Préférences emploi --}}
      <div class="form-section-label">Préférences emploi</div>

      <div class="form-row form-row--1">
        <div>
          <label class="field__label">Types de contrat souhaités <span class="req">*</span></label>
          <div style="display:flex;flex-wrap:wrap;gap:8px;margin-top:8px">
            @foreach($typesContrats as $tc)
              <label style="display:flex;align-items:center;gap:6px;font-size:13px;color:#042C53;cursor:pointer;padding:6px 12px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:8px">
                <input type="checkbox" name="types_contrat_ids[]" value="{{ $tc->id }}"
                  {{ in_array($tc->id, old('types_contrat_ids', $typesContratSelectionnes)) ? 'checked' : '' }}
                  style="width:15px;height:15px;accent-color:#185FA5;flex-shrink:0">
                {{ $tc->libelle }}
              </label>
            @endforeach
          </div>
          @error('types_contrat_ids')<p class="field__server-error">{{ $message }}</p>@enderror
        </div>
      </div>

      <div class="form-row form-row--2">
        <div>
          <label class="field__label" for="edit-niveau-experience">Niveau d'expérience <span class="req">*</span></label>
          <select class="field__select @error('niveau_experience_id') field--invalid @enderror" id="edit-niveau-experience" name="niveau_experience_id" required>
            <option value="">-- Sélectionnez --</option>
            @foreach($niveauxExperience as $ne)
              <option value="{{ $ne->id }}" {{ old('niveau_experience_id', $niveauExperienceSelectionne) == $ne->id ? 'selected' : '' }}>{{ $ne->libelle }}</option>
            @endforeach
          </select>
          @error('niveau_experience_id')<p class="field__server-error">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="field__label" for="edit-niveau-etude">Niveau d'études <span class="req">*</span></label>
          <select class="field__select @error('niveau_etude_id') field--invalid @enderror" id="edit-niveau-etude" name="niveau_etude_id" required>
            <option value="">-- Sélectionnez --</option>
            @foreach($niveauxEtude as $ne)
              <option value="{{ $ne->id }}" {{ old('niveau_etude_id', $niveauEtudeSelectionne) == $ne->id ? 'selected' : '' }}>{{ $ne->libelle }}</option>
            @endforeach
          </select>
          @error('niveau_etude_id')<p class="field__server-error">{{ $message }}</p>@enderror
        </div>
      </div>

      {{-- Contenu du CV --}}
      <div class="form-section-label">Contenu du CV</div>

      <div class="form-row form-row--1">
        <div>
          <label class="field__label">Compétences principales <span class="req">*</span></label>
          <div class="tag-input-wrap" id="comp-wrap">
            <div class="tag-input-box" id="comp-box">
              <div class="tag-input-tags" id="comp-tags"></div>
              <input type="text" class="tag-input-text" id="comp-text"
                     placeholder="Tapez puis Entrée, ou choisissez…" autocomplete="off">
            </div>
            <ul class="tag-suggestions" id="comp-suggestions"></ul>
            <input type="hidden" name="competences" id="comp-hidden" value="{{ old('competences', $cv->competences) }}">
          </div>
          <p style="font-size:12px;color:#94a3b8;margin:4px 0 0">Appuyez sur <kbd>Entrée</kbd> ou <kbd>,</kbd> pour valider. Cliquez × pour retirer.</p>
          @error('competences')<p class="field__server-error">{{ $message }}</p>@enderror
        </div>
      </div>

      {{-- ── EXPÉRIENCES ─────────────────────────────────── --}}
      <div class="form-section-sub">
        <span>Expériences professionnelles <span class="req">*</span></span>
      </div>
      <input type="hidden" name="experience" id="exp-hidden" value="{{ old('experience', $cv->experience) }}">

      <div id="exp-builder" class="cv-builder"></div>
      @error('experience')<p class="field__server-error">{{ $message }}</p>@enderror

      <button type="button" class="builder-add-btn" id="add-exp">
        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Ajouter une expérience
      </button>

      <template id="exp-tpl">
        <div class="cv-block">
          <div class="cv-block__head">
            <span class="cv-block__num"></span>
            <button type="button" class="cv-block__del" title="Supprimer">
              <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
          </div>
          <div class="cv-block__body">
            <div class="form-row form-row--2">
              <div>
                <label class="field__label">Poste <span class="req">*</span></label>
                <input type="text" class="field__input exp-poste" placeholder="ex: Comptable, Caissier, Infirmier…">
              </div>
              <div>
                <label class="field__label">Entreprise <span class="req">*</span></label>
                <input type="text" class="field__input exp-entreprise" placeholder="ex: MTN Bénin, SONEB, Ecobank…">
              </div>
            </div>
            <div class="form-row form-row--2">
              <div>
                <label class="field__label">Lieu</label>
                <input type="text" class="field__input exp-lieu" placeholder="ex: Cotonou, Porto-Novo…">
              </div>
              <div>
                <label class="field__label">Secteur</label>
                <input type="text" class="field__input exp-secteur" placeholder="ex: Banque, BTP, Santé…">
              </div>
            </div>
            <div class="form-row form-row--2">
              <div>
                <label class="field__label">Date de début <span class="req">*</span></label>
                <input type="month" class="field__input exp-debut">
              </div>
              <div>
                <label class="field__label">Date de fin <span style="font-size:11px;color:#94a3b8">(laisser vide si en cours)</span></label>
                <input type="month" class="field__input exp-fin">
              </div>
            </div>
            <div>
              <label class="field__label">Missions / Responsabilités</label>
              <div class="inline-tag-builder">
                <div class="inline-tag-box">
                  <div class="inline-tag-list"></div>
                  <input type="text" class="inline-tag-text" placeholder="ex: Tenue de la comptabilité… puis Entrée" maxlength="120">
                </div>
                <button type="button" class="inline-tag-addbtn">+ Ajouter</button>
              </div>
              <p class="field__hint">Max 20 missions · <kbd>Entrée</kbd> ou clic sur Ajouter</p>
            </div>
          </div>
        </div>
      </template>

      {{-- ── FORMATIONS ───────────────────────────────────── --}}
      <div class="form-section-sub" style="margin-top:24px">
        <span>Formations <span class="req">*</span></span>
      </div>
      <input type="hidden" name="formation" id="form-hidden" value="{{ old('formation', $cv->formation) }}">

      <div id="form-builder" class="cv-builder"></div>
      @error('formation')<p class="field__server-error">{{ $message }}</p>@enderror

      <button type="button" class="builder-add-btn" id="add-form">
        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Ajouter une formation
      </button>

      <template id="form-tpl">
        <div class="cv-block">
          <div class="cv-block__head">
            <span class="cv-block__num"></span>
            <button type="button" class="cv-block__del" title="Supprimer">
              <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
          </div>
          <div class="cv-block__body">
            <div class="form-row form-row--2">
              <div>
                <label class="field__label">Diplôme <span class="req">*</span></label>
                <input type="text" class="field__input form-diplome" placeholder="ex: Licence en Gestion, BTS Commerce…">
              </div>
              <div>
                <label class="field__label">Établissement <span class="req">*</span></label>
                <input type="text" class="field__input form-etablissement" placeholder="ex: UAC, ENEAM, UP Parakou…">
              </div>
            </div>
            <div class="form-row form-row--1">
              <div>
                <label class="field__label">Domaine / Spécialité</label>
                <input type="text" class="field__input form-domaine" placeholder="ex: Comptabilité, Droit, Génie Civil…">
              </div>
            </div>
            <div class="form-row form-row--2">
              <div>
                <label class="field__label">Date de début <span class="req">*</span></label>
                <input type="month" class="field__input form-debut">
              </div>
              <div>
                <label class="field__label">Date de fin <span style="font-size:11px;color:#94a3b8">(laisser vide si en cours)</span></label>
                <input type="month" class="field__input form-fin">
              </div>
            </div>
            <div>
              <label class="field__label">Activités / Réalisations</label>
              <div class="inline-tag-builder">
                <div class="inline-tag-box">
                  <div class="inline-tag-list"></div>
                  <input type="text" class="inline-tag-text" placeholder="ex: Major de promotion, Stage à BOA Bénin… puis Entrée" maxlength="120">
                </div>
                <button type="button" class="inline-tag-addbtn">+ Ajouter</button>
              </div>
              <p class="field__hint">Max 20 activités · <kbd>Entrée</kbd> ou clic sur Ajouter</p>
            </div>
          </div>
        </div>
      </template>

      <div class="form-row form-row--1">
        <div>
          <label class="field__label">Langues <span class="req">*</span></label>
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
            <input type="file" id="cvFile" name="fichier_path" accept=".pdf">
            <div class="upload-zone__icon">
              <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
              </svg>
            </div>
            <div class="upload-zone__title">Glissez votre fichier ici ou <span>cliquez pour charger</span></div>
            <div class="upload-zone__hint">PDF uniquement, max 5 Mo{{ $cv->fichier_path ? ' (optionnel)' : '' }}</div>
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

/* ── Tag input (compétences) ── */
function makeTagInput(wrapId, hiddenId, allSuggestions) {
  const wrap    = document.getElementById(wrapId);
  const box     = wrap.querySelector('.tag-input-box');
  const tagsEl  = wrap.querySelector('.tag-input-tags');
  const textEl  = wrap.querySelector('.tag-input-text');
  const sugEl   = wrap.querySelector('.tag-suggestions');
  const hidden  = document.getElementById(hiddenId);

  let tags = hidden.value ? hidden.value.split(',').map(s => s.trim()).filter(Boolean) : [];
  let activeIdx = -1;

  function sync() { hidden.value = tags.join(', '); }

  function renderTags() {
    tagsEl.innerHTML = tags.map((t, i) =>
      `<span class="tag-chip">${t}<button type="button" class="tag-chip__remove" data-i="${i}" tabindex="-1">×</button></span>`
    ).join('');
    sync();
  }

  function addTag(val) {
    val = val.trim();
    if (!val || tags.includes(val)) { textEl.value = ''; return; }
    tags.push(val);
    renderTags();
    textEl.value = '';
    hideSug();
  }

  function showSug(q) {
    q = q.toLowerCase().trim();
    if (!q) { hideSug(); return; }
    const hits = allSuggestions.filter(s => s.toLowerCase().includes(q) && !tags.includes(s)).slice(0, 8);
    if (!hits.length) { hideSug(); return; }
    sugEl.innerHTML = hits.map(s => `<li data-val="${s.replace(/"/g,'&quot;')}">${s}</li>`).join('');
    sugEl.classList.add('ts-open');
    activeIdx = -1;
  }

  function hideSug() { sugEl.classList.remove('ts-open'); activeIdx = -1; }

  function moveActive(dir) {
    const items = sugEl.querySelectorAll('li');
    if (!items.length) return;
    activeIdx = Math.max(0, Math.min(items.length - 1, activeIdx + dir));
    items.forEach((li, i) => li.classList.toggle('ts-active', i === activeIdx));
  }

  tagsEl.addEventListener('click', e => {
    const btn = e.target.closest('.tag-chip__remove');
    if (btn) { tags.splice(+btn.dataset.i, 1); renderTags(); }
  });

  textEl.addEventListener('input', () => showSug(textEl.value));

  textEl.addEventListener('keydown', e => {
    if (e.key === 'ArrowDown') { e.preventDefault(); moveActive(1); return; }
    if (e.key === 'ArrowUp')   { e.preventDefault(); moveActive(-1); return; }
    if (e.key === 'Escape')    { hideSug(); return; }

    if (e.key === 'Enter' || e.key === ',') {
      e.preventDefault();
      const active = sugEl.querySelector('li.ts-active');
      addTag(active ? active.dataset.val : textEl.value);
      return;
    }
    if (e.key === 'Backspace' && !textEl.value && tags.length) {
      tags.pop(); renderTags();
    }
  });

  sugEl.addEventListener('mousedown', e => {
    const li = e.target.closest('li');
    if (li) { e.preventDefault(); addTag(li.dataset.val); }
  });

  box.addEventListener('click', () => textEl.focus());
  document.addEventListener('click', e => { if (!wrap.contains(e.target)) hideSug(); });

  renderTags();
}

makeTagInput('comp-wrap', 'comp-hidden', @json($competences));

/* ══════════════════════════════════════════════════
   BUILDERS Expérience & Formation
══════════════════════════════════════════════════ */

/* ── Inline tag builder (missions / activités) ── */
function initInlineTagBuilder(block, max, initialTags) {
  const box    = block.querySelector('.inline-tag-box');
  const list   = block.querySelector('.inline-tag-list');
  const txt    = block.querySelector('.inline-tag-text');
  const addBtn = block.querySelector('.inline-tag-addbtn');
  let tags = Array.isArray(initialTags) ? initialTags.slice(0, max) : [];

  function render() {
    list.innerHTML = tags.map((t, i) =>
      `<span class="inline-tag-chip">${t}<button type="button" data-i="${i}" tabindex="-1">×</button></span>`
    ).join('');
  }

  function addTag(val) {
    val = val.trim();
    if (!val || tags.includes(val) || tags.length >= max) { txt.value = ''; return; }
    tags.push(val);
    render();
    txt.value = '';
  }

  list.addEventListener('click', e => {
    const btn = e.target.closest('button');
    if (btn) { tags.splice(+btn.dataset.i, 1); render(); }
  });

  txt.addEventListener('keydown', e => {
    if (e.key === 'Enter') { e.preventDefault(); addTag(txt.value); }
  });

  addBtn.addEventListener('click', () => addTag(txt.value));
  box.addEventListener('click', () => txt.focus());

  render();
  return { getTags: () => tags };
}

/* ── Renumber blocks ── */
function renumberBlocks(builderId, label) {
  document.querySelectorAll(`#${builderId} .cv-block__num`).forEach((el, i) => {
    el.textContent = `${label} ${i + 1}`;
  });
}

/* ── Format month string (YYYY-MM → "mois AAAA") ── */
const MOIS_LABELS = ['jan.','fév.','mars','avr.','mai','juin','juil.','août','sept.','oct.','nov.','déc.'];
const MOIS_INDEX  = MOIS_LABELS.reduce((acc, m, i) => (acc[m] = i + 1, acc), {});

function fmtMonth(val) {
  if (!val) return '';
  const [y, m] = val.split('-');
  return `${MOIS_LABELS[+m - 1] || ''} ${y}`;
}

/* ── Reconstitution au mieux d'un mois affiché ("jan. 2022") → "YYYY-MM" ── */
function parseMonthLabel(label) {
  label = (label || '').trim();
  if (!label || label === 'en cours') return '';
  const parts = label.split(' ');
  if (parts.length !== 2) return '';
  const mois = MOIS_INDEX[parts[0]];
  if (!mois || !/^\d{4}$/.test(parts[1])) return '';
  return parts[1] + '-' + String(mois).padStart(2, '0');
}

/* ── Reconstitution au mieux d'un bloc "Poste — Entreprise | Lieu | Secteur | debut → fin\nMissions : a, b" ── */
function parseCvBloc(bloc, metaLabel) {
  const lignes = bloc.split('\n');
  const premiereLigne = lignes[0] || '';
  let tags = [];
  if (lignes[1] && lignes[1].indexOf(metaLabel + ' : ') === 0) {
    tags = lignes[1].substring((metaLabel + ' : ').length).split(',').map(s => s.trim()).filter(Boolean);
  }
  const segments = premiereLigne.split(' | ');
  const tete = segments[0] || '';
  const metaParts = segments.slice(1);
  let partie1 = tete, partie2 = '';
  const sepIdx = tete.indexOf(' — ');
  if (sepIdx !== -1) {
    partie1 = tete.substring(0, sepIdx);
    partie2 = tete.substring(sepIdx + 3);
  }
  let debut = '', fin = '';
  const reste = [];
  metaParts.forEach(part => {
    if (part.indexOf('→') !== -1 && !debut && !fin) {
      const morceaux = part.split('→').map(s => s.trim());
      debut = parseMonthLabel(morceaux[0]);
      fin   = parseMonthLabel(morceaux[1]);
    } else {
      reste.push(part.trim());
    }
  });
  return { partie1: partie1.trim(), partie2: partie2.trim(), reste, debut, fin, tags };
}

/* ════════ EXPÉRIENCES ════════ */
const expBuilder  = document.getElementById('exp-builder');
const expHidden   = document.getElementById('exp-hidden');
const expTpl      = document.getElementById('exp-tpl');
const expTags     = new Map(); // block element → { getTags }

function addExpBlock(donnees) {
  const clone = expTpl.content.cloneNode(true);
  const block = clone.querySelector('.cv-block');
  const tagApi = initInlineTagBuilder(block, 20, donnees ? donnees.tags : []);
  expBuilder.appendChild(block);
  const inserted = expBuilder.lastElementChild;
  expTags.set(inserted, tagApi);
  if (donnees) {
    inserted.querySelector('.exp-poste').value      = donnees.partie1 || '';
    inserted.querySelector('.exp-entreprise').value = donnees.partie2 || '';
    inserted.querySelector('.exp-lieu').value       = donnees.reste[0] || '';
    inserted.querySelector('.exp-secteur').value    = donnees.reste[1] || '';
    inserted.querySelector('.exp-debut').value      = donnees.debut || '';
    inserted.querySelector('.exp-fin').value        = donnees.fin || '';
  }
  renumberBlocks('exp-builder', 'Expérience');
  inserted.querySelector('.cv-block__del').addEventListener('click', () => {
    expTags.delete(inserted);
    inserted.remove();
    renumberBlocks('exp-builder', 'Expérience');
  });
}

document.getElementById('add-exp').addEventListener('click', () => addExpBlock());

function serializeExp() {
  const parts = [];
  expBuilder.querySelectorAll('.cv-block').forEach(block => {
    const poste      = block.querySelector('.exp-poste')?.value.trim()      || '';
    const entreprise = block.querySelector('.exp-entreprise')?.value.trim() || '';
    if (!poste && !entreprise) return;
    const lieu    = block.querySelector('.exp-lieu')?.value.trim()    || '';
    const secteur = block.querySelector('.exp-secteur')?.value.trim() || '';
    const debut   = fmtMonth(block.querySelector('.exp-debut')?.value)  || '';
    const fin     = fmtMonth(block.querySelector('.exp-fin')?.value)    || 'en cours';
    const tags    = expTags.get(block)?.getTags() || [];

    let line = `${poste}${entreprise ? ' — ' + entreprise : ''}`;
    const meta = [lieu, secteur, debut && fin ? `${debut} → ${fin}` : debut].filter(Boolean);
    if (meta.length) line += ' | ' + meta.join(' | ');
    if (tags.length) line += '\nMissions : ' + tags.join(', ');
    parts.push(line);
  });
  expHidden.value = parts.join('\n\n');
}

/* ════════ FORMATIONS ════════ */
const formBuilder = document.getElementById('form-builder');
const formHidden  = document.getElementById('form-hidden');
const formTpl     = document.getElementById('form-tpl');
const formTags    = new Map();

function addFormBlock(donnees) {
  const clone = formTpl.content.cloneNode(true);
  const block = clone.querySelector('.cv-block');
  const tagApi = initInlineTagBuilder(block, 20, donnees ? donnees.tags : []);
  formBuilder.appendChild(block);
  const inserted = formBuilder.lastElementChild;
  formTags.set(inserted, tagApi);
  if (donnees) {
    inserted.querySelector('.form-diplome').value       = donnees.partie1 || '';
    inserted.querySelector('.form-etablissement').value = donnees.partie2 || '';
    inserted.querySelector('.form-domaine').value       = donnees.reste[0] || '';
    inserted.querySelector('.form-debut').value         = donnees.debut || '';
    inserted.querySelector('.form-fin').value           = donnees.fin || '';
  }
  renumberBlocks('form-builder', 'Formation');
  inserted.querySelector('.cv-block__del').addEventListener('click', () => {
    formTags.delete(inserted);
    inserted.remove();
    renumberBlocks('form-builder', 'Formation');
  });
}

document.getElementById('add-form').addEventListener('click', () => addFormBlock());

function serializeForm() {
  const parts = [];
  formBuilder.querySelectorAll('.cv-block').forEach(block => {
    const diplome  = block.querySelector('.form-diplome')?.value.trim()       || '';
    const etabl    = block.querySelector('.form-etablissement')?.value.trim() || '';
    if (!diplome && !etabl) return;
    const domaine = block.querySelector('.form-domaine')?.value.trim() || '';
    const debut   = fmtMonth(block.querySelector('.form-debut')?.value)  || '';
    const fin     = fmtMonth(block.querySelector('.form-fin')?.value)    || 'en cours';
    const tags    = formTags.get(block)?.getTags() || [];

    let line = `${diplome}${etabl ? ' — ' + etabl : ''}`;
    const meta = [domaine, debut && fin ? `${debut} → ${fin}` : debut].filter(Boolean);
    if (meta.length) line += ' | ' + meta.join(' | ');
    if (tags.length) line += '\nActivités : ' + tags.join(', ');
    parts.push(line);
  });
  formHidden.value = parts.join('\n\n');
}

/* ── Chargement initial : reconstitution des blocs depuis le texte existant ── */
(function () {
  const expExistant = expHidden.value;
  if (expExistant) {
    expExistant.split('\n\n').filter(Boolean).forEach(bloc => {
      addExpBlock(parseCvBloc(bloc, 'Missions'));
    });
  }
  const formExistant = formHidden.value;
  if (formExistant) {
    formExistant.split('\n\n').filter(Boolean).forEach(bloc => {
      addFormBlock(parseCvBloc(bloc, 'Activités'));
    });
  }
})();

/* ── Sérialisation avant soumission ── */
document.querySelector('form[action*="mes-cvs"]').addEventListener('submit', function () {
  serializeExp();
  serializeForm();
});
</script>

@endsection
