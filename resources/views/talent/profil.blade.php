@extends('layouts.candidat')
@section('title', 'Mon profil — Espace Talent')


@section('sidebar')
@include('candidat._sidebar')
@endsection
@php
  $disponibiliteLabels = [
    'immediatement' => 'Immédiatement',
    '1_mois'        => 'Dans 1 mois',
    '2_mois'        => 'Dans 2 mois',
    '3_mois'        => 'Dans 3 mois',
    'plus_3_mois'   => 'Plus de 3 mois',
  ];
  $contratLabels = ['cdi'=>'CDI','cdd'=>'CDD','stage'=>'Stage','alternance'=>'Alternance','interim'=>'Intérim'];

  if ($profil) {
    $travaux    = $profil->travaux;
    $completion = $profil->profil_completion;
    $checks = [
      ['label' => 'Photo de profil',       'done' => (bool)$profil->photo,           'pts' => 10, 'action' => route('talent.profil.edit').'#photo'],
      ['label' => 'Spécialité',            'done' => (bool)$profil->specialite,       'pts' => 10, 'action' => route('talent.profil.edit').'#specialite'],
      ['label' => 'Présentation',          'done' => (bool)$profil->bio,              'pts' => 10, 'action' => route('talent.profil.edit').'#bio'],
      ['label' => 'Compétences (min. 3)',  'done' => is_array($profil->competences) && count($profil->competences) >= 3, 'pts' => 20, 'action' => route('talent.profil.edit').'#competences'],
      ['label' => "Années d'expérience",   'done' => $profil->annees_experience !== null, 'pts' => 10, 'action' => route('talent.profil.edit').'#experience'],
      ['label' => 'Disponibilité',         'done' => (bool)$profil->disponibilite,    'pts' => 15, 'action' => route('talent.profil.edit').'#disponibilite'],
      ['label' => 'Types de contrat',      'done' => !empty($profil->types_contrat),  'pts' => 10, 'action' => route('talent.profil.edit').'#types_contrat'],
      ['label' => 'Lien vers travaux',     'done' => (bool)$profil->portfolio_url,    'pts' => 5,  'action' => route('talent.profil.edit').'#portfolio'],
      ['label' => 'Langues (min. 1)',      'done' => is_array($profil->langues) && count($profil->langues) >= 1, 'pts' => 10, 'action' => route('talent.profil.edit').'#langues'],
    ];
    $missing = array_filter($checks, fn($c) => !$c['done']);
    $done    = array_filter($checks, fn($c) =>  $c['done']);
  }
@endphp

@if(session('success'))<div id="flash-data" data-type="success" data-msg="{{ session('success') }}" style="display:none"></div>@endif
@if(session('error')  )<div id="flash-data" data-type="error"   data-msg="{{ session('error') }}"   style="display:none"></div>@endif

