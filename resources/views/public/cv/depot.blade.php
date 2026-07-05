@extends('layouts.app')
@section('title', 'Déposer un CV | Emploi Bouge Bénin')

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
    <a href="{{ route('cv.public.depot') }}"  class="cvt-subnav__link active">Déposer un CV</a>
  </div>
</div>

{{-- Hero --}}
<section class="depot-hero">
  <div class="depot-hero__inner">
    <span class="depot-hero__badge">Espace candidat</span>
    <h1 class="depot-hero__title">Déposez votre <em>CV</em></h1>
    <p class="depot-hero__sub">Remplissez le formulaire ci-dessous pour apparaître dans la CVthèque et être trouvé par les recruteurs.</p>
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
      <div class="depot-step__label">Votre CV</div>
    </div>
    <div class="depot-step">
      <div class="depot-step__num">Étape 3</div>
      <div class="depot-step__label">Confirmation</div>
    </div>
  </div>

  @auth
  <div class="depot-user-badge">
    <div class="depot-user-badge__avatar">{{ mb_strtoupper(mb_substr(auth()->user()->prenom ?? auth()->user()->email, 0, 1)) }}</div>
    <div>
      <div class="depot-user-badge__name">{{ auth()->user()->prenom ? auth()->user()->prenom.' '.auth()->user()->nom : auth()->user()->email }}</div>
      <div class="depot-user-badge__sub">Connecté — remplissez le formulaire ci-dessous</div>
    </div>
  </div>
  @endauth

  {{-- Quota badge --}}
  @auth
  @if(isset($quota) && !$quota['unlimited'])
  <div style="display:flex;align-items:center;gap:10px;background:{{ $quota['remaining'] <= 1 ? '#fffbeb' : '#f0f9ff' }};border:1.5px solid {{ $quota['remaining'] <= 1 ? '#fde68a' : '#bae6fd' }};border-radius:10px;padding:11px 16px;margin-bottom:18px">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="{{ $quota['remaining'] <= 1 ? '#d97706' : '#0284c7' }}" stroke-width="2" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
    <p style="margin:0;font-size:13px;color:{{ $quota['remaining'] <= 1 ? '#92400e' : '#0c4a6e' }};flex:1">
      <strong>{{ $quota['used'] }}/{{ $quota['limit'] }} CV(s)</strong> déposé(s) sur votre plan
      @if($quota['remaining'] === 0)
        — quota atteint
      @else
        — encore <strong>{{ $quota['remaining'] }} CV</strong> possible{{ $quota['remaining'] > 1 ? 's' : '' }} sur votre plan
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
      <span class="depot-card__head-title">Informations du CV</span>
    </div>
    <div class="depot-card__body">
      <form method="POST" action="{{ route('cv.public.depot.store') }}" enctype="multipart/form-data">
        @csrf

        {{-- TYPE DE DOCUMENT --}}
        <div class="form-row form-row--1">
          <div>
            <label class="field__label" for="depot-type">Type de document <span class="req">*</span></label>
            <select class="field__select @error('type_document_id') field--invalid @enderror" id="depot-type" name="type_document_id" required>
              <option value="">-- Choisissez --</option>
              @foreach($typesDocuments as $type)
                <option value="{{ $type->id }}" {{ old('type_document_id', $typeCVId) == $type->id ? 'selected' : '' }}>
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

        {{-- PHOTO DE PROFIL --}}
        <div style="display:flex;align-items:center;gap:18px;margin-bottom:20px;padding:16px 18px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:12px">
          <div style="position:relative;flex-shrink:0">
            <div id="photoCircle" style="width:64px;height:64px;border-radius:50%;background:linear-gradient(135deg,#042C53,#185FA5);display:flex;align-items:center;justify-content:center;overflow:hidden;border:2.5px solid #e2e8f0">
              <span id="photoInitials" style="color:#fff;font-size:1.3rem;font-weight:800">{{ mb_strtoupper(mb_substr(auth()->user()->prenom ?? '?', 0, 1)) }}</span>
              <img id="photoPreviewImg" src="" alt="" style="display:none;width:100%;height:100%;object-fit:cover">
            </div>
            <label for="photoInput" style="position:absolute;bottom:0;right:0;width:20px;height:20px;border-radius:50%;background:#185FA5;border:2px solid #fff;display:flex;align-items:center;justify-content:center;cursor:pointer">
              <svg width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="#fff" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><circle cx="12" cy="13" r="3"/></svg>
            </label>
          </div>
          <div>
            <p style="font-size:13px;font-weight:700;color:#042C53;margin:0 0 2px">Photo de profil <span style="font-weight:400;color:#64748b;font-size:11px">optionnel</span></p>
            <p style="font-size:12px;color:#64748b;margin:0 0 6px">JPG, PNG ou WebP · max 2 Mo</p>
            <label for="photoInput" style="font-size:12.5px;color:#185FA5;font-weight:600;cursor:pointer;text-decoration:underline">Choisir une photo</label>
            <span id="photoFileName" style="font-size:11.5px;color:#94a3b8;margin-left:8px"></span>
            @error('photo')<p class="field__server-error">{{ $message }}</p>@enderror
          </div>
          <input type="file" id="photoInput" name="photo" accept=".jpg,.jpeg,.png,.webp" style="display:none">
        </div>

        {{-- INFORMATIONS PERSONNELLES --}}
        <div class="form-section-label" style="margin-top:24px">Informations personnelles</div>
        <div class="form-row form-row--2">
          <div>
            <label class="field__label" for="depot-prenom">Prénom <span class="req">*</span></label>
            <input class="field__input @error('prenom') field--invalid @enderror" type="text" id="depot-prenom" name="prenom" required
              value="{{ old('prenom', auth()->user()->prenom) }}"
              placeholder="Ex : Gildas, Aïchatou, Rodrigue…">
            @error('prenom')<p class="field__server-error">{{ $message }}</p>@enderror
          </div>
          <div>
            <label class="field__label" for="depot-nom-famille">Nom de famille <span class="req">*</span></label>
            <input class="field__input @error('nom_famille') field--invalid @enderror" type="text" id="depot-nom-famille" name="nom_famille" required
              value="{{ old('nom_famille', auth()->user()->nom) }}"
              placeholder="Ex : Kpogbozan, Dohou, Adanhou…">
            @error('nom_famille')<p class="field__server-error">{{ $message }}</p>@enderror
          </div>
        </div>
        <div class="form-row form-row--2">
          <div>
            <label class="field__label" for="depot-tel">Téléphone <span class="req">*</span></label>
            <input class="field__input @error('tel') field--invalid @enderror" type="tel" id="depot-tel" name="tel" required
              value="{{ old('tel', auth()->user()->tel) }}"
              placeholder="Ex : +229 97 00 00 00">
            @error('tel')<p class="field__server-error">{{ $message }}</p>@enderror
          </div>
          <div>
            <label class="field__label" for="depot-disponibilite">Disponibilité <span class="req">*</span></label>
            <select class="field__select @error('disponibilite') field--invalid @enderror" id="depot-disponibilite" name="disponibilite" required>
              <option value="">-- Sélectionnez --</option>
              @foreach(\App\Models\CandidatProfil::libelles()['disponibilite'] as $key => $label)
                <option value="{{ $key }}" {{ old('disponibilite', auth()->user()->candidatProfil?->disponibilite) === $key ? 'selected' : '' }}>
                  {{ $label }}
                </option>
              @endforeach
            </select>
            @error('disponibilite')<p class="field__server-error">{{ $message }}</p>@enderror
          </div>
        </div>

        {{-- INTITULÉ / POSTE VISÉ --}}
        <div class="form-row form-row--1">
          <div>
            <label class="field__label" for="depot-nom">Intitulé / Poste visé <span class="req">*</span></label>
            <input class="field__input @error('nom') field--invalid @enderror" type="text" id="depot-nom" name="nom" required
              value="{{ old('nom') }}"
              placeholder="Ex : Comptable, Infirmier, Juriste, BTS Commerce ENEAM…">
            @error('nom')<p class="field__server-error">{{ $message }}</p>@enderror
            <p style="font-size:12px;color:#94a3b8;margin:4px 0 0">Pour un CV : le poste visé. Pour un diplôme ou certificat : le nom du document.</p>
          </div>
        </div>

        {{-- RÉSUMÉ --}}
        <div class="form-row form-row--1">
          <div>
            <label class="field__label">Résumé / Présentation <span class="req" id="req-resume">*</span></label>
            <textarea class="field__textarea @error('resume') field--invalid @enderror" id="depot-resume" name="resume" rows="4"
              placeholder="Présentez votre parcours, vos compétences clés et vos objectifs professionnels…">{{ old('resume') }}</textarea>
            @error('resume')<p class="field__server-error">{{ $message }}</p>@enderror
            <p style="font-size:12px;color:#94a3b8;margin:4px 0 0">Max 2 000 caractères</p>
          </div>
        </div>

        {{-- LOCALISATION --}}
        <div class="form-section-label" style="margin-top:24px">Localisation</div>
        <div class="form-row form-row--2">
          <div>
            <label class="field__label" for="depot-pays">Pays <span class="req" id="req-pays">*</span></label>
            <select class="field__select @error('pays') field--invalid @enderror" id="depot-pays" name="pays">
              <option value="">-- Sélectionnez --</option>
              @foreach($paysList as $p)
                <option value="{{ $p }}" {{ old('pays') === $p ? 'selected' : '' }}>{{ $p }}</option>
              @endforeach
            </select>
            @error('pays')<p class="field__server-error">{{ $message }}</p>@enderror
          </div>
          <div>
            <label class="field__label">Ville <span class="req">*</span></label>
            <input class="field__input @error('ville') field--invalid @enderror" type="text" name="ville" required
              value="{{ old('ville', auth()->user()->candidatProfil?->ville) }}"
              placeholder="Ex : Cotonou, Abomey-Calavi, Porto-Novo…">
            @error('ville')<p class="field__server-error">{{ $message }}</p>@enderror
          </div>
        </div>

        {{-- PRÉFÉRENCES EMPLOI --}}
        <div class="form-section-label" style="margin-top:24px">Préférences emploi</div>

        <div class="form-row form-row--1">
          <div>
            <label class="field__label">Types de contrat souhaités <span class="req" id="req-type-contrat">*</span></label>
            <div id="types-contrat-group" style="display:flex;flex-wrap:wrap;gap:8px;margin-top:8px">
              @foreach($typesContrats as $tc)
                <label style="display:flex;align-items:center;gap:6px;font-size:13px;color:#042C53;cursor:pointer;padding:6px 12px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:8px;transition:border-color .15s"
                       onmouseover="this.style.borderColor='#185FA5'" onmouseout="this.querySelector('input:not(:checked)') && (this.style.borderColor='#e2e8f0')">
                  <input type="checkbox" name="types_contrat_ids[]" value="{{ $tc->id }}"
                    {{ in_array($tc->id, old('types_contrat_ids', [])) ? 'checked' : '' }}
                    style="width:15px;height:15px;accent-color:#185FA5;flex-shrink:0">
                  {{ $tc->libelle }}
                </label>
              @endforeach
            </div>
            <p class="field__server-error" id="types-contrat-error" style="display:none">Sélectionnez au moins un type de contrat souhaité.</p>
            @error('types_contrat_ids')<p class="field__server-error">{{ $message }}</p>@enderror
          </div>
        </div>

        <div class="form-row form-row--2">
          <div>
            <label class="field__label" for="depot-niveau-experience">Niveau d'expérience <span class="req" id="req-niveau-experience">*</span></label>
            <select class="field__select @error('niveau_experience_id') field--invalid @enderror" id="depot-niveau-experience" name="niveau_experience_id" required>
              <option value="">-- Sélectionnez --</option>
              @foreach($niveauxExperience as $ne)
                <option value="{{ $ne->id }}" {{ old('niveau_experience_id') == $ne->id ? 'selected' : '' }}>
                  {{ $ne->libelle }}
                </option>
              @endforeach
            </select>
            @error('niveau_experience_id')<p class="field__server-error">{{ $message }}</p>@enderror
          </div>
          <div>
            <label class="field__label" for="depot-niveau-etude">Niveau d'études <span class="req" id="req-niveau-etude">*</span></label>
            <select class="field__select @error('niveau_etude_id') field--invalid @enderror" id="depot-niveau-etude" name="niveau_etude_id" required>
              <option value="">-- Sélectionnez --</option>
              @foreach($niveauxEtude as $ne)
                <option value="{{ $ne->id }}" {{ old('niveau_etude_id') == $ne->id ? 'selected' : '' }}>
                  {{ $ne->libelle }}
                </option>
              @endforeach
            </select>
            @error('niveau_etude_id')<p class="field__server-error">{{ $message }}</p>@enderror
          </div>
        </div>

        {{-- CONTENU DU CV --}}
        <div class="form-section-label" style="margin-top:24px">Contenu du CV</div>

        <div class="form-row form-row--1">
          <div>
            <label class="field__label">Compétences principales <span class="req" id="req-competences">*</span></label>
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
            <p class="field__server-error" id="comp-error" style="display:none">Indiquez au moins une compétence.</p>
            @error('competences')<p class="field__server-error">{{ $message }}</p>@enderror
          </div>
        </div>

        {{-- ── EXPÉRIENCES ─────────────────────────────────── --}}
        <div class="form-section-sub">
          <span>Expériences professionnelles <span class="req" id="req-experience">*</span></span>
        </div>
        <input type="hidden" name="experience" id="exp-hidden">

        <div id="exp-builder" class="cv-builder"></div>
        <p class="field__server-error" id="exp-error" style="display:none">Ajoutez au moins une expérience professionnelle.</p>
        @error('experience')<p class="field__server-error">{{ $message }}</p>@enderror

        <button type="button" class="builder-add-btn" id="add-exp">
          <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
          Ajouter une expérience
        </button>

        <template id="exp-tpl">
          <div class="cv-block">
            <div class="cv-block__head">
              <span class="cv-block__num"></span>
              <button type="button" class="cv-block__del" title="Supprimer">
                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
              </button>
            </div>
            <div class="cv-block__body">
              <div class="form-row form-row--2">
                <div>
                  <label class="field__label">Poste <span class="req">*</span></label>
                  <input type="text" class="field__input exp-poste" placeholder="ex: Comptable, Caissier, Infirmier…">
                </div>
                <div>
                  <label class="field__label">Entreprise <span class="req">*</span></label>
                  <input type="text" class="field__input exp-entreprise" placeholder="ex: MTN Bénin, SONEB, Ecobank…">
                </div>
              </div>
              <div class="form-row form-row--2">
                <div>
                  <label class="field__label">Lieu</label>
                  <input type="text" class="field__input exp-lieu" placeholder="ex: Cotonou, Porto-Novo…">
                </div>
                <div>
                  <label class="field__label">Secteur</label>
                  <input type="text" class="field__input exp-secteur" placeholder="ex: Banque, BTP, Santé…">
                </div>
              </div>
              <div class="form-row form-row--2">
                <div>
                  <label class="field__label">Date de début <span class="req">*</span></label>
                  <input type="month" class="field__input exp-debut">
                </div>
                <div>
                  <label class="field__label">Date de fin <span style="font-size:11px;color:#94a3b8">(laisser vide si en cours)</span></label>
                  <input type="month" class="field__input exp-fin">
                </div>
              </div>
              <div>
                <label class="field__label">Missions / Responsabilités</label>
                <div class="inline-tag-builder">
                  <div class="inline-tag-box">
                    <div class="inline-tag-list"></div>
                    <input type="text" class="inline-tag-text" placeholder="ex: Tenue de la comptabilité… puis Entrée" maxlength="120">
                  </div>
                  <button type="button" class="inline-tag-addbtn">+ Ajouter</button>
                </div>
                <p class="field__hint">Max 20 missions · <kbd>Entrée</kbd> ou clic sur Ajouter</p>
              </div>
            </div>
          </div>
        </template>

        {{-- ── FORMATIONS ───────────────────────────────────── --}}
        <div class="form-section-sub" style="margin-top:24px">
          <span>Formations <span class="req" id="req-formation">*</span></span>
        </div>
        <input type="hidden" name="formation" id="form-hidden">

        <div id="form-builder" class="cv-builder"></div>
        <p class="field__server-error" id="form-error" style="display:none">Ajoutez au moins une formation.</p>
        @error('formation')<p class="field__server-error">{{ $message }}</p>@enderror

        <button type="button" class="builder-add-btn" id="add-form">
          <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
          Ajouter une formation
        </button>

        <template id="form-tpl">
          <div class="cv-block">
            <div class="cv-block__head">
              <span class="cv-block__num"></span>
              <button type="button" class="cv-block__del" title="Supprimer">
                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
              </button>
            </div>
            <div class="cv-block__body">
              <div class="form-row form-row--2">
                <div>
                  <label class="field__label">Diplôme <span class="req">*</span></label>
                  <input type="text" class="field__input form-diplome" placeholder="ex: Licence en Gestion, BTS Commerce…">
                </div>
                <div>
                  <label class="field__label">Établissement <span class="req">*</span></label>
                  <input type="text" class="field__input form-etablissement" placeholder="ex: UAC, ENEAM, UP Parakou…">
                </div>
              </div>
              <div class="form-row form-row--1">
                <div>
                  <label class="field__label">Domaine / Spécialité</label>
                  <input type="text" class="field__input form-domaine" placeholder="ex: Comptabilité, Droit, Génie Civil…">
                </div>
              </div>
              <div class="form-row form-row--2">
                <div>
                  <label class="field__label">Date de début <span class="req">*</span></label>
                  <input type="month" class="field__input form-debut">
                </div>
                <div>
                  <label class="field__label">Date de fin <span style="font-size:11px;color:#94a3b8">(laisser vide si en cours)</span></label>
                  <input type="month" class="field__input form-fin">
                </div>
              </div>
              <div>
                <label class="field__label">Activités / Réalisations</label>
                <div class="inline-tag-builder">
                  <div class="inline-tag-box">
                    <div class="inline-tag-list"></div>
                    <input type="text" class="inline-tag-text" placeholder="ex: Major de promotion, Stage à BOA Bénin… puis Entrée" maxlength="120">
                  </div>
                  <button type="button" class="inline-tag-addbtn">+ Ajouter</button>
                </div>
                <p class="field__hint">Max 20 activités · <kbd>Entrée</kbd> ou clic sur Ajouter</p>
              </div>
            </div>
          </div>
        </template>

        <div class="form-row form-row--1">
          <div>
            <label class="field__label">Langues <span class="req" id="req-langues">*</span></label>
            <div id="langues-builder">
              @php $oldLangues = old('langues_ids', []); $oldNiveaux = old('niveaux_ids', []); @endphp
              @if(count($oldLangues))
                @foreach($oldLangues as $li => $lid)
                <div class="langue-row">
                  <select name="langues_ids[]" class="field__select langue-row__select">
                    <option value="">-- Langue --</option>
                    @foreach($languesList as $l)
                      <option value="{{ $l->id }}" {{ $lid == $l->id ? 'selected' : '' }}>{{ $l->nom }}</option>
                    @endforeach
                  </select>
                  <select name="niveaux_ids[]" class="field__select langue-row__select">
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
                <select name="langues_ids[]" class="field__select langue-row__select">
                  <option value="">-- Langue --</option>
                  @foreach($languesList as $l)
                    <option value="{{ $l->id }}">{{ $l->nom }}</option>
                  @endforeach
                </select>
                <select name="niveaux_ids[]" class="field__select langue-row__select">
                  <option value="">-- Niveau --</option>
                  @foreach($niveauxLangueList as $nl)
                    <option value="{{ $nl->id }}">{{ $nl->libelle }} ({{ $nl->code }})</option>
                  @endforeach
                </select>
                <button type="button" class="langue-row__remove" onclick="this.closest('.langue-row').remove()">×</button>
              </div>
            </template>
            <p class="field__server-error" id="langues-error" style="display:none">Ajoutez au moins une langue avec son niveau.</p>
            @error('langues_ids')<p class="field__server-error">{{ $message }}</p>@enderror
          </div>
        </div>

        {{-- FICHIER PDF OBLIGATOIRE --}}
        <div class="form-section-label" style="margin-top:24px">
          Fichier PDF
          <span style="font-size:11px;font-weight:700;padding:2px 8px;border-radius:20px;background:#fee2e2;color:#dc2626;border:1px solid #fecaca;margin-left:6px">Obligatoire</span>
        </div>

        <div class="form-row form-row--1">
          <div>
            <div class="upload-zone" id="uploadZone">
              <input type="file" id="cvFile" name="fichier_path" accept=".pdf" required>
              <div class="upload-zone__icon">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
              </div>
              <div class="upload-zone__title">Glissez votre CV ici ou <span>cliquez pour charger</span></div>
              <div class="upload-zone__hint">PDF uniquement · max 5 Mo</div>
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
          Enregistrer et publier dans la CVthèque
        </button>

      </form>
    </div>
  </div>

