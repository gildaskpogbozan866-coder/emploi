@extends('layouts.app')
@section('title', 'Nos Services | Emploi Bouge Bénin')
@section('description', 'Création de sites web, réseaux sociaux, marketing digital, SEO, formation informatique, CV professionnel et accompagnement digital au Bénin.')

@section('css')
<link rel="stylesheet" href="{{ asset('css/service/list-service.css') }}">
@endsection

@section('content')

{{-- ═══════════════════════════════════════════
     HERO
═══════════════════════════════════════════ --}}
<section class="svc-hero">
  <div class="svc-hero__bg-deco" aria-hidden="true">
    <div class="svc-hero__circle svc-hero__circle--1"></div>
    <div class="svc-hero__circle svc-hero__circle--2"></div>
    <div class="svc-hero__circle svc-hero__circle--3"></div>
  </div>
  <div class="container">
    <div class="svc-hero__inner">
      <div class="svc-hero__badge">
        Emploi Bouge Bénin
      </div>
      <h1 class="svc-hero__title">Votre partenaire <span>digital</span><br>pour réussir au Bénin</h1>
      <p class="svc-hero__sub">Création web, design, marketing, formation informatique et accompagnement : nous vous accompagnons à chaque étape de votre projet.</p>
      <div class="svc-hero__pills">
        <span class="svc-hero__pill">Sites web</span>
        <span class="svc-hero__pill">Réseaux sociaux</span>
        <span class="svc-hero__pill">Marketing digital</span>
        <span class="svc-hero__pill">SEO Google</span>
        <span class="svc-hero__pill">Formation</span>
        <span class="svc-hero__pill">CV Pro</span>
      </div>
      <div class="svc-hero__actions">
        <a href="#nos-services" class="svc-hero__btn--primary">
          Découvrir nos services
          <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
        </a>
        <a href="{{ route('contact') }}" class="svc-hero__btn--ghost">Nous contacter</a>
      </div>
      <div class="svc-hero__stats">
        <div class="svc-hero__stat">
          <span class="svc-hero__stat-num">10</span>
          <span class="svc-hero__stat-label">Services</span>
        </div>
        <div class="svc-hero__stat-sep"></div>
        <div class="svc-hero__stat">
          <span class="svc-hero__stat-num">500+</span>
          <span class="svc-hero__stat-label">Clients satisfaits</span>
        </div>
        <div class="svc-hero__stat-sep"></div>
        <div class="svc-hero__stat">
          <span class="svc-hero__stat-num">30min</span>
          <span class="svc-hero__stat-label">Délai de réponse</span>
        </div>
        <div class="svc-hero__stat-sep"></div>
        <div class="svc-hero__stat">
          <span class="svc-hero__stat-num">100%</span>
          <span class="svc-hero__stat-label">Satisfaction garantie</span>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════
     CARTE CV PREMIUM (Service phare)
