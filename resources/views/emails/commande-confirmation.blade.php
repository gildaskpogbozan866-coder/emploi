<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Commande confirmée</title>
  <style>
    body { margin:0; padding:0; background:#f0f4f8; font-family:Arial,sans-serif; }
    .wrap { max-width:580px; margin:32px auto; background:#fff; border-radius:14px; overflow:hidden; border:1px solid #e2e8f0; }
    .header { background:linear-gradient(135deg,#042C53,#185FA5); padding:32px 36px; text-align:center; }
    .header h1 { color:#fff; margin:0 0 6px; font-size:22px; font-weight:800; }
    .header p { color:rgba(255,255,255,.7); margin:0; font-size:13px; }
    .check { width:56px; height:56px; background:#F5C842; border-radius:50%; display:flex; align-items:center; justify-content:center; margin:0 auto 16px; font-size:26px; }
    .body { padding:32px 36px; }
    .body p { color:#374151; font-size:15px; line-height:1.75; margin:0 0 14px; }
    .ref-box { background:#f0f9ff; border:1.5px solid #bae6fd; border-radius:10px; padding:16px 20px; text-align:center; margin:24px 0; }
    .ref-box p { margin:0; font-size:13px; color:#0369a1; }
    .ref-box .ref { font-size:20px; font-weight:800; color:#042C53; letter-spacing:.05em; }
    .steps { background:#f8fafc; border-radius:12px; padding:20px 24px; margin:20px 0; }
    .steps p { font-size:13px; font-weight:700; color:#042C53; margin:0 0 12px; text-transform:uppercase; letter-spacing:.05em; }
    .step { display:flex; gap:12px; align-items:flex-start; margin-bottom:10px; }
    .step-num { width:24px; height:24px; border-radius:50%; background:#042C53; color:#F5C842; font-size:12px; font-weight:800; display:flex; align-items:center; justify-content:center; flex-shrink:0; margin-top:1px; }
    .step-text { font-size:13.5px; color:#374151; line-height:1.5; margin:0; }
    .email-highlight { background:#fffbeb; border:1px solid #fde68a; border-radius:8px; padding:12px 16px; margin:16px 0; font-size:14px; color:#92400e; font-weight:600; text-align:center; }
    .footer { background:#f8fafc; border-top:1px solid #e2e8f0; padding:20px 36px; text-align:center; }
    .footer p { color:#94a3b8; font-size:12px; margin:0; }
    .footer a { color:#185FA5; }
  </style>
</head>
<body>
<div class="wrap">

  <div class="header">
    <div class="check">✅</div>
    <h1>Commande confirmée !</h1>
    <p>Emploi Bouge Bénin · Service CV Professionnel</p>
  </div>

  <div class="body">

    <p>Bonjour <strong>{{ $clientInfo['nom_complet'] ?: 'cher(e) client(e)' }}</strong>,</p>
    <p>
      Nous avons bien reçu votre commande pour le service
      <strong>{{ $commande->service->nom }}</strong>.
      Notre équipe va prendre en charge votre dossier immédiatement.
    </p>

    <div class="ref-box">
      <p>Votre numéro de commande</p>
      <p class="ref">{{ $commande->reference }}</p>
      <p style="margin-top:4px;font-size:12px;color:#64748b">Conservez ce numéro pour tout suivi</p>
    </div>

    @if($commande->email_contact)
    <div class="email-highlight">
      📬 Votre CV sera livré à : <strong>{{ $commande->email_contact }}</strong>
    </div>
    @endif

    <div class="steps">
      <p>Comment ça se passe ?</p>
      <div class="step">
        <div class="step-num">1</div>
        <p class="step-text">Notre équipe analyse vos informations et commence la rédaction de votre CV.</p>
      </div>
      <div class="step">
        <div class="step-num">2</div>
        <p class="step-text">Votre CV professionnel est rédigé et mis en forme par nos experts.</p>
      </div>
      <div class="step">
        <div class="step-num">3</div>
        <p class="step-text">Vous recevez votre CV par email sous <strong>{{ $commande->service->delai ?? '1h' }}</strong>.</p>
      </div>
    </div>

    <p style="font-size:13.5px;color:#64748b">
      Une question ? Répondez directement à cet email en indiquant votre numéro de commande <strong>{{ $commande->reference }}</strong>.
    </p>

  </div>

  <div class="footer">
    <p>
      Emploi Bouge Bénin · Cotonou, République du Bénin<br>
      <a href="{{ url('/') }}">www.emploibougebenin.com</a>
    </p>
  </div>

</div>
</body>
</html>
