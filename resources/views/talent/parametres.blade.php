@extends('layouts.dashboard')
@section('title', 'Paramètres — Talent')
@section('space-label', 'Espace Talent')

@section('sidebar')
<a href="{{ route('home') }}" class="dash-sidebar__logo">
  <span>Emploi Bouge</span><small>Bénin · Talent</small>
</a>
<div class="dash-sidebar__user">
  <div class="dash-sidebar__avatar">{{ auth()->user()->initiale }}</div>
  <div class="dash-sidebar__info">
    <div class="dash-sidebar__name">{{ auth()->user()->nom_complet }}</div>
    <div class="dash-sidebar__role">{{ auth()->user()->metier ?? 'Talent' }}</div>
  </div>
</div>
<ul class="dash-nav">
  <li class="dash-nav__item {{ request()->routeIs('talent.dashboard') ? 'active' : '' }}">
    <a href="{{ route('talent.dashboard') }}">Tableau de bord</a>
  </li>
  <li class="dash-nav__item {{ request()->routeIs('talent.profil*') ? 'active' : '' }}">
    <a href="{{ route('talent.profil') }}">Mon profil</a>
  </li>
  <li class="dash-nav__item {{ request()->routeIs('talent.messagerie*') ? 'active' : '' }}">
    <a href="{{ route('talent.messagerie') }}">Messagerie</a>
  </li>
  <li class="dash-nav__item {{ request()->routeIs('talent.abonnement*') ? 'active' : '' }}">
    <a href="{{ route('talent.abonnement') }}">Abonnement Premium</a>
  </li>
  <li class="dash-nav__item active">
    <a href="{{ route('talent.parametres') }}">Paramètres</a>
  </li>
</ul>
@endsection