═══════════════════════════════════════════ --}}
<section class="section" style="background:#fff" id="cv-pro">
  <div class="container">

    <div class="svc-section-hd">
      <span class="svc-section-badge svc-section-badge--gold">
        Service phare · Très demandé
      </span>
      <h2 class="svc-section-title">CV Professionnel</h2>
      <p class="svc-section-sub">Notre service le plus populaire. Un CV qui retient l'attention en 7 secondes.</p>
    </div>

    <div class="cv-premium-card">
      <div class="cv-card__left">
        <span class="cv-card__badge">Service n°1, Très demandé</span>
        <h2 class="cv-card__title">Arrêtez de postuler<br>dans le vide.</h2>
        <p class="cv-card__hook">
          Un recruteur décide en <strong>7 secondes</strong>. Si votre CV ne retient pas l'attention immédiatement,
          votre candidature finit à la corbeille, même si vous êtes le meilleur candidat.
          <strong>Ne laissez plus passer vos chances.</strong>
        </p>
        <ul class="cv-card__features">
          <li>CV structuré, moderne et optimisé pour passer les filtres recruteurs</li>
          <li>Lettre de motivation ciblée et personnalisée à chaque poste</li>
          <li>Analyse complète de votre profil et de vos objectifs professionnels</li>
          <li>Livraison en 30min à 1h en format Word &amp; PDF, prêt à l'emploi</li>
          <li>1 révision gratuite incluse, jusqu'à votre entière satisfaction</li>
        </ul>
        <div class="cv-card__proof">
          <div class="cv-card__proof-avatars">
            @foreach(['MK','AB','FT','+'] as $av)
            <div class="cv-card__proof-avatar">{{ $av }}</div>
            @endforeach
          </div>
          <p class="cv-card__proof-text"><strong>+500 candidats</strong> ont décroché un entretien grâce à nous</p>
        </div>
        <a href="{{ route('service.commande', 'cv-professionnel') }}" class="cv-card__btn-primary">
          Je veux mon CV professionnel <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
      </div>
      <div class="cv-card__right">
        <span class="cv-card__right-badge">+500 commandes livrées</span>
        <p class="cv-card__price-label">Seulement</p>
        <p class="cv-card__price"><em>2 500</em> <span>FCFA</span></p>
        <p class="cv-card__price-sub">tout compris · paiement à la livraison</p>
        <div class="cv-card__delivery-badge">
          <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
          Livré en 30min à 1h
        </div>
        <p class="cv-card__guarantee">Satisfaction garantie<br>1 révision offerte</p>
        <a href="{{ route('service.commande', 'cv-professionnel') }}" class="cv-card__right-cta">
          Commander maintenant <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
        </a>
      </div>
    </div>

  </div>
</section>

