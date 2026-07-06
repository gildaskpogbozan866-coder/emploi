@extends('layouts.recruteur')
@section('title', 'Modifier l\'offre')

@section('css')
@include('partials._jquery-cdn')
@once
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/fr.js"></script>
@endonce
<style>
.offre-preview-grid { display: grid; grid-template-columns: 1fr 280px; gap: 0; }
@media (max-width: 640px) {
  .offre-preview-grid { grid-template-columns: 1fr; }
  .offre-preview-grid > div:first-child { border-right: none !important; border-bottom: 1px solid #f1f5f9; }
}
@media (max-width: 480px) {
  .offre-edit-actions .rec-btn { width: 100%; justify-content: center; min-width: 0; }
}
.offre-edit-grid2 { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); gap: 16px; margin-bottom: 18px; }
@media (max-width: 640px) {
  .offre-edit-grid2 { grid-template-columns: minmax(0, 1fr); }
}
</style>
@endsection

@section('sidebar')
@include('recruteur._sidebar')
@endsection

@section('content')
<div class="rec-topbar">
  <div class="rec-topbar__left">
    <a href="{{ route('recruteur.offres') }}" class="rec-btn rec-btn--outline rec-btn--sm" style="margin-bottom:8px"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg> Retour</a>
    <h1>Modifier l'offre</h1>
    <p>{{ $offre->titre }}</p>
  </div>
</div>

