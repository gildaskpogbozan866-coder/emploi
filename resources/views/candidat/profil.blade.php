@extends('layouts.candidat')
@section('title', 'Mon profil')

@section('sidebar')
@include('candidat._sidebar')
@endsection

@section('content')
<div class="cand-page-header">
  <div class="cand-page-header__left">
    <h1 class="cand-page-header__title">Mon profil</h1>
    <p class="cand-page-header__sub">Informations personnelles visibles par les recruteurs</p>
  </div>
</div>

<div class="cand-card" style="max-width:680px">
  <form method="POST" action="{{ route('candidat.profil.update') }}" enctype="multipart/form-data">
    @csrf @method('PUT')

    {{-- Avatar --}}
    <div style="display:flex;align-items:center;gap:20px;margin-bottom:24px;padding-bottom:22px;border-bottom:1px solid #edf0f4">
      <div style="width:72px;height:72px;border-radius:50%;background:rgba(55,138,221,0.12);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1.6rem;color:#185FA5;flex-shrink:0;overflow:hidden">
        @if($user->avatar)
          <img src="{{ asset('storage/'.$user->avatar) }}" style="width:100%;height:100%;object-fit:cover">
        @else
          {{ $user->initiale }}
        @endif
      </div>
      <div>
        <label class="cand-form-label">Photo de profil</label>
        <input type="file" name="avatar" accept="image/*" style="font-size:13px;color:#6b7a8d;display:block;margin-top:6px">
        <p class="cand-form-hint">JPG ou PNG, max 2 Mo recommandé</p>
      </div>
    </div>

    <div class="cand-form-grid">
      <div class="cand-form-group">
        <label class="cand-form-label">Prénom <span class="req">*</span></label>
        <input class="cand-form-input" type="text" name="prenom" value="{{ old('prenom', $user->prenom) }}" required>
        @error('prenom')<p style="color:#e53e3e;font-size:12px;margin-top:4px">{{ $message }}</p>@enderror
      </div>
      <div class="cand-form-group">
        <label class="cand-form-label">Nom <span class="req">*</span></label>
        <input class="cand-form-input" type="text" name="nom" value="{{ old('nom', $user->nom) }}" required>
        @error('nom')<p style="color:#e53e3e;font-size:12px;margin-top:4px">{{ $message }}</p>@enderror
      </div>
    </div>

    <div class="cand-form-group">
      <label class="cand-form-label">Téléphone</label>
      <input class="cand-form-input" type="tel" name="tel" value="{{ old('tel', $user->tel) }}" placeholder="+229 01 00 00 00">
    </div>

    <div class="cand-form-group">
      <label class="cand-form-label">Pays</label>
      <select class="cand-form-select" name="pays">
        @foreach(['Bénin','Côte d\'Ivoire','Sénégal','Cameroun','Togo','Mali','Burkina Faso','Niger','Guinée','Congo','Madagascar','Autre'] as $pays)
          <option value="{{ $pays }}" {{ old('pays', $user->pays) === $pays ? 'selected' : '' }}>{{ $pays }}</option>
        @endforeach
      </select>
    </div>

    <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:12px 16px;margin-bottom:16px;font-size:13px;color:#64748b">
      <strong style="color:#374151">Email :</strong> {{ $user->email }}
      <span style="margin-left:6px;font-size:11px;background:#e2e8f0;color:#64748b;padding:2px 8px;border-radius:20px">non modifiable</span>
    </div>

    <div class="cand-form-actions">
      <button type="submit" class="cand-btn cand-btn--primary">Mettre à jour mon profil</button>
    </div>
  </form>
</div>
@endsection
