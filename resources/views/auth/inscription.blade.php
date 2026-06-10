@extends('layouts.auth')
@section('title', 'Inscription — Emploi Bouge Bénin')

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
      <p class="auth-form-wrap__sub">Choisissez votre type de compte puis remplissez le formulaire.</p>

      <form class="aform" method="POST" action="{{ route('auth.inscription.store') }}">
        @csrf

        {{-- Sélecteur de rôle --}}
        <div class="aform__field">
          <label class="aform__label">Je suis…</label>
          <div class="role-grid">
            <button type="button" data-role="candidat" class="role-card {{ old('role','candidat') === 'candidat' ? 'selected' : '' }}" onclick="selectRole('candidat')">
              <div class="role-card__icon">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
              </div>
              <div class="role-card__label">Candidat</div>
              <div class="role-card__desc">Je cherche un emploi</div>
            </button>
            <button type="button" data-role="recruteur" class="role-card {{ old('role') === 'recruteur' ? 'selected' : '' }}" onclick="selectRole('recruteur')">
              <div class="role-card__icon">
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>
              </div>
              <div class="role-card__label">Recruteur</div>
              <div class="role-card__desc">Je recrute des candidats</div>
            </button>
          </div>
          <input type="hidden" name="role" id="roleInput" value="{{ old('role', 'candidat') }}" />
        </div>

        <div class="aform__row">
          <div class="aform__field">
            <label class="aform__label" for="prenom">Prénom</label>
            <input class="aform__input" type="text" id="prenom" name="prenom" value="{{ old('prenom') }}" placeholder="Jean" required />
          </div>
          <div class="aform__field">
            <label class="aform__label" for="nom">Nom</label>
            <input class="aform__input" type="text" id="nom" name="nom" value="{{ old('nom') }}" placeholder="Dupont" required />
          </div>
        </div>

        <div class="aform__field">
          <label class="aform__label" for="email">Adresse e-mail</label>
          <input class="aform__input @error('email') aform__input--error @enderror"
                 type="email" id="email" name="email"
                 value="{{ old('email') }}" placeholder="vous@exemple.com" required />
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

        <div class="aform__field">
          <label class="aform__label" for="tel">Téléphone</label>
          <input class="aform__input" type="tel" id="tel" name="tel" value="{{ old('tel') }}" placeholder="+229 01 00 00 00" />
        </div>

        <div class="aform__field">
          <label class="aform__label" for="pays">Pays</label>
          <select class="aform__input aform__select" id="pays" name="pays" required>
            <option value="">-- Sélectionnez votre pays --</option>
            @foreach(['Bénin','Côte d\'Ivoire','Sénégal','Cameroun','Togo','Mali','Burkina Faso','Niger','Guinée','Congo','Madagascar','Autre'] as $pays)
              <option value="{{ $pays }}" {{ old('pays') === $pays ? 'selected' : '' }}>{{ $pays }}</option>
            @endforeach
          </select>
        </div>

        <div class="aform__field" id="entrepriseField" style="{{ old('role') === 'recruteur' ? '' : 'display:none' }}">
          <label class="aform__label" for="entreprise">Nom de l'entreprise</label>
          <input class="aform__input" type="text" id="entreprise" name="entreprise" value="{{ old('entreprise') }}" placeholder="Ex : TechBénin SARL" />
        </div>

        <label class="aform__check">
          <input type="checkbox" required />
          J'accepte les <a href="/legale/cgv">conditions d'utilisation</a> et la <a href="/legale/politique-confidentialite">politique de confidentialité</a>.
        </label>

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
function selectRole(role) {
  document.querySelectorAll('.role-card').forEach(c => c.classList.remove('selected'));
  document.querySelector('.role-card[data-role="' + role + '"]')?.classList.add('selected');
  document.getElementById('roleInput').value = role;
  document.getElementById('entrepriseField').style.display = role === 'recruteur' ? '' : 'none';
}
</script>
@endsection
