@extends('layouts.app')
@section('title', $service->nom . ' | Emploi Bouge Bénin')
@section('description', $service->description ? Str::limit(strip_tags($service->description), 155) : 'Découvrez ce service proposé par Emploi Bouge Bénin.')
@section('canonical', route('service.detail', $service->slug))

@section('css')
<style>
/* ── Wrapper page ── */
.sdp { --navy:#042C53;--blue:#185FA5;--gold:#F5C842;--green:#22c55e; }
.sdp-wrap {
  max-width: 1160px;
  margin: 0 auto;
  padding: 0 32px;
}

/* ══════════════════════════════
   HERO
══════════════════════════════ */
.sdp-hero {
  background: linear-gradient(135deg, #021d36 0%, #042C53 55%, #0d4a8a 100%);
  padding: 56px 0 64px;
  position: relative;
  overflow: hidden;
}
.sdp-hero::after {
  content: '';
  position: absolute;
  width: 500px; height: 500px;
  border-radius: 50%;
  background: rgba(245,200,66,.06);
  top: -160px; right: -120px;
  pointer-events: none;
}
.sdp-hero__back {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  color: rgba(255,255,255,.5);
  font-size: 13px;
  font-weight: 500;
  text-decoration: none;
  margin-bottom: 28px;
  transition: color .18s;
  position: relative; z-index: 2;
}
.sdp-hero__back:hover { color: rgba(255,255,255,.9); }

.sdp-hero__inner {
  display: grid;
  grid-template-columns: 1fr 310px;
  gap: 48px;
  align-items: start;
  position: relative; z-index: 2;
}
.sdp-hero__tag {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: rgba(245,200,66,.15);
  border: 1px solid rgba(245,200,66,.3);
  color: #F5C842;
  font-size: 10px;
  font-weight: 800;
  letter-spacing: .1em;
  text-transform: uppercase;
  padding: 5px 12px;
  border-radius: 50px;
  margin-bottom: 16px;
}
.sdp-hero__title {
  font-size: clamp(1.7rem, 3.5vw, 2.6rem);
  font-weight: 900;
  color: #fff;
  line-height: 1.18;
  margin: 0 0 14px;
  letter-spacing: -.02em;
}
.sdp-hero__desc {
  font-size: .97rem;
  color: rgba(255,255,255,.7);
  line-height: 1.7;
  margin: 0 0 28px;
  max-width: 560px;
}
.sdp-hero__chips {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}
.sdp-hero__chip {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: rgba(255,255,255,.1);
  border: 1px solid rgba(255,255,255,.18);
  color: rgba(255,255,255,.88);
  font-size: 12px;
  font-weight: 600;
  padding: 6px 12px;
  border-radius: 50px;
}

/* Carte prix dans le hero (droite) */
.sdp-hero__pcard {
  background: rgba(255,255,255,.06);
  backdrop-filter: blur(10px);
  border: 1.5px solid rgba(255,255,255,.12);
  border-radius: 20px;
  padding: 28px 24px;
  text-align: center;
}
.sdp-hero__pcard-label {
  font-size: 10px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: .1em;
  color: rgba(255,255,255,.45);
  margin: 0 0 10px;
}
.sdp-hero__pcard-price {
  font-size: 2.6rem;
  font-weight: 900;
  color: #F5C842;
  line-height: 1;
  margin: 0 0 4px;
}
.sdp-hero__pcard-cur {
  font-size: 12px;
  color: rgba(255,255,255,.45);
  margin: 0 0 16px;
}
.sdp-hero__pcard-devis {
  font-size: 1.3rem;
  font-weight: 800;
  color: #F5C842;
  margin: 0 0 8px;
}
.sdp-hero__pcard-delai {
  display: inline-flex;
  align-items: center;
  gap: 5px;
  background: rgba(34,197,94,.15);
  border: 1px solid rgba(34,197,94,.3);
  color: #86efac;
  font-size: 11px;
  font-weight: 600;
  padding: 5px 12px;
  border-radius: 50px;
  margin-bottom: 18px;
}
.sdp-hero__pcard-wa {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  background: #25d366;
  color: #fff;
  font-size: 14px;
  font-weight: 800;
  padding: 14px 20px;
  border-radius: 12px;
  text-decoration: none;
  box-shadow: 0 4px 18px rgba(37,211,102,.35);
  transition: transform .18s, box-shadow .18s;
  margin-bottom: 10px;
}
.sdp-hero__pcard-wa:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(37,211,102,.5); }
.sdp-hero__pcard-order {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  background: #F5C842;
  color: #042C53;
  font-size: 13px;
  font-weight: 800;
  padding: 13px 20px;
  border-radius: 12px;
  text-decoration: none;
  box-shadow: 0 4px 14px rgba(245,200,66,.35);
  transition: transform .18s, box-shadow .18s;
  margin-bottom: 14px;
}
.sdp-hero__pcard-order:hover { transform: translateY(-2px); box-shadow: 0 8px 22px rgba(245,200,66,.5); }
.sdp-hero__pcard-trust {
  font-size: 11px;
  color: rgba(255,255,255,.4);
  line-height: 1.7;
}

/* ══════════════════════════════
   CORPS
══════════════════════════════ */
.sdp-body {
  background: #f8fafc;
  padding: 52px 0 64px;
}
.sdp-layout {
  display: grid;
  grid-template-columns: 1fr 310px;
  gap: 32px;
  align-items: start;
}
.sdp-col { display: flex; flex-direction: column; gap: 24px; }

/* Carte générique */
.sdp-card {
  background: #fff;
  border-radius: 18px;
  border: 1.5px solid #e8eef6;
  padding: 28px 26px;
}
.sdp-card--dark {
  background: linear-gradient(135deg, #042C53 0%, #0d3d70 60%, #185FA5 100%);
  border: none;
}
.sdp-card__head {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 20px;
  padding-bottom: 14px;
  border-bottom: 1.5px solid #f1f5f9;
}
.sdp-card--dark .sdp-card__head { border-bottom-color: rgba(255,255,255,.1); }
.sdp-card__head-ico {
  width: 32px; height: 32px;
  background: #f0f6ff;
  border-radius: 8px;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.sdp-card--dark .sdp-card__head-ico { background: rgba(245,200,66,.15); }
.sdp-card__head-title {
  font-size: .82rem;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: .1em;
  color: #185FA5;
  margin: 0;
}
.sdp-card--dark .sdp-card__head-title { color: rgba(255,255,255,.6); }

/* Checklist */
.sdp-checklist { display: flex; flex-direction: column; gap: 12px; }
.sdp-check {
  display: flex;
  align-items: flex-start;
  gap: 12px;
}
.sdp-check__ico {
  width: 20px; height: 20px;
  border-radius: 50%;
  background: #f0fdf4;
  border: 1.5px solid #86efac;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
  margin-top: 1px;
}
.sdp-check__text {
  font-size: .9rem;
  color: #042C53;
  line-height: 1.5;
}

/* Étapes */
.sdp-steps { display: flex; flex-direction: column; gap: 20px; }
.sdp-step { display: flex; align-items: flex-start; gap: 14px; }
.sdp-step__num {
  width: 34px; height: 34px;
  border-radius: 50%;
  background: linear-gradient(135deg, #042C53, #185FA5);
  color: #fff;
  font-size: 13px;
  font-weight: 800;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.sdp-step__title { font-size: .88rem; font-weight: 800; color: #042C53; margin: 0 0 3px; }
.sdp-step__desc  { font-size: .8rem; color: #64748b; line-height: 1.6; margin: 0; }

/* Grid "Pourquoi nous" */
.sdp-why-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 16px;
}
.sdp-why-item {
  display: flex;
  gap: 10px;
  align-items: flex-start;
}
.sdp-why-ico {
  width: 26px; height: 26px;
  border-radius: 50%;
  background: rgba(245,200,66,.15);
  border: 1px solid rgba(245,200,66,.3);
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.sdp-why-title { font-size: .82rem; font-weight: 800; color: #fff; margin: 0 0 3px; }
.sdp-why-desc  { font-size: .75rem; color: rgba(255,255,255,.6); margin: 0; line-height: 1.5; }

/* ── Sidebar ── */
.sdp-sidebar {
  display: flex;
  flex-direction: column;
  gap: 18px;
  position: sticky;
  top: 88px;
}
.sdp-side-card {
  background: #fff;
  border-radius: 16px;
  border: 1.5px solid #e8eef6;
  padding: 20px 18px;
}
.sdp-side-card__title {
  font-size: .82rem;
  font-weight: 800;
  color: #042C53;
  margin: 0 0 14px;
}
.sdp-side-contact {
  display: flex;
  align-items: center;
  gap: 9px;
  font-size: .8rem;
  color: #64748b;
  margin-bottom: 10px;
}
.sdp-side-contact svg { color: #185FA5; flex-shrink: 0; }
.sdp-side-wa {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 7px;
  background: #f0fdf4;
  border: 1.5px solid #86efac;
  color: #16a34a;
  font-size: .8rem;
  font-weight: 700;
  padding: 10px 14px;
  border-radius: 10px;
  text-decoration: none;
  transition: background .18s;
}
.sdp-side-wa:hover { background: #dcfce7; }

/* ── Bande CTA bas ── */
.sdp-cta {
  background: #f0f7ff;
  border-top: 1.5px solid #bfdbfe;
  padding: 40px 0;
}
.sdp-cta__inner {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 24px;
  flex-wrap: wrap;
}
.sdp-cta__title { font-size: 1.1rem; font-weight: 800; color: #042C53; margin: 0 0 4px; }
.sdp-cta__sub   { font-size: .82rem; color: #64748b; margin: 0; }
.sdp-cta__btns  { display: flex; gap: 10px; flex-wrap: wrap; flex-shrink: 0; }
.sdp-cta__btn {
  display: inline-flex; align-items: center; gap: 7px;
  padding: 11px 20px; border-radius: 10px;
  font-size: .85rem; font-weight: 700; text-decoration: none;
  transition: transform .18s;
}
.sdp-cta__btn:hover { transform: translateY(-2px); }
.sdp-cta__btn--wa   { background: #25d366; color: #fff; }
.sdp-cta__btn--back { background: #fff; border: 1.5px solid #bfdbfe; color: #185FA5; }
.sdp-cta__btn--back:hover { background: #ebf4fd; }

/* ══════════════════════════════
   RESPONSIVE
══════════════════════════════ */
@media (max-width: 900px) {
  .sdp-hero__inner { grid-template-columns: 1fr; gap: 28px; }
  .sdp-hero__pcard { max-width: 400px; }
  .sdp-layout { grid-template-columns: 1fr; }
  .sdp-sidebar { position: static; }
}
@media (max-width: 640px) {
  .sdp-wrap { padding: 0 18px; }
  .sdp-hero { padding: 40px 0 48px; }
  .sdp-hero__title { font-size: 1.6rem; }
  .sdp-hero__desc  { font-size: .88rem; }
  .sdp-body { padding: 36px 0 48px; }
  .sdp-card { padding: 20px 16px; }
  .sdp-why-grid { grid-template-columns: 1fr; gap: 14px; }
  .sdp-cta { padding: 32px 0; }
  .sdp-cta__inner { flex-direction: column; align-items: flex-start; gap: 16px; }
  .sdp-cta__btns  { width: 100%; }
  .sdp-cta__btn   { flex: 1; justify-content: center; }
}
@media (max-width: 380px) {
  .sdp-hero__title { font-size: 1.4rem; }
  .sdp-hero__chips { gap: 6px; }
  .sdp-hero__chip  { font-size: 11px; padding: 5px 10px; }
}
</style>
@endsection

@section('content')

@php
/* ── Données contextuelles ── */
$cats = ['cv-professionnel'=>'Carrière & Emploi','preparation-entretien'=>'Carrière & Emploi','linkedin-optimise'=>'Carrière & Emploi','coaching-entretien'=>'Carrière & Emploi','creation-cv-documents'=>'Carrière & Emploi','traduction-documents'=>'Documents & Rédaction','redaction-rapport-memoire'=>'Documents & Rédaction','creation-sites-web'=>'Digital & Web','creation-logo'=>'Digital & Web','gestion-reseaux-sociaux'=>'Digital & Web','marketing-digital'=>'Digital & Web','referencement-seo'=>'Digital & Web','developpement-applications'=>'Digital & Web','formation-informatique'=>'Formation','accompagnement-digital'=>'Formation'];
$catLabel = $cats[$service->slug] ?? 'Service';

$delai = $service->delai;
$chips = match($service->slug) {
  'cv-professionnel','creation-cv-documents' => [$delai ?? '30min à 1h','Format Word & PDF','1 révision offerte'],
  'linkedin-optimise'       => [$delai ?? '1h à 2h','Profil optimisé ATS','Mots-clés recruteurs'],
  'coaching-entretien','preparation-entretien' => [$delai ?? '1h de session','Feedback détaillé','Plan d\'action'],
  'traduction-documents'    => [$delai ? 'Délai : '.$delai : 'Délai : 1h','FR ↔ EN','Document certifié'],
  'redaction-rapport-memoire'=> [$delai ?? '12h à 24h','Selon normes','Révision incluse'],
  'creation-logo'           => [$delai ?? '24h à 48h','Fichiers HD livrés','Charte graphique'],
  'creation-sites-web'      => ['Responsive','SEO inclus','1 mois de support'],
  default                   => array_filter([$delai, 'Équipe experte', 'Réponse rapide']),
};

$steps = match(true) {
  in_array($service->slug, ['cv-professionnel','linkedin-optimise','creation-cv-documents']) => [
    ['Commandez en ligne','Remplissez le formulaire avec vos informations et objectifs.'],
    ['Analyse de votre profil','Nos experts étudient votre parcours et le poste visé.'],
    ['Rédaction & livraison','Document livré en Word & PDF dans les délais indiqués.'],
    ['Révision si besoin','1 révision gratuite jusqu\'à votre satisfaction totale.'],
  ],
  in_array($service->slug, ['coaching-entretien','preparation-entretien']) => [
    ['Réservez votre session','Passez commande et choisissez votre créneau disponible.'],
    ['Confirmation & lien','Recevez votre lien de visioconférence par email.'],
    ['Session intensive','1h avec simulation d\'entretien et feedback en temps réel.'],
    ['Plan d\'action','Recevez votre plan d\'amélioration personnalisé par écrit.'],
  ],
  $service->slug === 'creation-logo' => [
    ['Brief créatif','Dites-nous votre vision, vos couleurs préférées, votre univers.'],
    ['Propositions','Nous vous soumettons 2–3 concepts graphiques distincts.'],
    ['Ajustements','Vous choisissez et nous peaufinons selon vos retours.'],
    ['Livraison finale','Fichiers HD (PNG, SVG, PDF) livrés avec charte graphique.'],
  ],
  default => [
    ['Prise de contact','Écrivez-nous sur WhatsApp ou via notre formulaire en ligne.'],
    ['Analyse du besoin','Notre équipe étudie votre projet et prépare une proposition.'],
    ['Devis & validation','Vous recevez un devis clair. On démarre dès votre accord.'],
    ['Livraison & suivi','Projet réalisé dans les délais avec suivi complet inclus.'],
  ],
};

$waNumber  = \App\Models\ParametreApp::get('whatsapp_number', '22951929856');
$waMessage = urlencode('Bonjour, je suis intéressé(e) par votre service « ' . $service->nom . ' ». Pouvez-vous me donner plus d\'informations ?');
$waUrl     = 'https://wa.me/' . $waNumber . '?text=' . $waMessage;
@endphp

<div class="sdp">

{{-- ═══════════════════ HERO ═══════════════════ --}}
<div class="sdp-hero">
  <div class="sdp-wrap">
    <a href="{{ route('service.list') }}" class="sdp-hero__back">
      <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
      Tous nos services
    </a>
    <div class="sdp-hero__inner">
      <div>
        <span class="sdp-hero__tag">{{ $catLabel }}</span>
        <h1 class="sdp-hero__title">{{ $service->nom }}</h1>
        @if($service->description)
          <p class="sdp-hero__desc">{{ $service->description }}</p>
        @endif
        <div class="sdp-hero__chips">
          @foreach($chips as $chip)
            <span class="sdp-hero__chip">
              <svg width="7" height="7" fill="#F5C842" viewBox="0 0 8 8"><circle cx="4" cy="4" r="4"/></svg>
              {{ $chip }}
            </span>
          @endforeach
        </div>
      </div>

      {{-- Carte prix hero --}}
      <div class="sdp-hero__pcard">
        <p class="sdp-hero__pcard-label">Prix du service</p>
        @if($service->prix > 0)
          <p class="sdp-hero__pcard-price">{{ number_format($service->prix,0,',',' ') }}</p>
          <p class="sdp-hero__pcard-cur">FCFA tout compris</p>
        @else
          <p class="sdp-hero__pcard-devis">Sur devis</p>
          <p class="sdp-hero__pcard-cur">Contactez-nous pour un devis</p>
        @endif
        @if($service->delai)
          <div class="sdp-hero__pcard-delai">
            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 6v6l4 2"/></svg>
            {{ $service->delai }}
          </div>
        @endif
        <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="sdp-hero__pcard-wa">
          <svg width="17" height="17" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
          Nous écrire sur WhatsApp
        </a>
        @if($service->prix > 0)
        <a href="{{ route('service.commande', $service) }}" class="sdp-hero__pcard-order">
          <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
          Commander maintenant
        </a>
        @endif
        <p class="sdp-hero__pcard-trust">Réponse garantie sous 1h &nbsp;·&nbsp; Paiement sécurisé</p>
      </div>
    </div>
  </div>
</div>

{{-- ═══════════════════ CORPS ═══════════════════ --}}
<div class="sdp-body">
  <div class="sdp-wrap">
    <div class="sdp-layout">

      {{-- Colonne principale --}}
      <div class="sdp-col">

        {{-- Ce qui est inclus --}}
        @if($service->details)
        <div class="sdp-card">
          <div class="sdp-card__head">
            <div class="sdp-card__head-ico">
              <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="sdp-card__head-title">Ce qui est inclus</p>
          </div>
          <div class="sdp-checklist">
            @foreach(array_filter(array_map('trim', explode("\n", $service->details))) as $line)
            <div class="sdp-check">
              <div class="sdp-check__ico">
                <svg width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="#16a34a" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
              </div>
              <span class="sdp-check__text">{{ $line }}</span>
            </div>
            @endforeach
          </div>
        </div>
        @endif

        {{-- Processus --}}
        <div class="sdp-card">
          <div class="sdp-card__head">
            <div class="sdp-card__head-ico">
              <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <p class="sdp-card__head-title">Comment ça se passe</p>
          </div>
          <div class="sdp-steps">
            @foreach($steps as $i => [$title, $desc])
            <div class="sdp-step">
              <div class="sdp-step__num">{{ $i + 1 }}</div>
              <div>
                <p class="sdp-step__title">{{ $title }}</p>
                <p class="sdp-step__desc">{{ $desc }}</p>
              </div>
            </div>
            @endforeach
          </div>
        </div>

        {{-- Pourquoi nous --}}
        <div class="sdp-card sdp-card--dark">
          <div class="sdp-card__head">
            <div class="sdp-card__head-ico">
              <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#F5C842" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
            </div>
            <p class="sdp-card__head-title">Pourquoi choisir Emploi Bouge Bénin</p>
          </div>
          <div class="sdp-why-grid">
            @foreach([
              ['Réponse rapide','Notre équipe répond en moins d\'1 heure en semaine.'],
              ['Expertise locale','Nous connaissons le marché béninois et africain.'],
              ['Satisfaction garantie','Votre satisfaction est notre priorité absolue.'],
              ['Accompagnement continu','Disponibles après livraison pour toute question.'],
            ] as [$wTitle, $wDesc])
            <div class="sdp-why-item">
              <div class="sdp-why-ico">
                <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="#F5C842" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
              </div>
              <div>
                <p class="sdp-why-title">{{ $wTitle }}</p>
                <p class="sdp-why-desc">{{ $wDesc }}</p>
              </div>
            </div>
            @endforeach
          </div>
        </div>

      </div>

      {{-- Sidebar (desktop) --}}
      <div class="sdp-sidebar">
        <div class="sdp-side-card">
          <p class="sdp-side-card__title">Des questions avant de commander ?</p>
          <div class="sdp-side-contact">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            emploibouge@gmail.com
          </div>
          <div class="sdp-side-contact">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="#25d366"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            +229 01 51 92 98 56
          </div>
          <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="sdp-side-wa">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="#16a34a"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
            Poser une question
          </a>
        </div>

        {{-- Autres services --}}
        <div class="sdp-side-card">
          <p class="sdp-side-card__title">Vous pourriez aussi aimer</p>
          @php
          $related = \App\Models\Service::where('actif',true)->where('slug','!=',$service->slug)->inRandomOrder()->take(3)->get();
          @endphp
          @foreach($related as $rel)
          <a href="{{ route('service.detail', $rel->slug) }}"
             style="display:flex;align-items:center;justify-content:space-between;gap:8px;padding:9px 0;border-bottom:1px solid #f1f5f9;text-decoration:none;color:#042C53;font-size:.8rem;font-weight:600;transition:color .15s"
             onmouseover="this.style.color='#185FA5'" onmouseout="this.style.color='#042C53'">
            <span>{{ $rel->nom }}</span>
            @if($rel->prix > 0)
              <span style="color:#185FA5;font-weight:700;white-space:nowrap;font-size:.75rem">{{ number_format($rel->prix,0,',',' ') }} F</span>
            @else
              <span style="color:#94a3b8;font-size:.72rem">Devis</span>
            @endif
          </a>
          @endforeach
        </div>
      </div>

    </div>
  </div>
</div>

{{-- ═══════════════════ CTA BAS ═══════════════════ --}}
<div class="sdp-cta">
  <div class="sdp-wrap">
    <div class="sdp-cta__inner">
      <div>
        <p class="sdp-cta__title">Prêt à démarrer ?</p>
        <p class="sdp-cta__sub">Notre équipe vous répond en moins d'1h en semaine.</p>
      </div>
      <div class="sdp-cta__btns">
        <a href="{{ $waUrl }}" target="_blank" rel="noopener" class="sdp-cta__btn sdp-cta__btn--wa">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
          WhatsApp
        </a>
        <a href="{{ route('service.list') }}" class="sdp-cta__btn sdp-cta__btn--back">
          <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
          Tous les services
        </a>
      </div>
    </div>
  </div>
</div>

</div>{{-- .sdp --}}
@endsection