<div class="rec-card">
  <div class="rec-card__body" style="padding:28px">
    <form method="POST" action="{{ route('recruteur.offres.update', $offre) }}" enctype="multipart/form-data">
      @csrf @method('PUT')

      <div style="margin-bottom:18px">
        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Titre du poste <span style="color:#e53e3e">*</span></label>
        <input type="text" name="titre" value="{{ old('titre', $offre->titre) }}" required
               style="width:100%;padding:10px 14px;border:1.5px solid {{ $errors->has('titre') ? '#e53e3e' : '#d1d5db' }};border-radius:8px;font-size:14px;box-sizing:border-box">
        @error('titre') <p style="color:#e53e3e;font-size:12px;margin:3px 0 0">{{ $message }}</p> @enderror
      </div>

      <div class="offre-edit-grid2">
        <div>
          <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Entreprise <span style="color:#e53e3e">*</span></label>
          <input type="text" name="entreprise" value="{{ old('entreprise', $offre->entreprise) }}" required
                 style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box">
        </div>
        <div>
          <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Localisation <span style="color:#e53e3e">*</span></label>
          <input type="text" name="localisation" value="{{ old('localisation', $offre->localisation) }}" required
                 style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box">
        </div>
      </div>

      <div class="offre-edit-grid2">
        <div>
          <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Type de contrat <span style="color:#e53e3e">*</span></label>
          <select name="type" required style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box">
            <option value="">Sélectionner</option>
            @foreach($typeContrats as $tc)
              <option value="{{ $tc->id }}" {{ old('type', $offre?->type_contrat_id) == $tc->id ? 'selected' : '' }}>{{ $tc->libelle }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Secteur d'activité</label>
          <x-secteur-select name="secteur" :selected="collect(old('secteur', $offre->secteur ?? []))" />
        </div>
      </div>

      <div class="offre-edit-grid2">
        <div>
          <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Rémunération (FCFA / mois)</label>
          <div style="display:flex;gap:8px;align-items:center">
            <input type="number" name="salaire_min" value="{{ old('salaire_min', $offre->salaire_min) }}" placeholder="Min" min="0" step="1000"
                   style="flex:1;min-width:0;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box">
            <span style="color:#94a3b8;font-size:13px">–</span>
            <input type="number" name="salaire_max" value="{{ old('salaire_max', $offre->salaire_max) }}" placeholder="Max" min="0" step="1000"
                   style="flex:1;min-width:0;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box">
          </div>
        </div>
        <div>
          <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Date limite de candidature</label>
          <input type="date" name="date_limite" value="{{ old('date_limite', $offre->date_limite?->format('Y-m-d')) }}"
                 style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box">
        </div>
      </div>

      <div style="margin-bottom:18px">
        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Métier / Fonction</label>
        <select name="metier_id" id="metier-select-edit" style="width:100%">
          <option value="">Sélectionner un métier…</option>
          @foreach($metiers as $m)
            <option value="{{ $m->id }}" {{ old('metier_id', $offre->metier_id) == $m->id ? 'selected' : '' }}>{{ $m->nom }}</option>
          @endforeach
        </select>
        @error('metier_id')<p style="color:#e53e3e;font-size:12px;margin:3px 0 0">{{ $message }}</p>@enderror
      </div>

      <div class="offre-edit-grid2">
        <div>
          <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Niveau d'expérience</label>
          <select name="niveau_experience" style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box">
            <option value="">Non précisé</option>
            @foreach($niveauxExp as $n)
              <option value="{{ $n->code }}" {{ old('niveau_experience', $offre->niveau_experience) === $n->code ? 'selected' : '' }}>{{ $n->libelle }}</option>
            @endforeach
          </select>
          @error('niveau_experience')<p style="color:#e53e3e;font-size:12px;margin:3px 0 0">{{ $message }}</p>@enderror
        </div>
        <div>
          <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Niveau d'études requis</label>
          <select name="niveau_etude" style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box">
            <option value="">Non précisé</option>
            @foreach($niveauxEtude as $n)
              <option value="{{ $n->code }}" {{ old('niveau_etude', $offre->niveau_etude) === $n->code ? 'selected' : '' }}>{{ $n->libelle }}</option>
            @endforeach
          </select>
          @error('niveau_etude')<p style="color:#e53e3e;font-size:12px;margin:3px 0 0">{{ $message }}</p>@enderror
        </div>
      </div>

      <div style="margin-bottom:18px">
        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Description complète <span style="color:#e53e3e">*</span></label>
        <x-summernote name="description" :value="old('description', $offre->description)" :height="300" />
        @error('description') <p style="color:#e53e3e;font-size:12px;margin:3px 0 0">{{ $message }}</p> @enderror
      </div>

      <div style="margin-bottom:24px">
        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Compétences requises</label>
        <x-competences-select name="competences"
            :selected="collect(old('competences')) ?: $offre->competences->pluck('nom')" />
      </div>

      <div style="margin-bottom:24px">
        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:10px">Documents à fournir par le candidat</label>
        <div style="display:flex;flex-wrap:wrap;gap:16px">
          @php $exigeCv     = old('exige_cv',     $offre->exige_cv     ? '1' : '0') == '1'; @endphp
          @php $exigeLettre = old('exige_lettre',  $offre->exige_lettre ? '1' : '0') == '1'; @endphp
          <label style="display:flex;align-items:center;gap:10px;padding:12px 18px;border:1.5px solid {{ $exigeCv ? '#185FA5' : '#d1d5db' }};border-radius:10px;cursor:pointer;font-weight:500;font-size:14px;background:{{ $exigeCv ? '#eff6ff' : '#f9fafb' }};transition:border-color .15s">
            <input type="checkbox" name="exige_cv" value="1" {{ $exigeCv ? 'checked' : '' }}
                   onchange="this.closest('label').style.borderColor=this.checked?'#185FA5':'#d1d5db';this.closest('label').style.background=this.checked?'#eff6ff':'#f9fafb'">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Exiger un CV (PDF / Word)
          </label>
          <label style="display:flex;align-items:center;gap:10px;padding:12px 18px;border:1.5px solid {{ $exigeLettre ? '#185FA5' : '#d1d5db' }};border-radius:10px;cursor:pointer;font-weight:500;font-size:14px;background:{{ $exigeLettre ? '#eff6ff' : '#f9fafb' }};transition:border-color .15s">
            <input type="checkbox" name="exige_lettre" value="1" {{ $exigeLettre ? 'checked' : '' }}
                   onchange="this.closest('label').style.borderColor=this.checked?'#185FA5':'#d1d5db';this.closest('label').style.background=this.checked?'#eff6ff':'#f9fafb'">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            Exiger une lettre de motivation (PDF / Word)
          </label>
        </div>
        <small style="color:#94a3b8;margin-top:6px;display:block">Si coché, le champ sera obligatoire pour les candidats lors de la soumission.</small>
      </div>

      <div style="margin-bottom:24px">
        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:10px">Autres pièces justificatives requises <small style="color:#94a3b8;font-weight:400">(optionnel)</small></label>
        @php $requisIds = old('types_documents_requis', $offre->typesDocumentsRequis->pluck('id')->all()); @endphp
        <div style="display:flex;flex-wrap:wrap;gap:10px">
          @foreach($typesDocuments as $type)
          <label style="display:flex;align-items:center;gap:8px;padding:9px 14px;border:1.5px solid {{ in_array($type->id, $requisIds) ? '#185FA5' : '#d1d5db' }};border-radius:9px;cursor:pointer;font-weight:500;font-size:13.5px;background:{{ in_array($type->id, $requisIds) ? '#eff6ff' : '#f9fafb' }};transition:border-color .15s">
            <input type="checkbox" name="types_documents_requis[]" value="{{ $type->id }}"
                   {{ in_array($type->id, $requisIds) ? 'checked' : '' }}
                   onchange="this.closest('label').style.borderColor=this.checked?'#185FA5':'#d1d5db';this.closest('label').style.background=this.checked?'#eff6ff':'#f9fafb'"
                   style="accent-color:#185FA5">
            {{ $type->nom }}
          </label>
          @endforeach
        </div>
        <small style="color:#94a3b8;margin-top:6px;display:block">Le candidat devra fournir un document de chaque type coché (existant dans son espace ou téléversé lors de la candidature).</small>
      </div>

      <div style="margin-bottom:24px">
        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">
          Logo de l'entreprise <small style="font-weight:400;color:#94a3b8">(JPG, PNG, WebP · max 2 Mo)</small>
        </label>
        @if($offre->logo)
        <div style="display:flex;align-items:center;gap:12px;padding:10px 14px;background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:8px;margin-bottom:10px">
          <img src="{{ asset('storage/' . $offre->logo) }}" alt="Logo" style="height:48px;width:48px;object-fit:contain;border-radius:8px;background:#fff;border:1px solid #e2e8f0;padding:3px">
          <span style="font-size:13px;color:#16a34a;font-weight:600">Logo actuel</span>
          <label style="margin-left:auto;display:flex;align-items:center;gap:6px;font-size:12px;color:#dc2626;cursor:pointer">
            <input type="checkbox" name="_supprimer_logo" value="1"> Supprimer
          </label>
        </div>
        @endif
        <input type="file" name="logo" accept=".jpg,.jpeg,.png,.webp,.svg" id="logo-input-edit"
               style="width:100%;padding:8px 12px;border:1.5px solid #d1d5db;border-radius:8px;font-size:13px;background:#fff;cursor:pointer;box-sizing:border-box"
               onchange="previewLogo(this,'logo-preview-edit')">
        <div id="logo-preview-edit" style="display:none;margin-top:8px">
          <img src="" alt="Aperçu" style="height:60px;width:60px;object-fit:contain;border:1.5px solid #e2e8f0;border-radius:10px;padding:4px;background:#fff">
        </div>
        <small style="color:#94a3b8">Affiché sur votre annonce publique.</small>
        @error('logo') <p style="color:#e53e3e;font-size:12px;margin:3px 0 0">{{ $message }}</p> @enderror
      </div>

      <div style="margin-bottom:24px">
        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">
          Document joint <small style="font-weight:400;color:#94a3b8">(PDF, DOC, DOCX · max 5 Mo)</small>
        </label>
        @if($offre->fichier)
        <div style="display:flex;align-items:center;gap:12px;padding:10px 14px;background:#f0f9ff;border:1.5px solid #bae6fd;border-radius:8px;margin-bottom:10px">
          <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#0284c7" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
          <a href="{{ Storage::url($offre->fichier) }}" target="_blank" style="font-size:13px;color:#0284c7;font-weight:600;text-decoration:none">Voir le document actuel</a>
          <label style="margin-left:auto;display:flex;align-items:center;gap:6px;font-size:12px;color:#dc2626;cursor:pointer">
            <input type="checkbox" name="_supprimer_fichier" value="1"> Supprimer
          </label>
        </div>
        @endif
        <input type="file" name="fichier" accept=".pdf,.doc,.docx"
               style="width:100%;padding:8px 12px;border:1.5px solid #d1d5db;border-radius:8px;font-size:13px;background:#fff;cursor:pointer;box-sizing:border-box">
        @error('fichier') <p style="color:#e53e3e;font-size:12px;margin:3px 0 0">{{ $message }}</p> @enderror
      </div>

      <div class="offre-edit-actions" style="display:flex;gap:12px;flex-wrap:wrap">
        <button type="submit" class="rec-btn rec-btn--yellow" style="min-width:200px">Enregistrer les modifications</button>
        <button type="button" onclick="openOffrePreview()" class="rec-btn rec-btn--outline">
          <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px;margin-right:5px"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
          Prévisualiser
        </button>
        <a href="{{ route('recruteur.offres') }}" class="rec-btn rec-btn--outline">Annuler</a>
      </div>
    </form>
  </div>
</div>

{{-- Modale de prévisualisation --}}
<div id="offre-preview-modal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(4,44,83,.55);overflow-y:auto;padding:32px 16px" onclick="if(event.target===this)closeOffrePreview()">
  <div style="max-width:860px;margin:0 auto;box-shadow:0 20px 60px rgba(4,44,83,.25);border-radius:16px">
    <div style="position:sticky;top:0;z-index:10;display:flex;align-items:center;justify-content:space-between;padding:16px 24px;background:#042C53;color:#fff;border-radius:16px 16px 0 0">
      <div style="display:flex;align-items:center;gap:10px">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
        <span style="font-weight:700;font-size:15px">Prévisualisation de l'offre</span>
      </div>
      <button onclick="closeOffrePreview()" style="background:rgba(255,255,255,.15);border:none;color:#fff;width:32px;height:32px;border-radius:8px;cursor:pointer;font-size:18px;display:flex;align-items:center;justify-content:center;flex-shrink:0">&times;</button>
    </div>
    <div id="offre-preview-body" style="padding:0;background:#fff;border-radius:0 0 16px 16px;overflow:hidden"></div>
  </div>
</div>
@endsection

@section('scripts')
<script>
function previewLogo(input, previewId) {
  const preview = document.getElementById(previewId);
  if (input.files && input.files[0]) {
    const reader = new FileReader();
    reader.onload = e => {
      preview.querySelector('img').src = e.target.result;
      preview.style.display = 'block';
    };
    reader.readAsDataURL(input.files[0]);
  }
}
(function () {
  function initMetierSelect() {
    if (!window.$ || !$.fn.select2) return;
    $('#metier-select-edit').select2({
      language: 'fr',
      placeholder: 'Rechercher un métier…',
      allowClear: true,
      width: '100%',
    });
  }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initMetierSelect);
  } else {
    initMetierSelect();
  }
})();

