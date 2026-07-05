@extends('layouts.candidat')
@section('title', 'Mon espace Candidat')

@section('sidebar')
    @include('candidat._sidebar')
@endsection

@section('content')
    <div class="cand-page-header">
        <div class="cand-page-header__left">
            <h1 class="cand-page-header__title">Bonjour, {{ auth()->user()->prenom }}</h1>
            <p class="cand-page-header__sub">Voici un résumé de votre activité sur la plateforme.</p>
        </div>
        <div class="cand-page-header__actions">
            <a href="{{ route('offre.list') }}" class="cand-btn cand-btn--yellow">
                <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8" />
                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                </svg>
                Chercher des offres
            </a>
        </div>
    </div>

    {{-- Bannière abonnement --}}
    @if ($abonnement)
        @php
            $plan = $abonnement->plan;
            $isPremium = $plan && !$plan->is_free;
            $cvPct =
                $quotas && !$quotas['cvs']['unlimited'] && $quotas['cvs']['limit'] > 0
                    ? min(100, round(($quotas['cvs']['used'] / $quotas['cvs']['limit']) * 100))
                    : 0;
            $appPct =
                $quotas && !$quotas['candidatures']['unlimited'] && $quotas['candidatures']['limit'] > 0
                    ? min(100, round(($quotas['candidatures']['used'] / $quotas['candidatures']['limit']) * 100))
                    : 0;
            $cvColor = $cvPct >= 90 ? '#dc2626' : ($cvPct >= 70 ? '#d97706' : '#16a34a');
            $appColor = $appPct >= 90 ? '#dc2626' : ($appPct >= 70 ? '#d97706' : '#16a34a');
        @endphp
        <div
            style="background:{{ $isPremium ? 'linear-gradient(135deg,#042C53,#185FA5)' : '#f8fafc' }};border:1.5px solid {{ $isPremium ? 'transparent' : '#e2e8f0' }};border-radius:14px;padding:20px 24px;margin-bottom:22px;display:flex;flex-wrap:wrap;gap:20px;align-items:center">

            {{-- Info plan --}}
            <div style="flex:1;min-width:180px">
                <p
                    style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:{{ $isPremium ? 'rgba(255,255,255,.55)' : '#94a3b8' }};margin:0 0 4px">
                    Plan actif</p>
                <p style="font-size:1.15rem;font-weight:800;color:{{ $isPremium ? '#F5C842' : '#042C53' }};margin:0 0 2px">
                    {{ $plan?->name ?? 'Gratuit' }}</p>
                @if ($abonnement->ends_at)
                    <p style="font-size:12px;color:{{ $isPremium ? 'rgba(255,255,255,.6)' : '#64748b' }};margin:0">Expire le
                        {{ $abonnement->ends_at->format('d/m/Y') }} <span
                            style="opacity:.7">({{ $abonnement->ends_at->diffForHumans() }})</span></p>
                @else
                    <p style="font-size:12px;color:{{ $isPremium ? 'rgba(255,255,255,.6)' : '#64748b' }};margin:0">Sans
                        date d'expiration</p>
                @endif
            </div>



            @if (!$isPremium)
                <a href="{{ route('candidat.abonnement.pourquoi') }}"
                    style="display:inline-flex;align-items:center;gap:7px;padding:9px 16px;background:#F5C842;color:#042C53;border-radius:8px;font-weight:700;font-size:13px;text-decoration:none;white-space:nowrap">
                    <svg width="13" height="13" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                    </svg>
                    Découvrir les avantages →
                </a>
            @endif
        </div>
    @endif

    {{-- Abonnement déjà souscrit mais pas encore en vigueur --}}
    @if($abonnementProgramme)
        <div style="display:flex;align-items:flex-start;gap:14px;background:#f0fdf4;border:1.5px solid #86efac;border-radius:14px;padding:18px 20px;margin-bottom:22px">
            <span style="flex-shrink:0;width:38px;height:38px;border-radius:10px;background:#dcfce7;display:flex;align-items:center;justify-content:center">
                <svg width="19" height="19" fill="none" viewBox="0 0 24 24" stroke="#16a34a" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            </span>
            <div style="flex:1">
                <p style="font-size:13.5px;font-weight:700;color:#15803d;margin:0 0 4px">
                    Nouvel abonnement « {{ $abonnementProgramme->plan?->name }} » en attente
                </p>
                <p style="font-size:12.5px;color:#166534;margin:0;line-height:1.5">
                    Souscrit le {{ $abonnementProgramme->created_at->format('d/m/Y') }} — il prendra automatiquement le relais le
                    <strong>{{ $abonnementProgramme->starts_at->format('d/m/Y') }}</strong> ({{ $abonnementProgramme->starts_at->diffForHumans() }}),
                    à la fin de votre plan actuel ci-dessus (ou avant, si celui-ci épuise un de ses avantages plus tôt).
                </p>
            </div>
        </div>
    @endif

    {{-- Stats --}}
    @php
        $isPremiumUser = $abonnement && $abonnement->plan && !$abonnement->plan->is_free;
        $canSeeViews =
            $abonnement && $abonnement->plan && $abonnement->plan->getFeature('show_profile_views', '0') === '1';
    @endphp
    <div class="cand-stats">
        <a href="{{ route('candidat.candidatures') }}" class="" style="text-decoration: none;">

            <div class="cand-stat">
                <div class="cand-stat__icon cand-stat__icon--blue">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.41 2 2 0 0 1 3.6 1.21h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.68 2.81a2 2 0 0 1-.45 2.11L7.91 8.76A16 16 0 0 0 12 12a16 16 0 0 0 3.24 1.91l1.91-1.91a2 2 0 0 1 2.11-.45c.91.32 1.85.55 2.81.68A2 2 0 0 1 24 14.21v2.71Z" />
                    </svg>
                </div>
                <div>
                    <div class="cand-stat__val">{{ $stats['candidatures'] }}</div>
                    <div class="cand-stat__label">Candidatures envoyées</div>
                </div>
            </div>
        </a>

        <a href="{{ route('candidat.cvs') }}" class=""  style="text-decoration: none;">

            <div class="cand-stat">
                <div class="cand-stat__icon cand-stat__icon--dark">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 2 14 8 20 8" />
                    </svg>
                </div>
                <div>
                    <div class="cand-stat__val">{{ $stats['cvs'] }}</div>
                    <div class="cand-stat__label">Cv & diplôme</div>
                </div>
            </div>
        </a>

        
            <div class="cand-stat">
                <div class="cand-stat__icon cand-stat__icon--yellow">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                </div>
                <div>
                    @if ($canSeeViews)
                        <div class="cand-stat__val">{{ $stats['offres_vues'] }}</div>
                    @else
                        <a href="{{ route('candidat.abonnement.plans') }}"
                            style="display:inline-flex;align-items:center;gap:5px;background:#fef9c3;color:#92400e;font-size:11px;font-weight:700;padding:3px 10px;border-radius:99px;text-decoration:none;border:1px solid #fde68a">🔒
                            Premium</a>
                    @endif
                    <div class="cand-stat__label">Vues par recruteurs</div>
                </div>
            </div>

  

        <a href="{{ route('candidat.candidatures') }}?q=&statut=retenue" class=""  style="text-decoration: none;">


            <div class="cand-stat">
                <div class="cand-stat__icon cand-stat__icon--green">
                    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12" />
                    </svg>
                </div>
                <div>
                    <div class="cand-stat__val" style="color:#38A169">{{ $stats['retenues'] }}</div>
                    <div class="cand-stat__label">Retenues</div>
                </div>
            </div>

        </a>
    </div>

    {{-- Alerte upload si aucun document --}}
    @if($user->cvs->isEmpty() && $user->documents->isEmpty())
    <div style="display:flex;align-items:flex-start;gap:14px;background:#fff7ed;border:1.5px solid #fdba74;border-radius:14px;padding:18px 20px;margin-bottom:22px">
        <span style="flex-shrink:0;width:38px;height:38px;border-radius:10px;background:#ffedd5;display:flex;align-items:center;justify-content:center">
            <svg width="19" height="19" fill="none" viewBox="0 0 24 24" stroke="#ea580c" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
        </span>
        <div style="flex:1">
            <p style="font-size:13.5px;font-weight:700;color:#9a3412;margin:0 0 4px">Aucun document déposé</p>
            <p style="font-size:12.5px;color:#c2410c;margin:0 0 12px;line-height:1.5">Déposez votre CV, diplôme ou attestation pour être visible auprès des recruteurs. C'est obligatoire pour postuler.</p>
            <a href="{{ route('cv.public.depot') }}" style="display:inline-flex;align-items:center;gap:7px;padding:9px 16px;background:#ea580c;color:#fff;border-radius:8px;font-weight:700;font-size:13px;text-decoration:none">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                Déposer mon CV maintenant
            </a>
        </div>
    </div>
    @endif

    {{-- Bloc CV Professionnel --}}
    <div style="display:flex;align-items:center;gap:16px;background:linear-gradient(135deg,#042C53 0%,#185FA5 100%);border-radius:14px;padding:20px 24px;margin-bottom:22px">
        <span style="flex-shrink:0;width:44px;height:44px;border-radius:12px;background:rgba(245,200,66,.15);display:flex;align-items:center;justify-content:center">
            <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#F5C842" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        </span>
        <div style="flex:1;min-width:0">
            <p style="font-size:13px;font-weight:800;color:#F5C842;margin:0 0 3px;text-transform:uppercase;letter-spacing:.05em">CV Professionnel</p>
            <p style="font-size:13px;color:rgba(255,255,255,.85);margin:0;line-height:1.5">Faites rédiger votre CV par nos experts · Livraison en <strong style="color:#fff">{{ $cvService->delai ?? '30 min à 1h' }}</strong> · <strong style="color:#F5C842">{{ $cvService ? number_format($cvService->prix, 0, ',', ' ').' FCFA' : '2 500 FCFA' }}</strong></p>
        </div>
        <a href="{{ route('service.commande', 'cv-professionnel') }}" style="flex-shrink:0;display:inline-flex;align-items:center;gap:6px;padding:10px 18px;background:#F5C842;color:#042C53;border-radius:8px;font-weight:800;font-size:13px;text-decoration:none;white-space:nowrap">
            Commander →
        </a>
    </div>

    {{-- Actions rapides --}}
    @php
        $cvBloque = $quotas && !$quotas['cvs']['unlimited'] && $quotas['cvs']['used'] >= $quotas['cvs']['limit'];
        $appBloque =
            $quotas &&
            !$quotas['candidatures']['unlimited'] &&
            $quotas['candidatures']['used'] >= $quotas['candidatures']['limit'];
        $hasFeatured = $quotas && $quotas['featured_profile'];
    @endphp

    @if (!$isPremiumUser)
        <style>
            .dash-main-wrap {
                display: grid;
                grid-template-columns: 1fr 300px;
                gap: 20px;
                align-items: start
            }

            @media(max-width:900px) {
                .dash-main-wrap {
                    grid-template-columns: 1fr
                }
            }

            .dash-main-wrap .dash-premium-col {
                position: sticky;
                top: 20px
            }
        </style>
        <div class="dash-main-wrap">
            <div>{{-- Colonne principale --}}
    @endif

    <div class="cand-card">
        <div class="cand-card__head">
            <h2 class="cand-card__title">Actions rapides</h2>
        </div>
        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:12px">

            {{-- Chercher des offres (toujours disponible) --}}
            <a href="{{ route('offre.list') }}"
                style="display:flex;flex-direction:column;align-items:center;gap:10px;background:#f8fafc;border:1.5px solid #e2e6ed;border-radius:10px;padding:18px 14px;text-decoration:none;color:#042C53;font-size:13px;font-weight:600;transition:border-color .2s,box-shadow .2s"
                onmouseover="this.style.borderColor='#378ADD';this.style.boxShadow='0 2px 12px rgba(55,138,221,.12)'"
                onmouseout="this.style.borderColor='#e2e6ed';this.style.boxShadow='none'">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#378ADD"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8" />
                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                </svg>
                Chercher des offres
            </a>

            {{-- Déposer un CV, bloqué si quota atteint --}}
            @if ($cvBloque)
                <a href="{{ route('candidat.abonnement.plans') }}"
                    style="display:flex;flex-direction:column;align-items:center;gap:10px;background:#fff7ed;border:1.5px solid #fdba74;border-radius:10px;padding:18px 14px;text-decoration:none;color:#c2410c;font-size:13px;font-weight:600">
                    <span style="position:relative;display:inline-block">
                        <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#c2410c"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                            <polyline points="17 8 12 3 7 8" />
                            <line x1="12" y1="3" x2="12" y2="15" />
                        </svg>
                        <svg width="12" height="12" fill="#c2410c" viewBox="0 0 24 24"
                            style="position:absolute;bottom:-3px;right:-5px">
                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2" />
                            <path d="M7 11V7a5 5 0 0 1 10 0v4" />
                        </svg>
                    </span>
                    Déposer un CV
                    <span style="font-size:11px;font-weight:400;color:#c2410c">Limite atteinte, Upgrader</span>
                </a>
            @else
                <a href="{{ route('cv.public.depot') }}"
                    style="display:flex;flex-direction:column;align-items:center;gap:10px;background:#f8fafc;border:1.5px solid #e2e6ed;border-radius:10px;padding:18px 14px;text-decoration:none;color:#042C53;font-size:13px;font-weight:600;transition:border-color .2s,box-shadow .2s"
                    onmouseover="this.style.borderColor='#378ADD';this.style.boxShadow='0 2px 12px rgba(55,138,221,.12)'"
                    onmouseout="this.style.borderColor='#e2e6ed';this.style.boxShadow='none'">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#378ADD"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                        <polyline points="17 8 12 3 7 8" />
                        <line x1="12" y1="3" x2="12" y2="15" />
                    </svg>
                    Déposer un CV
                </a>
            @endif

            {{-- Alertes emploi --}}
            <a href="{{ route('candidat.alertes') }}"
                style="display:flex;flex-direction:column;align-items:center;gap:10px;background:#f8fafc;border:1.5px solid #e2e6ed;border-radius:10px;padding:18px 14px;text-decoration:none;color:#042C53;font-size:13px;font-weight:600;transition:border-color .2s,box-shadow .2s"
                onmouseover="this.style.borderColor='#378ADD';this.style.boxShadow='0 2px 12px rgba(55,138,221,.12)'"
                onmouseout="this.style.borderColor='#e2e6ed';this.style.boxShadow='none'">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#378ADD"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" />
                    <path d="M13.73 21a2 2 0 0 1-3.46 0" />
                </svg>
                Créer une alerte emploi
            </a>

            {{-- Profil mis en avant, premium uniquement --}}
            @if ($hasFeatured)
                <a href="{{ route('candidat.profil') }}"
                    style="display:flex;flex-direction:column;align-items:center;gap:10px;background:linear-gradient(135deg,#fffbeb,#fef3c7);border:1.5px solid #fde68a;border-radius:10px;padding:18px 14px;text-decoration:none;color:#92400e;font-size:13px;font-weight:600">
                    <svg width="22" height="22" fill="#F5C842" viewBox="0 0 24 24">
                        <path
                            d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                    </svg>
                    Profil en avant
                    <span style="font-size:11px;color:#b45309;font-weight:400">Actif sur votre plan</span>
                </a>
            @else
                <a href="{{ route('candidat.abonnement.plans') }}"
                    style="display:flex;flex-direction:column;align-items:center;gap:10px;background:#f8fafc;border:1.5px dashed #cbd5e1;border-radius:10px;padding:18px 14px;text-decoration:none;color:#94a3b8;font-size:13px;font-weight:600">
                    <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#cbd5e1"
                        stroke-width="2">
                        <path
                            d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                    </svg>
                    Profil en avant
                    <span
                        style="font-size:11px;color:#F5C842;font-weight:700;background:#042C53;padding:2px 8px;border-radius:20px">Premium</span>
                </a>
            @endif

        </div>
    </div>

    {{-- Dernières candidatures --}}
    <div class="cand-card">
        <div class="cand-card__head">

            <h2 class="cand-card__title">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                    <polyline points="14 2 14 8 20 8" />
                </svg>
                Dernières candidatures
            </h2>
            <a href="{{ route('candidat.candidatures') }}" class="cand-btn cand-btn--outline cand-btn--sm">Voir tout <svg
                    width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                    style="display:inline-block;vertical-align:-2px">
                    <line x1="5" y1="12" x2="19" y2="12" />
                    <polyline points="12 5 19 12 12 19" />
                </svg></a>
        </div>

        @if ($dernieres_candidatures->isEmpty())
            <div class="cand-empty">
                <div class="cand-empty__icon">
                    <svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <p class="cand-empty__title">Aucune candidature</p>
                <p class="cand-empty__text">Vous n'avez pas encore postulé à une offre. Parcourez les annonces disponibles.
                </p>
                <a href="{{ route('offre.list') }}" class="cand-btn cand-btn--yellow">Parcourir les offres</a>
            </div>
        @else
            <div class="cand-table-wrap">
                <table class="cand-table">
                    <thead>
                        <tr>
                            <th>Poste</th>
                            <th>Entreprise</th>
                            <th>Statut</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($dernieres_candidatures as $c)
                            <tr>
                                <td><a href="{{ route('offre.detail', $c->offre) }}"
                                        style="color:#185FA5;font-weight:600;text-decoration:none">{{ $c->offre->titre }}</a>
                                </td>
                                <td style="color:#6b7a8d">{{ $c->offre->entreprise }}</td>
                                <td>
                                    <span
                                        class="cand-badge cand-badge--{{ match ($c->statut) {
                                            'envoyee' => 'blue',
                                            'vue' => 'yellow',
                                            'retenue' => 'green',
                                            'refusee' => 'red',
                                            'entretien' => 'green',
                                            default => 'gray',
                                        } }}">
                                        {{ match ($c->statut) {
                                            'envoyee' => 'Envoyée',
                                            'vue' => 'Vue',
                                            'retenue' => '✓ Retenue',
                                            'refusee' => 'Refusée',
                                            'entretien' => 'Entretien',
                                            default => ucfirst($c->statut),
                                        } }}
                                    </span>
                                </td>
                                <td style="color:#6b7a8d;font-size:12px">{{ $c->created_at->format('d/m/Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    @if (!$isPremiumUser)
        </div>{{-- fin colonne principale --}}

        {{-- Colonne carte premium --}}
        <div class="dash-premium-col">
            <div
                style="background:linear-gradient(160deg,#042C53 0%,#185FA5 100%);border-radius:16px;padding:24px 20px;color:#fff;position:relative;overflow:hidden">

                {{-- Décor --}}
                <div
                    style="position:absolute;top:-30px;right:-30px;width:120px;height:120px;background:rgba(245,200,66,.08);border-radius:50%;pointer-events:none">
                </div>

                {{-- Badge --}}
                <div
                    style="display:inline-flex;align-items:center;gap:5px;background:#F5C842;color:#042C53;font-size:10.5px;font-weight:800;text-transform:uppercase;letter-spacing:.08em;padding:4px 10px;border-radius:99px;margin-bottom:16px">
                    <svg width="11" height="11" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z" />
                    </svg>
                    Premium
                </div>

                {{-- Titre --}}
                <h3 style="font-size:1.05rem;font-weight:800;color:#fff;margin:0 0 6px;line-height:1.3">
                    {{ $plan_premium?->name ?? 'Premium' }} </h3>
                <p style="font-size:12px;color:rgba(255,255,255,.65);margin:0 0 20px;line-height:1.6">
                    {{ $plan_premium?->description ?? 'Accédez à toutes les fonctionnalités premium.' }} </p>

                {{-- Prix --}}
                <div
                    style="background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.12);border-radius:10px;padding:12px 14px;margin-bottom:20px;text-align:center">
                    <div style="font-size:1.6rem;font-weight:900;color:#F5C842;line-height:1"> {{ $plan_premium?->price ?? '1 500' }}
                        <span style="font-size:.9rem;font-weight:700">FCFA</span>
                    </div>
                    <div style="font-size:11px;color:rgba(255,255,255,.55);margin-top:3px">par mois seulement</div>
                </div>

                {{-- Avantages --}}
                <ul style="list-style:none;padding:0;margin:0 0 22px;display:flex;flex-direction:column;gap:10px">
                    @foreach ([
            ['M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'Candidatures illimitées'],
            ['M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12', 'Dépôt de plusieurs CV'],
            ['M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z', 'Profil en tête de la CVthèque'],
            ['M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'CV recommandé aux entreprises'],
            ['M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z', 'Vues de votre CV par recruteurs'],
            ['M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9', 'Alertes emploi en temps réel'],
        ] as [$icon, $label])
                        <li style="display:flex;align-items:center;gap:9px;font-size:12.5px;color:rgba(255,255,255,.9)">
                            <span
                                style="width:20px;height:20px;background:rgba(245,200,66,.15);border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                                <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="#F5C842"
                                    stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}" />
                                </svg>
                            </span>
                            {{ $label }}
                        </li>
                    @endforeach
                </ul>

                {{-- CTA --}}
                <div style="margin-top:16px">
                    <a href="{{ route('candidat.abonnement.pourquoi') }}"
                        style="display:flex;align-items:center;justify-content:center;gap:8px;width:100%;padding:13px 18px;background:#F5C842;color:#042C53;border-radius:10px;font-weight:800;font-size:14px;text-decoration:none;transition:background .18s,transform .18s"
                        onmouseover="this.style.background='#f0bc1e';this.style.transform='translateY(-1px)'"
                        onmouseout="this.style.background='#F5C842';this.style.transform='none'">
                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        Voir pourquoi passer Premium
                    </a>
                    <p style="text-align:center;font-size:11px;color:rgba(255,255,255,.4);margin:10px 0 0">1 500 FCFA/mois
                        · Sans engagement · Résiliable à tout moment</p>
                </div>
            </div>
        </div>

        </div>{{-- fin dash-main-wrap --}}
    @endif

@endsection
