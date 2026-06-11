@extends('layouts.candidat')
@section('title', 'Créer mon profil Talent')


@section('sidebar')
@include('candidat._sidebar')
@endsection
@section('content')
<div class="dash-content">
  <div class="dash-content__header" style="margin-bottom:24px">
    <h1 class="dash-content__title">Créer mon profil Talent</h1>
    <p style="color:#64748b;margin:0;font-size:13.5px">Visible par les recruteurs dès la création — complétez-le ensuite pour plus de visibilité</p>
  </div>

  <div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:28px 32px;max-width:680px">
    <form method="POST" action="{{ route('talent.profil.store') }}" enctype="multipart/form-data">
      @csrf

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px">
        <div>
          <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Métier <span style="color:#e53e3e">*</span></label>
          <input type="text" name="metier" value="{{ old('metier') }}" required maxlength="200"
                 placeholder="Ex : Menuisier, Mécanicien, Électricien…"
                 style="width:100%;padding:9px 12px;border:1.5px solid {{ $errors->has('metier') ? '#e53e3e' : '#d1d5db' }};border-radius:8px;font-size:14px;box-sizing:border-box">
          @error('metier')<p style="color:#e53e3e;font-size:12px;margin:3px 0 0">{{ $message }}</p>@enderror
        </div>
        <div>
          <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Spécialité</label>
          <input type="text" name="specialite" value="{{ old('specialite') }}" maxlength="200"
                 placeholder="Ex : Menuiserie ébénisterie, Mécanique auto…"
                 style="width:100%;padding:9px 12px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box">
        </div>
        <div>
          <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Pays <span style="color:#e53e3e">*</span></label>
          <input type="text" name="pays" value="{{ old('pays') }}" required maxlength="100"
                 placeholder="Bénin, Sénégal…"
                 style="width:100%;padding:9px 12px;border:1.5px solid {{ $errors->has('pays') ? '#e53e3e' : '#d1d5db' }};border-radius:8px;font-size:14px;box-sizing:border-box">
          @error('pays')<p style="color:#e53e3e;font-size:12px;margin:3px 0 0">{{ $message }}</p>@enderror
        </div>
        <div>
          <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Ville</label>
          <input type="text" name="ville" value="{{ old('ville') }}" maxlength="100"
                 placeholder="Cotonou, Porto-Novo…"
                 style="width:100%;padding:9px 12px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box">
        </div>
      </div>

      <div style="margin-bottom:14px">
        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Présentation</label>
        <textarea name="bio" rows="4" maxlength="2000"
                  placeholder="Décrivez votre formation, vos expériences et ce que vous recherchez…"
                  style="width:100%;padding:9px 12px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;resize:vertical;box-sizing:border-box;line-height:1.6">{{ old('bio') }}</textarea>
      </div>

      <div style="margin-bottom:14px">
        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Compétences techniques <span style="color:#94a3b8;font-weight:400">(séparées par des virgules)</span></label>
        <input type="text" name="competences" value="{{ old('competences') }}"
               placeholder="Ex : Soudure, Pose carrelage, Conduite engin, Électricité bâtiment…"
               style="width:100%;padding:9px 12px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box">
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px">
        <div>
          <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Années d'expérience</label>
          <input type="number" name="annees_experience" value="{{ old('annees_experience') }}"
                 min="0" max="50" placeholder="Ex : 3"
                 style="width:100%;padding:9px 12px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box">
        </div>
        <div>
          <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Disponibilité</label>
          <select name="disponibilite" style="width:100%;padding:9px 12px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box;background:#fff">
            <option value="">— Sélectionner —</option>
            @foreach(['immediatement'=>'Immédiatement','1_mois'=>'Dans 1 mois','2_mois'=>'Dans 2 mois','3_mois'=>'Dans 3 mois','plus_3_mois'=>'Plus de 3 mois'] as $val => $label)
              <option value="{{ $val }}" {{ old('disponibilite') === $val ? 'selected' : '' }}>{{ $label }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div style="margin-bottom:14px">
        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:8px">Types de contrat souhaités</label>
        <div style="display:flex;flex-wrap:wrap;gap:12px">
          @foreach(['cdi'=>'CDI','cdd'=>'CDD','stage'=>'Stage','alternance'=>'Alternance','interim'=>'Intérim'] as $val => $label)
          <label style="display:flex;align-items:center;gap:6px;font-size:13.5px;color:#374151;cursor:pointer">
            <input type="checkbox" name="types_contrat[]" value="{{ $val }}"
                   {{ in_array($val, old('types_contrat', [])) ? 'checked' : '' }}
                   style="width:15px;height:15px;accent-color:#38A169">
            {{ $label }}
          </label>
          @endforeach
        </div>
      </div>

      <div style="margin-bottom:24px">
        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Photo de profil</label>
        <input type="file" name="photo" accept="image/jpeg,image/png,image/webp"
               style="display:block;width:100%;padding:8px 12px;border:1.5px solid #d1d5db;border-radius:8px;font-size:13px">
        <p style="font-size:12px;color:#94a3b8;margin:4px 0 0">JPG, PNG, WebP — max 2 Mo</p>
        @error('photo')<p style="color:#e53e3e;font-size:12px;margin:3px 0 0">{{ $message }}</p>@enderror
      </div>

      <div style="display:flex;gap:12px">
        <button type="submit" style="padding:11px 28px;background:#38A169;color:#fff;border:none;border-radius:8px;font-weight:700;font-size:14px;cursor:pointer">
          Créer mon profil
        </button>
        <a href="{{ route('talent.dashboard') }}" style="padding:11px 20px;background:#f1f5f9;color:#374151;border-radius:8px;font-weight:600;font-size:14px;text-decoration:none">
          Annuler
        </a>
      </div>
    </form>
  </div>
</div>

@if($errors->any())
<div id="flash-data" data-type="error" data-msg="{{ implode(' — ', $errors->all()) }}" style="display:none"></div>
@endif
@endsection

@section('scripts')
<script>
const _SwalToast = Swal.mixin({
  toast: true, position: 'top-end', showConfirmButton: false,
  timer: 4000, timerProgressBar: true,
  didOpen: t => { t.onmouseenter = Swal.stopTimer; t.onmouseleave = Swal.resumeTimer; }
});
const fd = document.getElementById('flash-data');
if (fd) _SwalToast.fire({ icon: 'error', title: fd.dataset.msg });
</script>
@endsection
