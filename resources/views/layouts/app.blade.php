<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  @php
    $seo       = $seo ?? [];
    $metaTitle = $__env->hasSection('title')
        ? $__env->yieldContent('title')
        : ($seo['meta_title'] ?? 'Emploi Bouge Bénin');
    $metaDesc  = $__env->hasSection('description')
        ? $__env->yieldContent('description')
        : ($seo['meta_description'] ?? 'Plateforme emploi au Bénin, offres, CV et recrutement.');
    $metaRobots   = $__env->hasSection('robots')
        ? $__env->yieldContent('robots')
        : ($seo['robots'] ?? 'index, follow');
    $metaCanonical = $__env->hasSection('canonical')
        ? $__env->yieldContent('canonical')
        : ($seo['canonical'] ?? url()->current());
    $ogTitle = $__env->hasSection('og_title')
        ? $__env->yieldContent('og_title')
        : ($seo['og_title'] ?? $metaTitle);
    $ogDesc  = $__env->hasSection('og_description')
        ? $__env->yieldContent('og_description')
        : ($seo['og_description'] ?? $metaDesc);
    $ogUrl   = $__env->hasSection('og_url')
        ? $__env->yieldContent('og_url')
        : url()->current();
    $gaId    = $seo['ga_id'] ?? '';
  @endphp

  <title>{{ $metaTitle }}</title>
  <meta name="description" content="{{ $metaDesc }}">
  <meta name="robots" content="{{ $metaRobots }}">
  <meta name="author" content="Emploi Bouge Bénin">
  <meta name="geo.region" content="BJ">
  <meta name="geo.placename" content="Cotonou, Bénin">
  <meta name="language" content="fr">
  @if(!empty($seo['gsc_verification']))
  <meta name="google-site-verification" content="{{ $seo['gsc_verification'] }}">
  @endif
  <link rel="canonical" href="{{ $metaCanonical }}">

  {{-- Open Graph --}}
  @php $ogImage = $__env->hasSection('og_image') ? $__env->yieldContent('og_image') : ($seo['og_image'] ?? asset('images/Logo.png')); @endphp
  <meta property="og:site_name"    content="Emploi Bouge Bénin">
  <meta property="og:type"         content="@yield('og_type', 'website')">
  <meta property="og:locale"       content="fr_FR">
  <meta property="og:title"        content="{{ $ogTitle }}">
  <meta property="og:description"  content="{{ $ogDesc }}">
  <meta property="og:url"          content="{{ $ogUrl }}">
  <meta property="og:image"        content="{{ $ogImage }}">
  <meta property="og:image:width"  content="1200">
  <meta property="og:image:height" content="630">
  <meta name="twitter:card"        content="summary_large_image">
  <meta name="twitter:title"       content="{{ $ogTitle }}">
  <meta name="twitter:description" content="{{ $ogDesc }}">
  <meta name="twitter:image"       content="{{ $ogImage }}">

  {{-- Preconnect for Google Fonts --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600&family=Jost:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />

  <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}" />
  <link rel="manifest" href="{{ route('pwa.manifest') }}">
  <meta name="theme-color" content="#042C53">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <meta name="apple-mobile-web-app-title" content="Emploi Bénin">
  <link rel="apple-touch-icon" href="{{ asset('images/Logo.png') }}">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/components.css') }}" />
  @yield('css')
  <meta name="csrf-token" content="{{ csrf_token() }}">

  {{-- JSON-LD structured data --}}
  @yield('jsonld')

  {{-- Google Analytics --}}
  @if($gaId)
  <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', '{{ $gaId }}');
  </script>
  @endif
</head>
<body>

  {{-- ── NAV ── --}}
  @include('components.nav')

  {{-- ── CONTENU ── --}}
  <main>
    @yield('content')
  </main>

  {{-- ── FOOTER ── --}}
  @include('components.footer')

  {{-- ── BANNIÈRE CONSENTEMENT COOKIES ── --}}
  <div id="cookie-banner" style="display:none;position:fixed;bottom:0;left:0;right:0;z-index:99999;background:#1e293b;color:#e2e8f0;padding:14px 20px;box-shadow:0 -4px 20px rgba(0,0,0,.25)">
    <div style="max-width:1100px;margin:0 auto;display:flex;align-items:center;flex-wrap:wrap;gap:12px">
      <p style="margin:0;font-size:13.5px;flex:1;min-width:200px;line-height:1.5">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#F5C842" stroke-width="2" style="display:inline-block;vertical-align:-3px;margin-right:6px"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        Ce site utilise des cookies pour améliorer votre expérience et mesurer l'audience.
        <a href="/legale/politique-confidentialite" style="color:#93c5fd;text-decoration:underline;margin-left:4px">En savoir plus</a>
      </p>
      <div style="display:flex;gap:8px;flex-shrink:0">
        <button onclick="setCookieConsent('refused')"
                style="padding:8px 16px;border-radius:7px;border:1.5px solid #475569;background:transparent;color:#94a3b8;font-size:13px;font-weight:600;cursor:pointer">
          Refuser
        </button>
        <button onclick="setCookieConsent('accepted')"
                style="padding:8px 16px;border-radius:7px;border:none;background:#F5C842;color:#1e293b;font-size:13px;font-weight:700;cursor:pointer">
          Tout accepter
        </button>
      </div>
    </div>
  </div>
  <script>
  (function () {
    if (!localStorage.getItem('cookie_consent')) {
      document.getElementById('cookie-banner').style.display = 'block';
    }
  })();
  function setCookieConsent(choice) {
    localStorage.setItem('cookie_consent', choice);
    document.getElementById('cookie-banner').style.display = 'none';
  }
  </script>

  {{-- ── POPUP PUBLICITAIRE ── --}}
  <div id="pub-popup" style="display:none;position:fixed;bottom:24px;right:24px;z-index:8888;width:320px;max-width:calc(100vw - 32px);border-radius:14px;overflow:hidden;box-shadow:0 8px 32px rgba(0,0,0,.22);background:#fff">
    <div style="background:linear-gradient(135deg,#042C53,#185FA5);padding:8px 14px;display:flex;align-items:center;justify-content:space-between">
      <span style="color:#F5C842;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em">Publicité</span>
      <button id="pub-close" aria-label="Fermer"
              style="background:none;border:none;cursor:pointer;color:rgba(255,255,255,.8);display:flex;align-items:center;padding:2px">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
    <div id="pub-body">
      <a id="pub-link" href="#" target="_blank" rel="noopener noreferrer sponsored" style="display:block">
        <img id="pub-img" src="" alt="" style="width:100%;max-height:340px;object-fit:contain;display:block;background:#f8fafc">
      </a>
      <div style="padding:12px 14px 14px">
        <p id="pub-titre" style="font-weight:700;color:#042C53;font-size:14px;margin:0 0 8px;line-height:1.3"></p>
        <div style="background:#f1f5f9;border-radius:6px;height:4px;overflow:hidden">
          <div id="pub-progress" style="height:100%;background:#185FA5;width:100%;transition:width linear"></div>
        </div>
        <p style="font-size:10.5px;color:#94a3b8;margin:5px 0 0;text-align:right">Fermeture automatique dans <span id="pub-countdown">10</span>s</p>
      </div>
    </div>
  </div>

  <script>
  (function () {
    const DISPLAY_DURATION = 10000;
    const INTERVAL_BETWEEN = 60000;

    const popup    = document.getElementById('pub-popup');
    const closeBtn = document.getElementById('pub-close');
    const pubImg   = document.getElementById('pub-img');
    const pubTitre = document.getElementById('pub-titre');
    const pubLink  = document.getElementById('pub-link');
    const pubProg  = document.getElementById('pub-progress');
    const pubCount = document.getElementById('pub-countdown');

    let ads = [];
    let closeTimer    = null;
    let countdownInterval = null;
    let elapsed       = 0;   // ms déjà consommées avant la pause
    let pauseStart    = null; // timestamp du début de la pause
    let isPaused      = false;

    function loadAds(callback) {
      const cached   = sessionStorage.getItem('pub_ads');
      const cachedAt = parseInt(sessionStorage.getItem('pub_ads_at') || '0');
      if (cached && Date.now() - cachedAt < 3600000) {
        callback(JSON.parse(cached));
      } else {
        fetch('/api/publicites/actives')
          .then(r => r.json())
          .then(data => {
            sessionStorage.setItem('pub_ads', JSON.stringify(data));
            sessionStorage.setItem('pub_ads_at', Date.now());
            callback(data);
          })
          .catch(() => callback([]));
      }
    }

    // Lance les timers pour la durée restante
    function startTimers(remaining) {
      pauseStart = null;
      isPaused   = false;

      // Barre de progression : reprend de sa position actuelle vers 0
      const pct = (remaining / DISPLAY_DURATION) * 100;
      pubProg.style.transition = 'none';
      pubProg.style.width = pct + '%';
      requestAnimationFrame(() => requestAnimationFrame(() => {
        pubProg.style.transition = 'width ' + remaining + 'ms linear';
        pubProg.style.width = '0%';
      }));

      // Compte à rebours
      pubCount.textContent = Math.ceil(remaining / 1000);
      clearInterval(countdownInterval);
      countdownInterval = setInterval(() => {
        const r = remaining - (Date.now() - (Date.now() - remaining + remaining)); // recalcul ci-dessous
        // On recalcule à partir de elapsed pour être précis
        const left = DISPLAY_DURATION - elapsed - (isPaused ? 0 : (Date.now() - pauseRef));
        pubCount.textContent = Math.max(0, Math.ceil(left / 1000));
      }, 200);

      // Fermeture automatique
      clearTimeout(closeTimer);
      closeTimer = setTimeout(closeAd, remaining);
    }

    // Référence de temps pour le calcul en cours
    let pauseRef = 0;

    function showAd(ad) {
      pubImg.src = ad.image_url;
      pubImg.alt = ad.titre;
      pubTitre.textContent = ad.titre;
      pubLink.href = ad.lien || '#';
      pubLink.style.pointerEvents = ad.lien ? '' : 'none';
      popup.style.display = 'block';

      elapsed   = 0;
      isPaused  = false;
      pauseRef  = Date.now();

      // Affichage initial du compte à rebours
      pubCount.textContent = Math.ceil(DISPLAY_DURATION / 1000);

      clearInterval(countdownInterval);
      countdownInterval = setInterval(() => {
        if (isPaused) return;
        const spent = elapsed + (Date.now() - pauseRef);
        const left  = Math.max(0, DISPLAY_DURATION - spent);
        pubCount.textContent = Math.ceil(left / 1000);
      }, 200);

      // Barre de progression
      pubProg.style.transition = 'none';
      pubProg.style.width = '100%';
      requestAnimationFrame(() => requestAnimationFrame(() => {
        pubProg.style.transition = 'width ' + DISPLAY_DURATION + 'ms linear';
        pubProg.style.width = '0%';
      }));

      clearTimeout(closeTimer);
      closeTimer = setTimeout(closeAd, DISPLAY_DURATION);
    }

    function pauseAd() {
      if (isPaused || popup.style.display === 'none') return;
      isPaused   = true;
      pauseStart = Date.now();
      elapsed   += Date.now() - pauseRef;

      // Figer la barre de progression
      const pct = parseFloat(pubProg.style.width) ||
                  (pubProg.getBoundingClientRect().width / pubProg.parentElement.getBoundingClientRect().width * 100);
      pubProg.style.transition = 'none';
      pubProg.style.width = Math.max(0, (1 - elapsed / DISPLAY_DURATION) * 100) + '%';

      clearTimeout(closeTimer);
      clearInterval(countdownInterval);
      pubCount.textContent = Math.max(0, Math.ceil((DISPLAY_DURATION - elapsed) / 1000));
    }

    function resumeAd() {
      if (!isPaused) return;
      isPaused = false;
      pauseRef = Date.now();
      const remaining = Math.max(0, DISPLAY_DURATION - elapsed);
      if (remaining <= 0) { closeAd(); return; }

      // Relancer la barre
      pubProg.style.transition = 'none';
      pubProg.style.width = ((remaining / DISPLAY_DURATION) * 100) + '%';
      requestAnimationFrame(() => requestAnimationFrame(() => {
        pubProg.style.transition = 'width ' + remaining + 'ms linear';
        pubProg.style.width = '0%';
      }));

      // Relancer le compte à rebours
      clearInterval(countdownInterval);
      countdownInterval = setInterval(() => {
        if (isPaused) return;
        const left = Math.max(0, remaining - (Date.now() - pauseRef));
        pubCount.textContent = Math.ceil(left / 1000);
      }, 200);

      clearTimeout(closeTimer);
      closeTimer = setTimeout(closeAd, remaining);
    }

    function closeAd() {
      clearTimeout(closeTimer);
      clearInterval(countdownInterval);
      popup.style.display = 'none';
      elapsed = 0; isPaused = false;

      const idx = (parseInt(sessionStorage.getItem('pub_index') || '0') + 1);
      sessionStorage.setItem('pub_index', idx);
      sessionStorage.setItem('pub_next_at', Date.now() + INTERVAL_BETWEEN);
    }

    function tick() {
      const nextAt = parseInt(sessionStorage.getItem('pub_next_at') || '0');
      if (Date.now() < nextAt) return;

      loadAds(function (data) {
        if (!data || data.length === 0) return;
        ads = data;
        const idx = parseInt(sessionStorage.getItem('pub_index') || '0') % ads.length;
        showAd(ads[idx]);
        sessionStorage.setItem('pub_next_at', Date.now() + INTERVAL_BETWEEN + DISPLAY_DURATION);
      });
    }

    closeBtn.addEventListener('click', closeAd);
    popup.addEventListener('mouseenter', pauseAd);
    popup.addEventListener('mouseleave', resumeAd);

    const firstVisit = !sessionStorage.getItem('pub_next_at');
    if (firstVisit) {
      sessionStorage.setItem('pub_next_at', Date.now() + 3000);
    }

    setInterval(tick, 1000);
    tick();
  })();
  </script>

  @if($recaptchaActif)
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  @endif

  <script src="{{ asset('js/app.js') }}" defer></script>
  @include('partials._form-guard')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
  @include('components.flash-swal')
  @yield('scripts')

  {{-- ── PWA INSTALL BANNER ── --}}
  <div id="pwa-banner" style="display:none;position:fixed;bottom:0;left:0;right:0;z-index:99998;background:#042C53;color:#fff;padding:14px 16px;box-shadow:0 -4px 24px rgba(0,0,0,.3)">
    <div style="max-width:600px;margin:0 auto;display:flex;align-items:center;gap:12px">
      <img src="{{ asset('images/Logo.png') }}" alt="" width="40" height="40" style="border-radius:10px;flex-shrink:0;object-fit:cover">
      <div style="flex:1;min-width:0">
        <p style="margin:0;font-size:13.5px;font-weight:700;color:#fff;line-height:1.3">Emploi Bouge Bénin</p>
        <p style="margin:0;font-size:12px;color:rgba(255,255,255,.7);line-height:1.4" id="pwa-banner-text">Installez l'application pour accéder rapidement aux offres d'emploi.</p>
      </div>
      <div style="display:flex;gap:8px;flex-shrink:0;align-items:center">
        <button id="pwa-install-btn" style="display:none;padding:9px 16px;background:#F5C842;color:#042C53;border:none;border-radius:8px;font-size:13px;font-weight:800;cursor:pointer;white-space:nowrap">
          Installer
        </button>
        <button id="pwa-ios-btn" style="display:none;padding:9px 16px;background:#F5C842;color:#042C53;border:none;border-radius:8px;font-size:13px;font-weight:800;cursor:pointer;white-space:nowrap">
          Comment installer ?
        </button>
        <button id="pwa-close-btn" aria-label="Fermer" style="background:none;border:none;cursor:pointer;color:rgba(255,255,255,.6);padding:4px;display:flex;align-items:center">
          <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </button>
      </div>
    </div>
    {{-- Guide installation --}}
    <div id="pwa-ios-guide" style="display:none;max-width:600px;margin:10px auto 0;background:rgba(255,255,255,.1);border-radius:10px;padding:12px 14px">
      <p id="pwa-guide-ios" style="margin:0;font-size:12.5px;color:#fff;line-height:1.9">
        <strong>iPhone / iPad :</strong>
        Appuyez sur
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#F5C842" stroke-width="2.2" style="display:inline-block;vertical-align:-2px"><path d="M4 12v8a2 2 0 002 2h12a2 2 0 002-2v-8"/><polyline points="16 6 12 2 8 6"/><line x1="12" y1="2" x2="12" y2="15"/></svg>
        <strong>"Partager"</strong> en bas de Safari &rarr; <strong>"Sur l'écran d'accueil"</strong> &rarr; <strong>"Ajouter"</strong>
      </p>
      <p id="pwa-guide-android" style="margin:0;font-size:12.5px;color:#fff;line-height:1.9">
        <strong>Android :</strong>
        Ouvrez le menu Chrome
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#F5C842" stroke-width="2.2" style="display:inline-block;vertical-align:-2px"><circle cx="12" cy="5" r="1"/><circle cx="12" cy="12" r="1"/><circle cx="12" cy="19" r="1"/></svg>
        &rarr; <strong>"Ajouter à l'écran d'accueil"</strong>
      </p>
      <p id="pwa-guide-desktop" style="margin:0;font-size:12.5px;color:#fff;line-height:1.9">
        <strong>Ordinateur (Chrome) :</strong>
        Cliquez sur l'icône
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#F5C842" stroke-width="2.2" style="display:inline-block;vertical-align:-2px"><path d="M12 3v13M5 10l7 7 7-7"/><path d="M5 21h14"/></svg>
        dans la barre d'adresse (en haut à droite) &rarr; <strong>"Installer"</strong>
      </p>
    </div>
  </div>

  <script>
  (function () {
    var STORAGE_KEY    = 'pwa_installed';
    var banner         = document.getElementById('pwa-banner');
    var installBtn     = document.getElementById('pwa-install-btn');
    var iosBtn         = document.getElementById('pwa-ios-btn');
    var closeBtn       = document.getElementById('pwa-close-btn');
    var iosGuide       = document.getElementById('pwa-ios-guide');
    var deferredPrompt = null;

    // App déjà installée → ne rien afficher
    if (localStorage.getItem(STORAGE_KEY) === 'yes') return;

    // Déjà ouvert en mode standalone (app installée)
    var isStandalone = window.navigator.standalone === true
                    || window.matchMedia('(display-mode: standalone)').matches;
    if (isStandalone) { localStorage.setItem(STORAGE_KEY, 'yes'); return; }

    var isIos = /iphone|ipad|ipod/i.test(navigator.userAgent);

    function showBanner() { banner.style.display = 'block'; }

    // ── Prompt natif (Chrome Android + Chrome Desktop) ─────
    window.addEventListener('beforeinstallprompt', function (e) {
      e.preventDefault();
      deferredPrompt = e;
      installBtn.style.display = 'inline-block';
      showBanner();
    });

    // ── iOS Safari : guide manuel ───────────────────────────
    if (isIos) {
      iosBtn.style.display = 'inline-block';
      showBanner();
    }

    // ── Tous les autres cas (si le prompt ne vient pas) ────
    // Attendre que la bannière cookies soit réglée pour éviter le chevauchement
    function tryShowFallback() {
      if (banner.style.display !== 'none') return;
      iosBtn.textContent = 'Comment installer ?';
      iosBtn.style.display = 'inline-block';
      showBanner();
    }

    var cookieConsent = localStorage.getItem('cookie_consent');
    if (cookieConsent) {
      // Cookie déjà accepté/refusé → afficher après 1.5s
      setTimeout(tryShowFallback, 1500);
    } else {
      // Attendre que l'utilisateur réponde à la bannière cookies
      var cookieCheckInterval = setInterval(function () {
        if (localStorage.getItem('cookie_consent')) {
          clearInterval(cookieCheckInterval);
          setTimeout(tryShowFallback, 800);
        }
      }, 500);
      // Sécurité : afficher quand même après 30s si pas de réponse
      setTimeout(function () {
        clearInterval(cookieCheckInterval);
        tryShowFallback();
      }, 30000);
    }

    // ── Clic "Installer" (Android natif) ───────────────────
    installBtn.addEventListener('click', function () {
      if (!deferredPrompt) return;
      deferredPrompt.prompt();
      deferredPrompt.userChoice.then(function (r) {
        if (r.outcome === 'accepted') {
          localStorage.setItem(STORAGE_KEY, 'yes');
          banner.style.display = 'none';
        }
        deferredPrompt = null;
      });
    });

    // ── Clic "Comment installer ?" ─────────────────────────
    iosBtn.addEventListener('click', function () {
      var guide          = document.getElementById('pwa-ios-guide');
      var guideIos       = document.getElementById('pwa-guide-ios');
      var guideAndroid   = document.getElementById('pwa-guide-android');
      var guideDesktop   = document.getElementById('pwa-guide-desktop');
      var isAndroid      = /android/i.test(navigator.userAgent);
      var isMobile       = isIos || isAndroid;

      guideIos.style.display     = isIos     ? 'block' : 'none';
      guideAndroid.style.display = (isAndroid && !isIos) ? 'block' : 'none';
      guideDesktop.style.display = !isMobile ? 'block' : 'none';

      guide.style.display = guide.style.display === 'none' ? 'block' : 'none';
    });

    // ── Fermer (réapparaît à la prochaine visite) ──────────
    closeBtn.addEventListener('click', function () {
      banner.style.display = 'none';
    });

    // ── App installée via le prompt ────────────────────────
    window.addEventListener('appinstalled', function () {
      localStorage.setItem(STORAGE_KEY, 'yes');
      banner.style.display = 'none';
    });

    // ── Enregistrement Service Worker ──────────────────────
    if ('serviceWorker' in navigator) {
      window.addEventListener('load', function () {
        navigator.serviceWorker.register('{{ asset("sw.js") }}').catch(function () {});
      });
    }
  })();
  </script>

</body>
</html>
