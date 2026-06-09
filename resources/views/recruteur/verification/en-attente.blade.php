@extends('layouts.auth')
@section('title', 'Dossier en cours d\'examen — Emploi Bouge Bénin')

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
      <div class="auth-panel__tag">En cours d'examen</div>
      <h2 class="auth-panel__title">Votre dossier<br>est entre de <span>bonnes mains</span>.</h2>
      <p class="auth-panel__desc">Notre équipe examine votre dossier dans les meilleurs délais. Vous recevrez une réponse par e-mail sous 24–48h ouvrables.</p>
      <div class="auth-panel__perks">
        <div class="auth-panel__perk"><span class="auth-panel__perk-icon"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span> Dossier reçu et enregistré</div>
        <div class="auth-panel__perk"><span class="auth-panel__perk-icon"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span> Examen sous 24–48h ouvrables</div>
        <div class="auth-panel__perk"><span class="auth-panel__perk-icon"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span> Notification par e-mail dès validation</div>
      </div>
    </div>
    <div class="auth-panel__footer">© {{ date('Y') }} Emploi Bouge Bénin</div>
  </div>

  <div class="auth-form-panel">
    <div class="auth-form-wrap" style="text-align:center">

      <div style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,#f59e0b,#d97706);display:flex;align-items:center;justify-content:center;margin:0 auto 24px;position:relative">
        <svg width="34" height="34" fill="none" viewBox="0 0 24 24" stroke="#fff" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
      </div>

      <h1 class="auth-form-wrap__title">Dossier en cours<br>d'examen</h1>
      <p class="auth-form-wrap__sub">
        Bienvenue, <strong>{{ auth()->user()->prenom }}</strong>.<br>
        Votre dossier de vérification a bien été reçu. Notre équipe le valide actuellement.
      </p>

      <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:12px;padding:20px;margin:24px 0;text-align:left">
        <div style="font-weight:600;color:#92400e;font-size:.88rem;margin-bottom:8px">Que se passe-t-il maintenant ?</div>
        <ul style="margin:0;padding-left:18px;color:#78350f;font-size:.84rem;line-height:1.8">
          <li>Notre équipe examine vos documents dans les 24–48h ouvrables.</li>
          <li>Vous recevrez un e-mail de confirmation à <strong>{{ auth()->user()->email }}</strong>.</li>
          <li>Dès validation, votre tableau de bord recruteur sera pleinement accessible.</li>
        </ul>
      </div>

      <p style="font-size:.82rem;color:#6b7280;margin-bottom:24px">
        Une question ? Contactez-nous à <a href="mailto:support@emploibougbenin.com" style="color:#2563eb">support@emploibouge.bj</a>
      </p>

      <a href="{{ route('home') }}" style="display:inline-block;background:#f1f5f9;color:#374151;border-radius:8px;padding:10px 24px;font-size:.88rem;text-decoration:none;margin-bottom:12px">
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
