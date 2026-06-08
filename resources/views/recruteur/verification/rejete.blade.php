@extends('layouts.auth')
@section('title', 'Dossier rejeté — Emploi Bouge Bénin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/compte-confirme.css') }}">
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
      <div class="auth-panel__tag">Dossier rejeté</div>
      <h2 class="auth-panel__title">Votre dossier<br>nécessite des <span>corrections</span>.</h2>
      <p class="auth-panel__desc">Lisez le motif de rejet, corrigez les points demandés, puis soumettez à nouveau votre dossier.</p>
    </div>
    <div class="auth-panel__footer">© {{ date('Y') }} Emploi Bouge Bénin</div>
  </div>

  <div class="auth-form-panel">
    <div class="auth-form-wrap" style="text-align:center">

      <div style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,#ef4444,#dc2626);display:flex;align-items:center;justify-content:center;margin:0 auto 24px">
        <svg width="34" height="34" fill="none" viewBox="0 0 24 24" stroke="#fff" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </div>

      <h1 class="auth-form-wrap__title">Dossier non validé</h1>
      <p class="auth-form-wrap__sub">
        Notre équipe a examiné votre dossier mais n'a pas pu le valider en l'état.
      </p>

      @if($verification?->note_admin)
        <div style="background:#fef2f2;border:1px solid #fca5a5;border-radius:12px;padding:20px;margin:20px 0;text-align:left">
          <div style="font-weight:700;color:#dc2626;font-size:.88rem;margin-bottom:8px;display:flex;align-items:center;gap:6px">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            Motif du rejet
          </div>
          <p style="color:#7f1d1d;font-size:.85rem;margin:0;line-height:1.6">{{ $verification->note_admin }}</p>
          @if($verification->reviewed_at)
            <p style="color:#9ca3af;font-size:.78rem;margin:10px 0 0">
              Examiné le {{ $verification->reviewed_at->format('d/m/Y à H:i') }}
            </p>
          @endif
        </div>
      @endif

      <a href="{{ route('recruteur.verification') }}"
         style="display:block;background:linear-gradient(135deg,#2563eb,#1d4ed8);color:#fff;border-radius:10px;padding:14px 24px;font-size:.95rem;font-weight:600;text-decoration:none;margin-bottom:12px">
        Corriger et resoumettre le dossier
      </a>

      <a href="{{ route('home') }}" style="display:block;background:#f1f5f9;color:#374151;border-radius:8px;padding:10px 24px;font-size:.88rem;text-decoration:none;margin-bottom:12px">
        Retour à l'accueil
      </a>

      <form method="POST" action="{{ route('auth.deconnecter') }}" style="margin-top:8px">
        @csrf
        <button type="submit" style="background:none;border:none;color:#6b7280;font-size:.82rem;cursor:pointer;text-decoration:underline">
          Se déconnecter
        </button>
      </form>

    </div>
  </div>

</div>
@endsection
