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
              <option value="{{ $tc->code }}" {{ old('type') === $tc->code ? 'selected' : '' }}>{{ $tc->libelle }}</option>
            @endforeach
          </select>
        </div>

        <div class="rec-form-group">
          <label>Secteur d'activité</label>
          <x-secteur-select name="secteur" :selected="collect(old('secteur', []))" />
        </div>
        <div class="rec-form-group">
          <label>Rémunération</label>
          <input type="text" name="salaire" value="{{ old('salaire') }}" placeholder="Ex : 150 000 - 250 000 FCFA">
        </div>

        <div class="rec-form-group">
          <label>Métier / Fonction</label>
          <select name="metier" id="metier-select-create" style="width:100%">
            <option value="">Sélectionner un métier…</option>
            @foreach($metiers as $m)
              <option value="{{ $m->nom }}" {{ old('metier') === $m->nom ? 'selected' : '' }}>{{ $m->nom }}</option>
            @endforeach
          </select>
          @error('metier')<small style="color:#e53e3e">{{ $message }}</small>@enderror
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

        <div class="rec-form-group full">
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
        <a href="{{ route('recruteur.offres') }}" class="rec-btn rec-btn--outline" style="padding:13px 22px">Annuler</a>
      </div>
    </form>
  </div>
</div>
@endsection

@section('scripts')
<script>
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
</script>
@endsection
