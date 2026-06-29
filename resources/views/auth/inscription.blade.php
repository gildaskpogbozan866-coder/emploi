@extends('layouts.auth')
@section('title', 'Inscription | Emploi Bouge Bénin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/inscription.css') }}">
@endsection

@section('content')
<div class="auth-page">

  <div class="auth-panel" id="leftPanel">
    <a href="{{ route('home') }}" class="auth-panel__logo">
      <span class="auth-panel__logo-icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
        </svg>
      </span>
      <span class="auth-panel__logo-text">Emploi Bouge Bénin</span>
    </a>
    <div class="auth-panel__body">
      <div class="auth-panel__tag">Inscription gratuite</div>
      <h2 class="auth-panel__title">Rejoignez des<br>milliers de <span>candidats</span>.</h2>
      <p class="auth-panel__desc">Créez votre compte en 2 minutes et accédez à toutes les opportunités d'emploi.</p>
      <div class="auth-panel__perks">
        <div class="auth-panel__perk"><span class="auth-panel__perk-icon"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span> Accès à toutes les offres vérifiées</div>
        <div class="auth-panel__perk"><span class="auth-panel__perk-icon"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span> Alertes emploi personnalisées</div>
        <div class="auth-panel__perk"><span class="auth-panel__perk-icon"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span> Profil visible par les recruteurs</div>
        <div class="auth-panel__perk"><span class="auth-panel__perk-icon"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg></span> Version Premium disponible</div>
      </div>
    </div>
    <div class="auth-panel__footer">© {{ date('Y') }} Emploi Bouge Bénin</div>
  </div>

  <div class="auth-form-panel">
    <div class="auth-form-wrap">
      <a href="{{ route('home') }}" class="auth-form-wrap__back">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
        </svg>
        Retour à l'accueil
      </a>

      <h1 class="auth-form-wrap__title">Créez votre<br>compte gratuit</h1>
      <p class="auth-form-wrap__sub">Choisissez votre type de compte, puis connectez-vous avec Google ou remplissez le formulaire.</p>

      <form class="aform" method="POST" action="{{ route('auth.inscription.store') }}">
        @csrf

        {{-- Sélecteur de rôle --}}
        <div class="aform__field">
          <label class="aform__label">Je suis…</label>
          <div class="role-grid">

            <button type="button" data-role="candidat" class="role-card {{ old('role','candidat') === 'candidat' ? 'selected' : '' }}" onclick="selectRole('candidat')">
              <div class="role-card__icon">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
              </div>
              <div class="role-card__body">
                <div class="role-card__label">Candidat</div>
                <div class="role-card__desc">Je cherche un emploi</div>
              </div>
              <div class="role-card__check"></div>
            </button>

            <button type="button" data-role="recruteur" class="role-card {{ old('role') === 'recruteur' ? 'selected' : '' }}" onclick="selectRole('recruteur')">
              <div class="role-card__icon">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>
              </div>
              <div class="role-card__body">
                <div class="role-card__label">Recruteur</div>
                <div class="role-card__desc">Je recrute des candidats</div>
              </div>
              <div class="role-card__check"></div>
            </button>

            <button type="button" data-role="annonceur" class="role-card {{ old('role') === 'annonceur' ? 'selected' : '' }}" onclick="selectRole('annonceur')">
              <div class="role-card__icon">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="13" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
              </div>
              <div class="role-card__body">
                <div class="role-card__label">Annonceur</div>
                <div class="role-card__desc">Je publie des affiches publicitaires</div>
              </div>
              <div class="role-card__check"></div>
            </button>

          </div>
          <input type="hidden" name="role" id="roleInput" value="{{ old('role', 'candidat') }}" />
        </div>

        {{-- Google OAuth --}}
        @if(config('services.google.client_id'))
        <a href="{{ route('auth.google') }}?role={{ old('role', 'candidat') }}" id="google-btn-inscription" class="google-btn">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" style="flex-shrink:0">
            <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
            <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
            <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
            <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
          </svg>
          Continuer avec Google
        </a>
        <div class="auth-divider"><span>ou remplir le formulaire</span></div>
        @endif

        <div class="aform__row">
          <div class="aform__field">
            <label class="aform__label" for="prenom">Prénom</label>
            <input class="aform__input @error('prenom') aform__input--error @enderror" type="text" id="prenom" name="prenom" value="{{ old('prenom') }}" placeholder="Kokou" required />
            @error('prenom')<p class="field__server-error">{{ $message }}</p>@enderror
          </div>
          <div class="aform__field">
            <label class="aform__label" for="nom">Nom</label>
            <input class="aform__input @error('nom') aform__input--error @enderror" type="text" id="nom" name="nom" value="{{ old('nom') }}" placeholder="Amoussou" required />
            @error('nom')<p class="field__server-error">{{ $message }}</p>@enderror
          </div>
        </div>

        <div class="aform__field">
          <label class="aform__label" for="email">Adresse e-mail</label>
          <input class="aform__input @error('email') aform__input--error @enderror"
                 type="email" id="email" name="email"
                 value="{{ old('email') }}" placeholder="vous@exemple.bj" required />
          @error('email')<p class="aform__error">{{ $message }}</p>@enderror
        </div>

        <div class="aform__row">
          <div class="aform__field">
            <label class="aform__label" for="password">Mot de passe</label>
            <input class="aform__input @error('password') aform__input--error @enderror"
                   type="password" id="password" name="password"
                   placeholder="Min. 8 caractères" required autocomplete="new-password" />
            @error('password')<p class="aform__error">{{ $message }}</p>@enderror
          </div>
          <div class="aform__field">
            <label class="aform__label" for="password_confirmation">Confirmer le mot de passe</label>
            <input class="aform__input @error('password_confirmation') aform__input--error @enderror"
                   type="password" id="password_confirmation" name="password_confirmation"
                   placeholder="Répétez le mot de passe" required autocomplete="new-password" />
            @error('password_confirmation')<p class="aform__error">{{ $message }}</p>@enderror
          </div>
        </div>

        {{-- <div class="aform__field">
          <label class="aform__label" for="pays">Pays</label>
          <select class="aform__input aform__select @error('pays') aform__input--error @enderror" id="pays" name="pays" required>
            <option value="">-- Sélectionnez votre pays --</option>
            @foreach($paysList as $p)
              <option value="{{ $p }}" {{ old('pays', 'Bénin') === $p ? 'selected' : '' }}>{{ $p }}</option>
            @endforeach
          </select>
          @error('pays')<p class="field__server-error">{{ $message }}</p>@enderror
        </div>

        <div class="aform__field">
          <label class="aform__label" for="tel">Téléphone</label>
          <div class="aform__tel-wrap">
            <span id="tel-prefix" class="aform__tel-prefix">+229</span>
            <input class="aform__input aform__input--tel" type="tel" id="tel" name="tel"
                   value="{{ old('tel') ? preg_replace('/^\+\d+\s*/', '', old('tel')) : '' }}"
                   placeholder="01 00 00 00" />
          </div>
        </div> --}}

        <div class="aform__field" id="entrepriseField" style="{{ old('role') === 'recruteur' ? '' : 'display:none' }}">
          <label class="aform__label" for="entreprise">Nom de l'entreprise</label>
          <input class="aform__input" type="text" id="entreprise" name="entreprise" value="{{ old('entreprise') }}" placeholder="Ex : TechBénin SARL" />
        </div>

        <label class="aform__check">
          <input type="checkbox" required />
          J'accepte les <a href="/legale/conditions-generales-utilisation" target="__blank">conditions d'utilisation</a> et la <a href="/legale/politique-confidentialite" target="__blank">politique de confidentialité</a>.
        </label>

        @if($recaptchaActif)
          <div class="g-recaptcha" data-sitekey="{{ $recaptchaSiteKey }}" style="margin-bottom:12px"></div>
        @endif
        @error('recaptcha')
          <p style="color:#e53e3e;font-size:13px;margin-bottom:8px">{{ $message }}</p>
        @enderror

        <button type="submit" class="aform__submit">Créer mon compte gratuitement</button>

        <p class="aform__switch">
          Déjà un compte ? <a href="{{ route('auth.connexion') }}">Se connecter</a>
        </p>
      </form>
    </div>
  </div>

