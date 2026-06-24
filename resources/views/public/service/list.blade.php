@extends('layouts.app')
@section('title', 'Nos Services | Emploi Bouge Bénin')
@section('description', 'CV professionnel, lettre de motivation, coaching, logo, site web… Livrés en 30min à 48h.')

@section('css')
<style>
/* ======================================================
   PAGE SERVICES
====================================================== */

/* ── HERO ────────────────────────────────────────────── */
.svc-hero {
  background: linear-gradient(135deg, #021d36 0%, #042C53 58%, #0d4a8a 100%);
  padding: 80px 0 72px;
  overflow: hidden;
  position: relative;
}
.svc-hero::after {
  content: '';
  position: absolute;
  inset: 0;
  background: radial-gradient(ellipse 55% 90% at 72% 50%, rgba(245,200,66,.08) 0%, transparent 70%);
  pointer-events: none;
}
.svc-hero__inner {
  max-width: 1160px;
  margin: 0 auto;
  padding: 0 32px;
  display: grid;
  grid-template-columns: 1fr 440px;
  gap: 48px;
  align-items: center;
  position: relative;
  z-index: 2;
}
.svc-hero__tag {
  display: inline-block;
  background: rgba(245,200,66,.15);
  color: #F5C842;
  border: 1px solid rgba(245,200,66,.3);
  border-radius: 50px;
  padding: 5px 14px;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: .08em;
  text-transform: uppercase;
  margin-bottom: 20px;
}
.svc-hero__title {
  font-size: 2.8rem;
  font-weight: 900;
  color: #fff;
  line-height: 1.15;
  margin: 0 0 16px;
  letter-spacing: -.02em;
}
.svc-hero__title span { color: #F5C842; }
.svc-hero__sub {
  font-size: 1.05rem;
  color: rgba(255,255,255,.72);
  line-height: 1.7;
  margin: 0 0 30px;
}
.svc-hero__stats {
  display: flex;
  align-items: center;
  gap: 20px;
  margin-bottom: 36px;
}
.svc-stat { display: flex; flex-direction: column; gap: 2px; }
.svc-stat__n { font-size: 1.5rem; font-weight: 900; color: #F5C842; line-height: 1; }
.svc-stat__l { font-size: 10px; color: rgba(255,255,255,.5); text-transform: uppercase; letter-spacing: .07em; }
.svc-stat__sep { width: 1px; height: 36px; background: rgba(255,255,255,.15); }
.svc-hero__btn {
  display: inline-flex;
  align-items: center;
  background: #F5C842;
  color: #042C53;
  padding: 13px 28px;
  border-radius: 50px;
  font-weight: 800;
  font-size: .92rem;
  text-decoration: none;
  transition: background .2s, transform .18s;
}
.svc-hero__btn:hover { background: #f0bc1e; transform: translateY(-2px); }
.svc-hero__illus { display: flex; align-items: center; justify-content: center; }
.svc-hero__svg {
  width: 100%;
  max-width: 440px;
  filter: drop-shadow(0 20px 50px rgba(0,0,0,.3));
  animation: svcFloat 4s ease-in-out infinite;
}
@keyframes svcFloat {
  0%,100% { transform: translateY(0); }
  50%      { transform: translateY(-9px); }
}

/* ── SERVICE PHARE ────────────────────────────────────── */
.svc-feat-wrap {
  background: #f8fafc;
  padding: 64px 0;
}
.svc-feat {
  max-width: 1160px;
  margin: 0 auto;
  padding: 0 32px;
}
.svc-feat__card {
  background: linear-gradient(135deg, #042C53 0%, #0d4a8a 100%);
  border-radius: 20px;
  padding: 40px 44px;
  display: flex;
  gap: 36px;
  align-items: flex-start;
  position: relative;
  overflow: hidden;
  box-shadow: 0 16px 48px rgba(4,44,83,.18);
}
.svc-feat__card::before {
  content: '';
  position: absolute;
  top: -60px; right: -60px;
  width: 260px; height: 260px;
  border-radius: 50%;
  background: rgba(245,200,66,.06);
  pointer-events: none;
}
.svc-feat__ico {
  font-size: 3.5rem;
  width: 86px; height: 86px;
  background: rgba(255,255,255,.1);
  border-radius: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.svc-feat__body { flex: 1; min-width: 0; }
.svc-feat__badge {
  display: inline-block;
  background: rgba(245,200,66,.2);
  color: #F5C842;
  border: 1px solid rgba(245,200,66,.4);
  border-radius: 50px;
  padding: 4px 12px;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: .05em;
  margin-bottom: 12px;
}
.svc-feat__title {
  font-size: 1.65rem;
  font-weight: 900;
  color: #fff;
  margin: 0 0 10px;
  line-height: 1.25;
}
.svc-feat__desc {
  color: rgba(255,255,255,.7);
  font-size: .93rem;
  line-height: 1.65;
  margin: 0 0 16px;
}
.svc-feat__list {
  list-style: none;
  padding: 0; margin: 0 0 24px;
  display: flex; flex-direction: column; gap: 7px;
}
.svc-feat__list li {
  display: flex; align-items: center; gap: 10px;
  color: rgba(255,255,255,.8);
  font-size: .87rem;
}
.svc-feat__list li::before {
  content: '';
  width: 16px; height: 16px;
  flex-shrink: 0;
  border-radius: 50%;
  background: rgba(34,197,94,.18) url("data:image/svg+xml,%3Csvg viewBox='0 0 24 24' fill='none' stroke='%2386efac' stroke-width='3' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' d='M5 13l4 4L19 7'/%3E%3C/svg%3E") center/9px no-repeat;
  border: 1.5px solid rgba(134,239,172,.45);
}
.svc-feat__footer {
  display: flex; align-items: center; gap: 20px; flex-wrap: wrap;
}
.svc-feat__price { display: flex; align-items: baseline; gap: 6px; }
.svc-feat__price-from { font-size: 11px; color: rgba(255,255,255,.5); text-transform: uppercase; letter-spacing: .06em; }
.svc-feat__price-val  { font-size: 1.6rem; font-weight: 900; color: #F5C842; }
.svc-feat__price-del  { font-size: 12px; color: rgba(255,255,255,.4); }
.svc-feat__btn {
  display: inline-flex; align-items: center;
  background: #F5C842; color: #042C53;
  padding: 13px 28px;
  border-radius: 50px;
  font-weight: 800; font-size: .9rem;
  text-decoration: none; white-space: nowrap;
  transition: background .18s, transform .18s;
}
.svc-feat__btn:hover { background: #f0bc1e; transform: translateY(-2px); }

/* ── GRILLE SERVICES ──────────────────────────────────── */
.svc-all {
  background: #fff;
  padding: 64px 0;
}
.svc-all__inner {
  max-width: 1160px;
  margin: 0 auto;
  padding: 0 32px;
}
.svc-cat-block { margin-bottom: 52px; }
.svc-cat-block:last-child { margin-bottom: 0; }

.svc-cat-head {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 20px;
  padding-bottom: 14px;
  border-bottom: 2px solid #f1f5f9;
}
.svc-cat-head__ico {
  width: 42px; height: 42px;
  background: #f0f6ff;
  border-radius: 10px;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.svc-cat-head__title {
  font-size: 1.15rem;
  font-weight: 800;
  color: #042C53;
  margin: 0;
  flex: 1;
}
.svc-cat-head__count {
  background: #f1f5f9;
  color: #64748b;
  font-size: 11px;
  font-weight: 700;
  padding: 3px 9px;
  border-radius: 50px;
}

/* Grille */
.svc-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(230px, 1fr));
  gap: 16px;
}

/* Carte */
.svc-card {
  display: flex;
  flex-direction: column;
  gap: 10px;
  background: #fff;
  border: 1.5px solid #e2e8f0;
  border-radius: 14px;
  padding: 22px 18px 18px;
  text-decoration: none;
  color: inherit;
  position: relative;
  overflow: hidden;
  transition: transform .18s, box-shadow .18s, border-color .18s;
  cursor: pointer;
}
.svc-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 28px rgba(4,44,83,.10);
  border-color: #185FA5;
}
.svc-card--hot {
  border-color: rgba(245,200,66,.5);
  background: linear-gradient(160deg, #fffef0 0%, #fff 55%);
}
.svc-card--hot:hover { border-color: #F5C842; }

.svc-card__hot-badge {
  position: absolute;
  top: 10px; right: 10px;
  background: #F5C842;
  color: #042C53;
  font-size: 9px;
  font-weight: 800;
  padding: 3px 8px;
  border-radius: 50px;
}
.svc-card__icon {
  width: 40px; height: 40px;
  border-radius: 10px;
  background: #f0f6ff;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.svc-card--hot .svc-card__icon { background: rgba(245,200,66,.12); }
.svc-card__name {
  font-size: .93rem;
  font-weight: 800;
  color: #042C53;
  margin: 0;
  line-height: 1.3;
}
.svc-card__desc {
  font-size: .78rem;
  color: #64748b;
  line-height: 1.55;
  margin: 0;
  flex: 1;
}
.svc-card__foot {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 6px;
  padding-top: 10px;
  border-top: 1px solid #f1f5f9;
  margin-top: auto;
}
.svc-card__price {
  font-size: 1rem;
  font-weight: 900;
  color: #042C53;
}
.svc-card__price small { font-size: .65rem; color: #94a3b8; font-weight: 600; }
.svc-card__price--devis { font-size: .8rem; color: #64748b; font-weight: 700; }
.svc-card__link {
  font-size: .78rem;
  font-weight: 700;
  color: #185FA5;
  white-space: nowrap;
}

/* ── RÉASSURANCE ──────────────────────────────────────── */
.svc-trust {
  background: #042C53;
  padding: 44px 0;
}
.svc-trust__inner {
  max-width: 1160px;
  margin: 0 auto;
  padding: 0 32px;
  display: grid;
  grid-template-columns: repeat(4, 1fr);
}
.svc-trust__item {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 6px;
  text-align: center;
  padding: 16px 20px;
}
.svc-trust__item + .svc-trust__item { border-left: 1px solid rgba(255,255,255,.1); }
.svc-trust__ico {
  width: 44px; height: 44px;
  border-radius: 50%;
  background: rgba(255,255,255,.08);
  border: 1px solid rgba(255,255,255,.12);
  display: flex; align-items: center; justify-content: center;
}
.svc-trust__item > strong { font-size: .88rem; color: #fff; font-weight: 800; display: block; }
.svc-trust__item > em     { font-size: .74rem; color: rgba(255,255,255,.5); font-style: normal; display: block; }

/* ── CTA CONTACT ──────────────────────────────────────── */
.svc-cta {
  background: #f8fafc;
  padding: 60px 0;
}
.svc-cta__inner {
  max-width: 1160px;
  margin: 0 auto;
  padding: 0 32px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 32px;
  flex-wrap: wrap;
}
.svc-cta__eye {
  font-size: 11px; font-weight: 700;
  text-transform: uppercase; letter-spacing: .1em;
  color: #185FA5; margin: 0 0 6px;
}
.svc-cta__title {
  font-size: 1.8rem; font-weight: 900;
  color: #042C53; margin: 0 0 8px;
}
.svc-cta__sub { color: #64748b; font-size: .88rem; margin: 0; max-width: 420px; }
.svc-cta__btns { display: flex; gap: 12px; flex-wrap: wrap; }
.svc-cta__btn {
  display: inline-flex; align-items: center; gap: 8px;
  padding: 12px 24px; border-radius: 50px;
  font-weight: 800; font-size: .88rem; text-decoration: none;
  transition: transform .18s, box-shadow .18s;
}
.svc-cta__btn:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,0,0,.12); }
.svc-cta__btn--wa   { background: #25D366; color: #fff; }
.svc-cta__btn--mail { background: #042C53; color: #fff; }

/* ── RESPONSIVE ───────────────────────────────────────── */
@media (max-width: 1024px) {
  .svc-hero__inner { grid-template-columns: 1fr 360px; gap: 32px; }
  .svc-trust__inner { grid-template-columns: 1fr 1fr; }
  .svc-trust__item:nth-child(2) { border-left: 1px solid rgba(255,255,255,.1); }
  .svc-trust__item:nth-child(3) { border-left: none; border-top: 1px solid rgba(255,255,255,.1); }
  .svc-trust__item:nth-child(4) { border-top: 1px solid rgba(255,255,255,.1); }
}
@media (max-width: 860px) {
  .svc-hero { padding: 56px 0 48px; }
  .svc-hero__inner { grid-template-columns: 1fr; }
  .svc-hero__illus { display: none; }
  .svc-hero__title { font-size: 2.1rem; }
  .svc-feat__card { padding: 28px 28px; gap: 20px; }
  .svc-feat__ico  { width: 70px; height: 70px; font-size: 2.8rem; }
  .svc-feat__title { font-size: 1.35rem; }
}
@media (max-width: 640px) {
  .svc-hero { padding: 40px 0 36px; }
  .svc-hero__inner,
  .svc-feat,
  .svc-all__inner,
  .svc-trust__inner,
  .svc-cta__inner { padding-left: 18px; padding-right: 18px; }
  .svc-hero__title { font-size: 1.75rem; }
  .svc-hero__sub   { font-size: .9rem; }
  .svc-stat__n     { font-size: 1.2rem; }
  .svc-hero__btn   { padding: 11px 22px; }
  .svc-feat-wrap   { padding: 36px 0; }
  .svc-feat__card  { flex-direction: column; padding: 22px 20px; gap: 14px; border-radius: 14px; }
  .svc-feat__ico   { width: 56px; height: 56px; font-size: 2.2rem; }
  .svc-feat__title { font-size: 1.2rem; }
  .svc-feat__footer { flex-direction: column; align-items: flex-start; gap: 14px; }
  .svc-feat__btn   { width: 100%; justify-content: center; }
  .svc-all         { padding: 40px 0; }
  .svc-cat-block   { margin-bottom: 36px; }
  .svc-grid { grid-template-columns: 1fr 1fr; gap: 10px; }
  .svc-card { padding: 14px 12px; }
  .svc-card__icon  { font-size: 1.6rem; }
  .svc-card__name  { font-size: .85rem; }
  .svc-card__desc  { display: none; }
  .svc-card__price { font-size: .9rem; }
  .svc-trust { padding: 28px 0; }
  .svc-trust__inner { grid-template-columns: 1fr 1fr; }
  .svc-trust__item { padding: 14px 10px; }
  .svc-trust__ico  { width: 36px; height: 36px; }
  .svc-trust__item > strong { font-size: .78rem; }
  .svc-trust__item > em     { font-size: .68rem; }
  .svc-cta { padding: 44px 0; }
  .svc-cta__inner { flex-direction: column; text-align: center; gap: 20px; }
  .svc-cta__title { font-size: 1.4rem; }
  .svc-cta__sub   { max-width: 100%; }
  .svc-cta__btns  { justify-content: center; width: 100%; }
}
@media (max-width: 380px) {
  .svc-hero__title { font-size: 1.5rem; }
  .svc-stat__sep   { display: none; }
  .svc-grid { grid-template-columns: 1fr; }
  .svc-card__desc  { display: block; }
  .svc-card { padding: 16px 14px; }
  .svc-trust__inner { grid-template-columns: 1fr; }
  .svc-trust__item + .svc-trust__item { border-left: none; border-top: 1px solid rgba(255,255,255,.1); }
  .svc-trust__item:nth-child(3) { border-top: 1px solid rgba(255,255,255,.1); }
  .svc-trust__item:nth-child(4) { border-top: 1px solid rgba(255,255,255,.1); }
  .svc-cta__btns  { flex-direction: column; }
  .svc-cta__btn   { justify-content: center; }
}
</style>
@endsection

@section('content')

{{-- ═══════════════════════════════════ HERO ═══════════════════════════════════ --}}
<section class="svc-hero">
  <div class="svc-hero__inner">
    <div>
      <span class="svc-hero__tag">Services professionnels · Bénin</span>
      <h1 class="svc-hero__title">Tout ce dont vous avez<br><span>besoin pour réussir</span></h1>
      <p class="svc-hero__sub">CV, lettre de motivation, logo, site web, traduction… Des experts à votre service, livrés rapidement et à prix abordable.</p>
      <div class="svc-hero__stats">
        <div class="svc-stat"><span class="svc-stat__n">+500</span><span class="svc-stat__l">Livrés</span></div>
        <div class="svc-stat__sep"></div>
        <div class="svc-stat"><span class="svc-stat__n">{{ $services->count() }}</span><span class="svc-stat__l">Services</span></div>
        <div class="svc-stat__sep"></div>
        <div class="svc-stat"><span class="svc-stat__n">30min</span><span class="svc-stat__l">Délai min.</span></div>
      </div>
      <a href="#services" class="svc-hero__btn">
        Voir les services
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
      </a>
    </div>
    <div class="svc-hero__illus" aria-hidden="true">
      <svg viewBox="0 0 460 380" fill="none" xmlns="http://www.w3.org/2000/svg" class="svc-hero__svg">
        <circle cx="340" cy="70"  r="130" fill="#F5C842" fill-opacity=".07"/>
        <circle cx="90"  cy="300" r="90"  fill="#185FA5" fill-opacity=".08"/>
        <rect x="40" y="280" width="380" height="10" rx="5" fill="#e2e8f0"/>
        <rect x="130" y="150" width="200" height="135" rx="10" fill="#042C53"/>
        <rect x="138" y="158" width="184" height="115" rx="6"  fill="#0d4a8a"/>
        <rect x="148" y="168" width="72"  height="6"   rx="3"    fill="#F5C842" fill-opacity=".9"/>
        <rect x="148" y="180" width="110" height="3.5" rx="1.75" fill="white"   fill-opacity=".25"/>
        <rect x="148" y="189" width="90"  height="3.5" rx="1.75" fill="white"   fill-opacity=".25"/>
        <rect x="148" y="198" width="130" height="3.5" rx="1.75" fill="white"   fill-opacity=".18"/>
        <rect x="148" y="207" width="80"  height="3.5" rx="1.75" fill="#60a5fa" fill-opacity=".55"/>
        <rect x="148" y="216" width="104" height="3.5" rx="1.75" fill="white"   fill-opacity=".25"/>
        <rect x="148" y="225" width="66"  height="3.5" rx="1.75" fill="#F5C842" fill-opacity=".45"/>
        <rect x="148" y="234" width="120" height="3.5" rx="1.75" fill="white"   fill-opacity=".18"/>
        <rect x="112" y="285" width="236" height="10" rx="5" fill="#042C53"/>
        <rect x="195" y="295" width="70"  height="5"  rx="2.5" fill="#1e3a5f"/>
        <g transform="rotate(-7 95 215)">
          <rect x="42"  y="148" width="106" height="142" rx="10" fill="white" stroke="#e2e8f0" stroke-width="1.5"/>
          <rect x="54"  y="162" width="82"  height="7"  rx="3.5" fill="#042C53"/>
          <rect x="54"  y="176" width="58"  height="4"  rx="2"   fill="#94a3b8"/>
          <rect x="54"  y="194" width="82"  height="3"  rx="1.5" fill="#e2e8f0"/>
          <rect x="54"  y="202" width="68"  height="3"  rx="1.5" fill="#e2e8f0"/>
          <rect x="54"  y="210" width="76"  height="3"  rx="1.5" fill="#e2e8f0"/>
          <rect x="54"  y="222" width="82"  height="3"  rx="1.5" fill="#F5C842" fill-opacity=".6"/>
          <rect x="54"  y="230" width="62"  height="3"  rx="1.5" fill="#e2e8f0"/>
          <rect x="54"  y="238" width="72"  height="3"  rx="1.5" fill="#e2e8f0"/>
          <rect x="54"  y="250" width="48"  height="16" rx="5"   fill="#042C53"/>
          <rect x="58"  y="256" width="40"  height="4"  rx="2"   fill="white" fill-opacity=".8"/>
        </g>
        <circle cx="135" cy="155" r="15" fill="#22c55e"/>
        <path d="M128 155l5 5 9-9" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
        <g transform="rotate(5 370 195)">
          <rect x="320" y="145" width="96"  height="126" rx="10" fill="white" stroke="#e2e8f0" stroke-width="1.5"/>
          <rect x="332" y="158" width="72"  height="6"   rx="3"   fill="#185FA5"/>
          <rect x="332" y="171" width="56"  height="3"   rx="1.5" fill="#e2e8f0"/>
          <rect x="332" y="179" width="64"  height="3"   rx="1.5" fill="#e2e8f0"/>
          <rect x="332" y="187" width="50"  height="3"   rx="1.5" fill="#e2e8f0"/>
          <rect x="332" y="200" width="72"  height="3"   rx="1.5" fill="#F5C842" fill-opacity=".6"/>
          <rect x="332" y="208" width="60"  height="3"   rx="1.5" fill="#e2e8f0"/>
          <rect x="332" y="220" width="44"  height="14"  rx="5"   fill="#F5C842"/>
          <rect x="336" y="226" width="36"  height="3"   rx="1.5" fill="#042C53" fill-opacity=".8"/>
        </g>
        <circle cx="230" cy="105" r="26" fill="#185FA5"/>
        <circle cx="230" cy="98"  r="15" fill="#fde9d9"/>
        <circle cx="225" cy="95"  r="2.5" fill="#042C53"/>
        <circle cx="235" cy="95"  r="2.5" fill="#042C53"/>
        <path d="M225 103 Q230 107 235 103" stroke="#042C53" stroke-width="1.5" stroke-linecap="round" fill="none"/>
        <path d="M212 145 Q230 135 248 145 L252 158 Q230 150 208 158 Z" fill="#185FA5"/>
        <circle cx="280" cy="90"  r="4.5" fill="#F5C842"/>
        <circle cx="170" cy="108" r="3"   fill="#185FA5" fill-opacity=".5"/>
        <circle cx="400" cy="230" r="3.5" fill="#F5C842" fill-opacity=".6"/>
        <rect x="348" y="108" width="96" height="34" rx="10" fill="#042C53"/>
        <circle cx="362" cy="125" r="8" fill="#F5C842"/>
        <path d="M358 125l3 3 5-5" stroke="#042C53" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
        <rect x="374" y="118" width="60" height="4"   rx="2"    fill="white" fill-opacity=".8"/>
        <rect x="374" y="127" width="44" height="3.5" rx="1.75" fill="white" fill-opacity=".4"/>
      </svg>
    </div>
  </div>
</section>

{{-- ════════════════════ SERVICE PHARE ════════════════════ --}}
@php $star = $services->firstWhere('slug','cv-professionnel') ?? $services->first(); @endphp
@if($star)
<div class="svc-feat-wrap">
  <div class="svc-feat">
    <div class="svc-feat__card">
      <div class="svc-feat__ico">
        <svg width="38" height="38" fill="none" viewBox="0 0 24 24" stroke="rgba(255,255,255,.85)" stroke-width="1.75">
          <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
      </div>
      <div class="svc-feat__body">
        <span class="svc-feat__badge">Service n°1 · Le plus commandé</span>
        <h2 class="svc-feat__title">{{ $star->nom }}</h2>
        <p class="svc-feat__desc">{{ $star->description ?? 'Un recruteur décide en 7 secondes. Notre équipe rédige un CV optimisé qui passe les filtres ATS et retient l\'attention des recruteurs.' }}</p>
        <ul class="svc-feat__list">
          <li>CV structuré, moderne et optimisé ATS</li>
          <li>Lettre de motivation ciblée et personnalisée</li>
          <li>Livraison Word & PDF prêt à l'emploi</li>
          <li>1 révision gratuite jusqu'à satisfaction</li>
        </ul>
        <div class="svc-feat__footer">
          <div class="svc-feat__price">
            <span class="svc-feat__price-from">Seulement</span>
            <span class="svc-feat__price-val">{{ number_format($star->prix, 0, ',', ' ') }} FCFA</span>
            <span class="svc-feat__price-del">· livré en 30min</span>
          </div>
          <a href="{{ route('service.commande', $star->slug) }}" class="svc-feat__btn">Commander maintenant →</a>
        </div>
      </div>
    </div>
  </div>
</div>
@endif

{{-- ════════════════════ TOUS LES SERVICES ════════════════════ --}}
@php
$cats = [
  [
    'label' => 'Carrière & Emploi',
    'slugs' => ['cv-professionnel','lettre-de-motivation','preparation-entretien','linkedin-optimise','coaching-entretien','creation-cv-documents'],
    'svg'   => '<svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>',
  ],
  [
    'label' => 'Documents & Rédaction',
    'slugs' => ['traduction-documents','redaction-rapport-memoire'],
    'svg'   => '<svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#7c3aed" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>',
  ],
  [
    'label' => 'Digital & Web',
    'slugs' => ['creation-sites-web','creation-logo','gestion-reseaux-sociaux','marketing-digital','referencement-seo','developpement-applications'],
    'svg'   => '<svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#0891b2" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" d="M8 21h8M12 17v4"/></svg>',
  ],
  [
    'label' => 'Formation & Accompagnement',
    'slugs' => ['formation-informatique','accompagnement-digital'],
    'svg'   => '<svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#059669" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0v4m-4 2h8"/></svg>',
  ],
];
$bySlug = $services->keyBy('slug');

/* SVG path d= par service (stroke, viewBox 0 0 24 24) */
$svcPaths = [
  'cv-professionnel'          => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
  'lettre-de-motivation'      => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
  'preparation-entretien'     => 'M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z',
  'linkedin-optimise'         => 'M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71',
  'coaching-entretien'        => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z',
  'creation-cv-documents'     => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
  'traduction-documents'      => 'M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129',
  'redaction-rapport-memoire' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253',
  'creation-sites-web'        => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
  'creation-logo'             => 'M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5',
  'gestion-reseaux-sociaux'   => 'M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z',
  'marketing-digital'         => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6',
  'referencement-seo'         => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0',
  'developpement-applications'=> 'M10 20l4-16M5 12.5L1 12l4-.5M19 12l4 .5-4 .5',
  'formation-informatique'    => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
  'accompagnement-digital'    => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
];
$defaultSvcPath = 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z';

$hot = ['cv-professionnel','creation-logo'];
@endphp

<div class="svc-all" id="services">
  <div class="svc-all__inner">
    @foreach($cats as $cat)
      @php $items = collect($cat['slugs'])->map(fn($s)=>$bySlug->get($s))->filter()->values(); @endphp
      @if($items->count())
      <div class="svc-cat-block">
        <div class="svc-cat-head">
          <div class="svc-cat-head__ico">{!! $cat['svg'] !!}</div>
          <h2 class="svc-cat-head__title">{{ $cat['label'] }}</h2>
          <span class="svc-cat-head__count">{{ $items->count() }}</span>
        </div>
        <div class="svc-grid">
          @foreach($items as $svc)
          @php $isHot = in_array($svc->slug, $hot); @endphp
          <a href="{{ route('service.detail', $svc->slug) }}" class="svc-card{{ $isHot ? ' svc-card--hot' : '' }}">
            @if($isHot)<span class="svc-card__hot-badge">Populaire</span>@endif
            <div class="svc-card__icon">
              <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $svcPaths[$svc->slug] ?? $defaultSvcPath }}"/>
              </svg>
            </div>
            <h3 class="svc-card__name">{{ $svc->nom }}</h3>
            @if($svc->description)
              <p class="svc-card__desc">{{ Str::limit($svc->description, 90) }}</p>
            @endif
            <div class="svc-card__foot">
              @if($svc->prix > 0)
                <span class="svc-card__price">{{ number_format($svc->prix,0,',',' ') }} <small>FCFA</small></span>
              @else
                <span class="svc-card__price svc-card__price--devis">Sur devis</span>
              @endif
              <span class="svc-card__link">Commander →</span>
            </div>
          </a>
          @endforeach
        </div>
      </div>
      @endif
    @endforeach
  </div>
</div>

{{-- ════════════════════ RÉASSURANCE ════════════════════ --}}
<div class="svc-trust">
  <div class="svc-trust__inner">
    <div class="svc-trust__item">
      <div class="svc-trust__ico">
        <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="rgba(255,255,255,.85)" stroke-width="2"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 6v6l4 2"/></svg>
      </div>
      <strong>Livraison rapide</strong><em>30min à 48h</em>
    </div>
    <div class="svc-trust__item">
      <div class="svc-trust__ico">
        <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="rgba(255,255,255,.85)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
      </div>
      <strong>Paiement sécurisé</strong><em>MTN · Moov · Celtiis</em>
    </div>
    <div class="svc-trust__item">
      <div class="svc-trust__ico">
        <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="rgba(255,255,255,.85)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
      </div>
      <strong>1 révision offerte</strong><em>Satisfaction garantie</em>
    </div>
    <div class="svc-trust__item">
      <div class="svc-trust__ico">
        <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="rgba(255,255,255,.85)" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
      </div>
      <strong>Support WhatsApp</strong><em>Réponse en &lt; 1h</em>
    </div>
  </div>
</div>

{{-- ════════════════════ CTA ════════════════════ --}}
<div class="svc-cta">
  <div class="svc-cta__inner">
    <div>
      <p class="svc-cta__eye">Vous avez un projet ?</p>
      <h2 class="svc-cta__title">Parlons-en ensemble</h2>
      <p class="svc-cta__sub">Notre équipe vous répond en moins d'1h en semaine via WhatsApp ou email.</p>
    </div>
    <div class="svc-cta__btns">
      <a href="https://wa.me/22951929856" target="_blank" rel="noopener" class="svc-cta__btn svc-cta__btn--wa">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
        WhatsApp
      </a>
      <a href="{{ route('contact') }}" class="svc-cta__btn svc-cta__btn--mail">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        Nous écrire
      </a>
    </div>
  </div>
</div>

@endsection
