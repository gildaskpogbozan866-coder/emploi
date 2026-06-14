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
        @include('partials._notification-bell')
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
      @php
        $refRoutes          = ['admin.competences*','admin.metiers*','admin.types-contrat*','admin.secteurs-activite*','admin.langues*','admin.niveaux-langue*','admin.niveaux-etude*','admin.niveaux-experience*'];
        $refActif           = request()->routeIs(...$refRoutes);
        $validationDocsActif = \App\Models\ParametreApp::get('recruteur_validation_docs', '0') === '1';
        $enAttente          = $validationDocsActif ? \App\Models\RecruteurVerification::where('statut','en_attente')->count() : 0;
        $msgsNonLus         = \App\Models\ContactMessage::where('lu', false)->count();
      @endphp
      <ul class="adm-nav">

        {{-- ── VUE D'ENSEMBLE ── --}}
        <li class="adm-nav__section">Vue d'ensemble</li>

        <li class="adm-nav__item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
          <a href="{{ route('admin.dashboard') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7" rx="1"/><rect x="14" y="3" width="7" height="7" rx="1"/><rect x="14" y="14" width="7" height="7" rx="1"/><rect x="3" y="14" width="7" height="7" rx="1"/></svg>
            Tableau de bord
          </a>
        </li>
        {{-- Statistiques consolidées dans le toggle Graphiques du tableau de bord --}}

        {{-- ── UTILISATEURS ── --}}
        <li class="adm-nav__section">Utilisateurs</li>

        @if($validationDocsActif)
        <li class="adm-nav__item {{ request()->routeIs('admin.verifications*') ? 'active' : '' }}">
          <a href="{{ route('admin.verifications.list') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            Vérifications recruteurs
            @if($enAttente > 0)
              <span style="background:#ef4444;color:#fff;font-size:.65rem;font-weight:700;padding:1px 6px;border-radius:99px;margin-left:auto">{{ $enAttente }}</span>
            @endif
          </a>
        </li>
        @endif
        <li class="adm-nav__item {{ request()->routeIs('admin.document-types*') ? 'active' : '' }}">
          <a href="{{ route('admin.document-types.index') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Documents recruteur
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
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            Recruteurs
          </a>
        </li>

        {{-- ── EMPLOI & CVS ── --}}
        <li class="adm-nav__section">Emploi &amp; CVs</li>

        <li class="adm-nav__item {{ request()->routeIs('admin.offres*') ? 'active' : '' }}">
          <a href="{{ route('admin.offres.list') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/><line x1="12" y1="12" x2="12" y2="16"/><line x1="10" y1="14" x2="14" y2="14"/></svg>
            Offres d'emploi
          </a>
        </li>
        <li class="adm-nav__item {{ request()->routeIs('admin.cvs*') || request()->routeIs('admin.documents*') ? 'active' : '' }}">
          <a href="{{ route('admin.cvs.list') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
            CVs &amp; Documents
          </a>
        </li>

        {{-- ── SERVICES & BOUTIQUE ── --}}
        <li class="adm-nav__section">Services &amp; Boutique</li>

        <li class="adm-nav__item {{ request()->routeIs('admin.services*') ? 'active' : '' }}">
          <a href="{{ route('admin.services.list') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
            Services proposés
          </a>
        </li>
        <li class="adm-nav__item {{ request()->routeIs('admin.commandes*') ? 'active' : '' }}">
          <a href="{{ route('admin.commandes.list') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></svg>
            Commandes clients
          </a>
        </li>

        {{-- ── FINANCE ── --}}
        <li class="adm-nav__section">Finance</li>

        <li class="adm-nav__item {{ request()->routeIs('admin.paiements*') ? 'active' : '' }}">
          <a href="{{ route('admin.paiements.list') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
            Paiements
          </a>
        </li>
        <li class="adm-nav__item {{ request()->routeIs('admin.abonnements*') ? 'active' : '' }}">
          <a href="{{ route('admin.abonnements') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
            Abonnements souscrits
          </a>
        </li>
        <li class="adm-nav__item {{ request()->routeIs('admin.plans*') ? 'active' : '' }}">
          <a href="{{ route('admin.plans.list') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
            Plans d'abonnement
          </a>
        </li>
        <li class="adm-nav__item {{ request()->routeIs('admin.publication-plans*') ? 'active' : '' }}">
          <a href="{{ route('admin.publication-plans.index') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
            Plans de publication
          </a>
        </li>
        <li class="adm-nav__item {{ request()->routeIs('admin.payment-settings*') ? 'active' : '' }}">
          <a href="{{ route('admin.payment-settings.index') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
            Gateways de paiement
          </a>
        </li>

        {{-- ── COMMUNICATION ── --}}
        <li class="adm-nav__section">Communication</li>

        <li class="adm-nav__item {{ request()->routeIs('admin.messagerie*') ? 'active' : '' }}">
          <a href="{{ route('admin.messagerie') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
            Messagerie
          </a>
        </li>
        <li class="adm-nav__item {{ request()->routeIs('admin.contact-messages*') ? 'active' : '' }}">
          <a href="{{ route('admin.contact-messages.index') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            Messages de contact
            @if($msgsNonLus > 0)
              <span style="background:#f59e0b;color:#fff;font-size:.65rem;font-weight:700;padding:1px 6px;border-radius:99px;margin-left:auto">{{ $msgsNonLus }}</span>
            @endif
          </a>
        </li>
        <li class="adm-nav__item {{ request()->routeIs('admin.signalements*') ? 'active' : '' }}">
          <a href="{{ route('admin.signalements.list') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2z"/></svg>
            Signalements
          </a>
        </li>
        <li class="adm-nav__item {{ request()->routeIs('admin.blog*') ? 'active' : '' }}">
          <a href="{{ route('admin.blog.list') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
            Blog &amp; Articles
          </a>
        </li>
        <li class="adm-nav__item {{ request()->routeIs('admin.publicites*') ? 'active' : '' }}">
          <a href="{{ route('admin.publicites.index') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="13" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
            Publicités
          </a>
        </li>

        {{-- ── CONFIGURATION ── --}}
        <li class="adm-nav__section">Configuration</li>

        <li class="adm-nav__item {{ request()->routeIs('admin.faqs*') ? 'active' : '' }}">
          <a href="{{ route('admin.faqs.index') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            FAQ
          </a>
        </li>

        <li class="adm-nav__item {{ request()->routeIs('admin.legales*') ? 'active' : '' }}">
          <a href="{{ route('admin.legales.index') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Pages légales
          </a>
        </li>

        <li class="adm-nav__item {{ request()->routeIs('admin.parametres*') && !request()->routeIs('admin.seo*') ? 'active' : '' }}">
          <a href="{{ route('admin.parametres') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
            Paramètres du site
          </a>
        </li>
        <li class="adm-nav__item {{ request()->routeIs('admin.seo*') ? 'active' : '' }}">
          <a href="{{ route('admin.seo.index') }}">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
            SEO &amp; Référencement
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
