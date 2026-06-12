@extends('layouts.candidat')
@section('title', 'Choisir un plan')

@section('sidebar')
@include('candidat._sidebar')
@endsection

@section('content')
<div class="cand-page-header">
  <div class="cand-page-header__left">
    <a href="{{ route('candidat.abonnement') }}" class="cand-btn cand-btn--outline" style="margin-bottom:8px;font-size:12px">
      ← Retour à mes abonnements
    </a>
    <h1 class="cand-page-header__title">Choisir un plan</h1>
    <p class="cand-page-header__sub">Sélectionnez le plan adapté à vos besoins</p>
  </div>
</div>

@if(session('error'))
<div style="background:#fef2f2;border:1px solid #fecaca;border-radius:10px;padding:14px 18px;margin-bottom:20px;display:flex;gap:10px;align-items:center">
  <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#dc2626" stroke-width="2" style="flex-shrink:0"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
  <p style="color:#dc2626;font-size:13.5px;font-weight:600;margin:0">{{ session('error') }}</p>
</div>
@endif

@if($abonnement)
<div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:10px;padding:14px 18px;margin-bottom:24px;display:flex;gap:10px;align-items:center">
  <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#1d4ed8" stroke-width="2" style="flex-shrink:0"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01"/></svg>
  <p style="color:#1d4ed8;font-size:13.5px;font-weight:600;margin:0">
    Vous avez actuellement le plan <strong>{{ $abonnement->plan?->name }}</strong>. Choisir un nouveau plan remplacera le plan actif.
  </p>
</div>
@endif

@php
$featureLabels = [
    'cv_limit'         => ['label' => 'CVs publiables',          'bool' => false],
    'job_apply_limit'  => ['label' => 'Candidatures / offre',    'bool' => false],
    'featured_profile' => ['label' => 'Profil mis en avant',     'bool' => true],
    'candidate_search' => ['label' => 'Accès CVthèque recruteurs','bool' => true],
    'job_post_limit'   => ['label' => 'Offres publiables',        'bool' => false],
    'featured_jobs'    => ['label' => 'Offres mises en avant',   'bool' => false],
];
@endphp

@if($plans->isEmpty())
<div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:12px;padding:32px;text-align:center;color:#64748b">
  <p style="margin:0;font-size:14px">Aucun plan disponible pour le moment. Contactez l'administration.</p>
</div>
@else
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:20px">

  @foreach($plans as $plan)
  @php $isActif = $abonnement && $abonnement->plan_id === $plan->id; @endphp

  <div style="background:#fff;border:2px solid {{ $isActif ? '#185FA5' : '#e2e8f0' }};border-radius:16px;overflow:hidden;display:flex;flex-direction:column;
    {{ !$plan->is_free ? 'box-shadow:0 4px 28px rgba(24,95,165,.12)' : '' }}">

    <div style="padding:24px 24px 18px">
      <div style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#94a3b8;margin-bottom:6px">
        @if($plan->duration_days) {{ $plan->duration_days }} jours
        @else Durée illimitée
        @endif
      </div>
      <h3 style="font-size:1.5rem;font-weight:800;color:#042C53;margin:0 0 10px">{{ $plan->name }}</h3>
      @if($plan->description)
        <p style="font-size:13.5px;color:#64748b;line-height:1.55;margin:0 0 20px">{{ $plan->description }}</p>
      @endif

      <div style="display:flex;align-items:baseline;gap:4px;margin-bottom:4px">
        @if($plan->is_free)
          <span style="font-size:2.4rem;font-weight:800;color:#042C53">Gratuit</span>
        @else
          <span style="font-size:2.4rem;font-weight:800;color:#042C53">{{ number_format($plan->price, 0, ',', ' ') }}</span>
          <div>
            <div style="font-size:13px;font-weight:700;color:#185FA5;line-height:1">{{ $plan->currency }}</div>
            @if($plan->duration_days)
              <div style="font-size:12px;color:#94a3b8;line-height:1">/ {{ $plan->duration_days }} jours</div>
            @endif
          </div>
        @endif
      </div>

      @if($isActif)
        <div style="margin-top:16px;background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:10px;padding:11px;text-align:center">
          <span style="font-weight:700;color:#16a34a;font-size:14px">✓ Plan actuel</span>
        </div>
      @else
        <form method="POST" action="{{ route('candidat.abonnement.store') }}" style="margin-top:16px">
          @csrf
          <input type="hidden" name="plan_id" value="{{ $plan->id }}">
          <button type="submit" class="cand-btn cand-btn--yellow" style="width:100%;justify-content:center;font-size:14px;padding:12px">
            @if($plan->is_free) Activer gratuitement @else S'abonner @endif
          </button>
        </form>
      @endif
    </div>

    <div style="height:1px;background:#f1f5f9;margin:0 24px"></div>

    <div style="padding:20px 24px;flex:1">
      @if($plan->features->isNotEmpty())
        <p style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#374151;margin:0 0 14px">Ce qui est inclus</p>
        <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:9px">
          @foreach($plan->features as $feat)
            @php
              $def  = $featureLabels[$feat->feature_key] ?? ['label' => $feat->feature_key, 'bool' => false];
              $skip = $def['bool'] && (int)$feat->feature_value === 0;
            @endphp
            @if(!$skip)
            <li style="font-size:13.5px;color:#374151;display:flex;align-items:flex-start;gap:10px">
              <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="#16a34a" stroke-width="2.5" style="flex-shrink:0;margin-top:2px"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
              {{ $def['label'] }}
              @if(!$def['bool'] && $feat->feature_value !== null && $feat->feature_value !== '')
                : <strong>{{ $feat->feature_value }}</strong>
              @endif
            </li>
            @endif
          @endforeach
        </ul>
      @else
        <p style="font-size:13px;color:#94a3b8;font-style:italic;margin:0">Fonctionnalités non définies.</p>
      @endif
    </div>
  </div>
  @endforeach

</div>
@endif

{{-- Comment ça marche --}}
<div style="margin-top:28px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:14px;padding:22px 26px">
  <h4 style="font-size:14px;font-weight:700;color:#042C53;margin:0 0 12px">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="vertical-align:middle;margin-right:6px"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01"/></svg>
    Comment ça marche ?
  </h4>
  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:14px">
    @foreach([
      ['1', 'Choisissez votre plan', 'Sélectionnez le plan qui correspond à vos besoins de recherche d\'emploi.'],
      ['2', 'Paiement Mobile Money', 'Un conseiller vous contacte pour finaliser le paiement (MTN, Moov, Wave).'],
      ['3', 'Activé après confirmation', 'Dès validation par notre équipe, votre profil bénéficie de toutes les fonctionnalités.'],
    ] as [$n, $titre, $desc])
    <div style="display:flex;gap:12px;align-items:flex-start">
      <div style="width:28px;height:28px;border-radius:50%;background:#185FA5;color:#fff;font-size:13px;font-weight:800;display:flex;align-items:center;justify-content:center;flex-shrink:0">{{ $n }}</div>
      <div>
        <p style="font-weight:700;color:#042C53;font-size:13.5px;margin:0 0 4px">{{ $titre }}</p>
        <p style="font-size:12.5px;color:#64748b;margin:0;line-height:1.55">{{ $desc }}</p>
      </div>
    </div>
    @endforeach
  </div>
</div>

@endsection
