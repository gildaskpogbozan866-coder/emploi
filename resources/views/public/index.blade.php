@extends('layouts.app')
@section('title', 'Emploi Bénin | Offres d\'emploi, recrutement & stages au Bénin | Emploi Bouge Bénin')
@section('description', 'Trouvez un emploi au Bénin sur Emploi Bouge Bénin : offres CDI, CDD, stages, bourses à Cotonou
    et partout au Bénin. Déposez votre CV gratuitement et soyez visible des recruteurs.')
@section('og_title', 'Emploi Bénin, Offres d\'emploi & recrutement | Emploi Bouge Bénin')
@section('og_description', 'La plateforme #1 pour trouver un emploi au Bénin. Offres vérifiées, dépôt CV gratuit,
    recrutement Cotonou & toutes villes du Bénin.')

@section('jsonld')
    @php
        $orgSchema = [
            '@context' => 'https://schema.org',
            '@graph' => [
                [
                    '@type' => 'Organization',
                    '@id' => route('home') . '#organization',
                    'name' => 'Emploi Bouge Bénin',
                    'url' => route('home'),
                    'logo' => [
                        '@type' => 'ImageObject',
                        'url' => asset('images/Logo.png'),
                    ],
                    'description' =>
                        'Plateforme d\'emploi au Bénin : offres d\'emploi, recrutement, dépôt de CV et services carrière.',
                    'address' => [
                        '@type' => 'PostalAddress',
                        'addressLocality' => 'Cotonou',
                        'addressCountry' => 'BJ',
                    ],
                    'contactPoint' => [
                        '@type' => 'ContactPoint',
                        'contactType' => 'customer support',
                        'email' => 'contact@emploibouge.bj',
                    ],
                    'sameAs' => ['https://whatsapp.com/channel/0029VbCGlUo5q08ZH1bnm11F'],
                ],
                [
                    '@type' => 'WebSite',
                    '@id' => route('home') . '#website',
                    'name' => 'Emploi Bouge Bénin',
                    'url' => route('home'),
                    'inLanguage' => 'fr-FR',
                    'description' => 'Plateforme d\'emploi et de recrutement au Bénin',
                    'potentialAction' => [
                        '@type' => 'SearchAction',
                        'target' => [
                            '@type' => 'EntryPoint',
                            'urlTemplate' => route('offre.list') . '?q={search_term_string}',
                        ],
                        'query-input' => 'required name=search_term_string',
                    ],
                ],
            ],
        ];
        echo '<script type="application/ld+json">' . json_encode($orgSchema, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';
    @endphp
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')

    {{-- ═══════════════════════════════════════════
     HERO
═══════════════════════════════════════════ --}}
    <section id="accueil" class="hero">
        <div class="hero-container">

            <div class="hero-left">
                <h1 class="hero-title">
                    Votre prochain emploi<br>
                    est déjà publié,<br>
                    <span class="accent">trouvez-le maintenant.</span>
                </h1>
                <p class="hero-sub">
                    <strong>La plateforme d'emploi numéro 1 au Bénin.</strong><br>
                    Des centaines d'offres vérifiées chaque semaine à <strong>Cotonou</strong> et dans tout le
                    <strong>Bénin</strong>, CDI, CDD, stages, bourses et freelance.<br>
                    Candidatez en quelques clics. Déposez votre CV gratuitement. Soyez contacté par les meilleurs
                    recruteurs.
                </p>

                <div class="hs2-wrap">
                    <div class="hs2-card">
                        <div class="hs2-row">
                            <div class="hs2-input-wrap">
                                <svg class="hs2-input-icon" width="18" height="18" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2.5">
                                    <circle cx="11" cy="11" r="8" />
                                    <path d="m21 21-4.35-4.35" />
                                </svg>
                                <input type="text" id="heroSearch" class="hs2-input"
                                    placeholder="Titre de poste, compétences, entreprise…" autocomplete="off" />
                            </div>
                            <button class="hs2-btn" id="heroSearchBtn">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                    stroke-width="2.5">
                                    <circle cx="11" cy="11" r="8" />
                                    <path d="m21 21-4.35-4.35" />
                                </svg>
                                <span>Rechercher</span>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="hero-actions">
                    <a href="{{ route('offre.list') }}" class="btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8" />
                            <path d="m21 21-4.35-4.35" />
                        </svg>
                        Parcourir les offres
                    </a>
                    @if (!auth()->check() || auth()->user()->hasRole('candidat'))
                        <a href="{{ auth()->check() ? auth()->check() && auth()->user()->hasRole('candidat') ? route('candidat.profil') : route('auth.inscription').'?role=candidat' : route('auth.inscription') . '?role=candidat' }}"
                            class="btn-hero-cv">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                            </svg>
                            Déposer mon CV
                        </a>
                    @endif
                    @if (!auth()->check() || auth()->user()->hasRole('recruteur'))
                        <a href="{{ auth()->check() ? route('offre.publier') : route('auth.inscription') . '?role=recruteur' }}"
                            class="btn-hero-outline">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            Publier une annonce
                        </a>
                    @endif
                    @if (!auth()->check() || auth()->user()->hasRole('annonceur'))
                        <a href="{{ auth()->check() ? route('annonceur.publicites') : route('auth.inscription') . '?role=annonceur' }}"
                            class="btn-hero-outline"
                            style="background:rgba(245,200,66,.12);border-color:#F5C842;color:#b8860b">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2.5">
                                <rect x="3" y="3" width="18" height="13" rx="2" />
                                <path d="M8 21h8M12 17v4" />
                            </svg>
                            Publier une Affiche Publicitaire
                        </a>
                    @endif
                </div>
            </div>

            <div class="hero-right">
                <div class="hero-visual">
                    <svg viewBox="0 0 460 420" xmlns="http://www.w3.org/2000/svg" class="hero-svg">
                        <circle cx="230" cy="210" r="185" fill="#EBF4FD" opacity="0.55" />
                        <circle cx="230" cy="210" r="138" fill="#378ADD" opacity="0.05" />
                        <rect x="55" y="292" width="350" height="13" rx="6.5" fill="#185FA5" opacity="0.18" />
                        <rect x="95" y="303" width="11" height="38" rx="4" fill="#378ADD"
                            opacity="0.18" />
                        <rect x="354" y="303" width="11" height="38" rx="4" fill="#378ADD"
                            opacity="0.18" />
                        <rect x="138" y="208" width="184" height="118" rx="10" fill="#042C53" />
                        <rect x="146" y="216" width="168" height="103" rx="6" fill="#185FA5" />
                        <rect x="153" y="223" width="154" height="89" rx="4" fill="#EBF4FD" />
                        <rect x="153" y="223" width="154" height="17" rx="4" fill="#378ADD" />
                        <text x="230" y="234" text-anchor="middle" font-family="Jost,sans-serif" font-size="6.5"
                            font-weight="600" fill="white">Tableau de bord recruteur</text>
                        <rect x="158" y="245" width="64" height="32" rx="4" fill="white" />
                        <circle cx="170" cy="256" r="7" fill="#378ADD" opacity="0.25" />
                        <rect x="180" y="251" width="33" height="4" rx="2" fill="#042C53"
                            opacity="0.55" />
                        <rect x="180" y="258" width="24" height="3" rx="1.5" fill="#A0AEC0" />
                        <rect x="162" y="268" width="18" height="5" rx="2.5" fill="#F5C842" />
                        <text x="171" y="272" text-anchor="middle" font-family="Jost,sans-serif" font-size="4.5"
                            font-weight="700" fill="#6B4800">CDI</text>
                        <rect x="228" y="245" width="64" height="32" rx="4" fill="white" />
                        <circle cx="240" cy="256" r="7" fill="#F5C842" opacity="0.4" />
                        <rect x="250" y="251" width="33" height="4" rx="2" fill="#042C53"
                            opacity="0.55" />
                        <rect x="250" y="258" width="24" height="3" rx="1.5" fill="#A0AEC0" />
                        <rect x="232" y="268" width="22" height="5" rx="2.5" fill="#378ADD" />
                        <text x="243" y="272" text-anchor="middle" font-family="Jost,sans-serif" font-size="4.5"
                            font-weight="700" fill="white">Stage</text>
                        <rect x="130" y="254" width="60" height="76" rx="5" fill="white"
                            stroke="#378ADD" stroke-width="1.5" />
                        <rect x="130" y="254" width="60" height="18" rx="5" fill="#378ADD" />
                        <text x="160" y="266" text-anchor="middle" font-family="Jost,sans-serif" font-size="6"
                            font-weight="700" fill="white">CV</text>
                        <rect x="136" y="278" width="36" height="3.5" rx="1.5" fill="#042C53"
                            opacity="0.6" />
                        <rect x="136" y="285" width="46" height="3" rx="1.5" fill="#A0AEC0" />
                        <rect x="136" y="291" width="40" height="3" rx="1.5" fill="#A0AEC0" />
                        <circle cx="178" cy="316" r="9" fill="#38A169" />
                        <path d="M172 316 L176 320 L184 310" stroke="white" stroke-width="2" fill="none"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <polygon points="422,125 425,136 436,136 427,143 430,154 422,147 414,154 417,143 408,136 419,136"
                            fill="#F5C842" opacity="0.85" />
                        <circle cx="230" cy="72" r="7" fill="#F5C842" opacity="0.45" />
                    </svg>
                </div>
            </div>

        </div>
    </section>

    {{-- ═══════════════════════════════════════════
     DOUBLE CTA
═══════════════════════════════════════════ --}}
    <section class="split-cta-section">
        <div class="split-cta-inner">
            <span class="split-cta-eyebrow">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                Par où commencer ?
            </span>
            <h2 class="split-cta-title">Candidat, Recruteur ou Annonceur ?</h2>
            <p class="split-cta-sub">Que vous cherchiez un emploi, recrutiez des talents ou souhaitiez diffuser une
                publicité, une seule plateforme pour tout.</p>
            <div class="split-cta-btns">
                @if (!auth()->check() || auth()->user()->hasRole('candidat'))
                    <a href="{{ auth()->check() ? auth()->check() && auth()->user()->hasRole('candidat') ? route('candidat.profil') : route('auth.inscription').'?role=candidat' : route('auth.inscription') . '?role=candidat' }}"
                        class="split-cta-btn split-cta-btn--candidat">
                        <span class="split-cta-btn__icon"><svg width="26" height="26" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg></span>
                        <span class="split-cta-btn__title">Je cherche du travail</span>
                        <span class="split-cta-btn__sub">CDI · CDD · Stage · Bourse · Freelance · Compétences</span>
                    </a>
                @endif
                @if (!auth()->check() || auth()->user()->hasRole('recruteur'))
                    <a href="{{ auth()->check() ? route('offre.publier') : route('auth.inscription') . '?role=recruteur' }}"
                        class="split-cta-btn split-cta-btn--recruteur">
                        <span class="split-cta-badge">Recommandé</span>
                        <span class="split-cta-btn__icon"><svg width="26" height="26" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg></span>
                        <span class="split-cta-btn__title">Je recrute</span>
                        <span class="split-cta-btn__sub">Publiez une annonce, accédez aux CV et profils</span>
                    </a>
                @endif
                @if (!auth()->check() || auth()->user()->hasRole('annonceur'))
                    <a href="{{ auth()->check() ? route('annonceur.publicites') : route('auth.inscription') . '?role=annonceur' }}"
                        class="split-cta-btn"
                        style="background:#fff;border:2px solid #F5C842;color:#042C53;position:relative">
                        <span class="split-cta-btn__icon" style="background:rgba(245,200,66,.15)"><svg width="26"
                                height="26" fill="none" viewBox="0 0 24 24" stroke="#b8860b" stroke-width="1.8">
                                <rect x="3" y="3" width="18" height="13" rx="2" />
                                <path d="M8 21h8M12 17v4" />
                            </svg></span>
                        <span class="split-cta-btn__title" style="color:#042C53">Je publie une publicité</span>
                        <span class="split-cta-btn__sub" style="color:#64748b">Affichage de votre marque devant des
                            milliers de visiteurs</span>
                    </a>
                @endif
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════
     OBSTACLES
═══════════════════════════════════════════ --}}
    <section style="padding:80px 20px;background:#fff">
        <div style="max-width:1100px;margin:0 auto">
            <style>
                .obs-wrap {
                    display: grid;
                    grid-template-columns: 1fr 1.4fr;
                    gap: 64px;
                    align-items: center
                }

                .obs-cards {
                    display: grid;
                    grid-template-columns: 1fr 1fr;
                    gap: 16px
                }

                @media(max-width:860px) {
                    .obs-wrap {
                        grid-template-columns: 1fr;
                        gap: 36px
                    }

                    .obs-cards {
                        grid-template-columns: 1fr 1fr
                    }
                }

                @media(max-width:480px) {
                    .obs-cards {
                        grid-template-columns: 1fr
                    }
                }
            </style>
            <div class="obs-wrap">

                {{-- Côté gauche, Texte --}}
                <div>
                    <span
                        style="display:inline-block;background:#eff6ff;color:#185FA5;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;padding:5px 14px;border-radius:99px;margin-bottom:20px">Les
                        défis</span>
                    <h2
                        style="font-size:clamp(1.5rem,2.5vw,2rem);font-weight:800;color:#042C53;line-height:1.25;margin:0 0 16px">
                        Obstacles rencontrés<br>par les jeunes Africains
                    </h2>
                    <p style="font-size:14.5px;color:#64748b;line-height:1.75;margin:0 0 28px">
                        Quatre principaux défis limitent l'accès aux opportunités en Afrique. Emploi Bouge Bénin a été conçu
                        pour lever ces barrières, une à une.
                    </p>
                    <div
                        style="display:flex;align-items:center;gap:10px;padding:14px 18px;background:#f8fafc;border-left:3px solid #185FA5;border-radius:0 8px 8px 0">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#185FA5"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p style="font-size:13px;color:#374151;margin:0;font-weight:500">Notre plateforme répond à chacun
                            de ces obstacles.</p>
                    </div>
                </div>

                {{-- Côté droit, 4 cartes --}}
                <div class="obs-cards">
                    @foreach ([['01', 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'Informations peu fiables', 'Sources non vérifiées, données obsolètes et dispersées.'], ['02', 'M13 10V3L4 14h7v7l9-11h-7z', 'Démarches complexes', 'Procédures longues, exigences floues, risques d\'erreur.'], ['03', 'M17 20h5v-2a4 4 0 00-4-4h-1M9 20H4v-2a4 4 0 014-4h1m4-4a4 4 0 100-8 4 4 0 000 8z', 'Ressources inadaptées', 'Contenus éloignés des réalités locales africaines.'], ['04', 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'Manque de réseau', 'Pas de mentorat, isolement, conseils trop génériques.']] as [$num, $path, $titre, $desc])
                        <div style="background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:14px;padding:22px 18px;position:relative;transition:box-shadow .2s,border-color .2s"
                            onmouseover="this.style.boxShadow='0 6px 24px rgba(24,95,165,.1)';this.style.borderColor='#bfdbfe'"
                            onmouseout="this.style.boxShadow='none';this.style.borderColor='#e2e8f0'">
                            <span
                                style="position:absolute;top:14px;right:16px;font-size:10px;font-weight:800;color:#cbd5e1;letter-spacing:.06em">{{ $num }}</span>
                            <div
                                style="width:40px;height:40px;background:#fff;border:1.5px solid #e2e8f0;border-radius:10px;display:flex;align-items:center;justify-content:center;margin-bottom:14px;box-shadow:0 1px 4px rgba(0,0,0,.06)">
                                <svg width="19" height="19" fill="none" viewBox="0 0 24 24" stroke="#185FA5"
                                    stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $path }}" />
                                </svg>
                            </div>
                            <h3 style="font-size:13.5px;font-weight:700;color:#042C53;margin:0 0 6px;line-height:1.3">
                                {{ $titre }}</h3>
                            <p style="font-size:12.5px;color:#64748b;line-height:1.6;margin:0">{{ $desc }}</p>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════
     NOS SERVICES
