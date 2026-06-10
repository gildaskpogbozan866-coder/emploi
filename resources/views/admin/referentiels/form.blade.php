@extends('layouts.admin')
@section('title', ($item ? 'Modifier' : 'Ajouter') . ' — ' . $label)

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <a href="{{ route($routeIndex) }}" class="adm-btn adm-btn--outline adm-btn--sm" style="margin-bottom:8px">← Retour</a>
    <h1>{{ $item ? 'Modifier' : 'Ajouter' }} — {{ $singular }}</h1>
  </div>
</div>

<div class="adm-card" style="max-width:600px">
  <div style="padding:24px">
    <form method="POST"
          action="{{ $item ? route($routeUpdate, $item) : route($routeStore) }}">
      @csrf
      @if($item) @method('PUT') @endif

      @if($hasCode)
        <div style="margin-bottom:18px">
          <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">
            Code <span style="color:#e53e3e">*</span>
          </label>
          <input type="text" name="code" value="{{ old('code', $item?->code) }}" required maxlength="50"
                 placeholder="Ex: CDI, SECTEUR_TECH…"
                 style="width:100%;padding:10px 14px;border:1.5px solid {{ $errors->has('code') ? '#e53e3e' : '#d1d5db' }};border-radius:8px;font-size:14px;box-sizing:border-box">
          @error('code')<p style="color:#e53e3e;font-size:12px;margin:3px 0 0">{{ $message }}</p>@enderror
        </div>
        <div style="margin-bottom:18px">
          <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">
            Libellé <span style="color:#e53e3e">*</span>
          </label>
          <input type="text" name="libelle" value="{{ old('libelle', $item?->libelle) }}" required maxlength="200"
                 placeholder="Ex: Contrat à durée indéterminée"
                 style="width:100%;padding:10px 14px;border:1.5px solid {{ $errors->has('libelle') ? '#e53e3e' : '#d1d5db' }};border-radius:8px;font-size:14px;box-sizing:border-box">
          @error('libelle')<p style="color:#e53e3e;font-size:12px;margin:3px 0 0">{{ $message }}</p>@enderror
        </div>
      @elseif($hasNom)
        <div style="margin-bottom:18px">
          <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">
            Nom <span style="color:#e53e3e">*</span>
          </label>
          <input type="text" name="nom" value="{{ old('nom', $item?->nom) }}" required maxlength="200"
                 placeholder="Ex: Développement web, Français…"
                 style="width:100%;padding:10px 14px;border:1.5px solid {{ $errors->has('nom') ? '#e53e3e' : '#d1d5db' }};border-radius:8px;font-size:14px;box-sizing:border-box">
          @error('nom')<p style="color:#e53e3e;font-size:12px;margin:3px 0 0">{{ $message }}</p>@enderror
        </div>
      @endif

      @if($hasDesc)
        <div style="margin-bottom:18px">
          <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">
            Description <span style="color:#94a3b8;font-weight:400">(optionnel)</span>
          </label>
          <textarea name="description" rows="3" maxlength="500"
                    placeholder="Courte description du métier…"
                    style="width:100%;padding:10px 14px;border:1.5px solid {{ $errors->has('description') ? '#e53e3e' : '#d1d5db' }};border-radius:8px;font-size:14px;resize:vertical;box-sizing:border-box">{{ old('description', $item?->description) }}</textarea>
          @error('description')<p style="color:#e53e3e;font-size:12px;margin:3px 0 0">{{ $message }}</p>@enderror
        </div>
      @endif

      @if($hasOrdre)
        <div style="margin-bottom:18px">
          <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">
            Ordre d'affichage <span style="color:#e53e3e">*</span>
          </label>
          <input type="number" name="ordre" value="{{ old('ordre', $item?->ordre) }}" required min="1"
                 style="width:120px;padding:10px 14px;border:1.5px solid {{ $errors->has('ordre') ? '#e53e3e' : '#d1d5db' }};border-radius:8px;font-size:14px;box-sizing:border-box">
          @error('ordre')<p style="color:#e53e3e;font-size:12px;margin:3px 0 0">{{ $message }}</p>@enderror
        </div>
      @endif

      <div style="display:flex;gap:12px;margin-top:8px">
        <button type="submit" class="adm-btn adm-btn--primary">
          {{ $item ? 'Mettre à jour' : 'Ajouter' }}
        </button>
        <a href="{{ route($routeIndex) }}" class="adm-btn adm-btn--outline">Annuler</a>
      </div>
    </form>
  </div>
</div>
@endsection