{{-- ═══════════════════════════════════════════
     NOS 10 SERVICES
═══════════════════════════════════════════ --}}
<section class="section svc-all" id="nos-services">
  <div class="container">

    <div class="svc-section-hd">
      <span class="svc-section-badge">
        10 services disponibles
      </span>
      <h2 class="svc-section-title">Tout ce que nous faisons pour vous</h2>
      <p class="svc-section-sub">Des solutions complètes pour votre présence digitale, votre communication et votre réussite professionnelle.</p>
    </div>

    @php
    $svcList = [
      [
        'title'  => 'Création de sites web',
        'slug'   => 'creation-sites-web',
        'desc'   => 'E-commerce, vitrine ou plateforme professionnelle : nous concevons votre site de A à Z. Design moderne, rapide et optimisé pour convertir vos visiteurs en clients.',
        'tag'    => 'E-commerce · Vitrine',
        'color'  => '#3b82f6',
        'bg'     => '#eff6ff',
        'num'    => '01',
        'icon'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>',
      ],
      [
        'title'  => 'Gestion des réseaux sociaux',
        'slug'   => 'gestion-reseaux-sociaux',
        'desc'   => 'Confiez-nous vos pages professionnelles Facebook, Instagram et LinkedIn. Contenus, calendrier éditorial et engagement : on gère tout à votre place.',
        'tag'    => 'Facebook · Instagram · LinkedIn',
        'color'  => '#8b5cf6',
        'bg'     => '#f5f3ff',
        'num'    => '02',
        'icon'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.658m-6.632-6.342l6.632-3.658m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>',
      ],
      [
        'title'  => 'Marketing digital et publicité',
        'slug'   => 'marketing-digital',
        'desc'   => 'Facebook Ads, Instagram Ads, Google Ads : nous créons et gérons vos campagnes pour maximiser votre visibilité, attirer plus de clients et booster vos ventes.',
        'tag'    => 'Facebook Ads · Google Ads',
        'color'  => '#ef4444',
        'bg'     => '#fef2f2',
        'num'    => '03',
        'icon'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>',
      ],
      [
        'title'  => 'Référencement Google (SEO)',
        'slug'   => 'referencement-seo',
        'desc'   => 'Apparaissez en première page de Google ! Audit SEO, optimisation technique et rédaction de contenu : nous améliorons votre visibilité durablement, sans payer par clic.',
        'tag'    => 'Référencement naturel',
        'color'  => '#10b981',
        'bg'     => '#f0fdf4',
        'num'    => '04',
        'icon'   => '<circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="m21 21-4.35-4.35"/><path stroke-linecap="round" stroke-linejoin="round" d="M11 8v6M8 11h6"/>',
      ],
      [
        'title'  => 'Développement d\'applications web',
        'slug'   => 'developpement-applications',
        'desc'   => 'Applications web sur mesure, dashboards, CRM et plateformes métier. Des solutions techniques adaptées exactement à vos besoins spécifiques.',
        'tag'    => 'Sur mesure',
        'color'  => '#6366f1',
        'bg'     => '#eef2ff',
        'num'    => '05',
        'icon'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>',
      ],
      [
        'title'  => 'Formation en informatique',
        'slug'   => 'formation-informatique',
        'desc'   => 'Maîtrisez les outils essentiels : Word, Excel, PowerPoint, Internet et les applications bureautiques. Cours individuels ou en groupe, adaptés à votre niveau.',
        'tag'    => 'Word · Excel · PowerPoint',
        'color'  => '#14b8a6',
        'bg'     => '#f0fdfa',
        'num'    => '06',
        'icon'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"/>',
      ],
      [
        'title'  => 'Création de CV et documents admin.',
        'slug'   => 'creation-cv-documents',
        'desc'   => 'CV professionnel optimisé ATS, lettre de motivation personnalisée, profil LinkedIn soigné et autres documents administratifs. Livré en 30min à 1h.',
        'tag'    => 'Livré en 30min à 1h',
        'color'  => '#f59e0b',
        'bg'     => '#fffbeb',
        'num'    => '07',
        'icon'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>',
      ],
      [
        'title'  => 'Accompagnement digital des entreprises',
        'slug'   => 'accompagnement-digital',
        'desc'   => 'Stratégie digitale, présence en ligne et formation : nous accompagnons les entreprises et entrepreneurs dans leur transformation numérique complète.',
        'tag'    => 'Entreprises · Entrepreneurs',
        'color'  => '#0ea5e9',
        'bg'     => '#f0f9ff',
        'num'    => '08',
        'icon'   => '<path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>',
      ],
    ];
    @endphp

    <div class="svc-cards-grid">
      @foreach($svcList as $svc)
      <a href="{{ route('service.detail', ['service' => $svc['slug']]) }}" class="svc-card" style="--c:{{ $svc['color'] }};--cb:{{ $svc['bg'] }}">
        <div class="svc-card__top">
          <div class="svc-card__icon">
            <svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="{{ $svc['color'] }}" stroke-width="1.8">
              {!! $svc['icon'] !!}
            </svg>
          </div>
          <span class="svc-card__num">{{ $svc['num'] }}</span>
        </div>
        <h3 class="svc-card__title">{{ $svc['title'] }}</h3>
        <p class="svc-card__text">{{ $svc['desc'] }}</p>
        <div class="svc-card__footer">
          <span class="svc-card__tag">{{ $svc['tag'] }}</span>
          <span class="svc-card__link">
            Voir les détails
            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </span>
        </div>
      </a>
      @endforeach
    </div>

  </div>
</section>

{{-- ═══════════════════════════════════════════
     CTA CONTACT
═══════════════════════════════════════════ --}}
<section class="svc-cta-band">
  <div class="container">
    <div class="svc-cta-band__inner">
      <div class="svc-cta-band__left">
        <p class="svc-cta-band__eyebrow">Vous avez un projet ?</p>
        <h2 class="svc-cta-band__title">Parlons-en ensemble</h2>
        <p class="svc-cta-band__sub">Notre équipe vous répond en moins d'1 heure en semaine, par WhatsApp ou email.</p>
      </div>
      <div class="svc-cta-band__btns">
        <a href="https://wa.me/22951929856" target="_blank" rel="noopener" class="svc-cta-band__btn svc-cta-band__btn--wa">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
          WhatsApp
        </a>
        <a href="{{ route('contact') }}" class="svc-cta-band__btn svc-cta-band__btn--mail">
          <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
          Nous écrire
        </a>
      </div>
    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════
     WHATSAPP COMMUNAUTÉ
