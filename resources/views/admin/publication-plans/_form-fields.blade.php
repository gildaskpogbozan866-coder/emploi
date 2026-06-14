{{-- Champs partagés entre le form création et édition. Passer $plan pour pré-remplir. --}}
@php $p = $plan ?? null; @endphp

<div>
  <label class="adm-form-label">Nom du plan <span style="color:#e53e3e">*</span></label>
  <input type="text" name="name" required placeholder="Ex : 1 semaine"
         class="adm-form-input" value="{{ old('name', $p?->name) }}">
</div>

<div>
  <label class="adm-form-label">Durée (jours)</label>
  <select name="duration_days" class="adm-form-input">
    <option value=""  {{ old('duration_days', $p?->duration_days) === null  ? 'selected' : '' }}>Illimité</option>
    <option value="1"  {{ (string)old('duration_days', $p?->duration_days) === '1'   ? 'selected' : '' }}>1 jour</option>
    <option value="7"  {{ (string)old('duration_days', $p?->duration_days) === '7'   ? 'selected' : '' }}>1 semaine (7 jours)</option>
    <option value="30" {{ (string)old('duration_days', $p?->duration_days) === '30'  ? 'selected' : '' }}>1 mois (30 jours)</option>
    <option value="90" {{ (string)old('duration_days', $p?->duration_days) === '90'  ? 'selected' : '' }}>3 mois (90 jours)</option>
    <option value="365"{{ (string)old('duration_days', $p?->duration_days) === '365' ? 'selected' : '' }}>1 an (365 jours)</option>
  </select>
  <p style="font-size:11.5px;color:#94a3b8;margin:3px 0 0">Laisser vide = visibilité illimitée.</p>
</div>

<div>
  <label class="adm-form-label">Prix (FCFA) <span style="color:#e53e3e">*</span></label>
  <input type="number" name="price" required min="0" step="100"
         placeholder="1000" class="adm-form-input" value="{{ old('price', $p?->price ?? 0) }}">
  <p style="font-size:11.5px;color:#94a3b8;margin:3px 0 0">Mettre 0 pour un plan gratuit.</p>
</div>

<label style="display:flex;align-items:center;gap:10px;cursor:pointer">
  <input type="checkbox" name="is_active" value="1"
         {{ old('is_active', $p?->is_active ?? true) ? 'checked' : '' }}
         style="width:16px;height:16px;accent-color:#185FA5">
  <span class="adm-form-label" style="margin:0">Plan actif (visible aux annonceurs)</span>
</label>
