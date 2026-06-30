@extends('layouts.candidat')
@section('title', 'Mon profil | Emploi Bouge Bénin')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/candidat/profil.css') }}">
    @include('partials._jquery-cdn')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/i18n/fr.js"></script>
@endsection

@php
    $profil = $user->candidatProfil;
    $libelles = App\Models\CandidatProfil::libelles();
    $completion = $user->profilCompletion;
@endphp

@section('sidebar')
    @include('candidat._sidebar')
@endsection

@section('content')

    {{-- Header --}}
    <div class="cand-page-header">
        <div class="cand-page-header__left">
            <div class="cand-page-header__title">Mon profil</div>
            <div class="cand-page-header__sub">Complétez votre profil pour maximiser vos chances</div>
        </div>
    </div>

    @if(session('warning'))
    <div class="cp-depot-alert">
        <div class="cp-depot-alert__icon">
            <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <div class="cp-depot-alert__body">
            <div class="cp-depot-alert__title">Remplissez votre profil avant de déposer un CV</div>
            <div class="cp-depot-alert__text">Les recruteurs voient vos informations de profil. Un profil complet multiplie vos chances d'être contacté.</div>
        </div>
        <button onclick="openModal('modal-infos')" class="cand-btn cand-btn--yellow cand-btn--sm" style="flex-shrink:0">
            Compléter maintenant
        </button>
    </div>
    @endif

    {{-- Hero --}}
    <div class="cp-hero">
        <div class="cp-hero__banner"></div>
        <div class="cp-hero__body">

            <div class="cp-hero__avatar-wrap" id="avatar-wrap">
                @if ($user->avatar)
                    <img src="{{ Storage::url($user->avatar) }}" alt="" class="cp-hero__avatar"
                        id="avatar-preview-img">
                    <div class="cp-hero__avatar-initials" id="avatar-preview-initials" style="display:none">
                        {{ $user->initiale }}</div>
                @else
                    <img src="" alt="" class="cp-hero__avatar" id="avatar-preview-img" style="display:none">
                    <div class="cp-hero__avatar-initials" id="avatar-preview-initials">{{ $user->initiale }}</div>
                @endif
                <label for="avatar-upload-input" class="cp-hero__avatar-btn" id="avatar-btn" title="Changer la photo">
                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <circle cx="12" cy="13" r="3" />
                    </svg>
                </label>
                <input type="file" id="avatar-upload-input" accept="image/jpeg,image/png,image/webp"
                    style="display:none">
                <div id="avatar-uploading"
                    style="display:none;position:absolute;inset:0;background:rgba(0,0,0,.45);border-radius:50%;display:none;align-items:center;justify-content:center">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.5"
                        style="animation:spin 1s linear infinite">
                        <path
                            d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83" />
                    </svg>
                </div>
            </div>

            <div class="cp-hero__name">{{ $user->nomComplet }}</div>
            @if ($profil?->titre_professionnel)
                <div class="cp-hero__titre">{{ $profil->titre_professionnel }}</div>
            @endif


            <div class="cp-hero__meta">
                @if ($user->pays || $profil?->ville)
                    <span class="cp-hero__meta-item">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <circle cx="12" cy="11" r="3" />
                        </svg>
                        {{ collect([$profil?->ville, $user->pays])->filter()->join(', ') }}
                    </span>
                @endif
                @if ($user->tel)
                    <span class="cp-hero__meta-item">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        {{ $user->tel }}
                    </span>
                @endif
                @if ($profil?->disponibilite)
                    <span class="cp-hero__meta-item">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 2v4M8 2v4M3 10h18" />
                        </svg>
                        {{ $libelles['disponibilite'][$profil->disponibilite] ?? '' }}
                    </span>
                @endif
                @if ($profil?->annees_experience !== null)
                    <span class="cp-hero__meta-item">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <rect x="2" y="7" width="20" height="14" rx="2" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2" />
                        </svg>
                        {{ $profil->annees_experience }} an{{ $profil->annees_experience > 1 ? 's' : '' }} d'expérience
                    </span>
                @endif
            </div>

            @if ($profil?->bio)
                <p class="cp-hero__bio">{{ $profil->bio }}</p>
            @endif

            <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px">
                <div class="cp-hero__links">
                    @if ($profil?->linkedin)
                        <a href="{{ $profil->linkedin }}" target="_blank" rel="noopener" class="cp-hero__link">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                <path
                                    d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z" />
                                <circle cx="4" cy="4" r="2" />
                            </svg>
                            LinkedIn
                        </a>
                    @endif
                    @if ($profil?->portfolio)
                        <a href="{{ $profil->portfolio }}" target="_blank" rel="noopener" class="cp-hero__link">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                            </svg>
                            Portfolio
                        </a>
                    @endif
                </div>
                <button class="cand-btn cand-btn--outline cand-btn--sm" onclick="openModal('modal-infos')">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    Modifier le profil
                </button>
            </div>
        </div>
    </div>

    {{-- �.��.��.��.��.��.��.��.��.��.��.� MODALES �.��.��.��.��.��.��.��.��.��.��.� --}}

    {{-- Modale Infos perso --}}
    <div class="cp-modal-overlay" id="modal-infos">
        <div class="cp-modal cp-modal--wide">
            <div class="cp-modal__head">
                <div class="cp-modal__title">Modifier mon profil</div>
                <button class="cp-modal__close" onclick="closeModal('modal-infos')"><svg width="14" height="14"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg></button>
            </div>
            <div class="cp-modal__body">
                <form id="form-profil-infos" method="POST" action="{{ route('candidat.profil.update') }}">
                    @csrf @method('PUT')

                    {{-- SECTION 1 : Votre identité --}}
                    <div class="cp-form-section-head">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Votre identité
                    </div>

                    <div class="cand-form-grid">
                        <div class="cand-form-group">
                            <label class="cand-form-label" for="profil-prenom">Prénom <span class="req">*</span></label>
                            <input type="text" id="profil-prenom" name="prenom"
                                class="cand-form-input @error('prenom') field--invalid @enderror"
                                value="{{ old('prenom', $user->prenom) }}" required>
                            @error('prenom')<p class="field__server-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="cand-form-group">
                            <label class="cand-form-label" for="profil-nom">Nom <span class="req">*</span></label>
                            <input type="text" id="profil-nom" name="nom"
                                class="cand-form-input @error('nom') field--invalid @enderror"
                                value="{{ old('nom', $user->nom) }}" required>
                            @error('nom')<p class="field__server-error">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="cand-form-group">
                        <label class="cand-form-label">
                            Titre professionnel
                            <span class="cp-oblig-badge">Obligatoire</span>
                        </label>
                        <input type="text" name="titre_professionnel" class="cand-form-input"
                            placeholder="ex: Comptable, Juriste, Infirmier, Enseignant, Agent commercial..."
                            value="{{ old('titre_professionnel', $profil?->titre_professionnel) }}">
                        <div class="cand-form-hint">Indique aux recruteurs votre métier en un coup d'œil</div>
                    </div>

                    <div class="cand-form-group">
                        <label class="cand-form-label">
                            Résumé / Bio
                            <span class="cp-oblig-badge">Obligatoire</span>
                        </label>
                        <textarea name="bio" class="cand-form-textarea @error('bio') field--invalid @enderror" rows="4"
                            placeholder="Ex : Comptable avec 5 ans d'expérience à Cotonou, spécialisé en fiscalité OHADA. Je recherche un poste en entreprise ou ONG...">{{ old('bio', $profil?->bio) }}</textarea>
                        @error('bio')<p class="field__server-error">{{ $message }}</p>@enderror
                        <div class="cand-form-hint">Max 1 000 caractères</div>
                    </div>

                    {{-- SECTION 2 : Localisation & contact --}}
                    <div class="cp-form-section-head">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <circle cx="12" cy="11" r="3"/>
                        </svg>
                        Localisation & contact
                    </div>

                    <div class="cand-form-group">
                        <label class="cand-form-label">Pays</label>
                        <select name="pays" id="cand-modal-pays" class="cand-form-select">
                            <option value="">-- Sélectionnez votre pays --</option>
                            @foreach ($paysList as $p)
                                <option value="{{ $p }}" {{ old('pays', $user->pays) === $p ? 'selected' : '' }}>{{ $p }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="cand-form-grid">
                        <div class="cand-form-group">
                            <label class="cand-form-label">Ville <span class="cp-oblig-badge">Obligatoire</span></label>
                            <input type="text" name="ville" class="cand-form-input"
                                placeholder="ex: Cotonou, Abomey-Calavi, Porto-Novo, Parakou..."
                                value="{{ old('ville', $profil?->ville) }}">
                        </div>
                        <div class="cand-form-group">
                            <label class="cand-form-label">Téléphone</label>
                            <div style="display:flex;align-items:stretch">
                                <span id="cand-tel-prefix"
                                    style="display:flex;align-items:center;justify-content:center;padding:0 12px;background:#f1f5f9;border:1.5px solid #e2e8f0;border-right:none;border-radius:8px 0 0 8px;font-size:13.5px;font-weight:700;color:#042C53;white-space:nowrap;min-width:60px;user-select:none">+229</span>
                                <input type="text" name="tel" id="cand-tel-input" class="cand-form-input"
                                    style="border-radius:0 8px 8px 0!important;flex:1;min-width:0"
                                    value="{{ old('tel', $user->tel) ? preg_replace('/^\+\d+\s*/', '', old('tel', $user->tel)) : '' }}">
                            </div>
                        </div>
                    </div>

                    <div class="cp-modal__actions">
                        <button type="button" class="cand-btn cand-btn--outline"
                            onclick="closeModal('modal-infos')">Annuler</button>
                        <button type="submit" class="cand-btn cand-btn--yellow">Enregistrer les modifications</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Bouton dépôt CV --}}
    <div style="margin-top:32px;padding:24px 28px;background:#fff;border:2px dashed #cbd5e1;border-radius:16px;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap">
        <div>
            <div style="font-size:15px;font-weight:700;color:#042C53">Prêt à être visible des recruteurs ?</div>
            <div style="font-size:13px;color:#64748b;margin-top:3px">Déposez votre CV dans la CVthèque pour être contacté directement.</div>
        </div>
        <a href="{{ route('cv.public.depot') }}" class="cand-btn cand-btn--yellow" style="flex-shrink:0;display:inline-flex;align-items:center;gap:8px">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
            </svg>
            Déposer votre CV
        </a>
    </div>

