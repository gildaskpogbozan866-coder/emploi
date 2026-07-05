@extends('layouts.app')
@section('title', 'Profil candidat · ' . ($candidat->candidatProfil?->titre_professionnel ?? 'Candidat') . ' | Emploi Bouge Bénin')

@section('css')
<link rel="stylesheet" href="{{ asset('css/cv/cvtheque.css') }}">
@endsection

@section('content')
@php
    $profil   = $candidat->candidatProfil;
    $libelles = \App\Models\CandidatProfil::libelles();
@endphp

{{-- ══════════ HERO ══════════ --}}
<section class="section page-hero">
    <div class="container page-hero__inner">
        <span class="badge badge--blue">CVthèque</span>
        <h1 class="page-hero__title">{{ $profil?->titre_professionnel ?? 'Profil candidat' }}</h1>
        <p class="page-hero__subtitle">
            @if($candidat->pays){{ $candidat->pays }}@endif
            @if($candidat->pays && $profil?->ville) · @endif
            @if($profil?->ville){{ $profil->ville }}@endif
            @if($profil?->disponibilite) · {{ $libelles['disponibilite'][$profil->disponibilite] }}@endif
        </p>
    </div>
</section>



{{-- ══════════ CORPS ══════════ --}}
<div class="cvtd-page">
    <div class="cvtd-wrap">

        <a href="{{ url()->previous() }}" class="page-back-link">
            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Retour
        </a>

        {{-- ── CARTE PROFIL ── --}}
        <div class="cvtd-profile">

            {{-- En-tête --}}
            <div class="cvtd-profile__head">
                {{-- Avatar anonyme --}}
                <div class="cvtd-avatar">
                    <svg width="30" height="30" fill="none" viewBox="0 0 24 24" stroke="rgba(255,255,255,.7)" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div class="cvtd-head-info">
                    <h2 class="cvtd-head-title">{{ $profil?->titre_professionnel ?? 'Candidat anonyme' }}</h2>
                    <p class="cvtd-head-meta">
                        <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        </svg>
                        {{ collect([$profil?->ville, $candidat->pays])->filter()->join(', ') }}
                    </p>
                    <div class="cvtd-head-badges">
                        @if($profil?->disponibilite)
                            <span class="cvtd-dispo-badge">
                                <span class="cvtd-dispo-dot" style="background:#16a34a"></span>
                                {{ $libelles['disponibilite'][$profil->disponibilite] }}
                            </span>
                        @endif
                        @if($candidat->premium)
                            <span class="cvtd-head-chip">
                                <svg width="11" height="11" viewBox="0 0 24 24" fill="#F5C842">
                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                </svg>
                                Premium
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Corps --}}
            <div class="cvtd-profile__body">

                {{-- Badges métiers --}}
                @if($candidat->metiers->isNotEmpty())
                    <div style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:18px">
                        @foreach($candidat->metiers as $metier)
                            <span class="cvtd-sect-tag">
                                <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                {{ $metier->nom }}
                            </span>
                        @endforeach
                    </div>
                @endif

                {{-- Résumé / Bio --}}
                @if($profil?->bio)
                    <div class="cvtd-section">
                        <p class="cvtd-section-label">
                            <span class="cvtd-section-label__icon">
                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </span>
                            Résumé
                        </p>
                        <p class="cvtd-section-text">{{ $profil->bio }}</p>
                    </div>
                @endif

                {{-- Infos clés --}}
                <div class="cvtd-section">
                    <p class="cvtd-section-label">
                        <span class="cvtd-section-label__icon">
                            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3"/>
                            </svg>
                        </span>
                        Informations clés
                    </p>
                    <div style="display:flex;flex-wrap:wrap;gap:8px;margin-top:8px">
                        @if($profil?->annees_experience !== null)
                            <span style="display:inline-flex;align-items:center;gap:5px;font-size:12px;font-weight:600;padding:5px 12px;border-radius:20px;background:#f0f7ff;color:#185FA5;border:1px solid #bfdbfe">
                                <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <rect x="2" y="7" width="20" height="14" rx="2"/>
                                    <path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>
                                </svg>
                                Expérience : {{ $profil->annees_experience }} an{{ $profil->annees_experience > 1 ? 's' : '' }}
                            </span>
                        @endif
                        @if($candidat->niveauExperience?->niveauExperience)
                            <span style="display:inline-flex;align-items:center;gap:5px;font-size:12px;font-weight:600;padding:5px 12px;border-radius:20px;background:#faf5ff;color:#7c3aed;border:1px solid #e9d5ff">
                                Niveau : {{ $candidat->niveauExperience->niveauExperience->libelle }}
                            </span>
                        @endif
                        @if($profil?->remote)
                            <span style="display:inline-flex;align-items:center;gap:5px;font-size:12px;font-weight:600;padding:5px 12px;border-radius:20px;background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0">
                                Télétravail : {{ $libelles['remote'][$profil->remote] }}
                            </span>
                        @endif
                        @if($profil?->salaire_min || $profil?->salaire_max)
                            <span style="display:inline-flex;align-items:center;gap:5px;font-size:12px;font-weight:600;padding:5px 12px;border-radius:20px;background:#fefce8;color:#854d0e;border:1px solid #fde68a">
                                <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <line x1="12" y1="1" x2="12" y2="23"/>
                                    <path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/>
                                </svg>
                                Prétention : {{ number_format($profil->salaire_min ?? 0, 0, ',', ' ') }}@if($profil->salaire_max) – {{ number_format($profil->salaire_max, 0, ',', ' ') }}@endif FCFA
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Expériences --}}
                @if($candidat->experiences->isNotEmpty())
                    <div class="cvtd-section">
                        <p class="cvtd-section-label">
                            <span class="cvtd-section-label__icon">
                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <rect x="2" y="7" width="20" height="14" rx="2"/>
                                    <path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/>
                                </svg>
                            </span>
                            Expériences professionnelles
                        </p>
                        <div style="display:flex;flex-direction:column;gap:14px;margin-top:10px">
                            @foreach($candidat->experiences as $exp)
                                <div style="padding:14px 16px;background:#fafbfc;border:1px solid #e8edf3;border-radius:12px">
                                    <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px;flex-wrap:wrap">
                                        <div>
                                            <div style="font-size:14px;font-weight:700;color:#042C53">{{ $exp->poste }}</div>
                                            <div style="font-size:13px;color:#185FA5;margin-top:2px">{{ $exp->entreprise }}</div>
                                        </div>
                                        <div style="text-align:right;flex-shrink:0">
                                            <div style="font-size:11.5px;color:#64748b">{{ $exp->duree() }}</div>
                                            @if($exp->lieu)
                                                <div style="font-size:11.5px;color:#94a3b8;margin-top:2px">{{ $exp->lieu }}</div>
                                            @endif
                                            @if($exp->en_cours)
                                                <span style="font-size:11px;font-weight:600;padding:2px 8px;border-radius:20px;background:#f0fdf4;color:#16a34a;border:1px solid #bbf7d0">En poste</span>
                                            @endif
                                        </div>
                                    </div>
                                    @if($exp->missions && count($exp->missions))
                                        <ul style="margin:10px 0 0 0;padding:0;list-style:none">
                                            @foreach($exp->missions as $mission)
                                                <li style="position:relative;padding-left:14px;font-size:12.5px;color:#475569;line-height:1.6;margin-bottom:3px">
                                                    <span style="position:absolute;left:0;color:#94a3b8">•</span>
                                                    {{ $mission }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Formations --}}
                @if($candidat->formations->isNotEmpty())
                    <div class="cvtd-section">
                        <p class="cvtd-section-label">
                            <span class="cvtd-section-label__icon">
                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path d="M22 10v6M2 10l10-5 10 5-10 5z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12v5c3 3 9 3 12 0v-5"/>
                                </svg>
                            </span>
                            Formations & Diplômes
                        </p>
                        <div style="display:flex;flex-direction:column;gap:10px;margin-top:10px">
                            @foreach($candidat->formations as $formation)
                                <div style="padding:12px 16px;background:#fafbfc;border:1px solid #e8edf3;border-radius:12px;display:flex;justify-content:space-between;align-items:flex-start;gap:8px;flex-wrap:wrap">
                                    <div>
                                        <div style="font-size:13.5px;font-weight:700;color:#042C53">{{ $formation->diplome }}</div>
                                        <div style="font-size:12.5px;color:#185FA5;margin-top:2px">{{ $formation->etablissement }}</div>
                                        @if($formation->domaine)
                                            <div style="font-size:12px;color:#64748b;margin-top:2px">{{ $formation->domaine }}</div>
                                        @endif
                                    </div>
                                    <div style="font-size:11.5px;color:#64748b;flex-shrink:0;text-align:right">
                                        {{ $formation->date_debut?->format('Y') }}
                                        @if($formation->en_cours)
                                            – <span style="color:#16a34a;font-weight:600">En cours</span>
                                        @elseif($formation->date_fin)
                                            – {{ $formation->date_fin->format('Y') }}
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Compétences --}}
                @if($candidat->competences->isNotEmpty())
                    <div class="cvtd-section">
                        <p class="cvtd-section-label">
                            <span class="cvtd-section-label__icon">
                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                            </span>
                            Compétences
                        </p>
                        <div style="display:flex;flex-wrap:wrap;gap:6px;margin-top:10px">
                            @foreach($candidat->competences as $comp)
                                <span style="display:inline-flex;align-items:center;gap:5px;font-size:12px;padding:4px 12px;border-radius:20px;background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;font-weight:600">
                                    {{ $comp->nom }}
                                    @if($comp->pivot->annees_experience)
                                        <span style="font-size:11px;opacity:.65">{{ $comp->pivot->annees_experience }}an(s)</span>
                                    @endif
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Langues --}}
                @if($candidat->languesCandidats->isNotEmpty())
                    <div class="cvtd-section">
                        <p class="cvtd-section-label">
                            <span class="cvtd-section-label__icon">
                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                                </svg>
                            </span>
                            Langues
                        </p>
                        <div style="display:flex;flex-wrap:wrap;gap:8px;margin-top:10px">
                            @foreach($candidat->languesCandidats as $lc)
                                <span style="display:inline-flex;align-items:center;gap:6px;font-size:12.5px;padding:5px 12px;border-radius:20px;background:#f1f5f9;color:#374151;border:1px solid #e2e8f0;font-weight:600">
                                    <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="#94a3b8" stroke-width="2">
                                        <circle cx="12" cy="12" r="10"/>
                                        <line x1="2" y1="12" x2="22" y2="12"/>
                                        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                                    </svg>
                                    {{ $lc->langue->nom }}
                                    <span style="font-size:11px;color:#94a3b8;font-weight:400">({{ $lc->niveau->code }})</span>
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Secteurs d'activité --}}
                @if($candidat->secteursActivite->isNotEmpty())
                    <div class="cvtd-section">
                        <p class="cvtd-section-label">
                            <span class="cvtd-section-label__icon">
                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </span>
                            Secteurs d'activité
                        </p>
                        <div style="display:flex;flex-wrap:wrap;gap:6px;margin-top:10px">
                            @foreach($candidat->secteursActivite as $s)
                                <span class="cvtd-sect-tag">{{ $s->libelle }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Types de contrat --}}
                @if($candidat->typesContrats->isNotEmpty())
                    <div class="cvtd-section">
                        <p class="cvtd-section-label">
                            <span class="cvtd-section-label__icon">
                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </span>
                            Types de contrat recherchés
                        </p>
                        <div style="display:flex;flex-wrap:wrap;gap:6px;margin-top:10px">
                            @foreach($candidat->typesContrats as $tc)
                                <span style="font-size:12px;font-weight:600;padding:4px 12px;border-radius:20px;background:#fefce8;color:#854d0e;border:1px solid #fde68a">
                                    {{ $tc->libelle }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Réalisations --}}
                @if($candidat->realisations->isNotEmpty())
                    <div class="cvtd-section">
                        <p class="cvtd-section-label">
                            <span class="cvtd-section-label__icon">
                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="3" width="18" height="18" rx="2"/>
                                    <circle cx="8.5" cy="8.5" r="1.5"/>
                                    <polyline points="21 15 16 10 5 21"/>
                                </svg>
                            </span>
                            Réalisations / Portfolio
                        </p>
                        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:10px;margin-top:10px">
                            @foreach($candidat->realisations as $r)
                                <div style="border-radius:10px;overflow:hidden;border:1px solid #e8edf3;cursor:zoom-in" onclick="openLightbox('{{ asset('storage/' . $r->photo) }}')">
                                    <img src="{{ asset('storage/' . $r->photo) }}" alt="{{ $r->description ?? '' }}" style="width:100%;height:110px;object-fit:cover;display:block">
                                    @if($r->description)
                                        <p style="font-size:11.5px;color:#64748b;margin:0;padding:6px 8px;line-height:1.4">{{ $r->description }}</p>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- CV (visible mais non téléchargeable) --}}
                @if($candidat->cvs->isNotEmpty())
                    <div class="cvtd-section">
                        <p class="cvtd-section-label">
                            <span class="cvtd-section-label__icon">
                                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </span>
                            CV
                        </p>
                        <div style="display:flex;flex-direction:column;gap:8px;margin-top:10px">
                            @foreach($candidat->cvs as $cv)
                                <div style="display:flex;align-items:center;justify-content:space-between;gap:12px;padding:12px 16px;background:#fafbfc;border:1px solid #e8edf3;border-radius:10px">
                                    <div style="display:flex;align-items:center;gap:10px;min-width:0">
                                        <div style="width:36px;height:36px;flex-shrink:0;background:#fee2e2;border-radius:8px;display:flex;align-items:center;justify-content:center">
                                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#ef4444" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <div style="min-width:0">
                                            <div style="font-size:13px;font-weight:600;color:#374151;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                                                {{ $cv->titre ?? 'CV' }}
                                            </div>
                                            <div style="font-size:11.5px;color:#94a3b8;margin-top:1px">
                                                Ajouté le {{ $cv->created_at->format('d/m/Y') }}
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Verrouillé : pas de téléchargement --}}
                                    <div style="display:flex;align-items:center;gap:5px;font-size:12px;font-weight:600;color:#94a3b8;flex-shrink:0;padding:6px 12px;border:1.5px dashed #cbd5e1;border-radius:8px;user-select:none">
                                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                                            <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                                        </svg>
                                        Accès restreint
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        </div>

        {{-- ── COORDONNÉES VERROUILLÉES ── --}}
        <div class="cvtd-locked">
            <div class="cvtd-locked__icon">
                <svg width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="#94a3b8" stroke-width="2">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                </svg>
            </div>
            <h3 class="cvtd-locked__title">Coordonnées confidentielles</h3>

            {{-- Aperçu flou des infos --}}
            <div style="margin:16px 0;display:flex;flex-direction:column;gap:10px">
                @foreach([
                    [
                        'icon'  => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                        'label' => 'Nom',
                        'val'   => '********',
                    ],
                    [
                        'icon'  => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                        'label' => 'Prénom',
                        'val'   => '********',
                    ],
                    [
                        'icon'  => 'M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z',
                        'label' => 'Téléphone',
                        'val'   => '01 ************',
                    ],
                    [
                        'icon'  => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
                        'label' => 'Email',
                        'val'   => '******@******.***',
                    ],
                ] as $row)
                    <div style="display:flex;align-items:center;gap:10px;padding:10px 14px;background:#f8fafc;border:1.5px dashed #e2e8f0;border-radius:10px;filter:blur(3.5px);user-select:none;pointer-events:none">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#94a3b8" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $row['icon'] }}"/>
                        </svg>
                        <span style="font-size:12px;font-weight:700;color:#374151;min-width:70px;flex-shrink:0">{{ $row['label'] }} :</span>
                        <span style="font-size:13px;color:#64748b;letter-spacing:0.04em">{{ $row['val'] }}</span>
                    </div>
                @endforeach
            </div>

            <p class="cvtd-locked__desc">
                @auth
                    @if(auth()->user()->hasRole(\App\Enums\Role::RECRUTEUR))
                        Accédez au profil complet depuis votre espace recruteur CVthèque.
                    @else
                        Ces informations sont réservées aux recruteurs inscrits.
                    @endif
                @else
                    Créez un compte recruteur gratuit pour accéder aux profils complets et contacter les candidats.
                @endauth
            </p>
            <div class="cvtd-locked__actions">
                @auth
                    @if(auth()->user()->hasRole(\App\Enums\Role::RECRUTEUR))
                        <a href="{{ route('recruteur.cvtheque') }}" class="cvtd-locked__btn cvtd-locked__btn--blue">
                            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Accéder à la CVthèque recruteur
                        </a>
                    @endif
                @else
                    <a href="{{ route('auth.inscription') }}" class="cvtd-locked__btn cvtd-locked__btn--yellow">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        Créer un compte recruteur
                    </a>
                    <a href="{{ route('auth.connexion') }}" class="cvtd-locked__btn cvtd-locked__btn--blue">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Se connecter
                    </a>
                @endauth
            </div>
        </div>

    </div>
</div>

{{-- Lightbox réalisations --}}
<div id="lbOverlay" onclick="this.style.display='none'"
     style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.85);z-index:9999;align-items:center;justify-content:center;cursor:zoom-out">
    <img id="lbImg" src="" alt="" style="max-width:90vw;max-height:90vh;border-radius:8px;box-shadow:0 20px 60px rgba(0,0,0,.5)">
</div>

@push('scripts')
<script>
function openLightbox(src) {
    const lb = document.getElementById('lbOverlay');
    document.getElementById('lbImg').src = src;
    lb.style.display = 'flex';
}
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') document.getElementById('lbOverlay').style.display = 'none';
});
</script>
@endpush

@endsection