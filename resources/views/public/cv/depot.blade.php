@extends('layouts.app')
@section('title', 'Déposer mon CV — Emploi Bouge Bénin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/cv/depot-cv.css') }}">
@endsection

@section('content')

{{-- Sous-nav --}}
<div class="cvt-subnav">
  <div class="cvt-subnav__inner">
    <a href="{{ route('cv.public.theque') }}" class="cvt-subnav__link">Trouver des CV</a>
    <a href="{{ route('cv.public.tarif') }}"  class="cvt-subnav__link">Packs CV</a>
    <a href="{{ route('cv.public.depot') }}"  class="cvt-subnav__link active">Déposer un CV</a>
  </div>
</div>

{{-- Hero --}}
<section class="depot-hero">
  <div class="depot-hero__inner">
    <span class="depot-hero__badge">Espace candidat</span>
    <h1 class="depot-hero__title">Déposez votre <em>CV</em></h1>
    <p class="depot-hero__sub">Rendez-vous visible auprès de centaines de recruteurs en quelques minutes.</p>
  </div>
</section>

<div class="depot-body">

  {{-- Indicateur d'étapes --}}
  <div class="depot-steps">
    <div class="depot-step depot-step--done">
      <div class="depot-step__num">Étape 1</div>
      <div class="depot-step__label">Votre compte</div>
    </div>
    <div class="depot-step depot-step--active">
      <div class="depot-step__num">Étape 2</div>
      <div class="depot-step__label">Votre CV</div>
    </div>
    <div class="depot-step">
      <div class="depot-step__num">Étape 3</div>
      <div class="depot-step__label">Confirmation</div>
    </div>
  </div>

  {{-- Badge utilisateur connecté --}}
  @auth
  <div class="depot-user-badge">
    <div class="depot-user-badge__avatar">{{ mb_strtoupper(mb_substr(auth()->user()->prenom, 0, 1)) }}</div>
    <div>
      <div class="depot-user-badge__name">{{ auth()->user()->prenom }} {{ auth()->user()->nom }}</div>
      <div class="depot-user-badge__sub">Connecté — prêt à déposer votre CV</div>
    </div>
  </div>
  @endauth

  {{-- Erreurs --}}
  @if($errors->any())
  <div class="depot-errors">
    <strong>Veuillez corriger les erreurs suivantes :</strong>
    <ul>
      @foreach($errors->all() as $err)<li>{{ $err }}</li>@endforeach
    </ul>
  </div>
  @endif

  {{-- Formulaire --}}
  <div class="depot-card">

    <div class="depot-card__head">
      <span class="depot-card__head-title">Informations de votre CV</span>
    </div>

    <div class="depot-card__body">
      <form method="POST" action="{{ route('cv.public.depot.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- Poste + Pays --}}
        <div class="form-row form-row--2">
          <div>
            <label class="field__label">Titre du poste visé <span class="req">*</span></label>
            <input class="field__input" type="text" name="titre_poste"
              value="{{ old('titre_poste') }}"
              placeholder="Ex : Développeur Web, Comptable…" required>
          </div>
          <div>
            <label class="field__label">Pays <span class="req">*</span></label>
            <select class="field__select" name="pays" required>
              <option value="">-- Sélectionnez --</option>
              @foreach(['Bénin','Côte d\'Ivoire','Sénégal','Cameroun','Togo','Mali','Burkina Faso','Niger','Guinée','Congo','Madagascar','Autre'] as $p)
                <option value="{{ $p }}" {{ old('pays') === $p ? 'selected' : '' }}>{{ $p }}</option>
              @endforeach
            </select>
          </div>
        </div>

        {{-- Ville --}}
        <div class="form-row form-row--1">
          <div>
            <label class="field__label">Ville</label>
            <input class="field__input" type="text" name="ville"
              value="{{ old('ville') }}" placeholder="Cotonou, Abidjan, Dakar…">
          </div>
        </div>

        {{-- Upload CV --}}
        <div class="form-section-label">Votre fichier CV</div>

        <div class="form-row form-row--1">
          <div>
            <div class="upload-zone" id="uploadZone">
              <input type="file" id="cvFile" name="fichier_path" accept=".pdf,.doc,.docx">
              <div class="upload-zone__icon">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
              </div>
              <div class="upload-zone__title">Glissez votre fichier ici ou <span>cliquez pour charger</span></div>
              <div class="upload-zone__hint">PDF, DOC, DOCX — max 5 Mo (optionnel)</div>
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

            {{-- Promo CV professionnel --}}
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

        {{-- Compétences --}}
        <div class="form-section-label">Compétences & profil</div>

        <div class="form-row form-row--1">
          <div>
            <label class="field__label">Compétences principales</label>
            <textarea class="field__textarea" name="competences" rows="3"
              placeholder="Ex : PHP, Laravel, MySQL, React, Design graphique…">{{ old('competences') }}</textarea>
          </div>
        </div>

        {{-- Expérience --}}
        <div class="form-row form-row--1">
          <div>
            <label class="field__label">Expérience professionnelle</label>
            <textarea class="field__textarea" name="experience" rows="4"
              placeholder="Décrivez vos expériences (poste, entreprise, durée, missions…)">{{ old('experience') }}</textarea>
          </div>
        </div>

        {{-- Formation --}}
        <div class="form-row form-row--1">
          <div>
            <label class="field__label">Formation</label>
            <textarea class="field__textarea" name="formation" rows="3"
              placeholder="Diplôme, école, année d'obtention…">{{ old('formation') }}</textarea>
          </div>
        </div>

        {{-- Langues --}}
        <div class="form-row form-row--1">
          <div>
            <label class="field__label">Langues</label>
            <input class="field__input" type="text" name="langues"
              value="{{ old('langues') }}"
              placeholder="Ex : Français (courant), Anglais (intermédiaire)…">
          </div>
        </div>

        <button type="submit" class="depot-submit-btn">
          <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
          </svg>
          Publier mon CV
        </button>

      </form>
    </div>
  </div>

</div>

{{-- Styles subnav (réutilisés depuis cvtheque) --}}
<style>
.cvt-subnav { background:#003d5c; border-bottom:1px solid rgba(255,255,255,.08); }
.cvt-subnav__inner { max-width:1180px; margin:0 auto; padding:0 24px; display:flex; align-items:center; height:42px; }
.cvt-subnav__link { font-family:var(--font-body); font-size:13px; font-weight:500; color:#F5C842; text-decoration:none; padding:0 20px; height:100%; display:flex; align-items:center; transition:color .2s,background .2s; border-right:1px solid rgba(255,255,255,.15); }
.cvt-subnav__link:first-child { border-left:1px solid rgba(255,255,255,.15); }
.cvt-subnav__link:hover { color:#fff; background:rgba(255,255,255,.06); }
.cvt-subnav__link.active { color:#fff; background:rgba(245,200,66,.12); }
</style>

@endsection

@section('scripts')
<script>
(function () {
  const input    = document.getElementById('cvFile');
  const zone     = document.getElementById('uploadZone');
  const preview  = document.getElementById('filePreview');
  const name     = document.getElementById('previewName');
  const meta     = document.getElementById('previewMeta');
  const remove   = document.getElementById('removeFile');

  function showFile(file) {
    if (!file) return;
    zone.classList.add('has-file');
    preview.classList.add('visible');
    name.textContent = file.name;
    meta.textContent = (file.size / 1024 / 1024).toFixed(2) + ' Mo · ' + file.name.split('.').pop().toUpperCase();
  }

  input.addEventListener('change', () => showFile(input.files[0]));

  zone.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('has-file'); });
  zone.addEventListener('dragleave', () => { if (!input.files[0]) zone.classList.remove('has-file'); });
  zone.addEventListener('drop', e => {
    e.preventDefault();
    if (e.dataTransfer.files[0]) {
      input.files = e.dataTransfer.files;
      showFile(e.dataTransfer.files[0]);
    }
  });

  remove.addEventListener('click', () => {
    input.value = '';
    zone.classList.remove('has-file');
    preview.classList.remove('visible');
    name.textContent = '—';
    meta.textContent = '—';
  });
})();
</script>
@endsection
