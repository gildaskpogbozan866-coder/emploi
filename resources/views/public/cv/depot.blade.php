@extends('layouts.app')
@section('title', 'Ajouter un CV ou document — Emploi Bouge Bénin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/cv/depot-cv.css') }}">
@endsection

@section('content')

{{-- Sous-nav --}}
<div class="cvt-subnav">
  <div class="cvt-subnav__inner">
    <a href="{{ route('cv.public.theque') }}" class="cvt-subnav__link">Trouver des CV</a>
    <a href="{{ route('cv.public.tarif') }}"  class="cvt-subnav__link">Packs CV</a>
    <a href="{{ route('cv.public.depot') }}"  class="cvt-subnav__link active">Ajouter un CV / Document</a>
  </div>
</div>

{{-- Hero --}}
<section class="depot-hero">
  <div class="depot-hero__inner">
    <span class="depot-hero__badge">Espace candidat</span>
    <h1 class="depot-hero__title">Ajoutez un <em>CV ou document</em></h1>
    <p class="depot-hero__sub">CV, diplôme, attestation, certificat de formation… déposez tout ce qui valorise votre parcours.</p>
  </div>
</section>

<div class="depot-body">

  {{-- Étapes --}}
  <div class="depot-steps">
    <div class="depot-step depot-step--done">
      <div class="depot-step__num">Étape 1</div>
      <div class="depot-step__label">Votre compte</div>
    </div>
    <div class="depot-step depot-step--active">
      <div class="depot-step__num">Étape 2</div>
      <div class="depot-step__label">Votre document</div>
    </div>
    <div class="depot-step">
      <div class="depot-step__num">Étape 3</div>
      <div class="depot-step__label">Confirmation</div>
    </div>
  </div>

  @auth
  <div class="depot-user-badge">
    <div class="depot-user-badge__avatar">{{ mb_strtoupper(mb_substr(auth()->user()->prenom, 0, 1)) }}</div>
    <div>
      <div class="depot-user-badge__name">{{ auth()->user()->prenom }} {{ auth()->user()->nom }}</div>
      <div class="depot-user-badge__sub">Connecté — remplissez les informations ci-dessous</div>
    </div>
  </div>
  @endauth

  @if($errors->any())
  <div class="depot-errors">
    <strong>Veuillez corriger les erreurs suivantes :</strong>
    <ul>@foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach</ul>
  </div>
  @endif

  <div class="depot-card">
    <div class="depot-card__head">
      <span class="depot-card__head-title">CV, diplôme, attestation ou certificat</span>
    </div>
    <div class="depot-card__body">
      <form method="POST" action="{{ route('cv.public.depot.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- Type --}}
        <div class="form-row form-row--1">
          <div>
            <label class="field__label">Type de document <span class="req">*</span></label>
            <select class="field__select" name="type_document_id" required>
              <option value="">-- Choisissez --</option>
              @foreach($typesDocuments as $type)
                <option value="{{ $type->id }}"
                  {{ old('type_document_id') == $type->id ? 'selected' : '' }}>
                  {{ $type->nom }}
                </option>
              @endforeach
            </select>
          </div>
        </div>

        {{-- Intitulé universel --}}
        <div class="form-row form-row--1">
          <div>
            <label class="field__label">Intitulé <span class="req">*</span></label>
            <input class="field__input" type="text" name="nom" required
              value="{{ old('nom') }}"
              placeholder="Ex : Développeur Web Full Stack, Licence en Droit — UAC 2023, Certificat AWS…">
            <p style="font-size:12px;color:#94a3b8;margin:4px 0 0">Pour un CV : indiquez le poste visé. Pour un diplôme ou certificat : indiquez le nom du document.</p>
          </div>
        </div>

        {{-- Détails complémentaires (CV) --}}
        <div class="form-section-label" style="margin-top:20px">
          Détails complémentaires
          <span style="font-size:11px;font-weight:400;color:#94a3b8;margin-left:6px">— utile pour les CV, ignorez si autre document</span>
        </div>

        <div class="form-row form-row--2">
          <div>
            <label class="field__label">Pays</label>
            <select class="field__select" name="pays">
              <option value="">-- Sélectionnez --</option>
              @foreach(['Bénin','Côte d\'Ivoire','Sénégal','Cameroun','Togo','Mali','Burkina Faso','Niger','Guinée','Congo','Madagascar','Autre'] as $p)
                <option value="{{ $p }}" {{ old('pays') === $p ? 'selected' : '' }}>{{ $p }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="field__label">Ville</label>
            <input class="field__input" type="text" name="ville"
              value="{{ old('ville') }}" placeholder="Cotonou, Abidjan, Dakar…">
          </div>
        </div>

        <div class="form-row form-row--1">
          <div>
            <label class="field__label">Compétences principales</label>
            <div class="tag-input-wrap" id="comp-wrap">
              <div class="tag-input-box" id="comp-box">
                <div class="tag-input-tags" id="comp-tags"></div>
                <input type="text" class="tag-input-text" id="comp-text"
                       placeholder="Tapez puis Entrée, ou choisissez…" autocomplete="off">
              </div>
              <ul class="tag-suggestions" id="comp-suggestions"></ul>
              <input type="hidden" name="competences" id="comp-hidden" value="{{ old('competences') }}">
            </div>
            <p style="font-size:12px;color:#94a3b8;margin:4px 0 0">Appuyez sur <kbd>Entrée</kbd> ou <kbd>,</kbd> pour valider. Cliquez × pour retirer.</p>
          </div>
        </div>

        <div class="form-row form-row--1">
          <div>
            <label class="field__label">Expérience professionnelle</label>
            <textarea class="field__textarea" name="experience" rows="4"
              placeholder="Décrivez vos expériences (poste, entreprise, durée, missions…)">{{ old('experience') }}</textarea>
          </div>
        </div>

        <div class="form-row form-row--1">
          <div>
            <label class="field__label">Formation</label>
            <textarea class="field__textarea" name="formation" rows="3"
              placeholder="Diplôme, école, année d'obtention…">{{ old('formation') }}</textarea>
          </div>
        </div>

        <div class="form-row form-row--1">
          <div>
            <label class="field__label">Langues</label>
            <div class="tag-input-wrap" id="lang-wrap">
              <div class="tag-input-box" id="lang-box">
                <div class="tag-input-tags" id="lang-tags"></div>
                <input type="text" class="tag-input-text" id="lang-text"
                       placeholder="Ex : Français (courant)…" autocomplete="off">
              </div>
              <ul class="tag-suggestions" id="lang-suggestions"></ul>
              <input type="hidden" name="langues" id="lang-hidden" value="{{ old('langues') }}">
            </div>
            <p style="font-size:12px;color:#94a3b8;margin:4px 0 0">Vous pouvez préciser le niveau : Français (courant), Anglais (intermédiaire)…</p>
          </div>
        </div>

        {{-- Upload --}}
        <div class="form-section-label" style="margin-top:20px">Votre fichier (CV ou document)</div>

        <div class="form-row form-row--1">
          <div>
            <div class="upload-zone" id="uploadZone">
              <input type="file" id="cvFile" name="fichier_path" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.webp">
              <div class="upload-zone__icon">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
              </div>
              <div class="upload-zone__title">Glissez votre fichier ici ou <span>cliquez pour charger</span></div>
              <div class="upload-zone__hint">PDF, DOC, DOCX, JPG, PNG ou WebP — max 5 Mo</div>
            </div>

            <div class="file-preview" id="filePreview">
              <div class="file-preview__icon">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
              </div>
              <div>
                <div class="file-preview__name" id="previewName">—</div>
                <div class="file-preview__meta" id="previewMeta">—</div>
              </div>
              <button type="button" class="file-preview__remove" id="removeFile">✕</button>
            </div>

            <a href="{{ route('service.list') }}" class="cv-promo-banner">
              <div class="cv-promo-banner__left">
                <div class="cv-promo-banner__icon">
                  <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#042C53" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                  </svg>
                </div>
                <div>
                  <div class="cv-promo-banner__title">Votre CV vous coûte des opportunités.</div>
                  <div class="cv-promo-banner__sub">Un recruteur décide en 7 secondes. Nos experts rédigent un CV qui passe tous les filtres — livré sous 48h.</div>
                </div>
              </div>
              <span class="cv-promo-banner__cta">
                Transformer mon CV
                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
              </span>
            </a>
          </div>
        </div>

        <button type="submit" class="depot-submit-btn">
          <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
          </svg>
          Enregistrer
        </button>

      </form>
    </div>
  </div>

</div>

<style>
.cvt-subnav { background:#003d5c; border-bottom:1px solid rgba(255,255,255,.08); }
.cvt-subnav__inner { max-width:1180px; margin:0 auto; padding:0 24px; display:flex; align-items:center; height:42px; }
.cvt-subnav__link { font-family:var(--font-body); font-size:13px; font-weight:500; color:#F5C842; text-decoration:none; padding:0 20px; height:100%; display:flex; align-items:center; transition:color .2s,background .2s; border-right:1px solid rgba(255,255,255,.15); }
.cvt-subnav__link:first-child { border-left:1px solid rgba(255,255,255,.15); }
.cvt-subnav__link:hover { color:#fff; background:rgba(255,255,255,.06); }
.cvt-subnav__link.active { color:#fff; background:rgba(245,200,66,.12); }

/* ── Tag input ────────────────────────────────── */
.tag-input-wrap { position: relative; }
.tag-input-box {
  display: flex; flex-wrap: wrap; gap: 6px; align-items: center;
  min-height: 76px; padding: 8px 10px;
  border: 1.5px solid #e2e8f0; border-radius: 8px;
  background: #fff; cursor: text; transition: border-color .2s, box-shadow .2s;
}
.tag-input-box:focus-within { border-color: #185FA5; box-shadow: 0 0 0 3px rgba(24,95,165,.08); }
.tag-input-tags { display: contents; }
.tag-chip {
  display: inline-flex; align-items: center; gap: 4px;
  padding: 3px 8px 3px 10px; border-radius: 20px;
  background: #EFF6FF; color: #1D4ED8; font-size: 12px; font-weight: 600;
  white-space: nowrap; max-width: 200px; overflow: hidden; text-overflow: ellipsis;
}
.tag-chip__remove {
  background: none; border: none; padding: 0 2px; cursor: pointer;
  color: #93C5FD; font-size: 16px; line-height: 1; transition: color .15s;
  flex-shrink: 0;
}
.tag-chip__remove:hover { color: #1D4ED8; }
.tag-input-text {
  flex: 1; min-width: 140px; border: none; outline: none;
  font-size: 13px; color: #374151; padding: 2px 0; background: transparent;
  font-family: inherit;
}
.tag-input-text::placeholder { color: #94a3b8; }
.tag-suggestions {
  position: absolute; left: 0; right: 0; top: calc(100% + 3px);
  background: #fff; border: 1.5px solid #e2e8f0; border-radius: 8px;
  box-shadow: 0 4px 16px rgba(0,0,0,.1);
  list-style: none; margin: 0; padding: 4px 0; z-index: 100;
  max-height: 180px; overflow-y: auto; display: none;
}
.tag-suggestions li {
  padding: 8px 14px; font-size: 13px; color: #374151; cursor: pointer;
  transition: background .1s;
}
.tag-suggestions li:hover, .tag-suggestions li.ts-active { background: #EFF6FF; color: #185FA5; }
.tag-suggestions.ts-open { display: block; }
kbd { font-size: 11px; background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 3px; padding: 1px 5px; }
</style>

@endsection

@section('scripts')
<script>
/* ── Upload preview ──────────────────────────── */
(function () {
  const input   = document.getElementById('cvFile');
  const zone    = document.getElementById('uploadZone');
  const preview = document.getElementById('filePreview');
  const name    = document.getElementById('previewName');
  const meta    = document.getElementById('previewMeta');
  const remove  = document.getElementById('removeFile');

  function showFile(file) {
    if (!file) return;
    zone.classList.add('has-file');
    preview.classList.add('visible');
    name.textContent = file.name;
    meta.textContent = (file.size / 1024 / 1024).toFixed(2) + ' Mo · ' + file.name.split('.').pop().toUpperCase();
  }

  input.addEventListener('change', () => showFile(input.files[0]));
  zone.addEventListener('dragover',  e => { e.preventDefault(); zone.classList.add('has-file'); });
  zone.addEventListener('dragleave', () => { if (!input.files[0]) zone.classList.remove('has-file'); });
  zone.addEventListener('drop', e => {
    e.preventDefault();
    if (e.dataTransfer.files[0]) { input.files = e.dataTransfer.files; showFile(e.dataTransfer.files[0]); }
  });
  remove.addEventListener('click', () => {
    input.value = '';
    zone.classList.remove('has-file');
    preview.classList.remove('visible');
    name.textContent = meta.textContent = '—';
  });
})();

/* ── Tag input factory ───────────────────────── */
function makeTagInput(wrapId, hiddenId, allSuggestions) {
  const wrap    = document.getElementById(wrapId);
  const box     = wrap.querySelector('.tag-input-box');
  const tagsEl  = wrap.querySelector('.tag-input-tags');
  const textEl  = wrap.querySelector('.tag-input-text');
  const sugEl   = wrap.querySelector('.tag-suggestions');
  const hidden  = document.getElementById(hiddenId);

  let tags = hidden.value ? hidden.value.split(',').map(s => s.trim()).filter(Boolean) : [];
  let activeIdx = -1;

  function sync() { hidden.value = tags.join(', '); }

  function renderTags() {
    tagsEl.innerHTML = tags.map((t, i) =>
      `<span class="tag-chip">${t}<button type="button" class="tag-chip__remove" data-i="${i}" tabindex="-1">×</button></span>`
    ).join('');
    sync();
  }

  function addTag(val) {
    val = val.trim();
    if (!val || tags.includes(val)) { textEl.value = ''; return; }
    tags.push(val);
    renderTags();
    textEl.value = '';
    hideSug();
  }

  function showSug(q) {
    q = q.toLowerCase().trim();
    if (!q) { hideSug(); return; }
    const hits = allSuggestions.filter(s => s.toLowerCase().includes(q) && !tags.includes(s)).slice(0, 8);
    if (!hits.length) { hideSug(); return; }
    sugEl.innerHTML = hits.map(s => `<li data-val="${s.replace(/"/g,'&quot;')}">${s}</li>`).join('');
    sugEl.classList.add('ts-open');
    activeIdx = -1;
  }

  function hideSug() { sugEl.classList.remove('ts-open'); activeIdx = -1; }

  function moveActive(dir) {
    const items = sugEl.querySelectorAll('li');
    if (!items.length) return;
    activeIdx = Math.max(0, Math.min(items.length - 1, activeIdx + dir));
    items.forEach((li, i) => li.classList.toggle('ts-active', i === activeIdx));
  }

  tagsEl.addEventListener('click', e => {
    const btn = e.target.closest('.tag-chip__remove');
    if (btn) { tags.splice(+btn.dataset.i, 1); renderTags(); }
  });

  textEl.addEventListener('input', () => showSug(textEl.value));

  textEl.addEventListener('keydown', e => {
    if (e.key === 'ArrowDown') { e.preventDefault(); moveActive(1); return; }
    if (e.key === 'ArrowUp')   { e.preventDefault(); moveActive(-1); return; }
    if (e.key === 'Escape')    { hideSug(); return; }

    if (e.key === 'Enter' || e.key === ',') {
      e.preventDefault();
      const active = sugEl.querySelector('li.ts-active');
      addTag(active ? active.dataset.val : textEl.value);
      return;
    }
    if (e.key === 'Backspace' && !textEl.value && tags.length) {
      tags.pop(); renderTags();
    }
  });

  sugEl.addEventListener('mousedown', e => {
    const li = e.target.closest('li');
    if (li) { e.preventDefault(); addTag(li.dataset.val); }
  });

  box.addEventListener('click', () => textEl.focus());
  document.addEventListener('click', e => { if (!wrap.contains(e.target)) hideSug(); });

  renderTags();
}

makeTagInput('comp-wrap', 'comp-hidden', @json($competences));
makeTagInput('lang-wrap', 'lang-hidden', @json($langues));
</script>
@endsection