</div>
@endsection

@section('scripts')
<script>
const roleLabels = { candidat: 'Candidat', recruteur: 'Recruteur', annonceur: 'Panneaux publicitaire', talent: 'Talent' };

function selectRole(role) {
  document.querySelectorAll('.role-card').forEach(c => c.classList.remove('selected'));
  document.querySelector('.role-card[data-role="' + role + '"]')?.classList.add('selected');
  document.getElementById('roleInput').value = role;
  document.getElementById('entrepriseField').style.display = role === 'recruteur' ? '' : 'none';
  var googleBtn = document.getElementById('google-btn-inscription');
  if (googleBtn) googleBtn.href = '{{ route("auth.google") }}?role=' + role;
}

// Auto-sélection depuis le paramètre URL ?role=
(function () {
  var urlRole = new URLSearchParams(window.location.search).get('role');
  if (urlRole && document.querySelector('.role-card[data-role="' + urlRole + '"]')) {
    selectRole(urlRole);
  } else {
    var current = document.getElementById('roleInput').value;
    if (current) selectRole(current);
  }
})();

// rôle JS reste ici — tel géré par tel-field.js
</script>
<script src="{{ asset('js/tel-field.js') }}"></script>
<script>initTelField('pays', 'tel-prefix', 'tel');</script>
@endsection
