{{-- PWA : balises <head> à inclure dans tous les layouts --}}
<link rel="manifest" href="{{ route('pwa.manifest') }}">
<meta name="theme-color" content="#042C53">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="Emploi Bénin">
<link rel="apple-touch-icon" href="{{ asset('images/pwa-icon-192.png') }}">
