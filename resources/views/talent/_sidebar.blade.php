@php $profil = auth()->user()->talentProfil; @endphp

<a href="{{ route('home') }}" class="cand-sidebar__logo">
  <span>Emploi Bouge</span><small>Bénin · Talent</small>
</a>

<div class="cand-sidebar__user">
  <div class="cand-sidebar__avatar">
    @if($profil?->photo)
      <img src="{{ asset('storage/'.$profil->photo) }}" alt="Photo" style="width:42px;height:42px;border-radius:50%;object-fit:cover">
    @else
      {{ auth()->user()->initiale }}
    @endif
  </div>
  <div class="cand-sidebar__info">
    <div class="cand-sidebar__name">{{ auth()->user()->nom_complet }}</div>
    <div class="cand-sidebar__role">{{ $profil?->metier ?? 'Talent' }}</div>
    @if($profil?->plan === 'premium')
      <span class="cand-sidebar__badge">Premium ★</span>
    @else
      <span class="cand-sidebar__badge cand-sidebar__badge--free">Gratuit</span>
    @endif
  </div>
</div>


<nav class="cand-nav" id="talentNav">
  <div class="cand-nav__section">Principal</div>

  <a href="{{ route('talent.dashboard') }}" class="cand-nav__item {{ request()->routeIs('talent.dashboard') ? 'active' : '' }}">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
    </svg>
    Tableau de bord
  </a>

  <a href="{{ route('offre.list') }}" class="cand-nav__item">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
    </svg>
    Parcourir les offres
  </a>

  <div class="cand-nav__divider"></div>
  <div class="cand-nav__section">Mon profil</div>

  <a href="{{ route('talent.profil') }}" class="cand-nav__item {{ request()->routeIs('talent.profil*') ? 'active' : '' }}">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
    </svg>
    Mon profil
  </a>

  <div class="cand-nav__divider"></div>
  <div class="cand-nav__section">Communication</div>

  <a href="{{ route('talent.messagerie') }}" class="cand-nav__item {{ request()->routeIs('talent.messagerie*') ? 'active' : '' }}">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
    </svg>
    Messagerie
  </a>

  <div class="cand-nav__divider"></div>
  <div class="cand-nav__section">Compte</div>

  <a href="{{ route('talent.abonnement') }}" class="cand-nav__item {{ request()->routeIs('talent.abonnement*') ? 'active' : '' }}">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
    </svg>
    Abonnement Premium
  </a>

  <a href="{{ route('talent.parametres') }}" class="cand-nav__item {{ request()->routeIs('talent.parametres*') ? 'active' : '' }}">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
    </svg>
    Paramètres
  </a>
</nav>
