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

  <script src="{{ asset('js/app.js') }}" defer></script>
  @include('partials._form-guard')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
  @include('components.flash-swal')
  @yield('scripts')
</body>
</html>
