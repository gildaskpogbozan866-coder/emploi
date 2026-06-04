@extends('layouts.dashboard')
@section('title', 'Créer mon profil Talent')
@section('space-label', 'Espace Talent')

@section('sidebar')
<a href="{{ route('home') }}" class="dash-sidebar__logo">
  <span>Emploi Bouge</span><small>Bénin · Talent</small>
</a>
<ul class="dash-nav">
  <li class="dash-nav__item"><a href="{{ route('talent.dashboard') }}">Tableau de bord</a></li>
  <li class="dash-nav__item active"><a href="{{ route('talent.profil') }}">Mon profil</a></li>
  <li class="dash-nav__item"><a href="{{ route('talent.messagerie') }}">Messagerie</a></li>
  <li class="dash-nav__item"><a href="{{ route('talent.abonnement') }}">Abonnement Premium</a></li>
  <li class="dash-nav__item"><a href="{{ route('talent.parametres') }}">Paramètres</a></li>
</ul>
@endsection

@section('content')
<div class="dash-content">
  <div class="dash-content__header">
    <h1 class="dash-content__title">Créer mon profil Talent</h1>
    <p style="color:#6b7a8d;margin:0">Votre profil sera visible par les recruteurs dès sa création</p>
  </div>

  <div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:28px 32px;max-width:680px">
    <form method="POST" action="{{ route('talent.profil.store') }}" enctype="multipart/form-data">
      @csrf

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
        <div>
          <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Métier / Titre <span style="color:#e53e3e">*</span></label>
          <input type="text" name="metier" value="{{ old('metier') }}" required placeholder="Ex: Développeur Web, Infographiste…"
                 style="width:100%;padding:10px 14px;border:1.5px solid {{ $errors->has('metier') ? '#e53e3e' : '#d1d5db' }};border-radius:8px;font-size:14px;box-sizing:border-box">
          @error('metier') <p style="color:#e53e3e;font-size:12px;margin:3px 0 0">{{ $message }}</p> @enderror
        </div>
        <div>
          <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Spécialité</label>
          <input type="text" name="specialite" value="{{ old('specialite') }}" placeholder="Ex: React, Design UI, Comptabilité…"
                 style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box">
        </div>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
        <div>
          <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Pays <span style="color:#e53e3e">*</span></label>
          <input type="text" name="pays" value="{{ old('pays') }}" required placeholder="Bénin, Sénégal, Côte d'Ivoire…"
                 style="width:100%;padding:10px 14px;border:1.5px solid {{ $errors->has('pays') ? '#e53e3e' : '#d1d5db' }};border-radius:8px;font-size:14px;box-sizing:border-box">
          @error('pays') <p style="color:#e53e3e;font-size:12px;margin:3px 0 0">{{ $message }}</p> @enderror
        </div>
        <div>
          <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Ville</label>
          <input type="text" name="ville" value="{{ old('ville') }}" placeholder="Cotonou, Dakar…"
                 style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box">
        </div>
      </div>

      <div style="margin-bottom:16px">
        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Biographie</label>
        <textarea name="bio" rows="4" placeholder="Présentez-vous en quelques phrases : votre parcours, vos compétences, vos objectifs…"
                  style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;resize:vertical;box-sizing:border-box">{{ old('bio') }}</textarea>
      </div>

      <div style="margin-bottom:16px">
        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Compétences</label>
        <input type="text" name="competences" value="{{ old('competences') }}" placeholder="HTML, CSS, Photoshop, Excel (séparées par des virgules)"
               style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box">
        <p style="font-size:12px;color:#94a3b8;margin:4px 0 0">Séparez les compétences par des virgules</p>
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
        <div>
          <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Expérience</label>
          <input type="text" name="experience" value="{{ old('experience') }}" placeholder="Ex: 3 ans en développement web"
                 style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box">
        </div>
        <div>
          <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Langues</label>
          <input type="text" name="langues" value="{{ old('langues') }}" placeholder="Français, Anglais, Fon…"
                 style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box">
        </div>
      </div>

      <div style="margin-bottom:24px">
        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Photo de profil</label>
        <input type="file" name="photo" accept="image/*"
               style="width:100%;padding:8px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:13.5px;box-sizing:border-box">
        <p style="font-size:12px;color:#94a3b8;margin:4px 0 0">PNG, JPG — max 2 Mo</p>
      </div>

      <div style="display:flex;gap:12px">
        <button type="submit" style="padding:11px 28px;background:#185FA5;color:#fff;border:none;border-radius:8px;font-weight:700;font-size:14px;cursor:pointer">
          Créer mon profil Talent
        </button>
        <a href="{{ route('talent.dashboard') }}" style="padding:11px 20px;background:#f1f5f9;color:#374151;border-radius:8px;font-weight:600;font-size:14px;text-decoration:none">
          Annuler
        </a>
      </div>
    </form>
  </div>
</div>
@endsection
