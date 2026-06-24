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

    {{-- Barre de complétion --}}
    <div class="cp-completion">
        <div class="cp-completion__head">
            <span class="cp-completion__label">Complétion du profil</span>
            <span class="cp-completion__pct">{{ $completion }}%</span>
        </div>
        <div class="cp-completion__bar">
            <div class="cp-completion__fill {{ $completion >= 80 ? 'cp-completion__fill--high' : ($completion >= 50 ? '' : 'cp-completion__fill--mid') }}"
                style="width:{{ $completion }}%"></div>
        </div>
        @php
            $checks = [
                [
                    'done' => !!$user->avatar,
                    'label' => 'Photo de profil',
                    'hint' => 'Un profil avec photo reçoit 3× plus de vues',
                    'pts' => 10,
                    'action' => "document.getElementById('avatar-btn').click()",
                ],
                [
                    'done' => !!$profil?->titre_professionnel,
                    'label' => 'Titre professionnel',
                    'hint' => 'Indique aux recruteurs votre métier en un coup d\'œil',
                    'pts' => 10,
                    'action' => "openModal('modal-infos')",
                ],
                [
                    'done' => !!$profil?->bio,
                    'label' => 'Bio / Résumé',
                    'hint' => 'Présentez votre parcours et vos ambitions (max 1 000 car.)',
                    'pts' => 10,
                    'action' => "openModal('modal-infos')",
                ],
                [
                    'done' => $user->experiences->isNotEmpty(),
                    'label' => 'Expérience professionnelle',
                    'hint' => 'Ajoutez au moins 1 poste occupé',
                    'pts' => 25,
                    'action' => 'openExpModal()',
                ],
                [
                    'done' => $user->formations->isNotEmpty(),
                    'label' => 'Formation / Diplôme',
                    'hint' => 'Ajoutez au moins 1 diplôme ou formation',
                    'pts' => 15,
                    'action' => 'openFormModal()',
                ],
                [
                    'done' => $user->competences->count() >= 3,
                    'label' => 'Compétences (min. 3)',
                    'hint' => 'Vous en avez ' . $user->competences->count() . '/3, ajoutez les vôtres',
                    'pts' => 10,
                    'action' => "openModal('modal-comp')",
                ],
                [
                    'done' => !!$profil?->disponibilite,
                    'label' => 'Disponibilité',
                    'hint' => 'Quand pouvez-vous commencer ? Les recruteurs filtrent par là',
                    'pts' => 5,
                    'action' => "openModal('modal-infos')",
                ],
                [
                    'done' => $user->typesContrats->isNotEmpty(),
                    'label' => 'Types de contrat',
                    'hint' => 'CDI, CDD, Freelance… indiquez vos préférences',
                    'pts' => 5,
                    'action' => "openModal('modal-infos')",
                ],
                [
                    'done' => !!$profil?->ville,
                    'label' => 'Ville de résidence',
                    'hint' => 'Permet aux recruteurs locaux de vous trouver',
                    'pts' => 5,
                    'action' => "openModal('modal-infos')",
                ],
                [
                    'done' => $user->langues->isNotEmpty(),
                    'label' => 'Langue maîtrisée',
                    'hint' => 'Ajoutez au moins 1 langue (français, anglais…)',
                    'pts' => 5,
                    'action' => "openModal('modal-lang')",
                ],
            ];
            $missing = collect($checks)->where('done', false);
            $done = collect($checks)->where('done', true);
        @endphp

        @if ($completion < 100)
            <div class="cp-completion__checklist">
                {{-- Titre section --}}
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px">
                    <div style="display:flex;align-items:center;gap:8px">
                        <span style="display:inline-flex;align-items:center;justify-content:center;width:24px;height:24px;background:#FEF3C7;border-radius:50%">
                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="#D97706" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                        </span>
                        <span style="font-size:13px;font-weight:700;color:#042C53">{{ $missing->count() }} section{{ $missing->count() > 1 ? 's' : '' }} à compléter</span>
                    </div>
                    <span style="font-size:11.5px;color:#94a3b8;font-weight:500">{{ $completion }}% complété</span>
                </div>

                {{-- Cartes manquantes --}}
                <div style="display:flex;flex-direction:column;gap:8px">
                    @foreach ($missing as $item)
                        <div onclick="{{ $item['action'] }}" role="button" tabindex="0"
                             style="display:flex;align-items:center;gap:12px;padding:12px 14px;background:#fff;border:1.5px solid #FDE68A;border-radius:10px;cursor:pointer;transition:border-color .15s,box-shadow .15s"
                             onmouseover="this.style.borderColor='#F59E0B';this.style.boxShadow='0 2px 8px rgba(245,158,11,.15)'"
                             onmouseout="this.style.borderColor='#FDE68A';this.style.boxShadow='none'">
                            <span style="flex-shrink:0;width:32px;height:32px;border-radius:8px;background:#FEF9C3;display:flex;align-items:center;justify-content:center">
                                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="#D97706" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v8m0 4h.01"/></svg>
                            </span>
                            <div style="flex:1;min-width:0">
                                <div style="font-size:13px;font-weight:700;color:#042C53;margin-bottom:2px">{{ $item['label'] }}</div>
                                <div style="font-size:11.5px;color:#64748b">{{ $item['hint'] }}</div>
                            </div>
                            <span style="flex-shrink:0;font-size:11.5px;font-weight:700;color:#D97706;background:#FEF3C7;border-radius:6px;padding:3px 10px;white-space:nowrap">Compléter →</span>
                        </div>
                    @endforeach
                </div>

                {{-- Éléments déjà complétés --}}
                @if ($done->isNotEmpty())
                    <details style="margin-top:10px">
                        <summary style="font-size:12px;color:#94a3b8;cursor:pointer;user-select:none;list-style:none;display:flex;align-items:center;gap:5px">
                            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            {{ $done->count() }} section{{ $done->count() > 1 ? 's' : '' }} déjà complété{{ $done->count() > 1 ? 'es' : 'e' }}
                        </summary>
                        <div style="display:flex;flex-direction:column;gap:6px;margin-top:8px">
                            @foreach ($done as $item)
                                <div style="display:flex;align-items:center;gap:10px;padding:10px 14px;background:#f0fdf4;border:1.5px solid #BBF7D0;border-radius:10px">
                                    <span style="flex-shrink:0;width:28px;height:28px;border-radius:8px;background:#DCFCE7;display:flex;align-items:center;justify-content:center">
                                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="#16A34A" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                    </span>
                                    <span style="font-size:12.5px;font-weight:600;color:#166534">{{ $item['label'] }}</span>
                                    <span style="margin-left:auto;font-size:11px;color:#16A34A;font-weight:700">✓ Fait</span>
                                </div>
                            @endforeach
                        </div>
                    </details>
                @endif
            </div>
        @else
            <div style="text-align:center;padding:10px 0 4px;font-size:13px;color:#16a34a;font-weight:600">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2.5" style="vertical-align:-2px">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                Profil 100% complet, félicitations !
            </div>
        @endif
    </div>

    {{-- Statut CV ──────────────────────────────────────────────────── --}}
    @php $cvVisible = $cv && $cv->visible; @endphp
    <div class="cp-cv-status {{ $cvVisible ? 'cp-cv-status--visible' : 'cp-cv-status--hidden' }}">
        <div class="cp-cv-status__icon">
            @if($cvVisible)
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
            @else
                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                </svg>
            @endif
        </div>
        <div class="cp-cv-status__body">
            <div class="cp-cv-status__title">
                @if($cvVisible)
                    Votre CV est visible dans la CVthèque
                @else
                    Votre CV n'est pas encore visible
                @endif
            </div>
            <div class="cp-cv-status__sub">
                @if($cvVisible)
                    Les recruteurs peuvent vous trouver et vous contacter. Complétez votre profil pour remonter dans les résultats.
                @else
                    Enregistrez votre profil pour que votre CV apparaisse automatiquement dans la CVthèque.
                @endif
            </div>
        </div>
        <div class="cp-cv-status__actions">
            @if($cvVisible)
                <a href="{{ route('candidat.cvs') }}" class="cand-btn cand-btn--sm cand-btn--outline">
                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Voir mon CV
                </a>
                @if(!$cv || $cv->plan === 'gratuit')
                <a href="{{ route('candidat.abonnement.plans') }}" class="cand-btn cand-btn--sm cand-btn--yellow">
                    ✦ Passer Premium
                </a>
                @endif
            @else
                <button type="submit" form="form-profil-infos" class="cand-btn cand-btn--sm cand-btn--primary">
                    Publier mon CV maintenant
                </button>
            @endif
        </div>
    </div>

    {{-- Grille 2 colonnes --}}
    <div class="cp-grid cand-2col" style="gap:18px">
        <div>

            {{-- Expériences --}}
            <div class="cp-section">
                <div class="cp-section__head">
                    <div class="cp-section__title">
                        <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <rect x="2" y="7" width="20" height="14" rx="2" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2" />
                        </svg>
                        Expériences professionnelles
                    </div>
                    <button class="cand-btn cand-btn--outline cand-btn--sm" onclick="openExpModal()">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Ajouter
                    </button>
                </div>
                <div class="cp-section__body" id="exp-list">
                    @forelse($user->experiences as $exp)
                        <div id="exp-item-{{ $exp->id }}">
                            <div class="cp-timeline__item"
                                style="display:flex;gap:16px;padding:16px 0;border-bottom:1px solid #f0f2f5">
                                <div class="cp-timeline__dot"><svg width="16" height="16" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <rect x="2" y="7" width="20" height="14" rx="2" />
                                        <path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2" />
                                    </svg></div>
                                <div class="cp-timeline__content" style="flex:1;min-width:0">
                                    <div class="cp-timeline__title">{{ $exp->poste }}</div>
                                    <div class="cp-timeline__sub">{{ $exp->entreprise }}</div>
                                    <div class="cp-timeline__meta">
                                        <span>{{ $exp->duree() }}</span>
                                        @if ($exp->lieu)
                                            <span>· {{ $exp->lieu }}</span>
                                        @endif
                                        @if ($exp->en_cours)
                                            <span class="cand-badge cand-badge--green">En poste</span>
                                        @endif
                                    </div>
                                    @if ($exp->missions && count($exp->missions))
                                        <ul class="cp-timeline__bullets">
                                            @foreach ($exp->missions as $mission)
                                                <li>{{ $mission }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                                <div class="cp-timeline__actions"
                                    style="display:flex;gap:6px;flex-shrink:0;align-self:flex-start">
                                    <button class="cand-btn cand-btn--outline cand-btn--sm cand-btn--icon-only"
                                        onclick='editExp({{ $exp->id }}, {{ json_encode($exp) }})'
                                        title="Modifier">
                                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                    <button class="cand-btn cand-btn--danger cand-btn--sm cand-btn--icon-only"
                                        onclick="deleteItem('experiences',{{ $exp->id }},'exp-item-{{ $exp->id }}')"
                                        title="Supprimer">
                                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="cand-empty" id="exp-empty">
                            <div class="cand-empty__icon"><svg width="24" height="24" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <rect x="2" y="7" width="20" height="14" rx="2" />
                                    <path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2" />
                                </svg></div>
                            <div class="cand-empty__title">Aucune expérience ajoutée</div>
                            <div class="cand-empty__text">Ajoutez vos expériences professionnelles pour attirer les
                                recruteurs.</div>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Formations --}}
            <div class="cp-section">
                <div class="cp-section__head">
                    <div class="cp-section__title">
                        <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        </svg>
                        Formations & Diplômes
                    </div>
                    <button class="cand-btn cand-btn--outline cand-btn--sm" onclick="openFormModal()">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Ajouter
                    </button>
                </div>
                <div class="cp-section__body" id="form-list">
                    @forelse($user->formations as $formation)
                        <div id="form-item-{{ $formation->id }}">
                            <div class="cp-timeline__item"
                                style="display:flex;gap:16px;padding:16px 0;border-bottom:1px solid #f0f2f5">
                                <div class="cp-timeline__dot"><svg width="16" height="16" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 14l9-5-9-5-9 5 9 5z" />
                                    </svg></div>
                                <div class="cp-timeline__content" style="flex:1;min-width:0">
                                    <div class="cp-timeline__title">{{ $formation->diplome }}</div>
                                    <div class="cp-timeline__sub">{{ $formation->etablissement }}</div>
                                    <div class="cp-timeline__meta">
                                        <span>{{ $formation->date_debut->format('Y') }}
                                            {{ $formation->en_cours ? 'En cours' : $formation->date_fin?->format('Y') ?? '' }}</span>
                                        @if ($formation->domaine)
                                            <span>· {{ $formation->domaine }}</span>
                                        @endif
                                    </div>
                                    @if ($formation->activites && count($formation->activites))
                                        <ul class="cp-timeline__bullets">
                                            @foreach ($formation->activites as $activite)
                                                <li>{{ $activite }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                                <div class="cp-timeline__actions"
                                    style="display:flex;gap:6px;flex-shrink:0;align-self:flex-start">
                                    <button class="cand-btn cand-btn--outline cand-btn--sm cand-btn--icon-only"
                                        onclick='editForm({{ $formation->id }}, {{ json_encode($formation) }})'
                                        title="Modifier">
                                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                        </svg>
                                    </button>
                                    <button class="cand-btn cand-btn--danger cand-btn--sm cand-btn--icon-only"
                                        onclick="deleteItem('formations',{{ $formation->id }},'form-item-{{ $formation->id }}')"
                                        title="Supprimer">
                                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="cand-empty" id="form-empty">
                            <div class="cand-empty__icon"><svg width="24" height="24" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                                </svg></div>
                            <div class="cand-empty__title">Aucune formation ajoutée</div>
                            <div class="cand-empty__text">Ajoutez vos diplômes et formations.</div>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Photos de travaux / Portfolio --}}
            <div class="cp-section">
                <div class="cp-section__head">
                    <div class="cp-section__title">
                        <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2" />
                            <circle cx="8.5" cy="8.5" r="1.5" />
                            <polyline points="21 15 16 10 5 21" />
                        </svg>
                        Photos de travaux / Portfolio
                    </div>
                    @if ($user->realisations->count() < 8)
                        <button class="cand-btn cand-btn--outline cand-btn--sm" onclick="openModal('modal-travaux')">
                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            Ajouter
                        </button>
                    @endif
                </div>
                <div class="cp-section__body">
                    @if ($user->realisations->isNotEmpty())
                        <div class="cp-travaux-grid">
                            @foreach ($user->realisations as $t)
                                <div class="cp-travail-item" id="travail-item-{{ $t->id }}">
                                    <div class="cp-travail-img"
                                        onclick="openLightbox('{{ asset('storage/' . $t->photo) }}')">
                                        <img src="{{ asset('storage/' . $t->photo) }}"
                                            alt="{{ $t->description ?? '' }}">
                                    </div>
                                    @if ($t->description)
                                        <p class="cp-travail-desc">{{ $t->description }}</p>
                                    @endif
                                    <form method="POST" action="{{ route('candidat.realisations.delete', $t->id) }}"
                                        data-confirm="Supprimer cette photo ?" data-confirm-btn="Supprimer"
                                        class="cp-travail-del">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="cand-btn cand-btn--danger cand-btn--sm cand-btn--icon-only"
                                            title="Supprimer">
                                            <svg width="12" height="12" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="cand-empty" style="padding:28px 0 12px">
                            <div class="cand-empty__icon"><svg width="24" height="24" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <rect x="3" y="3" width="18" height="18" rx="2" />
                                    <circle cx="8.5" cy="8.5" r="1.5" />
                                    <polyline points="21 15 16 10 5 21" />
                                </svg></div>
                            <div class="cand-empty__title">Aucune photo ajoutée</div>
                            <div class="cand-empty__text">Partagez des photos de vos réalisations pour illustrer votre
                                expertise.</div>
                        </div>
                    @endif
                </div>
            </div>


        </div>

        {{-- Colonne droite --}}
        <div>

            {{-- Compétences --}}
            <div class="cp-section">
                <div class="cp-section__head">
                    <div class="cp-section__title">
                        <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                        </svg>
                        Compétences
                    </div>
                    <button class="cand-btn cand-btn--outline cand-btn--sm" onclick="openModal('modal-comp')">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Ajouter
                    </button>
                </div>
                <div class="cp-section__body">
                    <div class="cp-chips" id="comp-list">
                        @forelse($user->competences as $comp)
                            <span class="cp-chip" id="comp-item-{{ $comp->id }}">
                                {{ $comp->nom }}
                                @if ($comp->pivot->annees_experience)
                                    <small style="opacity:.65;margin-left:3px">{{ $comp->pivot->annees_experience }}
                                        an(s)</small>
                                @endif
                                <button class="cp-chip__del"
                                    onclick="deleteItem('competences',{{ $comp->id }},'comp-item-{{ $comp->id }}')"
                                    title="Supprimer">
                                    <svg width="8" height="8" viewBox="0 0 12 12" fill="none">
                                        <path d="M1 1l10 10M11 1L1 11" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" />
                                    </svg>
                                </button>
                            </span>
                        @empty
                            <div class="cand-empty" id="comp-empty" style="padding:30px 0 10px;width:100%">
                                <div class="cand-empty__text" style="margin:0">Ajoutez vos compétences techniques et soft
                                    skills.</div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Langues --}}
            <div class="cp-section">
                <div class="cp-section__head">
                    <div class="cp-section__title">
                        <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129" />
                        </svg>
                        Langues
                    </div>
                    <button class="cand-btn cand-btn--outline cand-btn--sm" onclick="openModal('modal-lang')">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                        </svg>
                        Ajouter
                    </button>
                </div>
                <div class="cp-section__body" style="padding-left:0;padding-right:0">
                    <div id="lang-list" style="padding:0 22px">
                        @forelse($languesCandidats as $langue)
                            <div class="cp-langue" id="lang-item-{{ $langue->id }}">
                                <div class="cp-langue__left">
                                    <div class="cp-langue__flag"><svg width="16" height="16" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                            <circle cx="12" cy="12" r="10" />
                                            <line x1="2" y1="12" x2="22" y2="12" />
                                            <path
                                                d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z" />
                                        </svg></div>
                                    <div>
                                        <div class="cp-langue__nom">{{ $langue->langue->nom }}</div>
                                        <div class="cp-langue__niveau">
                                            {{ $langue->niveau->code }}
                                        </div>
                                    </div>
                                </div>
                                <button class="cand-btn cand-btn--danger cand-btn--sm cand-btn--icon-only"
                                    onclick="deleteItem('langues',{{ $langue->id }},'lang-item-{{ $langue->id }}')"
                                    title="Supprimer">
                                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        @empty
                            <div class="cand-empty" id="lang-empty" style="padding:24px 0 10px">
                                <div class="cand-empty__text" style="margin:0">Ajoutez les langues que vous maîtrisez.
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Préférences emploi --}}
            <div class="cp-section">
                <div class="cp-section__head">
                    <div class="cp-section__title">
                        <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <circle cx="12" cy="12" r="3" />
                        </svg>
                        Préférences emploi
                    </div>
                    <button class="cand-btn cand-btn--outline cand-btn--sm" onclick="openModal('modal-infos')">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        Modifier
                    </button>
                </div>
                <div class="cp-section__body">
                    @if ($profil)
                        <div class="cp-prefs">
                            <div class="cp-pref">
                                <div class="cp-pref__icon"><svg width="22" height="22" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <rect x="2" y="3" width="20" height="14" rx="2" />
                                        <line x1="8" y1="21" x2="16" y2="21" />
                                        <line x1="12" y1="17" x2="12" y2="21" />
                                    </svg></div>
                                <div class="cp-pref__label">Remote</div>
                                <div class="cp-pref__val">{{ $libelles['remote'][$profil->remote] ?? 'N/D' }}</div>
                            </div>
                            <div class="cp-pref">
                                <div class="cp-pref__icon"><svg width="22" height="22" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                        <line x1="16" y1="2" x2="16" y2="6" />
                                        <line x1="8" y1="2" x2="8" y2="6" />
                                        <line x1="3" y1="10" x2="21" y2="10" />
                                    </svg></div>
                                <div class="cp-pref__label">Dispo</div>
                                <div class="cp-pref__val" style="font-size:11px">
                                    {{ $libelles['disponibilite'][$profil->disponibilite] ?? 'N/D' }}</div>
                            </div>
                            <div class="cp-pref">
                                <div class="cp-pref__icon"><svg width="22" height="22" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <line x1="12" y1="1" x2="12" y2="23" />
                                        <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
                                    </svg></div>
                                <div class="cp-pref__label">Salaire</div>
                                <div class="cp-pref__val" style="font-size:11px">
                                    @if ($profil->salaire_min || $profil->salaire_max)
                                        {{ number_format($profil->salaire_min ?? 0, 0, ',', ' ') }}@if ($profil->salaire_max)
                                            , {{ number_format($profil->salaire_max, 0, ',', ' ') }}
                                        @endif FCFA
                                    @else
                                        N/D @endif
                                </div>
                            </div>
                        </div>
                        @if ($user->typesContrats->isNotEmpty())
                            <div style="margin-top:10px">
                                <div
                                    style="font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px">
                                    Contrats</div>
                                <div class="cp-contrats">
                                    @foreach ($user->typesContrats as $tc)
                                        <span class="cp-contrat-tag">{{ $tc->libelle }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        @if ($user->secteursActivite->isNotEmpty())
                            <div style="margin-top:10px">
                                <div
                                    style="font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px">
                                    Secteurs</div>
                                <div class="cp-contrats">
                                    @foreach ($user->secteursActivite as $s)
                                        <span class="cp-contrat-tag">{{ $s->libelle }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif


                        @if ($user->metiers->isNotEmpty())
                            <div style="margin-top:10px">
                                <div
                                    style="font-size:11px;font-weight:600;color:#94a3b8;text-transform:uppercase;letter-spacing:.5px;margin-bottom:6px">
                                    Métiers ciblés</div>
                                <div class="cp-contrats">
                                    @foreach ($user->metiers as $m)
                                        <span class="cp-contrat-tag">{{ $m->nom }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        @php
                            $niveauEtudeLabel = $user->niveauEtude?->niveauEtude?->libelle;
                            $niveauExpLabel = $user->niveauExperience?->niveauExperience?->libelle;
                        @endphp
                        @if ($niveauEtudeLabel || $niveauExpLabel)
                            <div class="cp-prefs" style="margin-top:12px">
                                @if ($niveauEtudeLabel)
                                    <div class="cp-pref">
                                        <div class="cp-pref__icon">
                                            <svg width="22" height="22" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 14l9-5-9-5-9 5 9 5z" />
                                            </svg>
                                        </div>
                                        <div class="cp-pref__label">Étude</div>
                                        <div class="cp-pref__val" style="font-size:11px">{{ $niveauEtudeLabel }}</div>
                                    </div>
                                @endif
                                @if ($niveauExpLabel)
                                    <div class="cp-pref">
                                        <div class="cp-pref__icon">
                                            <svg width="22" height="22" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor" stroke-width="1.5">
                                                <rect x="2" y="7" width="20" height="14" rx="2" />
                                                <path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2" />
                                            </svg>
                                        </div>
                                        <div class="cp-pref__label">Expér.</div>
                                        <div class="cp-pref__val" style="font-size:11px">{{ $niveauExpLabel }}</div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    @else
                        <p style="font-size:13px;color:#6b7a8d;padding:8px 0">Aucune préférence définie.</p>
                    @endif
                </div>
            </div>

            {{-- Documents --}}
            <div class="cp-section" id="documents-section">
                <div class="cp-section__head">
                    <div class="cp-section__title">
                        <svg width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Mes documents
                        <span style="font-size:10.5px;font-weight:700;padding:1px 7px;border-radius:20px;background:#fee2e2;color:#dc2626;border:1px solid #fecaca;margin-left:6px">Obligatoire</span>
                    </div>
                </div>
                <div class="cp-section__body">

                    {{-- Alerte si aucun document / upsell Premium si déposé --}}
                    @if($user->documents->isEmpty())
                    <div style="display:flex;align-items:flex-start;gap:10px;background:#fff7ed;border:1.5px solid #fed7aa;border-radius:10px;padding:12px 14px;margin-bottom:14px">
                        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#ea580c" stroke-width="2.2" style="flex-shrink:0;margin-top:1px"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                        <div>
                            <div style="font-size:13px;font-weight:700;color:#9a3412;margin-bottom:2px">Aucun document déposé</div>
                            <div style="font-size:12px;color:#c2410c">Déposez votre CV, diplôme ou attestation pour être visible des recruteurs.</div>
                        </div>
                    </div>
                    @else
                    {{-- Bloc Premium --}}
                    <div style="background:linear-gradient(135deg,#042C53 0%,#0d3560 55%,#185FA5 100%);border-radius:14px;padding:16px;margin-bottom:14px;position:relative;overflow:hidden">
                        <div style="position:absolute;top:-24px;right:-24px;width:90px;height:90px;background:rgba(245,200,66,.10);border-radius:50%;pointer-events:none"></div>
                        <div style="position:absolute;bottom:-30px;left:-18px;width:110px;height:110px;background:rgba(245,200,66,.06);border-radius:50%;pointer-events:none"></div>

                        {{-- Header --}}
                        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:10px;margin-bottom:14px;flex-wrap:wrap">
                            <div style="position:relative;z-index:1">
                                <div style="display:inline-flex;align-items:center;gap:5px;background:rgba(245,200,66,.2);border:1px solid rgba(245,200,66,.35);border-radius:20px;padding:3px 10px;margin-bottom:6px">
                                    <span style="color:#F5C842;font-size:12px">★</span>
                                    <span style="font-size:10.5px;font-weight:800;color:#F5C842;text-transform:uppercase;letter-spacing:.06em">Premium</span>
                                </div>
                                <div style="font-size:17px;font-weight:900;color:#fff;line-height:1.25;margin-bottom:4px">Décuplez vos chances<br>d'être recruté</div>
                                <div style="font-size:12px;color:rgba(255,255,255,.6)">Seulement&nbsp;<span style="color:#F5C842;font-weight:800;font-size:15px">2 000 FCFA</span>&nbsp;/ mois</div>
                            </div>
                            <a href="{{ route('candidat.abonnement.plans') }}"
                               style="position:relative;z-index:1;display:inline-flex;align-items:center;gap:5px;background:#F5C842;color:#042C53;font-size:12px;font-weight:800;padding:9px 15px;border-radius:9px;text-decoration:none;white-space:nowrap;flex-shrink:0;box-shadow:0 4px 14px rgba(245,200,66,.35)"
                               onmouseover="this.style.background='#fdd835'" onmouseout="this.style.background='#F5C842'">
                                Passer Premium →
                            </a>
                        </div>

                        {{-- Avantages --}}
                        @php
                            $avantages = [
                                ['⚡', 'Profil prioritaire', 'Vu en premier par les recruteurs'],
                                ['👁', 'Qui a vu votre profil', 'Stats de vues en temps réel'],
                                ['🔔', 'Alertes instantanées', 'Ne ratez aucune offre'],
                                ['🏆', 'Badge Premium', 'Crédibilité dès le premier regard'],
                                ['🚀', 'Candidature 1 clic', 'Postulez plus vite que les autres'],
                                ['🔓', 'Offres exclusives', 'Réservées aux membres Premium'],
                            ];
                        @endphp
                        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(130px,1fr));gap:6px;position:relative;z-index:1">
                            @foreach($avantages as [$icon, $titre, $sous])
                            <div style="display:flex;align-items:flex-start;gap:7px;background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.1);border-radius:9px;padding:8px 10px">
                                <span style="font-size:15px;flex-shrink:0;line-height:1.4">{{ $icon }}</span>
                                <div>
                                    <div style="font-size:11.5px;font-weight:700;color:#fff;line-height:1.3">{{ $titre }}</div>
                                    <div style="font-size:10px;color:rgba(255,255,255,.5);line-height:1.4;margin-top:1px">{{ $sous }}</div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Liste des documents existants --}}
                    @if($user->documents->isNotEmpty())
                    <div style="display:flex;flex-direction:column;gap:8px;margin-bottom:14px">
                        @foreach($user->documents as $doc)
                        <div style="display:flex;align-items:center;gap:10px;padding:10px 12px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px">
                            <span style="flex-shrink:0;width:32px;height:32px;border-radius:8px;background:#eff6ff;display:flex;align-items:center;justify-content:center">
                                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </span>
                            <div style="flex:1;min-width:0">
                                <div style="font-size:13px;font-weight:700;color:#042C53;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $doc->nom }}</div>
                                <div style="font-size:11.5px;color:#64748b">{{ $doc->type?->nom ?? '—' }}</div>
                            </div>
                            <form method="POST" action="{{ route('candidat.documents.destroy', $doc) }}" data-confirm="Supprimer ce document ?" data-confirm-btn="Supprimer" style="margin:0">
                                @csrf @method('DELETE')
                                <button type="submit" class="cand-btn cand-btn--danger cand-btn--sm cand-btn--icon-only" title="Supprimer">
                                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </div>
                        @endforeach
                    </div>
                    @endif

                    {{-- Formulaire d'upload --}}
                    <form method="POST" action="{{ route('candidat.documents.store') }}" enctype="multipart/form-data"
                          style="border:1.5px dashed #cbd5e1;border-radius:10px;padding:16px;background:#f8fafc">
                        @csrf
                        <div style="font-size:12.5px;font-weight:700;color:#042C53;margin-bottom:12px;display:flex;align-items:center;gap:6px">
                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                            Ajouter un document
                        </div>
                        <div class="cand-form-group">
                            <label class="cand-form-label">Type de document <span class="req">*</span></label>
                            <select name="type_document_id" class="cand-form-select @error('type_document_id') field--invalid @enderror" required>
                                <option value="">-- Sélectionnez --</option>
                                @foreach($typesDocuments as $td)
                                    <option value="{{ $td->id }}" {{ old('type_document_id') == $td->id ? 'selected' : '' }}>{{ $td->nom }}</option>
                                @endforeach
                            </select>
                            @error('type_document_id')<p class="field__server-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="cand-form-group">
                            <label class="cand-form-label">Fichier <span class="req">*</span></label>
                            <input type="file" name="fichier"
                                class="cand-form-input @error('fichier') field--invalid @enderror"
                                accept=".pdf,.jpg,.jpeg,.png,.webp,.doc,.docx"
                                required style="padding:7px">
                            <div class="cand-form-hint">PDF, JPG, PNG, WebP, Word · Max 5 Mo</div>
                            @error('fichier')<p class="field__server-error">{{ $message }}</p>@enderror
                        </div>
                        <div style="display:flex;justify-content:flex-end;margin-bottom:10px">
                            <button type="submit" class="cand-btn cand-btn--yellow cand-btn--sm">
                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="vertical-align:-2px"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                Téléverser un fichier
                            </button>
                        </div>
                        <div style="text-align:center;padding-top:8px;border-top:1px solid #e8edf3">
                            <span style="font-size:11px;color:#94a3b8">Vous n'avez pas de CV ?</span>
                            <a href="{{ route('service.commande', 'cv-professionnel') }}"
                               style="font-size:11px;font-weight:800;color:#185FA5;text-decoration:none;text-transform:uppercase;letter-spacing:.03em;margin-left:4px"
                               onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                                Commander maintenant →
                            </a>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>


    {{-- ══ Carte de Publication CVthèque ══════════════════════════ --}}
    @php
        $checkBio         = !empty(trim($profil?->bio ?? ''));
        $checkVille       = !empty(trim($profil?->ville ?? ''));
        $checkDispo       = !empty($profil?->disponibilite);
        $checkContrat     = $user->typesContrats->isNotEmpty();
        $checkCompetence  = $user->competences->isNotEmpty();
        $checkParcours    = $user->experiences->isNotEmpty() || $user->formations->isNotEmpty();
        $checkLangue      = $user->languesCandidats->isNotEmpty();
        $checkFichier     = !empty($cv?->fichier_path) || $user->documents->isNotEmpty();
        $totalChecks      = 8;
        $doneChecks       = collect([$checkBio,$checkVille,$checkDispo,$checkContrat,$checkCompetence,$checkParcours,$checkLangue,$checkFichier])->filter()->count();
        $toutComplet      = $doneChecks === $totalChecks;
        $dejaPubile       = !is_null($cv?->publie_le);
    @endphp
    <div class="cp-publish-card" id="section-publication">
        <div class="cp-publish-card__header">
            <div class="cp-publish-card__icon">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div>
                <div class="cp-publish-card__title">
                    @if($dejaPubile && $cv?->visible)
                        Votre profil est publié dans la CVthèque ✓
                    @elseif($dejaPubile && !$cv?->visible)
                        Votre profil a été masqué par un administrateur
                    @else
                        Publiez votre profil dans la CVthèque
                    @endif
                </div>
                <div class="cp-publish-card__sub">{{ $doneChecks }}/{{ $totalChecks }} sections complétées</div>
            </div>
            <div class="cp-publish-card__bar-wrap">
                <div class="cp-publish-card__bar" style="width:{{ round($doneChecks/$totalChecks*100) }}%"></div>
            </div>
        </div>

        {{-- Checklist --}}
        <div class="cp-publish-checklist">
            @php
                $items = [
                    [$checkBio,        'Résumé / bio',                   'Ajoutez un résumé dans "Modifier mon profil"'],
                    [$checkVille,      'Ville de résidence',             'Indiquez votre ville dans "Modifier mon profil"'],
                    [$checkDispo,      'Disponibilité',                  'Choisissez votre disponibilité dans "Modifier mon profil"'],
                    [$checkContrat,    'Type de contrat souhaité',        'Cochez au moins un type de contrat dans "Modifier mon profil"'],
                    [$checkCompetence, 'Au moins une compétence',         'Ajoutez une compétence via le bouton "Ajouter une compétence"'],
                    [$checkParcours,   'Expérience ou formation',         'Ajoutez une expérience ou une formation'],
                    [$checkLangue,     'Au moins une langue',             'Ajoutez une langue via "Ajouter une langue"'],
                    [$checkFichier,    'CV ou document téléversé',        'Téléversez votre CV (PDF/Word) ou un diplôme dans la section "Documents"'],
                ];
            @endphp
            @foreach($items as [$ok, $label, $hint])
            <div class="cp-publish-check {{ $ok ? 'cp-publish-check--ok' : 'cp-publish-check--ko' }}">
                @if($ok)
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#16a34a" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                @else
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#dc2626" stroke-width="2.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                @endif
                <div>
                    <span class="cp-publish-check__label">{{ $label }}</span>
                    @if(!$ok)<span class="cp-publish-check__hint"> — {{ $hint }}</span>@endif
                </div>
            </div>
            @endforeach
        </div>

        {{-- Bouton Publier --}}
        <form method="POST" action="{{ route('candidat.profil.publier') }}" style="margin-top:20px">
            @csrf
            <button type="submit" class="cand-btn cand-btn--primary cand-btn--lg cp-publish-btn">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                </svg>
                @if($dejaPubile)
                    Mettre à jour ma CVthèque
                @else
                    Publier mon profil dans la CVthèque
                @endif
            </button>
            @if($dejaPubile && $cv?->visible)
            <p style="font-size:12px;color:#16a34a;margin:10px 0 0;display:flex;align-items:center;gap:5px">
                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                Publié le {{ $cv->publie_le->format('d/m/Y à H:i') }} · <a href="{{ route('cv.public.detail', $cv) }}" target="_blank" style="color:#185FA5">Voir dans la CVthèque →</a>
            </p>
            @endif
        </form>
    </div>

    {{-- Lightbox --}}
    <div id="lbOverlay" onclick="closeLightbox()"
        style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.85);z-index:9999;align-items:center;justify-content:center;cursor:zoom-out">
        <img id="lbImg" src="" alt=""
            style="max-width:90vw;max-height:90vh;border-radius:8px;box-shadow:0 20px 60px rgba(0,0,0,.5)">
    </div>

    {{-- ═══════════ MODALES ═══════════ --}}

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

                    {{-- SECTION 3 : Préférences emploi --}}
                    <div class="cp-form-section-head">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <rect x="2" y="7" width="20" height="14" rx="2"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>
                        </svg>
                        Préférences emploi
                    </div>

                    <div class="cand-form-grid">
                        <div class="cand-form-group">
                            <label class="cand-form-label">Domaine / Spécialité</label>
                            {{-- Custom multi-select widget --}}
                            <div class="ms-wrap" id="ms-metiers-wrap">
                                <div class="cand-form-input ms-trigger" id="ms-metiers-trigger"
                                     role="combobox" aria-haspopup="listbox" aria-expanded="false" tabindex="0">
                                    <span id="ms-metiers-chips"></span>
                                    <span id="ms-metiers-ph" style="color:#94a3b8;font-size:14px">Sélectionnez un ou plusieurs domaines…</span>
                                    <svg class="ms-chevron" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                                </div>
                            </div>
                            <select name="metiers_ids[]" id="cand-modal-metiers" multiple style="display:none">
                                @foreach ($metiers as $p)
                                    <option value="{{ $p->id }}"
                                        {{ in_array($p->id, old('metiers_ids', $user->metiers->pluck('id')->toArray() ?? [])) ? 'selected' : '' }}>
                                        {{ $p->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="cand-form-group">
                            <label class="cand-form-label">Années d'expérience</label>
                            <input type="number" name="annees_experience"
                                class="cand-form-input @error('annees_experience') field--invalid @enderror"
                                min="0" max="50" placeholder="ex: 3"
                                value="{{ old('annees_experience', $profil?->annees_experience) }}">
                            @error('annees_experience')<p class="field__server-error">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="cand-form-group">
                        <label class="cand-form-label">Disponibilité <span class="cp-oblig-badge">Obligatoire</span></label>
                        <div class="cp-pill-group">
                            @foreach ($libelles['disponibilite'] as $val => $lab)
                                <label class="cp-pill">
                                    <input type="radio" name="disponibilite" value="{{ $val }}"
                                        {{ old('disponibilite', $profil?->disponibilite) === $val ? 'checked' : '' }}>
                                    {{ $lab }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="cand-form-group">
                        <label class="cand-form-label">Télétravail</label>
                        <div class="cp-pill-group">
                            @foreach ($libelles['remote'] as $val => $lab)
                                <label class="cp-pill">
                                    <input type="radio" name="remote" value="{{ $val }}"
                                        {{ old('remote', $profil?->remote ?? 'non') === $val ? 'checked' : '' }}>
                                    {{ $lab }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="cand-form-group">
                        <label class="cand-form-label">Types de contrat souhaités <span class="cp-oblig-badge">Obligatoire</span></label>
                        <div class="cp-contract-grid">
                            @foreach ($typesContrats as $tc)
                                <label class="cp-contract-label">
                                    <input type="checkbox" name="types_contrat_ids[]" value="{{ $tc->id }}"
                                        {{ in_array($tc->id, old('types_contrat_ids', $user->typesContrats->pluck('id')->toArray())) ? 'checked' : '' }}>
                                    {{ $tc->libelle }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="cand-form-grid">
                        <div class="cand-form-group">
                            <label class="cand-form-label">Salaire min (FCFA/mois)</label>
                            <input type="number" name="salaire_min"
                                class="cand-form-input @error('salaire_min') field--invalid @enderror" min="0"
                                placeholder="ex: 80 000"
                                value="{{ old('salaire_min', $profil?->salaire_min) }}">
                            @error('salaire_min')<p class="field__server-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="cand-form-group">
                            <label class="cand-form-label">Salaire max (FCFA/mois)</label>
                            <input type="number" name="salaire_max"
                                class="cand-form-input @error('salaire_max') field--invalid @enderror" min="0"
                                placeholder="ex: 250 000"
                                value="{{ old('salaire_max', $profil?->salaire_max) }}">
                            @error('salaire_max')<p class="field__server-error">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    {{-- SECTION 4 : Liens professionnels --}}
                    <div class="cp-form-section-head">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                        Liens professionnels
                    </div>

                    <div class="cand-form-grid">
                        <div class="cand-form-group">
                            <label class="cand-form-label">
                                <svg width="13" height="13" viewBox="0 0 24 24" fill="#0077b5" style="vertical-align:-2px;margin-right:4px"><path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z"/><circle cx="4" cy="4" r="2" fill="#0077b5"/></svg>
                                LinkedIn
                            </label>
                            <input type="url" name="linkedin"
                                class="cand-form-input @error('linkedin') field--invalid @enderror"
                                placeholder="https://linkedin.com/in/votre-profil"
                                value="{{ old('linkedin', $profil?->linkedin) }}">
                            @error('linkedin')<p class="field__server-error">{{ $message }}</p>@enderror
                        </div>
                        <div class="cand-form-group">
                            <label class="cand-form-label">
                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="#6b7a8d" stroke-width="2" style="vertical-align:-2px;margin-right:4px"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                Portfolio / Site web
                            </label>
                            <input type="url" name="portfolio"
                                class="cand-form-input @error('portfolio') field--invalid @enderror"
                                placeholder="https://votre-site.com"
                                value="{{ old('portfolio', $profil?->portfolio) }}">
                            @error('portfolio')<p class="field__server-error">{{ $message }}</p>@enderror
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

    {{-- Modale Expérience --}}
    <div class="cp-modal-overlay" id="modal-exp">
        <div class="cp-modal">
            <div class="cp-modal__head">
                <div class="cp-modal__title" id="modal-exp-title">Ajouter une expérience</div>
                <button class="cp-modal__close" onclick="closeModal('modal-exp')"><svg width="14" height="14"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg></button>
            </div>
            <div class="cp-modal__body">
                <div class="cand-form-grid">
                    <div class="cand-form-group"><label class="cand-form-label">Poste <span
                                class="req">*</span></label><input type="text" id="exp-poste"
                            class="cand-form-input" placeholder="ex: Comptable, Caissier, Infirmier, Enseignant..."></div>
                    <div class="cand-form-group"><label class="cand-form-label">Entreprise <span
                                class="req">*</span></label><input type="text" id="exp-entreprise"
                            class="cand-form-input" placeholder="ex: MTN Bénin, SONEB, Ecobank, Mairie de Cotonou..."></div>
                </div>
                <div class="cand-form-grid">
                    <div class="cand-form-group"><label class="cand-form-label">Lieu</label><input type="text"
                            id="exp-lieu" class="cand-form-input" placeholder="ex: Cotonou, Porto-Novo, Abomey-Calavi..."></div>
                    <div class="cand-form-group"><label class="cand-form-label">Secteur</label><input type="text"
                            id="exp-secteur" class="cand-form-input" placeholder="ex: Banque, BTP, Santé, Commerce..."></div>
                </div>
                <div class="cand-form-grid">
                    <div class="cand-form-group"><label class="cand-form-label">Date de début <span
                                class="req">*</span></label><input type="date" id="exp-date-debut"
                            class="cand-form-input"></div>
                    <div class="cand-form-group" id="exp-date-fin-wrap"><label class="cand-form-label">Date de
                            fin</label><input type="date" id="exp-date-fin" class="cand-form-input"></div>
                </div>
                <div class="cand-form-group">
                    <label class="cp-checkbox-wrap"><input type="checkbox" id="exp-en-cours"
                            onchange="toggleDateFin('exp')"> Je suis actuellement en poste</label>
                </div>
                <div class="cand-form-group">
                    <label class="cand-form-label">Missions / Responsabilités</label>
                    <div style="display:flex;gap:8px;margin-bottom:6px">
                        <input type="text" id="exp-mission-input" class="cand-form-input"
                            placeholder="ex: Tenue de la comptabilité, Gestion de la caisse, Suivi des livraisons..." style="flex:1"
                            onkeydown="if(event.key==='Enter'){event.preventDefault();addExpMission()}">
                        <button type="button" onclick="addExpMission()" class="cand-btn cand-btn--outline cand-btn--sm"
                            style="flex-shrink:0;white-space:nowrap">+ Ajouter</button>
                    </div>
                    <div id="exp-missions-list"></div>
                    <div class="cand-form-hint">Max 20 missions, Entrée ou clic sur Ajouter</div>
                </div>
                <div class="cp-modal__actions">
                    <button type="button" class="cand-btn cand-btn--outline"
                        onclick="closeModal('modal-exp')">Annuler</button>
                    <button type="button" class="cand-btn cand-btn--yellow" onclick="saveExp()">Enregistrer</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modale Formation --}}
    <div class="cp-modal-overlay" id="modal-form">
        <div class="cp-modal">
            <div class="cp-modal__head">
                <div class="cp-modal__title" id="modal-form-title">Ajouter une formation</div>
                <button class="cp-modal__close" onclick="closeModal('modal-form')"><svg width="14" height="14"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg></button>
            </div>
            <div class="cp-modal__body">
                <div class="cand-form-grid">
                    <div class="cand-form-group"><label class="cand-form-label">Diplôme <span
                                class="req">*</span></label><input type="text" id="form-diplome"
                            class="cand-form-input" placeholder="ex: Licence en Gestion, BTS Commerce, DUT Informatique..."></div>
                    <div class="cand-form-group"><label class="cand-form-label">Établissement <span
                                class="req">*</span></label><input type="text" id="form-etablissement"
                            class="cand-form-input" placeholder="ex: UAC, ENEAM, UP Parakou, IBAM, EPAC..."></div>
                </div>
                <div class="cand-form-group"><label class="cand-form-label">Domaine / Spécialité</label><input
                        type="text" id="form-domaine" class="cand-form-input"
                        placeholder="ex: Comptabilité, Droit, Génie Civil, Gestion RH..."></div>
                <div class="cand-form-grid">
                    <div class="cand-form-group"><label class="cand-form-label">Date de début <span
                                class="req">*</span></label><input type="date" id="form-date-debut"
                            class="cand-form-input"></div>
                    <div class="cand-form-group" id="form-date-fin-wrap"><label class="cand-form-label">Date de
                            fin</label><input type="date" id="form-date-fin" class="cand-form-input"></div>
                </div>
                <div class="cand-form-group">
                    <label class="cp-checkbox-wrap"><input type="checkbox" id="form-en-cours"
                            onchange="toggleDateFin('form')"> Formation en cours</label>
                </div>
                <div class="cand-form-group">
                    <label class="cand-form-label">Activités / Réalisations</label>
                    <div style="display:flex;gap:8px;margin-bottom:6px">
                        <input type="text" id="form-activite-input" class="cand-form-input"
                            placeholder="ex: Major de promotion, Stage à la BOA Bénin, Mémoire sur la fiscalité OHADA..." style="flex:1"
                            onkeydown="if(event.key==='Enter'){event.preventDefault();addFormActivite()}">
                        <button type="button" onclick="addFormActivite()"
                            class="cand-btn cand-btn--outline cand-btn--sm" style="flex-shrink:0;white-space:nowrap">+
                            Ajouter</button>
                    </div>
                    <div id="form-activites-list"></div>
                    <div class="cand-form-hint">Max 20 activités, Entrée ou clic sur Ajouter</div>
                </div>
                <div class="cp-modal__actions">
                    <button type="button" class="cand-btn cand-btn--outline"
                        onclick="closeModal('modal-form')">Annuler</button>
                    <button type="button" class="cand-btn cand-btn--yellow" onclick="saveForm()">Enregistrer</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modale Compétence --}}
    <div class="cp-modal-overlay" id="modal-comp">
        <div class="cp-modal" style="max-width:420px">
            <div class="cp-modal__head">
                <div class="cp-modal__title">Ajouter une compétence</div>
                <button class="cp-modal__close" onclick="closeModal('modal-comp')"><svg width="14" height="14"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg></button>
            </div>
            <div class="cp-modal__body">
                <div class="cand-form-group">
                    <label class="cand-form-label">Filtrer par métier <span style="font-size:11px;color:#6b7a8d;font-weight:400">(optionnel)</span></label>
                    <select id="comp-metier-filter" class="cand-form-select">
                        <option value="">-- Toutes les compétences --</option>
                        @foreach ($metiers->filter(fn($m) => $m->competences->isNotEmpty()) as $m)
                            <option value="{{ $m->id }}">{{ $m->nom }}</option>
                        @endforeach
                    </select>
                    <div class="cand-form-hint">Choisissez un métier pour afficher uniquement ses compétences</div>
                </div>
                <div class="cand-form-group">
                    <label class="cand-form-label">Compétence <span class="req">*</span></label>
                    <select id="comp-competence-id" class="cand-form-select">
                        <option value="">-- Choisir une compétence --</option>
                    </select>
                </div>
                <div class="cand-form-group">
                    <label class="cand-form-label">Années d'expérience</label>
                    <input type="number" id="comp-annees" class="cand-form-input" min="0" max="50"
                        placeholder="ex: 3">
                    <div class="cand-form-hint">Laisser vide si non précisé</div>
                </div>
                <div class="cp-modal__actions">
                    <button type="button" class="cand-btn cand-btn--outline"
                        onclick="closeModal('modal-comp')">Annuler</button>
                    <button type="button" class="cand-btn cand-btn--yellow" onclick="saveComp()">Ajouter</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modale Langue --}}
    <div class="cp-modal-overlay" id="modal-lang">
        <div class="cp-modal" style="max-width:420px">
            <div class="cp-modal__head">
                <div class="cp-modal__title">Ajouter une langue</div>
                <button class="cp-modal__close" onclick="closeModal('modal-lang')"><svg width="14"
                        height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg></button>
            </div>
            <div class="cp-modal__body">
                <div class="cand-form-group">
                    <label class="cand-form-label">Langue <span class="req">*</span></label>
                    <select id="lang-langue" class="cand-form-select">
                        <option value="">-- Choisir une langue --</option>
                        @foreach ($langues as $l)
                            <option value="{{ $l->id }}">{{ $l->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="cand-form-group">
                    <label class="cand-form-label">Niveau <span class="req">*</span></label>
                    <div class="cp-pill-group" id="lang-niveau-pills">
                        @foreach ($niveauxLangue as $nl)
                            <label class="cp-pill" title="{{ $nl->libelle }}">
                                <input type="radio" name="lang-niveau-radio" value="{{ $nl->id }}">
                                {{ $nl->code }}
                            </label>
                        @endforeach
                    </div>
                </div>
                <div class="cp-modal__actions">
                    <button type="button" class="cand-btn cand-btn--outline"
                        onclick="closeModal('modal-lang')">Annuler</button>
                    <button type="button" id="btn-save-lang" class="cand-btn cand-btn--yellow"
                        onclick="saveLang()">Ajouter</button>
                </div>
            </div>
        </div>
    </div>


    {{-- Modale Photos de travaux --}}
    <div class="cp-modal-overlay" id="modal-travaux">
        <div class="cp-modal" style="max-width:480px">
            <div class="cp-modal__head">
                <div class="cp-modal__title">Ajouter des photos de travaux</div>
                <button class="cp-modal__close" onclick="closeModal('modal-travaux')"><svg width="14"
                        height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg></button>
            </div>
            <div class="cp-modal__body">
                <form method="POST" action="{{ route('candidat.realisations.store') }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="cand-form-group">
                        <label class="cand-form-label">Photos <span class="req">*</span></label>
                        <input type="file" name="photos[]" class="cand-form-input"
                            accept="image/jpeg,image/png,image/webp" multiple style="padding:7px">
                        <div class="cand-form-hint">JPG, PNG ou WebP, max 3 Mo par photo, max 8 photos au total</div>
                    </div>
                    <div class="cand-form-group">
                        <label class="cand-form-label">Description (optionnelle)</label>
                        <input type="text" name="descriptions[0]" class="cand-form-input"
                            placeholder="ex: Bâtiment construit à Akpakpa, Boutique aménagée à Dantokpa, Logo créé pour une ONG...">
                    </div>
                    <div class="cp-modal__actions">
                        <button type="button" class="cand-btn cand-btn--outline"
                            onclick="closeModal('modal-travaux')">Annuler</button>
                        <button type="submit" class="cand-btn cand-btn--yellow">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
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

        function openLightbox(src) {
            const lb = document.getElementById('lbOverlay');
            document.getElementById('lbImg').src = src;
            lb.style.display = 'flex';
        }

        function closeLightbox() {
            document.getElementById('lbOverlay').style.display = 'none';
        }
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeLightbox();
        });

        function renderBulletList(containerId, items, removeFn) {
            const el = document.getElementById(containerId);
            if (!items.length) {
                el.innerHTML = '';
                return;
            }
            el.innerHTML = items.map((m, i) => `
    <div style="display:flex;align-items:center;gap:8px;padding:7px 10px;background:#f8fafc;border-radius:6px;margin-bottom:4px;border:1px solid #e8ecf0">
      <span style="color:#64748b;font-size:18px;line-height:1;flex-shrink:0">•</span>
      <span style="flex:1;font-size:13px;color:#1e293b">${m.replace(/</g,'&lt;')}</span>
      <button type="button" onclick="${removeFn}(${i})" style="background:none;border:none;cursor:pointer;color:#ef4444;font-size:18px;line-height:1;padding:0 2px;flex-shrink:0" title="Supprimer">×</button>
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
        // ── Toast SweetAlert2 ────────────────────────────────────
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

        /* ── Upload avatar immédiat ──────────────────────────── */
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

        // ── Filtrage du select compétences selon métiers cochés ──
        (function() {
            const metiersCompetences = @json($metiersCompetencesJson);
            const allCompetences = @json($competences->map(fn($c) => ['id' => $c->id, 'nom' => $c->nom])->values());

            // Construire un index competence_id → [metier_ids]
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
                    // Aucun filtre métier → toutes les compétences
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
                        // Métier sans compétences liées → fallback toutes
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

            // Écouter le filtre métier dans modal-comp uniquement
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

        // ── Multi-select : Spécialité / Domaine d'expertise ──────────────
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
                '<input type="text" class="ss-search" placeholder="Rechercher un métier…" autocomplete="off">' +
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
        /* ── Multi-select métiers ── */
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

        /* ── Carte Publication ── */
        .cp-publish-card {
            background: #fff;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            padding: 28px 28px 24px;
            margin-bottom: 24px;
            box-shadow: 0 2px 12px rgba(4,44,83,.06);
        }
        .cp-publish-card__header {
            display: flex;
            align-items: flex-start;
            gap: 14px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        .cp-publish-card__icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            background: #ebf4fd;
            color: #185FA5;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .cp-publish-card__title {
            font-size: 15px;
            font-weight: 700;
            color: #042C53;
        }
        .cp-publish-card__sub {
            font-size: 12px;
            color: #64748b;
            margin-top: 2px;
        }
        .cp-publish-card__bar-wrap {
            height: 6px;
            background: #e2e8f0;
            border-radius: 99px;
            margin-top: 10px;
            width: 100%;
            overflow: hidden;
        }
        .cp-publish-card__bar {
            height: 100%;
            background: linear-gradient(90deg, #185FA5, #378ADD);
            border-radius: 99px;
            transition: width .4s ease;
        }
        .cp-publish-card__errors {
            background: #fef2f2;
            border: 1px solid #fca5a5;
            border-radius: 10px;
            padding: 14px 16px;
            display: flex;
            gap: 10px;
            margin-bottom: 18px;
        }
        .cp-publish-checklist {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .cp-publish-check {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 9px 14px;
            border-radius: 8px;
            font-size: 13.5px;
        }
        .cp-publish-check--ok  { background: #f0fdf4; }
        .cp-publish-check--ko  { background: #fef9f0; }
        .cp-publish-check__label { font-weight: 600; color: #042C53; }
        .cp-publish-check--ok .cp-publish-check__label { color: #16a34a; }
        .cp-publish-check--ko .cp-publish-check__label { color: #92400e; }
        .cp-publish-check__hint { font-size: 12px; color: #78716c; font-weight: 400; }
        .cp-publish-btn {
            width: 100%;
            justify-content: center;
            font-size: 15px;
            padding: 14px 24px;
            border-radius: 12px;
        }

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
            content: "•";
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
            content: "▶";
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

    @if(session('publier_erreurs'))
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var manque = @json(session('publier_erreurs'));
        var liste = manque.map(function(item) {
            return '<li style="text-align:left;padding:4px 0;color:#374151">• ' + item.charAt(0).toUpperCase() + item.slice(1) + '</li>';
        }).join('');

        Swal.fire({
            icon: 'warning',
            title: 'Profil incomplet',
            html: '<p style="font-size:14px;color:#6b7280;margin:0 0 14px">Complétez ces sections avant de publier :</p>'
                + '<ul style="list-style:none;margin:0;padding:0;font-size:13.5px;font-weight:600">' + liste + '</ul>',
            confirmButtonText: 'Compléter mon profil',
            confirmButtonColor: '#185FA5',
            showCancelButton: false,
            customClass: { popup: 'swal-profil-incomplet' },
        }).then(function() {
            var card = document.getElementById('section-publication');
            if (card) {
                card.scrollIntoView({ behavior: 'smooth', block: 'center' });
                card.style.outline = '3px solid #185FA5';
                setTimeout(function() { card.style.outline = ''; }, 2000);
            }
        });
    });
    </script>
    @endif

    @if(session('profil_publie'))
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'success',
            title: 'Profil publié !',
            html: '<p style="font-size:14px;color:#374151;margin:0">Votre profil est maintenant <strong style="color:#16a34a">visible dans la CVthèque</strong>.<br>Les recruteurs peuvent vous trouver et vous contacter.</p>',
            confirmButtonText: 'Voir dans la CVthèque',
            confirmButtonColor: '#185FA5',
            showCancelButton: true,
            cancelButtonText: 'Rester ici',
            cancelButtonColor: '#6b7280',
        }).then(function(result) {
            if (result.isConfirmed) {
                @if($cv)
                window.open('{{ route('cv.public.detail', $cv) }}', '_blank');
                @endif
            }
        });
    });
    </script>
    @endif
@endsection
