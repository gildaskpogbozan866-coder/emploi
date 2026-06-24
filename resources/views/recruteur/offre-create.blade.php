@extends('layouts.recruteur')
@section('title', 'Publier une offre')

@section('css')
@include('partials._jquery-cdn')
@once
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/fr.js"></script>
@endonce
<style>
#metier-select-create + .select2-container .select2-selection--single {
  border: 1.5px solid #d1d5db;
  border-radius: 8px;
  height: 42px;
  display: flex;
  align-items: center;
}
</style>
@endsection

@section('sidebar')
@include('recruteur._sidebar')
@endsection

@section('content')
<div class="rec-topbar">
  <div class="rec-topbar__left">
    <h1>Publier une offre d'emploi</h1>
    <p>Votre offre sera publiée immédiatement et visible par tous les candidats.</p>
  </div>
  <div class="rec-topbar__actions">
    <a href="{{ route('recruteur.offres') }}" class="rec-btn rec-btn--outline">
      <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"/></svg>
      Retour aux offres
    </a>
  </div>
</div>

<div class="rec-card">
  <div class="rec-card__body">
    <form method="POST" action="{{ route('recruteur.offres.store') }}" enctype="multipart/form-data">
      @csrf

      <div class="rec-form-grid">
        <div class="rec-form-group">
          <label>Titre du poste <span style="color:#e53e3e">*</span></label>
          <input type="text" name="titre" value="{{ old('titre') }}" placeholder="Ex : Développeur Web Full Stack" required>
          @error('titre')<small style="color:#e53e3e">{{ $message }}</small>@enderror
        </div>
        <div class="rec-form-group">
          <label>Entreprise <span style="color:#e53e3e">*</span></label>
          <input type="text" name="entreprise" value="{{ old('entreprise', auth()->user()->entreprise) }}" placeholder="Nom de l'entreprise" required>
          @error('entreprise')<small style="color:#e53e3e">{{ $message }}</small>@enderror
        </div>

        <div class="rec-form-group">
          <label>Localisation <span style="color:#e53e3e">*</span></label>
          <input type="text" name="localisation" value="{{ old('localisation') }}" placeholder="Cotonou, Bénin" required>
        </div>
        <div class="rec-form-group">
          <label>Type de contrat <span style="color:#e53e3e">*</span></label>
          <select name="type" required>
            <option value="">Sélectionner</option>
            @foreach($typeContrats as $tc)
              <option value="{{ $tc->id }}" {{ old('type') == $tc->id ? 'selected' : '' }}>{{ $tc->libelle }}</option>
            @endforeach
          </select>
        </div>

        <div class="rec-form-group">
          <label>Secteur d'activité</label>
          <x-secteur-select name="secteur" :selected="collect(old('secteur', []))" />
        </div>
        <div class="rec-form-group">
          <label>Rémunération (FCFA / mois)</label>
          <div style="display:flex;gap:8px;align-items:center">
            <input type="number" name="salaire_min" value="{{ old('salaire_min') }}" placeholder="Min" min="0" step="1000" style="flex:1">
            <span style="color:#94a3b8;font-size:13px">–</span>
            <input type="number" name="salaire_max" value="{{ old('salaire_max') }}" placeholder="Max" min="0" step="1000" style="flex:1">
          </div>
        </div>

        <div class="rec-form-group">
          <label>Métier / Fonction</label>
          <select name="metier_id" id="metier-select-create" style="width:100%">
            <option value="">Sélectionner un métier…</option>
            @foreach($metiers as $m)
              <option value="{{ $m->id }}" {{ old('metier_id') == $m->id ? 'selected' : '' }}>{{ $m->nom }}</option>
            @endforeach
          </select>
          @error('metier_id')<small style="color:#e53e3e">{{ $message }}</small>@enderror
        </div>
        <div class="rec-form-group">
          <label>Date limite de candidature</label>
          <input type="date" name="date_limite" value="{{ old('date_limite') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
        </div>

        <div class="rec-form-group">
          <label>Niveau d'expérience</label>
          <select name="niveau_experience">
            <option value="">Non précisé</option>
            @foreach($niveauxExp as $n)
              <option value="{{ $n->code }}" {{ old('niveau_experience') === $n->code ? 'selected' : '' }}>{{ $n->libelle }}</option>
            @endforeach
          </select>
          @error('niveau_experience')<small style="color:#e53e3e">{{ $message }}</small>@enderror
        </div>
        <div class="rec-form-group">
          <label>Niveau d'études requis</label>
          <select name="niveau_etude">
            <option value="">Non précisé</option>
            @foreach($niveauxEtude as $n)
              <option value="{{ $n->code }}" {{ old('niveau_etude') === $n->code ? 'selected' : '' }}>{{ $n->libelle }}</option>
            @endforeach
          </select>
          @error('niveau_etude')<small style="color:#e53e3e">{{ $message }}</small>@enderror
        </div>

        <div class="rec-form-group full">
          <label>Description du poste <span style="color:#e53e3e">*</span> <small style="color:#94a3b8;font-weight:400">(min. 50 caractères)</small></label>
          <x-summernote name="description" :value="old('description', '')" :height="300" placeholder="Décrivez le poste, les missions, le contexte, l'entreprise…" />
          @error('description')<small style="color:#e53e3e">{{ $message }}</small>@enderror
        </div>

        <div class="rec-form-group full">
          <label>Compétences requises</label>
          <x-competences-select name="competences" :selected="collect(old('competences', []))" />
        </div>

        <div class="rec-form-group full">
          <label>Exigences & conditions</label>
          <textarea name="exigences" rows="3" placeholder="Diplôme requis, expérience minimale, conditions particulières…">{{ old('exigences') }}</textarea>
        </div>

        <div class="rec-form-group">
          <label>Logo de l'entreprise <small style="color:#94a3b8;font-weight:400">(optionnel, JPG, PNG, WebP · max 2 Mo)</small></label>
          <input type="file" name="logo" accept=".jpg,.jpeg,.png,.webp,.svg" id="logo-input-create"
                 style="width:100%;padding:8px 12px;border:1.5px solid #d1d5db;border-radius:8px;font-size:13px;background:#fff;cursor:pointer"
                 onchange="previewLogo(this,'logo-preview-create')">
          <div id="logo-preview-create" style="display:none;margin-top:10px">
            <img src="" alt="Aperçu logo" style="height:60px;width:60px;object-fit:contain;border:1.5px solid #e2e8f0;border-radius:10px;padding:4px;background:#fff">
          </div>
          <small style="color:#94a3b8">Affiché sur votre annonce publique.</small>
          @error('logo')<small style="color:#e53e3e">{{ $message }}</small>@enderror
        </div>

        <div class="rec-form-group">
          <label>Document joint <small style="color:#94a3b8;font-weight:400">(optionnel, PDF, DOC, DOCX · max 5 Mo)</small></label>
          <input type="file" name="fichier" accept=".pdf,.doc,.docx" style="width:100%;padding:8px 12px;border:1.5px solid #d1d5db;border-radius:8px;font-size:13px;background:#fff;cursor:pointer">
          <small style="color:#94a3b8">Utile pour les annonces officielles, appels d'offres ou documents multi-pages.</small>
          @error('fichier')<small style="color:#e53e3e">{{ $message }}</small>@enderror
        </div>
      </div>

      <div style="display:flex;gap:12px;margin-top:24px;padding-top:20px;border-top:1px solid #e2e8f0">
        <button type="submit" class="rec-btn rec-btn--yellow" style="flex:1;justify-content:center;padding:13px">
          <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 2L11 13"/><path d="M22 2L15 22 11 13 2 9l20-7z"/></svg>
          Publier l'offre
        </button>
        <button type="button" onclick="openOffrePreview()" class="rec-btn rec-btn--outline" style="padding:13px 22px">
          <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px;margin-right:5px"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
          Prévisualiser
        </button>
        <a href="{{ route('recruteur.offres') }}" class="rec-btn rec-btn--outline" style="padding:13px 22px">Annuler</a>
      </div>
    </form>
  </div>
