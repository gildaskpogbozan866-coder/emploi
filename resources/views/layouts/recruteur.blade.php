<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Espace Recruteur') — Emploi Bouge Bénin</title>
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}" />
  <link rel="stylesheet" href="{{ asset('css/recruteur/recruteur.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/dashboard-layout.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/components.css') }}" />
  @yield('css')
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

  <header class="dash-header dash-header--recruteur">
    <div class="dash-header__inner">
      <div class="dash-header__left">
        <button class="dash-header__burger" id="recBurger" aria-label="Menu">
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

  <div class="rec-wrap">
    <div class="rec-overlay" id="recOverlay"></div>
    <aside class="rec-sidebar" id="recSidebar">
      <button class="rec-sidebar__close" id="recClose" aria-label="Fermer le menu">
        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
      @yield('sidebar')
    </aside>
    <main class="rec-main">
      @if(session('error'))
        <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:14px 18px;margin-bottom:20px;display:flex;gap:10px;align-items:center">
          <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#dc2626" stroke-width="2.5" style="flex-shrink:0"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          <p style="color:#dc2626;font-size:13.5px;font-weight:600;margin:0">{{ session('error') }}</p>
        </div>
      @endif
      @yield('content')
    </main>
  </div>

  <script>
    (function() {
      const burger   = document.getElementById('recBurger');
      const sidebar  = document.getElementById('recSidebar');
      const overlay  = document.getElementById('recOverlay');
      const closeBtn = document.getElementById('recClose');
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
