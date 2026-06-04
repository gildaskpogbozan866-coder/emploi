@extends('layouts.app')
@section('title', 'Publier une offre d\'emploi — Emploi Bouge Bénin')

@section('content')
<section style="padding:48px 20px;background:#f8fafc;min-height:70vh">
  <div style="max-width:680px;margin:0 auto">
    <div style="text-align:center;margin-bottom:36px">
      <span style="background:#185FA5;color:#fff;font-size:12px;font-weight:800;padding:4px 16px;border-radius:20px;text-transform:uppercase;letter-spacing:.06em">Recruteurs</span>
      <h1 style="font-size:2rem;font-weight:800;color:#042C53;margin:16px 0 10px">Publiez votre offre d'emploi</h1>
      <p style="font-size:1rem;color:#64748b;max-width:460px;margin:0 auto">Touchez des milliers de candidats qualifiés au Bénin et en Afrique de l'Ouest.</p>
    </div>

    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:18px;padding:32px">
      <form method="POST" action="{{ route('offre.publier.store') }}">
        @csrf

        <div style="margin-bottom:18px">
          <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Titre du poste <span style="color:#e53e3e">*</span></label>
          <input type="text" name="titre" value="{{ old('titre') }}" required placeholder="Ex: Développeur Web Junior"
                 style="width:100%;padding:10px 14px;border:1.5px solid {{ $errors->has('titre') ? '#e53e3e' : '#d1d5db' }};border-radius:8px;font-size:14px;box-sizing:border-box">
          @error('titre') <p style="color:#e53e3e;font-size:12px;margin:3px 0 0">{{ $message }}</p> @enderror
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:18px">
          <div>
            <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Entreprise <span style="color:#e53e3e">*</span></label>
            <input type="text" name="entreprise" value="{{ old('entreprise') }}" required placeholder="Nom de votre entreprise"
                   style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box">
          </div>
          <div>
            <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Localisation <span style="color:#e53e3e">*</span></label>
            <input type="text" name="localisation" value="{{ old('localisation') }}" required placeholder="Cotonou, Bénin"
                   style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box">
          </div>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:18px">
          <div>
            <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Type de contrat <span style="color:#e53e3e">*</span></label>
            <select name="type" required style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box">
              <option value="">-- Choisir --</option>
              @foreach(['CDI','CDD','Stage','Bourse','Freelance','Temps partiel'] as $type)
                <option value="{{ $type }}" {{ old('type') === $type ? 'selected' : '' }}>{{ $type }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Secteur</label>
            <input type="text" name="secteur" value="{{ old('secteur') }}" placeholder="Tech, Finance, Santé…"
                   style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box">
          </div>
        </div>

        <div style="margin-bottom:18px">
          <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Description complète <span style="color:#e53e3e">*</span></label>
          <textarea name="description" rows="8" required placeholder="Décrivez le poste, les missions, le profil recherché…"
                    style="width:100%;padding:10px 14px;border:1.5px solid {{ $errors->has('description') ? '#e53e3e' : '#d1d5db' }};border-radius:8px;font-size:14px;resize:vertical;box-sizing:border-box">{{ old('description') }}</textarea>
          @error('description') <p style="color:#e53e3e;font-size:12px;margin:3px 0 0">{{ $message }}</p> @enderror
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:24px">
          <div>
            <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Rémunération (optionnel)</label>
            <input type="text" name="salaire" value="{{ old('salaire') }}" placeholder="Ex: 150 000 FCFA/mois"
                   style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box">
          </div>
          <div>
            <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Date limite</label>
            <input type="date" name="date_limite" value="{{ old('date_limite') }}"
                   style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box">
          </div>
        </div>

        <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:10px;padding:14px 18px;margin-bottom:24px">
          <p style="font-size:13px;color:#92400e;margin:0">
            <strong>Note :</strong> Votre offre sera examinée avant publication. Le délai moyen est de 24h.
          </p>
        </div>

        <button type="submit" style="width:100%;padding:13px 24px;background:#185FA5;color:#fff;border:none;border-radius:10px;font-weight:800;font-size:15px;cursor:pointer">
          Soumettre l'offre pour validation
        </button>
      </form>
    </div>
  </div>
</section>
@endsection