</div>
@endsection

{{-- Modale de prévisualisation --}}
<div id="offre-preview-modal" style="display:none;position:fixed;inset:0;z-index:9999;background:rgba(4,44,83,.55);overflow-y:auto;padding:32px 16px" onclick="if(event.target===this)closeOffrePreview()">
  <div style="max-width:860px;margin:0 auto;background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 20px 60px rgba(4,44,83,.25)">
    {{-- Header modale --}}
    <div style="display:flex;align-items:center;justify-content:space-between;padding:16px 24px;background:#042C53;color:#fff">
      <div style="display:flex;align-items:center;gap:10px">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
        <span style="font-weight:700;font-size:15px">Prévisualisation de l'offre</span>
      </div>
      <button onclick="closeOffrePreview()" style="background:rgba(255,255,255,.15);border:none;color:#fff;width:32px;height:32px;border-radius:8px;cursor:pointer;font-size:18px;display:flex;align-items:center;justify-content:center">&times;</button>
    </div>
    {{-- Contenu preview --}}
    <div id="offre-preview-body" style="padding:0"></div>
  </div>
</div>

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
    $('#metier-select-create').select2({
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

  const titre       = val('titre') || '(Titre non renseigné)';
  const entreprise  = val('entreprise') || '–';
  const localisation= val('localisation');
  const type        = selText('type');
  const metier      = selText('metier_id');
  const salaireMin  = val('salaire_min');
  const salaireMax  = val('salaire_max');
  const dateLimite  = val('date_limite');
  const niveauExp   = selText('niveau_experience');
  const niveauEtude = selText('niveau_etude');
  const exigences   = val('exigences');

  // Description depuis Summernote
  let description = '';
  if (window.$ && typeof $('#sn-description').summernote === 'function') {
    description = $('#sn-description').summernote('code') || '';
  }

  // Salaire formaté
  const fmt = (n) => parseInt(n).toLocaleString('fr-FR');
  let salaire = '';
  if (salaireMin && salaireMax) salaire = fmt(salaireMin) + ' – ' + fmt(salaireMax) + ' FCFA';
  else if (salaireMin) salaire = 'À partir de ' + fmt(salaireMin) + ' FCFA';
  else if (salaireMax) salaire = 'Jusqu\'à ' + fmt(salaireMax) + ' FCFA';

  // Logo
  const logoImg = document.querySelector('#logo-preview-create img');
  const logoHtml = logoImg?.src && logoImg.src !== window.location.href
    ? '<img src="' + logoImg.src + '" style="width:100%;height:100%;object-fit:contain;padding:6px">'
    : '<span style="font-weight:800;font-size:1.2rem;color:#185FA5">' + (entreprise.substring(0,2).toUpperCase()) + '</span>';

  // Badges hero
  let badges = '';
  if (type)        badges += '<span style="display:inline-flex;align-items:center;padding:4px 12px;background:rgba(255,255,255,.2);border:1px solid rgba(255,255,255,.35);border-radius:20px;font-size:12.5px;font-weight:600;color:#fff">' + type + '</span> ';
  if (localisation) badges += '<span style="display:inline-flex;align-items:center;gap:4px;padding:4px 12px;background:rgba(255,255,255,.15);border:1px solid rgba(255,255,255,.25);border-radius:20px;font-size:12.5px;color:rgba(255,255,255,.9)">📍 ' + localisation + '</span>';

  // Sidebar rows
  const row = (icon, label, value) => value
    ? '<div style="display:flex;align-items:flex-start;gap:12px;padding:10px 0;border-bottom:1px solid #f1f5f9"><div style="width:32px;height:32px;border-radius:8px;background:#f0f7ff;display:flex;align-items:center;justify-content:center;flex-shrink:0;color:#185FA5">' + icon + '</div><div><p style="font-size:11px;color:#94a3b8;font-weight:600;text-transform:uppercase;letter-spacing:.06em;margin:0 0 2px">' + label + '</p><p style="font-size:13.5px;color:#042C53;font-weight:600;margin:0">' + value + '</p></div></div>'
    : '';

  const iconType  = '<svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v16"/></svg>';
  const iconLoc   = '<svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>';
  const iconSal   = '<svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>';
  const iconExp   = '<svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>';
  const iconDate  = '<svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>';

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
    <div style="display:grid;grid-template-columns:1fr 280px;gap:0">
      <div style="padding:24px;border-right:1px solid #f1f5f9">
        ${description ? `<div style="margin-bottom:24px"><h3 style="font-size:13px;font-weight:700;color:#042C53;text-transform:uppercase;letter-spacing:.08em;margin:0 0 12px;padding-bottom:8px;border-bottom:2px solid #e8f0fe">Description du poste</h3><div style="font-size:14px;color:#374151;line-height:1.75">${description}</div></div>` : ''}
        ${exigences ? `<div style="margin-bottom:24px"><h3 style="font-size:13px;font-weight:700;color:#042C53;text-transform:uppercase;letter-spacing:.08em;margin:0 0 12px;padding-bottom:8px;border-bottom:2px solid #e8f0fe">Exigences du poste</h3><div style="font-size:14px;color:#374151;line-height:1.75;white-space:pre-line">${exigences.replace(/</g,'&lt;')}</div></div>` : ''}
        ${!description && !exigences ? '<p style="color:#94a3b8;font-style:italic;font-size:13px">Renseignez la description et les exigences pour voir le contenu ici.</p>' : ''}
      </div>
      <div style="padding:20px">
        <div style="background:#f8fafc;border-radius:12px;padding:16px;margin-bottom:16px">
          <p style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.08em;margin:0 0 10px">Détails de l'offre</p>
          ${row(iconType,  'Type de contrat', type)}
          ${row(iconLoc,   'Localisation',    localisation)}
          ${row(iconSal,   'Rémunération',    salaire)}
          ${row(iconExp,   'Expérience',      niveauExp)}
          ${row(iconExp,   'Niveau d\'études', niveauEtude)}
          ${row(iconDate,  'Date limite',      dateFormatee)}
        </div>
        <div style="background:#fef9ec;border:1.5px solid #fcd34d;border-radius:10px;padding:12px 14px">
          <p style="font-size:12px;color:#92400e;font-weight:600;margin:0 0 4px">Aperçu uniquement</p>
          <p style="font-size:11.5px;color:#92400e;margin:0">Ceci est une prévisualisation. Cliquez sur « Publier l'offre » pour la rendre visible.</p>
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