@section('content')

  <div class="cand-page-header">
    <div class="cand-page-header__left">
      <div class="cand-page-header__title">Mon profil</div>
      <div class="cand-page-header__sub">Votre profil public visible par les recruteurs</div>
    </div>
    @if($profil)
    <div class="cand-page-header__actions">
      <a href="{{ route('talent.profil.edit') }}" class="cand-btn cand-btn--primary">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
        Modifier
      </a>
    </div>
    @endif
  </div>

  @if(!$profil)

    <div class="cand-empty">
      <div class="cand-empty__icon">
        <svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
      </div>
      <div class="cand-empty__title">Profil non trouvé</div>
      <p class="cand-empty__text">Une erreur est survenue. Contactez le support.</p>
    </div>

  @else

    <div class="cand-2col">

      {{-- Colonne principale --}}
      <div>

        {{-- Carte identité --}}
        <div class="cand-card">
          <div style="display:flex;align-items:flex-start;gap:18px;flex-wrap:wrap">
            <div style="width:84px;height:84px;border-radius:50%;background:{{ $profil->plan==='premium' ? 'linear-gradient(135deg,#F5C842,#e0a800)' : 'linear-gradient(135deg,#d1fae5,#a7f3d0)' }};display:flex;align-items:center;justify-content:center;font-size:2rem;font-weight:800;color:{{ $profil->plan==='premium' ? '#042C53' : '#065f46' }};overflow:hidden;flex-shrink:0">
              @if($profil->photo)
                <img src="{{ asset('storage/'.$profil->photo) }}" alt="" style="width:84px;height:84px;border-radius:50%;object-fit:cover">
              @else
                {{ auth()->user()->initiale }}
              @endif
            </div>
            <div style="flex:1;min-width:0">
              <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;margin-bottom:3px">
                <h2 style="font-size:1.2rem;font-weight:800;color:#042C53;margin:0">{{ auth()->user()->nom_complet }}</h2>
                @if($profil->plan === 'premium')
                  <span class="cand-badge cand-badge--yellow">★ Premium</span>
                @endif
              </div>
              <p style="font-size:15px;font-weight:700;color:#38A169;margin:0 0 1px">{{ $profil->metier }}</p>
              @if($profil->specialite)
                <p style="font-size:13px;color:#64748b;margin:0 0 6px">{{ $profil->specialite }}</p>
              @endif
              <div style="display:flex;flex-wrap:wrap;gap:12px;font-size:12.5px;color:#94a3b8">
                @if($profil->ville || $profil->pays)
                  <span>
                    <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="vertical-align:-1px;margin-right:3px"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><circle cx="12" cy="11" r="3"/></svg>
                    {{ collect([$profil->ville, $profil->pays])->filter()->join(', ') }}
                  </span>
                @endif
                @if($profil->disponibilite)
                  <span class="cand-badge cand-badge--green">{{ $disponibiliteLabels[$profil->disponibilite] }}</span>
                @endif
                @if($profil->vues)
                  <span style="color:#94a3b8">{{ $profil->vues }} vue{{ $profil->vues > 1 ? 's' : '' }}</span>
                @endif
              </div>
            </div>
            <a href="{{ route('talent.profil.edit') }}" class="cand-btn cand-btn--outline cand-btn--sm">Modifier</a>
          </div>
        </div>

        {{-- Présentation --}}
        @if($profil->bio)
        <div class="cand-card">
          <div class="cand-card__head">
            <div class="cand-card__title">
              <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h7"/></svg>
              Présentation
            </div>
          </div>
          <p style="font-size:14px;color:#374151;line-height:1.75;margin:0">{{ $profil->bio }}</p>
        </div>
        @endif

        {{-- Compétences --}}
        @if(!empty($profil->competences))
        <div class="cand-card">
          <div class="cand-card__head">
            <div class="cand-card__title">
              <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
              Compétences techniques
            </div>
          </div>
          <div style="display:flex;flex-wrap:wrap;gap:8px">
            @foreach($profil->competences as $comp)
              <span class="cand-badge cand-badge--green">{{ $comp }}</span>
            @endforeach
          </div>
        </div>
        @endif

        {{-- Expériences professionnelles --}}
        @if($profil->experiences->isNotEmpty())
        <div class="cand-card">
          <div class="cand-card__head">
            <div class="cand-card__title">
              <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>
              Expériences professionnelles
            </div>
          </div>
          @foreach($profil->experiences as $exp)
          <div style="display:flex;gap:12px;padding:10px 0;{{ !$loop->last ? 'border-bottom:1px solid #f0f2f5' : '' }}">
            <div style="width:34px;height:34px;background:#f0fdf4;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px">
              <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#38A169" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>
            </div>
            <div>
              <p style="font-weight:700;color:#1e293b;font-size:14px;margin:0 0 1px">{{ $exp->poste }}</p>
              @if($exp->employeur)<p style="font-size:13px;color:#64748b;margin:0 0 1px">{{ $exp->employeur }}</p>@endif
              <p style="font-size:12px;color:#94a3b8;margin:0 0 4px">{{ $exp->periode }}</p>
              @if($exp->description)<p style="font-size:13px;color:#374151;margin:0;line-height:1.6">{{ $exp->description }}</p>@endif
            </div>
          </div>
          @endforeach
        </div>
        @endif

        {{-- Formations --}}
        @if($profil->formations->isNotEmpty())
        <div class="cand-card">
          <div class="cand-card__head">
            <div class="cand-card__title">
              <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path stroke-linecap="round" stroke-linejoin="round" d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
              Formations & Diplômes
            </div>
          </div>
          @foreach($profil->formations as $form)
          <div style="display:flex;gap:12px;padding:10px 0;{{ !$loop->last ? 'border-bottom:1px solid #f0f2f5' : '' }}">
            <div style="width:34px;height:34px;background:#f0fdf4;border-radius:8px;display:flex;align-items:center;justify-content:center;flex-shrink:0;margin-top:2px">
              <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#38A169" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/></svg>
            </div>
            <div>
              <p style="font-weight:700;color:#1e293b;font-size:14px;margin:0 0 1px">{{ $form->diplome }}</p>
              @if($form->etablissement)<p style="font-size:13px;color:#64748b;margin:0 0 1px">{{ $form->etablissement }}</p>@endif
              @if($form->annee_obtention)<p style="font-size:12px;color:#94a3b8;margin:0 0 4px">{{ $form->annee_obtention }}</p>@endif
              @if($form->description)<p style="font-size:13px;color:#374151;margin:0;line-height:1.6">{{ $form->description }}</p>@endif
            </div>
          </div>
          @endforeach
        </div>
        @endif

        {{-- Disponibilité & Contrat --}}
        @if($profil->annees_experience !== null || $profil->disponibilite || !empty($profil->types_contrat))
        <div class="cand-card">
          <div class="cand-card__head">
            <div class="cand-card__title">
              <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" d="M16 2v4M8 2v4M3 10h18"/></svg>
              Disponibilité & Contrat
            </div>
          </div>
          <div style="display:flex;flex-wrap:wrap;gap:20px">
            @if($profil->annees_experience !== null)
            <div>
              <p style="font-size:11.5px;color:#94a3b8;margin:0 0 3px;text-transform:uppercase;letter-spacing:.05em">Expérience</p>
              <p style="font-size:14px;font-weight:600;color:#1e293b;margin:0">{{ $profil->annees_experience }} an{{ $profil->annees_experience > 1 ? 's' : '' }}</p>
            </div>
            @endif
            @if($profil->disponibilite)
            <div>
              <p style="font-size:11.5px;color:#94a3b8;margin:0 0 3px;text-transform:uppercase;letter-spacing:.05em">Disponible</p>
              <p style="font-size:14px;font-weight:600;color:#1e293b;margin:0">{{ $disponibiliteLabels[$profil->disponibilite] }}</p>
            </div>
            @endif
            @if(!empty($profil->types_contrat))
            <div>
              <p style="font-size:11.5px;color:#94a3b8;margin:0 0 6px;text-transform:uppercase;letter-spacing:.05em">Contrats</p>
              <div style="display:flex;flex-wrap:wrap;gap:5px">
                @foreach($profil->types_contrat as $tc)
                  <span class="cand-badge cand-badge--blue">{{ $contratLabels[$tc] ?? $tc }}</span>
                @endforeach
              </div>
            </div>
            @endif
          </div>
        </div>
        @endif

        {{-- Langues --}}
        @if(!empty($profil->langues))
        <div class="cand-card">
          <div class="cand-card__head">
            <div class="cand-card__title">
              <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
              Langues
            </div>
          </div>
          <div style="display:flex;flex-wrap:wrap;gap:8px">
            @foreach($profil->langues as $lang)
              <span class="cand-badge cand-badge--gray">
                {{ $lang['langue'] ?? '' }}
                @if(!empty($lang['niveau']))
                  · <span style="opacity:.7">{{ $lang['niveau'] }}</span>
                @endif
              </span>
            @endforeach
          </div>
        </div>
        @endif

        {{-- Attestations --}}
        @if($profil->attestations->isNotEmpty())
        <div class="cand-card">
          <div class="cand-card__head">
            <div class="cand-card__title">
              <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
              Attestations & Certificats
            </div>
          </div>
          <div style="display:flex;flex-direction:column;gap:8px">
            @foreach($profil->attestations as $att)
            <a href="{{ asset('storage/'.$att->fichier) }}" target="_blank" rel="noopener"
               style="display:flex;align-items:center;gap:10px;padding:9px 12px;border:1px solid #e2e8f0;border-radius:8px;background:#f8fafc;text-decoration:none;transition:border-color .15s"
               onmouseover="this.style.borderColor='#38A169'" onmouseout="this.style.borderColor='#e2e8f0'">
              <div style="width:30px;height:30px;background:#f0fdf4;border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                @if($att->est_image)
                  <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="#38A169" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                @else
                  <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="#38A169" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                @endif
              </div>
              <span style="font-size:13.5px;font-weight:600;color:#374151;flex:1">{{ $att->nom }}</span>
              <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="#94a3b8" stroke-width="2"><path d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
            @endforeach
          </div>
        </div>
        @endif

        {{-- Photos de travaux --}}
        @if($travaux->isNotEmpty())
        <div class="cand-card">
          <div class="cand-card__head">
            <div class="cand-card__title">
              <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
              Photos de travaux
            </div>
          </div>
          <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:10px">
            @foreach($travaux as $t)
            <div style="border-radius:9px;overflow:hidden;border:1px solid #e2e8f0;cursor:pointer" onclick="openLightbox('{{ asset('storage/'.$t->photo) }}')">
              <img src="{{ asset('storage/'.$t->photo) }}" alt="{{ $t->description ?? '' }}"
                   style="width:100%;height:120px;object-fit:cover;display:block">
              @if($t->description)
                <p style="font-size:12px;color:#64748b;margin:0;padding:6px 8px;line-height:1.4">{{ $t->description }}</p>
              @endif
            </div>
            @endforeach
          </div>
        </div>
        @endif

        {{-- CTA Premium --}}
        @if($profil->plan !== 'premium')
        <div style="background:linear-gradient(135deg,#f0fdf4,#dcfce7);border:1px solid #bbf7d0;border-radius:10px;padding:18px 22px;display:flex;align-items:center;gap:16px;flex-wrap:wrap">
          <div style="flex:1">
            <p style="font-weight:700;color:#042C53;margin:0 0 3px">★ Passez au Premium — 3 000 FCFA/mois</p>
            <p style="font-size:13px;color:#64748b;margin:0">Profil mis en avant · coordonnées visibles · badge Premium</p>
          </div>
          <a href="{{ route('talent.abonnement') }}" class="cand-btn cand-btn--primary">Passer au Premium</a>
        </div>
        @endif

      </div>

      {{-- Sidebar complétion (sticky) --}}
      <div style="position:sticky;top:88px;display:flex;flex-direction:column;gap:14px">

        <div class="cand-card" style="margin-bottom:0">
          <div class="cand-card__head">
            <div class="cand-card__title">Complétion du profil</div>
          </div>

          <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px">
            <div style="flex:1;height:7px;background:#e2e8f0;border-radius:99px;overflow:hidden">
              <div style="height:100%;width:{{ $completion }}%;background:{{ $completion >= 80 ? '#38A169' : ($completion >= 50 ? '#F5C842' : '#ef4444') }};border-radius:99px;transition:width .4s ease"></div>
            </div>
            <span style="font-size:13px;font-weight:800;color:{{ $completion >= 80 ? '#38A169' : ($completion >= 50 ? '#b45309' : '#ef4444') }};min-width:34px;text-align:right">{{ $completion }}%</span>
          </div>

          @if(count($missing) === 0)
            <p style="font-size:13px;color:#38A169;font-weight:600;margin:0;text-align:center;padding:6px 0">
              <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="vertical-align:-2px"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
              Profil complet !
            </p>
          @else
            <p style="font-size:12px;color:#6b7a8d;margin:0 0 10px">{{ count($missing) }} élément{{ count($missing) > 1 ? 's' : '' }} manquant{{ count($missing) > 1 ? 's' : '' }} :</p>
            <div style="display:flex;flex-direction:column;gap:5px">
              @foreach($missing as $check)
              <a href="{{ $check['action'] }}" style="display:flex;align-items:center;gap:8px;text-decoration:none;padding:7px 9px;border-radius:7px;background:#f8fafc;border:1px solid #e2e8f0" onmouseover="this.style.borderColor='#38A169'" onmouseout="this.style.borderColor='#e2e8f0'">
                <div style="width:18px;height:18px;border-radius:50%;background:#fef2f2;border:1.5px solid #fca5a5;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                  <svg width="7" height="7" fill="none" viewBox="0 0 24 24" stroke="#ef4444" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16M4 12h16"/></svg>
                </div>
                <div style="flex:1;min-width:0">
                  <p style="font-size:12px;font-weight:600;color:#374151;margin:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $check['label'] }}</p>
                  <p style="font-size:10.5px;color:#94a3b8;margin:0">+{{ $check['pts'] }} pts</p>
                </div>
              </a>
              @endforeach
            </div>
          @endif

          @if(count($done) > 0)
          <details style="margin-top:12px">
            <summary style="font-size:11.5px;color:#94a3b8;cursor:pointer;list-style:none">{{ count($done) }} élément{{ count($done) > 1 ? 's' : '' }} complété{{ count($done) > 1 ? 's' : '' }}</summary>
            <div style="margin-top:8px;display:flex;flex-direction:column;gap:4px">
              @foreach($done as $check)
              <div style="display:flex;align-items:center;gap:7px;font-size:12px;color:#64748b">
                <div style="width:16px;height:16px;border-radius:50%;background:#f0fdf4;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                  <svg width="7" height="7" fill="none" viewBox="0 0 24 24" stroke="#38A169" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </div>
                {{ $check['label'] }}
              </div>
              @endforeach
            </div>
          </details>
          @endif
        </div>

        <a href="{{ route('talent.profil.edit') }}" class="cand-btn cand-btn--primary" style="justify-content:center">
          <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
          Compléter mon profil
        </a>

      </div>

    </div>

  @endif

@endsection

{{-- Lightbox --}}
<div id="lbOverlay" onclick="closeLightbox()" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.85);z-index:9999;align-items:center;justify-content:center;cursor:zoom-out">
  <img id="lbImg" src="" alt="" style="max-width:90vw;max-height:90vh;border-radius:8px;box-shadow:0 20px 60px rgba(0,0,0,.5)">
</div>

@section('scripts')
<script>
function openLightbox(src) {
  const lb = document.getElementById('lbOverlay');
  document.getElementById('lbImg').src = src;
  lb.style.display = 'flex';
}
function closeLightbox() {
  document.getElementById('lbOverlay').style.display = 'none';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeLightbox(); });

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
