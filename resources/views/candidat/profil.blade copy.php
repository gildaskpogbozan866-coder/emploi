@extends('layouts.candidat')
@section('title', 'Mon profil — Emploi Bouge Bénin')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/candidat/profil.css') }}">
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

    {{-- Hero --}}
    <div class="cp-hero">
        <div class="cp-hero__banner"></div>
        <div class="cp-hero__body">

            <div class="cp-hero__avatar-wrap">
                @if ($user->avatar)
                    <img src="{{ Storage::url($user->avatar) }}" alt="" class="cp-hero__avatar">
                @else
                    <div class="cp-hero__avatar-initials">{{ $user->initiale }}</div>
                @endif
                <label for="avatar-input" class="cp-hero__avatar-btn" title="Changer la photo">
                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                        <circle cx="12" cy="13" r="3" />
                    </svg>
                </label>
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
                    'action' => "openModal('modal-infos')",
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
                    'hint' => 'Vous en avez ' . $user->competences->count() . '/3 — ajoutez les vôtres',
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
                    'done' => !empty($profil?->types_contrat),
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
                <div class="cp-completion__checklist-title">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3" />
                    </svg>
                    {{ $missing->count() }} élément{{ $missing->count() > 1 ? 's' : '' }}
                    manquant{{ $missing->count() > 1 ? 's' : '' }} — {{ $missing->sum('pts') }} pts à gagner
                </div>
                <div class="cp-checklist-grid">
                    @foreach ($missing as $item)
                        <div class="cp-check-item cp-check-item--miss" onclick="{{ $item['action'] }}" role="button"
                            tabindex="0">
                            <div class="cp-check-icon cp-check-icon--miss">
                                <svg width="12" height="12" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v8m0 4h.01" />
                                </svg>
                            </div>
                            <div class="cp-check-body">
                                <div class="cp-check-label">{{ $item['label'] }}</div>
                                <div class="cp-check-hint">{{ $item['hint'] }}</div>
                            </div>
                            <div class="cp-check-pts">+{{ $item['pts'] }} pts</div>
                        </div>
                    @endforeach
                </div>
                @if ($done->isNotEmpty())
                    <details class="cp-checklist-done">
                        <summary>{{ $done->count() }} élément{{ $done->count() > 1 ? 's' : '' }}
                            complété{{ $done->count() > 1 ? 's' : '' }}</summary>
                        <div class="cp-checklist-grid" style="margin-top:8px">
                            @foreach ($done as $item)
                                <div class="cp-check-item cp-check-item--done">
                                    <div class="cp-check-icon cp-check-icon--done">
                                        <svg width="12" height="12" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                    <div class="cp-check-body">
                                        <div class="cp-check-label">{{ $item['label'] }}</div>
                                    </div>
                                    <div class="cp-check-pts cp-check-pts--done">+{{ $item['pts'] }} pts</div>
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
                Profil 100% complet — félicitations !
            </div>
        @endif
    </div>

    {{-- Grille 2 colonnes --}}
    <div class="cp-grid" style="display:grid;grid-template-columns:1fr 320px;gap:18px;align-items:start">
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
                                        <span>{{ $formation->date_debut->format('Y') }} —
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
                        {{-- @forelse($user->competences as $comp)
                            <span class="cp-chip cp-chip--{{ $comp->niveau }}" id="comp-item-{{ $comp->id }}">
                                {{ $comp->nom }}
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
                        @endforelse --}}
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
                                            — {{ number_format($profil->salaire_max, 0, ',', ' ') }}
                                        @endif FCFA
                                    @else
                                        N/D @endif
                                </div>
                            </div>
                        </div>
                        @if ($profil->types_contrat)
                            <div class="cp-contrats">
                                @foreach ($profil->types_contrat as $type)
                                    <span class="cp-contrat-tag">{{ $libelles['types_contrat'][$type] ?? $type }}</span>
                                @endforeach
                            </div>
                        @endif
                    @else
                        <p style="font-size:13px;color:#6b7a8d;padding:8px 0">Aucune préférence définie.</p>
                    @endif
                </div>
            </div>

        </div>
    </div>

    {{-- ═══════════ MODALES ═══════════ --}}

    {{-- Modale Infos perso --}}
    <div class="cp-modal-overlay" id="modal-infos">
        <div class="cp-modal">
            <div class="cp-modal__head">
                <div class="cp-modal__title">Informations & préférences</div>
                <button class="cp-modal__close" onclick="closeModal('modal-infos')"><svg width="14" height="14"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg></button>
            </div>
            <div class="cp-modal__body">
                <form method="POST" action="{{ route('candidat.profil.update') }}" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="cand-form-grid">
                        <div class="cand-form-group">
                            <label class="cand-form-label">Prénom <span class="req">*</span></label>
                            <input type="text" name="prenom" class="cand-form-input"
                                value="{{ old('prenom', $user->prenom) }}" required>
                        </div>
                        <div class="cand-form-group">
                            <label class="cand-form-label">Nom <span class="req">*</span></label>
                            <input type="text" name="nom" class="cand-form-input"
                                value="{{ old('nom', $user->nom) }}" required>
                        </div>
                    </div>
                    <div class="cand-form-group">
                        <label class="cand-form-label">Titre professionnel</label>
                        <input type="text" name="titre_professionnel" class="cand-form-input"
                            placeholder="ex: Développeur Full Stack, Comptable..."
                            value="{{ old('titre_professionnel', $profil?->titre_professionnel) }}">
                    </div>
                    <div class="cand-form-group">
                        <label class="cand-form-label">Résumé / Bio</label>
                        <textarea name="bio" class="cand-form-textarea" rows="3"
                            placeholder="Décrivez votre parcours et vos ambitions...">{{ old('bio', $profil?->bio) }}</textarea>
                        <div class="cand-form-hint">Max 1000 caractères</div>
                    </div>
                    <div class="cand-form-grid">
                        <div class="cand-form-group">
                            <label class="cand-form-label">Téléphone</label>
                            <input type="text" name="tel" class="cand-form-input"
                                value="{{ old('tel', $user->tel) }}">
                        </div>
                        <div class="cand-form-group">
                            <label class="cand-form-label">Ville</label>
                            <input type="text" name="ville" class="cand-form-input"
                                value="{{ old('ville', $profil?->ville) }}">
                        </div>
                    </div>
                    <div class="cand-form-group">
                        <label class="cand-form-label">Pays</label>
                        <input type="text" name="pays" class="cand-form-input"
                            value="{{ old('pays', $user->pays) }}">
                    </div>
                    <div class="cand-form-grid">
                        <div class="cand-form-group">
                            <label class="cand-form-label">Disponibilité</label>
                            <select name="disponibilite" class="cand-form-select">
                                <option value="">Non définie</option>
                                @foreach ($libelles['disponibilite'] as $val => $lab)
                                    <option value="{{ $val }}"
                                        {{ old('disponibilite', $profil?->disponibilite) === $val ? 'selected' : '' }}>
                                        {{ $lab }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="cand-form-group">
                            <label class="cand-form-label">Télétravail</label>
                            <select name="remote" class="cand-form-select">
                                @foreach ($libelles['remote'] as $val => $lab)
                                    <option value="{{ $val }}"
                                        {{ old('remote', $profil?->remote ?? 'non') === $val ? 'selected' : '' }}>
                                        {{ $lab }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="cand-form-group">
                        <label class="cand-form-label">Types de contrat souhaités</label>
                        <div style="display:flex;flex-wrap:wrap;gap:10px;margin-top:4px">
                            @foreach ($libelles['types_contrat'] as $val => $lab)
                                <label style="display:flex;align-items:center;gap:6px;font-size:13px;cursor:pointer">
                                    <input type="checkbox" name="types_contrat[]" value="{{ $val }}"
                                        {{ in_array($val, old('types_contrat', $profil?->types_contrat ?? [])) ? 'checked' : '' }}>
                                    {{ $lab }}
                                </label>
                            @endforeach
                        </div>
                    </div>
                    <div class="cand-form-grid">
                        <div class="cand-form-group">
                            <label class="cand-form-label">Salaire min (FCFA/mois)</label>
                            <input type="number" name="salaire_min" class="cand-form-input" min="0"
                                value="{{ old('salaire_min', $profil?->salaire_min) }}">
                        </div>
                        <div class="cand-form-group">
                            <label class="cand-form-label">Salaire max (FCFA/mois)</label>
                            <input type="number" name="salaire_max" class="cand-form-input" min="0"
                                value="{{ old('salaire_max', $profil?->salaire_max) }}">
                        </div>
                    </div>
                    <div class="cand-form-grid">
                        <div class="cand-form-group">
                            <label class="cand-form-label">LinkedIn</label>
                            <input type="url" name="linkedin" class="cand-form-input"
                                placeholder="https://linkedin.com/in/..."
                                value="{{ old('linkedin', $profil?->linkedin) }}">
                        </div>
                        <div class="cand-form-group">
                            <label class="cand-form-label">Portfolio / Site web</label>
                            <input type="url" name="portfolio" class="cand-form-input" placeholder="https://..."
                                value="{{ old('portfolio', $profil?->portfolio) }}">
                        </div>
                    </div>
                    <div class="cand-form-group">
                        <label class="cand-form-label">Photo de profil</label>
                        <input type="file" name="avatar" id="avatar-input" accept="image/jpeg,image/png,image/webp"
                            class="cand-form-input" style="padding:7px">
                        <div class="cand-form-hint">JPG, PNG ou WebP — max 2 Mo</div>
                    </div>
                    <div class="cp-modal__actions">
                        <button type="button" class="cand-btn cand-btn--outline"
                            onclick="closeModal('modal-infos')">Annuler</button>
                        <button type="submit" class="cand-btn cand-btn--primary">Enregistrer</button>
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
                            class="cand-form-input" placeholder="ex: Développeur Web"></div>
                    <div class="cand-form-group"><label class="cand-form-label">Entreprise <span
                                class="req">*</span></label><input type="text" id="exp-entreprise"
                            class="cand-form-input" placeholder="ex: Société XYZ"></div>
                </div>
                <div class="cand-form-grid">
                    <div class="cand-form-group"><label class="cand-form-label">Lieu</label><input type="text"
                            id="exp-lieu" class="cand-form-input" placeholder="ex: Cotonou, Bénin"></div>
                    <div class="cand-form-group"><label class="cand-form-label">Secteur</label><input type="text"
                            id="exp-secteur" class="cand-form-input" placeholder="ex: Informatique..."></div>
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
                            placeholder="ex: Développement de l'application mobile..." style="flex:1"
                            onkeydown="if(event.key==='Enter'){event.preventDefault();addExpMission()}">
                        <button type="button" onclick="addExpMission()" class="cand-btn cand-btn--outline cand-btn--sm"
                            style="flex-shrink:0;white-space:nowrap">+ Ajouter</button>
                    </div>
                    <div id="exp-missions-list"></div>
                    <div class="cand-form-hint">Max 20 missions — Entrée ou clic sur Ajouter</div>
                </div>
                <div class="cp-modal__actions">
                    <button type="button" class="cand-btn cand-btn--outline"
                        onclick="closeModal('modal-exp')">Annuler</button>
                    <button type="button" class="cand-btn cand-btn--primary" onclick="saveExp()">Enregistrer</button>
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
                            class="cand-form-input" placeholder="ex: Master, Licence..."></div>
                    <div class="cand-form-group"><label class="cand-form-label">Établissement <span
                                class="req">*</span></label><input type="text" id="form-etablissement"
                            class="cand-form-input" placeholder="ex: UAC"></div>
                </div>
                <div class="cand-form-group"><label class="cand-form-label">Domaine / Spécialité</label><input
                        type="text" id="form-domaine" class="cand-form-input"
                        placeholder="ex: Informatique, Gestion..."></div>
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
                            placeholder="ex: Major de promotion, Projet de fin d'études..." style="flex:1"
                            onkeydown="if(event.key==='Enter'){event.preventDefault();addFormActivite()}">
                        <button type="button" onclick="addFormActivite()"
                            class="cand-btn cand-btn--outline cand-btn--sm" style="flex-shrink:0;white-space:nowrap">+
                            Ajouter</button>
                    </div>
                    <div id="form-activites-list"></div>
                    <div class="cand-form-hint">Max 20 activités — Entrée ou clic sur Ajouter</div>
                </div>
                <div class="cp-modal__actions">
                    <button type="button" class="cand-btn cand-btn--outline"
                        onclick="closeModal('modal-form')">Annuler</button>
                    <button type="button" class="cand-btn cand-btn--primary" onclick="saveForm()">Enregistrer</button>
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
                <div class="cand-form-group"><label class="cand-form-label">Compétence <span
                            class="req">*</span></label><input type="text" id="comp-nom" class="cand-form-input"
                        placeholder="ex: JavaScript, Comptabilité..."></div>
                <div class="cand-form-group">
                    <label class="cand-form-label">Niveau</label>
                    <select id="comp-niveau" class="cand-form-select">
                        <option value="debutant">Débutant</option>
                        <option value="intermediaire" selected>Intermédiaire</option>
                        <option value="avance">Avancé</option>
                        <option value="expert">Expert</option>
                    </select>
                </div>
                <div class="cp-modal__actions">
                    <button type="button" class="cand-btn cand-btn--outline"
                        onclick="closeModal('modal-comp')">Annuler</button>
                    <button type="button" class="cand-btn cand-btn--primary" onclick="saveComp()">Ajouter</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modale Langue --}}
    <div class="cp-modal-overlay" id="modal-lang">
        <div class="cp-modal" style="max-width:420px">
            <div class="cp-modal__head">
                <div class="cp-modal__title">Ajouter une langue</div>
                <button class="cp-modal__close" onclick="closeModal('modal-lang')"><svg width="14" height="14"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg></button>
            </div>
            <div class="cp-modal__body">
                <div class="cand-form-group">
                    <label class="cand-form-label" for="lang-langue">Langue <span class="req">*</span></label>

                    <select id="lang-langue" class="cand-form-select" name="langue_id">
                        @foreach ($langues as $langue)
                            <option value="{{ $langue->id }}">{{ $langue->nom }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="cand-form-group">
                    <label class="cand-form-label" for="lang-niveau">Niveau</label>
                    <select id="lang-niveau" class="cand-form-select" name="niveau_id">
                        @foreach ($niveauxLangue as $niveau)
                            <option value="{{ $niveau->id }}">{{ $niveau->code }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="cp-modal__actions">
                    <button type="button" class="cand-btn cand-btn--outline"
                        onclick="closeModal('modal-lang')">Annuler</button>
                    <button type="button" class="cand-btn cand-btn--primary" onclick="saveLang()">Ajouter</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        const CSRF = '{{ csrf_token() }}';
        let editingExpId = null,
            editingFormId = null;
        let expMissions = [],
            formActivites = [];

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
            }).catch(() => showToast('Erreur réseau — réessayez.', true));
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
                showToast(data.errors ? Object.values(data.errors).flat().join(' — ') : (data.message ?? 'Erreur.'),
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
                showToast(data.errors ? Object.values(data.errors).flat().join(' — ') : (data.message ?? 'Erreur.'),
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
            const {
                ok,
                data
            } = await ajax('/candidat/profil/competences', 'POST', {
                nom: document.getElementById('comp-nom').value,
                niveau: document.getElementById('comp-niveau').value
            });
            if (!ok) {
                showToast(data.message ?? 'Erreur.', true);
                return;
            }
            const c = data.competence;
            const empty = document.getElementById('comp-empty');
            if (empty) empty.remove();
            const chip = document.createElement('span');
            chip.className = `cp-chip cp-chip--${c.niveau}`;
            chip.id = `comp-item-${c.id}`;
            chip.innerHTML =
                `${c.nom}<button class="cp-chip__del" onclick="deleteItem('competences',${c.id},'comp-item-${c.id}')" title="Supprimer"><svg width="8" height="8" viewBox="0 0 12 12" fill="none"><path d="M1 1l10 10M11 1L1 11" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg></button>`;
            document.getElementById('comp-list').appendChild(chip);
            document.getElementById('comp-nom').value = '';
            showToast('Compétence ajoutée !');
        }

        // Langues
        const niveauxLabels = {
            A1: 'A1 — Débutant',
            A2: 'A2 — Élémentaire',
            B1: 'B1 — Intermédiaire',
            B2: 'B2 — Intermédiaire supérieur',
            C1: 'C1 — Avancé',
            C2: 'C2 — Maîtrise',
            natif: 'Langue natale'
        };
        async function saveLang() {
            const {
                ok,
                data
            } = await ajax('/candidat/profil/langues', 'POST', {
                langue_id: document.getElementById('lang-langue').value,
                niveau_id: document.getElementById('lang-niveau').value
            });
            if (!ok) {
                showToast(data.message ?? 'Erreur.', true);
                return;
            }
            const l = data.langue;
            const empty = document.getElementById('lang-empty');
            if (empty) empty.remove();
            const row = document.createElement('div');
            row.className = 'cp-langue';
            row.id = `lang-item-${l.id}`;
            row.innerHTML =
                `<div class="cp-langue__left"><div class="cp-langue__flag"><svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg></div><div><div class="cp-langue__nom">${l.langue}</div><div class="cp-langue__niveau">${niveauxLabels[l.niveau]??l.niveau}</div></div></div><button class="cand-btn cand-btn--danger cand-btn--sm cand-btn--icon-only" onclick="deleteItem('langues',${l.id},'lang-item-${l.id}')" title="Supprimer"><svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>`;
            document.getElementById('lang-list').appendChild(row);
            document.getElementById('lang-langue').value = '';
            showToast('Langue ajoutée !');
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
            showToast({{ Js::from(implode(' — ', $errors->all())) }}, true);
        @endif
    </script>
    <style>
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
@endsection
