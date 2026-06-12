@extends('layouts.admin')
@section('title', ($plan->exists ? 'Modifier' : 'Créer') . ' un plan — Administration')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <a href="{{ route('admin.plans.list') }}" class="adm-btn adm-btn--outline adm-btn--sm" style="margin-bottom:8px">← Retour</a>
    <h1>{{ $plan->exists ? 'Modifier le plan' : 'Créer un plan d\'abonnement' }}</h1>
    <p>{{ $plan->exists ? 'Mise à jour de « ' . $plan->name . ' »' : 'Définissez les conditions et fonctionnalités du plan.' }}</p>
  </div>
</div>

<form method="POST"
      action="{{ $plan->exists ? route('admin.plans.update', $plan) : route('admin.plans.store') }}"
      style="max-width:780px">
  @csrf
  @if($plan->exists) @method('PUT') @endif

  {{-- ── SECTION 1 : Informations générales ─────────────── --}}
  <div class="adm-card" style="margin-bottom:20px">
    <div style="padding:20px 24px 4px;border-bottom:1px solid #f1f5f9">
      <h2 style="font-size:14px;font-weight:700;color:#042C53;margin:0 0 2px">Informations générales</h2>
    </div>
    <div style="padding:20px 24px">

      <div style="margin-bottom:16px">
        <label for="name" style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">
          Nom du plan <span style="color:#e53e3e">*</span>
        </label>
        <input id="name" type="text" name="name" value="{{ old('name', $plan->name) }}" required
               placeholder="Ex : Premium Candidat"
               style="width:100%;padding:10px 14px;border:1.5px solid {{ $errors->has('name') ? '#e53e3e' : '#d1d5db' }};border-radius:8px;font-size:14px;box-sizing:border-box">
        @error('name') <p style="color:#e53e3e;font-size:12px;margin:3px 0 0">{{ $message }}</p> @enderror
      </div>

      <div style="margin-bottom:16px">
        <label for="slug" style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">
          Slug (identifiant unique) <span style="color:#e53e3e">*</span>
          <span style="font-weight:400;color:#94a3b8"> — lettres minuscules, chiffres et tirets uniquement</span>
        </label>
        <input id="slug" type="text" name="slug" value="{{ old('slug', $plan->slug) }}" required
               placeholder="Ex : premium-candidat"
               style="width:100%;padding:10px 14px;border:1.5px solid {{ $errors->has('slug') ? '#e53e3e' : '#d1d5db' }};border-radius:8px;font-size:14px;font-family:monospace;box-sizing:border-box">
        @error('slug') <p style="color:#e53e3e;font-size:12px;margin:3px 0 0">{{ $message }}</p> @enderror
      </div>

      <div style="margin-bottom:16px">
        <label for="description" style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Description</label>
        <textarea id="description" name="description" rows="2"
                  placeholder="Courte description affichée à l'utilisateur…"
                  style="width:100%;padding:10px 14px;border:1.5px solid {{ $errors->has('description') ? '#e53e3e' : '#d1d5db' }};border-radius:8px;font-size:14px;resize:vertical;box-sizing:border-box">{{ old('description', $plan->description) }}</textarea>
        @error('description') <p style="color:#e53e3e;font-size:12px;margin:3px 0 0">{{ $message }}</p> @enderror
      </div>

      <div>
        <label for="target_type" style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">
          Cible <span style="color:#e53e3e">*</span>
        </label>
        <select id="target_type" name="target_type" required
                style="width:100%;max-width:280px;padding:10px 14px;border:1.5px solid {{ $errors->has('target_type') ? '#e53e3e' : '#d1d5db' }};border-radius:8px;font-size:14px;background:#fff">
          <option value="">— Choisir —</option>
          <option value="candidat"  {{ old('target_type', $plan->target_type) === 'candidat'  ? 'selected' : '' }}>Candidats uniquement</option>
          <option value="recruteur" {{ old('target_type', $plan->target_type) === 'recruteur' ? 'selected' : '' }}>Recruteurs uniquement</option>
          <option value="both"      {{ old('target_type', $plan->target_type) === 'both'      ? 'selected' : '' }}>Tous les utilisateurs</option>
        </select>
        @error('target_type') <p style="color:#e53e3e;font-size:12px;margin:3px 0 0">{{ $message }}</p> @enderror
      </div>

    </div>
  </div>

  {{-- ── SECTION 2 : Tarification ────────────────────────── --}}
  <div class="adm-card" style="margin-bottom:20px">
    <div style="padding:20px 24px 4px;border-bottom:1px solid #f1f5f9">
      <h2 style="font-size:14px;font-weight:700;color:#042C53;margin:0 0 2px">Tarification &amp; durée</h2>
    </div>
    <div style="padding:20px 24px">

      <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;margin-bottom:16px">
        <div>
          <label for="price" style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">
            Prix <span style="color:#e53e3e">*</span>
          </label>
          <input id="price" type="number" name="price" min="0" value="{{ old('price', $plan->price ?? 0) }}" required
                 style="width:100%;padding:10px 14px;border:1.5px solid {{ $errors->has('price') ? '#e53e3e' : '#d1d5db' }};border-radius:8px;font-size:14px;box-sizing:border-box">
          @error('price') <p style="color:#e53e3e;font-size:12px;margin:3px 0 0">{{ $message }}</p> @enderror
          <p style="font-size:11.5px;color:#94a3b8;margin:4px 0 0">Mettre 0 pour un plan gratuit.</p>
        </div>
        <div>
          <label for="currency" style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Devise</label>
          <input id="currency" type="text" name="currency" maxlength="10"
                 value="{{ old('currency', $plan->currency ?? 'FCFA') }}"
                 style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box">
        </div>
        <div>
          <label for="duration_days" style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Durée (jours)</label>
          <input id="duration_days" type="number" name="duration_days" min="1" max="3650"
                 value="{{ old('duration_days', $plan->duration_days) }}"
                 placeholder="Vide = illimité"
                 style="width:100%;padding:10px 14px;border:1.5px solid {{ $errors->has('duration_days') ? '#e53e3e' : '#d1d5db' }};border-radius:8px;font-size:14px;box-sizing:border-box">
          @error('duration_days') <p style="color:#e53e3e;font-size:12px;margin:3px 0 0">{{ $message }}</p> @enderror
          <p style="font-size:11.5px;color:#94a3b8;margin:4px 0 0">Laisser vide pour une durée illimitée.</p>
        </div>
      </div>

      <div style="display:flex;align-items:center;gap:10px">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" id="is_active" value="1"
               {{ old('is_active', $plan->exists ? $plan->is_active : true) ? 'checked' : '' }}
               style="width:16px;height:16px">
        <label for="is_active" style="font-size:13.5px;font-weight:600;color:#374151;cursor:pointer">
          Plan actif (visible et souscriptible par les utilisateurs)
        </label>
      </div>

    </div>
  </div>

  {{-- ── SECTION 3 : Fonctionnalités ────────────────────── --}}
  <div class="adm-card" style="margin-bottom:24px">
    <div style="padding:20px 24px 4px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between">
      <div>
        <h2 style="font-size:14px;font-weight:700;color:#042C53;margin:0 0 2px">Fonctionnalités &amp; quotas</h2>
        <p style="font-size:12px;color:#94a3b8;margin:0">Définissez les limites et accès inclus dans ce plan.</p>
      </div>
      <button type="button" id="addFeatureBtn" class="adm-btn adm-btn--outline adm-btn--sm">
        + Ajouter une fonctionnalité
      </button>
    </div>
    <div style="padding:20px 24px">

      <div id="features-container" style="display:flex;flex-direction:column;gap:10px">
        {{-- Lignes injectées par JS --}}
      </div>

      <p id="no-features-msg" style="color:#94a3b8;font-size:13px;font-style:italic;margin:8px 0 0;display:none">
        Aucune fonctionnalité définie. Cliquez sur « Ajouter » pour en configurer.
      </p>
    </div>
  </div>

  {{-- Boutons --}}
  <div style="display:flex;gap:12px">
    <button type="submit" class="adm-btn adm-btn--primary">
      {{ $plan->exists ? 'Enregistrer les modifications' : 'Créer le plan' }}
    </button>
    <a href="{{ route('admin.plans.list') }}" class="adm-btn adm-btn--outline">Annuler</a>
  </div>

