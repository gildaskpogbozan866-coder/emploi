<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Emploi Bouge Bénin')</title>
  <meta name="description" content="@yield('description', 'Plateforme emploi au Bénin — offres, CV, talents.')">
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

  {{-- ── FLASH MESSAGES ── --}}
  @include('components.flash')

  {{-- ── CONTENU ── --}}
  <main>
    @yield('content')
  </main>

  {{-- ── FOOTER ── --}}
  @include('components.footer')

  <script src="{{ asset('js/app.js') }}" defer></script>
  @include('partials._form-guard')
  @yield('scripts')
</body>
</html>
