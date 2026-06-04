@extends('layouts.candidat')
@section('title', 'Modifier mon CV')

@section('sidebar')
@include('candidat._sidebar')
@endsection

@section('content')
<div class="cand-page-header">
  <div class="cand-page-header__left">
    <h1 class="cand-page-header__title">Modifier mon CV</h1>
    <p class="cand-page-header__sub">Mettez à jour les informations de votre CV</p>
  </div>
</div>

<div class="cand-card" style="max-width:640px">
  <form method="POST" action="{{ route('candidat.cvs.update', $cv) }}">
    @csrf @method('PUT')

    <div style="margin-bottom:18px">
      <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Titre du poste visé <span style="color:#e53e3e">*</span></label>
      <input type="text" name="titre_poste" value="{{ old('titre_poste', $cv->titre_poste) }}" required
             style="width:100%;padding:10px 14px;border:1.5px solid {{ $errors->has('titre_poste') ? '#e53e3e' : '#d1d5db' }};border-radius:8px;font-size:14px;box-sizing:border-box">
      @error('titre_poste') <p style="color:#e53e3e;font-size:12px;margin:3px 0 0">{{ $message }}</p> @enderror
    </div>

    <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:18px">
      <div>
        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Pays <span style="color:#e53e3e">*</span></label>
        <input type="text" name="pays" value="{{ old('pays', $cv->pays) }}" required
               style="width:100%;padding:10px 14px;border:1.5px solid {{ $errors->has('pays') ? '#e53e3e' : '#d1d5db' }};border-radius:8px;font-size:14px;box-sizing:border-box">
        @error('pays') <p style="color:#e53e3e;font-size:12px;margin:3px 0 0">{{ $message }}</p> @enderror
      </div>
      <div>
        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Ville</label>
        <input type="text" name="ville" value="{{ old('ville', $cv->ville) }}"
               style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box">
      </div>
    </div>

    <div style="margin-bottom:18px">
      <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Compétences</label>
      <textarea name="competences" rows="3"
                placeholder="Listez vos compétences principales…"
                style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;resize:vertical;box-sizing:border-box">{{ old('competences', $cv->competences) }}</textarea>
    </div>

    <div style="margin-bottom:18px">
      <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Expérience professionnelle</label>
      <textarea name="experience" rows="4"
                placeholder="Décrivez votre parcours professionnel…"
                style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;resize:vertical;box-sizing:border-box">{{ old('experience', $cv->experience) }}</textarea>
    </div>

    <div style="margin-bottom:18px">
      <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Formation</label>
      <textarea name="formation" rows="3"
                placeholder="Vos diplômes et formations…"
                style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;resize:vertical;box-sizing:border-box">{{ old('formation', $cv->formation) }}</textarea>
    </div>

    <div style="margin-bottom:24px">
      <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Langues</label>
      <input type="text" name="langues" value="{{ old('langues', $cv->langues) }}" placeholder="Français, Anglais, Fon…"
             style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box">
    </div>

    <div style="display:flex;gap:12px">
      <button type="submit" class="cand-btn cand-btn--primary">Enregistrer les modifications</button>
      <a href="{{ route('candidat.cvs') }}" class="cand-btn cand-btn--outline">Annuler</a>
    </div>
  </form>
</div>
@endsection