═══════════════════════════════════════════ --}}
    <section id="services" class="section services-section">
        <div class="container">
            <div class="section-header section-header--center">
                <span class="badge badge--yellow">Nos services</span>
                <h2 class="section-title">Nos services d'<span class="text-accent">accompagnement</span></h2>
                <p class="section-subtitle">Tout ce dont vous avez besoin pour décrocher votre opportunité professionnelle.
                </p>
            </div>

            <div class="formation-section">
                <article class="formation-card">
                    <div class="formation-card__left">
                        <span class="formation-card__tag">Service n°1, Très demandé</span>
                        <h3 class="formation-card__title">
                            CV Professionnel <em>&amp; Lettre de Motivation</em><br>
                            <span style="font-size:0.75em;color:#64748b;font-weight:600;">Arrêtez de postuler dans le
                                vide.</span>
                        </h3>
                        <p class="formation-card__hook">
                            Un recruteur décide en <strong>7 secondes</strong>. Nos experts rédigent pour vous un CV
                            percutant et une lettre convaincante <strong>adaptés au marché africain</strong>.
                        </p>
                        <ul class="formation-card__features">
                            @foreach (['Analyse complète de votre profil et de vos objectifs professionnels', 'CV structuré, moderne et optimisé pour passer les filtres ATS', 'Lettre de motivation personnalisée et convaincante', 'Livraison Word &amp; PDF prêt à l\'emploi sous 48h', '1 révision gratuite incluse après livraison'] as $f)
                                <li class="formation-card__feature">
                                    <span class="formation-card__check"><svg width="10" height="10"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg></span>
                                    {!! $f !!}
                                </li>
                            @endforeach
                        </ul>
                        <div class="formation-card__actions">
                            <a href="{{ route('service.commande', 'cv-professionnel') }}" class="fc-btn-primary">
                                Je veux mon CV professionnel
                                <svg width="15" height="15" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </a>
                            <a href="{{ route('service.detail', 'cv-professionnel') }}" class="fc-btn-outline">Voir les
                                détails</a>
                        </div>
                    </div>
                    <div class="formation-card__right">
                        <span class="formation-card__badge-hot">+500 commandes</span>
                        <div class="formation-card__price-box">
                            <span class="formation-card__price-label">Seulement</span>
                            <span class="formation-card__price">2 500</span>
                            <span class="formation-card__currency">FCFA tout compris</span>
                        </div>
                        <div class="formation-card__delivery">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Livré sous 48h
                        </div>
                        <div class="formation-card__guarantee">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            Satisfaction garantie · 1 révision offerte
                        </div>
                    </div>
                </article>
            </div>

            <div class="services-cta">
                <a href="{{ route('service.list') }}" class="btn btn--yellow">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                    Découvrir tous nos services
                </a>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════
     DERNIÈRES OFFRES
