@extends('layouts.app')
@section('title', 'FAQ : Questions fréquentes | Emploi Bouge Bénin')
@section('description', 'Toutes les réponses à vos questions sur Emploi Bouge Bénin : postuler, déposer un CV, publier une offre, services CV professionnel, paiements et plus encore.')
@section('canonical', route('faq'))

@section('css')
<style>
/* ═══════════════════════════════════
   FAQ HERO
═══════════════════════════════════ */
.faq-hero {
  background: linear-gradient(135deg, #042C53 0%, #0d3d70 50%, #185FA5 100%);
  padding: 64px 20px 80px;
  text-align: center;
  position: relative;
  overflow: hidden;
}
.faq-hero::before {
  content: '';
  position: absolute;
  inset: 0;
  background: radial-gradient(circle at 70% 40%, rgba(245,200,66,.08) 0%, transparent 60%),
              radial-gradient(circle at 20% 80%, rgba(24,95,165,.3) 0%, transparent 50%);
}
.faq-hero__inner { position: relative; max-width: 680px; margin: 0 auto; }
.faq-hero__badge {
  display: inline-flex; align-items: center; gap: 7px;
  background: rgba(245,200,66,.18); color: #F5C842;
  font-size: 11px; font-weight: 800; letter-spacing: .1em; text-transform: uppercase;
  padding: 5px 14px; border-radius: 20px; border: 1px solid rgba(245,200,66,.3);
  margin-bottom: 20px;
}
.faq-hero__title {
  font-size: clamp(28px, 5vw, 46px);
  font-weight: 800; color: #fff;
  line-height: 1.15; margin: 0 0 16px;
}
.faq-hero__sub {
  font-size: 16px; color: rgba(255,255,255,.72);
  line-height: 1.7; margin: 0 0 36px;
}
.faq-hero__search {
  display: flex; align-items: center;
  background: #fff; border-radius: 14px;
  padding: 6px 6px 6px 18px;
  box-shadow: 0 8px 32px rgba(0,0,0,.2);
  gap: 10px;
}
.faq-hero__search-icon { color: #94a3b8; flex-shrink: 0; }
.faq-hero__search input {
  flex: 1; border: none; outline: none;
  font-size: 15px; color: #042C53;
  background: transparent;
  font-family: var(--font-body);
}
.faq-hero__search input::placeholder { color: #94a3b8; }
.faq-hero__search-btn {
  background: linear-gradient(135deg,#042C53,#185FA5);
  color: #F5C842; border: none; cursor: pointer;
  font-size: 13px; font-weight: 700;
  padding: 11px 22px; border-radius: 10px;
  font-family: var(--font-body);
  white-space: nowrap;
}

/* ═══════════════════════════════════
   STATS BAND
═══════════════════════════════════ */
.faq-stats {
  background: #fff;
  border-bottom: 1px solid #e8edf3;
  padding: 20px 0;
}
.faq-stats__inner {
  max-width: 900px; margin: 0 auto; padding: 0 20px;
  display: flex; justify-content: center; gap: 48px; flex-wrap: wrap;
}
.faq-stat { text-align: center; }
.faq-stat__num { font-size: 22px; font-weight: 800; color: #042C53; }
.faq-stat__label { font-size: 12px; color: #64748b; font-weight: 500; }

/* ═══════════════════════════════════
   TABS
═══════════════════════════════════ */
.faq-tabs {
  display: flex; gap: 8px; flex-wrap: wrap;
  margin-bottom: 36px;
}
.faq-tab {
  display: inline-flex; align-items: center; gap: 7px;
  padding: 9px 18px; border-radius: 24px;
  font-size: 13px; font-weight: 600; cursor: pointer;
  border: 1.5px solid #e2e8f0;
  background: #fff; color: #475569;
  transition: all .2s; user-select: none;
}
.faq-tab:hover { border-color: #185FA5; color: #185FA5; }
.faq-tab.active {
  background: linear-gradient(135deg,#042C53,#185FA5);
  color: #fff; border-color: transparent;
  box-shadow: 0 4px 14px rgba(4,44,83,.22);
}
.faq-tab__count {
  background: rgba(255,255,255,.22);
  font-size: 11px; font-weight: 700;
  padding: 1px 7px; border-radius: 20px;
}
.faq-tab:not(.active) .faq-tab__count {
  background: #f1f5f9; color: #64748b;
}

/* ═══════════════════════════════════
   CATEGORY SECTION
═══════════════════════════════════ */
.faq-category { display: none; }
.faq-category.active { display: block; animation: fadeSlide .25s ease; }
@keyframes fadeSlide {
  from { opacity: 0; transform: translateY(6px); }
  to   { opacity: 1; transform: translateY(0); }
}

.faq-category__header {
  display: flex; align-items: center; gap: 14px;
  margin-bottom: 20px;
}
.faq-category__icon {
  width: 44px; height: 44px; border-radius: 12px;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0;
}
.faq-category__name {
  font-size: 18px; font-weight: 800; color: #042C53;
}
.faq-category__count {
  font-size: 12px; color: #64748b; font-weight: 500; margin-top: 1px;
}

/* ═══════════════════════════════════
   ACCORDION
═══════════════════════════════════ */
.faq-list { display: flex; flex-direction: column; gap: 10px; }

.faq-item {
  background: #fff;
  border: 1.5px solid #e8edf3;
  border-radius: 14px;
  overflow: hidden;
  transition: border-color .2s, box-shadow .2s;
}
.faq-item:hover { border-color: #bfdbfe; box-shadow: 0 4px 16px rgba(4,44,83,.07); }
.faq-item.open { border-color: #185FA5; box-shadow: 0 4px 20px rgba(4,44,83,.1); }

.faq-item__q {
  width: 100%; padding: 18px 20px;
  display: flex; align-items: center; gap: 14px;
  background: none; border: none; cursor: pointer;
  text-align: left; font-family: var(--font-body);
}
.faq-item__num {
  width: 28px; height: 28px; border-radius: 8px;
  background: #f0f7ff; color: #185FA5;
  font-size: 12px; font-weight: 800;
  display: flex; align-items: center; justify-content: center;
  flex-shrink: 0; transition: background .2s, color .2s;
}
.faq-item.open .faq-item__num { background: #185FA5; color: #fff; }

.faq-item__text {
  flex: 1; font-size: 14.5px; font-weight: 700;
  color: #042C53; line-height: 1.4;
}

.faq-item__chevron {
  flex-shrink: 0; width: 20px; height: 20px;
  border-radius: 50%; background: #f1f5f9;
  display: flex; align-items: center; justify-content: center;
  transition: transform .25s, background .2s;
}
.faq-item.open .faq-item__chevron {
  transform: rotate(180deg);
  background: #EBF4FD;
}

.faq-item__body {
  max-height: 0; overflow: hidden;
  transition: max-height .35s ease, padding .3s;
}
.faq-item.open .faq-item__body { max-height: 600px; }

.faq-item__answer {
  padding: 0 20px 20px 62px;
  font-size: 14px; color: #475569; line-height: 1.8;
  border-top: 1px solid #f1f5f9;
}
.faq-item__answer ul { padding-left: 18px; margin: 8px 0; }
.faq-item__answer li { margin-bottom: 6px; }
.faq-item__answer strong { color: #042C53; }
.faq-item__answer a { color: #185FA5; font-weight: 600; text-decoration: none; }
.faq-item__answer a:hover { text-decoration: underline; }

/* ── Pas de résultat search ── */
.faq-no-result {
  text-align: center; padding: 60px 20px; color: #94a3b8;
  display: none;
}
.faq-no-result svg { margin: 0 auto 14px; display: block; opacity: .4; }

/* ═══════════════════════════════════
   CTA BOTTOM
═══════════════════════════════════ */
.faq-cta {
  background: linear-gradient(135deg,#042C53 0%,#185FA5 100%);
  border-radius: 20px; padding: 40px 32px;
  display: flex; align-items: center; justify-content: space-between;
  gap: 24px; flex-wrap: wrap;
  margin-top: 48px;
  position: relative; overflow: hidden;
}
.faq-cta::before {
  content: '?';
  position: absolute; right: 32px; top: -10px;
  font-size: 140px; font-weight: 900; color: rgba(255,255,255,.04);
  line-height: 1; pointer-events: none;
}
.faq-cta__title { font-size: 19px; font-weight: 800; color: #fff; margin: 0 0 6px; }
.faq-cta__sub { font-size: 14px; color: rgba(255,255,255,.7); margin: 0; }
.faq-cta__btn {
  display: inline-flex; align-items: center; gap: 8px;
  background: #F5C842; color: #042C53;
  font-size: 14px; font-weight: 800;
  padding: 13px 28px; border-radius: 12px;
  text-decoration: none; white-space: nowrap;
  box-shadow: 0 4px 18px rgba(245,200,66,.35);
  transition: transform .18s, box-shadow .18s; flex-shrink: 0;
}
.faq-cta__btn:hover { transform: translateY(-2px); box-shadow: 0 8px 28px rgba(245,200,66,.45); }

/* ═══════════════════════════════════
   RESPONSIVE
═══════════════════════════════════ */
@media (max-width: 640px) {
  .faq-hero { padding: 44px 16px 60px; }
  .faq-hero__search { flex-wrap: wrap; }
  .faq-hero__search-btn { width: 100%; justify-content: center; }
  .faq-stats__inner { gap: 28px; }
  .faq-item__answer { padding-left: 20px; }
  .faq-cta { padding: 28px 20px; }
  .faq-cta__title { font-size: 16px; }
}
</style>
@endsection

@section('content')

{{-- ══════════════════════ HERO ══════════════════════ --}}
<section class="faq-hero">
  <div class="faq-hero__inner">
    <div class="faq-hero__badge">
      <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M9.09 9a3 3 0 015.83 1c0 2-3 3-3 3M12 17h.01"/></svg>
      Centre d'aide
    </div>
    <h1 class="faq-hero__title">Questions fréquentes</h1>
    <p class="faq-hero__sub">Toutes les réponses sur Emploi Bouge Bénin : candidatures, recrutement, services et paiements.</p>

    <div class="faq-hero__search">
      <svg class="faq-hero__search-icon" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="m21 21-4.35-4.35"/></svg>
      <input type="text" id="faqSearch" placeholder="Rechercher une question…" autocomplete="off">
      <button class="faq-hero__search-btn" onclick="runSearch(document.getElementById('faqSearch').value)">Rechercher</button>
    </div>
  </div>
</section>

{{-- ══════════════════════ STATS ══════════════════════ --}}
<div class="faq-stats">
  <div class="faq-stats__inner">
    @php $total = $faqs->flatten()->count(); @endphp
    <div class="faq-stat">
      <div class="faq-stat__num">{{ $total }}</div>
      <div class="faq-stat__label">Réponses disponibles</div>
    </div>
    <div class="faq-stat">
      <div class="faq-stat__num">{{ $faqs->count() }}</div>
      <div class="faq-stat__label">Catégories</div>
    </div>
    <div class="faq-stat">
      <div class="faq-stat__num">1h</div>
      <div class="faq-stat__label">Temps de réponse support</div>
    </div>
  </div>
</div>

{{-- ══════════════════════ CONTENU ══════════════════════ --}}
<section class="section" style="background:#f8fafc">
  <div class="container" style="max-width:860px">

    @php
      $icons = [
        'Candidats'  => ['color'=>'#EBF4FD','stroke'=>'#185FA5','path'=>'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
        'Recruteurs' => ['color'=>'#fef3c7','stroke'=>'#d97706','path'=>'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
        'Services'   => ['color'=>'#f0fdf4','stroke'=>'#16a34a','path'=>'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z'],
        'Paiements'  => ['color'=>'#fdf2f8','stroke'=>'#9333ea','path'=>'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
        'Général'    => ['color'=>'#f0f7ff','stroke'=>'#042C53','path'=>'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
      ];
      $categories = $faqs->keys();
      $firstCat   = $categories->first();
    @endphp

    {{-- Onglets --}}
    <div class="faq-tabs" id="faqTabs">
      @foreach($categories as $cat)
      @php $icon = $icons[$cat] ?? $icons['Général']; @endphp
      <button class="faq-tab {{ $cat === $firstCat ? 'active' : '' }}"
              data-cat="{{ $cat }}" onclick="switchTab('{{ $cat }}')">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="{{ $cat === $firstCat ? '#fff' : $icon['stroke'] }}" stroke-width="2" class="tab-icon" data-cat="{{ $cat }}">
          <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon['path'] }}"/>
        </svg>
        {{ $cat }}
        <span class="faq-tab__count">{{ $faqs[$cat]->count() }}</span>
      </button>
      @endforeach
    </div>

    {{-- Zone de recherche (tous les items cachés par défaut) --}}
    <div id="faqSearchResults" style="display:none; scroll-margin-top: 100px;">
      <div id="faqSearchHeader" style="display:flex;align-items:center;gap:10px;margin-bottom:20px;padding:14px 18px;background:#EBF4FD;border-radius:12px;border:1px solid #bfdbfe">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="2.5"><circle cx="11" cy="11" r="8"/><path stroke-linecap="round" d="m21 21-4.35-4.35"/></svg>
        <span id="faqSearchLabel" style="font-size:14px;font-weight:700;color:#042C53"></span>
        <button onclick="clearSearch()" style="margin-left:auto;background:none;border:none;cursor:pointer;color:#64748b;font-size:13px;font-weight:600;display:flex;align-items:center;gap:5px">
          <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M18 6 6 18M6 6l12 12"/></svg>
          Effacer
        </button>
      </div>
      <div class="faq-list" id="faqSearchList"></div>
      <div class="faq-no-result" id="faqNoResult">
        <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        <p style="font-size:15px;font-weight:600;color:#64748b;margin:0">Aucune question trouvée</p>
        <p style="font-size:13px;color:#94a3b8;margin:6px 0 0">Essayez avec un autre mot-clé ou consultez nos catégories.</p>
      </div>
    </div>

    {{-- Catégories --}}
    @foreach($faqs as $categorie => $questions)
    @php $icon = $icons[$categorie] ?? $icons['Général']; @endphp
    <div class="faq-category {{ $categorie === $firstCat ? 'active' : '' }}" id="cat-{{ Str::slug($categorie) }}">

      <div class="faq-category__header">
        <div class="faq-category__icon" style="background:{{ $icon['color'] }}">
          <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="{{ $icon['stroke'] }}" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon['path'] }}"/>
          </svg>
        </div>
        <div>
          <div class="faq-category__name">{{ $categorie }}</div>
          <div class="faq-category__count">{{ $questions->count() }} question{{ $questions->count() > 1 ? 's' : '' }}</div>
        </div>
      </div>

      <div class="faq-list">
        @foreach($questions as $i => $faq)
        <div class="faq-item" data-question="{{ strtolower(strip_tags($faq->question)) }}" data-answer="{{ strtolower(strip_tags($faq->reponse)) }}">
          <button class="faq-item__q" onclick="toggleFaq(this)">
            <span class="faq-item__num">{{ $i + 1 }}</span>
            <span class="faq-item__text">{{ $faq->question }}</span>
            <span class="faq-item__chevron">
              <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
            </span>
          </button>
          <div class="faq-item__body">
            <div class="faq-item__answer">{!! $faq->reponse !!}</div>
          </div>
        </div>
        @endforeach
      </div>

    </div>
    @endforeach

    {{-- CTA --}}
    <div class="faq-cta">
      <div>
        <p class="faq-cta__title">Vous n'avez pas trouvé votre réponse ?</p>
        <p class="faq-cta__sub">Notre équipe répond en moins d'1 heure en semaine. WhatsApp ou email.</p>
      </div>
      <a href="{{ route('contact') }}" class="faq-cta__btn">
        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
        Nous contacter
      </a>
    </div>

  </div>
</section>

@endsection

@section('scripts')
<script>
// ── Accordion ──────────────────────────────
function toggleFaq(btn) {
  var item = btn.closest('.faq-item');
  var wasOpen = item.classList.contains('open');
  // fermer tous les items de la même liste
  item.closest('.faq-list').querySelectorAll('.faq-item').forEach(function(el) {
    el.classList.remove('open');
  });
  if (!wasOpen) item.classList.add('open');
}

// ── Tabs ───────────────────────────────────
function switchTab(cat) {
  // Désactiver search
  document.getElementById('faqSearch').value = '';
  document.getElementById('faqSearchResults').style.display = 'none';

  // Tabs
  document.querySelectorAll('.faq-tab').forEach(function(t) {
    var isActive = t.getAttribute('data-cat') === cat;
    t.classList.toggle('active', isActive);
    var icon = t.querySelector('.tab-icon');
    if (icon) icon.setAttribute('stroke', isActive ? '#fff' : icon.getAttribute('data-default-stroke') || '#185FA5');
  });

  // Catégories
  document.querySelectorAll('.faq-category').forEach(function(el) {
    el.classList.remove('active');
  });
  var target = document.getElementById('cat-' + cat.toLowerCase().replace(/\s+/g,'-').replace(/[éèê]/g,'e').replace(/[àâ]/g,'a').replace(/[ûü]/g,'u').replace(/[îï]/g,'i').replace(/[ôö]/g,'o').replace(/[^a-z0-9-]/g,''));
  if (target) target.classList.add('active');
}

// ── Recherche ──────────────────────────────
var allFaqItems = null;

function clearSearch() {
  document.getElementById('faqSearch').value = '';
  document.getElementById('faqSearchResults').style.display = 'none';
  var activeTab = document.querySelector('.faq-tab.active');
  if (activeTab) switchTab(activeTab.getAttribute('data-cat'));
}

function runSearch(q) {
  q = (q || '').trim().toLowerCase();

  if (!q) {
    clearSearch();
    return;
  }

  // Cacher les catégories
  document.querySelectorAll('.faq-category').forEach(function(el) { el.classList.remove('active'); });
  document.getElementById('faqSearchResults').style.display = 'block';

  // Cloner et filtrer
  if (!allFaqItems) {
    allFaqItems = Array.from(document.querySelectorAll('.faq-category .faq-item'));
  }

  var list = document.getElementById('faqSearchList');
  list.innerHTML = '';
  var found = 0;

  allFaqItems.forEach(function(item) {
    var inQ = item.getAttribute('data-question') || '';
    var inA = item.getAttribute('data-answer') || '';
    if (inQ.indexOf(q) > -1 || inA.indexOf(q) > -1) {
      var clone = item.cloneNode(true);
      clone.classList.remove('open');
      clone.querySelector('.faq-item__q').setAttribute('onclick','toggleFaq(this)');
      list.appendChild(clone);
      found++;
    }
  });

  document.getElementById('faqNoResult').style.display = found === 0 ? 'block' : 'none';
  list.style.display = found === 0 ? 'none' : '';

  // Mettre à jour le label
  var label = document.getElementById('faqSearchLabel');
  if (label) label.textContent = found + ' résultat' + (found > 1 ? 's' : '') + ' pour "' + document.getElementById('faqSearch').value.trim() + '"';

  // Scroll vers les résultats
  document.getElementById('faqSearchResults').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

document.getElementById('faqSearch').addEventListener('input', function() {
  runSearch(this.value);
});
</script>
@endsection
