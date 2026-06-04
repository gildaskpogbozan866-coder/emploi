@extends('layouts.app')
@section('title', 'FAQ — Emploi Bouge Bénin')

@section('content')
<section class="page-hero">
  <div class="container">
    <h1 class="page-hero__title">Questions fréquentes</h1>
    <p class="page-hero__sub">Tout ce que vous devez savoir sur Emploi Bouge Bénin.</p>
  </div>
</section>

<section class="section">
  <div class="container" style="max-width:760px">

    @php
    $faqs = [
      'Candidats' => [
        ['Est-ce gratuit pour les candidats ?', 'Oui, totalement gratuit. Vous pouvez déposer votre CV, postuler aux offres et créer des alertes emploi sans débourser un centime. Un plan Premium optionnel est disponible pour plus de visibilité.'],
        ['Comment fonctionne le dépôt de CV ?', 'Créez un compte, remplissez votre profil, et déposez votre CV en quelques minutes. Votre profil sera visible par les recruteurs de notre réseau.'],
        ['Puis-je postuler à plusieurs offres ?', 'Oui, le nombre de candidatures est illimité sur le plan gratuit.'],
        ['Comment recevoir des alertes emploi ?', 'Dans votre espace candidat, créez des alertes basées sur des mots-clés, localisation ou type de contrat. Vous serez notifié(e) par email selon la fréquence choisie.'],
      ],
      'Recruteurs' => [
        ['Comment publier une offre d\'emploi ?', 'Créez un compte recruteur, remplissez le formulaire de publication et soumettez votre offre. Elle sera vérifiée et publiée sous 24h ouvrées.'],
        ['Combien coûte la publication d\'une offre ?', 'La première offre est gratuite. Des plans premium sont disponibles pour booster la visibilité et publier plusieurs offres.'],
        ['Puis-je accéder aux CV des candidats ?', 'Oui, notre CVthèque est accessible aux recruteurs enregistrés. Un abonnement premium donne accès aux coordonnées complètes.'],
      ],
      'Général' => [
        ['Les offres sont-elles vérifiées ?', 'Toutes les offres passent par notre équipe de modération avant publication. Nous vérifions l\'existence de l\'entreprise et la légitimité de l\'offre.'],
        ['Comment signaler une offre suspecte ?', 'Sur chaque offre, un lien "Signaler" permet de nous alerter. Notre équipe traite tous les signalements sous 48h.'],
        ['Quels pays sont couverts ?', 'La plateforme est principalement axée sur le Bénin, mais couvre également la Côte d\'Ivoire, le Sénégal, le Togo, le Cameroun et d\'autres pays d\'Afrique francophone.'],
      ],
    ];
    @endphp

    @foreach($faqs as $categorie => $questions)
    <div style="margin-bottom:36px">
      <h2 style="font-size:1.1rem;font-weight:700;color:#042C53;margin-bottom:14px;padding-bottom:8px;border-bottom:2px solid #EBF4FD">{{ $categorie }}</h2>
      <div style="display:flex;flex-direction:column;gap:2px">
        @foreach($questions as $faq)
        <details style="background:#fff;border:1px solid #e2e8f0;border-radius:10px;overflow:hidden">
          <summary style="padding:14px 18px;font-weight:600;color:#042C53;cursor:pointer;font-size:.92rem;list-style:none;display:flex;justify-content:space-between;align-items:center">
            {{ $faq[0] }}
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="flex-shrink:0;transition:transform .2s">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
            </svg>
          </summary>
          <div style="padding:0 18px 16px;color:#475569;line-height:1.7;font-size:.9rem">{{ $faq[1] }}</div>
        </details>
        @endforeach
      </div>
    </div>
    @endforeach

    <div style="background:#f0f7ff;border:1px solid #bfdbfe;border-radius:14px;padding:24px;text-align:center;margin-top:40px">
      <p style="font-weight:600;color:#185FA5;margin:0 0 8px">Vous n'avez pas trouvé votre réponse ?</p>
      <p style="color:#64748b;margin:0 0 16px;font-size:.9rem">Notre équipe est disponible pour vous aider.</p>
      <a href="{{ route('contact') }}" class="btn btn--blue">Nous contacter</a>
    </div>

  </div>
</section>
@endsection