═══════════════════════════════════════════ --}}
    <section id="offres" class="section offres-section">
        <div class="container">
            <div class="offres-header">
                <div>
                    <span class="badge badge--yellow">Opportunités</span>
                    <h2 class="section-title" style="margin-top:10px">Dernières offres publiées</h2>
                    <p class="section-subtitle">Les offres les plus récentes, mises à jour en continu.</p>
                </div>
                <a href="{{ route('offre.list') }}" class="btn btn--blue offres-header__cta" style="color: #F5C842;">
                    Voir toutes les offres
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>

            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:16px">
                @forelse($offres as $offre)
                    <a href="{{ route('offre.detail', $offre) }}"
                        style="display:block;background:#fff;border:1.5px solid #e8edf3;border-radius:16px;padding:20px;text-decoration:none;transition:box-shadow .18s,transform .18s;box-shadow:0 2px 8px rgba(4,44,83,.06)"
                        onmouseover="this.style.boxShadow='0 8px 28px rgba(4,44,83,.13)';this.style.transform='translateY(-2px)'"
                        onmouseout="this.style.boxShadow='0 2px 8px rgba(4,44,83,.06)';this.style.transform='none'">

                        {{-- En-tête : logo + titre + entreprise + date --}}
                        <div style="display:flex;align-items:flex-start;gap:12px;margin-bottom:12px">
                            <div
                                style="flex-shrink:0;width:48px;height:48px;border-radius:12px;background:linear-gradient(135deg,#e8f0fe,#dbeafe);border:1.5px solid #c7d7f8;display:flex;align-items:center;justify-content:center;overflow:hidden">
                                @if ($offre->logo)
                                    <img src="{{ asset('storage/' . $offre->logo) }}" alt="{{ $offre->entreprise }}"
                                        style="width:100%;height:100%;object-fit:contain;padding:4px">
                                @else
                                    <span
                                        style="font-size:1.05rem;font-weight:800;color:#185FA5;letter-spacing:-1px">{{ strtoupper(substr($offre->entreprise, 0, 2)) }}</span>
                                @endif
                            </div>
                            <div style="flex:1;min-width:0">
                                <div
                                    style="font-size:14px;font-weight:700;color:#042C53;line-height:1.3;margin-bottom:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                                    {{ $offre->titre }}</div>
                                <div
                                    style="font-size:12px;color:#185FA5;font-weight:600;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                                    {{ $offre->entreprise }}</div>
                            </div>
                            <div style="flex-shrink:0;font-size:10.5px;color:#94a3b8;white-space:nowrap">
                                {{ $offre->created_at->diffForHumans() }}</div>
                        </div>

                        {{-- Description --}}
                        <p
                            style="font-size:12.5px;color:#64748b;line-height:1.6;margin:0 0 12px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden">
                            {{ Str::limit(strip_tags($offre->description), 110) }}</p>

                        {{-- Badges + CTA --}}
                        <div
                            style="display:flex;align-items:center;gap:5px;flex-wrap:wrap;padding-top:12px;border-top:1px solid #f1f5f9">
                            @if ($offre->type)
                                <span
                                    style="font-size:10.5px;font-weight:700;padding:2px 8px;border-radius:20px;background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe">{{ $offre?->type?->libelle }}</span>
                            @endif
                            <span
                                style="font-size:10.5px;font-weight:600;padding:2px 8px;border-radius:20px;background:#f1f5f9;color:#475569;display:inline-flex;align-items:center;gap:3px">
                                <svg width="9" height="9" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                </svg>
                                {{ $offre->localisation }}
                            </span>
                            @if ($offre->salaireFormate())
                                <span
                                    style="font-size:10.5px;font-weight:600;padding:2px 8px;border-radius:20px;background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0">{{ $offre->salaireFormate() }}</span>
                            @endif
                            <span
                                style="font-size:11.5px;font-weight:700;color:#185FA5;margin-left:auto;display:inline-flex;align-items:center;gap:3px">
                                Voir l'offre
                                <svg width="11" height="11" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </span>
                        </div>

                    </a>
                @empty
                    <p style="color:#64748b;padding:32px 0;text-align:center;grid-column:1/-1">Aucune offre publiée pour
                        l'instant.</p>
                @endforelse
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════
     DERNIERS CV
