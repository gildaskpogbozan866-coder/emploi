@extends('layouts.app')
@section('title', 'Ajouter un CV ou document | Emploi Bouge Bénin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/cv/cvtheque.css') }}">
<link rel="stylesheet" href="{{ asset('css/cv/depot-cv.css') }}">
<link rel="stylesheet" href="{{ asset('css/searchable-select.css') }}">
@endsection

@section('content')

{{-- Sous-nav --}}
<div class="cvt-subnav">
  <div class="cvt-subnav__inner">
    <a href="{{ route('cv.public.theque') }}" class="cvt-subnav__link">Trouver des CV</a>
    <a href="{{ route('cv.public.tarif') }}"  class="cvt-subnav__link">Packs CV</a>
    <a href="{{ auth()->check() && auth()->user()->hasRole('candidat') ? route('candidat.profil') : route('auth.inscription').'?role=candidat' }}"  class="cvt-subnav__link active">Ajouter un CV / Document</a>
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
      <div class="depot-user-badge__sub">Connecté, remplissez les informations ci-dessous</div>
    </div>
  </div>
  @endauth

  {{-- Quota badge --}}
  @auth
  @if(isset($quota) && !$quota['unlimited'])
  <div style="display:flex;align-items:center;gap:10px;background:{{ $quota['remaining'] <= 1 ? '#fffbeb' : '#f0f9ff' }};border:1.5px solid {{ $quota['remaining'] <= 1 ? '#fde68a' : '#bae6fd' }};border-radius:10px;padding:11px 16px;margin-bottom:18px">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="{{ $quota['remaining'] <= 1 ? '#d97706' : '#0284c7' }}" stroke-width="2" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
    <p style="margin:0;font-size:13px;color:{{ $quota['remaining'] <= 1 ? '#92400e' : '#0c4a6e' }};flex:1">
      <strong>{{ $quota['used'] }}/{{ $quota['limit'] }} documents</strong> utilisés sur votre plan
      @if($quota['remaining'] === 0)
        quota atteint
      @else
        encore <strong>{{ $quota['remaining'] }} slot{{ $quota['remaining'] > 1 ? 's' : '' }}</strong> disponible{{ $quota['remaining'] > 1 ? 's' : '' }}
      @endif
    </p>
    @if($quota['remaining'] <= 1)
      <a href="{{ route('candidat.abonnement.plans') }}" style="font-size:12.5px;font-weight:700;color:#92400e;white-space:nowrap">Voir les plans →</a>
    @endif
  </div>
  @endif
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
            <label class="field__label" for="depot-type">Type de document <span class="req">*</span></label>
            <select class="field__select @error('type_document_id') field--invalid @enderror" id="depot-type" name="type_document_id" required>
              <option value="">-- Choisissez --</option>
              @foreach($typesDocuments as $type)
                <option value="{{ $type->id }}"
                  {{ old('type_document_id', $typeCVId) == $type->id ? 'selected' : '' }}>
                  {{ $type->nom }}
                </option>
              @endforeach
            </select>
            @error('type_document_id')<p class="field__server-error">{{ $message }}</p>@enderror
            <p style="font-size:12px;color:#185FA5;margin:5px 0 0;font-weight:500">
              <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="display:inline-block;vertical-align:-1px"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
              Seuls les <strong>Curriculum Vitae</strong> apparaissent dans la CVthèque publique.
            </p>
          </div>
        </div>

        {{-- Intitulé universel --}}
        <div class="form-row form-row--1">
          <div>
            <label class="field__label" for="depot-nom">Intitulé <span class="req">*</span></label>
            <input class="field__input @error('nom') field--invalid @enderror" type="text" id="depot-nom" name="nom" required
              value="{{ old('nom') }}"
              placeholder="Ex : Comptable, Licence en Gestion UAC 2022, Attestation stage SONEB, BTS Commerce ENEAM…">
            @error('nom')<p class="field__server-error">{{ $message }}</p>@enderror
            <p style="font-size:12px;color:#94a3b8;margin:4px 0 0">Pour un CV : indiquez le poste visé. Pour un diplôme ou certificat : indiquez le nom du document.</p>
          </div>
        </div>

        {{-- Détails complémentaires (CV) --}}
        {{-- <div class="form-section-label" style="margin-top:20px">
          Détails complémentaires
          <span style="font-size:11px;font-weight:400;color:#94a3b8;margin-left:6px">utile pour les CV, ignorez si autre document</span>
        </div> --}}

        {{-- Photo de profil --}}
        {{-- <div style="display:flex;align-items:center;gap:20px;margin-bottom:24px;padding:18px 20px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:12px">
          <div id="photoPreviewWrap" style="position:relative;flex-shrink:0">
            <div id="photoCircle" style="width:72px;height:72px;border-radius:50%;background:linear-gradient(135deg,#042C53,#185FA5);display:flex;align-items:center;justify-content:center;overflow:hidden;border:2.5px solid #e2e8f0">
              <span id="photoInitials" style="color:#fff;font-size:1.4rem;font-weight:800">{{ mb_strtoupper(mb_substr(auth()->user()->prenom ?? '?', 0, 1)) }}</span>
              <img id="photoPreviewImg" src="" alt="" style="display:none;width:100%;height:100%;object-fit:cover">
            </div>
            <label for="photoInput" style="position:absolute;bottom:0;right:0;width:22px;height:22px;border-radius:50%;background:#185FA5;border:2px solid #fff;display:flex;align-items:center;justify-content:center;cursor:pointer">
              <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="#fff" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><circle cx="12" cy="13" r="3"/></svg>
            </label>
          </div>
          <div>
            <p style="font-size:13.5px;font-weight:700;color:#042C53;margin:0 0 3px">Photo de profil</p>
            <p style="font-size:12.5px;color:#64748b;margin:0 0 8px">JPG, PNG ou WebP · max 2 Mo</p>
            <label for="photoInput" style="font-size:12.5px;color:#185FA5;font-weight:600;cursor:pointer;text-decoration:underline">Choisir une photo</label>
            <span id="photoFileName" style="font-size:12px;color:#94a3b8;margin-left:8px"></span>
          </div>
          <input type="file" id="photoInput" name="photo" accept=".jpg,.jpeg,.png,.webp" style="display:none">
        </div> --}}

        {{-- <div class="form-row form-row--2">
          <div>
            <label class="field__label">Pays</label>
            <select class="field__select" name="pays" data-searchable>
              <option value="">-- Sélectionnez --</option>
              @foreach($paysList as $p)
                <option value="{{ $p }}" {{ old('pays') === $p ? 'selected' : '' }}>{{ $p }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="field__label">Ville</label>
            @include('_partials.villes-select', ['selected' => old('ville')])
          </div>
        </div>

        <div class="form-row form-row--2">
          <div>
            <label class="field__label">Secteur d'activité</label>
            <select class="field__select" name="secteur">
              <option value="">-- Sélectionnez --</option>
              @foreach($secteursList as $s)
                <option value="{{ $s->libelle }}" {{ old('secteur') === $s->libelle ? 'selected' : '' }}>{{ $s->libelle }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="field__label">Métier / Poste recherché</label>
            <select class="field__select" name="metier">
              <option value="">-- Sélectionnez --</option>
              @foreach($metiersList as $m)
                <option value="{{ $m->nom }}" {{ old('metier') === $m->nom ? 'selected' : '' }}>{{ $m->nom }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="form-row form-row--2">
          <div>
            <label class="field__label">Niveau d'expérience</label>
            <select class="field__select" name="niveau_experience">
              <option value="">-- Sélectionnez --</option>
              @foreach($niveauxExpList as $ne)
                <option value="{{ $ne->code }}" {{ old('niveau_experience') === $ne->code ? 'selected' : '' }}>{{ $ne->libelle }}</option>
              @endforeach
            </select>
          </div>
          <div>
            <label class="field__label">Niveau d'études</label>
            <select class="field__select" name="niveau_etude">
              <option value="">-- Sélectionnez --</option>
              @foreach($niveauxEtudeList as $ne)
                <option value="{{ $ne->code }}" {{ old('niveau_etude') === $ne->code ? 'selected' : '' }}>{{ $ne->libelle }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="form-row form-row--2">
          <div>
            <label class="field__label">Type de contrat recherché</label>
            <select class="field__select" name="type_contrat">
              <option value="">-- Sélectionnez --</option>
              @foreach($typeContratsList as $tc)
                <option value="{{ $tc->code }}" {{ old('type_contrat') === $tc->code ? 'selected' : '' }}>{{ $tc->libelle }}</option>
              @endforeach
            </select>
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
            <div id="langues-builder">
              @php $oldLangues = old('langues_ids', []); $oldNiveaux = old('niveaux_ids', []); @endphp
              @if(count($oldLangues))
                @foreach($oldLangues as $li => $lid)
                <div class="langue-row">
                  <select name="langues_ids[]" class="field__select langue-row__select" required>
                    <option value="">-- Langue --</option>
                    @foreach($languesList as $l)
                      <option value="{{ $l->id }}" {{ $lid == $l->id ? 'selected' : '' }}>{{ $l->nom }}</option>
                    @endforeach
                  </select>
                  <select name="niveaux_ids[]" class="field__select langue-row__select" required>
                    <option value="">-- Niveau --</option>
                    @foreach($niveauxLangueList as $nl)
                      <option value="{{ $nl->id }}" {{ ($oldNiveaux[$li] ?? '') == $nl->id ? 'selected' : '' }}>{{ $nl->libelle }} ({{ $nl->code }})</option>
                    @endforeach
                  </select>
                  <button type="button" class="langue-row__remove" onclick="this.closest('.langue-row').remove()">×</button>
                </div>
                @endforeach
              @endif
            </div>
            <button type="button" id="add-langue-row" class="langue-add-btn">
              <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
              Ajouter une langue
            </button>
            <template id="langue-row-tpl">
              <div class="langue-row">
                <select name="langues_ids[]" class="field__select langue-row__select" required>
                  <option value="">-- Langue --</option>
                  @foreach($languesList as $l)
                    <option value="{{ $l->id }}">{{ $l->nom }}</option>
                  @endforeach
                </select>
                <select name="niveaux_ids[]" class="field__select langue-row__select" required>
                  <option value="">-- Niveau --</option>
                  @foreach($niveauxLangueList as $nl)
                    <option value="{{ $nl->id }}">{{ $nl->libelle }} ({{ $nl->code }})</option>
                  @endforeach
                </select>
                <button type="button" class="langue-row__remove" onclick="this.closest('.langue-row').remove()">×</button>
              </div>
            </template>
          </div>
        </div> --}}

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
              <div class="upload-zone__hint">PDF, DOC, DOCX, JPG, PNG ou WebP, max 5 Mo</div>
            </div>

            <div class="file-preview" id="filePreview">
              <div class="file-preview__icon">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
              </div>
              <div>
                <div class="file-preview__name" id="previewName">-</div>
                <div class="file-preview__meta" id="previewMeta">-</div>
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
                  <div class="cv-promo-banner__title">Votre CV vous fait rater des opportunités.</div>
                  <div class="cv-promo-banner__sub">Un recruteur décide en 7 secondes. Nos experts rédigent un CV qui passe tous les filtres, livré en 30 min à 1h max.</div>
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
/* ── Langue builder ───────────────────────────── */
#langues-builder { display: flex; flex-direction: column; gap: 8px; margin-bottom: 8px; }
.langue-row {
  display: flex; gap: 8px; align-items: center;
}
.langue-row__select { flex: 1; margin: 0; }
.langue-row__remove {
  flex-shrink: 0; width: 30px; height: 36px;
  background: #fee2e2; border: none; border-radius: 6px;
  color: #dc2626; font-size: 16px; cursor: pointer; transition: background .15s;
}
.langue-row__remove:hover { background: #fca5a5; }
.langue-add-btn {
  display: inline-flex; align-items: center; gap: 6px;
  padding: 7px 14px; border: 1.5px dashed #93c5fd; border-radius: 8px;
  background: #eff6ff; color: #185FA5; font-size: 13px; font-weight: 600;
  cursor: pointer; transition: background .15s;
}
.langue-add-btn:hover { background: #dbeafe; }
</style>

@endsection

@section('scripts')
<script src="{{ asset('js/searchable-select.js') }}"></script>
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
    name.textContent = meta.textContent = '-';
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

/* ── Langue builder ──────────────────────────── */
document.getElementById('add-langue-row').addEventListener('click', function () {
  const tpl = document.getElementById('langue-row-tpl');
  const clone = tpl.content.cloneNode(true);
  document.getElementById('langues-builder').appendChild(clone);
});

/* ── Photo preview ───────────────────────────── */
(function () {
  const input    = document.getElementById('photoInput');
  const img      = document.getElementById('photoPreviewImg');
  const initials = document.getElementById('photoInitials');
  const label    = document.getElementById('photoFileName');

  input.addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;
    label.textContent = file.name;
    const reader = new FileReader();
    reader.onload = e => {
      img.src = e.target.result;
      img.style.display = 'block';
      initials.style.display = 'none';
    };
    reader.readAsDataURL(file);
  });
})();
</script>
@endsection
