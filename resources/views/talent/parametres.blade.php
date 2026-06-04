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
    <p style="color:#6b7a8d;margin:0">Modifiez vos informations de connexion</p>
  </div>

  <div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:28px;max-width:540px">
    <form method="POST" action="{{ route('talent.parametres.update') }}">
      @csrf @method('PUT')

      <div style="margin-bottom:18px">
        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Adresse email</label>
        <input type="email" name="email" value="{{ old('email', $user->email) }}" required
               style="width:100%;padding:10px 14px;border:1.5px solid {{ $errors->has('email') ? '#e53e3e' : '#d1d5db' }};border-radius:8px;font-size:14px;color:#1e293b;box-sizing:border-box">
        @error('email') <p style="color:#e53e3e;font-size:12px;margin:4px 0 0">{{ $message }}</p> @enderror
      </div>

      <div style="margin-bottom:18px">
        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Numéro de téléphone</label>
        <input type="text" name="tel" value="{{ old('tel', $user->tel) }}"
               placeholder="+229 XX XX XX XX"
               style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:14px;color:#1e293b;box-sizing:border-box">
      </div>

      <button type="submit" style="padding:11px 24px;background:#185FA5;color:#fff;border:none;border-radius:8px;font-weight:700;font-size:14px;cursor:pointer">
        Enregistrer les modifications
      </button>
    </form>
  </div>

  <div style="background:#fff;border:1px solid #fde8e8;border-radius:14px;padding:22px 28px;max-width:540px;margin-top:20px">
    <h3 style="color:#c53030;font-size:15px;font-weight:700;margin:0 0 8px">Zone de danger</h3>
    <p style="font-size:13px;color:#64748b;margin:0 0 14px">La suppression de votre compte est irréversible et entraîne la perte de toutes vos données.</p>
    <button type="button" style="padding:9px 18px;background:transparent;border:1.5px solid #e53e3e;color:#e53e3e;border-radius:8px;font-weight:600;font-size:13px;cursor:pointer"
            onclick="return confirm('Êtes-vous sûr de vouloir supprimer définitivement votre compte ?')">
      Supprimer mon compte
    </button>
  </div>
</div>
@endsection
