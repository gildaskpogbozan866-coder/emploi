<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="robots" content="noindex, nofollow">
  <meta name="color-scheme" content="light">
  <title>Maintenance en cours | Emploi Bouge Bénin</title>

  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32.png') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700;800&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@500;600;700&display=swap" rel="stylesheet" />

  {{--
    Page 100% autonome, volontairement sans @extends('layouts.app') : le layout
    principal déclenche des requêtes DB (SEO, pubs, recaptcha...) et si la
    maintenance est justement due à une panne DB, la page casserait avec lui.
    Pas de framework CSS chargé via CDN non plus (Tailwind inclus) : on évite
    toute dépendance réseau externe autre que les polices, pour que la page
    s'affiche même si le reste de l'infra est instable. Tout le CSS ci-dessous
    est écrit à la main mais organisé en "tokens" (variables) pour rester
    aussi simple à modifier qu'un config Tailwind.
  --}}
  <style>
    :root {
      /* ── Palette de marque ── */
      --navy-900:   #042C53;
      --navy-950:   #031E3A;
      --blue-600:   #185FA5;
      --blue-400:   #378ADD;
      --amber-400:  #F5C842;
      --amber-500:  #E0B430;
      --white:      #FFFFFF;

      /* ── Surfaces & texte (thème clair) ── */
      --bg-top:      #F4F8FC;
      --bg-bottom:   #E9F1FA;
      --surface:     rgba(255,255,255,.72);
      --surface-solid: #FFFFFF;
      --border:      rgba(4,44,83,.10);
      --border-soft: rgba(4,44,83,.06);
      --text-1:      #0B2540;
      --text-2:      #45607C;
      --text-3:      #7C93AC;
      --shadow-color: 4,44,83;

      --radius-lg: 22px;
      --radius-md: 16px;
      --radius-sm: 11px;
    }

    *,*::before,*::after { box-sizing: border-box; }
    html, body { height: 100%; }

    body {
      margin: 0;
      font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
      color: var(--text-1);
      background:
        radial-gradient(ellipse 60% 50% at 15% 8%, rgba(55,138,221,.16), transparent 60%),
        radial-gradient(ellipse 50% 45% at 100% 100%, rgba(245,200,66,.14), transparent 55%),
        linear-gradient(180deg, var(--bg-top) 0%, var(--bg-bottom) 100%);
      min-height: 100vh;
      -webkit-font-smoothing: antialiased;
    }

    a { color: inherit; }

    @media (prefers-reduced-motion: reduce) {
      *, *::before, *::after { animation-duration: .001ms !important; animation-iteration-count: 1 !important; transition-duration: .001ms !important; }
    }

    :focus-visible {
      outline: 2.5px solid var(--blue-600);
      outline-offset: 3px;
      border-radius: 6px;
    }

    /* ── Halos décoratifs (immobiles, purement atmosphériques) ── */
    .glow { position: fixed; border-radius: 50%; filter: blur(100px); pointer-events: none; z-index: 0; }
    .glow--1 { width: 460px; height: 460px; top: -180px; left: -160px; background: rgba(55,138,221,.20); }
    .glow--2 { width: 420px; height: 420px; bottom: -180px; right: -140px; background: rgba(245,200,66,.16); }

    /* ── Layout général ── */
    .page {
      position: relative;
      z-index: 1;
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      gap: 28px;
      padding: 48px 20px 32px;
      text-align: center;
    }

    .brand {
      display: flex;
      align-items: center;
      gap: 9px;
      opacity: 0;
      animation: rise .6s ease .05s forwards;
    }
    .brand img { height: 24px; width: auto; }
    .brand span {
      font-family: 'Jost', sans-serif;
      font-size: 13px;
      font-weight: 600;
      letter-spacing: .06em;
      text-transform: uppercase;
      color: var(--text-2);
    }

    main {
      width: 100%;
      max-width: 620px;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    /* ── Illustration : anneau orbital + fusée ── */
    .illustration {
      position: relative;
      width: 104px;
      height: 104px;
      margin-bottom: 26px;
      opacity: 0;
      animation: rise .7s ease .1s forwards, float 5s ease-in-out 1s infinite;
    }
    .illustration__orbit {
      position: absolute;
      inset: -14px;
      border-radius: 50%;
      border: 1.5px dashed rgba(24,95,165,.28);
      animation: spin-slow 18s linear infinite;
    }
    .illustration__orbit::after {
      content: '';
      position: absolute;
      top: -4px;
      left: 50%;
      width: 8px;
      height: 8px;
      margin-left: -4px;
      border-radius: 50%;
      background: var(--amber-400);
      box-shadow: 0 0 0 5px rgba(245,200,66,.22);
    }
    .illustration__ring {
      position: absolute;
      inset: 4px;
      border-radius: 50%;
      background: radial-gradient(circle, rgba(55,138,221,.18), transparent 72%);
      animation: pulse-ring 3.2s ease-in-out infinite;
    }
    .illustration__badge {
      position: absolute;
      inset: 14px;
      border-radius: 27px;
      background: linear-gradient(150deg, var(--blue-400), var(--blue-600) 65%, var(--navy-900));
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow:
        0 18px 34px -12px rgba(4,44,83,.45),
        inset 0 1px 0 rgba(255,255,255,.35);
    }
    .illustration__badge svg { width: 46%; height: 46%; }
    .rocket-drift { animation: drift 3.4s ease-in-out infinite; transform-origin: center; }

    @keyframes float        { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-9px); } }
    @keyframes spin-slow    { to { transform: rotate(360deg); } }
    @keyframes drift        { 0%,100% { transform: translateY(0) rotate(0deg); } 50% { transform: translateY(-2px) rotate(-4deg); } }
    @keyframes pulse-ring   { 0%,100% { transform: scale(1); opacity: 1; } 50% { transform: scale(1.14); opacity: .55; } }
    @keyframes rise         { from { opacity: 0; transform: translateY(12px); } to { opacity: 1; transform: translateY(0); } }

    .eyebrow {
      font-family: 'Jost', sans-serif;
      font-size: 12.5px;
      font-weight: 600;
      letter-spacing: .12em;
      text-transform: uppercase;
      color: var(--blue-600);
      margin: 0 0 10px;
      opacity: 0;
      animation: rise .7s ease .14s forwards;
    }

    h1 {
      font-family: 'Jost', sans-serif;
      font-size: clamp(26px, 4.4vw, 36px);
      font-weight: 700;
      letter-spacing: -.015em;
      line-height: 1.15;
      margin: 0 0 12px;
      color: var(--navy-950);
      opacity: 0;
      animation: rise .7s ease .18s forwards;
    }

    .lead {
      font-size: 15px;
      line-height: 1.65;
      color: var(--text-2);
      max-width: 460px;
      margin: 0 0 32px;
      opacity: 0;
      animation: rise .7s ease .24s forwards;
    }

    /* ── Compte à rebours ── */
    .countdown {
      display: flex;
      gap: 12px;
      margin-bottom: 30px;
      opacity: 0;
      animation: rise .7s ease .3s forwards;
    }
    .countdown__unit {
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 8px;
    }
    .countdown__tile {
      position: relative;
      width: clamp(58px, 16vw, 76px);
      height: clamp(58px, 16vw, 76px);
      border-radius: var(--radius-md);
      background: var(--surface);
      backdrop-filter: blur(18px);
      -webkit-backdrop-filter: blur(18px);
      border: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow:
        0 1px 1px rgba(var(--shadow-color), .04),
        0 12px 28px -14px rgba(var(--shadow-color), .28);
      overflow: hidden;
    }
    .countdown__tile::before {
      /* fine ligne "split-flap" au centre, discret clin d'œil aux horloges de gare */
      content: '';
      position: absolute;
      left: 8%;
      right: 8%;
      top: 50%;
      height: 1px;
      background: rgba(4,44,83,.07);
    }
    .countdown__value {
      font-family: 'JetBrains Mono', monospace;
      font-variant-numeric: tabular-nums;
      font-size: clamp(22px, 5.4vw, 30px);
      font-weight: 600;
      color: var(--navy-950);
      transition: transform .35s cubic-bezier(.2,.8,.2,1), opacity .35s ease;
    }
    .countdown__value.tick {
      animation: tick-swap .4s cubic-bezier(.2,.8,.2,1);
    }
    @keyframes tick-swap {
      0%   { transform: translateY(-8px); opacity: 0; }
      45%  { transform: translateY(0);    opacity: 1; }
      100% { transform: translateY(0);    opacity: 1; }
    }
    .countdown__label {
      font-family: 'Jost', sans-serif;
      font-size: 11px;
      font-weight: 500;
      letter-spacing: .06em;
      text-transform: uppercase;
      color: var(--text-3);
    }
    .countdown__sep {
      align-self: center;
      font-family: 'JetBrains Mono', monospace;
      font-size: clamp(18px, 4vw, 24px);
      font-weight: 500;
      color: var(--border);
      transform: translateY(-11px);
    }

    /* ── Barre de progression ── */
    .progress-wrap {
      width: 100%;
      max-width: 400px;
      margin-bottom: 30px;
      opacity: 0;
      animation: rise .7s ease .36s forwards;
    }
    .progress-label {
      display: flex;
      justify-content: space-between;
      align-items: baseline;
      font-size: 12.5px;
      color: var(--text-2);
      margin-bottom: 8px;
    }
    .progress-label span:first-child { font-weight: 500; }
    #progress-pct {
      font-family: 'JetBrains Mono', monospace;
      font-weight: 600;
      color: var(--blue-600);
      font-variant-numeric: tabular-nums;
    }
    .progress-track {
      height: 8px;
      border-radius: 999px;
      background: rgba(4,44,83,.08);
      overflow: hidden;
    }
    .progress-fill {
      height: 100%;
      width: 0%;
      border-radius: 999px;
      background: linear-gradient(90deg, var(--blue-400), var(--blue-600));
      transition: width .6s ease;
      position: relative;
      overflow: hidden;
    }
    .progress-fill::after {
      content: '';
      position: absolute;
      inset: 0;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,.55), transparent);
      animation: shimmer 2.4s ease-in-out infinite;
    }
    @keyframes shimmer { 0% { transform: translateX(-100%); } 100% { transform: translateX(100%); } }

    /* ── Boutons ── */
    .actions {
      display: flex;
      gap: 12px;
      flex-wrap: wrap;
      justify-content: center;
      margin-bottom: 28px;
      opacity: 0;
      animation: rise .7s ease .42s forwards;
    }
    .btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 13px 22px;
      border-radius: var(--radius-sm);
      font-family: 'Jost', sans-serif;
      font-size: 14px;
      font-weight: 600;
      text-decoration: none;
      cursor: pointer;
      border: none;
      transition: transform .18s ease, box-shadow .18s ease, background .18s ease;
    }
    .btn svg { width: 16px; height: 16px; flex-shrink: 0; }
    .btn--primary {
      background: var(--amber-400);
      color: var(--navy-950);
      box-shadow: 0 12px 26px -10px rgba(245,200,66,.7);
      animation: soft-pulse 3s ease-in-out infinite;
    }
    .btn--primary:hover { background: var(--amber-500); transform: translateY(-2px); box-shadow: 0 16px 30px -8px rgba(245,200,66,.75); }
    @keyframes soft-pulse {
      0%,100% { box-shadow: 0 12px 26px -10px rgba(245,200,66,.7); }
      50%     { box-shadow: 0 12px 32px -6px rgba(245,200,66,.9); }
    }
    .btn--secondary {
      background: var(--surface-solid);
      color: var(--navy-900);
      border: 1.5px solid var(--border);
    }
    .btn--secondary:hover { background: #F7FAFD; border-color: rgba(24,95,165,.35); transform: translateY(-2px); }

    /* ── Carte d'information ── */
    .info-card {
      width: 100%;
      background: var(--surface);
      backdrop-filter: blur(18px);
      -webkit-backdrop-filter: blur(18px);
      border: 1px solid var(--border);
      border-radius: var(--radius-lg);
      padding: 18px 22px;
      margin-bottom: 26px;
      box-shadow: 0 14px 32px -18px rgba(var(--shadow-color), .3);
      opacity: 0;
      animation: rise .7s ease .48s forwards;
    }
    .info-card__title {
      font-family: 'Jost', sans-serif;
      font-size: 12.5px;
      font-weight: 600;
      letter-spacing: .04em;
      color: var(--text-2);
      margin: 0 0 12px;
      text-align: left;
    }
    .info-card ul {
      list-style: none;
      margin: 0;
      padding: 0;
      display: flex;
      flex-wrap: wrap;
      gap: 9px 22px;
    }
    .info-card li {
      display: flex;
      align-items: center;
      gap: 7px;
      font-size: 13px;
      color: var(--text-1);
      white-space: nowrap;
    }
    .info-card li .check {
      width: 18px; height: 18px;
      border-radius: 50%;
      background: rgba(24,95,165,.10);
      display: flex; align-items: center; justify-content: center;
      flex-shrink: 0;
    }
    .info-card li svg { width: 11px; height: 11px; color: var(--blue-600); }

    /* ── Réseaux sociaux ── */
    .socials {
      display: flex;
      gap: 10px;
      margin-bottom: 18px;
      opacity: 0;
      animation: rise .7s ease .54s forwards;
    }
    .social {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      background: var(--surface-solid);
      border: 1px solid var(--border);
      transition: transform .2s ease, background .2s ease, color .2s ease, border-color .2s ease;
      color: var(--text-2);
    }
    .social svg { width: 15px; height: 15px; }
    .social:hover { transform: translateY(-3px); background: var(--navy-900); color: var(--white); border-color: var(--navy-900); }
    .social[aria-disabled="true"] { opacity: .35; pointer-events: none; }

    footer.page-footer {
      font-family: 'Jost', sans-serif;
      font-size: 11px;
      color: var(--text-3);
      opacity: 0;
      animation: rise .7s ease .6s forwards;
    }
    footer.page-footer .sep { margin: 0 8px; opacity: .6; }

    @media (max-width: 420px) {
      .countdown { gap: 7px; }
      .countdown__sep { display: none; }
      .info-card ul { justify-content: center; }
      .btn { padding: 12px 18px; font-size: 13px; }
    }
  </style>
</head>
<body>

  <span class="glow glow--1" aria-hidden="true"></span>
  <span class="glow glow--2" aria-hidden="true"></span>

  <div class="page">

    {{-- ── En-tête ── --}}
    <div class="brand">
      <img src="{{ asset('images/Logo.png') }}" alt="">
      <span>Emploi Bouge Bénin</span>
    </div>

    <main>

      {{-- ── Illustration : fusée en orbite ── --}}
      <div class="illustration" aria-hidden="true">
        <span class="illustration__orbit"></span>
        <span class="illustration__ring"></span>
        <span class="illustration__badge">
          <svg class="rocket-drift" viewBox="0 0 24 24" fill="none">
            <path fill="#fff" d="M13.13 2.5c-1.6 1.1-3.06 2.75-4.1 4.55l-2.6.35a1.5 1.5 0 00-1.1.82L3.5 11.4a.75.75 0 00.72 1.08l2.53-.2c-.05.5-.07 1-.03 1.5a.75.75 0 00.22.48l1.3 1.3c.13.13.3.21.48.22.5.04 1 .02 1.5-.03l-.2 2.53a.75.75 0 001.08.72l3.18-1.83c.4-.24.7-.62.82-1.1l.35-2.6c1.8-1.04 3.45-2.5 4.55-4.1 1.9-2.76 2.2-5.6 1.9-6.7-1.1-.3-3.94 0-6.7 1.9zm1.2 6.13a1.75 1.75 0 112.47-2.47 1.75 1.75 0 01-2.47 2.47zM4.8 16.9c.7-.7 1.9-.7 2.9-.4-.15 1-.6 2.4-1.7 3.5-1 1-3 1.4-3 1.4s.4-2 1.4-3c.2-.2.3-.3.4-.4v-1.1z"/>
          </svg>
        </span>
      </div>

      <p class="eyebrow">Statut de la plateforme</p>
      <h1>Une amélioration est en cours</h1>
      <p class="lead">
        Nous effectuons actuellement une mise à jour importante afin d'améliorer votre expérience.
        Notre plateforme sera de nouveau disponible très prochainement. Merci pour votre patience.
      </p>

      {{-- ── Compte à rebours réel ── --}}
      <div class="countdown" id="countdown" role="timer" aria-live="polite" aria-atomic="true" aria-label="Temps restant avant la fin de la maintenance">
        <div class="countdown__unit">
          <div class="countdown__tile"><span class="countdown__value" id="cd-days">00</span></div>
          <span class="countdown__label">Jours</span>
        </div>
        <span class="countdown__sep">:</span>
        <div class="countdown__unit">
          <div class="countdown__tile"><span class="countdown__value" id="cd-hours">00</span></div>
          <span class="countdown__label">Heures</span>
        </div>
        <span class="countdown__sep">:</span>
        <div class="countdown__unit">
          <div class="countdown__tile"><span class="countdown__value" id="cd-minutes">00</span></div>
          <span class="countdown__label">Minutes</span>
        </div>
        <span class="countdown__sep">:</span>
        <div class="countdown__unit">
          <div class="countdown__tile"><span class="countdown__value" id="cd-seconds">00</span></div>
          <span class="countdown__label">Secondes</span>
        </div>
      </div>

      {{-- ── Progression réelle (temps écoulé / durée totale) ── --}}
      <div class="progress-wrap">
        <div class="progress-label">
          <span>Progression de la mise à jour</span>
          <span id="progress-pct">0%</span>
        </div>
        <div class="progress-track" role="progressbar" aria-label="Avancement de la mise à jour" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" id="progress-track">
          <div class="progress-fill" id="progress-fill"></div>
        </div>
      </div>

      {{-- ── Boutons ── --}}
      <div class="actions">
        <a href="{{ url('/') }}" class="btn btn--primary">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h5M20 20v-5h-5M4.6 15a8 8 0 0014.4 3.4M19.4 9A8 8 0 005 5.6"/></svg>
          Réessayer
        </a>
        <a href="https://whatsapp.com/channel/0029VbCGlUo5q08ZH1bnm11F" target="_blank" rel="noopener" class="btn btn--secondary">
          <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
          WhatsApp
        </a>
      </div>

      {{-- ── Carte d'information ── --}}
      <div class="info-card">
        <p class="info-card__title">Pendant cette maintenance</p>
        <ul>
          <li><span class="check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span> Données sécurisées</li>
          <li><span class="check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span> Aucun compte perdu</li>
          <li><span class="check"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg></span> Candidatures conservées</li>
        </ul>
      </div>

      {{-- ── Réseaux sociaux ── --}}
      <div class="socials">
        <a href="#" class="social" title="Bientôt disponible" aria-label="Facebook (bientôt disponible)" aria-disabled="true" tabindex="-1">
          <svg viewBox="0 0 24 24" fill="currentColor"><path d="M22 12.06C22 6.5 17.52 2 12 2S2 6.5 2 12.06C2 17.08 5.66 21.24 10.44 22v-7.03H7.9v-2.91h2.54V9.85c0-2.5 1.49-3.89 3.77-3.89 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56v1.88h2.78l-.44 2.91h-2.34V22C18.34 21.24 22 17.08 22 12.06z"/></svg>
        </a>
        <a href="#" class="social" title="Bientôt disponible" aria-label="LinkedIn (bientôt disponible)" aria-disabled="true" tabindex="-1">
          <svg viewBox="0 0 24 24" fill="currentColor"><path d="M19 3A2 2 0 0121 5v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h14zM8.34 18v-8.4H5.67V18h2.67zM7 8.48c.86 0 1.4-.57 1.4-1.28-.02-.73-.54-1.28-1.38-1.28-.84 0-1.4.55-1.4 1.28 0 .71.54 1.28 1.37 1.28H7zm11 9.52v-4.83c0-2.59-1.38-3.79-3.23-3.79-1.49 0-2.15.82-2.52 1.4V9.6h-2.67s.03.62 0 8.4h2.67v-4.69c0-.25.02-.5.1-.68.2-.5.66-1.02 1.44-1.02 1.02 0 1.43.78 1.43 1.92V18H18z"/></svg>
        </a>
        <a href="https://whatsapp.com/channel/0029VbCGlUo5q08ZH1bnm11F" target="_blank" rel="noopener" class="social" title="WhatsApp" aria-label="WhatsApp">
          <svg viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
        </a>
        <a href="mailto:contact@emploibougebenin.com" class="social" title="Email" aria-label="Nous écrire par email">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        </a>
      </div>

      <footer class="page-footer">
        &copy; {{ date('Y') }} Emploi Bouge Bénin <span class="sep">·</span> Version 3.2
      </footer>

    </main>
  </div>

  <script>
    (function () {
      'use strict';

      // ────────────────────────────────────────────────────────────────
      // CONFIGURATION : à modifier à la main à chaque maintenance.
      // Aucune valeur n'est injectée depuis le backend : ce sont deux
      // constantes JS en dur, à mettre à jour toi-même dans ce fichier
      // avant chaque déploiement de la page.
      //
      // MAINTENANCE_START : date/heure de début (sert à calculer la barre
      //   de progression réelle = temps écoulé / durée totale).
      // MAINTENANCE_END : date/heure de fin prévue, utilisée par le vrai
      //   compte à rebours.
      //
      // Format : 'AAAA-MM-JJTHH:MM:SS' en heure locale (Afrique/Porto-Novo,
      // UTC+1, pas de changement d'heure). Exemple ci-dessous : maintenance
      // du 2 juillet 2026, 22h00, jusqu'au 3 juillet 2026, 06h00.
      // ────────────────────────────────────────────────────────────────
      var MAINTENANCE_START = new Date('2026-07-02T22:00:00');
      var MAINTENANCE_END   = new Date('2026-07-03T06:00:00');

      var totalMs = Math.max(1, MAINTENANCE_END - MAINTENANCE_START);

      var els = {
        days:    document.getElementById('cd-days'),
        hours:   document.getElementById('cd-hours'),
        minutes: document.getElementById('cd-minutes'),
        seconds: document.getElementById('cd-seconds'),
      };
      var fill  = document.getElementById('progress-fill');
      var pct   = document.getElementById('progress-pct');
      var track = document.getElementById('progress-track');

      var lastValues = { days: null, hours: null, minutes: null, seconds: null };

      function setUnit(el, key, value) {
        var formatted = String(Math.max(0, value)).padStart(2, '0');
        if (lastValues[key] !== formatted) {
          lastValues[key] = formatted;
          el.textContent = formatted;
          // micro-animation "split-flap" uniquement quand la valeur change
          el.classList.remove('tick');
          // force reflow pour pouvoir rejouer l'animation
          void el.offsetWidth;
          el.classList.add('tick');
        }
      }

      function render() {
        var now = new Date();
        var remainingMs = MAINTENANCE_END - now;

        if (remainingMs <= 0) {
          setUnit(els.days, 'days', 0);
          setUnit(els.hours, 'hours', 0);
          setUnit(els.minutes, 'minutes', 0);
          setUnit(els.seconds, 'seconds', 0);
          fill.style.width = '100%';
          pct.textContent = '100%';
          track.setAttribute('aria-valuenow', 100);
          return;
        }

        var totalSeconds = Math.floor(remainingMs / 1000);
        var days    = Math.floor(totalSeconds / 86400);
        var hours   = Math.floor((totalSeconds % 86400) / 3600);
        var minutes = Math.floor((totalSeconds % 3600) / 60);
        var seconds = totalSeconds % 60;

        setUnit(els.days, 'days', days);
        setUnit(els.hours, 'hours', hours);
        setUnit(els.minutes, 'minutes', minutes);
        setUnit(els.seconds, 'seconds', seconds);

        // Progression réelle basée sur le temps écoulé depuis le début
        var elapsedMs = now - MAINTENANCE_START;
        var progress = Math.min(100, Math.max(0, (elapsedMs / totalMs) * 100));
        var rounded = Math.round(progress);
        fill.style.width = rounded + '%';
        pct.textContent = rounded + '%';
        track.setAttribute('aria-valuenow', rounded);
      }

      render();
      setInterval(render, 1000);
    })();
  </script>
</body>
</html>