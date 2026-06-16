@extends('layouts.admin')
@section('title', ($pack->exists ? 'Modifier' : 'Nouveau') . ' pack crédits CVthèque')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <h1>{{ $pack->exists ? 'Modifier le pack' : 'Nouveau pack crédits CVthèque' }}</h1>
    <p><a href="{{ route('admin.credit-cv-packs.index') }}" style="color:#185FA5;text-decoration:none">← Retour à la liste</a></p>
  </div>
</div>

@if($errors->any())
  <div class="adm-alert adm-alert--danger" style="margin-bottom:16px">
    <ul style="margin:0;padding-left:16px">
      @foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach
    </ul>
  </div>
@endif

<div class="adm-card" style="max-width:560px">
  <form method="POST"
        action="{{ $pack->exists ? route('admin.credit-cv-packs.update', $pack) : route('admin.credit-cv-packs.store') }}">
    @csrf
    @if($pack->exists) @method('PUT') @endif

    <div style="display:grid;gap:18px">

      {{-- Label --}}
      <div>
        <label style="display:block;font-size:12px;font-weight:700;color:#374151;text-transform:uppercase;letter-spacing:.05em;margin-bottom:5px">Label *</label>
        <input type="text" name="label" value="{{ old('label', $pack->label) }}"
               placeholder="ex. 10 crédits"
               style="width:100%;padding:9px 12px;border:1px solid #d1d5db;border-radius:7px;font-size:13.5px;font-family:inherit;outline:none"
               required>
      </div>

      {{-- Crédits + Prix --}}
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
        <div>
          <label style="display:block;font-size:12px;font-weight:700;color:#374151;text-transform:uppercase;letter-spacing:.05em;margin-bottom:5px">Nombre de crédits *</label>
          <input type="number" name="credits" value="{{ old('credits', $pack->credits) }}"
                 min="1" max="9999"
                 style="width:100%;padding:9px 12px;border:1px solid #d1d5db;border-radius:7px;font-size:13.5px;font-family:inherit;outline:none"
                 required>
        </div>
        <div>
          <label style="display:block;font-size:12px;font-weight:700;color:#374151;text-transform:uppercase;letter-spacing:.05em;margin-bottom:5px">Prix (FCFA) *</label>
          <input type="number" name="prix" value="{{ old('prix', $pack->prix) }}"
                 min="0"
                 style="width:100%;padding:9px 12px;border:1px solid #d1d5db;border-radius:7px;font-size:13.5px;font-family:inherit;outline:none"
                 required>
        </div>
      </div>

      {{-- Badge + Ordre --}}
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px">
        <div>
          <label style="display:block;font-size:12px;font-weight:700;color:#374151;text-transform:uppercase;letter-spacing:.05em;margin-bottom:5px">Badge <span style="font-weight:400;text-transform:none">(optionnel)</span></label>
          <input type="text" name="badge" value="{{ old('badge', $pack->badge) }}"
                 placeholder="ex. Populaire"
                 style="width:100%;padding:9px 12px;border:1px solid #d1d5db;border-radius:7px;font-size:13.5px;font-family:inherit;outline:none">
        </div>
        <div>
          <label style="display:block;font-size:12px;font-weight:700;color:#374151;text-transform:uppercase;letter-spacing:.05em;margin-bottom:5px">Ordre d'affichage *</label>
          <input type="number" name="ordre" value="{{ old('ordre', $pack->ordre ?? 0) }}"
                 min="0" max="255"
                 style="width:100%;padding:9px 12px;border:1px solid #d1d5db;border-radius:7px;font-size:13.5px;font-family:inherit;outline:none"
                 required>
        </div>
      </div>

      {{-- Checkboxes --}}
      <div style="display:flex;gap:24px;flex-wrap:wrap">
        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13.5px;font-weight:600;color:#374151">
          <input type="hidden" name="featured" value="0">
          <input type="checkbox" name="featured" value="1" {{ old('featured', $pack->featured) ? 'checked' : '' }}
                 style="width:16px;height:16px;accent-color:#042C53;cursor:pointer">
          Pack vedette <span style="font-size:12px;font-weight:400;color:#94a3b8">(mis en avant visuellement)</span>
        </label>
        <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13.5px;font-weight:600;color:#374151">
          <input type="hidden" name="actif" value="0">
          <input type="checkbox" name="actif" value="1" {{ old('actif', $pack->actif ?? true) ? 'checked' : '' }}
                 style="width:16px;height:16px;accent-color:#042C53;cursor:pointer">
          Actif <span style="font-size:12px;font-weight:400;color:#94a3b8">(visible pour les recruteurs)</span>
        </label>
      </div>

      {{-- Actions --}}
      <div style="display:flex;gap:10px;padding-top:6px">
        <button type="submit" class="adm-btn adm-btn--yellow">
          {{ $pack->exists ? 'Enregistrer les modifications' : 'Créer le pack' }}
        </button>
        <a href="{{ route('admin.credit-cv-packs.index') }}" class="adm-btn adm-btn--outline">Annuler</a>
      </div>

    </div>
  </form>
</div>
@endsection
