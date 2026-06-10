<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Espace Talent') — Emploi Bouge Bénin</title>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}" />
  <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/candidat/candidat.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/dashboard-layout.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/components.css') }}" />
  @yield('css')
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

  <header class="dash-header dash-header--talent">
    <div class="dash-header__inner">
      <div class="dash-header__left">
        <button class="dash-header__burger" id="talentBurger" aria-label="Menu">
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
          <span class="dash-header__username">{{ auth()->user()->prenom }}</span>
        </div>
        <form method="POST" action="{{ route('auth.deconnecter') }}" style="display:inline">
          @csrf
          <button type="submit" class="dash-header__logout">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
            <span>Déconnexion</span>
          </button>
        </form>
      </div>
    </div>
  </header>

  <div class="cand-wrap">
    <div class="cand-overlay" id="talentOverlay"></div>
    <aside class="cand-sidebar cand-sidebar--talent" id="talentSidebar">
      <button class="cand-sidebar__close" id="talentClose" aria-label="Fermer le menu">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
      @include('talent._sidebar')
    </aside>
    <main class="cand-main">
      @include('components.flash')
      @yield('content')
    </main>
  </div>

  <script>
    (function() {
      const burger  = document.getElementById('talentBurger');
      const sidebar = document.getElementById('talentSidebar');
      const overlay = document.getElementById('talentOverlay');
      const closeBtn = document.getElementById('talentClose');
      function openSidebar()  { sidebar.classList.add('open');    overlay.classList.add('active'); document.body.style.overflow = 'hidden'; }
      function closeSidebar() { sidebar.classList.remove('open'); overlay.classList.remove('active'); document.body.style.overflow = ''; }
      burger?.addEventListener('click', () => sidebar.classList.contains('open') ? closeSidebar() : openSidebar());
      closeBtn?.addEventListener('click', closeSidebar);
      overlay?.addEventListener('click', closeSidebar);
      document.addEventListener('keydown', e => { if (e.key === 'Escape') closeSidebar(); });
      sidebar?.querySelectorAll('.cand-nav__item').forEach(link => {
        link.addEventListener('click', () => { if (window.innerWidth <= 900) closeSidebar(); });
      });
    })();
  </script>
  @include('partials._form-guard')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
  @yield('scripts')
</body>
</html>
