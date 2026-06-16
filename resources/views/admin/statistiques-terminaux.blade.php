@extends('layouts.admin')
@section('title', 'Terminaux & Géographie')

@section('css')
<style>
/* ── Hero banner ── */
.st-hero {
  background: linear-gradient(135deg, #042C53 0%, #185FA5 60%, #378ADD 100%);
  border-radius: 18px;
  padding: 28px 32px;
  margin-bottom: 24px;
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 24px;
  flex-wrap: wrap;
  position: relative;
  overflow: hidden;
}
.st-hero::before {
  content: '';
  position: absolute;
  top: -40px; right: -40px;
  width: 220px; height: 220px;
  border-radius: 50%;
  background: rgba(255,255,255,0.04);
  pointer-events: none;
}
.st-hero::after {
  content: '';
  position: absolute;
  bottom: -60px; left: 30%;
  width: 300px; height: 300px;
  border-radius: 50%;
  background: rgba(255,255,255,0.03);
  pointer-events: none;
}
.st-hero__left { flex: 1; min-width: 200px; }
.st-hero__badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: rgba(245,200,66,.18);
  border: 1px solid rgba(245,200,66,.35);
  color: #F5C842;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: .07em;
  text-transform: uppercase;
  padding: 4px 12px;
  border-radius: 20px;
  margin-bottom: 10px;
}
.st-hero__title { color: #fff; font-size: 1.5rem; font-weight: 700; margin: 0 0 4px; }
.st-hero__sub   { color: rgba(255,255,255,.65); font-size: .84rem; margin: 0; }

/* Period filter */
.st-period { display: flex; gap: 6px; flex-wrap: wrap; margin-top: 18px; }
.st-period__btn {
  padding: 7px 16px;
  border-radius: 9px;
  font-size: 12.5px;
  font-weight: 600;
  text-decoration: none;
  border: 1px solid rgba(255,255,255,.22);
  color: rgba(255,255,255,.75);
  background: rgba(255,255,255,.08);
  transition: background .15s, color .15s, border-color .15s;
}
.st-period__btn:hover { background: rgba(255,255,255,.16); color: #fff; }
.st-period__btn--active {
  background: #F5C842;
  color: #042C53;
  border-color: #F5C842;
}

/* Hero stat blocs */
.st-hero__right { display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-start; }
.st-hero-stat {
  background: rgba(255,255,255,.10);
  border: 1px solid rgba(255,255,255,.15);
  border-radius: 14px;
  padding: 16px 20px;
  min-width: 120px;
  text-align: center;
  backdrop-filter: blur(4px);
}
.st-hero-stat__val { font-size: 1.65rem; font-weight: 700; color: #fff; line-height: 1; }
.st-hero-stat__label { font-size: 11px; color: rgba(255,255,255,.65); margin-top: 5px; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; }

/* ── Device cards ── */
.st-device-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-bottom: 24px; }
.st-device-card {
  background: #fff;
  border: 1px solid #E2E8F0;
  border-radius: 14px;
  padding: 20px;
  display: flex;
  align-items: center;
  gap: 16px;
  position: relative;
  overflow: hidden;
}
.st-device-card::after {
  content: '';
  position: absolute;
  bottom: 0; left: 0; right: 0;
  height: 3px;
  border-radius: 0 0 14px 14px;
}
.st-device-card--mobile::after  { background: linear-gradient(90deg,#16a34a,#4ade80); }
.st-device-card--desktop::after { background: linear-gradient(90deg,#185FA5,#378ADD); }
.st-device-card--tablet::after  { background: linear-gradient(90deg,#7c3aed,#a78bfa); }
.st-device-icon {
  width: 52px; height: 52px;
  border-radius: 14px;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.st-device-icon--mobile  { background: #dcfce7; color: #16a34a; }
.st-device-icon--desktop { background: #dbeafe; color: #185FA5; }
.st-device-icon--tablet  { background: #ede9fe; color: #7c3aed; }
.st-device-pct { font-size: 1.8rem; font-weight: 700; color: #042C53; line-height: 1; }
.st-device-label { font-size: 12px; color: #718096; margin-top: 3px; font-weight: 600; }
.st-device-count {
  position: absolute; top: 14px; right: 16px;
  font-size: 11.5px; color: #94a3b8; font-weight: 600;
  background: #f8fafc; padding: 2px 8px; border-radius: 20px;
  border: 1px solid #E2E8F0;
}

/* ── Charts row ── */
.st-charts-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; }
.st-charts-row--wide { grid-template-columns: 380px 1fr; }

/* ── Card header override for this page ── */
.adm-card__head {
  padding: 16px 22px;
  border-bottom: 1px solid #E2E8F0;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 10px;
}
.adm-card__head-title {
  font-size: .9rem;
  font-weight: 700;
  color: #042C53;
  display: flex;
  align-items: center;
  gap: 8px;
}
.adm-card__head-title-icon {
  width: 28px; height: 28px;
  border-radius: 8px;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.adm-card__head-badge {
  font-size: 11px; font-weight: 600;
  color: #718096; background: #f1f5f9;
  padding: 3px 10px; border-radius: 20px;
}

/* ── Donut center ── */
.st-donut-wrap { position: relative; width: 160px; height: 160px; flex-shrink: 0; }
.st-donut-center {
  position: absolute;
  inset: 0;
  display: flex; flex-direction: column;
  align-items: center; justify-content: center;
  pointer-events: none;
}
.st-donut-center__val  { font-size: 1.4rem; font-weight: 700; color: #042C53; }
.st-donut-center__lbl  { font-size: 10px; color: #94a3b8; font-weight: 600; text-transform: uppercase; letter-spacing: .04em; }

/* ── Progress bars ── */
.st-bar-row { display: flex; flex-direction: column; gap: 13px; }
.st-bar-item {}
.st-bar-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 5px; }
.st-bar-name { font-size: 13px; font-weight: 600; color: #374151; display: flex; align-items: center; gap: 7px; }
.st-bar-dot  { width: 9px; height: 9px; border-radius: 50%; flex-shrink: 0; }
.st-bar-num  { font-size: 13px; font-weight: 700; color: #042C53; }
.st-bar-pct  { font-size: 11.5px; color: #94a3b8; margin-left: 4px; }
.st-bar-track { height: 7px; background: #f1f5f9; border-radius: 99px; overflow: hidden; }
.st-bar-fill  { height: 100%; border-radius: 99px; transition: width .6s ease; }

/* ── Table geo ── */
.st-geo-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.st-rank {
  width: 24px; height: 24px;
  border-radius: 8px;
  background: #f1f5f9;
  color: #718096;
  font-size: 11.5px;
  font-weight: 700;
  display: inline-flex;
  align-items: center; justify-content: center;
}
.st-rank--1 { background: #fef3c7; color: #92400e; }
.st-rank--2 { background: #f1f5f9; color: #374151; }
.st-rank--3 { background: #fef2f2; color: #9b2c2c; }
.st-inline-bar {
  height: 4px;
  background: #f1f5f9;
  border-radius: 99px;
  margin-top: 5px;
  overflow: hidden;
}
.st-empty {
  padding: 48px 24px;
  text-align: center;
}
.st-empty__icon {
  width: 56px; height: 56px;
  border-radius: 16px;
  background: #f1f5f9;
  display: flex; align-items: center; justify-content: center;
  margin: 0 auto 16px;
  color: #cbd5e0;
}
.st-empty__title { font-size: .9rem; font-weight: 700; color: #374151; margin-bottom: 6px; }
.st-empty__desc  { font-size: .8rem; color: #94a3b8; max-width: 280px; margin: 0 auto; line-height: 1.6; }

/* Responsive */
@media (max-width: 900px) {
  .st-device-grid  { grid-template-columns: 1fr; }
  .st-charts-row   { grid-template-columns: 1fr; }
  .st-charts-row--wide { grid-template-columns: 1fr; }
  .st-geo-grid     { grid-template-columns: 1fr; }
  .st-hero { flex-direction: column; }
}
</style>
@endsection

@section('content')
@php
  $total_dev = array_sum($devices);
  $pct = fn($n) => $total_dev > 0 ? round($n / $total_dev * 100, 1) : 0;
@endphp

{{-- ══════════════════════════════════════════
     HERO BANNER
══════════════════════════════════════════ --}}
<div class="st-hero">
  <div class="st-hero__left">
    <div class="st-hero__badge">
      <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
      Analyse d'audience
    </div>
    <h1 class="st-hero__title">Terminaux &amp; Géographie</h1>
    <p class="st-hero__sub">Appareils, navigateurs, systèmes d'exploitation et localisation des visiteurs</p>
    <div class="st-period">
      @foreach([['1',"Aujourd'hui"],['7','7 jours'],['30','30 jours'],['90','90 jours'],['tout','Tout']] as [$val,$label])
        <a href="{{ route('admin.statistiques.terminaux', ['periode' => $val]) }}"
           class="st-period__btn {{ $periode === $val ? 'st-period__btn--active' : '' }}">
          {{ $label }}
        </a>
      @endforeach
    </div>
  </div>
  <div class="st-hero__right">
    <div class="st-hero-stat">
      <div class="st-hero-stat__val">{{ number_format($total, 0, ',', ' ') }}</div>
      <div class="st-hero-stat__label">Visites totales</div>
    </div>
    <div class="st-hero-stat">
      <div class="st-hero-stat__val">{{ number_format($uniques, 0, ',', ' ') }}</div>
      <div class="st-hero-stat__label">Visiteurs uniques</div>
    </div>
    @if($total_dev > 0)
    <div class="st-hero-stat">
      <div class="st-hero-stat__val">{{ $pct($devices['mobile']) }}%</div>
      <div class="st-hero-stat__label">Sur mobile</div>
    </div>
    @endif
  </div>
</div>

{{-- ══════════════════════════════════════════
     DEVICE CARDS
══════════════════════════════════════════ --}}
<div class="st-device-grid">

  {{-- Téléphone --}}
  <div class="st-device-card st-device-card--mobile">
    <div class="st-device-icon st-device-icon--mobile">
      <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
        <rect x="5" y="2" width="14" height="20" rx="2" ry="2"/>
        <line x1="12" y1="18" x2="12.01" y2="18" stroke-width="2.5" stroke-linecap="round"/>
      </svg>
    </div>
    <div>
      <div class="st-device-pct">{{ $pct($devices['mobile']) }}<span style="font-size:1rem;font-weight:500;color:#94a3b8">%</span></div>
      <div class="st-device-label">Téléphone mobile</div>
    </div>
    <div class="st-device-count">{{ number_format($devices['mobile'], 0, ',', ' ') }} visites</div>
  </div>

  {{-- Ordinateur --}}
  <div class="st-device-card st-device-card--desktop">
    <div class="st-device-icon st-device-icon--desktop">
      <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
        <line x1="8" y1="21" x2="16" y2="21"/>
        <line x1="12" y1="17" x2="12" y2="21"/>
      </svg>
    </div>
    <div>
      <div class="st-device-pct">{{ $pct($devices['ordinateur']) }}<span style="font-size:1rem;font-weight:500;color:#94a3b8">%</span></div>
      <div class="st-device-label">Ordinateur</div>
    </div>
    <div class="st-device-count">{{ number_format($devices['ordinateur'], 0, ',', ' ') }} visites</div>
  </div>

  {{-- Tablette --}}
  <div class="st-device-card st-device-card--tablet">
    <div class="st-device-icon st-device-icon--tablet">
      <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
        <rect x="4" y="2" width="16" height="20" rx="2" ry="2"/>
        <line x1="12" y1="18" x2="12.01" y2="18" stroke-width="2.5" stroke-linecap="round"/>
      </svg>
    </div>
    <div>
      <div class="st-device-pct">{{ $pct($devices['tablette']) }}<span style="font-size:1rem;font-weight:500;color:#94a3b8">%</span></div>
      <div class="st-device-label">Tablette</div>
    </div>
    <div class="st-device-count">{{ number_format($devices['tablette'], 0, ',', ' ') }} visites</div>
  </div>

</div>

{{-- ══════════════════════════════════════════
     GRAPHIQUES LIGNE 1 — Donut + Navigateurs
══════════════════════════════════════════ --}}
<div class="st-charts-row st-charts-row--wide" style="margin-bottom:16px">

  {{-- Donut terminaux --}}
  <div class="adm-card">
    <div class="adm-card__head">
      <div class="adm-card__head-title">
        <span class="adm-card__head-title-icon" style="background:#eff6ff">
          <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="2"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3"/></svg>
        </span>
        Répartition des terminaux
      </div>
      <span class="adm-card__head-badge">{{ $total_dev }} visites</span>
    </div>
    <div class="adm-card__body">
      @if($total_dev === 0)
        <div class="st-empty">
          <div class="st-empty__icon">
            <svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
          </div>
          <div class="st-empty__title">Aucune visite enregistrée</div>
          <div class="st-empty__desc">Les données apparaissent au fil des visites sur le site.</div>
        </div>
      @else
      <div style="display:flex;align-items:center;gap:28px;flex-wrap:wrap">
        <div class="st-donut-wrap">
          <canvas id="chartDevice" width="160" height="160"></canvas>
          <div class="st-donut-center">
            <div class="st-donut-center__val">{{ $total_dev }}</div>
            <div class="st-donut-center__lbl">visites</div>
          </div>
        </div>
        <div class="st-bar-row" style="flex:1;min-width:130px">
          @foreach([['mobile','Téléphone','#16a34a'],['ordinateur','Ordinateur','#185FA5'],['tablette','Tablette','#7c3aed']] as [$key,$label,$color])
          <div class="st-bar-item">
            <div class="st-bar-top">
              <span class="st-bar-name">
                <span class="st-bar-dot" style="background:{{ $color }}"></span>
                {{ $label }}
              </span>
              <span>
                <span class="st-bar-num">{{ $pct($devices[$key]) }}%</span>
                <span class="st-bar-pct">({{ number_format($devices[$key], 0, ',', ' ') }})</span>
              </span>
            </div>
            <div class="st-bar-track">
              <div class="st-bar-fill" style="width:{{ $pct($devices[$key]) }}%;background:{{ $color }}"></div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
      @endif
    </div>
  </div>

  {{-- Navigateurs --}}
  <div class="adm-card">
    <div class="adm-card__head">
      <div class="adm-card__head-title">
        <span class="adm-card__head-title-icon" style="background:#f0fdf4">
          <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#16a34a" stroke-width="2"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M2 12h20M12 2a15.3 15.3 0 010 20M12 2a15.3 15.3 0 000 20"/></svg>
        </span>
        Navigateurs web
      </div>
      @if(!$parBrowser->isEmpty())
      <span class="adm-card__head-badge">{{ $parBrowser->count() }} types</span>
      @endif
    </div>
    <div class="adm-card__body">
      @if($parBrowser->isEmpty())
        <div class="st-empty">
          <div class="st-empty__icon">
            <svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/></svg>
          </div>
          <div class="st-empty__title">Aucune donnée</div>
        </div>
      @else
      @php $totalBr = $parBrowser->sum(); $brColors = ['Chrome'=>'#4285f4','Firefox'=>'#ff7139','Edge'=>'#0078d7','Safari'=>'#007aff','Opera'=>'#ff1b2d','Autre'=>'#94a3b8']; @endphp
      <div class="st-bar-row">
        @foreach($parBrowser as $browser => $nb)
        @php $pctBr = $totalBr > 0 ? round($nb / $totalBr * 100, 1) : 0; $brColor = $brColors[$browser] ?? '#042C53'; @endphp
        <div class="st-bar-item">
          <div class="st-bar-top">
            <span class="st-bar-name">
              <span class="st-bar-dot" style="background:{{ $brColor }}"></span>
              {{ $browser }}
            </span>
            <span>
              <span class="st-bar-num">{{ number_format($nb, 0, ',', ' ') }}</span>
              <span class="st-bar-pct">{{ $pctBr }}%</span>
            </span>
          </div>
          <div class="st-bar-track">
            <div class="st-bar-fill" style="width:{{ $pctBr }}%;background:{{ $brColor }}"></div>
          </div>
        </div>
        @endforeach
      </div>
      @endif
    </div>
  </div>

</div>

{{-- ══════════════════════════════════════════
     GRAPHIQUES LIGNE 2 — OS + Tendance
══════════════════════════════════════════ --}}
<div class="st-charts-row" style="margin-bottom:16px">

  {{-- OS --}}
  <div class="adm-card">
    <div class="adm-card__head">
      <div class="adm-card__head-title">
        <span class="adm-card__head-title-icon" style="background:#fef3c7">
          <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#d97706" stroke-width="2"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
        </span>
        Systèmes d'exploitation
      </div>
      @if(!$parOs->isEmpty())
      <span class="adm-card__head-badge">{{ $parOs->count() }} OS</span>
      @endif
    </div>
    <div class="adm-card__body">
      @if($parOs->isEmpty())
        <div class="st-empty">
          <div class="st-empty__icon">
            <svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><rect x="2" y="3" width="20" height="14" rx="2"/></svg>
          </div>
          <div class="st-empty__title">Aucune donnée</div>
        </div>
      @else
      @php $totalOs = $parOs->sum(); $osColors = ['Windows'=>'#0078d4','macOS'=>'#555','Android'=>'#3ddc84','iOS'=>'#007aff','Linux'=>'#f5a623','Autre'=>'#94a3b8']; @endphp
      <div class="st-bar-row">
        @foreach($parOs as $os => $nb)
        @php $pctOs = $totalOs > 0 ? round($nb / $totalOs * 100, 1) : 0; $osColor = $osColors[$os] ?? '#185FA5'; @endphp
        <div class="st-bar-item">
          <div class="st-bar-top">
            <span class="st-bar-name">
              <span class="st-bar-dot" style="background:{{ $osColor }}"></span>
              {{ $os }}
            </span>
            <span>
              <span class="st-bar-num">{{ number_format($nb, 0, ',', ' ') }}</span>
              <span class="st-bar-pct">{{ $pctOs }}%</span>
            </span>
          </div>
          <div class="st-bar-track">
            <div class="st-bar-fill" style="width:{{ $pctOs }}%;background:{{ $osColor }}"></div>
          </div>
        </div>
        @endforeach
      </div>
      @endif
    </div>
  </div>

  {{-- Tendance 30 jours --}}
  <div class="adm-card">
    <div class="adm-card__head">
      <div class="adm-card__head-title">
        <span class="adm-card__head-title-icon" style="background:#eff6ff">
          <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
        </span>
        Tendance — 30 derniers jours
      </div>
      @php
        $jours = []; $counts = [];
        for ($i = 29; $i >= 0; $i--) {
          $d = now()->subDays($i)->toDateString();
          $jours[]  = now()->subDays($i)->format('d/m');
          $counts[] = $parJour[$d] ?? 0;
        }
        $maxCount = max($counts) ?: 1;
        $totalTrend = array_sum($counts);
      @endphp
      <span class="adm-card__head-badge">{{ number_format($totalTrend, 0, ',', ' ') }} sur 30j</span>
    </div>
    <div class="adm-card__body">
      @if($totalTrend === 0)
        <div class="st-empty">
          <div class="st-empty__icon">
            <svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
          </div>
          <div class="st-empty__title">Aucune visite ces 30 derniers jours</div>
          <div class="st-empty__desc">Le graphique de tendance s'affichera avec les premières visites.</div>
        </div>
      @else
      <canvas id="chartTendance" height="130"></canvas>
      @endif
    </div>
  </div>

</div>

{{-- ══════════════════════════════════════════
     GÉOGRAPHIE — Pays + Villes
══════════════════════════════════════════ --}}
<div class="st-geo-grid">

  {{-- Top pays --}}
  <div class="adm-card">
    <div class="adm-card__head">
      <div class="adm-card__head-title">
        <span class="adm-card__head-title-icon" style="background:#eff6ff">
          <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2z"/></svg>
        </span>
        Top pays
      </div>
      <span class="adm-card__head-badge">{{ $topPays->count() }} pays</span>
    </div>
    @if($topPays->isEmpty())
      <div class="st-empty">
        <div class="st-empty__icon">
          <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2z"/></svg>
        </div>
        <div class="st-empty__title">Géolocalisation indisponible en local</div>
        <div class="st-empty__desc">Les données géographiques apparaissent dès la mise en production. ip-api.com sera interrogé pour chaque IP publique.</div>
      </div>
    @else
    @php $maxPays = $topPays->max('total'); @endphp
    <div class="adm-table-wrap">
      <table class="adm-table">
        <thead>
          <tr>
            <th style="width:36px">#</th>
            <th>Pays</th>
            <th style="text-align:right">Visites</th>
            <th style="text-align:right">%</th>
          </tr>
        </thead>
        <tbody>
          @foreach($topPays as $i => $row)
          @php $pctP = $total > 0 ? round($row->total / $total * 100, 1) : 0; @endphp
          <tr>
            <td><span class="st-rank {{ $i < 3 ? 'st-rank--'.($i+1) : '' }}">{{ $i + 1 }}</span></td>
            <td>
              <div style="font-weight:600;color:#1e293b;font-size:13px">{{ $row->country }}</div>
              <div class="st-inline-bar">
                <div style="height:100%;width:{{ round($row->total/$maxPays*100) }}%;background:linear-gradient(90deg,#185FA5,#378ADD);border-radius:99px;min-width:3px"></div>
              </div>
            </td>
            <td style="text-align:right;font-weight:700;color:#042C53;font-size:13px">{{ number_format($row->total, 0, ',', ' ') }}</td>
            <td style="text-align:right">
              <span class="adm-badge adm-badge--blue" style="font-size:11px">{{ $pctP }}%</span>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @endif
  </div>

  {{-- Top villes --}}
  <div class="adm-card">
    <div class="adm-card__head">
      <div class="adm-card__head-title">
        <span class="adm-card__head-title-icon" style="background:#faf5ff">
          <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#7c3aed" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </span>
        Top villes
      </div>
      <span class="adm-card__head-badge">{{ $topVilles->count() }} villes</span>
    </div>
    @if($topVilles->isEmpty())
      <div class="st-empty">
        <div class="st-empty__icon">
          <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <div class="st-empty__title">Géolocalisation indisponible en local</div>
        <div class="st-empty__desc">Les villes s'afficheront en production avec les vraies adresses IP des visiteurs.</div>
      </div>
    @else
    @php $maxVille = $topVilles->max('total'); @endphp
    <div class="adm-table-wrap">
      <table class="adm-table">
        <thead>
          <tr>
            <th style="width:36px">#</th>
            <th>Ville</th>
            <th style="text-align:right">Visites</th>
          </tr>
        </thead>
        <tbody>
          @foreach($topVilles as $i => $row)
          <tr>
            <td><span class="st-rank {{ $i < 3 ? 'st-rank--'.($i+1) : '' }}">{{ $i + 1 }}</span></td>
            <td>
              <div style="font-weight:600;color:#1e293b;font-size:13px">{{ $row->city }}</div>
              <div style="font-size:11.5px;color:#64748b">{{ $row->country }}</div>
              <div class="st-inline-bar">
                <div style="height:100%;width:{{ round($row->total/$maxVille*100) }}%;background:linear-gradient(90deg,#7c3aed,#a78bfa);border-radius:99px;min-width:3px"></div>
              </div>
            </td>
            <td style="text-align:right;font-weight:700;color:#042C53;font-size:13px">{{ number_format($row->total, 0, ',', ' ') }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @endif
  </div>

</div>

@endsection

@section('scripts')
@if($total_dev > 0)
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
Chart.defaults.font.family = "'Jost', sans-serif";

// ── Donut terminaux ──
new Chart(document.getElementById('chartDevice'), {
  type: 'doughnut',
  data: {
    labels: ['Téléphone', 'Ordinateur', 'Tablette'],
    datasets: [{
      data: [{{ $devices['mobile'] }}, {{ $devices['ordinateur'] }}, {{ $devices['tablette'] }}],
      backgroundColor: ['#16a34a', '#185FA5', '#7c3aed'],
      borderWidth: 4,
      borderColor: '#fff',
      hoverOffset: 8,
      hoverBorderWidth: 4,
    }]
  },
  options: {
    cutout: '72%',
    animation: { animateRotate: true, duration: 800 },
    plugins: {
      legend: { display: false },
      tooltip: {
        callbacks: {
          label: (ctx) => {
            const total = ctx.chart.data.datasets[0].data.reduce((a,b)=>a+b,0);
            const pct = total > 0 ? (ctx.raw/total*100).toFixed(1) : 0;
            return ` ${ctx.label} : ${ctx.raw} (${pct}%)`;
          }
        }
      }
    },
  }
});

@if(array_sum($counts) > 0)
// ── Courbe tendance ──
new Chart(document.getElementById('chartTendance'), {
  type: 'line',
  data: {
    labels: {!! json_encode($jours) !!},
    datasets: [{
      data: {!! json_encode($counts) !!},
      borderColor: '#185FA5',
      backgroundColor: (ctx) => {
        const gradient = ctx.chart.ctx.createLinearGradient(0, 0, 0, 200);
        gradient.addColorStop(0, 'rgba(24,95,165,.18)');
        gradient.addColorStop(1, 'rgba(24,95,165,0)');
        return gradient;
      },
      borderWidth: 2.5,
      pointRadius: 3,
      pointBackgroundColor: '#185FA5',
      pointBorderColor: '#fff',
      pointBorderWidth: 2,
      pointHoverRadius: 6,
      fill: true,
      tension: 0.4,
    }]
  },
  options: {
    plugins: { legend: { display: false }, tooltip: { callbacks: { title: (items) => items[0].label } } },
    scales: {
      x: {
        ticks: { font: { size: 10 }, maxTicksLimit: 10, color: '#94a3b8' },
        grid: { display: false },
        border: { color: '#E2E8F0' }
      },
      y: {
        beginAtZero: true,
        ticks: { font: { size: 10 }, color: '#94a3b8', precision: 0 },
        grid: { color: '#f1f5f9', drawBorder: false },
        border: { display: false }
      },
    }
  }
});
@endif
</script>
@endif
@endsection
