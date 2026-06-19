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

  <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('images/favicon-64.png') }}?v=2">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32.png') }}?v=2">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16.png') }}?v=2">
  <link rel="shortcut icon" href="{{ asset('images/favicon-64.png') }}?v=2">
  @include('partials._pwa-head')
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
  <div id="cookie-banner" style="display:none;position:fixed;bottom:0;left:0;right:0;z-index:99999;background:#1e293b;color:#e2e8f0;padding:12px 16px;box-shadow:0 -4px 20px rgba(0,0,0,.25)">
    <div style="max-width:1100px;margin:0 auto;display:flex;align-items:center;flex-wrap:wrap;gap:10px">
      <p style="margin:0;font-size:13px;flex:1;min-width:180px;line-height:1.5">
        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="#F5C842" stroke-width="2" style="display:inline-block;vertical-align:-3px;margin-right:5px"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        Ce site utilise des cookies pour améliorer votre expérience.
        <a href="/legale/politique-confidentialite" style="color:#93c5fd;text-decoration:underline;margin-left:4px;white-space:nowrap;display:inline-flex;align-items:center;padding:6px 0;min-height:36px">En savoir plus</a>
      </p>
      <div style="display:flex;gap:8px;flex-shrink:0;width:100%;justify-content:flex-end" id="cookie-actions">
        <button onclick="setCookieConsent('refused')"
                style="padding:8px 14px;border-radius:7px;border:1.5px solid #475569;background:transparent;color:#94a3b8;font-size:13px;font-weight:600;cursor:pointer;min-height:40px">
          Refuser
        </button>
        <button onclick="setCookieConsent('accepted')"
                style="padding:8px 16px;border-radius:7px;border:none;background:#F5C842;color:#1e293b;font-size:13px;font-weight:700;cursor:pointer;min-height:40px">
          Tout accepter
        </button>
      </div>
    </div>
  </div>
  <script>
  (function () {
    var banner = document.getElementById('cookie-banner');
    if (!localStorage.getItem('cookie_consent')) {
      banner.style.display = 'block';
      document.body.style.paddingBottom = (banner.offsetHeight + 8) + 'px';
    }
  })();
  function setCookieConsent(choice) {
    localStorage.setItem('cookie_consent', choice);
    document.getElementById('cookie-banner').style.display = 'none';
    document.body.style.paddingBottom = '';
  }
  </script>

  {{-- ── POPUP PUBLICITAIRE ── --}}
  <div id="pub-popup" style="display:none;position:fixed;bottom:16px;right:16px;z-index:8888;width:300px;max-width:calc(100vw - 24px);border-radius:14px;overflow:hidden;box-shadow:0 8px 32px rgba(0,0,0,.22);background:#fff">
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

  @include('partials._pwa-banner')

</body>
</html>
