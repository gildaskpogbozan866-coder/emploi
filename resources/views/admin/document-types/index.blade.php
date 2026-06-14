@extends('layouts.admin')
@section('title', 'Documents recruteur — Configuration')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <h1>Documents requis — Recruteurs</h1>
    <p>Définissez les justificatifs demandés lors de l'inscription d'un recruteur</p>
  </div>
  <div class="adm-topbar__right">
    <a href="{{ route('admin.verifications.list') }}" class="adm-btn adm-btn--outline">
      Voir les dossiers en attente
    </a>
  </div>
</div>

@if(session('success'))
  <div style="background:#dcfce7;border:1.5px solid #bbf7d0;border-radius:10px;padding:12px 18px;margin-bottom:20px;font-size:13.5px;color:#15803d;display:flex;align-items:center;gap:10px">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
    {{ session('success') }}
  </div>
@endif

@if(session('error'))
  <div style="background:#fee2e2;border:1.5px solid #fecaca;border-radius:10px;padding:12px 18px;margin-bottom:20px;font-size:13.5px;color:#dc2626;display:flex;align-items:center;gap:10px">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
    {{ session('error') }}
  </div>
@endif

@if($errors->any())
  <div style="background:#fee2e2;border:1.5px solid #fecaca;border-radius:10px;padding:12px 18px;margin-bottom:20px;font-size:13.5px;color:#dc2626">
    {{ $errors->first() }}
  </div>
@endif

{{-- Toggle validation --}}
<div class="adm-card" style="margin-bottom:28px;padding:20px 24px;display:flex;align-items:center;justify-content:space-between;gap:20px">
  <div>
    <p style="font-size:15px;font-weight:700;color:#042C53;margin:0 0 4px">Validation des documents requise</p>
    <p style="font-size:13px;color:#64748b;margin:0">
      @if($validationActive)
        <span style="color:#d97706;font-weight:600">Activée</span> — Les recruteurs doivent soumettre un dossier avant d'accéder au dashboard.
      @else
        <span style="color:#94a3b8;font-weight:600">Désactivée</span> — Les recruteurs accèdent directement après confirmation de leur email.
      @endif
    </p>
  </div>
  <form method="POST" action="{{ route('admin.document-types.toggle') }}" style="flex-shrink:0">
    @csrf
    <input type="hidden" name="validation_requise" value="{{ $validationActive ? '0' : '1' }}">
    <button type="submit" class="adm-btn {{ $validationActive ? 'adm-btn--danger' : 'adm-btn--green' }}">
      {{ $validationActive ? 'Désactiver' : 'Activer' }}
    </button>
  </form>
</div>

