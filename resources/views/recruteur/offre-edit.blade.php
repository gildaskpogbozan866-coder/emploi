@extends('layouts.recruteur')
@section('title', 'Modifier l\'offre')

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
            <option value="">— Choisir —</option>
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