</form>

@section('css')
<style>
  .feature-row {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 10px 12px;
  }
  .feature-row select,
  .feature-row input[type="text"] {
    padding: 8px 12px;
    border: 1.5px solid #d1d5db;
    border-radius: 6px;
    font-size: 13px;
    background: #fff;
    box-sizing: border-box;
  }
  .feature-row select      { flex: 2; }
  .feature-row input[type="text"] { flex: 1; }
  .feature-row .remove-btn {
    flex-shrink: 0;
    background: none;
    border: 1px solid #fca5a5;
    color: #ef4444;
    width: 28px;
    height: 28px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    line-height: 1;
    display: flex;
    align-items: center;
    justify-content: center;
  }
  .feature-row .remove-btn:hover { background: #fef2f2; }
</style>
@endsection

<script>
const FEATURE_KEYS = @json($featureKeys);

{{-- Priorité à old() si retour de validation, sinon fonctionnalités du plan existant --}}
const existingFeatures = @json(
  old('features') !== null
    ? old('features', [])
    : ($plan->exists
        ? $plan->features->map(fn($f) => ['key' => $f->feature_key, 'value' => $f->feature_value])->toArray()
        : [])
);

let featureIdx = 0;

function escHtml(s) {
  return String(s ?? '').replace(/&/g,'&amp;').replace(/"/g,'&quot;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
}

function buildOptions(selected) {
  let html = '<option value="">— Choisir —</option>';
  for (const [k, label] of Object.entries(FEATURE_KEYS)) {
    html += `<option value="${escHtml(k)}"${k === selected ? ' selected' : ''}>${escHtml(label)}</option>`;
  }
  html += `<option value="__custom__"${selected === '__custom__' ? ' selected' : ''}>Clé personnalisée…</option>`;
  return html;
}

function addFeatureRow(key, value) {
  key   = key   || '';
  value = value || '';

  const i        = featureIdx++;
  const isCustom = key !== '' && !FEATURE_KEYS[key];

  const div = document.createElement('div');
  div.className = 'feature-row';

  {{-- Un seul input hidden par ligne — le select n'a pas de name --}}
  div.innerHTML = `
    <select>${buildOptions(isCustom ? '__custom__' : key)}</select>
    <input type="text" class="custom-key-input" placeholder="Clé personnalisée"
           value="${escHtml(isCustom ? key : '')}"
           style="${isCustom ? '' : 'display:none'}">
    <input type="hidden" name="features[${i}][key]" value="${escHtml(key)}">
    <input type="text"   name="features[${i}][value]" value="${escHtml(value)}" placeholder="Valeur (ex: 5, 1, 100)">
    <button type="button" class="remove-btn" title="Supprimer">✕</button>
  `;

  const sel       = div.querySelector('select');
  const customTxt = div.querySelector('.custom-key-input');
  const hiddenKey = div.querySelector(`input[name="features[${i}][key]"]`);

  sel.addEventListener('change', () => {
    if (sel.value === '__custom__') {
      customTxt.style.display = '';
      hiddenKey.value = customTxt.value;
      customTxt.focus();
    } else {
      customTxt.style.display = 'none';
      customTxt.value = '';
      hiddenKey.value = sel.value;
    }
  });

  customTxt.addEventListener('input', () => {
    hiddenKey.value = customTxt.value;
  });

  div.querySelector('.remove-btn').addEventListener('click', () => {
    div.remove();
    updateEmptyMsg();
  });

  document.getElementById('features-container').appendChild(div);
  updateEmptyMsg();
}

function updateEmptyMsg() {
  const empty = document.getElementById('no-features-msg');
  empty.style.display = document.querySelectorAll('.feature-row').length === 0 ? '' : 'none';
}

document.getElementById('addFeatureBtn').addEventListener('click', () => addFeatureRow());

// Slug auto-generation
const nameInput = document.getElementById('name');
const slugInput = document.getElementById('slug');
let slugManuallyEdited = {{ $plan->exists ? 'true' : 'false' }};

slugInput.addEventListener('input', () => { slugManuallyEdited = true; });
nameInput.addEventListener('input', function () {
  if (slugManuallyEdited) return;
  slugInput.value = this.value
    .toLowerCase()
    .normalize('NFD').replace(/[̀-ͯ]/g, '')
    .replace(/[^a-z0-9\s]/g, '')
    .trim()
    .replace(/\s+/g, '-');
});

document.addEventListener('DOMContentLoaded', () => {
  if (existingFeatures.length > 0) {
    existingFeatures.forEach(f => addFeatureRow(f.key, f.value));
  } else {
    updateEmptyMsg();
  }
});
</script>
@endsection
