<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Emploi Bouge Bénin')</title>
  <meta name="description" content="@yield('description', 'Plateforme emploi au Bénin — offres, CV, talents.')">
  {{-- Open Graph --}}
  <meta property="og:site_name" content="Emploi Bouge Bénin">
  <meta property="og:type"      content="@yield('og_type', 'website')">
  <meta property="og:title"     content="@yield('og_title', 'Emploi Bouge Bénin — Offres d\'emploi au Bénin')">
  <meta property="og:description" content="@yield('og_description', 'Plateforme emploi au Bénin — offres, CV, talents.')">
  <meta property="og:url"       content="@yield('og_url', request()->url())">
  @hasSection('og_image')
  <meta property="og:image"     content="@yield('og_image')">
  <meta name="twitter:card"     content="summary_large_image">
  @else
  <meta name="twitter:card"     content="summary">
  @endif
  <meta name="twitter:title"       content="@yield('og_title', 'Emploi Bouge Bénin')">
  <meta name="twitter:description" content="@yield('og_description', 'Plateforme emploi au Bénin.')">
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600&family=Jost:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}" />
  <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/components.css') }}" />
  @yield('css')
  <meta name="csrf-token" content="{{ csrf_token() }}">
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
