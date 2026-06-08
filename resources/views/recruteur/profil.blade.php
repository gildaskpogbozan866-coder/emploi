@extends('layouts.recruteur')
@section('title', 'Mon profil recruteur')

@section('sidebar')
@include('recruteur._sidebar')
@endsection

@section('content')
<div class="rec-topbar">
  <div class="rec-topbar__left">
    <h1>Mon profil recruteur</h1>
    <p>Informations de votre compte et de votre entreprise</p>
  </div>
</div>

<div class="rec-card" style="max-width:680px">
  <div class="rec-card__body">
    <form method="POST" action="{{ route('recruteur.profil.update') }}" enctype="multipart/form-data">
      @csrf @method('PUT')

      {{-- Avatar entreprise --}}
      <div class="rec-avatar-upload">
        <div class="rec-avatar-preview">
          @if($user->avatar)
            <img src="{{ asset('storage/'.$user->avatar) }}">
          @else
            {{ $user->initiale }}
          @endif
        </div>
        <div>
          <p style="font-size:13px;font-weight:600;color:#042C53;margin:0 0 4px">Logo / Photo</p>
          <input type="file" name="avatar" accept="image/*" style="font-size:12.5px;color:#6b7a8d">
          <p style="font-size:12px;color:#94a3b8;margin:4px 0 0">JPG ou PNG recommandé</p>
        </div>
      </div>

      <div class="rec-form-grid">
        <div class="rec-form-group">
          <label>Prénom <span style="color:#e53e3e">*</span></label>
          <input type="text" name="prenom" value="{{ old('prenom', $user->prenom) }}" required>
          @error('prenom')<small style="color:#e53e3e">{{ $message }}</small>@enderror
        </div>
        <div class="rec-form-group">
          <label>Nom <span style="color:#e53e3e">*</span></label>
          <input type="text" name="nom" value="{{ old('nom', $user->nom) }}" required>
          @error('nom')<small style="color:#e53e3e">{{ $message }}</small>@enderror
        </div>

        <div class="rec-form-group full">
          <label>Nom de l'entreprise</label>
          <input type="text" name="entreprise" value="{{ old('entreprise', $user->entreprise) }}" placeholder="Ex : TechBénin SARL">
        </div>

        <div class="rec-form-group">
          <label>Téléphone</label>
          <input type="tel" name="tel" value="{{ old('tel', $user->tel) }}" placeholder="+229 01 00 00 00">
        </div>
        <div class="rec-form-group">
          <label>Pays</label>
          <select name="pays">
            @foreach(['Bénin','Côte d\'Ivoire','Sénégal','Cameroun','Togo','Mali','Burkina Faso','Autre'] as $p)
              <option value="{{ $p }}" {{ old('pays', $user->pays) === $p ? 'selected' : '' }}>{{ $p }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:12px 16px;margin-bottom:18px;font-size:13px;color:#64748b">
        <strong style="color:#374151">Email :</strong> {{ $user->email }}
        <span style="margin-left:6px;font-size:11px;background:#e2e8f0;color:#64748b;padding:2px 8px;border-radius:20px">non modifiable</span>
      </div>

      <div style="display:flex;gap:10px">
        <button type="submit" class="rec-btn rec-btn--primary">Mettre à jour mon profil</button>
      </div>
    </form>
  </div>
</div>
@endsection