function openOffrePreview() {
  const get = (name) => document.querySelector('[name="' + name + '"]');
  const val = (name) => get(name)?.value?.trim() || '';
  const selText = (name) => { const el = get(name); return el?.options[el.selectedIndex]?.text || ''; };

  const titre        = val('titre') || '(Titre non renseigné)';
  const entreprise   = val('entreprise') || '–';
  const localisation = val('localisation');
  const type         = selText('type');
  const metier       = selText('metier_id');
  const salaireMin   = val('salaire_min');
  const salaireMax   = val('salaire_max');
  const dateLimite   = val('date_limite');
  const niveauExp    = selText('niveau_experience');
  const niveauEtude  = selText('niveau_etude');
  const exigences    = val('exigences');

  let description = '';
  if (window.$ && typeof $('#sn-description').summernote === 'function') {
    description = $('#sn-description').summernote('code') || '';
  }

  const fmt = (n) => parseInt(n).toLocaleString('fr-FR');
  let salaire = '';
  if (salaireMin && salaireMax) salaire = fmt(salaireMin) + ' – ' + fmt(salaireMax) + ' FCFA';
  else if (salaireMin) salaire = 'À partir de ' + fmt(salaireMin) + ' FCFA';
  else if (salaireMax) salaire = 'Jusqu\'à ' + fmt(salaireMax) + ' FCFA';

  const logoImg = document.querySelector('#logo-preview-edit img');
  const logoHtml = logoImg?.src && logoImg.src !== window.location.href
    ? '<img src="' + logoImg.src + '" style="width:100%;height:100%;object-fit:contain;padding:6px">'
    : '<span style="font-weight:800;font-size:1.2rem;color:#185FA5">' + entreprise.substring(0,2).toUpperCase() + '</span>';

  let badges = '';
  if (type)         badges += '<span style="display:inline-flex;align-items:center;padding:4px 12px;background:rgba(255,255,255,.2);border:1px solid rgba(255,255,255,.35);border-radius:20px;font-size:12.5px;font-weight:600;color:#fff">' + type + '</span> ';
  if (localisation) badges += '<span style="display:inline-flex;align-items:center;gap:4px;padding:4px 12px;background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.25);border-radius:20px;font-size:12.5px;color:rgba(255,255,255,.9)">📍 ' + localisation + '</span>';

  const row = (icon, label, value) => value
    ? '<div style="display:flex;align-items:flex-start;gap:12px;padding:10px 0;border-bottom:1px solid #f1f5f9"><div style="width:32px;height:32px;border-radius:8px;background:#f0f7ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;color:#185FA5">' + icon + '</div><div><p style="font-size:11px;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:.06em;margin:0 0 2px">' + label + '</p><p style="font-size:13.5px;color:#042C53;font-weight:600;margin:0">' + value + '</p></div></div>'
    : '';

  const iconType = '<svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg>';
  const iconLoc  = '<svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>';
  const iconSal  = '<svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
  const iconExp  = '<svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>';
  const iconDate = '<svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>';

  const dateFormatee = dateLimite ? new Date(dateLimite).toLocaleDateString('fr-FR', {day:'2-digit',month:'2-digit',year:'numeric'}) : '';

  const html = `
    <div style="background:linear-gradient(135deg,#042C53,#185FA5);padding:28px 32px">
      <div style="display:flex;align-items:flex-start;gap:18px">
        <div style="width:64px;height:64px;border-radius:14px;background:rgba(255,255,255,.15);border:2px solid rgba(255,255,255,.3);display:flex;align-items:center;justify-content:center;flex-shrink:0;overflow:hidden">${logoHtml}</div>
        <div>
          <h1 style="font-size:1.4rem;font-weight:800;color:#fff;margin:0 0 4px">${titre}</h1>
          <p style="font-size:14px;color:rgba(255,255,255,.75);margin:0 0 12px">${entreprise}</p>
          <div style="display:flex;flex-wrap:wrap;gap:8px">${badges}</div>
        </div>
      </div>
    </div>
    <div class="offre-preview-grid">
      <div style="padding:24px;border-right:1px solid #f1f5f9">
        ${description ? `<div style="margin-bottom:24px"><h3 style="font-size:13px;font-weight:700;color:#042C53;text-transform:uppercase;letter-spacing:.08em;margin:0 0 12px;padding-bottom:8px;border-bottom:2px solid #e8f0fe">Description du poste</h3><div style="font-size:14px;color:#374151;line-height:1.75">${description}</div></div>` : ''}
        ${exigences ? `<div style="margin-bottom:24px"><h3 style="font-size:13px;font-weight:700;color:#042C53;text-transform:uppercase;letter-spacing:.08em;margin:0 0 12px;padding-bottom:8px;border-bottom:2px solid #e8f0fe">Exigences du poste</h3><div style="font-size:14px;color:#374151;line-height:1.75;white-space:pre-line">${exigences.replace(/</g,'&lt;')}</div></div>` : ''}
        ${!description && !exigences ? '<p style="color:#94a3b8;font-style:italic;font-size:13px">Renseignez la description et les exigences pour voir le contenu ici.</p>' : ''}
      </div>
      <div style="padding:20px">
        <div style="background:#f8fafc;border-radius:12px;padding:16px;margin-bottom:16px">
          <p style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin:0 0 10px">Détails de l'offre</p>
          ${row(iconType, 'Type de contrat', type)}
          ${row(iconLoc,  'Localisation',    localisation)}
          ${row(iconSal,  'Rémunération',    salaire)}
          ${row(iconExp,  'Expérience',      niveauExp)}
          ${row(iconExp,  'Niveau d\'études', niveauEtude)}
          ${row(iconDate, 'Date limite',      dateFormatee)}
        </div>
        <div style="background:#fef9ec;border:1.5px solid #fcd34d;border-radius:10px;padding:12px 14px">
          <p style="font-size:12px;color:#92400e;font-weight:600;margin:0 0 4px">Aperçu uniquement</p>
          <p style="font-size:11.5px;color:#92400e;margin:0">Ceci est une prévisualisation. Enregistrez pour appliquer les modifications.</p>
        </div>
      </div>
    </div>`;

  document.getElementById('offre-preview-body').innerHTML = html;
  document.getElementById('offre-preview-modal').style.display = 'block';
  document.body.style.overflow = 'hidden';
}

function closeOffrePreview() {
  document.getElementById('offre-preview-modal').style.display = 'none';
  document.body.style.overflow = '';
}

document.addEventListener('keydown', (e) => { if (e.key === 'Escape') closeOffrePreview(); });
</script>
@endsection
