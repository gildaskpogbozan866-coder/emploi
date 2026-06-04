@extends('layouts.app')
@section('title', 'Services — Emploi Bouge Bénin')
@section('description', 'Des services concrets pour trouver un emploi, rédiger un CV professionnel et développer vos compétences en Afrique.')

@section('css')
<link rel="stylesheet" href="{{ asset('css/service/list-service.css') }}">
@endsection

@section('content')

  {{-- ═══════════════════════════════════════════
       PAGE HERO
  ═══════════════════════════════════════════ --}}
  <section class="section page-hero">
    <div class="container page-hero__inner">
      <span class="badge badge--blue">Services</span>
      <h1 class="page-hero__title">Nos services d'accompagnement</h1>
      <p class="page-hero__subtitle">
        Des outils concrets pour vous aider à trouver un emploi, développer vos
        compétences et connecter avec les meilleures opportunités en Afrique.
      </p>
    </div>
  </section>

  {{-- ═══════════════════════════════════════════
       GRILLE DE SERVICES
  ═══════════════════════════════════════════ --}}
  <section class="section" style="background:#fff;" id="services">
    <div class="container">

      <div class="section-header section-header--center" style="margin-bottom:52px">
        <span class="badge badge--yellow">Ce que nous offrons</span>
        <h2 class="section-title" style="margin-top:10px">Tout ce dont vous avez besoin</h2>
        <p class="section-subtitle" style="max-width:500px;margin:10px auto 0">
          Chaque service est conçu pour répondre aux besoins réels de la jeunesse africaine.
        </p>
      </div>

      {{-- ── Carte featured CV ── --}}
      <div class="cv-premium-card">

        <div class="cv-card__left">
          <span class="cv-card__badge">Service n°1 — Très demandé</span>
          <h2 class="cv-card__title">Arrêtez de postuler<br>dans le vide.</h2>
          <p class="cv-card__hook">
            Un recruteur décide en <strong>7 secondes</strong>. Si votre CV ne retient pas l'attention immédiatement,
            votre candidature finit à la corbeille — même si vous êtes le meilleur candidat.
            <strong>Ne laissez plus passer vos chances.</strong>
          </p>
          <ul class="cv-card__features">
            <li>CV structuré, moderne et optimisé pour passer les filtres recruteurs</li>
            <li>Lettre de motivation ciblée et personnalisée à chaque poste</li>
            <li>Analyse complète de votre profil et de vos objectifs professionnels</li>
            <li>Livraison sous 48 h en format Word &amp; PDF, prêt à l'emploi</li>
            <li>1 révision gratuite incluse — jusqu'à votre entière satisfaction</li>
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
            Je veux mon CV professionnel →
          </a>
        </div>

        <div class="cv-card__right">
          <span class="cv-card__right-badge">+500 commandes livrées</span>
          <p class="cv-card__price-label">Seulement</p>
          <p class="cv-card__price"><em>2 500</em> <span>FCFA</span></p>
          <p class="cv-card__price-sub">tout compris · paiement à la livraison</p>
          <div class="cv-card__delivery-badge">
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            Livré sous 48 h
          </div>
          <p class="cv-card__guarantee">Satisfaction garantie<br>1 révision offerte</p>
          <a href="{{ route('service.commande', 'cv-professionnel') }}" class="cv-card__right-cta">
            Commander maintenant →
          </a>
        </div>

      </div>

      {{-- ── Grille services ── --}}
      <div class="svc-grid">

        <div class="svc-item">
          <div class="svc-item__icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="#2563eb" stroke-width="1.8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
          </div>
          <h3 class="svc-item__title">Offres d'emploi ciblées</h3>
          <p class="svc-item__text">Accédez à des centaines d'offres vérifiées et mises à jour quotidiennement, adaptées au marché africain et international.</p>
          <a href="{{ route('offre.list') }}" class="svc-item__link">Explorer les offres →</a>
        </div>

        <div class="svc-item">
          <div class="svc-item__icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="#d4a00a" stroke-width="1.8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
            </svg>
          </div>
          <h3 class="svc-item__title">Coaching CV &amp; entretien</h3>
          <p class="svc-item__text">Nos experts vous accompagnent pour rédiger un CV percutant et vous préparer aux entretiens d'embauche avec confiance.</p>
          <a href="{{ route('service.commande', 'coaching-entretien') }}" class="svc-item__link">Réserver une session →</a>
        </div>

        <div class="svc-item">
          <div class="svc-item__icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="#2563eb" stroke-width="1.8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
          </div>
          <h3 class="svc-item__title">Réseau professionnel</h3>
          <p class="svc-item__text">Intégrez notre communauté WhatsApp et échangez avec des professionnels, recruteurs et candidats à travers le continent.</p>
          <a href="https://whatsapp.com/channel/0029VbCGlUo5q08ZH1bnm11F" target="_blank" rel="noopener" class="svc-item__link">Rejoindre la communauté →</a>
        </div>

        {{-- Services dynamiques BDD (hors cv-professionnel déjà affiché) --}}
        @foreach($services->whereNotIn('slug', ['cv-professionnel']) as $service)
        <div class="svc-item">
          <div class="svc-item__icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="#2563eb" stroke-width="1.8">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
          </div>
          <h3 class="svc-item__title">{{ $service->nom }}</h3>
          <p class="svc-item__text">{{ $service->description }}</p>
          <a href="{{ route('service.commande', $service) }}" class="svc-item__link">
            Commander — {{ number_format($service->prix, 0, ',', ' ') }} {{ $service->devise }} →
          </a>
        </div>
        @endforeach

      </div>

      <div style="text-align:center;margin-top:48px">
        <a href="{{ route('contact') }}" class="btn btn--yellow">
          Devenir partenaire
          <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </a>
      </div>

    </div>
  </section>

  {{-- ── WhatsApp ── --}}
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

  {{-- ── Newsletter ── --}}
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
