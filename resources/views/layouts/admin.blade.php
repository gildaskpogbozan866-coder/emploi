<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Administration') — Emploi Bouge Bénin</title>
  <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}" />
  <link rel="stylesheet" href="{{ asset('css/components.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/admin/admin.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/dashboard-layout.css') }}" />
  @yield('css')
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

  <header class="dash-header dash-header--admin">
    <div class="dash-header__inner">
      <div class="dash-header__left">
        <button class="dash-header__burger" id="admBurger" aria-label="Menu">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>
          </svg>
        </button>
        <a href="{{ route('home') }}" class="dash-header__logo">
          <img src="{{ asset('images/Logo.png') }}" alt="Emploi Bouge Bénin">
        </a>
      </div>
      <div class="dash-header__right">
        <div class="dash-header__user">
          <div class="dash-header__avatar">{{ auth()->user()->initiale }}</div>
          <span class="dash-header__username">{{ auth()->user()->nom_complet }}</span>
        </div>
        <form method="POST" action="{{ route('auth.deconnecter') }}" style="display:inline">
          @csrf
          <button type="submit" class="dash-header__logout">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            Déconnexion
          </button>
        </form>
      </div>
    </div>
  </header>

  <div class="adm-wrap">
    <div class="adm-overlay" id="admOverlay"></div>
    <aside class="adm-sidebar" id="admSidebar">
      <button class="adm-sidebar__close" id="admClose" aria-label="Fermer le menu">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
      <a href="{{ route('home') }}" class="adm-sidebar__logo">
        <span>Emploi Bouge</span><small>Bénin · Administration</small>
      </a>
      <div class="adm-sidebar__user">
        <div class="adm-sidebar__avatar">{{ auth()->user()->initiale }}</div>
        <div class="adm-sidebar__info">
          <div class="adm-sidebar__name">{{ auth()->user()->nom_complet }}</div>
          <div class="adm-sidebar__role">Super Admin</div>
        </div>
      </div>
      <ul class="adm-nav">
        <li class="adm-nav__section">Vue d'ensemble</li>
        <li class="adm-nav__item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
          <a href="{{ route('admin.dashboard') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
            Tableau de bord
          </a>
        </li>
        <li class="adm-nav__section">Utilisateurs</li>
        <li class="adm-nav__item {{ request()->routeIs('admin.verifications*') ? 'active' : '' }}">
          <a href="{{ route('admin.verifications.list') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12l2 2 4-4"/><path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9c1.51 0 2.93.37 4.18 1.02"/><path d="M16 5l2 2 4-4"/></svg>
            Vérifications recruteurs
            @php
              $enAttente = \App\Models\RecruteurVerification::where('statut','en_attente')->count();
            @endphp
            @if($enAttente > 0)
              <span style="background:#ef4444;color:#fff;font-size:.65rem;font-weight:700;padding:1px 6px;border-radius:99px;margin-left:auto">{{ $enAttente }}</span>
            @endif
          </a>
        </li>
        <li class="adm-nav__item {{ request()->routeIs('admin.utilisateurs.candidats*') ? 'active' : '' }}">
          <a href="{{ route('admin.utilisateurs.candidats') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
            Candidats
          </a>
        </li>
        <li class="adm-nav__item {{ request()->routeIs('admin.utilisateurs.recruteurs*') ? 'active' : '' }}">
          <a href="{{ route('admin.utilisateurs.recruteurs') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
            Recruteurs
          </a>
        </li>
        <li class="adm-nav__section">Contenu</li>
        <li class="adm-nav__item {{ request()->routeIs('admin.offres*') ? 'active' : '' }}">
          <a href="{{ route('admin.offres.list') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            Offres d'emploi
          </a>
        </li>
        <li class="adm-nav__item {{ request()->routeIs('admin.cvs*') || request()->routeIs('admin.documents*') ? 'active' : '' }}">
          <a href="{{ route('admin.cvs.list') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            CVs & Documents
          </a>
        </li>
        <li class="adm-nav__item {{ request()->routeIs('admin.blog*') ? 'active' : '' }}">
          <a href="{{ route('admin.blog.list') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            Blog
          </a>
        </li>
        <li class="adm-nav__item {{ request()->routeIs('admin.services*') ? 'active' : '' }}">
          <a href="{{ route('admin.services.list') }}">Services</a>
        </li>
        <li class="adm-nav__item {{ request()->routeIs('admin.commandes*') ? 'active' : '' }}">
          <a href="{{ route('admin.commandes.list') }}">Commandes</a>
        </li>
        <li class="adm-nav__section">Commerce</li>
        <li class="adm-nav__item {{ request()->routeIs('admin.paiements*') ? 'active' : '' }}">
          <a href="{{ route('admin.paiements.list') }}">Paiements</a>
        </li>
        <li class="adm-nav__item {{ request()->routeIs('admin.abonnements*') ? 'active' : '' }}">
          <a href="{{ route('admin.abonnements') }}">Abonnements souscrits</a>
        </li>
        <li class="adm-nav__item {{ request()->routeIs('admin.plans*') ? 'active' : '' }}">
          <a href="{{ route('admin.plans.list') }}">Plans d'abonnement</a>
        </li>
        <li class="adm-nav__section">Modération</li>
        <li class="adm-nav__item {{ request()->routeIs('admin.messagerie*') ? 'active' : '' }}">
          <a href="{{ route('admin.messagerie') }}">Messagerie</a>
        </li>
        <li class="adm-nav__item {{ request()->routeIs('admin.signalements*') ? 'active' : '' }}">
          <a href="{{ route('admin.signalements.list') }}">Signalements</a>
        </li>
        <li class="adm-nav__item {{ request()->routeIs('admin.statistiques*') ? 'active' : '' }}">
          <a href="{{ route('admin.statistiques') }}">Statistiques</a>
        </li>
        <li class="adm-nav__section">Configuration</li>
        @php
          $refRoutes = ['admin.competences*','admin.metiers*','admin.types-contrat*','admin.secteurs-activite*','admin.langues*','admin.niveaux-langue*','admin.niveaux-etude*','admin.niveaux-experience*'];
          $refActif  = request()->routeIs(...$refRoutes);
        @endphp
        <li class="adm-nav__item {{ request()->routeIs('admin.parametres*') ? 'active' : '' }}">
          <a href="{{ route('admin.parametres') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
            Paramètres
          </a>
        </li>
        <li class="adm-nav__item {{ $refActif ? 'active' : '' }}">
          <details {{ $refActif ? 'open' : '' }}>
            <summary>
              <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><line x1="3" y1="6" x2="3.01" y2="6"/><line x1="3" y1="12" x2="3.01" y2="12"/><line x1="3" y1="18" x2="3.01" y2="18"/></svg>
              Référentiels RH
              <svg class="adm-nav__chevron" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
            </summary>
            <ul class="adm-nav__sub">
              <li><a href="{{ route('admin.competences.index') }}" class="{{ request()->routeIs('admin.competences*') ? 'active' : '' }}">Compétences</a></li>
              <li><a href="{{ route('admin.metiers.index') }}" class="{{ request()->routeIs('admin.metiers*') ? 'active' : '' }}">Métiers</a></li>
              <li><a href="{{ route('admin.types-contrat.index') }}" class="{{ request()->routeIs('admin.types-contrat*') ? 'active' : '' }}">Types de contrat</a></li>
              <li><a href="{{ route('admin.secteurs-activite.index') }}" class="{{ request()->routeIs('admin.secteurs-activite*') ? 'active' : '' }}">Secteurs d'activité</a></li>
              <li><a href="{{ route('admin.langues.index') }}" class="{{ request()->routeIs('admin.langues*') ? 'active' : '' }}">Langues</a></li>
              <li><a href="{{ route('admin.niveaux-langue.index') }}" class="{{ request()->routeIs('admin.niveaux-langue*') ? 'active' : '' }}">Niveaux de langue</a></li>
              <li><a href="{{ route('admin.niveaux-etude.index') }}" class="{{ request()->routeIs('admin.niveaux-etude*') ? 'active' : '' }}">Niveaux d'étude</a></li>
              <li><a href="{{ route('admin.niveaux-experience.index') }}" class="{{ request()->routeIs('admin.niveaux-experience*') ? 'active' : '' }}">Niveaux d'expérience</a></li>
            </ul>
          </details>
        </li>
        <li class="adm-nav__section">Sécurité</li>
        <li class="adm-nav__item {{ request()->routeIs('admin.permissions*') ? 'active' : '' }}">
          <a href="{{ route('admin.permissions.index') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
            Rôles &amp; Permissions
          </a>
        </li>
      </ul>
    </aside>

    <main class="adm-main">
      @yield('content')
    </main>
  </div>

  <script>
    (function() {
      const burger   = document.getElementById('admBurger');
      const sidebar  = document.getElementById('admSidebar');
      const overlay  = document.getElementById('admOverlay');
      const closeBtn = document.getElementById('admClose');
      function openSidebar()  { sidebar.classList.add('open');    overlay.classList.add('active'); document.body.style.overflow = 'hidden'; }
      function closeSidebar() { sidebar.classList.remove('open'); overlay.classList.remove('active'); document.body.style.overflow = ''; }
      burger?.addEventListener('click', () => sidebar.classList.contains('open') ? closeSidebar() : openSidebar());
      closeBtn?.addEventListener('click', closeSidebar);
      overlay?.addEventListener('click', closeSidebar);
      document.addEventListener('keydown', e => { if (e.key === 'Escape') closeSidebar(); });
      sidebar?.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => { if (window.innerWidth <= 900) closeSidebar(); });
      });
    })();
  </script>
  @include('partials._form-guard')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
  @include('components.flash-swal')
  @yield('scripts')
</body>
</html>
