@extends('layouts.app')
@section('title', 'À propos — Emploi Bouge Bénin')
@section('description', 'Découvrez la mission, les valeurs et l\'équipe fondatrice d\'Emploi Bouge Bénin.')

@section('css')
<link rel="stylesheet" href="{{ asset('css/a-propos.css') }}">
@endsection

@section('content')

  {{-- ═══════════════════════════════════════════
       PAGE HERO
  ═══════════════════════════════════════════ --}}
  <section class="section page-hero">
    <div class="container page-hero__inner">
      <span class="badge badge--blue">À propos</span>
      <h1 class="page-hero__title">Qui sommes-nous ?</h1>
      <p class="page-hero__subtitle">
        Emploi Bouge Bénin est une plateforme dédiée à la jeunesse africaine,
        connectant talents et opportunités à travers le continent et au-delà.
      </p>
    </div>
  </section>

  {{-- ═══════════════════════════════════════════
       SECTION : NOTRE MISSION
  ═══════════════════════════════════════════ --}}
  <section class="mission-section" id="mission">
    <div class="container">

      <div class="mission__header">
        <span class="badge badge--yellow">Notre raison d'être</span>
        <h2 class="mission__title">Notre Mission <em>Éducative</em></h2>

        <blockquote class="mission__quote">
          <p class="mission__quote-text">
            Transformer l'Afrique par l'éducation, l'information et la formation
          </p>
        </blockquote>

        <p class="mission__body">
          Fondée par Gildas Hyacinthe KPOGBOZAN, notre plateforme s'engage à rendre l'éducation
          de qualité accessible à tous les Africains. Nous croyons que chaque jeune mérite
          les outils, les ressources et les connexions nécessaires pour réaliser son plein
          potentiel — qu'il soit en ville ou dans les zones rurales, débutant ou expérimenté.
          Notre mission est de briser les barrières, d'ouvrir des portes et de bâtir une
          génération africaine consciente, compétente et ambitieuse.
        </p>
      </div>

      {{-- Statistiques --}}
      <div class="stats-grid">
        @foreach([
          ['50K+', 'Apprenants actifs'],
          ['95%',  'Taux de satisfaction'],
          ['25+',  'Pays africains desservis'],
          ['10K+', 'Projets réalisés'],
          ['85%',  'Taux d\'emploi après formation'],
          ['4.9/5','Note moyenne'],
        ] as [$num, $label])
        <div class="stat-card">
          <span class="stat-card__num">{{ $num }}</span>
          <span class="stat-card__label">{{ $label }}</span>
        </div>
        @endforeach
      </div>

    </div>
  </section>

  {{-- ═══════════════════════════════════════════
       SECTION : NOTRE FONDATEUR
  ═══════════════════════════════════════════ --}}
  <section class="founder-section" id="fondateur">
    <div class="container">

      <div class="founder__header">
        <p class="founder__header-badge">L'équipe fondatrice</p>
        <h2 class="founder__header-title">Notre <em>Fondateur</em></h2>
      </div>

      <div class="founder-card-new">

        {{-- Panneau gauche — bleu foncé --}}
        <div class="founder-panel-left">

          <div class="founder-photo-wrap">
            <div class="founder-photo-ring"></div>
            <div class="founder-photo-bg"></div>
            <div class="founder-photo-frame">
              @if(file_exists(public_path('images/fondateur.jpg')))
                <img src="{{ asset('images/fondateur.jpg') }}" alt="Gildas Hyacinthe KPOGBOZAN" />
              @else
                <span class="founder-photo__placeholder" aria-hidden="true">👤</span>
              @endif
            </div>
          </div>

          <h3 class="founder-panel-left__name">Gildas Hyacinthe<br>KPOGBOZAN</h3>
          <span class="founder-panel-left__role">CEO &amp; Fondateur</span>

          <div class="founder-panel-stats">
            @foreach([
              ['50K+', 'Apprenants accompagnés'],
              ['25+',  'Pays touchés en Afrique'],
              ['95%',  'Taux de satisfaction'],
              ['3 ans','D\'engagement continu'],
            ] as [$n, $l])
            <div class="founder-panel-stat">
              <span class="founder-panel-stat__num">{{ $n }}</span>
              <span class="founder-panel-stat__label">{{ $l }}</span>
            </div>
            @endforeach
          </div>

        </div>

        {{-- Panneau droit — blanc --}}
        <div class="founder-panel-right">

          <p class="founder-panel-right__overline">Notre Fondateur</p>
          <h2 class="founder-panel-right__title">Gildas Hyacinthe<br><em>KPOGBOZAN</em></h2>

          <blockquote class="founder-quote">
            <p>L'accès à l'information et à la formation peut transformer des vies — et toute une génération.</p>
          </blockquote>

          <p class="founder-panel-right__bio">
            Jeune visionnaire passionné d'éducation et de développement de la jeunesse africaine,
            Gildas Hyacinthe KPOGBOZAN a fondé Emploi Bouge Bénin avec la conviction profonde
            que chaque jeune mérite les outils nécessaires pour réaliser son plein potentiel.
            Animé par la volonté de réduire le chômage des jeunes en Afrique, il a bâti une
            plateforme qui réunit recruteurs, talents et formateurs autour d'un même objectif :
            construire une Afrique plus compétente et plus prospère.
          </p>

          <div class="founder-panel-right__tags">
            @foreach(['Éducation','Afrique','Recrutement','Innovation'] as $tag)
            <span class="founder-tag-pill">{{ $tag }}</span>
            @endforeach
          </div>

          <div class="founder-panel-right__actions">
            <a href="{{ route('contact') }}" class="founder-btn-primary">
              <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
              Nous contacter
            </a>
            <a href="https://whatsapp.com/channel/0029VbCGlUo5q08ZH1bnm11F" target="_blank" rel="noopener noreferrer" class="founder-btn-outline">
              <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347"/></svg>
              Notre chaîne WhatsApp
            </a>
          </div>

        </div>

      </div>
    </div>
  </section>

  {{-- ═══════════════════════════════════════════
       SECTION : NOS VALEURS
  ═══════════════════════════════════════════ --}}
  <section class="values-section" id="valeurs">
    <div class="container">

      <div class="values__header">
        <span class="badge badge--yellow">Ce qui nous guide</span>
        <h2 class="values__title">Nos <em>Valeurs</em></h2>
        <p class="values__subtitle">Les principes qui guident chacune de nos actions</p>
      </div>

      <div class="values-grid">

        <div class="value-card">
          <div class="value-card__icon">
            <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
            </svg>
          </div>
          <h3 class="value-card__title">Excellence Académique</h3>
          <p class="value-card__text">Nous maintenons les plus hauts standards de qualité dans chaque contenu, chaque formation et chaque accompagnement que nous proposons.</p>
        </div>

        <div class="value-card">
          <div class="value-card__icon">
            <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z"/>
            </svg>
          </div>
          <h3 class="value-card__title">Accessibilité</h3>
          <p class="value-card__text">L'éducation et l'emploi doivent être accessibles à tous, sans barrière géographique, économique ou sociale. C'est notre engagement fondamental.</p>
        </div>

        <div class="value-card">
          <div class="value-card__icon">
            <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
            </svg>
          </div>
          <h3 class="value-card__title">Innovation Continue</h3>
          <p class="value-card__text">Nous évoluons constamment pour répondre aux besoins changeants du marché africain et offrir des solutions toujours plus pertinentes et efficaces.</p>
        </div>

        <div class="value-card">
          <div class="value-card__icon">
            <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
          </div>
          <h3 class="value-card__title">Communauté Solidaire</h3>
          <p class="value-card__text">Nous cultivons un écosystème bienveillant où chaque membre peut grandir, s'entraider et contribuer au développement collectif de l'Afrique.</p>
        </div>

      </div>
    </div>
  </section>

  {{-- ═══════════════════════════════════════════
       WHATSAPP
  ═══════════════════════════════════════════ --}}
  <section class="section whatsapp-section">
    <div class="container">
      <div class="whatsapp-card">
        <div class="whatsapp-card__icon-wrap">
          <span class="whatsapp-pulse" aria-hidden="true"></span>
          <div class="whatsapp-card__icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
              <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
          </div>
        </div>
        <div class="whatsapp-card__content">
          <h2 class="whatsapp-card__title">Rejoignez notre communauté WhatsApp</h2>
          <p class="whatsapp-card__text">Recevez les dernières opportunités en temps réel et échangez avec des centaines de jeunes ambitieux.</p>
          <a href="https://whatsapp.com/channel/0029VbCGlUo5q08ZH1bnm11F" target="_blank" rel="noopener noreferrer" class="btn btn--whatsapp">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
              <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347"/>
            </svg>
            Rejoindre notre chaîne WhatsApp
          </a>
        </div>
        <div class="whatsapp-stats">
          <div class="whatsapp-stat">
            <span class="whatsapp-stat__num">2 500+</span>
            <span class="whatsapp-stat__label">Membres actifs</span>
          </div>
          <div class="whatsapp-stat__divider"></div>
          <div class="whatsapp-stat">
            <span class="whatsapp-stat__num">Emploi</span>
            <span class="whatsapp-stat__label">Nouvelles offres</span>
          </div>
          <div class="whatsapp-stat__divider"></div>
          <div class="whatsapp-stat">
            <span class="whatsapp-stat__num">Gratuit</span>
            <span class="whatsapp-stat__label">Accès libre</span>
          </div>
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
          <p class="newsletter__sub">Recevez chaque semaine les meilleures offres d'emploi au Bénin, des conseils carrière exclusifs et les actualités du marché du travail.</p>
          <div class="newsletter__stats">
            <div class="newsletter__stat">
              <div class="newsletter__stat-icon">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              </div>
              <div class="newsletter__stat-text">
                <span class="newsletter__stat-num">2 000+</span>
                <span class="newsletter__stat-label">Abonnés actifs</span>
              </div>
            </div>
            <div class="newsletter__stat">
              <div class="newsletter__stat-icon">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
              </div>
              <div class="newsletter__stat-text">
                <span class="newsletter__stat-num">Chaque semaine</span>
                <span class="newsletter__stat-label">Nouvelles offres</span>
              </div>
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
<script src="{{ asset('js/a-propos.js') }}" defer></script>
@endsection
