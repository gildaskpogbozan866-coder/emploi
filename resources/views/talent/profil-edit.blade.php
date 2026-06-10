@extends('layouts.candidat')
@section('title', 'Compléter mon profil — Espace Talent')


@section('sidebar')
@include('candidat._sidebar')
@endsection
@section('content')

  <div class="cand-page-header">
    <div class="cand-page-header__left">
      <div class="cand-page-header__title">Mon profil</div>
      <div class="cand-page-header__sub">Renseignez vos informations pour être visible par les recruteurs</div>
    </div>
    <div class="cand-page-header__actions">
      <a href="{{ route('talent.profil') }}" class="cand-btn cand-btn--outline">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Voir mon profil
      </a>
    </div>
  </div>

  @if($errors->any())
  <div id="flash-data" data-type="error" data-msg="{{ implode(' — ', $errors->all()) }}" style="display:none"></div>
  @endif
  @if(session('success'))<div id="flash-data" data-type="success" data-msg="{{ session('success') }}" style="display:none"></div>@endif

  {{-- Grille 2 colonnes — HORS du form principal pour éviter les forms imbriqués --}}
  <div class="cand-2col">

    {{-- Colonne principale --}}
    <div>

      {{-- ════ FORM PRINCIPAL — champs de base uniquement ════ --}}
      <form method="POST" action="{{ route('talent.profil.update') }}" enctype="multipart/form-data" id="talentEditForm">
        @csrf @method('PUT')

        {{-- Photo de profil --}}
        <div id="photo" class="cand-card">
          <div class="cand-card__head">
            <div class="cand-card__title">
              <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><circle cx="12" cy="13" r="3"/></svg>
              Photo de profil
            </div>
          </div>
          <div style="display:flex;align-items:center;gap:16px;flex-wrap:wrap">
            <div style="width:68px;height:68px;border-radius:50%;background:#f0fdf4;border:2px solid #bbf7d0;overflow:hidden;display:flex;align-items:center;justify-content:center;font-size:1.4rem;font-weight:700;color:#065f46;flex-shrink:0" id="photoPreview">
              @if($profil->photo)
                <img src="{{ asset('storage/'.$profil->photo) }}" alt="" style="width:68px;height:68px;object-fit:cover" id="previewImg">
              @else
                <span id="initiale">{{ auth()->user()->initiale }}</span>
              @endif
            </div>
            <div style="flex:1">
              <input type="file" name="photo" id="photoInput" accept="image/jpeg,image/png,image/webp"
                     class="cand-form-input" onchange="previewPhoto(this)">
              <p class="cand-form-hint">JPG, PNG, WebP — max 2 Mo</p>
              @error('photo')<p style="color:#e53e3e;font-size:12px;margin:3px 0 0">{{ $message }}</p>@enderror
            </div>
          </div>
        </div>

        {{-- Identité --}}
        <div id="specialite" class="cand-card">
          <div class="cand-card__head">
            <div class="cand-card__title">
              <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
              Identité professionnelle
            </div>
          </div>
          <div class="cand-form-grid">
            <div class="cand-form-group">
              <label class="cand-form-label">Métier <span class="req">*</span></label>
              <input type="text" name="metier" value="{{ old('metier', $profil->metier) }}" required maxlength="200"
                     placeholder="Ex : Menuisier, Mécanicien, Électricien…" class="cand-form-input">
              @error('metier')<p style="color:#e53e3e;font-size:12px;margin:3px 0 0">{{ $message }}</p>@enderror
            </div>
            <div class="cand-form-group">
              <label class="cand-form-label">Spécialité</label>
              <input type="text" name="specialite" value="{{ old('specialite', $profil->specialite) }}" maxlength="200"
                     placeholder="Ex : Menuiserie ébénisterie, Mécanique auto…" class="cand-form-input">
            </div>
            <div class="cand-form-group">
              <label class="cand-form-label">Pays <span class="req">*</span></label>
              <input type="text" name="pays" value="{{ old('pays', $profil->pays) }}" required maxlength="100"
                     placeholder="Bénin, Sénégal…" class="cand-form-input">
              @error('pays')<p style="color:#e53e3e;font-size:12px;margin:3px 0 0">{{ $message }}</p>@enderror
            </div>
            <div class="cand-form-group">
              <label class="cand-form-label">Ville</label>
              <input type="text" name="ville" value="{{ old('ville', $profil->ville) }}" maxlength="100"
                     placeholder="Cotonou, Porto-Novo…" class="cand-form-input">
            </div>
          </div>
        </div>

        {{-- Présentation --}}
        <div id="bio" class="cand-card">
          <div class="cand-card__head">
            <div class="cand-card__title">
              <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/></svg>
              Présentation
            </div>
          </div>
          <div class="cand-form-group">
            <textarea name="bio" rows="5" maxlength="2000" id="bioInput"
                      placeholder="Décrivez votre formation, vos expériences passées, votre sérieux et ce que vous recherchez…"
                      class="cand-form-textarea">{{ old('bio', $profil->bio) }}</textarea>
            <p class="cand-form-hint" style="text-align:right"><span id="bioCount">{{ mb_strlen(old('bio', $profil->bio ?? '')) }}</span>/2000</p>
          </div>
        </div>

        {{-- Compétences --}}
        <div id="competences" class="cand-card">
          <div class="cand-card__head">
            <div class="cand-card__title">
              <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
              Compétences techniques
            </div>
          </div>
          <p class="cand-form-hint" style="margin-bottom:12px">Tapez une compétence et appuyez sur Entrée ou cliquez Ajouter</p>
          <div style="display:flex;gap:8px;margin-bottom:12px">
            <input type="text" id="compInput" placeholder="Ex : Soudure, Pose carrelage, Peinture bâtiment…"
                   class="cand-form-input" style="flex:1">
            <button type="button" onclick="addComp()" class="cand-btn cand-btn--primary">+ Ajouter</button>
          </div>
          <div id="compList" style="display:flex;flex-wrap:wrap;gap:8px;min-height:32px"></div>
          <input type="hidden" name="competences" id="compHidden" value="{{ old('competences', implode(',', $profil->competences ?? [])) }}">
        </div>

        {{-- Expérience générale --}}
        <div id="experience" class="cand-card">
          <div class="cand-card__head">
            <div class="cand-card__title">
              <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3"/></svg>
              Expérience générale
            </div>
          </div>
          <div class="cand-form-grid">
            <div class="cand-form-group">
              <label class="cand-form-label">Années d'expérience</label>
              <input type="number" name="annees_experience"
                     value="{{ old('annees_experience', $profil->annees_experience) }}"
                     min="0" max="50" placeholder="Ex : 3" class="cand-form-input">
            </div>
            <div id="portfolio" class="cand-form-group">
              <label class="cand-form-label">Lien vers travaux (optionnel)</label>
              <input type="url" name="portfolio_url" value="{{ old('portfolio_url', $profil->portfolio_url) }}"
                     placeholder="https://… (photos ou vidéos de réalisations)" class="cand-form-input">
              @error('portfolio_url')<p style="color:#e53e3e;font-size:12px;margin:3px 0 0">{{ $message }}</p>@enderror
            </div>
            <div class="cand-form-group" style="grid-column:1/-1">
              <label class="cand-form-label">Description libre</label>
              <input type="text" name="experience" value="{{ old('experience', $profil->experience) }}" maxlength="500"
                     placeholder="Ex : 3 ans d'expérience en menuiserie dans une entreprise à Cotonou"
                     class="cand-form-input">
            </div>
          </div>
        </div>

        {{-- Disponibilité & Contrat --}}
        <div id="disponibilite" class="cand-card">
          <div class="cand-card__head">
            <div class="cand-card__title">
              <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" d="M16 2v4M8 2v4M3 10h18"/></svg>
              Disponibilité & Contrat recherché
            </div>
          </div>
          <div class="cand-form-grid">
            <div class="cand-form-group">
              <label class="cand-form-label">Disponibilité</label>
              <select name="disponibilite" class="cand-form-select">
                <option value="">— Sélectionner —</option>
                @foreach(['immediatement'=>'Immédiatement','1_mois'=>'Dans 1 mois','2_mois'=>'Dans 2 mois','3_mois'=>'Dans 3 mois','plus_3_mois'=>'Plus de 3 mois'] as $val => $lbl)
                  <option value="{{ $val }}" {{ old('disponibilite', $profil->disponibilite) === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                @endforeach
              </select>
            </div>
            <div id="types_contrat" class="cand-form-group">
              <label class="cand-form-label">Types de contrat souhaités</label>
              <div style="display:flex;flex-direction:column;gap:7px;margin-top:4px">
                @foreach(['cdi'=>'CDI','cdd'=>'CDD','stage'=>'Stage','alternance'=>'Alternance','interim'=>'Intérim'] as $val => $lbl)
                <label style="display:flex;align-items:center;gap:8px;font-size:13.5px;color:#374151;cursor:pointer">
                  <input type="checkbox" name="types_contrat[]" value="{{ $val }}"
                         {{ in_array($val, old('types_contrat', $profil->types_contrat ?? [])) ? 'checked' : '' }}
                         style="width:15px;height:15px;accent-color:#38A169">
                  {{ $lbl }}
                </label>
                @endforeach
              </div>
            </div>
          </div>
        </div>

        {{-- Langues --}}
        <div id="langues" class="cand-card">
          <div class="cand-card__head">
            <div class="cand-card__title">
              <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
              Langues
            </div>
          </div>
          <p class="cand-form-hint" style="margin-bottom:12px">Ajoutez vos langues parlées avec leur niveau</p>
          <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:12px">
            <input type="text" id="langInput" placeholder="Ex : Français, Fon, Yoruba…"
                   class="cand-form-input" style="flex:1;min-width:140px">
            <select id="langNiveau" class="cand-form-select" style="width:auto">
              <option value="A1">A1</option><option value="A2">A2</option>
              <option value="B1">B1</option><option value="B2" selected>B2</option>
              <option value="C1">C1</option><option value="C2">C2</option>
              <option value="natif">Natif</option>
            </select>
            <button type="button" onclick="addLang()" class="cand-btn cand-btn--primary">+ Ajouter</button>
          </div>
          <div id="langList" style="display:flex;flex-wrap:wrap;gap:8px;min-height:32px"></div>
          <input type="hidden" name="langues_json" id="langHidden">
        </div>

      </form>{{-- ════ FIN DU FORM PRINCIPAL ════ --}}


      {{-- ════ SECTIONS INDÉPENDANTES (hors form principal) ════ --}}

      {{-- Suppression photo (séparée du form principal) --}}
      @if($profil->photo)
      <div class="cand-card">
        <div class="cand-card__head">
          <div class="cand-card__title">Supprimer la photo actuelle</div>
        </div>
        <div style="display:flex;align-items:center;gap:14px">
          <img src="{{ asset('storage/'.$profil->photo) }}" alt="" style="width:52px;height:52px;border-radius:50%;object-fit:cover;border:2px solid #e2e8f0">
          <form method="POST" action="{{ route('talent.profil.photo.delete') }}" onsubmit="return confirm('Supprimer la photo ?')">
            @csrf @method('DELETE')
            <button type="submit" class="cand-btn cand-btn--danger cand-btn--sm">Supprimer la photo</button>
          </form>
        </div>
      </div>
      @endif

      {{-- Expériences professionnelles --}}
      <div id="experiences" class="cand-card">
        <div class="cand-card__head">
          <div class="cand-card__title">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>
            Expériences professionnelles
          </div>
        </div>

        @foreach($profil->experiences as $exp)
        <div style="display:flex;gap:12px;align-items:flex-start;padding:10px 0;border-bottom:1px solid #f0f2f5">
          <div style="width:34px;height:34px;background:#f0fdf4;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#38A169" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>
          </div>
          <div style="flex:1;min-width:0">
            <p style="font-weight:700;color:#1e293b;font-size:14px;margin:0 0 1px">{{ $exp->poste }}</p>
            @if($exp->employeur)<p style="font-size:13px;color:#64748b;margin:0 0 1px">{{ $exp->employeur }}</p>@endif
            <p style="font-size:12px;color:#94a3b8;margin:0 0 3px">{{ $exp->periode }}</p>
            @if($exp->description)<p style="font-size:13px;color:#374151;margin:0;line-height:1.5">{{ $exp->description }}</p>@endif
          </div>
          <form method="POST" action="{{ route('talent.experiences.delete', $exp) }}" onsubmit="return confirm('Supprimer ?')">
            @csrf @method('DELETE')
            <button type="submit" class="cand-btn cand-btn--danger cand-btn--sm">Supprimer</button>
          </form>
        </div>
        @endforeach

        <details style="margin-top:14px" {{ $errors->has('poste') ? 'open' : '' }}>
          <summary style="cursor:pointer;font-size:13.5px;font-weight:600;color:#38A169;list-style:none;display:flex;align-items:center;gap:6px">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Ajouter une expérience
          </summary>
          <form method="POST" action="{{ route('talent.experiences.store') }}" style="margin-top:12px;padding:14px;background:#f8fafc;border-radius:9px;display:flex;flex-direction:column;gap:10px">
            @csrf
            <div class="cand-form-grid">
              <div class="cand-form-group">
                <label class="cand-form-label">Poste / Fonction <span class="req">*</span></label>
                <input type="text" name="poste" value="{{ old('poste') }}" required maxlength="200"
                       placeholder="Ex : Menuisier, Apprenti maçon…" class="cand-form-input">
                @error('poste')<p style="color:#e53e3e;font-size:11.5px;margin:3px 0 0">{{ $message }}</p>@enderror
              </div>
              <div class="cand-form-group">
                <label class="cand-form-label">Employeur / Atelier</label>
                <input type="text" name="employeur" value="{{ old('employeur') }}" maxlength="200"
                       placeholder="Ex : Menuiserie Adjovi, Garage Sossou…" class="cand-form-input">
              </div>
              <div class="cand-form-group">
                <label class="cand-form-label">Date de début <span class="req">*</span></label>
                <input type="text" name="date_debut" value="{{ old('date_debut') }}" required placeholder="Ex : Jan 2021" maxlength="20" class="cand-form-input">
              </div>
              <div class="cand-form-group">
                <label class="cand-form-label">Date de fin</label>
                <input type="text" name="date_fin" value="{{ old('date_fin') }}" placeholder="Ex : Déc 2023" maxlength="20" id="expDateFin" class="cand-form-input">
                <label style="display:flex;align-items:center;gap:6px;font-size:12px;color:#64748b;margin-top:6px;cursor:pointer">
                  <input type="checkbox" name="en_cours" value="1" {{ old('en_cours') ? 'checked' : '' }}
                         onchange="document.getElementById('expDateFin').disabled=this.checked;if(this.checked)document.getElementById('expDateFin').value=''"
                         style="accent-color:#38A169">
                  Poste actuel (en cours)
                </label>
              </div>
            </div>
            <div class="cand-form-group">
              <label class="cand-form-label">Description</label>
              <textarea name="description" rows="3" maxlength="1000" placeholder="Décrivez vos principales tâches…" class="cand-form-textarea">{{ old('description') }}</textarea>
            </div>
            <div class="cand-form-actions">
              <button type="submit" class="cand-btn cand-btn--primary">Enregistrer l'expérience</button>
            </div>
          </form>
        </details>
      </div>

      {{-- Formations --}}
      <div id="formations" class="cand-card">
        <div class="cand-card__head">
          <div class="cand-card__title">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
            Formations & Diplômes
          </div>
        </div>

        @foreach($profil->formations as $form)
        <div style="display:flex;gap:12px;align-items:flex-start;padding:10px 0;border-bottom:1px solid #f0f2f5">
          <div style="width:34px;height:34px;background:#f0fdf4;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#38A169" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
          </div>
          <div style="flex:1;min-width:0">
            <p style="font-weight:700;color:#1e293b;font-size:14px;margin:0 0 1px">{{ $form->diplome }}</p>
            @if($form->etablissement)<p style="font-size:13px;color:#64748b;margin:0 0 1px">{{ $form->etablissement }}</p>@endif
            @if($form->annee_obtention)<p style="font-size:12px;color:#94a3b8;margin:0 0 3px">{{ $form->annee_obtention }}</p>@endif
            @if($form->description)<p style="font-size:13px;color:#374151;margin:0;line-height:1.5">{{ $form->description }}</p>@endif
          </div>
          <form method="POST" action="{{ route('talent.formations.delete', $form) }}" onsubmit="return confirm('Supprimer ?')">
            @csrf @method('DELETE')
            <button type="submit" class="cand-btn cand-btn--danger cand-btn--sm">Supprimer</button>
          </form>
        </div>
        @endforeach

        <details style="margin-top:14px" {{ $errors->has('diplome') ? 'open' : '' }}>
          <summary style="cursor:pointer;font-size:13.5px;font-weight:600;color:#38A169;list-style:none;display:flex;align-items:center;gap:6px">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Ajouter une formation
          </summary>
          <form method="POST" action="{{ route('talent.formations.store') }}" style="margin-top:12px;padding:14px;background:#f8fafc;border-radius:9px;display:flex;flex-direction:column;gap:10px">
            @csrf
            <div class="cand-form-grid">
              <div class="cand-form-group">
                <label class="cand-form-label">Diplôme / Certificat <span class="req">*</span></label>
                <input type="text" name="diplome" value="{{ old('diplome') }}" required maxlength="200"
                       placeholder="Ex : CAP Menuiserie, BEP Électricité…" class="cand-form-input">
                @error('diplome')<p style="color:#e53e3e;font-size:11.5px;margin:3px 0 0">{{ $message }}</p>@enderror
              </div>
              <div class="cand-form-group">
                <label class="cand-form-label">Établissement / Centre</label>
                <input type="text" name="etablissement" value="{{ old('etablissement') }}" maxlength="200"
                       placeholder="Ex : CFPME Cotonou…" class="cand-form-input">
              </div>
              <div class="cand-form-group">
                <label class="cand-form-label">Année d'obtention</label>
                <input type="number" name="annee_obtention" value="{{ old('annee_obtention') }}"
                       min="1950" max="{{ date('Y') + 1 }}" placeholder="Ex : 2020" class="cand-form-input">
              </div>
            </div>
            <div class="cand-form-group">
              <label class="cand-form-label">Description (optionnel)</label>
              <textarea name="description" rows="2" maxlength="1000" placeholder="Précisions sur la formation…" class="cand-form-textarea">{{ old('description') }}</textarea>
            </div>
            <div class="cand-form-actions">
              <button type="submit" class="cand-btn cand-btn--primary">Enregistrer la formation</button>
            </div>
          </form>
        </details>
      </div>

      {{-- Attestations --}}
      <div id="attestations" class="cand-card">
        <div class="cand-card__head">
          <div class="cand-card__title">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Attestations & Certificats
          </div>
          <span class="cand-badge cand-badge--gray">{{ $profil->attestations->count() }}/10</span>
        </div>

        @if($profil->attestations->isNotEmpty())
        <div style="display:flex;flex-direction:column;gap:7px;margin-bottom:16px">
          @foreach($profil->attestations as $att)
          <div style="display:flex;align-items:center;gap:10px;padding:9px 12px;border:1px solid #e2e8f0;border-radius:8px;background:#f8fafc">
            <div style="width:30px;height:30px;background:#f0fdf4;border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
              @if($att->est_image)
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#38A169" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
              @else
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#38A169" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
              @endif
            </div>
            <div style="flex:1;min-width:0">
              <p style="font-size:13.5px;font-weight:600;color:#374151;margin:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $att->nom }}</p>
            </div>
            <a href="{{ asset('storage/'.$att->fichier) }}" target="_blank" class="cand-btn cand-btn--outline cand-btn--sm">Voir</a>
            <form method="POST" action="{{ route('talent.attestations.delete', $att) }}" onsubmit="return confirm('Supprimer ?')">
              @csrf @method('DELETE')
              <button type="submit" class="cand-btn cand-btn--danger cand-btn--sm">×</button>
            </form>
          </div>
          @endforeach
        </div>
        @endif

        @if($profil->attestations->count() < 10)
        <form method="POST" action="{{ route('talent.attestations.store') }}" enctype="multipart/form-data"
              style="border:2px dashed #d0d7e2;border-radius:9px;padding:14px;display:flex;flex-direction:column;gap:10px">
          @csrf
          <div class="cand-form-grid">
            <div class="cand-form-group">
              <label class="cand-form-label">Nom du document <span class="req">*</span></label>
              <input type="text" name="nom" value="{{ old('nom') }}" required maxlength="200"
                     placeholder="Ex : CAP Menuiserie, Attestation employeur…" class="cand-form-input">
              @error('nom')<p style="color:#e53e3e;font-size:11.5px;margin:3px 0 0">{{ $message }}</p>@enderror
            </div>
            <div class="cand-form-group">
              <label class="cand-form-label">Fichier <span class="req">*</span></label>
              <input type="file" name="fichier" required accept=".pdf,image/jpeg,image/png,image/webp" class="cand-form-input">
              @error('fichier')<p style="color:#e53e3e;font-size:11.5px;margin:3px 0 0">{{ $message }}</p>@enderror
            </div>
          </div>
          <p class="cand-form-hint">PDF, JPG, PNG, WebP — max 5 Mo</p>
          <div class="cand-form-actions">
            <button type="submit" class="cand-btn cand-btn--primary">Ajouter l'attestation</button>
          </div>
        </form>
        @else
        <p style="font-size:13px;color:#94a3b8;text-align:center;padding:10px">Limite de 10 fichiers atteinte.</p>
        @endif
      </div>

      {{-- Photos de travaux --}}
      <div id="travaux" class="cand-card">
        <div class="cand-card__head">
          <div class="cand-card__title">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
            Photos de travaux
          </div>
          <span class="cand-badge cand-badge--gray">{{ $profil->travaux->count() }}/8</span>
        </div>

        @if($profil->travaux->isNotEmpty())
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:10px;margin-bottom:16px">
          @foreach($profil->travaux as $t)
          <div style="position:relative;border-radius:9px;overflow:hidden;border:1px solid #e2e8f0;background:#f8fafc">
            <img src="{{ asset('storage/'.$t->photo) }}" alt="{{ $t->description ?? '' }}"
                 style="width:100%;height:110px;object-fit:cover;display:block">
            @if($t->description)
              <p style="font-size:11.5px;color:#64748b;margin:0;padding:5px 8px;line-height:1.4">{{ $t->description }}</p>
            @endif
            <form method="POST" action="{{ route('talent.profil.travaux.delete', $t) }}"
                  onsubmit="return confirm('Supprimer cette photo ?')"
                  style="position:absolute;top:5px;right:5px">
              @csrf @method('DELETE')
              <button type="submit" style="width:24px;height:24px;border-radius:50%;background:rgba(0,0,0,.55);border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;color:#fff;font-size:14px;line-height:1">×</button>
            </form>
          </div>
          @endforeach
        </div>
        @endif

        @if($profil->travaux->count() < 8)
        <form method="POST" action="{{ route('talent.profil.travaux.store') }}" enctype="multipart/form-data"
              id="travauxForm" style="border:2px dashed #d0d7e2;border-radius:9px;padding:14px">
          @csrf
          <div class="cand-form-group">
            <label class="cand-form-label">
              Ajouter des photos <span style="color:#94a3b8;font-weight:400">({{ 8 - $profil->travaux->count() }} restant{{ 8 - $profil->travaux->count() > 1 ? 'es' : 'e' }})</span>
            </label>
            <input type="file" name="photos[]" id="travauxInput" multiple accept="image/jpeg,image/png,image/webp"
                   class="cand-form-input" onchange="previewTravaux(this)">
            <p class="cand-form-hint">JPG, PNG, WebP — max 3 Mo par photo</p>
          </div>
          <div id="travauxPreviews" style="display:flex;flex-wrap:wrap;gap:10px;margin-bottom:12px"></div>
          <div id="travauxDescriptions" style="display:flex;flex-direction:column;gap:8px;margin-bottom:12px"></div>
          <button type="submit" id="travauxSubmit" style="display:none" class="cand-btn cand-btn--primary">
            Enregistrer les photos
          </button>
        </form>
        @else
        <p style="font-size:13px;color:#94a3b8;text-align:center;padding:12px">Limite de 8 photos atteinte — supprimez-en une pour en ajouter.</p>
        @endif
      </div>

    </div>{{-- fin colonne principale --}}

    {{-- ════ Sidebar sticky ════ --}}
    <div style="position:sticky;top:88px;display:flex;flex-direction:column;gap:12px">

      <div class="cand-card" style="margin-bottom:0">
        <div class="cand-card__head">
          <div class="cand-card__title">Sections</div>
        </div>
        <nav style="display:flex;flex-direction:column;gap:3px">
          @foreach([
            ['#specialite',    'Identité'],
            ['#bio',           'Présentation'],
            ['#competences',   'Compétences'],
            ['#experience',    'Expérience générale'],
            ['#disponibilite', 'Disponibilité & Contrat'],
            ['#langues',       'Langues'],
            ['#experiences',   'Expériences pro.'],
            ['#formations',    'Formations'],
            ['#attestations',  'Attestations'],
            ['#travaux',       'Photos de travaux'],
          ] as [$anchor, $lbl])
          <a href="{{ $anchor }}" style="display:block;padding:6px 9px;border-radius:6px;font-size:12.5px;color:#374151;text-decoration:none;font-weight:500;transition:background .15s" onmouseover="this.style.background='#f0fdf4'" onmouseout="this.style.background='transparent'">
            {{ $lbl }}
          </a>
          @endforeach
        </nav>
      </div>

      {{-- form= pointe sur talentEditForm (hors formulaire) --}}
      <button type="submit" form="talentEditForm" class="cand-btn cand-btn--primary" style="justify-content:center;padding:12px 18px;font-size:14px">
        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
        Enregistrer
      </button>

      <a href="{{ route('talent.profil') }}" class="cand-btn cand-btn--outline" style="justify-content:center">
        Annuler
      </a>

    </div>

  </div>{{-- fin grille --}}

@endsection

@section('scripts')
<script>
document.getElementById('bioInput')?.addEventListener('input', function() {
  document.getElementById('bioCount').textContent = this.value.length;
});

function previewPhoto(input) {
  if (!input.files[0]) return;
  const reader = new FileReader();
  reader.onload = e => {
    let img = document.getElementById('previewImg');
    if (!img) {
      const prev = document.getElementById('photoPreview');
      prev.innerHTML = '';
      img = document.createElement('img');
      img.id = 'previewImg';
      img.style.cssText = 'width:68px;height:68px;object-fit:cover';
      prev.appendChild(img);
    }
    img.src = e.target.result;
  };
  reader.readAsDataURL(input.files[0]);
}

// Compétences
let comps = [];
(function init() {
  const raw = document.getElementById('compHidden').value;
  comps = raw ? raw.split(',').map(c => c.trim()).filter(Boolean) : [];
  renderComps();
})();

function renderComps() {
  const list = document.getElementById('compList');
  list.innerHTML = '';
  comps.forEach((c, i) => {
    const pill = document.createElement('div');
    pill.style.cssText = 'display:inline-flex;align-items:center;gap:6px;background:#f0fdf4;border:1px solid #bbf7d0;color:#065f46;padding:5px 12px;border-radius:20px;font-size:13px;font-weight:500';
    pill.innerHTML = `<span>${escHtml(c)}</span><button type="button" onclick="removeComp(${i})" style="background:none;border:none;cursor:pointer;color:#94a3b8;font-size:14px;line-height:1;padding:0 0 0 2px">×</button>`;
    list.appendChild(pill);
  });
  document.getElementById('compHidden').value = comps.join(',');
}

function addComp() {
  const inp = document.getElementById('compInput');
  const val = inp.value.trim();
  if (!val) return;
  val.split(',').map(p => p.trim()).filter(Boolean).forEach(p => { if (!comps.includes(p)) comps.push(p); });
  inp.value = '';
  renderComps();
}
function removeComp(i) { comps.splice(i, 1); renderComps(); }
document.getElementById('compInput')?.addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); addComp(); } });

