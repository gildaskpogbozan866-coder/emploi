<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@yield('title', 'Emploi Bouge Bénin')</title>
  <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600&family=Jost:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
  <link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon.svg') }}" />
  <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
  @yield('css')
  <style>body { padding-top: 0 !important; }</style>
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body>

  @yield('content')

  @include('partials._form-guard')
  @yield('scripts')
</body>
</html>