═══════════════════════════════════════════ --}}
<section class="section whatsapp-section">
  <div class="container">
    <div class="whatsapp-card">
      <div class="whatsapp-card__icon-wrap">
        <span class="whatsapp-pulse" aria-hidden="true"></span>
        <div class="whatsapp-card__icon">
          <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" fill="currentColor">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
          </svg>
        </div>
      </div>
      <div class="whatsapp-card__content">
        <h2 class="whatsapp-card__title">Rejoignez notre communauté WhatsApp</h2>
        <p class="whatsapp-card__text">Recevez les dernières opportunités en temps réel.</p>
        <a href="https://whatsapp.com/channel/0029VbCGlUo5q08ZH1bnm11F" target="_blank" rel="noopener noreferrer" class="btn btn--whatsapp">
          <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347"/></svg>
          Rejoindre notre chaîne WhatsApp
        </a>
      </div>
      <div class="whatsapp-stats">
        <div class="whatsapp-stat"><span class="whatsapp-stat__num">2 500+</span><span class="whatsapp-stat__label">Membres actifs</span></div>
        <div class="whatsapp-stat__divider"></div>
        <div class="whatsapp-stat"><span class="whatsapp-stat__num">Emploi</span><span class="whatsapp-stat__label">Nouvelles offres</span></div>
        <div class="whatsapp-stat__divider"></div>
        <div class="whatsapp-stat"><span class="whatsapp-stat__num">Gratuit</span><span class="whatsapp-stat__label">Accès libre</span></div>
      </div>
    </div>
  </div>
</section>

{{-- ═══════════════════════════════════════════
     NEWSLETTER
═══════════════════════════════════════════ --}}
<section class="newsletter">
  <div class="container">
    <div class="newsletter__inner">
      <div class="newsletter__content">
        <span class="newsletter__badge">
          <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
          Newsletter
        </span>
        <h2 class="newsletter__title">Ne ratez aucune<br><span>opportunité d'emploi</span></h2>
        <p class="newsletter__sub">Recevez chaque semaine les meilleures offres d'emploi au Bénin.</p>
        <div class="newsletter__stats">
          <div class="newsletter__stat">
            <div class="newsletter__stat-icon"><svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
            <div class="newsletter__stat-text"><span class="newsletter__stat-num">2 000+</span><span class="newsletter__stat-label">Abonnés actifs</span></div>
          </div>
          <div class="newsletter__stat">
            <div class="newsletter__stat-icon"><svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg></div>
            <div class="newsletter__stat-text"><span class="newsletter__stat-num">Chaque semaine</span><span class="newsletter__stat-label">Nouvelles offres</span></div>
          </div>
        </div>
      </div>
      <div class="newsletter__form-side">
        <form class="newsletter__form" action="{{ route('contact.envoyer') }}" method="POST">
          @csrf
          <input type="hidden" name="type" value="newsletter">
          <label class="newsletter__form-label">Votre adresse email</label>
          <div class="newsletter__form-group">
            <input type="email" name="email" class="newsletter__input" placeholder="exemple@email.com" required>
            <button type="submit" class="newsletter__btn">
              <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
              S'abonner gratuitement
            </button>
          </div>
          <p class="newsletter__privacy">Zéro spam. Désabonnement en un clic.</p>
        </form>
      </div>
    </div>
  </div>
</section>

@endsection

@section('scripts')
<script src="{{ asset('js/service/list-service.js') }}" defer></script>
@endsection