// Langues
let langs = [];
(function initLangs() {
  const raw = {!! json_encode($profil->langues ?? []) !!};
  langs = Array.isArray(raw) ? raw : [];
  renderLangs();
})();

function renderLangs() {
  const list = document.getElementById('langList');
  list.innerHTML = '';
  langs.forEach((l, i) => {
    const pill = document.createElement('div');
    pill.style.cssText = 'display:inline-flex;align-items:center;gap:6px;background:#f8fafc;border:1px solid #e2e8f0;color:#374151;padding:5px 12px;border-radius:20px;font-size:13px';
    pill.innerHTML = `<span>${escHtml(l.langue)}</span><span style="background:#e2e8f0;color:#64748b;padding:1px 7px;border-radius:10px;font-size:11px">${escHtml(l.niveau)}</span><button type="button" onclick="removeLang(${i})" style="background:none;border:none;cursor:pointer;color:#94a3b8;font-size:14px;line-height:1;padding:0 0 0 2px">×</button>`;
    list.appendChild(pill);
  });
  document.getElementById('langHidden').value = JSON.stringify(langs);
}

function addLang() {
  const lang   = document.getElementById('langInput').value.trim();
  const niveau = document.getElementById('langNiveau').value;
  if (!lang) return;
  if (!langs.find(l => l.langue.toLowerCase() === lang.toLowerCase())) langs.push({ langue: lang, niveau });
  document.getElementById('langInput').value = '';
  renderLangs();
}
function removeLang(i) { langs.splice(i, 1); renderLangs(); }
document.getElementById('langInput')?.addEventListener('keydown', e => { if (e.key === 'Enter') { e.preventDefault(); addLang(); } });