═══════════════════════════════════════════ --}}
    <section class="cv-section">
        <div class="container">
            <div class="offres-header">
                <div>
                    <span class="badge badge--yellow">CV récents</span>
                    <h2 class="section-title" style="margin-top:10px">Derniers profils déposés</h2>
                    <p class="section-subtitle">Des candidats actifs, emploi, stage, bourse ou freelance.</p>
                </div>
                <a href="{{ route('cv.public.theque') }}" class="btn btn--blue offres-header__cta"
                    style="color: #F5C842;">
                    Accéder aux profils
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </a>
            </div>

            <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(340px,1fr));gap:24px">
                @foreach ($candidats as $item)
                    @php
                        $profil = $item->candidatProfil;
                        $formation = $item->formations->first();
                        $libelles = \App\Models\CandidatProfil::libelles();
                        $cvItem = $item->cvs->first();
                        $cvUrl  = $cvItem ? route('cv.public.detail', $cvItem) : route('cv.public.theque');
                    @endphp

                    <a href="{{ $cvUrl }}"
                        style="display:flex;flex-direction:column;background:#fff;border:1.5px solid #e8edf3;border-radius:20px;padding:28px;text-decoration:none;transition:box-shadow .2s,transform .2s;box-shadow:0 4px 16px rgba(4,44,83,.07)"
                        onmouseover="this.style.boxShadow='0 12px 40px rgba(4,44,83,.16)';this.style.transform='translateY(-3px)'"
                        onmouseout="this.style.boxShadow='0 4px 16px rgba(4,44,83,.07)';this.style.transform='none'">

                        {{-- En-tête : avatar anonyme + titre professionnel --}}
                        <div style="display:flex;align-items:center;gap:16px;margin-bottom:18px">

                            {{-- Avatar anonyme --}}
                            <div
                                style="width:56px;height:56px;flex-shrink:0;border-radius:50%;background:linear-gradient(135deg,#e8f0fe,#dbeafe);display:flex;align-items:center;justify-content:center;border:2px solid #e8edf3">
                                <svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="#94a3b8"
                                    stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>

                            {{-- Titre professionnel + localisation --}}
                            <div style="min-width:0;flex:1">
                                @if ($profil?->titre_professionnel)
                                    <div
                                        style="font-size:15px;font-weight:800;color:#042C53;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                                        {{ $profil->titre_professionnel }}
                                    </div>
                                @else
                                    <div style="font-size:15px;font-weight:800;color:#94a3b8;font-style:italic">
                                        Profil sans titre
                                    </div>
                                @endif

                                @if ($item->pays || $profil?->ville)
                                    <div
                                        style="display:flex;align-items:center;gap:4px;font-size:12px;color:#94a3b8;margin-top:4px">
                                        <svg width="11" height="11" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <circle cx="12" cy="11" r="3" />
                                        </svg>
                                        {{ collect([$profil?->ville, $item->pays])->filter()->join(', ') }}
                                    </div>
                                @endif
                            </div>

                            @if ($item->premium)
                                <span
                                    style="flex-shrink:0;font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;background:#fef9c3;color:#854d0e;border:1px solid #fde68a">★
                                    Premium</span>
                            @endif
                        </div>

                        <div style="height:1px;background:#f1f5f9;margin-bottom:16px"></div>

                        <div style="display:flex;flex-direction:column;gap:14px;flex:1">

                            {{-- Expérience + Disponibilité --}}
                            <div>
                                <div
                                    style="font-size:10.5px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.6px;margin-bottom:6px">
                                    Disponibilité & expérience</div>
                                <div style="display:flex;gap:6px;flex-wrap:wrap">
                                    @if ($profil?->annees_experience !== null)
                                        <span
                                            style="display:inline-flex;align-items:center;gap:5px;font-size:12px;font-weight:600;padding:4px 10px;border-radius:20px;background:#f0f7ff;color:#185FA5;border:1px solid #bfdbfe">
                                            <svg width="11" height="11" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <rect x="2" y="7" width="20" height="14" rx="2" />
                                                <path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2" />
                                            </svg>
                                            {{ $profil->annees_experience }}
                                            an{{ $profil->annees_experience > 1 ? 's' : '' }} d'exp.
                                        </span>
                                    @endif
                                    @if ($profil?->disponibilite)
                                        <span
                                            style="display:inline-flex;align-items:center;gap:5px;font-size:12px;font-weight:600;padding:4px 10px;border-radius:20px;background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0">
                                            <svg width="9" height="9" viewBox="0 0 8 8" fill="#16a34a">
                                                <circle cx="4" cy="4" r="4" />
                                            </svg>
                                            {{ $libelles['disponibilite'][$profil->disponibilite] ?? '' }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Dernière formation --}}
                            @if ($formation)
                                <div>
                                    <div
                                        style="font-size:10.5px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.6px;margin-bottom:6px">
                                        Dernière formation</div>
                                    <div
                                        style="display:flex;align-items:flex-start;gap:8px;background:#fafbfc;border:1px solid #e8edf3;border-radius:10px;padding:10px 12px">
                                        <svg width="14" height="14" style="flex-shrink:0;margin-top:1px"
                                            fill="none" viewBox="0 0 24 24" stroke="#94a3b8" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 14l9-5-9-5-9 5 9 5z" />
                                        </svg>
                                        <div style="min-width:0">
                                            <div
                                                style="font-size:12.5px;font-weight:700;color:#374151;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                                                {{ $formation->diplome }}
                                            </div>
                                            <div style="font-size:11.5px;color:#64748b;margin-top:1px">
                                                {{ $formation->etablissement }}
                                                @if ($formation->date_debut)
                                                    ·
                                                    {{ $formation->date_debut->format('Y') }}{{ $formation->en_cours ? ' – En cours' : ($formation->date_fin ? ' – ' . $formation->date_fin->format('Y') : '') }}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Secteurs --}}
                            @if ($item->secteursActivite->isNotEmpty())
                                <div>
                                    <div
                                        style="font-size:10.5px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.6px;margin-bottom:6px">
                                        Secteurs d'activité</div>
                                    <div style="display:flex;flex-wrap:wrap;gap:5px">
                                        @foreach ($item->secteursActivite->take(3) as $secteur)
                                            <span
                                                style="font-size:11.5px;padding:3px 9px;border-radius:20px;background:#f1f5f9;color:#475569;border:1px solid #e2e8f0">
                                                {{ $secteur->libelle }}
                                            </span>
                                        @endforeach
                                        @if ($item->secteursActivite->count() > 3)
                                            <span
                                                style="font-size:11.5px;padding:3px 9px;border-radius:20px;background:#f1f5f9;color:#94a3b8">
                                                +{{ $item->secteursActivite->count() - 3 }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            {{-- Compétences --}}
                            @if ($item->competences->isNotEmpty())
                                <div>
                                    <div
                                        style="font-size:10.5px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.6px;margin-bottom:6px">
                                        Compétences</div>
                                    <div style="display:flex;flex-wrap:wrap;gap:5px">
                                        @foreach ($item->competences->take(4) as $comp)
                                            <span
                                                style="font-size:11.5px;padding:3px 9px;border-radius:20px;background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe">
                                                {{ $comp->nom }}
                                            </span>
                                        @endforeach
                                        @if ($item->competences->count() > 4)
                                            <span
                                                style="font-size:11.5px;padding:3px 9px;border-radius:20px;background:#eff6ff;color:#94a3b8">
                                                +{{ $item->competences->count() - 4 }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            {{-- Langues --}}
                            @if ($item->languesCandidats->isNotEmpty())
                                <div>
                                    <div
                                        style="font-size:10.5px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.6px;margin-bottom:6px">
                                        Langues</div>
                                    <div style="display:flex;align-items:center;gap:6px;flex-wrap:wrap">
                                        <svg width="12" height="12" fill="none" viewBox="0 0 24 24"
                                            stroke="#94a3b8" stroke-width="2" style="flex-shrink:0">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                                        </svg>
                                        @foreach ($item->languesCandidats->take(3) as $lc)
                                            <span style="font-size:11.5px;color:#64748b">
                                                {{ $lc->langue->nom }}
                                                <span
                                                    style="color:#cbd5e1">({{ $lc->niveau->code }})</span>{{ !$loop->last ? ' ·' : '' }}
                                            </span>
                                        @endforeach
                                        @if ($item->languesCandidats->count() > 3)
                                            <span
                                                style="font-size:11.5px;color:#94a3b8">+{{ $item->languesCandidats->count() - 3 }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                        </div>

                        {{-- CTA --}}
                        <div style="margin-top:20px;padding-top:16px;border-top:1px solid #f1f5f9">
                            <span
                                style="display:flex;align-items:center;justify-content:center;gap:6px;background:linear-gradient(135deg,#042C53,#185FA5);color:#fff;font-size:14px;font-weight:700;padding:12px 20px;border-radius:12px">
                                Voir le profil
                                <svg width="14" height="14" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                </svg>
                            </span>
                        </div>

                    </a>
                @endforeach
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
                        <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24"
                            fill="currentColor">
                            <path
                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z" />
                        </svg>
                    </div>
                </div>
                <div class="whatsapp-card__content">
                    <h2 class="whatsapp-card__title">Rejoignez notre communauté WhatsApp</h2>
                    <p class="whatsapp-card__text">Recevez les dernières opportunités en temps réel et échangez avec des
                        centaines de jeunes ambitieux.</p>
                    <a href="https://whatsapp.com/channel/0029VbCGlUo5q08ZH1bnm11F" target="_blank"
                        rel="noopener noreferrer" class="btn btn--whatsapp">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                            fill="currentColor">
                            <path
                                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347" />
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

    @if ($partenaires->isNotEmpty())
        {{-- ═══════════════════════════════════════════
     ILS NOUS FONT CONFIANCE
═══════════════════════════════════════════ --}}
        <section style="padding:64px 20px;background:#fff">
            <div style="max-width:1100px;margin:0 auto">

                {{-- Header --}}
                <div style="text-align:center;margin-bottom:44px">
                    <span
                        style="display:inline-flex;align-items:center;gap:6px;background:#eff6ff;color:#185FA5;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;padding:5px 14px;border-radius:99px;margin-bottom:14px">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                        Ils nous font confiance
                    </span>
                    <h2 style="font-size:1.5rem;font-weight:800;color:#042C53;margin:0 0 8px">Nos partenaires & entreprises
                    </h2>
                    <p style="font-size:14px;color:#64748b;margin:0">Des entreprises qui nous font confiance pour leurs
                        recrutements et leurs annonces.</p>
                </div>

                {{-- Grille 5 colonnes --}}
                <style>
                    .partenaires-grid {
                        display: grid;
                        grid-template-columns: repeat(5, 1fr);
                        gap: 16px
                    }

                    @media(max-width:640px) {
                        .partenaires-grid {
                            grid-template-columns: repeat(2, 1fr)
                        }
                    }
                </style>
                <div class="partenaires-grid">
                    @foreach ($partenaires as $p)
                        @php $tag = $p->lien ? 'a' : 'div'; @endphp
                        <{{ $tag }}
                            @if ($p->lien) href="{{ $p->lien }}" target="_blank" rel="noopener noreferrer" @endif
                            title="{{ $p->nom }}"
                            style="display:flex;flex-direction:column;align-items:center;justify-content:center;gap:10px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:14px;padding:24px 16px;text-decoration:none;transition:border-color .2s,box-shadow .2s,transform .2s"
                            onmouseover="this.style.borderColor='#185FA5';this.style.boxShadow='0 4px 20px rgba(24,95,165,.12)';this.style.transform='translateY(-2px)'"
                            onmouseout="this.style.borderColor='#e2e8f0';this.style.boxShadow='none';this.style.transform='translateY(0)'">
                            <div style="width:64px;height:44px;display:flex;align-items:center;justify-content:center">
                                <img src="{{ asset('storage/' . $p->logo) }}" alt="{{ $p->nom }}" loading="lazy"
                                    style="max-width:64px;max-height:44px;object-fit:contain;filter:grayscale(1);transition:filter .2s"
                                    onmouseover="this.style.filter='grayscale(0)'"
                                    onmouseout="this.style.filter='grayscale(1)'">
                            </div>
                            <span
                                style="font-size:11.5px;font-weight:600;color:#64748b;text-align:center;line-height:1.3">{{ $p->nom }}</span>
                            </{{ $tag }}>
                    @endforeach
                </div>

            </div>
        </section>
    @endif

    {{-- ═══════════════════════════════════════════
     SECTION ANNONCEURS
═══════════════════════════════════════════ --}}
    <section
        style="padding:72px 20px;background:linear-gradient(135deg,#042C53 0%,#0f3d7a 100%);position:relative;overflow:hidden">

        {{-- Décor --}}
        <div
            style="position:absolute;top:-60px;right:-60px;width:320px;height:320px;background:rgba(255,255,255,.04);border-radius:50%;pointer-events:none">
        </div>
        <div
            style="position:absolute;bottom:-80px;left:-40px;width:240px;height:240px;background:rgba(245,200,66,.06);border-radius:50%;pointer-events:none">
        </div>

        <style>
            .annonceurs-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 56px;
                align-items: center
            }

            @media(max-width:768px) {
                .annonceurs-grid {
                    grid-template-columns: 1fr;
                    gap: 32px
                }

                .annonceurs-text {
                    order: 1
                }

                .annonceurs-card {
                    order: 2
                }
            }
        </style>
        <div style="max-width:1000px;margin:0 auto;position:relative" class="annonceurs-grid">

            {{-- Texte --}}
            <div class="annonceurs-text">
                <span
                    style="display:inline-flex;align-items:center;gap:6px;background:rgba(245,200,66,.15);color:#F5C842;font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;padding:5px 14px;border-radius:99px;margin-bottom:20px">
                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2.5">
                        <rect x="3" y="3" width="18" height="13" rx="2" />
                        <path d="M8 21h8M12 17v4" />
                    </svg>
                    Pour les entreprises
                </span>
                <h2 style="font-size:clamp(1.5rem,3vw,2rem);font-weight:800;color:#fff;margin:0 0 16px;line-height:1.25">
                    Vous avez un message<br>à faire passer ?
                </h2>
                <p style="font-size:15px;color:rgba(255,255,255,.75);line-height:1.75;margin:0 0 28px">
                    La plateforme est aussi pour les annonceurs. Diffusez vos publicités auprès de milliers de candidats,
                    recruteurs et talents actifs sur Emploi Bouge Bénin.
                </p>
                <ul style="list-style:none;padding:0;margin:0 0 32px;display:flex;flex-direction:column;gap:12px">
                    @foreach (['Visibilité ciblée auprès de professionnels béninois', 'Formats adaptés : bannière, affiche, popup', 'Activation immédiate après validation admin', 'Tableau de bord dédié pour suivre vos publicités'] as $item)
                        <li style="display:flex;align-items:center;gap:10px;font-size:14px;color:rgba(255,255,255,.85)">
                            <span
                                style="width:20px;height:20px;background:#F5C842;border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="#042C53"
                                    stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </span>
                            {{ $item }}
                        </li>
                    @endforeach
                </ul>
                <div style="display:flex;gap:12px;flex-wrap:wrap">
                    <a href="{{ auth()->check() && auth()->user()->hasRole('annonceur') ? route('annonceur.publicites') : route('auth.inscription') . '?role=annonceur' }}"
                        style="display:inline-flex;align-items:center;gap:8px;padding:13px 28px;background:#F5C842;color:#042C53;border-radius:10px;font-weight:800;font-size:14px;text-decoration:none">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2.5">
                            <rect x="3" y="3" width="18" height="13" rx="2" />
                            <path d="M8 21h8M12 17v4" />
                        </svg>
                        Publier une publicité
                    </a>
                    <a href="{{ route('contact') }}"
                        style="display:inline-flex;align-items:center;gap:8px;padding:13px 28px;background:rgba(255,255,255,.1);color:#fff;border:1.5px solid rgba(255,255,255,.25);border-radius:10px;font-weight:700;font-size:14px;text-decoration:none">
                        Nous contacter
                    </a>
                </div>
            </div>

            {{-- Carte illustration --}}
            <div class="annonceurs-card" style="display:flex;flex-direction:column;gap:16px">
                <div
                    style="background:rgba(255,255,255,.07);border:1px solid rgba(255,255,255,.12);border-radius:16px;padding:24px;backdrop-filter:blur(4px)">
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:16px">
                        <div
                            style="width:40px;height:40px;background:#F5C842;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#042C53"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z" />
                            </svg>
                        </div>
                        <div>
                            <p style="font-size:13px;font-weight:700;color:#fff;margin:0">Portée maximale</p>
                            <p style="font-size:12px;color:rgba(255,255,255,.55);margin:0">Touchez la bonne audience</p>
                        </div>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
                        @foreach ([['Candidats', 'Actifs sur la plateforme'], ['Recruteurs', 'À la recherche de talents'], ['Talents', 'Freelances & consultants'], ['Visiteurs', 'Trafic quotidien']] as [$label, $desc])
                            <div style="background:rgba(255,255,255,.06);border-radius:10px;padding:12px">
                                <p style="font-size:12px;font-weight:700;color:#F5C842;margin:0 0 2px">{{ $label }}
                                </p>
                                <p style="font-size:11px;color:rgba(255,255,255,.5);margin:0;line-height:1.4">
                                    {{ $desc }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div
                    style="background:rgba(245,200,66,.1);border:1px solid rgba(245,200,66,.2);border-radius:12px;padding:16px 20px;display:flex;align-items:center;gap:12px">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#F5C842"
                        stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <path stroke-linecap="round" d="M12 8v4l3 3" />
                    </svg>
                    <p style="font-size:13px;color:rgba(255,255,255,.85);margin:0;line-height:1.5">
                        <strong style="color:#F5C842">Activation rapide.</strong> Votre publicité est en ligne dès
                        validation par notre équipe.
                    </p>
                </div>
            </div>

        </div>
    </section>

    {{-- ═══════════════════════════════════════════
     BLOG
═══════════════════════════════════════════ --}}
    <section class="section home-blog-section">
        <div class="container">
            <div class="home-blog__header">
                <div class="home-blog__header-left">
                    <span class="badge badge--yellow">Blog &amp; Conseils</span>
                    <h2 class="section-title" style="margin-top:10px">Derniers articles</h2>
                    <p class="section-subtitle">Conseils carrière, guides pratiques et opportunités pour réussir sur le
                        marché africain.</p>
                </div>
                <a href="{{ route('blog.list') }}" class="home-blog__see-all">
                    Tous les articles
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>

            <div class="articles-grid">
                @foreach ($articles as $article)
                    <article class="article-card">
                        <a href="{{ route('blog.detail', $article) }}" class="article-card__cover" tabindex="-1">
                            <span class="article-card__category">{{ $article->categorie }}</span>
                            @if ($article->image)
                                <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->titre }}"
                                    class="card__img" loading="lazy" />
                            @else
                                <img src="https://placehold.co/600x340/EEF4FF/2563eb?text={{ urlencode($article->categorie ?? 'Blog') }}"
                                    alt="{{ $article->titre }}" class="card__img" loading="lazy" />
                            @endif
                        </a>
                        <div class="article-card__body">
                            <div class="article-card__meta">
                                <span class="article-card__date">{{ $article->publie_le?->format('d M Y') }}</span>
                                <span class="article-card__dot"></span>
                                <span class="article-card__read">{{ $article->temps_lecture }} min</span>
                                <span class="article-card__dot"></span>
                                <span class="article-card__views">
                                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                    <span class="blog-read-count"
                                        data-init="{{ $article->vues }}">{{ number_format($article->vues) }}</span>
                                    lectures
                                </span>
                            </div>
                            <h3 class="article-card__title">{{ $article->titre }}</h3>
                            <p class="article-card__text">{{ $article->extrait }}</p>
                            <a href="{{ route('blog.detail', $article) }}" class="article-card__link">
                                Lire l'article
                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="articles-cta">
                <a href="{{ route('blog.list') }}" class="btn btn--outline-blue" style="color: #F5C842;">
                    Voir tous les articles
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
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
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        Newsletter
                    </span>
                    <h2 class="newsletter__title">Ne ratez aucune<br><span>opportunité d'emploi</span></h2>
                    <p class="newsletter__sub">Recevez chaque semaine les meilleures offres d'emploi au Bénin, des conseils
                        carrière exclusifs et les actualités du marché du travail.</p>
                    <div class="newsletter__stats">
                        <div class="newsletter__stat">
                            <div class="newsletter__stat-icon">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div class="newsletter__stat-text">
                                <span class="newsletter__stat-num">2 000+</span>
                                <span class="newsletter__stat-label">Abonnés actifs</span>
                            </div>
                        </div>
                        <div class="newsletter__stat">
                            <div class="newsletter__stat-icon">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
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
                            <input type="email" name="email" class="newsletter__input"
                                placeholder="exemple@email.com" required>
                            <button type="submit" class="newsletter__btn">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                </svg>
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
    <script src="{{ asset('js/index.js') }}" defer></script>
@endsection
