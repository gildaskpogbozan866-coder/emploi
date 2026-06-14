<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Espace Annonceur') — Emploi Bouge Bénin</title>
  <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}" />
  <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/candidat/candidat.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/dashboard-layout.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/components.css') }}" />
  @yield('css')
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

  <header class="dash-header dash-header--annonceur">
    <div class="dash-header__inner">
      <div class="dash-header__left">
        <button class="dash-header__burger" id="annBurger" aria-label="Menu">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>
          </svg>
        </button>
        <a href="{{ route('home') }}" class="dash-header__logo">
          <img src="{{ asset('images/Logo.png') }}" alt="Emploi Bouge Bénin">
        </a>
      </div>
      <div class="dash-header__right">
        @include('partials._notification-bell')
        <div class="dash-header__avatar" title="{{ auth()->user()->nom_complet }}">{{ auth()->user()->initiale }}</div>
        <form method="POST" action="{{ route('auth.deconnecter') }}" style="display:inline">
          @csrf
          <button type="submit" class="dash-header__logout" title="Se déconnecter" aria-label="Se déconnecter">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
          </button>
        </form>
      </div>
    </div>
  </header>

  <div class="cand-wrap">
    <div class="cand-overlay" id="annOverlay"></div>
    <aside class="cand-sidebar cand-sidebar--annonceur" id="annSidebar">
      <button class="cand-sidebar__close" id="annClose" aria-label="Fermer le menu">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>

      <div class="cand-sidebar__user">
        <div class="cand-sidebar__avatar">{{ auth()->user()->initiale }}</div>
        <div class="cand-sidebar__info">
          <div class="cand-sidebar__name">{{ auth()->user()->nom_complet }}</div>
          <div class="cand-sidebar__role">Annonceur</div>
        </div>
      </div>

      <nav class="cand-nav" id="annNav">
        <div class="cand-nav__section">Mon espace</div>

        <a href="{{ route('annonceur.dashboard') }}"
           class="cand-nav__item {{ request()->routeIs('annonceur.dashboard') ? 'active' : '' }}">
          <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/>
            <rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/>
          </svg>
          Tableau de bord
        </a>

        <div class="cand-nav__section">Publicités</div>

        <a href="{{ route('annonceur.publicites') }}"
           class="cand-nav__item {{ request()->routeIs('annonceur.publicites*') ? 'active' : '' }}">
          <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="18" height="13" rx="2"/><path d="M8 21h8M12 17v4"/>
          </svg>
          Mes annonces
        </a>

        <div class="cand-nav__divider"></div>

        <a href="{{ route('home') }}" class="cand-nav__item">
          <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
          </svg>
          Retour à l'accueil
        </a>
      </nav>
    </aside>

    <main class="cand-main">
      @yield('content')
    </main>
  </div>

  <script>
    (function() {
      const burger  = document.getElementById('annBurger');
      const sidebar = document.getElementById('annSidebar');
      const overlay = document.getElementById('annOverlay');
      const closeBtn = document.getElementById('annClose');
      function openSidebar()  { sidebar.classList.add('open');    overlay.classList.add('active'); document.body.style.overflow = 'hidden'; }
      function closeSidebar() { sidebar.classList.remove('open'); overlay.classList.remove('active'); document.body.style.overflow = ''; }
      burger?.addEventListener('click', () => sidebar.classList.contains('open') ? closeSidebar() : openSidebar());
      closeBtn?.addEventListener('click', closeSidebar);
      overlay?.addEventListener('click', closeSidebar);
      document.addEventListener('keydown', e => { if (e.key === 'Escape') closeSidebar(); });
    })();
  </script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
  @include('components.flash-swal')
  @yield('scripts')
</body>
</html>