// Photos de travaux
function previewTravaux(input) {
  const previews     = document.getElementById('travauxPreviews');
  const descriptions = document.getElementById('travauxDescriptions');
  const submit       = document.getElementById('travauxSubmit');
  previews.innerHTML = '';
  descriptions.innerHTML = '';

  const files = Array.from(input.files).slice(0, {{ 8 - $profil->travaux->count() }});
  if (!files.length) { submit.style.display = 'none'; return; }

  files.forEach((file, i) => {
    const reader = new FileReader();
    reader.onload = e => {
      const wrap = document.createElement('div');
      wrap.style.cssText = 'position:relative;border-radius:8px;overflow:hidden;border:1px solid #e2e8f0;width:110px;flex-shrink:0';
      wrap.innerHTML = `<img src="${e.target.result}" style="width:110px;height:85px;object-fit:cover;display:block">`;
      previews.appendChild(wrap);
    };
    reader.readAsDataURL(file);

    const descWrap = document.createElement('div');
    descWrap.innerHTML = `
      <label class="cand-form-label" style="font-size:12px">Description photo ${i+1} <span style="color:#94a3b8;font-weight:400">(optionnel)</span></label>
      <input type="text" name="descriptions[]" maxlength="300" placeholder="Ex : Armoire fabriquée sur mesure"
             class="cand-form-input">
    `;
    descriptions.appendChild(descWrap);
  });

  submit.style.display = 'inline-flex';
}

function escHtml(str) {
  return String(str).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// Toast flash
const _SwalToast = Swal.mixin({
  toast: true, position: 'top-end', showConfirmButton: false,
  timer: 4000, timerProgressBar: true,
  didOpen: t => { t.onmouseenter = Swal.stopTimer; t.onmouseleave = Swal.resumeTimer; }
});
const fd = document.getElementById('flash-data');
if (fd) {
  let msg = fd.dataset.msg;
  try { msg = JSON.parse(msg); } catch(e) {}
  _SwalToast.fire({ icon: fd.dataset.type === 'error' ? 'error' : 'success', title: msg });
}
</script>
@endsection
