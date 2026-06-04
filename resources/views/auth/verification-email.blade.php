@extends('layouts.auth')
@section('title', 'Vérification OTP — Emploi Bouge Bénin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/verification-email.css') }}">
@endsection

@section('content')

@php $email = session('otp_email'); @endphp

@if(!$email)
  {{-- Session perdue : redirection douce --}}
  <div style="display:flex;align-items:center;justify-content:center;min-height:100vh;flex-direction:column;gap:16px;font-family:var(--font-body)">
    <p style="color:#64748b">Session expirée. Veuillez recommencer.</p>
    <a href="{{ route('auth.connexion') }}" style="color:#185FA5;font-weight:700;">← Retour à la connexion</a>
  </div>
@else

<div class="auth-page">

  {{-- Panneau gauche --}}
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
      <div class="auth-panel__tag">Vérification</div>
      <h2 class="auth-panel__title">Vérifiez<br>votre <span>e-mail</span></h2>
      <p class="auth-panel__desc">
        Un code à 6 chiffres a été envoyé à votre adresse.
        Il est valable pendant <strong style="color:#F5C842">10 minutes</strong>.
      </p>
      <div class="auth-panel__steps">
        <div class="auth-step">
          <span class="auth-step__num">1</span>
          <span class="auth-step__label">Ouvrez votre boîte e-mail</span>
        </div>
        <div class="auth-step">
          <span class="auth-step__num">2</span>
          <span class="auth-step__label">Copiez le code à 6 chiffres</span>
        </div>
        <div class="auth-step">
          <span class="auth-step__num">3</span>
          <span class="auth-step__label">Collez-le ici et accédez à votre espace</span>
        </div>
      </div>
    </div>

    <div class="auth-panel__footer">© {{ date('Y') }} Emploi Bouge Bénin</div>
  </div>

  {{-- Panneau droit --}}
  <div class="auth-form-panel">
    <div class="auth-form-wrap">

      <a href="{{ route('auth.connexion') }}" class="auth-form-wrap__back">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        Retour
      </a>

      {{-- Icône enveloppe animée --}}
      <div class="ve-icon-wrap">
        <div class="ve-envelope">
          <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="1.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
          </svg>
        </div>
        <div class="ve-ping"></div>
      </div>

      <h1 class="auth-form-wrap__title">Code envoyé !</h1>

      {{-- Badge email --}}
      <div class="ve-email-badge">✉ {{ $email }}</div>

      {{-- Message renvoi --}}
      @if(session('resent'))
        <div class="ve-toast">
          <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
          Nouveau code envoyé avec succès !
        </div>
      @endif

      {{-- Code OTP en dev --}}
      @if(session('otp_debug'))
        <div class="ve-debug-box">
          <span>⚠ Dev — Code OTP :</span>
          <code id="devCode">{{ session('otp_debug') }}</code>
          <button type="button" onclick="copierCode()" class="ve-debug-copy">Copier</button>
        </div>
      @endif

      {{-- Formulaire 6 cases --}}
      <form method="POST" action="{{ route('auth.verification.otp') }}" id="otpForm">
        @csrf
        <input type="hidden" name="code" id="codeHidden">

        <div class="otp-inputs" id="otpInputs">
          @for($i = 0; $i < 6; $i++)
            <input type="text" inputmode="numeric" pattern="[0-9]" maxlength="1"
                   class="otp-input {{ $errors->has('code') ? 'otp-input--error' : '' }}"
                   autocomplete="{{ $i === 0 ? 'one-time-code' : 'off' }}"
                   {{ $i === 0 ? 'autofocus' : '' }}>
          @endfor
        </div>

        @error('code')
          <p class="ve-error">{{ $message }}</p>
        @enderror

        {{-- Countdown --}}
        <div class="ve-countdown" id="countdown">
          <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"/></svg>
          Expire dans <span id="timer">10:00</span>
        </div>

        <button type="submit" class="aform__submit" id="submitBtn" disabled>
          Confirmer le code
        </button>
      </form>

      <div class="ve-divider"></div>

      {{-- Renvoyer le code --}}
      <form method="POST" action="{{ route('auth.verification.renvoyer') }}" id="resendForm">
        @csrf
        <button type="submit" class="ve-resend-btn" id="resendBtn" disabled>
          <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
          <span id="resendLabel">Renvoyer un code (<span id="resendTimer">30</span>s)</span>
        </button>
      </form>

      <div class="ve-actions">
        <a href="{{ route('auth.connexion') }}" class="ve-link">
          <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
          Changer d'adresse e-mail
        </a>
      </div>

    </div>
  </div>

