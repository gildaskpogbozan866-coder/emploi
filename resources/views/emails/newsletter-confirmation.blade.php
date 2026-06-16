<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bienvenue dans la newsletter</title>
  <style>
    body { margin: 0; padding: 0; background: #f8fafc; font-family: 'Jost', Arial, sans-serif; }
    .wrap { max-width: 580px; margin: 40px auto; background: #fff; border-radius: 14px; overflow: hidden; border: 1px solid #e2e8f0; }
    .header { background: #042C53; padding: 32px 40px; text-align: center; }
    .header h1 { color: #fff; margin: 0; font-size: 22px; font-weight: 700; }
    .header p { color: rgba(255,255,255,0.7); margin: 6px 0 0; font-size: 13px; }
    .body { padding: 36px 40px; }
    .body p { color: #374151; font-size: 15px; line-height: 1.7; margin: 0 0 16px; }
    .highlight { background: #EBF4FD; border-left: 4px solid #185FA5; border-radius: 0 8px 8px 0; padding: 14px 18px; margin: 24px 0; }
    .highlight p { margin: 0; font-size: 14px; color: #042C53; font-weight: 600; }
    .btn { display: block; width: fit-content; margin: 28px auto; background: #042C53; color: #fff; text-decoration: none; padding: 14px 32px; border-radius: 8px; font-weight: 700; font-size: 15px; }
    .footer { background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 20px 40px; text-align: center; }
    .footer p { color: #94a3b8; font-size: 12px; margin: 0; }
    .footer a { color: #185FA5; }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="header">
      <h1>Emploi Bouge Bénin</h1>
      <p>La plateforme d'emploi numéro 1 au Bénin</p>
    </div>
    <div class="body">
      <p>Bonjour {{ $subscriber->prenom ? $subscriber->prenom : 'cher(e) abonné(e)' }},</p>
      <p>
        Nous avons bien reçu votre inscription à la <strong>newsletter d'Emploi Bouge Bénin</strong>.
        Vous faites maintenant partie de notre communauté !
      </p>
      <div class="highlight">
        <p>Ce que vous recevrez :</p>
      </div>
      <ul style="color:#374151;font-size:14px;line-height:2;padding-left:20px;margin:0 0 20px">
        <li>Les nouvelles offres d'emploi et stages au Bénin</li>
        <li>Les conseils carrière et astuces de recrutement</li>
        <li>Les actualités du marché de l'emploi en Afrique francophone</li>
        <li>Les opportunités exclusives pour nos abonnés</li>
      </ul>
      <p>En attendant, explorez dès maintenant toutes nos offres disponibles :</p>
      <a href="{{ url('/offres') }}" class="btn">Voir les offres d'emploi</a>
      <p style="font-size:13px;color:#94a3b8;margin-top:24px">
        Vous ne souhaitez plus recevoir nos e-mails ?
        <a href="{{ url('/newsletter/desabonner/' . $subscriber->token) }}" style="color:#185FA5">Se désabonner</a>
      </p>
    </div>
    <div class="footer">
      <p>
        Emploi Bouge Bénin · Cotonou, République du Bénin<br>
        <a href="{{ url('/') }}">www.emploibougebenin.com</a> ·
        <a href="mailto:contact@emploibougebenin.com">contact@emploibougebenin.com</a>
      </p>
    </div>
  </div>
</body>
</html>
