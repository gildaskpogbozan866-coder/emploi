@extends('layouts.app')
@section('title', 'Contact — Emploi Bouge Bénin')
@section('description', 'Contactez Emploi Bouge Bénin pour toute question, partenariat ou collaboration.')

@section('css')
<link rel="stylesheet" href="{{ asset('css/contact.css') }}">
@endsection

@section('content')

  {{-- ═══════════════════════════════════════════
       PAGE HERO
  ═══════════════════════════════════════════ --}}
  <section class="section page-hero">
    <div class="container page-hero__inner">
      <span class="badge badge--blue">Contact</span>
      <h1 class="page-hero__title">Parlons-en</h1>
      <p class="page-hero__subtitle">
        Une question, un partenariat, une collaboration ? Nous sommes à votre
        écoute. Contactez-nous directement.
      </p>
    </div>
  </section>

  {{-- ═══════════════════════════════════════════
       FORMULAIRE DE CONTACT
  ═══════════════════════════════════════════ --}}
  <section class="contact-section">
    <div class="container contact-section__inner">

      {{-- Colonne gauche : infos + promesse --}}
      <div class="contact-side">
        <span class="badge badge--yellow" style="margin-bottom:18px">Nous contacter</span>
        <h2 class="contact-side__title">Une question ?<br><span class="text-accent">Écrivez-nous.</span></h2>
        <p class="contact-side__sub">Nous lisons chaque message et répondons sous 24h.</p>

        <div class="contact-side__items">
          <a href="mailto:emploibougebenin@gmail.com" class="contact-side__item">
            <span class="contact-side__icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </span>
            <span>emploibougebenin@gmail.com</span>
          </a>
          <a href="https://wa.me/22901519298" target="_blank" rel="noopener noreferrer" class="contact-side__item contact-side__item--wa">
            <span class="contact-side__icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            </span>
            <span>+229 01 51 92 98 56</span>
          </a>
        </div>

        <div class="contact-side__promise">
          <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          Réponse sous 24h
        </div>
      </div>

      {{-- Colonne droite : formulaire --}}
      <div class="contact-form-card">

        @if(session('contact_sent'))
          @php $sent = session('contact_sent'); @endphp
          <div class="contact-confirm">
            <div class="contact-confirm__icon">
              <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="contact-confirm__title">Message envoyé, {{ $sent['prenom'] }} !</h3>
            <p class="contact-confirm__sub">Votre message a bien été reçu. Nous vous répondrons <strong>sous 24h</strong> à l'adresse&nbsp;:</p>
            <div class="contact-confirm__email">{{ $sent['email'] }}</div>
            <div class="contact-confirm__meta">
              <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-5 5a2 2 0 01-2.828 0l-7-7A2 2 0 013 10V5a2 2 0 012-2z"/></svg>
              Sujet : <strong>
                {{ match($sent['sujet']) {
                  'question'    => 'Question générale',
                  'partenariat' => 'Proposition de partenariat',
                  'signalement' => 'Signalement d\'un contenu',
                  'technique'   => 'Problème technique',
                  default       => 'Autre',
                } }}
              </strong>
            </div>
            <a href="{{ route('contact') }}" class="btn btn--yellow contact-confirm__btn">
              Envoyer un autre message
            </a>
          </div>
        @endif

        @if($errors->any())
          <div class="contact-error">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
            {{ $errors->first() }}
          </div>
        @endif

        @if(!session('contact_sent'))
        <form class="contact-form" method="POST" action="{{ route('contact.envoyer') }}">
          @csrf
          <input type="hidden" name="type" value="contact">

          <div class="contact-form__row">
            <div class="contact-form__field">
              <label class="contact-form__label" for="prenom">Prénom <span class="contact-form__req">*</span></label>
              <input class="contact-form__input {{ $errors->has('prenom') ? 'contact-form__input--error' : '' }}"
                     type="text" id="prenom" name="prenom"
                     value="{{ old('prenom', auth()->user()?->prenom) }}"
                     placeholder="Jean" required />
            </div>
            <div class="contact-form__field">
              <label class="contact-form__label" for="nom">Nom</label>
              <input class="contact-form__input"
                     type="text" id="nom" name="nom"
                     value="{{ old('nom', auth()->user()?->nom) }}"
                     placeholder="Dupont" />
            </div>
          </div>

          <div class="contact-form__field">
            <label class="contact-form__label" for="email">Adresse e-mail <span class="contact-form__req">*</span></label>
            <input class="contact-form__input {{ $errors->has('email') ? 'contact-form__input--error' : '' }}"
                   type="email" id="email" name="email"
                   value="{{ old('email', auth()->user()?->email) }}"
                   placeholder="jean@exemple.com" required />
          </div>

          <div class="contact-form__field">
            <label class="contact-form__label" for="sujet">Sujet <span class="contact-form__req">*</span></label>
            <select class="contact-form__input contact-form__select {{ $errors->has('sujet') ? 'contact-form__input--error' : '' }}"
                    id="sujet" name="sujet" required>
              <option value="">— Choisissez un sujet —</option>
              <option value="question"     {{ old('sujet') === 'question'     ? 'selected' : '' }}>Question générale</option>
              <option value="partenariat"  {{ old('sujet') === 'partenariat'  ? 'selected' : '' }}>Proposition de partenariat</option>
              <option value="signalement"  {{ old('sujet') === 'signalement'  ? 'selected' : '' }}>Signalement d'un contenu</option>
              <option value="technique"    {{ old('sujet') === 'technique'    ? 'selected' : '' }}>Problème technique</option>
              <option value="autre"        {{ old('sujet') === 'autre'        ? 'selected' : '' }}>Autre</option>
            </select>
          </div>

          <div class="contact-form__field">
            <label class="contact-form__label" for="message">Message <span class="contact-form__req">*</span></label>
            <textarea class="contact-form__input contact-form__textarea {{ $errors->has('message') ? 'contact-form__input--error' : '' }}"
                      id="message" name="message" rows="5"
                      placeholder="Décrivez votre demande en quelques lignes…" required>{{ old('message') }}</textarea>
          </div>

          @if($recaptchaActif)
            <div class="g-recaptcha" data-sitekey="{{ $recaptchaSiteKey }}" style="margin-bottom:16px"></div>
          @endif
          @error('recaptcha')
            <p style="color:#e53e3e;font-size:13px;margin-bottom:10px">{{ $message }}</p>
          @enderror

          <button type="submit" class="btn btn--yellow contact-form__btn">
            Envoyer le message
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
            </svg>
          </button>
        </form>
        @endif
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
            <svg xmlns="http://www.w3.org/2000/svg" width="34" height="34" viewBox="0 0 24 24" fill="currentColor">
              <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
          </div>
        </div>
        <div class="whatsapp-card__content">
          <h2 class="whatsapp-card__title">Rejoignez notre communauté WhatsApp</h2>
          <p class="whatsapp-card__text">Recevez les dernières opportunités en temps réel et échangez avec des centaines de jeunes ambitieux.</p>
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
          <p class="newsletter__sub">Recevez chaque semaine les meilleures offres d'emploi au Bénin et les actualités du marché du travail.</p>
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
<script src="{{ asset('js/contact.js') }}" defer></script>
@endsection