@endsection

@section('scripts')
    <script>
        $('#cand-modal-metiers').select2({
            language: 'fr',
            placeholder: 'Rechercher un métier…',
            allowClear: true,
            width: '100%',
        });
        const CSRF = '{{ csrf_token() }}';
        let editingExpId = null,
            editingFormId = null;
        let expMissions = [],
            formActivites = [];

        function closeLightbox() {
            const lb = document.getElementById('lbOverlay');
            if (lb) lb.style.display = 'none';
        }

        function renderBulletList(containerId, items, removeFn) {
            const el = document.getElementById(containerId);
            if (!items.length) {
                el.innerHTML = '';
                return;
            }
            el.innerHTML = items.map((m, i) => `
    <div style="display:flex;align-items:center;gap:8px;padding:7px 10px;background:#f8fafc;border-radius:6px;margin-bottom:4px;border:1px solid #e8ecf0">
      <span style="color:#64748b;font-size:18px;line-height:1;flex-shrink:0">�?�</span>
      <span style="flex:1;font-size:13px;color:#1e293b">${m.replace(/</g,'&lt;')}</span>
      <button type="button" onclick="${removeFn}(${i})" style="background:none;border:none;cursor:pointer;color:#ef4444;font-size:18px;line-height:1;padding:0 2px;flex-shrink:0" title="Supprimer">�-</button>
    </div>`).join('');
        }

        function addExpMission() {
            const input = document.getElementById('exp-mission-input');
            const val = input.value.trim();
            if (!val || expMissions.length >= 20) return;
            expMissions.push(val);
            renderBulletList('exp-missions-list', expMissions, 'removeExpMission');
            input.value = '';
        }

        function removeExpMission(i) {
            expMissions.splice(i, 1);
            renderBulletList('exp-missions-list', expMissions, 'removeExpMission');
        }

        function addFormActivite() {
            const input = document.getElementById('form-activite-input');
            const val = input.value.trim();
            if (!val || formActivites.length >= 20) return;
            formActivites.push(val);
            renderBulletList('form-activites-list', formActivites, 'removeFormActivite');
            input.value = '';
        }

        function removeFormActivite(i) {
            formActivites.splice(i, 1);
            renderBulletList('form-activites-list', formActivites, 'removeFormActivite');
        }

        function openModal(id) {
            document.getElementById(id).classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('open');
            document.body.style.overflow = '';
        }
        document.querySelectorAll('.cp-modal-overlay').forEach(el => el.addEventListener('click', e => {
            if (e.target === el) closeModal(el.id);
        }));
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') document.querySelectorAll('.cp-modal-overlay.open').forEach(el => closeModal(el
                .id));
        });

        function toggleDateFin(p) {
            const checked = document.getElementById(p + '-en-cours').checked;
            const wrap = document.getElementById(p + '-date-fin-wrap');
            wrap.style.opacity = checked ? '.4' : '1';
            wrap.querySelector('input').disabled = checked;
        }
        // �"?�"? Toast SweetAlert2 �"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"?
        const _SwalToast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            didOpen: (t) => {
                t.onmouseenter = Swal.stopTimer;
                t.onmouseleave = Swal.resumeTimer;
            }
        });

        function showToast(msg, isErr = false) {
            _SwalToast.fire({
                icon: isErr ? 'error' : 'success',
                title: msg
            });
        }

        /* �"?�"? Upload avatar immédiat �"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"? */
        (function() {
            const input = document.getElementById('avatar-upload-input');
            const img = document.getElementById('avatar-preview-img');
            const initials = document.getElementById('avatar-preview-initials');
            const spinner = document.getElementById('avatar-uploading');
            const btn = document.getElementById('avatar-btn');

            if (!input) return;

            input.addEventListener('change', async function() {
                const file = this.files[0];
                if (!file) return;

                // Preview local immédiat
                const reader = new FileReader();
                reader.onload = e => {
                    img.src = e.target.result;
                    img.style.display = 'block';
                    if (initials) initials.style.display = 'none';
                };
                reader.readAsDataURL(file);

                // Upload AJAX
                btn.style.pointerEvents = 'none';
                spinner.style.display = 'flex';

                const fd = new FormData();
                fd.append('avatar', file);
                fd.append('_token', CSRF);

                try {
                    const res = await fetch('{{ route('candidat.profil.avatar.update') }}', {
                        method: 'POST',
                        body: fd
                    });
                    const data = await res.json();
                    if (res.ok && data.url) {
                        img.src = data.url;
                        showToast('Photo de profil mise à jour !');
                    } else {
                        showToast(data.message || 'Erreur lors de l\'upload.', true);
                    }
                } catch (e) {
                    showToast('Erreur réseau.', true);
                } finally {
                    spinner.style.display = 'none';
                    btn.style.pointerEvents = '';
                    input.value = '';
                }
            });
        })();

        async function ajax(url, method, body) {
            const opts = {
                method,
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'Accept': 'application/json'
                }
            };
            if (method !== 'DELETE' && method !== 'GET') {
                opts.headers['Content-Type'] = 'application/json';
                opts.body = JSON.stringify(body);
            }
            const r = await fetch(url, opts);
            let data = {};
            try {
                data = await r.json();
            } catch (e) {}
            return {
                ok: r.ok,
                status: r.status,
                data
            };
        }
        async function deleteItem(type, id, elId) {
            const labels = {
                experiences: 'cette expérience',
                formations: 'cette formation',
                competences: 'cette compétence',
                langues: 'cette langue'
            };
            const {
                isConfirmed
            } = await Swal.fire({
                title: 'Supprimer ' + (labels[type] ?? 'cet élément') + ' ?',
                text: 'Cette action est irréversible.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Oui, supprimer',
                cancelButtonText: 'Annuler',
                reverseButtons: true,
                focusCancel: true,
            });
            if (!isConfirmed) return;
            const map = {
                experiences: `/candidat/profil/experiences/${id}`,
                formations: `/candidat/profil/formations/${id}`,
                competences: `/candidat/profil/competences/${id}`,
                langues: `/candidat/profil/langues/${id}`
            };
            ajax(map[type], 'DELETE').then(({
                ok,
                status,
                data
            }) => {
                if (ok) {
                    const el = document.getElementById(elId);
                    el.style.transition = 'opacity .3s';
                    el.style.opacity = '0';
                    setTimeout(() => el.remove(), 300);
                    showToast('Supprimé avec succès');
                } else {
                    showToast(status === 403 ? 'Action non autorisée.' : (data.message ??
                        'Erreur lors de la suppression.'), true);
                }
            }).catch(() => showToast('Erreur réseau, réessayez.', true));
        }

        // Expériences
        function openExpModal() {
            editingExpId = null;
            expMissions = [];
            document.getElementById('modal-exp-title').textContent = 'Ajouter une expérience';
            ['exp-poste', 'exp-entreprise', 'exp-lieu', 'exp-secteur', 'exp-date-debut', 'exp-date-fin',
                'exp-mission-input'
            ].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.value = '';
            });
            document.getElementById('exp-en-cours').checked = false;
            toggleDateFin('exp');
            renderBulletList('exp-missions-list', expMissions, 'removeExpMission');
            openModal('modal-exp');
        }

        function editExp(id, d) {
            editingExpId = id;
            expMissions = d.missions ?? [];
            document.getElementById('modal-exp-title').textContent = "Modifier l'expérience";
            document.getElementById('exp-poste').value = d.poste;
            document.getElementById('exp-entreprise').value = d.entreprise;
            document.getElementById('exp-lieu').value = d.lieu ?? '';
            document.getElementById('exp-secteur').value = d.secteur ?? '';
            document.getElementById('exp-date-debut').value = d.date_debut?.substring(0, 10) ?? '';
            document.getElementById('exp-date-fin').value = d.date_fin?.substring(0, 10) ?? '';
            document.getElementById('exp-en-cours').checked = !!d.en_cours;
            document.getElementById('exp-mission-input').value = '';
            renderBulletList('exp-missions-list', expMissions, 'removeExpMission');
            toggleDateFin('exp');
            openModal('modal-exp');
        }
        async function saveExp() {
            const body = {
                poste: document.getElementById('exp-poste').value,
                entreprise: document.getElementById('exp-entreprise').value,
                lieu: document.getElementById('exp-lieu').value,
                secteur: document.getElementById('exp-secteur').value,
                date_debut: document.getElementById('exp-date-debut').value,
                date_fin: document.getElementById('exp-date-fin').value,
                en_cours: document.getElementById('exp-en-cours').checked,
                missions: expMissions
            };
            const {
                ok,
                data
            } = await ajax(editingExpId ? `/candidat/profil/experiences/${editingExpId}` :
                '/candidat/profil/experiences', editingExpId ? 'PUT' : 'POST', body);
            if (!ok) {
                showToast(data.errors ? Object.values(data.errors).flat().join(', ') : (data.message ?? 'Erreur.'),
                    true);
                return;
            }
            sessionStorage.setItem('_toast', JSON.stringify({
                msg: editingExpId ? 'Expérience mise à jour !' : 'Expérience ajoutée !',
                err: false
            }));
            closeModal('modal-exp');
            location.reload();
        }

        // Formations
        function openFormModal() {
            editingFormId = null;
            formActivites = [];
            document.getElementById('modal-form-title').textContent = 'Ajouter une formation';
            ['form-diplome', 'form-etablissement', 'form-domaine', 'form-date-debut', 'form-date-fin',
                'form-activite-input'
            ].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.value = '';
            });
            document.getElementById('form-en-cours').checked = false;
            toggleDateFin('form');
            renderBulletList('form-activites-list', formActivites, 'removeFormActivite');
            openModal('modal-form');
        }

        function editForm(id, d) {
            editingFormId = id;
            formActivites = d.activites ?? [];
            document.getElementById('modal-form-title').textContent = 'Modifier la formation';
            document.getElementById('form-diplome').value = d.diplome;
            document.getElementById('form-etablissement').value = d.etablissement;
            document.getElementById('form-domaine').value = d.domaine ?? '';
            document.getElementById('form-date-debut').value = d.date_debut?.substring(0, 10) ?? '';
            document.getElementById('form-date-fin').value = d.date_fin?.substring(0, 10) ?? '';
            document.getElementById('form-en-cours').checked = !!d.en_cours;
            document.getElementById('form-activite-input').value = '';
            renderBulletList('form-activites-list', formActivites, 'removeFormActivite');
            toggleDateFin('form');
            openModal('modal-form');
        }
        async function saveForm() {
            const body = {
                diplome: document.getElementById('form-diplome').value,
                etablissement: document.getElementById('form-etablissement').value,
                domaine: document.getElementById('form-domaine').value,
                date_debut: document.getElementById('form-date-debut').value,
                date_fin: document.getElementById('form-date-fin').value,
                en_cours: document.getElementById('form-en-cours').checked,
                activites: formActivites
            };
            const {
                ok,
                data
            } = await ajax(editingFormId ? `/candidat/profil/formations/${editingFormId}` :
                '/candidat/profil/formations', editingFormId ? 'PUT' : 'POST', body);
            if (!ok) {
                showToast(data.errors ? Object.values(data.errors).flat().join(', ') : (data.message ?? 'Erreur.'),
                    true);
                return;
            }
            sessionStorage.setItem('_toast', JSON.stringify({
                msg: editingFormId ? 'Formation mise à jour !' : 'Formation ajoutée !',
                err: false
            }));
            closeModal('modal-form');
            location.reload();
        }

        // Compétences
        async function saveComp() {
            const competenceId = document.getElementById('comp-competence-id').value;
            if (!competenceId) {
                showToast('Veuillez sélectionner une compétence.', true);
                return;
            }
            const annees = document.getElementById('comp-annees').value;
            const {
                ok,
                data
            } = await ajax('/candidat/profil/competences', 'POST', {
                competence_id: competenceId,
                annees_experience: annees !== '' ? parseInt(annees) : null,
            });
            if (!ok) {
                showToast(data.message ?? 'Erreur.', true);
                return;
            }
            const c = data.competence;
            const empty = document.getElementById('comp-empty');
            if (empty) empty.remove();
            const chip = document.createElement('span');
            chip.className = 'cp-chip';
            chip.id = `comp-item-${c.id}`;
            const anneesHtml = c.annees_experience ?
                `<small style="opacity:.65;margin-left:3px">${c.annees_experience} an(s)</small>` :
                '';
            chip.innerHTML =
                `${c.nom}${anneesHtml}<button class="cp-chip__del" onclick="deleteItem('competences',${c.id},'comp-item-${c.id}')" title="Supprimer"><svg width="8" height="8" viewBox="0 0 12 12" fill="none"><path d="M1 1l10 10M11 1L1 11" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></button>`;
            document.getElementById('comp-list').appendChild(chip);
            document.getElementById('comp-competence-id').value = '';
            document.getElementById('comp-annees').value = '';
            showToast('Compétence ajoutée !');
        }

        // �"?�"? Filtrage du select compétences selon métiers cochés �"?�"?
        (function() {
            const metiersCompetences = @json($metiersCompetencesJson);
            const allCompetences = @json($competences->map(fn($c) => ['id' => $c->id, 'nom' => $c->nom])->values());

            // Construire un index competence_id �?' [metier_ids]
            const compToMetiers = {};
            Object.entries(metiersCompetences).forEach(([metierId, comps]) => {
                comps.forEach(c => {
                    if (!compToMetiers[c.id]) compToMetiers[c.id] = {
                        nom: c.nom,
                        metiers: []
                    };
                    compToMetiers[c.id].metiers.push(parseInt(metierId));
                });
            });

            const compSelect = document.getElementById('comp-competence-id');

            function getSelectedMetierIds() {
                const filter = document.getElementById('comp-metier-filter');
                if (filter && filter.value) return [parseInt(filter.value)];
                const select = document.getElementById('cand-modal-metiers');
                if (!select) return [];
                return Array.from(select.selectedOptions).map(el => parseInt(el.value));
            }

            function refreshCompSelect() {
                const selectedMetiers = getSelectedMetierIds();
               
                const currentVal = compSelect.value;


                // Vider et reconstruire les options
                compSelect.innerHTML = '<option value="">-- Choisir une compétence --</option>';

                let options = [];

                if (selectedMetiers.length === 0) {
                    // Aucun filtre métier �?' toutes les compétences
                    options = [...allCompetences].sort((a, b) => a.nom.localeCompare(b.nom));
                } else {
                    // Compétences liées aux métiers sélectionnés
                    const seen = new Set();
                    selectedMetiers.forEach(mid => {
                        (metiersCompetences[mid] ?? []).forEach(c => {
                            if (!seen.has(c.id)) { seen.add(c.id); options.push(c); }
                        });
                    });
                    options.sort((a, b) => a.nom.localeCompare(b.nom));

                    if (options.length === 0) {
                        // Métier sans compétences liées �?' fallback toutes
                        options = [...allCompetences].sort((a, b) => a.nom.localeCompare(b.nom));
                    }
                }

                const seen2 = new Set();
                options.forEach(c => {
                    if (seen2.has(c.id)) return;
                    seen2.add(c.id);
                    const opt = document.createElement('option');
                    opt.value = c.id;
                    opt.textContent = c.nom;
                    compSelect.appendChild(opt);
                });

                // Restaurer la sélection si encore valide
                if (currentVal && seen2.has(parseInt(currentVal))) {
                    compSelect.value = currentVal;
                }
            }

            // �?couter le filtre métier dans modal-comp uniquement
            document.addEventListener('change', function(e) {
                if (e.target.matches('#comp-metier-filter') || e.target.matches('input[name="metiers_ids[]"]')) {
                    refreshCompSelect();
                }
            });

            // Peupler quand on ouvre la modale compétences
            const origOpenModal = window.openModal;
            window.openModal = function(id) {
                origOpenModal(id);
                if (id === 'modal-comp') refreshCompSelect();
            };
        })();
        // Langues
        async function saveLang() {
            const btn = document.getElementById('btn-save-lang');
            if (btn.disabled) return;
            btn.disabled = true;
            const {
                ok,
                data
            } = await ajax('/candidat/profil/langues', 'POST', {
                langue_id: document.getElementById('lang-langue').value,
                niveau_id: document.querySelector('input[name="lang-niveau-radio"]:checked')?.value ?? ''
            });
            btn.disabled = false;
            if (!ok) {
                showToast(data.message ?? 'Erreur.', true);
                return;
            }
            try {
                const l = data.langue;
                const empty = document.getElementById('lang-empty');
                if (empty) empty.remove();
                const row = document.createElement('div');
                row.className = 'cp-langue';
                row.id = `lang-item-${l.id}`;
                row.innerHTML =
                    `<div class="cp-langue__left"><div class="cp-langue__flag"><svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg></div><div><div class="cp-langue__nom">${l.langue}</div><div class="cp-langue__niveau">${l.niveau}</div></div></div><button class="cand-btn cand-btn--danger cand-btn--sm cand-btn--icon-only" onclick="deleteItem('langues',${l.id},'lang-item-${l.id}')" title="Supprimer"><svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>`;
                document.getElementById('lang-list').appendChild(row);
                const langSelect = document.getElementById('lang-langue');
                const addedOption = langSelect.querySelector(`option[value="${l.langue_id}"]`);
                if (addedOption) addedOption.remove();
                langSelect.value = '';
                document.querySelectorAll('input[name="lang-niveau-radio"]').forEach(r => r.checked = false);
                showToast('Langue ajoutée !');
                closeModal('modal-lang');
            } catch (e) {
                showToast('Langue ajoutée ! (rechargez la page)', false);
                console.error('saveLang UI error:', e);
            }
        }

        // Messages après reload
        const _pt = sessionStorage.getItem('_toast');
        if (_pt) {
            const {
                msg,
                err
            } = JSON.parse(_pt);
            sessionStorage.removeItem('_toast');
            showToast(msg, err);
        }
        const _flash = document.getElementById('flash-data');
        if (_flash) showToast(_flash.dataset.msg, _flash.dataset.type === 'error');
        @if ($errors->any())
            openModal('modal-infos');
            showToast({{ Js::from(implode(', ', $errors->all())) }}, true);
        @endif

        // �"?�"? Multi-select : Spécialité / Domaine d'expertise �"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"?�"?
        (function () {
            function msEsc(str) {
                const d = document.createElement('div');
                d.appendChild(document.createTextNode(String(str)));
                return d.innerHTML;
            }

            const select  = document.getElementById('cand-modal-metiers');
            const wrap    = document.getElementById('ms-metiers-wrap');
            const trigger = document.getElementById('ms-metiers-trigger');
            const chips   = document.getElementById('ms-metiers-chips');
            const ph      = document.getElementById('ms-metiers-ph');
            if (!select || !wrap) return;

            // Dropdown porté dans <body> pour passer au-dessus du modal
            const drop = document.createElement('div');
            drop.className = 'ms-drop ss-drop';
            drop.innerHTML =
                '<input type="text" class="ss-search" placeholder="Rechercher un métier�?�" autocomplete="off">' +
                '<ul class="ss-list" role="listbox"></ul>';
            document.body.appendChild(drop);
            const srch = drop.querySelector('.ss-search');
            const list = drop.querySelector('.ss-list');

            let isOpen = false;

            function getSelected() {
                return Array.from(select.options).filter(o => o.selected);
            }

            function renderChips() {
                chips.innerHTML = '';
                const sel = getSelected();
                ph.style.display = sel.length ? 'none' : '';
                sel.forEach(opt => {
                    const chip = document.createElement('span');
                    chip.className = 'tag-chip';
                    chip.innerHTML =
                        msEsc(opt.textContent.trim()) +
                        '<button type="button" class="tag-chip__remove ms-remove" data-val="' + opt.value + '" aria-label="Retirer">' +
                        '<svg width="8" height="8" viewBox="0 0 12 12" fill="none"><path d="M1 1l10 10M11 1L1 11" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>' +
                        '</button>';
                    chips.appendChild(chip);
                });
            }

            function buildList(q) {
                list.innerHTML = '';
                q = (q || '').toLowerCase().trim();
                let count = 0;
                Array.from(select.options).forEach(opt => {
                    const txt = opt.textContent.trim();
                    if (q && txt.toLowerCase().indexOf(q) === -1) return;
                    const li = document.createElement('li');
                    const sel = opt.selected;
                    li.className = 'ss-option ms-option' + (sel ? ' ss-option--sel' : '');
                    li.setAttribute('role', 'option');
                    li.setAttribute('aria-selected', sel);
                    const checkSvg = sel
                        ? '<svg class="ms-check" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"><polyline points="20 6 9 17 4 12"/></svg>'
                        : '<svg class="ms-check ms-check--empty" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="3"/></svg>';
                    if (q) {
                        const i = txt.toLowerCase().indexOf(q);
                        li.innerHTML = checkSvg + msEsc(txt.slice(0, i)) + '<mark>' + msEsc(txt.slice(i, i + q.length)) + '</mark>' + msEsc(txt.slice(i + q.length));
                    } else {
                        li.innerHTML = checkSvg + msEsc(txt);
                    }
                    li.addEventListener('mousedown', e => {
                        e.preventDefault();
                        opt.selected = !opt.selected;
                        renderChips();
                        buildList(srch.value);
                        select.dispatchEvent(new Event('change', { bubbles: true }));
                    });
                    list.appendChild(li);
                    count++;
                });
                if (!count) {
                    const emp = document.createElement('li');
                    emp.className = 'ss-empty';
                    emp.textContent = 'Aucun résultat';
                    list.appendChild(emp);
                }
            }

            function positionDrop() {
                const r = wrap.getBoundingClientRect();
                Object.assign(drop.style, {
                    position: 'fixed',
                    zIndex:   '9999',
                    top:      (r.bottom + 4) + 'px',
                    left:     r.left + 'px',
                    width:    r.width + 'px',
                    minWidth: '220px',
                });
            }

            function open() {
                if (isOpen) return;
                isOpen = true;
                positionDrop();
                drop.classList.add('ss-drop--open');
                trigger.classList.add('ms-trigger--open');
                trigger.setAttribute('aria-expanded', 'true');
                srch.value = '';
                buildList('');
                setTimeout(() => srch.focus(), 0);
            }

            function close() {
                if (!isOpen) return;
                isOpen = false;
                drop.classList.remove('ss-drop--open');
                trigger.classList.remove('ms-trigger--open');
                trigger.setAttribute('aria-expanded', 'false');
            }

            trigger.addEventListener('click', e => {
                if (e.target.closest('.ms-remove')) return;
                isOpen ? close() : open();
            });

            chips.addEventListener('click', e => {
                const btn = e.target.closest('.ms-remove');
                if (!btn) return;
                const opt = Array.from(select.options).find(o => o.value === btn.dataset.val);
                if (opt) opt.selected = false;
                renderChips();
                if (isOpen) buildList(srch.value);
                select.dispatchEvent(new Event('change', { bubbles: true }));
            });

            trigger.addEventListener('keydown', e => {
                if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); open(); }
                if (e.key === 'Escape') close();
                if (e.key === 'ArrowDown' && !isOpen) open();
            });

            srch.addEventListener('keydown', e => {
                if (e.key === 'Escape') { close(); trigger.focus(); }
            });
            srch.addEventListener('input', () => buildList(srch.value));

            document.addEventListener('click', e => {
                if (!wrap.contains(e.target) && !drop.contains(e.target)) close();
            });
            document.addEventListener('focusin', e => {
                if (!wrap.contains(e.target) && !drop.contains(e.target)) close();
            });
            window.addEventListener('scroll', () => { if (isOpen) positionDrop(); }, { passive: true });
            window.addEventListener('resize', () => { if (isOpen) positionDrop(); });

            // Fermer le dropdown quand n'importe quel modal se ferme
            const _origCloseModal = window.closeModal;
            window.closeModal = function (id) {
                _origCloseModal(id);
                close();
            };

            renderChips();
        })();
    </script>
    <style>
        /* �"?�"? Multi-select métiers �"?�"? */
        .ms-wrap { position: relative; width: 100%; }

        .tag-chip {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            background: #ebf4fd;
            color: #185FA5;
            font-size: 12px;
            font-weight: 600;
            padding: 2px 8px 2px 10px;
            border-radius: 20px;
            white-space: nowrap;
        }
        .tag-chip__remove {
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            display: flex;
            align-items: center;
            color: #185FA5;
            opacity: .7;
        }
        .tag-chip__remove:hover { opacity: 1; }

        .ms-trigger {
            display: flex;
            cursor: pointer;
            min-height: 40px;
            align-items: center;
            padding: 6px 10px;
            flex-wrap: wrap;
            gap: 5px;
        }
        .ms-trigger--open {
            border-color: #185FA5;
            box-shadow: 0 0 0 3px rgba(24,95,165,.1);
        }
        .ms-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            flex: 1;
        }
        .ms-placeholder {
            color: #94a3b8;
            font-size: 13.5px;
            line-height: 1.8;
            flex: 1;
        }
        .ms-chevron {
            flex-shrink: 0;
            color: #6b7280;
            margin-left: 4px;
            margin-top: 5px;
            transition: transform .18s;
            align-self: flex-start;
        }
        .ms-trigger--open .ms-chevron { transform: rotate(180deg); }

        .ss-drop {
            display: none;
            background: #fff;
            border: 1px solid #dde2ea;
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(4,44,83,.12);
            overflow: hidden;
        }
        .ss-drop--open { display: block; }
        .ss-search {
            width: 100%;
            border: none;
            border-bottom: 1px solid #f0f2f5;
            padding: 9px 14px;
            font-size: 13px;
            outline: none;
            color: #042C53;
            box-sizing: border-box;
        }
        .ss-list {
            list-style: none;
            margin: 0;
            padding: 4px 0;
            max-height: 220px;
            overflow-y: auto;
        }
        .ss-option {
            padding: 9px 14px;
            cursor: pointer;
            font-size: 13.5px;
            color: #042C53;
        }
        .ss-empty {
            padding: 10px 14px;
            color: #94a3b8;
            font-size: 13px;
        }
        .ms-drop { background: #fff; }
        .ms-drop .ss-list { background: #fff; }
        .ms-drop .ss-option {
            background: #fff;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .ms-drop .ss-option:hover { background: #f0f7ff; color: #185FA5; }
        .ms-drop .ss-option--sel { background: #ebf4fd; color: #185FA5; }
        .ms-check {
            flex-shrink: 0;
            color: #185FA5;
        }
        .ms-check--empty { color: #cbd5e1; }
        .ss-option--sel .ms-check--empty { display: none; }

        /* Spécialité hero */
        .cp-hero__specialite {
            font-size: 13px;
            color: #64748b;
            margin-top: 2px
        }

        /* Attestations */
        .cp-att-item {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 9px 0;
            border-bottom: 1px solid #f0f2f5
        }

        .cp-att-item:last-child {
            border-bottom: none
        }

        .cp-att-link {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
            text-decoration: none;
            min-width: 0;
            padding: 2px 8px 2px 0
        }

        .cp-att-icon {
            width: 30px;
            height: 30px;
            background: #f0fdf4;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0
        }

        .cp-att-name {
            font-size: 13.5px;
            font-weight: 600;
            color: #374151;
            flex: 1;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap
        }

        /* Photos de travaux */
        .cp-travaux-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 10px
        }

        .cp-travail-item {
            border-radius: 9px;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            position: relative
        }

        .cp-travail-img {
            cursor: pointer
        }

        .cp-travail-img img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            display: block
        }

        .cp-travail-desc {
            font-size: 12px;
            color: #64748b;
            margin: 0;
            padding: 6px 8px 6px;
            line-height: 1.4
        }

        .cp-travail-del {
            position: absolute;
            top: 6px;
            right: 6px
        }

        @media(max-width:960px) {
            .cp-grid {
                grid-template-columns: 1fr !important
            }
        }

        .cp-timeline__bullets {
            margin: 6px 0 0 0;
            padding: 0;
            list-style: none
        }

        .cp-timeline__bullets li {
            position: relative;
            padding-left: 14px;
            font-size: 13px;
            color: #475569;
            line-height: 1.5;
            margin-bottom: 3px
        }

        .cp-timeline__bullets li::before {
            content: "�?�";
            position: absolute;
            left: 0;
            color: #94a3b8
        }

        /* Checklist complétion */
        .cp-completion__checklist {
            margin-top: 12px;
            border-top: 1px solid #e8ecf0;
            padding-top: 12px
        }

        .cp-completion__checklist-title {
            font-size: 12px;
            font-weight: 600;
            color: #f59e0b;
            display: flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 10px
        }

        .cp-checklist-grid {
            display: flex;
            flex-direction: column;
            gap: 6px
        }

        .cp-check-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            border-radius: 8px;
            transition: background .15s
        }

        .cp-check-item--miss {
            background: #fffbeb;
            border: 1px solid #fde68a;
            cursor: pointer
        }

        .cp-check-item--miss:hover {
            background: #fef3c7
        }

        .cp-check-item--done {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            cursor: default
        }

        .cp-check-icon {
            width: 22px;
            height: 22px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0
        }

        .cp-check-icon--miss {
            background: #fef3c7;
            color: #d97706
        }

        .cp-check-icon--done {
            background: #dcfce7;
            color: #16a34a
        }

        .cp-check-body {
            flex: 1;
            min-width: 0
        }

        .cp-check-label {
            font-size: 13px;
            font-weight: 600;
            color: #1e293b
        }

        .cp-check-hint {
            font-size: 11.5px;
            color: #64748b;
            margin-top: 1px
        }

        .cp-check-pts {
            font-size: 12px;
            font-weight: 700;
            flex-shrink: 0;
            white-space: nowrap
        }

        .cp-check-item--miss .cp-check-pts {
            color: #d97706
        }

        .cp-check-pts--done {
            color: #16a34a
        }

        .cp-checklist-done {
            margin-top: 8px
        }

        .cp-checklist-done summary {
            font-size: 12px;
            color: #94a3b8;
            cursor: pointer;
            user-select: none;
            list-style: none;
            display: flex;
            align-items: center;
            gap: 4px
        }

        .cp-checklist-done summary::before {
            content: "�-�";
            font-size: 9px;
            transition: transform .2s
        }

        .cp-checklist-done[open] summary::before {
            transform: rotate(90deg)
        }
    </style>

    <script src="{{ asset('js/tel-field.js') }}"></script>
    <script>
        initTelField('cand-modal-pays', 'cand-tel-prefix', 'cand-tel-input');
    </script>

@endsection
