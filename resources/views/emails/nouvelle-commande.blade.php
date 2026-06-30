<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nouvelle commande {{ $commande->reference }}</title>
  <style>
    body { margin:0; padding:0; background:#f0f4f8; font-family:Arial,sans-serif; }
    .wrap { max-width:600px; margin:32px auto; background:#fff; border-radius:14px; overflow:hidden; border:1px solid #e2e8f0; }
    .header { background:#042C53; padding:28px 36px; }
    .header h1 { color:#fff; margin:0 0 4px; font-size:20px; font-weight:700; }
    .header p { color:rgba(255,255,255,.6); margin:0; font-size:13px; }
    .badge { display:inline-block; background:#F5C842; color:#042C53; font-weight:800; font-size:13px; padding:4px 12px; border-radius:20px; margin-top:12px; }
    .body { padding:28px 36px; }
    .section-title { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:.08em; color:#94a3b8; margin:0 0 14px; padding-bottom:8px; border-bottom:1px solid #f1f5f9; }
    .row { margin-bottom:12px; }
    .row .label { font-size:12px; color:#94a3b8; margin:0 0 2px; }
    .row .value { font-size:14px; font-weight:600; color:#1e293b; margin:0; }
    .block { background:#f8fafc; border-radius:10px; padding:14px 18px; margin-bottom:12px; }
    .block .label { font-size:12px; color:#94a3b8; margin:0 0 6px; }
    .block .value { font-size:13.5px; color:#374151; margin:0; line-height:1.65; white-space:pre-wrap; }
    .file-link { display:inline-flex; align-items:center; gap:6px; background:#eff6ff; border:1px solid #bfdbfe; border-radius:8px; padding:8px 14px; color:#1d4ed8; font-size:13px; font-weight:600; text-decoration:none; }
    .divider { border:none; border-top:1px solid #f1f5f9; margin:20px 0; }
    .footer { background:#f8fafc; border-top:1px solid #e2e8f0; padding:18px 36px; text-align:center; }
    .footer p { color:#94a3b8; font-size:12px; margin:0; }
  </style>
</head>
<body>
<div class="wrap">

  <div class="header">
    <h1>Nouvelle commande reçue</h1>
    <p>{{ $commande->service->nom }}</p>
    <span class="badge">{{ $commande->reference }}</span>
  </div>

  <div class="body">

    {{-- Résumé --}}
    <p class="section-title">Résumé de la commande</p>
    <div style="display:flex;gap:24px;flex-wrap:wrap;margin-bottom:20px">
      <div class="row">
        <p class="label">Service</p>
        <p class="value">{{ $commande->service->nom }}</p>
      </div>
      <div class="row">
        <p class="label">Montant</p>
        <p class="value" style="color:#16a34a">{{ number_format($commande->montant, 0, ',', ' ') }} FCFA</p>
      </div>
      <div class="row">
        <p class="label">Soumise le</p>
        <p class="value">{{ $commande->created_at->format('d/m/Y à H:i') }}</p>
      </div>
      <div class="row">
        <p class="label">Délai promis</p>
        <p class="value">{{ $commande->service->delai ?? 'Non précisé' }}</p>
      </div>
    </div>

    <hr class="divider">

    {{-- Infos client --}}
    <p class="section-title">Informations du client</p>
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px">
      <div class="row">
        <p class="label">Nom complet</p>
        <p class="value">{{ $clientInfo['nom_complet'] ?: '—' }}</p>
      </div>
      <div class="row">
        <p class="label">Email de livraison</p>
        <p class="value">{{ $commande->email_contact }}</p>
      </div>
      @if($clientInfo['tel'])
      <div class="row">
        <p class="label">Téléphone</p>
        <p class="value">{{ $clientInfo['tel'] }}</p>
      </div>
      @endif
      @if($clientInfo['ville'])
      <div class="row">
        <p class="label">Ville</p>
        <p class="value">{{ $clientInfo['ville'] }}</p>
      </div>
      @endif
    </div>

    <hr class="divider">

    {{-- Parcours --}}
    <p class="section-title">Parcours & informations pour le CV</p>

    @if($clientInfo['poste_vise'])
    <div class="row" style="margin-bottom:14px">
      <p class="label">Poste / Métier visé</p>
      <p class="value" style="font-size:15px;color:#042C53">{{ $clientInfo['poste_vise'] }}</p>
    </div>
    @endif

    @if($clientInfo['niveau_etudes'])
    <div class="row" style="margin-bottom:14px">
      <p class="label">Niveau d'études</p>
      <p class="value">{{ $clientInfo['niveau_etudes'] }}</p>
    </div>
    @endif

    @if($clientInfo['experiences'])
    <div class="block">
      <p class="label">Expériences</p>
      <p class="value">{{ $clientInfo['experiences'] }}</p>
    </div>
    @endif

    @if($clientInfo['competences'])
    <div class="block">
      <p class="label">Compétences</p>
      <p class="value">{{ $clientInfo['competences'] }}</p>
    </div>
    @endif

    @if($clientInfo['details_supplementaires'])
    <div class="block">
      <p class="label">Informations supplémentaires</p>
      <p class="value">{{ $clientInfo['details_supplementaires'] }}</p>
    </div>
    @endif

    {{-- Fichier joint --}}
    @if($commande->fichier_joint)
    <hr class="divider">
    <p class="section-title">Fichier joint</p>
    <a href="{{ url('storage/' . $commande->fichier_joint) }}" class="file-link" target="_blank">
      📎 Télécharger le fichier joint
    </a>
    @endif

  </div>

  <div class="footer">
    <p>Emploi Bouge Bénin · Administration<br>
      Répondez directement à cet email ou gérez la commande dans le tableau de bord.
    </p>
  </div>

</div>
</body>
</html>
