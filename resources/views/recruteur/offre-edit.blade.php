@extends('layouts.recruteur')
@section('title', 'Modifier l\'offre')

@section('sidebar')
@include('recruteur._sidebar')
@endsection

@section('content')
<div class="rec-topbar">
  <div class="rec-topbar__left">
    <a href="{{ route('recruteur.offres') }}" class="rec-btn rec-btn--outline rec-btn--sm" style="margin-bottom:8px">← Retour</a>
    <h1>Modifier l'offre</h1>
    <p>{{ $offre->titre }}</p>
  </div>
</div>

<div class="rec-card" style="max-width:680px">
  <div class="rec-card__body" style="padding:28px">
    <form method="POST" action="{{ route('recruteur.offres.update', $offre) }}">
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
            @foreach(['CDI','CDD','Stage','Bourse','Freelance','Temps partiel'] as $type)
              <option value="{{ $type }}" {{ old('type', $offre->type) === $type ? 'selected' : '' }}>{{ $type }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Secteur d'activité</label>
          <input type="text" name="secteur" value="{{ old('secteur', $offre->secteur) }}"
                 style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box">
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
        <textarea name="description" rows="8" required
                  style="width:100%;padding:10px 14px;border:1.5px solid {{ $errors->has('description') ? '#e53e3e' : '#d1d5db' }};border-radius:8px;font-size:14px;resize:vertical;box-sizing:border-box">{{ old('description', $offre->description) }}</textarea>
        @error('description') <p style="color:#e53e3e;font-size:12px;margin:3px 0 0">{{ $message }}</p> @enderror
      </div>

      <div style="margin-bottom:24px">
        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Compétences requises</label>
        <textarea name="competences" rows="3" placeholder="Listez les compétences attendues…"
                  style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;resize:vertical;box-sizing:border-box">{{ old('competences', $offre->competences) }}</textarea>
      </div>

      <div style="display:flex;gap:12px">
        <button type="submit" class="rec-btn rec-btn--primary">Enregistrer les modifications</button>
        <a href="{{ route('recruteur.offres') }}" class="rec-btn rec-btn--outline">Annuler</a>
      </div>
    </form>
  </div>
</div>
@endsection
