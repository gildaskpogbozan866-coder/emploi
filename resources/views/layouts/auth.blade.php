<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Emploi Bouge Bénin')</title>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600&family=Jost:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="icon" type="image/png" sizes="64x64" href="{{ asset('images/favicon-64.png') }}?v=2">
  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32.png') }}?v=2">
  <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16.png') }}?v=2">
  <link rel="shortcut icon" href="{{ asset('images/favicon-64.png') }}?v=2">
  <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ @filemtime(public_path('css/style.css')) }}" />
  @yield('css')
  <style>body { padding-top: 0 !important; }</style>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @include('partials._pwa-head')
</head>
<body>

  @yield('content')

  @include('partials._form-guard')
  @if($recaptchaActif ?? false)
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
  @endif
  @yield('scripts')
</body>
</html>
