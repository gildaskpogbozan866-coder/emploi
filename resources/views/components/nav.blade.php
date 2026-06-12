<header class="site-header">
  <div class="nav-wrapper">
    <nav class="nav">
      <a href="{{ route('home') }}" class="nav__logo">
        <span class="nav__logo-mark"><img src="{{ asset('images/Logo.png') }}" alt="Emploi Bouge Bénin"></span>
      </a>
      <ul class="nav__links">
        <li><a href="{{ route('home') }}" class="nav__link {{ request()->routeIs('home') ? 'nav__link--active' : '' }}">Accueil</a></li>
        <li><a href="{{ route('a-propos') }}" class="nav__link {{ request()->routeIs('a-propos') ? 'nav__link--active' : '' }}">À propos</a></li>
        <li><a href="{{ route('service.list') }}" class="nav__link {{ request()->routeIs('service.*') ? 'nav__link--active' : '' }}">Services</a></li>
        <li><a href="{{ route('offre.list') }}" class="nav__link {{ request()->routeIs('offre.*') ? 'nav__link--active' : '' }}">Offres d'emploi</a></li>
        <li><a href="{{ route('contact') }}" class="nav__link {{ request()->routeIs('contact') ? 'nav__link--active' : '' }}">Contact</a></li>
      </ul>
      <div class="nav__actions">
        <a href="{{ route('cv.public.depot') }}" class="nav__cta">
          <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
          Déposer mon CV
        </a>
        @guest
          <a href="{{ route('auth.connexion') }}" class="nav__btn-outline">Connexion</a>
          <a href="{{ route('auth.inscription') }}" class="nav__btn-filled">Inscription</a>
        @else
          <div style="display:flex;align-items:center;gap:8px">
            <a href="{{ match(auth()->user()->role) {
              'admin'     => route('admin.dashboard'),
              'recruteur' => route('recruteur.dashboard'),
              default     => route('candidat.dashboard')
            } }}" class="nav__btn-outline" style="display:flex;align-items:center;gap:6px;padding:8px 14px">
              <span style="width:22px;height:22px;border-radius:50%;background:#042C53;color:#fff;font-size:.7rem;font-weight:700;display:flex;align-items:center;justify-content:center">{{ auth()->user()->initiale }}</span>
              <span style="max-width:100px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;font-size:.85rem">{{ auth()->user()->prenom }}</span>
            </a>
            <form method="POST" action="{{ route('auth.deconnecter') }}" style="display:inline">
              @csrf
              <button type="submit" style="background:none;border:1px solid #e2e8f0;border-radius:8px;padding:7px 12px;cursor:pointer;font-size:.78rem;color:#64748b;font-family:var(--font-body,Jost,sans-serif)">Sortir</button>
            </form>
          </div>
        @endguest
        <button class="nav__hamburger" id="hamburger" aria-label="Ouvrir le menu" aria-expanded="false">
          <span></span><span></span><span></span>
        </button>
      </div>
    </nav>
  </div>

  <div class="nav__mobile" id="mobileMenu">
    <ul class="nav__mobile-list">
      <li><a href="{{ route('home') }}" class="nav__mobile-link">Accueil</a></li>
      <li><a href="{{ route('a-propos') }}" class="nav__mobile-link">À propos</a></li>
      <li><a href="{{ route('service.list') }}" class="nav__mobile-link">Services</a></li>
      <li><a href="{{ route('offre.list') }}" class="nav__mobile-link">Offres d'emploi</a></li>
      <li><a href="{{ route('contact') }}" class="nav__mobile-link">Contact</a></li>
    </ul>
    <a href="{{ route('cv.public.depot') }}" class="nav__mobile-cta">Déposer mon CV</a>
    @guest
      <div class="nav__mobile-auth">
        <a href="{{ route('auth.connexion') }}" class="nav__btn-outline">Connexion</a>
        <a href="{{ route('auth.inscription') }}" class="nav__btn-filled">Inscription</a>
      </div>
    @else
      <div style="gap:8px;flex-direction:column;padding:0 16px 16px">
        <a href="{{ match(auth()->user()->role) {
          'admin'     => route('admin.dashboard'),
          'recruteur' => route('recruteur.dashboard'),
          default     => route('candidat.dashboard')
        } }}" class="nav__btn-filled" style="text-align:center;display:block">Mon espace</a>
        <form method="POST" action="{{ route('auth.deconnecter') }}">
          @csrf
          <button type="submit" style="background:none;border:1px solid #e2e8f0;border-radius:8px;padding:10px;cursor:pointer;font-size:.85rem;color:#64748b;width:100%;margin-top:8px">Déconnexion</button>
        </form>
      </div>
    @endauth
  </div>
</header>
