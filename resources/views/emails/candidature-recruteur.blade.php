<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nouvelle candidature reçue</title>
  <style>
    body   { margin:0; padding:0; background:#f1f5f9; font-family: Arial, sans-serif; -webkit-font-smoothing:antialiased; }
    table  { border-spacing:0; }
    td     { padding:0; }
    img    { border:0; }
    a      { color:inherit; }

    .wrap         { max-width:580px; margin:40px auto; }
    .card         { background:#fff; border-radius:16px; overflow:hidden; border:1px solid #dce6f4; }

    /* ── Header ── */
    .header       { background:linear-gradient(135deg,#042C53 0%,#185FA5 100%); padding:32px 40px 28px; }
    .header-logo  { color:#F5C842; font-size:11px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; margin:0 0 10px; }
    .header-title { color:#fff; font-size:22px; font-weight:700; margin:0 0 6px; line-height:1.3; }
    .header-sub   { color:rgba(255,255,255,.65); font-size:13px; margin:0; }

    /* ── Alert badge ── */
    .alert-wrap   { padding:24px 40px 0; }
    .alert-badge  { display:inline-block; background:#FFF3CD; border:1px solid #F5C842; color:#92400e; font-size:12px; font-weight:700; letter-spacing:.05em; padding:5px 14px; border-radius:20px; text-transform:uppercase; }

    /* ── Body ── */
    .body         { padding:20px 40px 32px; }
    .greeting     { font-size:16px; color:#374151; line-height:1.6; margin:0 0 18px; }

    /* ── Candidat card ── */
    .cand-card    { background:#EBF4FD; border:1px solid #bfdbfe; border-radius:12px; padding:20px 24px; margin:0 0 20px; }
    .cand-label   { font-size:10px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:.08em; margin:0 0 12px; }
    .cand-name    { font-size:18px; font-weight:700; color:#042C53; margin:0 0 6px; }
    .cand-email   { font-size:13.5px; color:#185FA5; text-decoration:none; display:block; margin:0 0 12px; }
    .cand-row     { display:flex; gap:8px; align-items:center; font-size:13px; color:#4b5563; margin:4px 0; }
    .cand-dot     { width:5px; height:5px; border-radius:50%; background:#94a3b8; flex-shrink:0; }

    /* ── Offre card ── */
    .offre-card   { background:#f8fafc; border:1px solid #e2e8f0; border-radius:12px; padding:18px 24px; margin:0 0 24px; }
    .offre-label  { font-size:10px; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:.08em; margin:0 0 10px; }
    .offre-titre  { font-size:16px; font-weight:700; color:#042C53; margin:0 0 4px; }
    .offre-co     { font-size:13px; color:#64748b; margin:0; }

    /* ── Info rows ── */
    .info-row     { display:flex; gap:10px; align-items:flex-start; font-size:13.5px; color:#374151; margin:0 0 10px; line-height:1.5; }
    .info-icon    { width:20px; text-align:center; flex-shrink:0; margin-top:1px; color:#64748b; }

    /* ── CTA ── */
    .cta-wrap     { text-align:center; margin:8px 0 28px; }
    .cta-btn      { display:inline-block; background:#042C53; color:#fff !important; text-decoration:none; font-size:15px; font-weight:700; padding:14px 36px; border-radius:10px; letter-spacing:.02em; }
    .cta-btn:hover { background:#185FA5; }

    /* ── Divider ── */
    .divider      { border:none; border-top:1px solid #e2e8f0; margin:0 0 24px; }

    /* ── Footer ── */
    .footer       { background:#f8fafc; border-top:1px solid #e2e8f0; padding:20px 40px; text-align:center; }
    .footer p     { color:#94a3b8; font-size:12px; margin:0 0 4px; line-height:1.6; }
    .footer a     { color:#185FA5; text-decoration:none; }

    @media (max-width:600px) {
      .wrap         { margin:0; }
      .card         { border-radius:0; border:none; }
      .header, .body, .footer, .alert-wrap { padding-left:20px; padding-right:20px; }
    }
  </style>
</head>
<body>
@php
  $candidat   = $candidature->candidat;
  $offre      = $candidature->offre;
  $recruteur  = $offre?->recruteur;
  $nomCandidat = trim(($candidat?->prenom ?? '') . ' ' . ($candidat?->nom ?? '')) ?: 'Un candidat';
  $prenomRec  = $recruteur?->prenom ?? 'Recruteur';
  $dateCandidature = $candidature->created_at?->locale('fr')->isoFormat('dddd D MMMM YYYY [à] HH[h]mm') ?? now()->locale('fr')->isoFormat('dddd D MMMM YYYY');
  $lienDetail = route('recruteur.candidatures.show', $candidature);
@endphp

<div class="wrap">
  <div class="card">

    {{-- ── HEADER ── --}}
    <div class="header">
      <p class="header-logo">Emploi Bouge Bénin</p>
      <h1 class="header-title">Nouvelle candidature reçue</h1>
      <p class="header-sub">Un candidat a postulé à l'une de vos offres d'emploi</p>
    </div>

    {{-- ── BADGE ── --}}
    <div class="alert-wrap">
      <span class="alert-badge">📋 Candidature à traiter</span>
    </div>

    {{-- ── BODY ── --}}
    <div class="body">

      <p class="greeting">
        Bonjour <strong>{{ $prenomRec }}</strong>,<br>
        Vous venez de recevoir une nouvelle candidature pour une de vos offres publiées sur
        <strong>Emploi Bouge Bénin</strong>. Voici le récapitulatif :
      </p>

      {{-- Candidat --}}
      <div class="cand-card">
        <p class="cand-label">👤 Candidat</p>
        <p class="cand-name">{{ $nomCandidat }}</p>
        <a href="mailto:{{ $candidat?->email }}" class="cand-email">{{ $candidat?->email }}</a>
        @if($candidat?->tel)
        <div class="cand-row">
          <span class="cand-dot"></span>
          <span>Téléphone : {{ $candidat->tel }}</span>
        </div>
        @endif
        @if($candidat?->pays)
        <div class="cand-row">
          <span class="cand-dot"></span>
          <span>Pays : {{ $candidat->pays }}</span>
        </div>
        @endif
        <div class="cand-row" style="margin-top:10px;font-size:12px;color:#64748b">
          <span>Candidature envoyée le {{ $dateCandidature }}</span>
        </div>
      </div>

      {{-- Offre --}}
      <div class="offre-card">
        <p class="offre-label">💼 Offre concernée</p>
        <p class="offre-titre">{{ $offre?->titre }}</p>
        <p class="offre-co">{{ $offre?->entreprise }}{{ $offre?->localisation ? ' · ' . $offre->localisation : '' }}</p>
      </div>

      {{-- Message de motivation (si présent) --}}
      @if($candidature->message_motivation)
      <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:16px 20px;margin:0 0 24px">
        <p style="font-size:10px;font-weight:700;color:#166534;text-transform:uppercase;letter-spacing:.08em;margin:0 0 8px">💬 Message de motivation</p>
        <p style="font-size:13.5px;color:#374151;line-height:1.7;margin:0">{{ Str::limit($candidature->message_motivation, 400) }}</p>
      </div>
      @endif

      {{-- CTA --}}
      <div class="cta-wrap">
        <a href="{{ $lienDetail }}" class="cta-btn">
          Consulter la candidature complète →
        </a>
      </div>

      <hr class="divider">

      <p style="font-size:13px;color:#64748b;line-height:1.7;margin:0">
        Connectez-vous à votre espace recruteur pour consulter le CV et le dossier complet
        du candidat, et mettre à jour le statut de la candidature (en attente, accepté, refusé).
      </p>

    </div>

    {{-- ── FOOTER ── --}}
    <div class="footer">
      <p>
        <strong style="color:#374151">Emploi Bouge Bénin</strong><br>
        La plateforme d'emploi numéro 1 au Bénin<br>
        <a href="{{ url('/') }}">emploibougebenin.com</a> ·
        <a href="mailto:contact@emploibougebenin.com">contact@emploibougebenin.com</a>
      </p>
      <p style="margin-top:10px">
        Vous recevez cet email car vous êtes recruteur sur notre plateforme.<br>
        <a href="{{ route('recruteur.dashboard') }}">Accéder à mon espace</a>
      </p>
    </div>

  </div>
</div>
</body>
</html>
