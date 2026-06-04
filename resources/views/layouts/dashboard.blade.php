<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Mon espace') — Emploi Bouge Bénin</title>
  <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}" />
  <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/dashboard-layout.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/components.css') }}" />
  @yield('css')
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

  {{-- ── HEADER DASHBOARD ── --}}
  <header class="dash-header">
    <div class="dash-header__inner">
      <div class="dash-header__left">
        <button class="dash-header__burger" onclick="document.getElementById('sidebar').classList.toggle('open')" aria-label="Menu">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
            <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>
          </svg>
        </button>
        <a href="{{ route('home') }}" class="dash-header__logo">
          <img src="{{ asset('images/Logo.png') }}" alt="Emploi Bouge Bénin">
        </a>
        <div class="dash-header__divider"></div>
        <span class="dash-header__space">@yield('space-label', 'Mon espace')</span>
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
            <span>Déconnexion</span>
          </button>
        </form>
      </div>
    </div>
  </header>

  <div class="dash-wrap">
    {{-- ── SIDEBAR ── --}}
    <aside class="dash-sidebar" id="sidebar">
      @yield('sidebar')
    </aside>

    {{-- ── CONTENU ── --}}
    <main class="dash-main">
      @include('components.flash')
      @yield('content')
    </main>
  </div>

  @yield('scripts')
</body>
</html>
