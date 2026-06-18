@extends('layouts.app')
@section('title', $service->nom . ' | Emploi Bouge Bénin')
@section('description', $service->description ? Str::limit(strip_tags($service->description), 155) : 'Découvrez ce service proposé par Emploi Bouge Bénin.')
@section('canonical', route('service.detail', $service->slug))

@section('css')
<style>
/* ═══════════════════════════════════════════
   SERVICE DETAIL — Hero
═══════════════════════════════════════════ */
.sd-hero {
  background: linear-gradient(135deg, #042C53 0%, #0d3d70 55%, #185FA5 100%);
  padding: 64px 0 72px;
  position: relative;
  overflow: hidden;
}
.sd-hero::before {
  content: '';
  position: absolute;
  width: 440px; height: 440px;
  border-radius: 50%;
  background: rgba(245,200,66,.05);
  top: -140px; right: -80px;
  pointer-events: none;
}
.sd-hero__back {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  color: rgba(255,255,255,.55);
  font-family: var(--font-body);
  font-size: 13px;
  font-weight: 500;
  text-decoration: none;
  margin-bottom: 28px;
  transition: color .18s;
}
.sd-hero__back:hover { color: rgba(255,255,255,.9); }

.sd-hero__type {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: rgba(245,200,66,.15);
  border: 1px solid rgba(245,200,66,.3);
  color: #F5C842;
  font-family: var(--font-body);
  font-size: 11px;
  font-weight: 800;
  letter-spacing: .1em;
  text-transform: uppercase;
  padding: 5px 13px;
  border-radius: 20px;
  margin-bottom: 18px;
}
.sd-hero__title {
  font-family: var(--font-display);
  font-size: clamp(28px, 4vw, 46px);
  font-weight: 500;
  color: #fff;
  line-height: 1.15;
  margin: 0 0 16px;
  max-width: 680px;
}
.sd-hero__desc {
  font-family: var(--font-body);
  font-size: 16px;
  color: rgba(255,255,255,.72);
  line-height: 1.7;
  max-width: 600px;
  margin: 0 0 32px;
}
.sd-hero__chips {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
}
.sd-hero__chip {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  background: rgba(255,255,255,.1);
  border: 1px solid rgba(255,255,255,.18);
  color: rgba(255,255,255,.88);
  font-family: var(--font-body);
  font-size: 12.5px;
  font-weight: 600;
  padding: 7px 14px;
  border-radius: 20px;
}
.sd-hero__chip svg { flex-shrink: 0; }

/* ═══════════════════════════════════════════
   LAYOUT PRINCIPAL
═══════════════════════════════════════════ */
.sd-body {
  background: #f8fafc;
  padding: 52px 0 64px;
}
.sd-layout {
  display: grid;
  grid-template-columns: 1fr 340px;
  gap: 36px;
  align-items: start;
}

/* ── Colonne gauche : contenu ── */
.sd-content { display: flex; flex-direction: column; gap: 28px; }

.sd-card {
  background: #fff;
  border-radius: 20px;
  border: 1.5px solid #e8eef6;
  padding: 32px 28px;
}

.sd-card__label {
  display: flex;
  align-items: center;
  gap: 10px;
  font-family: var(--font-body);
  font-size: 11px;
  font-weight: 800;
  letter-spacing: .1em;
  text-transform: uppercase;
  color: #185FA5;
  margin-bottom: 20px;
}
.sd-card__label-line {
  flex: 1;
  height: 1px;
  background: #e8eef6;
}

.sd-checklist {
  display: flex;
  flex-direction: column;
  gap: 14px;
}
.sd-check {
  display: flex;
  align-items: flex-start;
  gap: 13px;
}
.sd-check__icon {
  width: 22px; height: 22px;
  border-radius: 50%;
  background: #f0fdf4;
  border: 1.5px solid #86efac;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
  margin-top: 1px;
}
.sd-check__text {
  font-family: var(--font-body);
  font-size: 14.5px;
  color: #042C53;
  line-height: 1.5;
}

/* Bloc process */
.sd-steps {
  display: flex;
  flex-direction: column;
  gap: 20px;
  counter-reset: step;
}
.sd-step {
  display: flex;
  align-items: flex-start;
  gap: 16px;
  counter-increment: step;
}
.sd-step__num {
  width: 36px; height: 36px;
  border-radius: 50%;
  background: linear-gradient(135deg, #042C53, #185FA5);
  color: #fff;
  font-family: var(--font-body);
  font-size: 13px;
  font-weight: 800;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.sd-step__body {}
.sd-step__title {
  font-family: var(--font-body);
  font-size: 14px;
  font-weight: 800;
  color: #042C53;
  margin: 0 0 4px;
}
.sd-step__desc {
  font-family: var(--font-body);
  font-size: 13px;
  color: #64748b;
  line-height: 1.6;
  margin: 0;
}

/* ── Colonne droite : sidebar sticky ── */
.sd-sidebar {
  display: flex;
  flex-direction: column;
  gap: 20px;
  position: sticky;
  top: 90px;
}

/* Carte prix / CTA */
.sd-price-card {
  background: linear-gradient(160deg, #042C53 0%, #0d4580 60%, #185FA5 100%);
  border-radius: 20px;
  padding: 32px 24px;
  text-align: center;
  position: relative;
  overflow: hidden;
}
.sd-price-card::before {
  content: '';
  position: absolute;
  width: 200px; height: 200px;
  border-radius: 50%;
  background: rgba(245,200,66,.06);
  top: -60px; right: -40px;
}
.sd-price-card__eyebrow {
  font-family: var(--font-body);
  font-size: 11px;
  font-weight: 600;
  letter-spacing: .12em;
  text-transform: uppercase;
  color: rgba(255,255,255,.5);
  margin: 0 0 10px;
  position: relative; z-index: 1;
}
.sd-price-card__price {
  font-family: var(--font-body);
  font-weight: 800;
  color: #F5C842;
  font-size: clamp(36px, 4vw, 48px);
  line-height: 1;
  margin: 0 0 4px;
  position: relative; z-index: 1;
}
.sd-price-card__currency {
  font-family: var(--font-body);
  font-size: 13px;
  color: rgba(255,255,255,.55);
  margin: 0 0 6px;
  position: relative; z-index: 1;
}
.sd-price-card__devis {
  font-family: var(--font-body);
  font-size: 18px;
  font-weight: 800;
  color: #F5C842;
  margin: 0 0 6px;
  position: relative; z-index: 1;
}

.sd-price-card__delay {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: rgba(56,161,105,.2);
  border: 1px solid rgba(56,161,105,.35);
  color: #68d391;
  font-family: var(--font-body);
  font-size: 12px;
  font-weight: 600;
  padding: 6px 14px;
  border-radius: 8px;
  margin: 12px 0 22px;
  position: relative; z-index: 1;
}

/* WhatsApp CTA — bouton principal */
.sd-btn-wa {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 9px;
  background: #25d366;
  color: #fff;
  font-family: var(--font-body);
  font-size: 15px;
  font-weight: 800;
  padding: 16px 20px;
  border-radius: 13px;
  text-decoration: none;
  box-shadow: 0 4px 20px rgba(37,211,102,.35);
  transition: transform .18s, box-shadow .18s;
  margin-bottom: 10px;
  position: relative; z-index: 1;
}
.sd-btn-wa:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 32px rgba(37,211,102,.5);
}

/* Commander CTA — bouton secondaire */
.sd-btn-order {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  background: #F5C842;
  color: #042C53;
  font-family: var(--font-body);
  font-size: 14px;
  font-weight: 800;
  padding: 14px 20px;
  border-radius: 13px;
  text-decoration: none;
  box-shadow: 0 4px 16px rgba(245,200,66,.4);
  transition: transform .18s, box-shadow .18s;
  position: relative; z-index: 1;
}
.sd-btn-order:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 24px rgba(245,200,66,.55);
}

.sd-price-card__trust {
  font-family: var(--font-body);
  font-size: 12px;
  color: rgba(255,255,255,.45);
  margin-top: 14px;
  line-height: 1.6;
  position: relative; z-index: 1;
}

/* Carte contact rapide */
.sd-contact-card {
  background: #fff;
  border-radius: 16px;
  border: 1.5px solid #e8eef6;
  padding: 22px 20px;
  display: flex;
  flex-direction: column;
  gap: 14px;
}
.sd-contact-card__title {
  font-family: var(--font-body);
  font-size: 13px;
  font-weight: 800;
  color: #042C53;
  margin: 0;
}
.sd-contact-card__row {
  display: flex;
  align-items: center;
  gap: 10px;
  font-family: var(--font-body);
  font-size: 13px;
  color: #64748b;
  line-height: 1.5;
}
.sd-contact-card__row svg { color: #185FA5; flex-shrink: 0; }

/* ═══════════════════════════════════════════
   BANDE CTA BAS
═══════════════════════════════════════════ */
.sd-cta-band {
  background: #f0f7ff;
  border-top: 1.5px solid #bfdbfe;
  padding: 40px 0;
}
.sd-cta-band__inner {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 24px;
  flex-wrap: wrap;
}
.sd-cta-band__title {
  font-family: var(--font-body);
  font-size: 17px;
  font-weight: 800;
  color: #042C53;
  margin: 0 0 4px;
}
.sd-cta-band__sub {
  font-family: var(--font-body);
  font-size: 13px;
  color: #64748b;
  margin: 0;
}
.sd-cta-band__btns {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
  flex-shrink: 0;
}
.sd-cta-band__btn-wa {
  display: inline-flex; align-items: center; gap: 7px;
  background: #25d366; color: #fff;
  font-family: var(--font-body); font-size: 13px; font-weight: 700;
  padding: 11px 20px; border-radius: 10px; text-decoration: none;
  transition: transform .18s;
}
.sd-cta-band__btn-wa:hover { transform: translateY(-2px); }
.sd-cta-band__btn-back {
  display: inline-flex; align-items: center; gap: 7px;
  background: #fff; border: 1.5px solid #bfdbfe; color: #185FA5;
  font-family: var(--font-body); font-size: 13px; font-weight: 700;
  padding: 11px 20px; border-radius: 10px; text-decoration: none;
  transition: background .18s;
}
.sd-cta-band__btn-back:hover { background: #EBF4FD; }

/* ═══════════════════════════════════════════
   RESPONSIVE
═══════════════════════════════════════════ */
@media (max-width: 900px) {
  .sd-layout { grid-template-columns: 1fr; }
  .sd-sidebar { position: static; }
}
@media (max-width: 600px) {
  .sd-hero { padding: 48px 0 56px; }
  .sd-hero__title { font-size: 26px; }
  .sd-card { padding: 24px 18px; }
  .sd-price-card { padding: 28px 18px; }
  .sd-cta-band__inner { flex-direction: column; align-items: flex-start; }
  .sd-cta-band__btns { width: 100%; }
  .sd-cta-band__btn-wa,
  .sd-cta-band__btn-back { flex: 1; justify-content: center; }
}
</style>
@endsection

@section('content')

@php
$typeLabels = [
  'redaction'  => 'Rédaction professionnelle',
  'coaching'   => 'Coaching & Formation',
  'digital'    => 'Service digital',
  'formation'  => 'Formation',
  'consulting' => 'Consulting digital',
];
$typeLabel = $typeLabels[$service->type] ?? 'Service';

// Chips héro selon le service
$chips = match($service->slug) {
  'cv-professionnel'          => [['icon'=>'clock','text'=>'Livré en 30min à 1h'],['icon'=>'check','text'=>'Satisfaction garantie'],['icon'=>'refresh','text'=>'1 révision offerte']],
  'linkedin-optimise'         => [['icon'=>'clock','text'=>'Livré en 24h'],['icon'=>'check','text'=>'Profil 100% optimisé'],['icon'=>'star','text'=>'Mots-clés recruteurs']],
  'coaching-entretien'        => [['icon'=>'clock','text'=>'Session d\'1 heure'],['icon'=>'check','text'=>'Feedback détaillé'],['icon'=>'star','text'=>'Plan d\'amélioration']],
  'creation-sites-web'        => [['icon'=>'check','text'=>'Design responsive'],['icon'=>'star','text'=>'SEO inclus'],['icon'=>'clock','text'=>'1 mois de support']],
  'gestion-reseaux-sociaux'   => [['icon'=>'check','text'=>'Contenus créatifs'],['icon'=>'star','text'=>'Calendrier éditorial'],['icon'=>'clock','text'=>'Rapport mensuel']],
  'marketing-digital'         => [['icon'=>'check','text'=>'Ciblage précis'],['icon'=>'star','text'=>'Optimisation en temps réel'],['icon'=>'clock','text'=>'Rapport hebdomadaire']],
  'referencement-seo'         => [['icon'=>'check','text'=>'Audit SEO complet'],['icon'=>'star','text'=>'Première page Google'],['icon'=>'clock','text'=>'Suivi mensuel']],
  'developpement-applications'=> [['icon'=>'check','text'=>'Sur mesure'],['icon'=>'star','text'=>'Technologies modernes'],['icon'=>'clock','text'=>'Formation incluse']],
  'formation-informatique'    => [['icon'=>'check','text'=>'Tous niveaux'],['icon'=>'star','text'=>'Cours individuels ou groupe'],['icon'=>'clock','text'=>'Attestation remise']],
  'creation-cv-documents'     => [['icon'=>'clock','text'=>'Livré en 30min à 1h'],['icon'=>'check','text'=>'Format Word & PDF'],['icon'=>'refresh','text'=>'1 révision offerte']],
  'accompagnement-digital'    => [['icon'=>'check','text'=>'Stratégie personnalisée'],['icon'=>'star','text'=>'Formation équipes'],['icon'=>'clock','text'=>'Suivi continu']],
  default                     => [['icon'=>'check','text'=>'Service professionnel'],['icon'=>'star','text'=>'Équipe experte'],['icon'=>'clock','text'=>'Réponse rapide']],
};

// Étapes du processus
$steps = match(true) {
  in_array($service->slug, ['cv-professionnel','linkedin-optimise','creation-cv-documents']) => [
    ['num'=>'1','title'=>'Vous passez commande','desc'=>'Cliquez sur "Commander" et remplissez le formulaire avec vos informations.'],
    ['num'=>'2','title'=>'Nos experts analysent votre profil','desc'=>'Nous étudions votre parcours, vos objectifs et le poste visé.'],
    ['num'=>'3','title'=>'Rédaction et livraison','desc'=>'Votre document est rédigé et livré en Word & PDF dans les délais indiqués.'],
    ['num'=>'4','title'=>'Révision si nécessaire','desc'=>'Vous avez droit à 1 révision gratuite si vous souhaitez des ajustements.'],
  ],
  $service->slug === 'coaching-entretien' => [
    ['num'=>'1','title'=>'Vous réservez votre session','desc'=>'Choisissez un créneau disponible et passez commande.'],
    ['num'=>'2','title'=>'Vous recevez votre lien','desc'=>'Un lien de visioconférence vous est envoyé par email.'],
    ['num'=>'3','title'=>'Session de coaching','desc'=>'1 heure intensive avec simulation d\'entretien et feedback détaillé.'],
    ['num'=>'4','title'=>'Votre plan d\'action','desc'=>'Recevez votre plan d\'amélioration personnalisé par écrit.'],
  ],
  default => [
    ['num'=>'1','title'=>'Vous nous contactez','desc'=>'Envoyez-nous un message via WhatsApp ou le formulaire de contact.'],
    ['num'=>'2','title'=>'Nous analysons votre besoin','desc'=>'Notre équipe étudie votre projet et vous prépare une proposition adaptée.'],
    ['num'=>'3','title'=>'Devis et validation','desc'=>'Vous recevez un devis clair et transparent. On démarre dès votre accord.'],
    ['num'=>'4','title'=>'Livraison et suivi','desc'=>'Votre projet est réalisé dans les délais et vous bénéficiez d\'un suivi complet.'],
  ],
};

$waNumber  = '22951929856';
$waMessage = urlencode('Bonjour, je suis intéressé(e) par votre service : ' . $service->nom . '. Pouvez-vous me donner plus d\'informations ?');
$waUrl     = 'https://wa.me/' . $waNumber . '?text=' . $waMessage;
@endphp

{{-- ══════════════ HERO ══════════════ --}}
<section class="sd-hero">
  <div class="container">

    <a href="{{ route('service.list') }}" class="sd-hero__back">
      <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
      Tous nos services
    </a>

    <div class="sd-hero__type">
      {{ $typeLabel }}
    </div>

    <h1 class="sd-hero__title">{{ $service->nom }}</h1>

    @if($service->description)
      <p class="sd-hero__desc">{{ $service->description }}</p>
    @endif

    <div class="sd-hero__chips">
      @foreach($chips as $chip)
      <span class="sd-hero__chip">
        @if($chip['icon'] === 'clock')
          <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"/></svg>
        @elseif($chip['icon'] === 'refresh')
          <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
        @else
          <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        @endif
        {{ $chip['text'] }}
      </span>
      @endforeach
    </div>

  </div>
</section>

{{-- ══════════════ CORPS ══════════════ --}}
<section class="sd-body">
  <div class="container">
    <div class="sd-layout">

      {{-- ── Colonne principale ── --}}
      <div class="sd-content">

        {{-- Ce qui est inclus --}}
        @if($service->details)
        <div class="sd-card">
          <div class="sd-card__label">
            Ce qui est inclus
            <span class="sd-card__label-line"></span>
          </div>
          <div class="sd-checklist">
            @foreach(array_filter(array_map('trim', explode("\n", $service->details))) as $line)
            <div class="sd-check">
              <div class="sd-check__icon">
                <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="#16a34a" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
              </div>
              <span class="sd-check__text">{{ $line }}</span>
            </div>
            @endforeach
          </div>
        </div>
        @endif

        {{-- Processus --}}
        <div class="sd-card">
          <div class="sd-card__label">
            Comment ça se passe
            <span class="sd-card__label-line"></span>
          </div>
          <div class="sd-steps">
            @foreach($steps as $step)
            <div class="sd-step">
              <div class="sd-step__num">{{ $step['num'] }}</div>
              <div class="sd-step__body">
                <p class="sd-step__title">{{ $step['title'] }}</p>
                <p class="sd-step__desc">{{ $step['desc'] }}</p>
              </div>
            </div>
            @endforeach
          </div>
        </div>

        {{-- Pourquoi nous --}}
        <div class="sd-card" style="background:linear-gradient(135deg,#042C53 0%,#0d3d70 60%,#185FA5 100%);border:none">
          <div class="sd-card__label" style="color:rgba(255,255,255,.5)">
            Pourquoi choisir Emploi Bouge Bénin
            <span class="sd-card__label-line" style="background:rgba(255,255,255,.1)"></span>
          </div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px">
            @foreach([
              ['title'=>'Réponse rapide','desc'=>'Notre équipe répond en moins d\'1 heure en semaine.'],
              ['title'=>'Expertise locale','desc'=>'Nous connaissons le marché béninois et africain.'],
              ['title'=>'Satisfaction garantie','desc'=>'Votre satisfaction est notre priorité absolue.'],
              ['title'=>'Accompagnement continu','desc'=>'Nous restons disponibles après livraison.'],
            ] as $why)
            <div style="display:flex;gap:12px;align-items:flex-start">
              <span style="width:28px;height:28px;border-radius:50%;background:rgba(245,200,66,.15);border:1px solid rgba(245,200,66,.3);display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="#F5C842" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
              </span>
              <div>
                <p style="font-family:var(--font-body);font-size:13px;font-weight:800;color:#fff;margin:0 0 4px">{{ $why['title'] }}</p>
                <p style="font-family:var(--font-body);font-size:12px;color:rgba(255,255,255,.6);margin:0;line-height:1.5">{{ $why['desc'] }}</p>
              </div>
            </div>
            @endforeach
          </div>
        </div>

      </div>

      {{-- ── Sidebar ── --}}
      <div class="sd-sidebar">

        {{-- Carte prix / CTA principale --}}
        <div class="sd-price-card">
          <p class="sd-price-card__eyebrow">Prix du service</p>

          @if($service->prix > 0)
            <p class="sd-price-card__price">{{ number_format($service->prix, 0, ',', ' ') }}</p>
            <p class="sd-price-card__currency">FCFA tout compris</p>
          @else
            <p class="sd-price-card__devis">Sur devis</p>
            <p class="sd-price-card__currency">Contactez-nous pour un devis personnalisé</p>
          @endif

          @if($service->delai)
          <div class="sd-price-card__delay">
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"/></svg>
            {{ $service->delai }}
          </div>
          @endif

          {{-- Bouton WhatsApp (toujours présent, bien mis en avant) --}}
          <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="sd-btn-wa">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            Nous écrire sur WhatsApp
          </a>

          {{-- Bouton Commander (si service commandable avec paiement) --}}
          @if($service->prix > 0)
          <a href="{{ route('service.commande', $service) }}" class="sd-btn-order">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            Commander maintenant
          </a>
          @endif

          <p class="sd-price-card__trust">
            ✓ Réponse garantie sous 1h<br>
            ✓ Satisfaction ou remboursement<br>
            ✓ Paiement sécurisé
          </p>
        </div>

        {{-- Carte contact rapide --}}
        <div class="sd-contact-card">
          <p class="sd-contact-card__title">Des questions avant de commander ?</p>
          <div class="sd-contact-card__row">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            <span>Gildaskpogbozan866@gmail.com</span>
          </div>
          <div class="sd-contact-card__row">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="#25d366"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            <span>+229 01 51 92 98 56</span>
          </div>
          <a href="{{ $waUrl }}" target="_blank" rel="noopener"
             style="display:flex;align-items:center;justify-content:center;gap:7px;background:#f0fdf4;border:1.5px solid #86efac;color:#16a34a;font-family:var(--font-body);font-size:13px;font-weight:700;padding:10px 16px;border-radius:10px;text-decoration:none;transition:background .18s"
             onmouseover="this.style.background='#dcfce7'"
             onmouseout="this.style.background='#f0fdf4'">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="#16a34a"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            Poser une question sur WhatsApp
          </a>
        </div>

      </div>
    </div>
  </div>
</section>

{{-- ══════════════ CTA BAS ══════════════ --}}
<section class="sd-cta-band">
  <div class="container">
    <div class="sd-cta-band__inner">
      <div>
        <p class="sd-cta-band__title">Prêt à démarrer ?</p>
        <p class="sd-cta-band__sub">Notre équipe vous répond en moins d'1 heure en semaine.</p>
      </div>
      <div class="sd-cta-band__btns">
        <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="sd-cta-band__btn-wa">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
          Contacter sur WhatsApp
        </a>
        <a href="{{ route('service.list') }}" class="sd-cta-band__btn-back">
          <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
          Voir tous les services
        </a>
      </div>
    </div>
  </div>
</section>

@endsection