</div>

@endif
@endsection

@section('scripts')
<script>
(function () {
  /* ── Cases OTP ── */
  const inputs   = Array.from(document.querySelectorAll('.otp-input'));
  const hidden   = document.getElementById('codeHidden');
  const submitBtn = document.getElementById('submitBtn');
  const form     = document.getElementById('otpForm');

  function syncHidden() {
    const val = inputs.map(i => i.value).join('');
    hidden.value = val;
    submitBtn.disabled = val.length < 6;
  }

  inputs.forEach((input, idx) => {
    input.addEventListener('input', e => {
      const v = e.target.value.replace(/\D/g, '');
      input.value = v.slice(-1);
      if (v && idx < 5) inputs[idx + 1].focus();
      syncHidden();
      if (hidden.value.length === 6) form.submit();
    });

    input.addEventListener('keydown', e => {
      if (e.key === 'Backspace' && !input.value && idx > 0) {
        inputs[idx - 1].focus();
        inputs[idx - 1].value = '';
        syncHidden();
      }
    });

    input.addEventListener('paste', e => {
      e.preventDefault();
      const text = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '');
      text.split('').slice(0, 6).forEach((ch, i) => { if (inputs[i]) inputs[i].value = ch; });
      syncHidden();
      const next = Math.min(text.length, 5);
      inputs[next].focus();
      if (hidden.value.length === 6) form.submit();
    });
  });

  /* ── Countdown 10 min ── */
  let seconds = 10 * 60;
  const timerEl = document.getElementById('timer');
  const countdownEl = document.getElementById('countdown');

  const tick = setInterval(() => {
    seconds--;
    const m = String(Math.floor(seconds / 60)).padStart(2, '0');
    const s = String(seconds % 60).padStart(2, '0');
    if (timerEl) timerEl.textContent = m + ':' + s;
    if (seconds <= 0) {
      clearInterval(tick);
      if (timerEl) timerEl.textContent = '00:00';
      if (countdownEl) countdownEl.classList.add('ve-countdown--expired');
      submitBtn.disabled = true;
      submitBtn.textContent = 'Code expiré — renvoyez-en un';
    }
  }, 1000);

  /* ── Resend cooldown 30s ── */
  const resendBtn   = document.getElementById('resendBtn');
  const resendTimer = document.getElementById('resendTimer');
  const resendLabel = document.getElementById('resendLabel');
  let cooldown = 30;

  const resendTick = setInterval(() => {
    cooldown--;
    if (resendTimer) resendTimer.textContent = cooldown;
    if (cooldown <= 0) {
      clearInterval(resendTick);
      resendBtn.disabled = false;
      resendLabel.innerHTML = 'Renvoyer un code';
    }
  }, 1000);

  /* ── Copier code dev ── */
  window.copierCode = function () {
    const code = document.getElementById('devCode')?.textContent.trim();
    if (!code) return;
    navigator.clipboard.writeText(code).then(() => {
      code.split('').forEach((ch, i) => { if (inputs[i]) inputs[i].value = ch; });
      syncHidden();
      if (hidden.value.length === 6) setTimeout(() => form.submit(), 300);
    });
  };
})();
</script>
@endsection
