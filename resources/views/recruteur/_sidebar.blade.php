<a href="{{ route('home') }}" class="rec-sidebar__logo">
  <span>Emploi Bouge</span>
  <small>Bénin · Recruteur</small>
</a>

<div class="rec-sidebar__user">
  <div class="rec-sidebar__avatar">{{ auth()->user()->initiale }}</div>
  <div class="rec-sidebar__info">
    <div class="rec-sidebar__name">{{ auth()->user()->nom_complet }}</div>
    <div class="rec-sidebar__role">{{ auth()->user()->entreprise ?? 'Recruteur' }}</div>
    @if(auth()->user()->premium)
      <span class="rec-sidebar__badge">Premium ★</span>
    @else
      <span class="rec-sidebar__badge rec-sidebar__badge--free">Gratuit</span>
    @endif
  </div>
</div>

<ul class="rec-nav">
  <li class="rec-nav__section">Tableau de bord</li>

  <li class="rec-nav__item {{ request()->routeIs('recruteur.dashboard') ? 'active' : '' }}">
    <a href="{{ route('recruteur.dashboard') }}">
      <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
      </svg>
      Vue d'ensemble
    </a>
  </li>

  <li class="rec-nav__section">Recrutement</li>

  <li class="rec-nav__item {{ request()->routeIs('recruteur.offres*') ? 'active' : '' }}">
    <a href="{{ route('recruteur.offres') }}">
      <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/>
      </svg>
      Mes offres d'emploi
    </a>
  </li>

  <li class="rec-nav__item {{ request()->routeIs('recruteur.candidatures*') ? 'active' : '' }}">
    <a href="{{ route('recruteur.candidatures') }}">
      <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>
      </svg>
      Candidatures reçues
    </a>
  </li>

  <li class="rec-nav__item {{ request()->routeIs('recruteur.cvtheque*') ? 'active' : '' }}">
    <a href="{{ route('recruteur.cvtheque') }}">
      <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/>
      </svg>
      CVthèque
    </a>
  </li>

  <hr class="rec-nav__divider">
  <li class="rec-nav__section">Suivi</li>

  <li class="rec-nav__item {{ request()->routeIs('recruteur.statistiques*') ? 'active' : '' }}">
    <a href="{{ route('recruteur.statistiques') }}">
      <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/>
      </svg>
      Statistiques
    </a>
  </li>

  <li class="rec-nav__item {{ request()->routeIs('recruteur.messagerie*') ? 'active' : '' }}">
    <a href="{{ route('recruteur.messagerie') }}">
      <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
      </svg>
      Messagerie
    </a>
  </li>

  <hr class="rec-nav__divider">
  <li class="rec-nav__section">Compte</li>

  <li class="rec-nav__item {{ request()->routeIs('recruteur.abonnement*') ? 'active' : '' }}">
    <a href="{{ route('recruteur.abonnement') }}">
      <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
      </svg>
      Mon abonnement
    </a>
  </li>

  <li class="rec-nav__item {{ request()->routeIs('recruteur.profil*') ? 'active' : '' }}">
    <a href="{{ route('recruteur.profil') }}">
      <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>
      </svg>
      Mon profil
    </a>
  </li>

  <li class="rec-nav__item {{ request()->routeIs('recruteur.parametres*') ? 'active' : '' }}">
    <a href="{{ route('recruteur.parametres') }}">
      <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/>
      </svg>
      Paramètres
    </a>
  </li>
</ul>
