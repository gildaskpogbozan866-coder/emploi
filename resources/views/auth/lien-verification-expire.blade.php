@extends('layouts.auth')
@section('title', 'Lien expiré | Emploi Bouge Bénin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/connexion.css') }}">
@endsection

@section('content')
<div class="auth-page">

  <div class="auth-panel">
    <a href="{{ route('home') }}" class="auth-panel__logo">
      <span class="auth-panel__logo-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
      </span>
      <span class="auth-panel__logo-text">Emploi Bouge Bénin</span>
    </a>
    <div class="auth-panel__body">
      <div class="auth-panel__tag">Vérification e-mail</div>
      <h2 class="auth-panel__title">Ce lien a<br>expiré</h2>
      <p class="auth-panel__desc">Les liens de vérification sont valides <strong>60 minutes</strong>. Demandez-en un nouveau pour activer votre compte.</p>
    </div>
    <div class="auth-panel__footer">© {{ date('Y') }} Emploi Bouge Bénin</div>
  </div>

  <div class="auth-form-panel">
    <div class="auth-form-wrap">

      {{-- Icône expiration --}}
      <div style="width:64px;height:64px;border-radius:50%;background:#FFF3CD;border:2px solid #F5C842;display:flex;align-items:center;justify-content:center;margin:0 auto 24px">
        <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="#d97706" stroke-width="2">
          <circle cx="12" cy="12" r="10"/>
          <line x1="12" y1="8" x2="12" y2="12" stroke-linecap="round"/>
          <line x1="12" y1="16" x2="12.01" y2="16" stroke-linecap="round" stroke-width="2.5"/>
        </svg>
      </div>

      <h1 class="auth-form-wrap__title" style="text-align:center">Lien de vérification expiré</h1>
      <p class="auth-form-wrap__sub" style="text-align:center">
        Ce lien n'est plus valide. Les liens de confirmation expirent après <strong>60 minutes</strong>.
        <br>Demandez un nouveau lien ci-dessous.
      </p>

      @auth
        {{-- Utilisateur connecté : proposer renvoi direct --}}
        <div style="background:#f0f7ff;border:1px solid #bfdbfe;border-radius:12px;padding:20px;text-align:center;margin-bottom:20px">
          <p style="font-size:.85rem;color:#374151;margin:0 0 14px">
            Connecté en tant que <strong>{{ auth()->user()->email }}</strong>
          </p>
          <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit"
              style="background:#042C53;color:#fff;border:none;border-radius:8px;padding:12px 28px;font-size:.9rem;cursor:pointer;font-weight:600;width:100%">
              Recevoir un nouveau lien de vérification
            </button>
          </form>
        </div>
        <form method="POST" action="{{ route('auth.deconnecter') }}" style="text-align:center">
          @csrf
          <button type="submit"
            style="background:none;border:none;color:#6b7280;font-size:.82rem;cursor:pointer;text-decoration:underline">
            Se déconnecter et utiliser un autre compte
          </button>
        </form>
      @else
        {{-- Visiteur non connecté : inviter à se connecter --}}
        <div style="background:#f0f7ff;border:1px solid #bfdbfe;border-radius:12px;padding:20px;text-align:center;margin-bottom:20px">
          <p style="font-size:.85rem;color:#374151;margin:0 0 14px">
            Connectez-vous à votre compte pour recevoir un nouveau lien.
          </p>
          <a href="{{ route('auth.connexion') }}"
            style="display:block;background:#042C53;color:#fff;text-decoration:none;border-radius:8px;padding:12px 28px;font-size:.9rem;font-weight:600">
            Se connecter
          </a>
        </div>
        <div style="text-align:center">
          <a href="{{ route('home') }}" style="color:#6b7280;font-size:.82rem;text-decoration:underline">
            Retour à l'accueil
          </a>
        </div>
      @endauth

    </div>
  </div>

</div>
@endsection
