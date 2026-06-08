@extends('layouts.auth')
@section('title', 'Vérifiez votre e-mail — Emploi Bouge Bénin')

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
      <h2 class="auth-panel__title">Plus qu'une<br>étape !</h2>
      <p class="auth-panel__desc">Confirmez votre adresse e-mail pour activer votre compte et accéder à la plateforme.</p>
      <div class="auth-panel__steps">
        <div class="auth-step">
          <span class="auth-step__num">1</span>
          <span class="auth-step__label">Ouvrez votre boîte e-mail</span>
        </div>
        <div class="auth-step">
          <span class="auth-step__num">2</span>
          <span class="auth-step__label">Cliquez sur le lien de confirmation</span>
        </div>
        <div class="auth-step">
          <span class="auth-step__num">3</span>
          <span class="auth-step__label">Accédez à votre espace</span>
        </div>
      </div>
    </div>
    <div class="auth-panel__footer">© {{ date('Y') }} Emploi Bouge Bénin</div>
  </div>

  <div class="auth-form-panel">
    <div class="auth-form-wrap">

      <div style="width:64px;height:64px;border-radius:50%;background:linear-gradient(135deg,#2563eb,#1d4ed8);display:flex;align-items:center;justify-content:center;margin:0 auto 24px">
        <svg width="30" height="30" fill="none" viewBox="0 0 24 24" stroke="#fff" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
      </div>

      <h1 class="auth-form-wrap__title" style="text-align:center">Vérifiez votre<br>adresse e-mail</h1>
      <p class="auth-form-wrap__sub" style="text-align:center">
        Un lien de confirmation a été envoyé à <strong>{{ auth()->user()->email }}</strong>.<br>
        Cliquez sur ce lien pour activer votre compte.
      </p>

      @if(session('resent'))
        <div style="background:#d1fae5;border:1px solid #6ee7b7;border-radius:8px;padding:12px 16px;font-size:.85rem;color:#065f46;margin-bottom:20px;text-align:center">
          E-mail renvoyé avec succès. Vérifiez votre boîte de réception (et vos spams).
        </div>
      @endif

      <div style="background:#f0f7ff;border:1px solid #bfdbfe;border-radius:12px;padding:20px;text-align:center;margin-bottom:24px">
        <p style="font-size:.85rem;color:#374151;margin:0 0 14px">Vous n'avez pas reçu l'e-mail ?</p>
        <form method="POST" action="{{ route('verification.send') }}">
          @csrf
          <button type="submit" style="background:#2563eb;color:#fff;border:none;border-radius:8px;padding:10px 24px;font-size:.9rem;cursor:pointer;font-weight:600">
            Renvoyer le lien de confirmation
          </button>
        </form>
      </div>

      <form method="POST" action="{{ route('auth.deconnecter') }}" style="text-align:center">
        @csrf
        <button type="submit" style="background:none;border:none;color:#6b7280;font-size:.85rem;cursor:pointer;text-decoration:underline">
          Se déconnecter et utiliser un autre compte
        </button>
      </form>

    </div>
  </div>

</div>
@endsection
