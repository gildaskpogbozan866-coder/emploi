@extends('layouts.recruteur')
@section('title', 'Modifier l\'offre')

@section('css')
@include('partials._jquery-cdn')
@once
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/fr.js"></script>
@endonce
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

<div class="rec-card" style="max-width:680px">
  <div class="rec-card__body" style="padding:28px">
    <form method="POST" action="{{ route('recruteur.offres.update', $offre) }}" enctype="multipart/form-data">
      @csrf @method('PUT')

      <div style="margin-bottom:18px">
        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Titre du poste <span style="color:#e53e3e">*</span></label>
        <input type="text" name="titre" value="{{ old('titre', $offre->titre) }}" required
               style="width:100%;padding:10px 14px;border:1.5px solid {{ $errors->has('titre') ? '#e53e3e' : '#d1d5db' }};border-radius:8px;font-size:14px;box-sizing:border-box">
        @error('titre') <p style="color:#e53e3e;font-size:12px;margin:3px 0 0">{{ $message }}</p> @enderror
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:18px">
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

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:18px">
        <div>
          <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Type de contrat <span style="color:#e53e3e">*</span></label>
          <select name="type" required style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box">
            <option value="">Sélectionner</option>
            @foreach($typeContrats as $tc)
              <option value="{{ $tc->code }}" {{ old('type', $offre->type) === $tc->code ? 'selected' : '' }}>{{ $tc->libelle }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Secteur d'activité</label>
          <x-secteur-select name="secteur" :selected="collect(old('secteur', $offre->secteur ?? []))" />
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:18px">
        <div>
          <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Rémunération (optionnel)</label>
          <input type="text" name="salaire" value="{{ old('salaire', $offre->salaire) }}" placeholder="Ex: 150 000 - 200 000 FCFA"
                 style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box">
        </div>
        <div>
          <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Date limite de candidature</label>
          <input type="date" name="date_limite" value="{{ old('date_limite', $offre->date_limite?->format('Y-m-d')) }}"
                 style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box">
        </div>
      </div>

      <div style="margin-bottom:18px">
        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Métier / Fonction</label>
        <select name="metier" id="metier-select-edit" style="width:100%">
          <option value="">Sélectionner un métier…</option>
          @foreach($metiers as $m)
            <option value="{{ $m->nom }}" {{ old('metier', $offre->metier) === $m->nom ? 'selected' : '' }}>{{ $m->nom }}</option>
          @endforeach
        </select>
        @error('metier')<p style="color:#e53e3e;font-size:12px;margin:3px 0 0">{{ $message }}</p>@enderror
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:18px">
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

      <div style="display:flex;gap:12px">
        <button type="submit" class="rec-btn rec-btn--yellow">Enregistrer les modifications</button>
        <a href="{{ route('recruteur.offres') }}" class="rec-btn rec-btn--outline">Annuler</a>
      </div>
    </form>
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
</script>
@endsection
