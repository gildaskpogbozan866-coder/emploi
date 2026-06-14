@extends('layouts.admin')
@section('title', 'Créer un service — Administration')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <a href="{{ route('admin.services.list') }}" class="adm-btn adm-btn--outline adm-btn--sm" style="margin-bottom:8px">← Retour</a>
    <h1>Créer un service</h1>
    <p>Ajoutez un nouveau service d'accompagnement</p>
  </div>
</div>

<div class="adm-card" style="max-width:680px">
  <div style="padding:24px">
    <form method="POST" action="{{ route('admin.services.store') }}">
      @csrf

      <div style="margin-bottom:18px">
        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Nom du service <span style="color:#e53e3e">*</span></label>
        <input type="text" name="nom" value="{{ old('nom') }}" required
               placeholder="Ex: CV Professionnel + Lettre de motivation"
               style="width:100%;padding:10px 14px;border:1.5px solid {{ $errors->has('nom') ? '#e53e3e' : '#d1d5db' }};border-radius:8px;font-size:14px;box-sizing:border-box">
        @error('nom') <p style="color:#e53e3e;font-size:12px;margin:3px 0 0">{{ $message }}</p> @enderror
      </div>

      <div style="margin-bottom:18px">
        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Description <span style="color:#e53e3e">*</span></label>
        <textarea name="description" rows="4" required
                  placeholder="Description courte affichée sur la liste des services…"
                  style="width:100%;padding:10px 14px;border:1.5px solid {{ $errors->has('description') ? '#e53e3e' : '#d1d5db' }};border-radius:8px;font-size:14px;resize:vertical;box-sizing:border-box">{{ old('description') }}</textarea>
        @error('description') <p style="color:#e53e3e;font-size:12px;margin:3px 0 0">{{ $message }}</p> @enderror
      </div>

      <div style="margin-bottom:18px">
        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Détails (optionnel)</label>
        <textarea name="details" rows="5"
                  placeholder="Description longue avec tous les avantages, ce qui est inclus, etc."
                  style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;resize:vertical;box-sizing:border-box">{{ old('details') }}</textarea>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:18px">
        <div>
          <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Prix (FCFA) <span style="color:#e53e3e">*</span></label>
          <input type="number" name="prix" value="{{ old('prix', 0) }}" min="0" required
                 style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box">
          @error('prix') <p style="color:#e53e3e;font-size:12px;margin:3px 0 0">{{ $message }}</p> @enderror
        </div>
        <div>
          <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Délai de livraison</label>
          <input type="text" name="delai" value="{{ old('delai') }}" placeholder="Ex: 48h, 3 jours, Sur RDV"
                 style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box">
        </div>
        <div>
          <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Type / Catégorie</label>
          <input type="text" name="type" value="{{ old('type') }}" placeholder="Ex: cv, coaching, linkedin"
                 style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box">
        </div>
      </div>

      <div style="margin-bottom:24px;display:flex;align-items:center;gap:10px">
        <input type="checkbox" name="actif" id="actif" value="1" {{ old('actif', '1') ? 'checked' : '' }}
               style="width:16px;height:16px">
        <label for="actif" style="font-size:13.5px;font-weight:600;color:#374151;cursor:pointer">Service actif (visible sur le site)</label>
      </div>

      <div style="display:flex;gap:12px">
        <button type="submit" class="adm-btn adm-btn--yellow">Créer le service</button>
        <a href="{{ route('admin.services.list') }}" class="adm-btn adm-btn--outline">Annuler</a>
      </div>
    </form>
  </div>
</div>
@endsection