</div>

<style>
/* ── Langue builder ───────────────────────────── */
#langues-builder { display: flex; flex-direction: column; gap: 8px; margin-bottom: 8px; }
.langue-row { display: flex; gap: 8px; align-items: center; }
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

/* ── CV Builders (exp + formation) ─────────────── */
.form-section-sub {
  font-size: 12px; font-weight: 700; color: #185FA5;
  text-transform: uppercase; letter-spacing: .06em;
  margin: 20px 0 10px; display: flex; align-items: center; gap: 8px;
}
.form-section-sub::after {
  content: ''; flex: 1; height: 1px; background: #e2e8f0;
}
.cv-builder { display: flex; flex-direction: column; gap: 12px; margin-bottom: 10px; }
.cv-block {
  border: 1.5px solid #e2e8f0; border-radius: 12px;
  background: #fafbfc; overflow: hidden;
}
.cv-block__head {
  display: flex; align-items: center; justify-content: space-between;
  padding: 10px 14px; background: #f1f5f9; border-bottom: 1px solid #e2e8f0;
}
.cv-block__num { font-size: 12px; font-weight: 700; color: #185FA5; }
.cv-block__del {
  width: 26px; height: 26px; border-radius: 6px; border: none;
  background: #fee2e2; color: #dc2626; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: background .15s;
}
.cv-block__del:hover { background: #fca5a5; }
.cv-block__body { padding: 14px; display: flex; flex-direction: column; gap: 12px; }
.builder-add-btn {
  display: inline-flex; align-items: center; gap: 7px;
  padding: 9px 16px; border: 1.5px dashed #93c5fd; border-radius: 9px;
  background: #eff6ff; color: #185FA5; font-size: 13px; font-weight: 600;
  cursor: pointer; transition: background .15s; margin-bottom: 4px;
}
.builder-add-btn:hover { background: #dbeafe; }

/* ── Inline tag builder (missions / activités) ── */
.inline-tag-builder { display: flex; gap: 8px; align-items: flex-start; margin-top: 6px; }
.inline-tag-box {
  flex: 1; min-height: 42px; padding: 6px 10px;
  border: 1px solid #e2e8f0; border-radius: 8px; background: #fff;
  display: flex; flex-wrap: wrap; gap: 5px; align-items: center; cursor: text;
  transition: border-color .15s;
}
.inline-tag-box:focus-within { border-color: #185FA5; box-shadow: 0 0 0 3px rgba(24,95,165,.1); }
.inline-tag-chip {
  display: inline-flex; align-items: center; gap: 4px;
  padding: 3px 8px; background: #eff6ff; border: 1px solid #bfdbfe;
  border-radius: 20px; font-size: 12px; font-weight: 600; color: #1e40af;
}
.inline-tag-chip button {
  background: none; border: none; cursor: pointer;
  color: #60a5fa; font-size: 13px; line-height: 1; padding: 0;
}
.inline-tag-chip button:hover { color: #dc2626; }
.inline-tag-text {
  border: none; outline: none; font-size: 13px;
  color: #1e293b; min-width: 140px; flex: 1; background: transparent; font-family: inherit;
}
.inline-tag-addbtn {
  flex-shrink: 0; padding: 8px 13px;
  background: #042C53; color: #F5C842; font-size: 12.5px; font-weight: 700;
  border: none; border-radius: 8px; cursor: pointer; white-space: nowrap;
  transition: background .15s;
}
.inline-tag-addbtn:hover { background: #185FA5; color: #fff; }
.field__hint { font-size: 11.5px; color: #94a3b8; margin: 4px 0 0; }
</style>

@endsection

@section('scripts')
<script src="{{ asset('js/searchable-select.js') }}"></script>
<script>
/* ── Upload fichier CV preview ── */
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
    meta.textContent = (file.size / 1024 / 1024).toFixed(2) + ' Mo · PDF';
  }

  input.addEventListener('change', () => showFile(input.files[0]));
  zone.addEventListener('dragover',  e => { e.preventDefault(); zone.classList.add('dragging'); });
  zone.addEventListener('dragleave', () => zone.classList.remove('dragging'));
  zone.addEventListener('drop', e => {
    e.preventDefault();
    zone.classList.remove('dragging');
    if (e.dataTransfer.files[0]) { input.files = e.dataTransfer.files; showFile(e.dataTransfer.files[0]); }
  });
  remove.addEventListener('click', () => {
    input.value = '';
    zone.classList.remove('has-file');
    preview.classList.remove('visible');
    name.textContent = meta.textContent = '-';
  });
})();

/* ── Tag input (compétences) ── */
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

/* ── Langue builder ── */
document.getElementById('add-langue-row').addEventListener('click', function () {
  const tpl   = document.getElementById('langue-row-tpl');
  const clone = tpl.content.cloneNode(true);
  document.getElementById('langues-builder').appendChild(clone);
});

/* ── Photo preview ── */
(function () {
  const input    = document.getElementById('photoInput');
  if (!input) return;
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

/* ══════════════════════════════════════════════════
   BUILDERS Expérience & Formation
══════════════════════════════════════════════════ */

/* ── Inline tag builder (missions / activités) ── */
function initInlineTagBuilder(block, max) {
  const box    = block.querySelector('.inline-tag-box');
  const list   = block.querySelector('.inline-tag-list');
  const txt    = block.querySelector('.inline-tag-text');
  const addBtn = block.querySelector('.inline-tag-addbtn');
  let tags = [];

  function render() {
    list.innerHTML = tags.map((t, i) =>
      `<span class="inline-tag-chip">${t}<button type="button" data-i="${i}" tabindex="-1">×</button></span>`
    ).join('');
  }

  function addTag(val) {
    val = val.trim();
    if (!val || tags.includes(val) || tags.length >= max) { txt.value = ''; return; }
    tags.push(val);
    render();
    txt.value = '';
  }

  list.addEventListener('click', e => {
    const btn = e.target.closest('button');
    if (btn) { tags.splice(+btn.dataset.i, 1); render(); }
  });

  txt.addEventListener('keydown', e => {
    if (e.key === 'Enter') { e.preventDefault(); addTag(txt.value); }
  });

  addBtn.addEventListener('click', () => addTag(txt.value));
  box.addEventListener('click', () => txt.focus());

  return { getTags: () => tags };
}

/* ── Renumber blocks ── */
function renumberBlocks(builderId, label) {
  document.querySelectorAll(`#${builderId} .cv-block__num`).forEach((el, i) => {
    el.textContent = `${label} ${i + 1}`;
  });
}

/* ── Format month string (YYYY-MM → "mois AAAA") ── */
function fmtMonth(val) {
  if (!val) return '';
  const [y, m] = val.split('-');
  const months = ['jan.','fév.','mars','avr.','mai','juin','juil.','août','sept.','oct.','nov.','déc.'];
  return `${months[+m - 1] || ''} ${y}`;
}

/* ════════ EXPÉRIENCES ════════ */
const expBuilder  = document.getElementById('exp-builder');
const expHidden   = document.getElementById('exp-hidden');
const expTpl      = document.getElementById('exp-tpl');
const expTags     = new Map(); // block element → { getTags }

function addExpBlock() {
  const clone = expTpl.content.cloneNode(true);
  const block = clone.querySelector('.cv-block');
  const tagApi = initInlineTagBuilder(block, 20);
  expBuilder.appendChild(block);
  const inserted = expBuilder.lastElementChild;
  expTags.set(inserted, tagApi);
  renumberBlocks('exp-builder', 'Expérience');
  inserted.querySelector('.cv-block__del').addEventListener('click', () => {
    expTags.delete(inserted);
    inserted.remove();
    renumberBlocks('exp-builder', 'Expérience');
  });
}

document.getElementById('add-exp').addEventListener('click', addExpBlock);

function serializeExp() {
  const parts = [];
  expBuilder.querySelectorAll('.cv-block').forEach(block => {
    const poste      = block.querySelector('.exp-poste')?.value.trim()      || '';
    const entreprise = block.querySelector('.exp-entreprise')?.value.trim() || '';
    if (!poste && !entreprise) return;
    const lieu    = block.querySelector('.exp-lieu')?.value.trim()    || '';
    const secteur = block.querySelector('.exp-secteur')?.value.trim() || '';
    const debut   = fmtMonth(block.querySelector('.exp-debut')?.value)  || '';
    const fin     = fmtMonth(block.querySelector('.exp-fin')?.value)    || 'en cours';
    const tags    = expTags.get(block)?.getTags() || [];

    let line = `${poste}${entreprise ? ' — ' + entreprise : ''}`;
    const meta = [lieu, secteur, debut && fin ? `${debut} → ${fin}` : debut].filter(Boolean);
    if (meta.length) line += ' | ' + meta.join(' | ');
    if (tags.length) line += '\nMissions : ' + tags.join(', ');
    parts.push(line);
  });
  expHidden.value = parts.join('\n\n');
}

/* ════════ FORMATIONS ════════ */
const formBuilder = document.getElementById('form-builder');
const formHidden  = document.getElementById('form-hidden');
const formTpl     = document.getElementById('form-tpl');
const formTags    = new Map();

function addFormBlock() {
  const clone = formTpl.content.cloneNode(true);
  const block = clone.querySelector('.cv-block');
  const tagApi = initInlineTagBuilder(block, 20);
  formBuilder.appendChild(block);
  const inserted = formBuilder.lastElementChild;
  formTags.set(inserted, tagApi);
  renumberBlocks('form-builder', 'Formation');
  inserted.querySelector('.cv-block__del').addEventListener('click', () => {
    formTags.delete(inserted);
    inserted.remove();
    renumberBlocks('form-builder', 'Formation');
  });
}

document.getElementById('add-form').addEventListener('click', addFormBlock);

function serializeForm() {
  const parts = [];
  formBuilder.querySelectorAll('.cv-block').forEach(block => {
    const diplome  = block.querySelector('.form-diplome')?.value.trim()       || '';
    const etabl    = block.querySelector('.form-etablissement')?.value.trim() || '';
    if (!diplome && !etabl) return;
    const domaine = block.querySelector('.form-domaine')?.value.trim() || '';
    const debut   = fmtMonth(block.querySelector('.form-debut')?.value)  || '';
    const fin     = fmtMonth(block.querySelector('.form-fin')?.value)    || 'en cours';
    const tags    = formTags.get(block)?.getTags() || [];

    let line = `${diplome}${etabl ? ' — ' + etabl : ''}`;
    const meta = [domaine, debut && fin ? `${debut} → ${fin}` : debut].filter(Boolean);
    if (meta.length) line += ' | ' + meta.join(' | ');
    if (tags.length) line += '\nActivités : ' + tags.join(', ');
    parts.push(line);
  });
  formHidden.value = parts.join('\n\n');
}

/* ── Champs obligatoires uniquement pour un CV (pas diplôme/attestation/etc.) ── */
const CV_TYPE_ID = '{{ $typeCVId }}';
const typeSelect  = document.getElementById('depot-type');
const niveauExpSelect   = document.getElementById('depot-niveau-experience');
const niveauEtudeSelect = document.getElementById('depot-niveau-etude');
const resumeField = document.getElementById('depot-resume');
const paysField    = document.getElementById('depot-pays');

function isCvSelected() {
  return typeSelect.value === CV_TYPE_ID;
}

function updateCvOnlyRequiredFields() {
  const isCv = isCvSelected();
  ['req-type-contrat', 'req-niveau-experience', 'req-niveau-etude', 'req-competences',
   'req-resume', 'req-experience', 'req-formation', 'req-langues', 'req-pays'].forEach(id => {
    document.getElementById(id).style.display = isCv ? 'inline' : 'none';
  });
  niveauExpSelect.required   = isCv;
  niveauEtudeSelect.required = isCv;
  resumeField.required       = isCv;
  paysField.required         = isCv;
}

typeSelect.addEventListener('change', updateCvOnlyRequiredFields);
updateCvOnlyRequiredFields();

function hasCompleteLangueRow() {
  return Array.from(document.querySelectorAll('#langues-builder .langue-row')).some(row => {
    const selects = row.querySelectorAll('select');
    return selects[0]?.value && selects[1]?.value;
  });
}

/* ── Validation + sérialisation avant soumission ── */
document.querySelector('form[action*="deposer"]').addEventListener('submit', function (e) {
  serializeExp();
  serializeForm();

  if (!isCvSelected()) return;

  let valid = true;
  let firstInvalidEl = null;

  function fail(errorId, el) {
    document.getElementById(errorId).style.display = 'block';
    valid = false;
    if (!firstInvalidEl) firstInvalidEl = el;
  }

  const typesContratChecked = document.querySelectorAll('#types-contrat-group input:checked').length > 0;
  document.getElementById('types-contrat-error').style.display = typesContratChecked ? 'none' : 'block';
  if (!typesContratChecked) fail('types-contrat-error', document.getElementById('types-contrat-group'));

  const compHasTags = document.getElementById('comp-hidden').value.trim().length > 0;
  document.getElementById('comp-error').style.display = compHasTags ? 'none' : 'block';
  if (!compHasTags) fail('comp-error', document.getElementById('comp-box'));

  const expHasContent = document.getElementById('exp-hidden').value.trim().length > 0;
  document.getElementById('exp-error').style.display = expHasContent ? 'none' : 'block';
  if (!expHasContent) fail('exp-error', document.getElementById('exp-builder'));

  const formHasContent = document.getElementById('form-hidden').value.trim().length > 0;
  document.getElementById('form-error').style.display = formHasContent ? 'none' : 'block';
  if (!formHasContent) fail('form-error', document.getElementById('form-builder'));

  const languesOk = hasCompleteLangueRow();
  document.getElementById('langues-error').style.display = languesOk ? 'none' : 'block';
  if (!languesOk) fail('langues-error', document.getElementById('langues-builder'));

  if (!valid) {
    e.preventDefault();
    firstInvalidEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
  }
});

/* Les builders démarrent vides — l'utilisateur clique pour ajouter */
</script>
@endsection
