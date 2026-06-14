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
        : ($seo['meta_description'] ?? 'Plateforme emploi au Bénin — offres, CV et recrutement.');
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
  @if(!empty($seo['gsc_verification']))
  <meta name="google-site-verification" content="{{ $seo['gsc_verification'] }}">
  @endif
  <link rel="canonical" href="{{ $metaCanonical }}">

  {{-- Open Graph --}}
  <meta property="og:site_name"    content="Emploi Bouge Bénin">
  <meta property="og:type"         content="@yield('og_type', 'website')">
  <meta property="og:title"        content="{{ $ogTitle }}">
  <meta property="og:description"  content="{{ $ogDesc }}">
  <meta property="og:url"          content="{{ $ogUrl }}">

  @hasSection('og_image')
  <meta property="og:image"  content="@yield('og_image')">
  <meta name="twitter:card"  content="summary_large_image">
  @elseif(!empty($seo['og_image']))
  <meta property="og:image"  content="{{ $seo['og_image'] }}">
  <meta name="twitter:card"  content="summary_large_image">
  @else
  <meta name="twitter:card"  content="summary">
  @endif

  <meta name="twitter:title"       content="{{ $ogTitle }}">
  <meta name="twitter:description" content="{{ $ogDesc }}">

  {{-- Preconnect for Google Fonts --}}
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600&family=Jost:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />

  <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}" />
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

  @if($recaptchaActif)
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  @endif

  <script src="{{ asset('js/app.js') }}" defer></script>
  @include('partials._form-guard')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
  @include('components.flash-swal')
  @yield('scripts')
</body>
</html>