<div style="display:grid;grid-template-columns:1fr 380px;gap:24px;align-items:start">

  {{-- Liste des types --}}
  <div>
    <h2 style="font-size:14px;font-weight:700;color:#042C53;margin:0 0 14px;text-transform:uppercase;letter-spacing:.05em">Types de documents ({{ $types->count() }})</h2>

    @forelse($types as $type)
    <div class="adm-card" style="margin-bottom:12px;padding:0;overflow:hidden">
      <details>
        <summary style="padding:16px 20px;cursor:pointer;display:flex;align-items:center;gap:14px;list-style:none">
          <div style="flex:1;display:flex;align-items:center;gap:12px">
            <span style="font-size:12px;font-weight:700;color:#94a3b8;width:24px;text-align:center">{{ $type->ordre ?: '—' }}</span>
            <div>
              <p style="font-size:14px;font-weight:700;color:#042C53;margin:0">{{ $type->nom }}</p>
              @if($type->description)
                <p style="font-size:12px;color:#94a3b8;margin:2px 0 0">{{ Str::limit($type->description, 80) }}</p>
              @endif
            </div>
          </div>
          <div style="display:flex;align-items:center;gap:8px;flex-shrink:0">
            @if($type->accepte_fichier)
              <span style="font-size:11px;padding:2px 8px;border-radius:20px;background:#eff6ff;color:#3b82f6;font-weight:600">Fichier</span>
            @endif
            @if($type->accepte_texte)
              <span style="font-size:11px;padding:2px 8px;border-radius:20px;background:#f0fdf4;color:#16a34a;font-weight:600">Texte</span>
            @endif
            @if($type->est_requis)
              <span style="font-size:11px;padding:2px 8px;border-radius:20px;background:#fef9c3;color:#ca8a04;font-weight:600">Requis</span>
            @endif
            <span style="font-size:11px;padding:2px 8px;border-radius:20px;font-weight:600;background:{{ $type->est_actif ? '#dcfce7' : '#f1f5f9' }};color:{{ $type->est_actif ? '#16a34a' : '#94a3b8' }}">
              {{ $type->est_actif ? 'Actif' : 'Inactif' }}
            </span>
          </div>
        </summary>

        {{-- Formulaire d'édition --}}
        <div style="border-top:1px solid #f1f5f9;padding:20px">
          <form method="POST" action="{{ route('admin.document-types.update', $type) }}">
            @csrf @method('PUT')
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px">
              <div style="grid-column:1/-1">
                <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:5px">Nom du document</label>
                <input type="text" name="nom" value="{{ $type->nom }}" required
                       style="width:100%;padding:9px 12px;border:1.5px solid #d1d5db;border-radius:8px;font-size:13px;box-sizing:border-box">
              </div>
              <div style="grid-column:1/-1">
                <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:5px">Instructions (optionnel)</label>
                <textarea name="description" rows="2"
                          style="width:100%;padding:9px 12px;border:1.5px solid #d1d5db;border-radius:8px;font-size:13px;resize:vertical;box-sizing:border-box">{{ $type->description }}</textarea>
              </div>
              <div>
                <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:8px">Ordre d'affichage</label>
                <input type="number" name="ordre" value="{{ $type->ordre }}" min="0" max="999"
                       style="width:100%;padding:9px 12px;border:1.5px solid #d1d5db;border-radius:8px;font-size:13px;box-sizing:border-box">
              </div>
              <div style="display:flex;flex-direction:column;gap:8px;padding-top:20px">
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13px;color:#374151">
                  <input type="checkbox" name="accepte_fichier" value="1" {{ $type->accepte_fichier ? 'checked' : '' }}>
                  Accepte un fichier (PDF/image)
                </label>
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13px;color:#374151">
                  <input type="checkbox" name="accepte_texte" value="1" {{ $type->accepte_texte ? 'checked' : '' }}>
                  Accepte une saisie texte/numéro
                </label>
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13px;color:#374151">
                  <input type="checkbox" name="est_requis" value="1" {{ $type->est_requis ? 'checked' : '' }}>
                  Obligatoire
                </label>
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13px;color:#374151">
                  <input type="checkbox" name="est_actif" value="1" {{ $type->est_actif ? 'checked' : '' }}>
                  Actif
                </label>
              </div>
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center">
              <form method="POST" action="{{ route('admin.document-types.destroy', $type) }}" onsubmit="return confirm('Supprimer ce type de document ?')">
                @csrf @method('DELETE')
                <button type="submit" class="adm-btn adm-btn--danger" style="font-size:12px;padding:6px 14px">Supprimer</button>
              </form>
              <button type="submit" class="adm-btn adm-btn--yellow" style="font-size:13px">Enregistrer</button>
            </div>
          </form>
        </div>
      </details>
    </div>
    @empty
      <div class="adm-card" style="padding:40px;text-align:center;color:#94a3b8">
        <p style="font-size:14px;margin:0">Aucun type de document configuré.</p>
        <p style="font-size:13px;margin:8px 0 0">Ajoutez-en un depuis le formulaire ci-contre.</p>
      </div>
    @endforelse
  </div>

  {{-- Formulaire d'ajout --}}
  <div class="adm-card" style="padding:22px;position:sticky;top:20px">
    <h2 style="font-size:14px;font-weight:700;color:#042C53;margin:0 0 18px;text-transform:uppercase;letter-spacing:.05em">Ajouter un type</h2>
    <form method="POST" action="{{ route('admin.document-types.store') }}">
      @csrf
      <div style="display:flex;flex-direction:column;gap:14px">
        <div>
          <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:5px">Nom <span style="color:#dc2626">*</span></label>
          <input type="text" name="nom" value="{{ old('nom') }}" required placeholder="Ex : RCCM, IFU, Pièce d'identité…"
                 style="width:100%;padding:9px 12px;border:1.5px solid #d1d5db;border-radius:8px;font-size:13px;box-sizing:border-box">
        </div>
        <div>
          <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:5px">Instructions</label>
          <textarea name="description" rows="2" placeholder="Précisions affichées au recruteur…"
                    style="width:100%;padding:9px 12px;border:1.5px solid #d1d5db;border-radius:8px;font-size:13px;resize:vertical;box-sizing:border-box">{{ old('description') }}</textarea>
        </div>
        <div>
          <label style="display:block;font-size:12px;font-weight:600;color:#374151;margin-bottom:5px">Ordre</label>
          <input type="number" name="ordre" value="{{ old('ordre', 0) }}" min="0" max="999"
                 style="width:100%;padding:9px 12px;border:1.5px solid #d1d5db;border-radius:8px;font-size:13px;box-sizing:border-box">
        </div>
        <div style="display:flex;flex-direction:column;gap:8px">
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13px;color:#374151">
            <input type="checkbox" name="accepte_fichier" value="1" checked>
            Accepte un fichier (PDF/image)
          </label>
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13px;color:#374151">
            <input type="checkbox" name="accepte_texte" value="1">
            Accepte une saisie texte/numéro
          </label>
          <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13px;color:#374151">
            <input type="checkbox" name="est_requis" value="1" checked>
            Obligatoire
          </label>
        </div>
        <button type="submit" class="adm-btn adm-btn--yellow" style="width:100%">Ajouter le type</button>
      </div>
    </form>
  </div>

</div>
@endsection