@section('content')
<div class="dash-content">
  <div class="dash-content__header">
    <h1 class="dash-content__title">Paramètres du compte</h1>
    <p style="color:#6b7a8d;margin:0">Modifiez vos informations personnelles et votre sécurité</p>
  </div>

  @include('partials._statut-compte')

  {{-- Informations personnelles --}}
  <div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:28px;max-width:560px;margin-bottom:20px">
    <h2 style="font-size:15px;font-weight:700;color:#0f172a;margin:0 0 4px">Informations personnelles</h2>
    <p style="font-size:13px;color:#6b7280;margin:0 0 20px">Tout sauf votre adresse e-mail peut être modifié.</p>

    @if(session('success'))
      <div style="background:#d1fae5;border:1px solid #6ee7b7;border-radius:8px;padding:10px 14px;font-size:.85rem;color:#065f46;margin-bottom:18px">
        {{ session('success') }}
      </div>
    @endif

    <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:12px 16px;font-size:13px;color:#64748b;margin-bottom:20px">
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:middle;margin-right:8px"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
      <strong style="color:#374151">Email :</strong> {{ auth()->user()->email }}
      <span style="margin-left:6px;font-size:11px;background:#e2e8f0;padding:2px 8px;border-radius:20px">non modifiable</span>
    </div>

    <form method="POST" action="{{ route('talent.parametres.update') }}">
      @csrf @method('PUT')

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px">
        <div>
          <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Prénom <span style="color:#e53e3e">*</span></label>
          <input type="text" name="prenom" value="{{ old('prenom', $user->prenom) }}" required
                 style="width:100%;padding:10px 14px;border:1.5px solid {{ $errors->has('prenom') ? '#e53e3e' : '#d1d5db' }};border-radius:8px;font-size:14px;color:#1e293b;box-sizing:border-box">
          @error('prenom')<p style="color:#e53e3e;font-size:12px;margin:4px 0 0">{{ $message }}</p>@enderror
        </div>
        <div>
          <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Nom <span style="color:#e53e3e">*</span></label>
          <input type="text" name="nom" value="{{ old('nom', $user->nom) }}" required
                 style="width:100%;padding:10px 14px;border:1.5px solid {{ $errors->has('nom') ? '#e53e3e' : '#d1d5db' }};border-radius:8px;font-size:14px;color:#1e293b;box-sizing:border-box">
          @error('nom')<p style="color:#e53e3e;font-size:12px;margin:4px 0 0">{{ $message }}</p>@enderror
        </div>
      </div>

      <div style="margin-bottom:14px">
        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Métier / Spécialité</label>
        <input type="text" name="metier" value="{{ old('metier', $user->metier) }}"
               placeholder="Ex : Développeur Web, Graphiste..."
               style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;color:#1e293b;box-sizing:border-box">
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:20px">
        <div>
          <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Téléphone</label>
          <input type="text" name="tel" value="{{ old('tel', $user->tel) }}"
                 placeholder="+229 XX XX XX XX"
                 style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;color:#1e293b;box-sizing:border-box">
        </div>
        <div>
          <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Pays</label>
          <select name="pays" style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;color:#1e293b;box-sizing:border-box">
            @foreach(['Bénin','Côte d\'Ivoire','Sénégal','Cameroun','Togo','Mali','Burkina Faso','Niger','Guinée','Congo','Madagascar','Autre'] as $p)
              <option value="{{ $p }}" {{ old('pays', $user->pays) === $p ? 'selected' : '' }}>{{ $p }}</option>
            @endforeach
          </select>
        </div>
      </div>

      <button type="submit" style="padding:11px 24px;background:#185FA5;color:#fff;border:none;border-radius:8px;font-weight:700;font-size:14px;cursor:pointer">
        Enregistrer les modifications
      </button>
    </form>
  </div>

  {{-- Mot de passe --}}
  <div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:24px 28px;max-width:560px;margin-bottom:20px">
    <h2 style="font-size:15px;font-weight:700;color:#0f172a;margin:0 0 16px">Changer le mot de passe</h2>

    @if(session('mdp_success'))
      <div style="background:#d1fae5;border:1px solid #6ee7b7;border-radius:8px;padding:10px 14px;font-size:.85rem;color:#065f46;margin-bottom:16px">
        {{ session('mdp_success') }}
      </div>
    @endif

    <form method="POST" action="{{ route('auth.changer-mot-de-passe.store') }}">
      @csrf

      <div style="margin-bottom:14px">
        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Mot de passe actuel <span style="color:#e53e3e">*</span></label>
        <input type="password" name="mot_de_passe_actuel" placeholder="••••••••" autocomplete="current-password"
               style="width:100%;padding:10px 14px;border:1.5px solid {{ $errors->has('mot_de_passe_actuel') ? '#e53e3e' : '#d1d5db' }};border-radius:8px;font-size:14px;box-sizing:border-box">
        @error('mot_de_passe_actuel')<p style="color:#e53e3e;font-size:12px;margin:4px 0 0">{{ $message }}</p>@enderror
      </div>

      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:20px">
        <div>
          <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Nouveau mot de passe <span style="color:#e53e3e">*</span></label>
          <input type="password" name="password" placeholder="Min. 8 caractères" autocomplete="new-password"
                 style="width:100%;padding:10px 14px;border:1.5px solid {{ $errors->has('password') ? '#e53e3e' : '#d1d5db' }};border-radius:8px;font-size:14px;box-sizing:border-box">
          @error('password')<p style="color:#e53e3e;font-size:12px;margin:4px 0 0">{{ $message }}</p>@enderror
        </div>
        <div>
          <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Confirmer <span style="color:#e53e3e">*</span></label>
          <input type="password" name="password_confirmation" placeholder="Répétez" autocomplete="new-password"
                 style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;box-sizing:border-box">
        </div>
      </div>

      <button type="submit" style="padding:11px 24px;background:#185FA5;color:#fff;border:none;border-radius:8px;font-weight:700;font-size:14px;cursor:pointer">
        Enregistrer le nouveau mot de passe
      </button>
    </form>
  </div>

  {{-- Zone danger --}}
  <div style="background:#fff;border:1px solid #fde8e8;border-radius:14px;padding:22px 28px;max-width:560px">
    <h3 style="color:#c53030;font-size:15px;font-weight:700;margin:0 0 8px">Zone de danger</h3>
    <p style="font-size:13px;color:#64748b;margin:0 0 14px">La suppression de votre compte est irréversible et entraîne la perte de toutes vos données.</p>
    <button type="button"
            style="padding:9px 18px;background:transparent;border:1.5px solid #e53e3e;color:#e53e3e;border-radius:8px;font-weight:600;font-size:13px;cursor:pointer"
            onclick="alert('Pour supprimer votre compte, contactez : support@emploibouge.bj')">
      Supprimer mon compte
    </button>
  </div>
</div>
@endsection
