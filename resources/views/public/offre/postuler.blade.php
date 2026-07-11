@extends('layouts.app')
@section('title', 'Postuler | ' . $offre->titre)

@section('css')
<link rel="stylesheet" href="{{ asset('css/cv/depot-cv.css') }}">
@endsection

@section('content')
<section class="section" style="background:#f8fafc;min-height:70vh">
  <div class="container" style="max-width:780px">

    <a href="{{ route('offre.detail', $offre) }}" class="page-back-link">
      <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
      </svg>
      Retour à l'offre
    </a>

    @if($aPostule)
    {{-- Déjà postulé --}}
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:18px;padding:48px 36px;text-align:center">
      <div style="width:72px;height:72px;border-radius:50%;background:#d1fae5;display:flex;align-items:center;justify-content:center;margin:0 auto 20px">
        <svg width="36" height="36" fill="none" viewBox="0 0 24 24" stroke="#16a34a" stroke-width="2.5">
          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
        </svg>
      </div>
      <h2 style="font-size:1.4rem;font-weight:800;color:#042C53;margin:0 0 10px">Vous avez déjà postulé</h2>
      <p style="color:#64748b;font-size:14.5px;margin:0 0 28px;line-height:1.6">
        Votre candidature pour <strong>{{ $offre->titre }}</strong> chez <strong>{{ $offre->entreprise }}</strong> a bien été transmise.
      </p>
      <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
        <a href="{{ route('candidat.candidatures') }}" class="btn btn--blue">
          Voir mes candidatures
        </a>
        <a href="{{ route('offre.list') }}" class="btn btn--outline">
          Voir d'autres offres
        </a>
      </div>
    </div>

    @else

    {{-- Résumé de l'offre --}}
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:20px 24px;margin-bottom:20px;display:flex;gap:16px;align-items:center;flex-wrap:wrap">
      <div style="width:52px;height:52px;border-radius:12px;background:#dbeafe;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1.1rem;color:#185FA5;flex-shrink:0">
        {{ strtoupper(substr($offre->entreprise, 0, 2)) }}
      </div>
      <div style="flex:1;min-width:0">
        <p style="font-weight:800;color:#042C53;font-size:1rem;margin:0 0 5px">{{ $offre->titre }}</p>
        <p style="color:#64748b;font-size:13px;margin:0;display:flex;align-items:center;gap:8px;flex-wrap:wrap">
          <span>{{ $offre->entreprise }}</span>
          <span style="color:#cbd5e0">·</span>
          <span>{{ $offre->localisation }}</span>
          <span style="color:#cbd5e0">·</span>
          <span style="background:#dbeafe;color:#1e40af;font-size:11.5px;font-weight:700;padding:2px 10px;border-radius:20px">{{ $offre?->type?->libelle }}</span>
        </p>
      </div>
    </div>

    {{-- Formulaire candidature --}}
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:18px;padding:36px">
      <h1 style="font-size:1.4rem;font-weight:800;color:#042C53;margin:0 0 6px">Envoyer ma candidature</h1>
      <p style="color:#64748b;font-size:13.5px;margin:0 0 28px;line-height:1.55">
        Ajoutez un message de motivation et/ou un CV pour maximiser vos chances.
      </p>

      @if(session('error_duplicate'))
      <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:14px 18px;margin-bottom:20px;display:flex;gap:10px;align-items:center">
        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#dc2626" stroke-width="2" style="flex-shrink:0">
          <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p style="font-size:13px;color:#b91c1c;margin:0">Vous avez déjà postulé à cette offre.</p>
      </div>
      @endif

      @php
        $hasAny = $cvs->isNotEmpty() || $documents->isNotEmpty();
        $reqDocs = collect();
        if ($offre->exige_cv)     $reqDocs->push('un CV');
        if ($offre->exige_lettre) $reqDocs->push('une lettre de motivation');
      @endphp

      <form method="POST" action="{{ route('offre.postuler.store', $offre) }}" enctype="multipart/form-data">
        @csrf

        {{-- Badges exigences --}}
        @if($offre->exige_cv || $offre->exige_lettre)
        <div style="background:#fef9ec;border:1.5px solid #fcd34d;border-radius:10px;padding:12px 16px;margin-bottom:24px;display:flex;gap:10px;align-items:flex-start">
          <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#92400e" stroke-width="2" style="flex-shrink:0;margin-top:2px"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          <p style="font-size:13px;color:#92400e;margin:0;line-height:1.6">
            Ce recruteur exige : <strong>{{ $reqDocs->join(' et ') }}</strong>. Ces champs sont obligatoires.
          </p>
        </div>
        @endif

        {{-- Message de motivation --}}
        <div style="margin-bottom:24px">
          <label style="display:block;font-size:13.5px;font-weight:700;color:#374151;margin-bottom:8px">
            Message de motivation
            <span style="font-weight:400;color:#64748b;font-size:12px">optionnel</span>
          </label>
          <textarea name="message_motivation" rows="7"
                    placeholder="Expliquez en quelques mots pourquoi vous correspondez à ce poste, vos motivations et vos points forts…"
                    style="width:100%;padding:14px 16px;border:1.5px solid {{ $errors->has('message_motivation') ? '#dc2626' : '#d1d5db' }};border-radius:10px;font-size:14px;font-family:inherit;color:#1e293b;resize:vertical;box-sizing:border-box;line-height:1.65;outline:none"
                    onfocus="this.style.borderColor='#185FA5';this.style.boxShadow='0 0 0 3px rgba(24,95,165,.1)'"
                    onblur="this.style.borderColor='#d1d5db';this.style.boxShadow='none'">{{ old('message_motivation') }}</textarea>
        </div>

        {{-- ── CV requis ─────────────────────────────────────────── --}}
        @if($offre->exige_cv)
        <div style="margin-bottom:28px">
          <label style="display:block;font-size:13.5px;font-weight:700;color:#374151;margin-bottom:10px">
            CV <span style="color:#e53e3e">*</span>
            <span style="font-weight:400;color:#64748b;font-size:12px">Sélectionnez un CV existant ou téléversez-en un</span>
          </label>

          <input type="hidden" name="cv_id"       id="selectedCvId"  value="{{ old('cv_id') }}">
          <input type="hidden" name="document_id" id="selectedDocId" value="{{ old('document_id') }}">

          @if($hasAny)
          <div style="display:flex;flex-direction:column;gap:8px;margin-bottom:14px" id="docCards">
            @foreach($cvs as $cv)
            <div onclick="selectItem('cv', {{ $cv->id }})"
                 id="item-card-cv-{{ $cv->id }}"
                 style="display:flex;align-items:center;gap:12px;padding:12px 16px;border:2px solid {{ old('cv_id') == $cv->id ? '#185FA5' : '#e2e8f0' }};border-radius:10px;cursor:pointer;transition:all .15s;background:{{ old('cv_id') == $cv->id ? '#f0f7ff' : '#fff' }}">
              <div id="item-dot-cv-{{ $cv->id }}"
                   style="width:18px;height:18px;min-width:18px;border-radius:50%;border:2px solid {{ old('cv_id') == $cv->id ? '#185FA5' : '#cbd5e0' }};background:{{ old('cv_id') == $cv->id ? '#185FA5' : 'transparent' }};display:flex;align-items:center;justify-content:center;transition:all .15s;flex-shrink:0">
                @if(old('cv_id') == $cv->id)<div style="width:6px;height:6px;background:#fff;border-radius:50%"></div>@endif
              </div>
              <div style="flex:1;min-width:0">
                <p style="font-weight:700;color:#042C53;margin:0 0 2px;font-size:14px">{{ $cv->metier ?? $cv->titre_poste ?? 'Mon CV' }}</p>
                <p style="font-size:12px;color:#64748b;margin:0">{{ $cv->ville }}</p>
              </div>
              <span style="font-size:11px;background:#dbeafe;color:#1e40af;padding:2px 10px;border-radius:20px;font-weight:600;flex-shrink:0">CV</span>
            </div>
            @endforeach

            @foreach($documents as $doc)
            <div onclick="selectItem('doc', {{ $doc->id }})"
                 id="item-card-doc-{{ $doc->id }}"
                 style="display:flex;align-items:center;gap:12px;padding:12px 16px;border:2px solid {{ old('document_id') == $doc->id ? '#185FA5' : '#e2e8f0' }};border-radius:10px;cursor:pointer;transition:all .15s;background:{{ old('document_id') == $doc->id ? '#f0f7ff' : '#fff' }}">
              <div id="item-dot-doc-{{ $doc->id }}"
                   style="width:18px;height:18px;min-width:18px;border-radius:50%;border:2px solid {{ old('document_id') == $doc->id ? '#185FA5' : '#cbd5e0' }};background:{{ old('document_id') == $doc->id ? '#185FA5' : 'transparent' }};display:flex;align-items:center;justify-content:center;transition:all .15s;flex-shrink:0">
                @if(old('document_id') == $doc->id)<div style="width:6px;height:6px;background:#fff;border-radius:50%"></div>@endif
              </div>
              <div style="flex:1;min-width:0">
                <p style="font-weight:700;color:#042C53;margin:0 0 2px;font-size:14px">{{ $doc->nom }}</p>
                <p style="font-size:12px;color:#64748b;margin:0">{{ $doc->type?->nom }}</p>
              </div>
              <span style="font-size:11px;background:#f3e8ff;color:#7c3aed;padding:2px 10px;border-radius:20px;font-weight:600;flex-shrink:0">{{ $doc->type?->nom ?? 'Document' }}</span>
            </div>
            @endforeach
          </div>

          <button type="button" id="toggleFileBtn" onclick="toggleFileSection()"
                  style="font-size:13px;color:#185FA5;font-weight:600;background:none;border:none;cursor:pointer;padding:4px 0;display:inline-flex;align-items:center;gap:6px;margin-bottom:10px">
            <svg id="toggleFileIcon" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            <span id="toggleFileTxt">Ou téléverser un nouveau CV</span>
          </button>
          <div id="fileUploadSection" style="display:none">
          @endif

          <label id="dropzone-cv"
                 style="display:flex;align-items:center;gap:14px;padding:18px 20px;border:2px dashed {{ $errors->has('cv_file') ? '#dc2626' : '#cbd5e0' }};border-radius:12px;cursor:pointer;background:#fafafa;transition:all .2s"
                 onmouseover="this.style.borderColor='#185FA5';this.style.background='#f0f7ff'"
                 onmouseout="this.style.borderColor='{{ $errors->has('cv_file') ? '#dc2626' : '#cbd5e0' }}';this.style.background='#fafafa'">
            <div style="width:42px;height:42px;border-radius:10px;background:#dbeafe;display:flex;align-items:center;justify-content:center;flex-shrink:0">
              <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
            </div>
            <div style="flex:1;min-width:0">
              <p id="cvFileLabel" style="font-size:14px;font-weight:600;color:#185FA5;margin:0">Cliquer pour joindre votre CV</p>
              <p style="font-size:12px;color:#64748b;margin:3px 0 0">PDF, DOC, DOCX — max 5 Mo</p>
            </div>
            <input type="file" name="cv_file" id="cvFileInput" accept=".pdf,.doc,.docx" style="display:none"
                   onchange="onFileChosen(this,'cvFileLabel','cvFileInput')">
          </label>
          @error('cv_file')
            <p style="color:#dc2626;font-size:12.5px;margin:6px 0 0">{{ $message }}</p>
          @enderror

          @if($hasAny)
          </div>{{-- /fileUploadSection --}}
          @endif

          <a href="{{ route('service.commande', 'cv-professionnel') }}" class="cv-promo-banner" style="margin-top:14px">
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
              Commander un CV adéquat
              <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
              </svg>
            </span>
          </a>
        </div>
        @endif
        {{-- ── /CV requis ──────────────────────────────────────────── --}}

        {{-- ── Documents requis par le recruteur ───────────────────── --}}
        @foreach($offre->typesDocumentsRequis as $typeRequis)
        <div style="margin-bottom:28px">
          <label style="display:block;font-size:13.5px;font-weight:700;color:#374151;margin-bottom:10px">
            {{ $typeRequis->nom }} <span style="color:#e53e3e">*</span>
            <span style="font-weight:400;color:#64748b;font-size:12px">Requis par le recruteur pour cette offre</span>
          </label>

          @php $docsCeType = $documentsRequisParType->get($typeRequis->id, collect()); @endphp
          @if($docsCeType->isNotEmpty())
          <div style="display:flex;flex-direction:column;gap:8px;margin-bottom:12px">
            @php $oldExistant = old('pieces_existantes.'.$typeRequis->id); @endphp
            @foreach($docsCeType as $doc)
            <label style="display:flex;align-items:center;gap:12px;padding:12px 16px;border:1.5px solid {{ $oldExistant == $doc->id ? '#185FA5' : '#e2e8f0' }};border-radius:10px;cursor:pointer;background:{{ $oldExistant == $doc->id ? '#f0f7ff' : '#fff' }}">
              <input type="radio" name="pieces_existantes[{{ $typeRequis->id }}]" value="{{ $doc->id }}"
                     {{ $oldExistant == $doc->id ? 'checked' : '' }}
                     style="width:17px;height:17px;accent-color:#185FA5;flex-shrink:0">
              <div style="flex:1;min-width:0">
                <p style="font-weight:700;color:#042C53;margin:0 0 2px;font-size:13.5px">{{ $doc->nom }}</p>
                <p style="font-size:12px;color:#64748b;margin:0">Déjà dans votre espace</p>
              </div>
            </label>
            @endforeach
            <label style="display:flex;align-items:center;gap:12px;padding:12px 16px;border:1.5px dashed #cbd5e0;border-radius:10px;cursor:pointer;background:#fafafa">
              <input type="radio" name="pieces_existantes[{{ $typeRequis->id }}]" value=""
                     {{ !$oldExistant ? 'checked' : '' }}
                     style="width:17px;height:17px;accent-color:#185FA5;flex-shrink:0">
              <span style="font-size:13.5px;color:#64748b;font-weight:600">Téléverser un nouveau document</span>
            </label>
          </div>
          @endif

          <div id="upload-requis-{{ $typeRequis->id }}" style="{{ $docsCeType->isNotEmpty() && $oldExistant ? 'display:none' : '' }}">
            <label style="display:flex;align-items:center;gap:14px;padding:16px 18px;border:2px dashed {{ $errors->has('pieces_nouvelles.'.$typeRequis->id) ? '#dc2626' : '#cbd5e0' }};border-radius:12px;cursor:pointer;background:#fafafa">
              <div style="width:38px;height:38px;border-radius:10px;background:#dbeafe;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
              </div>
              <div style="flex:1;min-width:0">
                <p style="font-size:13.5px;font-weight:600;color:#185FA5;margin:0">Cliquer pour joindre « {{ $typeRequis->nom }} »</p>
                <p style="font-size:12px;color:#64748b;margin:3px 0 0">PDF, DOC, DOCX, JPG, PNG — max 5 Mo. Sera aussi ajouté à vos documents.</p>
              </div>
              <input type="file" name="pieces_nouvelles[{{ $typeRequis->id }}]" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.webp" style="display:none"
                     onchange="this.closest('label').querySelector('p').textContent = this.files[0]?.name || 'Cliquer pour joindre « {{ $typeRequis->nom }} »'">
            </label>
          </div>
          @error('pieces_nouvelles.'.$typeRequis->id)
            <p style="color:#dc2626;font-size:12.5px;margin:6px 0 0">{{ $message }}</p>
          @enderror

          @if($docsCeType->isNotEmpty())
          <script>
          document.querySelectorAll('input[name="pieces_existantes[{{ $typeRequis->id }}]"]').forEach(function (radio) {
            radio.addEventListener('change', function () {
              document.getElementById('upload-requis-{{ $typeRequis->id }}').style.display = this.value === '' ? '' : 'none';
            });
          });
          </script>
          @endif
        </div>
        @endforeach
        {{-- ── /Documents requis ───────────────────────────────────── --}}

        {{-- ── Pièces justificatives (optionnel) ───────────────────── --}}
        @if($documentsLibres->isNotEmpty())
        <div style="margin-bottom:28px">
          <label style="display:block;font-size:13.5px;font-weight:700;color:#374151;margin-bottom:10px">
            Autres pièces justificatives
            <span style="font-weight:400;color:#64748b;font-size:12px">Optionnel — joignez d'autres diplômes, attestations ou certificats pertinents</span>
          </label>
          <div style="display:flex;flex-direction:column;gap:8px">
            @php $oldPieces = old('pieces_ids', []); @endphp
            @foreach($documentsLibres as $doc)
            <label style="display:flex;align-items:center;gap:12px;padding:12px 16px;border:1.5px solid #e2e8f0;border-radius:10px;cursor:pointer;background:#fff">
              <input type="checkbox" name="pieces_ids[]" value="{{ $doc->id }}"
                     {{ in_array($doc->id, $oldPieces) ? 'checked' : '' }}
                     style="width:17px;height:17px;accent-color:#185FA5;flex-shrink:0">
              <div style="flex:1;min-width:0">
                <p style="font-weight:700;color:#042C53;margin:0 0 2px;font-size:13.5px">{{ $doc->nom }}</p>
                <p style="font-size:12px;color:#64748b;margin:0">{{ $doc->type?->nom }}</p>
              </div>
            </label>
            @endforeach
          </div>
        </div>
        @endif
        {{-- ── /Pièces justificatives ──────────────────────────────── --}}

        {{-- ── Lettre de motivation requise ───────────────────────── --}}
        @if($offre->exige_lettre)
        <div style="margin-bottom:28px">
          <label style="display:block;font-size:13.5px;font-weight:700;color:#374151;margin-bottom:10px">
            Lettre de motivation <span style="color:#e53e3e">*</span>
            <span style="font-weight:400;color:#64748b;font-size:12px">Fichier PDF ou Word uniquement</span>
          </label>
          <label id="dropzone-lettre"
                 style="display:flex;align-items:center;gap:14px;padding:18px 20px;border:2px dashed {{ $errors->has('lettre_file') ? '#dc2626' : '#cbd5e0' }};border-radius:12px;cursor:pointer;background:#fafafa;transition:all .2s"
                 onmouseover="this.style.borderColor='#185FA5';this.style.background='#f0f7ff'"
                 onmouseout="this.style.borderColor='{{ $errors->has('lettre_file') ? '#dc2626' : '#cbd5e0' }}';this.style.background='#fafafa'">
            <div style="width:42px;height:42px;border-radius:10px;background:#fce7f3;display:flex;align-items:center;justify-content:center;flex-shrink:0">
              <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#be185d" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <div style="flex:1;min-width:0">
              <p id="lettreFileLabel" style="font-size:14px;font-weight:600;color:#be185d;margin:0">Cliquer pour joindre votre lettre de motivation</p>
              <p style="font-size:12px;color:#64748b;margin:3px 0 0">PDF, DOC, DOCX — max 5 Mo</p>
            </div>
            <input type="file" name="lettre_file" id="lettreFileInput" accept=".pdf,.doc,.docx" style="display:none"
                   onchange="onFileChosen(this,'lettreFileLabel','lettreFileInput')">
          </label>
          @error('lettre_file')
            <p style="color:#dc2626;font-size:12.5px;margin:6px 0 0">{{ $message }}</p>
          @enderror
        </div>
        @endif
        {{-- ── /Lettre de motivation ──────────────────────────────── --}}

        {{-- Info --}}
        <div style="background:#f0f9ff;border:1px solid #bae6fd;border-radius:10px;padding:14px 18px;margin-bottom:26px;display:flex;gap:10px;align-items:flex-start">
          <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#0369a1" stroke-width="2" style="flex-shrink:0;margin-top:2px">
            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          <p style="font-size:13px;color:#0369a1;margin:0;line-height:1.6">
            Votre candidature sera transmise directement au recruteur. Vous serez notifié(e) dès qu'elle sera consultée depuis votre <strong>espace candidat</strong>.
          </p>
        </div>

        {{-- Boutons --}}
        <div style="display:flex;gap:12px;flex-wrap:wrap">
          <button type="submit" class="btn btn--yellow" style="flex:1;min-width:220px">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
            </svg>
            Envoyer ma candidature
          </button>
          <a href="{{ route('offre.detail', $offre) }}" class="btn btn--outline">
            Annuler
          </a>
        </div>
      </form>
    </div>
    @endif

  </div>
</section>

@if(!$aPostule)
<script>
let fileOpen = false;

function clearAllItems() {
  document.querySelectorAll('[id^="item-card-"]').forEach(el => {
    el.style.border = '2px solid #e2e8f0';
    el.style.background = '#fff';
  });
  document.querySelectorAll('[id^="item-dot-"]').forEach(el => {
    el.style.border = '2px solid #cbd5e0';
    el.style.background = 'transparent';
    el.innerHTML = '';
  });
  const selCv  = document.getElementById('selectedCvId');
  const selDoc = document.getElementById('selectedDocId');
  if (selCv)  selCv.value  = '';
  if (selDoc) selDoc.value = '';
}

function selectItem(type, id) {
  clearAllItems();
  const card = document.getElementById('item-card-' + type + '-' + id);
  const dot  = document.getElementById('item-dot-'  + type + '-' + id);
  if (card) { card.style.border = '2px solid #185FA5'; card.style.background = '#f0f7ff'; }
  if (dot)  { dot.style.border = '2px solid #185FA5'; dot.style.background = '#185FA5'; dot.innerHTML = '<div style="width:6px;height:6px;background:#fff;border-radius:50%"></div>'; }
  if (type === 'cv')  { const el = document.getElementById('selectedCvId');  if (el) el.value = id; }
  if (type === 'doc') { const el = document.getElementById('selectedDocId'); if (el) el.value = id; }
  const fileSection = document.getElementById('fileUploadSection');
  const cvFileInput = document.getElementById('cvFileInput');
  if (fileSection) { fileSection.style.display = 'none'; fileOpen = false; updateToggleBtn(); }
  if (cvFileInput) { cvFileInput.value = ''; const lbl = document.getElementById('cvFileLabel'); if (lbl) lbl.textContent = 'Cliquer pour joindre votre CV'; }
}

function toggleFileSection() {
  fileOpen = !fileOpen;
  const fileSection = document.getElementById('fileUploadSection');
  if (fileSection) fileSection.style.display = fileOpen ? 'block' : 'none';
  if (fileOpen) clearAllItems();
  updateToggleBtn();
}

function updateToggleBtn() {
  const icon = document.getElementById('toggleFileIcon');
  const txt  = document.getElementById('toggleFileTxt');
  if (!icon || !txt) return;
  if (fileOpen) {
    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/>';
    txt.textContent = 'Masquer';
  } else {
    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>';
    txt.textContent = 'Ou téléverser un nouveau CV';
  }
}

function onFileChosen(input, labelId, inputId) {
  const label = document.getElementById(labelId);
  if (input.files[0]) {
    if (label) label.textContent = input.files[0].name;
    if (labelId === 'cvFileLabel') clearAllItems();
  } else {
    if (label) label.textContent = input.name === 'lettre_file'
      ? 'Cliquer pour joindre votre lettre de motivation'
      : 'Cliquer pour joindre votre CV';
  }
}
</script>
@endif

@endsection
