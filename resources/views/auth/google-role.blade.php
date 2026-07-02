@extends('layouts.auth')
@section('title', 'Choisir votre type de compte | Emploi Bouge Bénin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/inscription.css') }}">
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
      <div class="auth-panel__tag">Presque terminé !</div>
      <h2 class="auth-panel__title">Une dernière<br>étape.</h2>
      <p class="auth-panel__desc">Indiquez-nous comment vous souhaitez utiliser la plateforme pour personnaliser votre expérience.</p>
    </div>
    <div class="auth-panel__footer">© {{ date('Y') }} Emploi Bouge Bénin</div>
  </div>

  <div class="auth-form-panel">
    <div class="auth-form-wrap">

      {{-- Avatar Google --}}
      <div style="display:flex;align-items:center;gap:12px;margin-bottom:24px;padding:14px 16px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:12px">
        @if($googleData['avatar'])
          <img src="{{ $googleData['avatar'] }}" alt="" width="42" height="42" style="border-radius:50%;object-fit:cover;border:2px solid #e2e8f0">
        @endif
        <div>
          <p style="font-weight:700;color:#042C53;margin:0;font-size:14px">{{ $googleData['prenom'] }} {{ $googleData['nom'] }}</p>
          <p style="font-size:12px;color:#64748b;margin:0">{{ $googleData['email'] }}</p>
        </div>
        <div style="margin-left:auto">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
          </svg>
        </div>
      </div>

      <h1 class="auth-form-wrap__title" style="font-size:1.5rem">Quel est votre profil ?</h1>
      <p class="auth-form-wrap__sub">Choisissez comment vous souhaitez utiliser Emploi Bouge Bénin.</p>

      @error('role')
        <div style="background:#fef2f2;border:1px solid #fca5a5;border-radius:8px;padding:10px 14px;font-size:.85rem;color:#991b1b;margin-bottom:16px">
          {{ $message }}
        </div>
      @enderror

      <form method="POST" action="{{ route('auth.google.create') }}">
        @csrf
        <div class="aform__field">
          <div class="role-grid">
            <button type="button" data-role="candidat" class="role-card" onclick="selectRole('candidat')">
              <div class="role-card__icon">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
              </div>
              <div class="role-card__label">Candidat</div>
              <div class="role-card__desc">Je cherche un emploi</div>
            </button>
            <button type="button" data-role="recruteur" class="role-card" onclick="selectRole('recruteur')">
              <div class="role-card__icon">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>
              </div>
              <div class="role-card__label">Recruteur</div>
              <div class="role-card__desc">Je recrute des candidats</div>
            </button>
            <button type="button" data-role="annonceur" class="role-card" onclick="selectRole('annonceur')">
              <div class="role-card__icon">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="13" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
              </div>
              <div class="role-card__label">Panneaux publicitaire</div>
              <div class="role-card__desc">Je publie des affiches pub</div>
            </button>
          </div>
          <input type="hidden" name="role" id="roleInput" value="" />
        </div>

        <button type="submit" class="aform__submit" style="margin-top:8px">Créer mon compte →</button>
      </form>
    </div>
  </div>

</div>
@endsection

@section('scripts')
<script>
function selectRole(role) {
  document.querySelectorAll('.role-card').forEach(c => c.classList.remove('selected'));
  document.querySelector('.role-card[data-role="' + role + '"]')?.classList.add('selected');
  document.getElementById('roleInput').value = role;
}
</script>
@endsection
