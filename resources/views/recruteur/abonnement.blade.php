@extends('layouts.recruteur')
@section('title', 'Mon abonnement recruteur')

@section('sidebar')
@include('recruteur._sidebar')
@endsection

@section('content')
<div class="rec-topbar">
  <div class="rec-topbar__left">
    <h1>Mon abonnement</h1>
    <p>Choisissez le plan adapté à vos besoins de recrutement</p>
  </div>
</div>

{{-- Plan actuel --}}
@if($abonnement)
<div style="background:linear-gradient(135deg,#021e3a 0%,#185FA5 100%);border-radius:14px;padding:24px 28px;margin-bottom:32px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px">
  <div>
    <p style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.55);margin:0 0 5px">Plan actuel</p>
    <h2 style="font-size:1.7rem;font-weight:800;margin:0;color:#fff">
      {{ $abonnement->plan === 'premium_30' ? 'Premium' : 'Illimité' }}
    </h2>
    @if($abonnement->expire_le)
      <p style="font-size:13px;margin:6px 0 0;color:rgba(255,255,255,.65)">
        Expire le {{ $abonnement->expire_le->format('d/m/Y') }}
      </p>
    @endif
  </div>
  <div style="background:rgba(255,255,255,.12);border-radius:10px;padding:14px 22px;text-align:right">
    <p style="font-size:1.4rem;font-weight:800;color:#F5C842;margin:0">{{ number_format($abonnement->prix,0,',',' ') }} FCFA</p>
    <p style="font-size:12px;color:rgba(255,255,255,.55);margin:2px 0 0">/ mois</p>
  </div>
</div>
@else
<div style="background:#fffbeb;border:1px solid #fde68a;border-radius:12px;padding:18px 22px;margin-bottom:28px;display:flex;align-items:center;gap:12px">
  <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#92400e" stroke-width="2" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
  <p style="font-size:13.5px;color:#92400e;margin:0;font-weight:600">
    Vous n'avez pas encore d'abonnement actif. Souscrivez ci-dessous pour commencer à publier vos annonces.
  </p>
</div>
@endif

{{-- Cartes plans --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:20px">

  @foreach($plans as $key => $plan)
  @php $isActif = $abonnement && $abonnement->plan === $key; @endphp

  <div style="background:#fff;border:2px solid {{ $isActif ? '#185FA5' : '#e2e8f0' }};border-radius:16px;overflow:hidden;display:flex;flex-direction:column;position:relative;
    {{ $plan['badge'] ? 'box-shadow:0 4px 28px rgba(24,95,165,.15)' : '' }}">

    {{-- Badge --}}
    @if($plan['badge'])
      <div style="background:#F5C842;color:#042C53;font-size:11.5px;font-weight:800;text-align:center;padding:8px;letter-spacing:.04em">
        ★ {{ $plan['badge'] }}
      </div>
    @endif

    {{-- En-tête --}}
    <div style="padding:24px 24px 18px">
      <div style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:#94a3b8;margin-bottom:6px">
        {{ $plan['sous_label'] }}
      </div>
      <h3 style="font-size:1.5rem;font-weight:800;color:#042C53;margin:0 0 10px">{{ $plan['label'] }}</h3>
      <p style="font-size:13.5px;color:#64748b;line-height:1.55;margin:0 0 20px">{{ $plan['description'] }}</p>

      <div style="display:flex;align-items:baseline;gap:4px;margin-bottom:4px">
        <span style="font-size:2.4rem;font-weight:800;color:#042C53">{{ number_format($plan['prix'],0,',',' ') }}</span>
        <div>
          <div style="font-size:13px;font-weight:700;color:#185FA5;line-height:1">FCFA</div>
          <div style="font-size:12px;color:#94a3b8;line-height:1">/ mois</div>
        </div>
      </div>

      @if(!$isActif)
      <form method="POST" action="{{ route('recruteur.abonnement.store') }}" style="margin-top:16px">
        @csrf
        <input type="hidden" name="plan" value="{{ $key }}">
        <button type="submit" class="rec-btn rec-btn--yellow" style="width:100%;justify-content:center;font-size:14px;padding:12px">
          S'abonner — 1 mois
        </button>
      </form>
      @else
        <div style="margin-top:16px;background:#f0fdf4;border:1.5px solid #bbf7d0;border-radius:10px;padding:11px;text-align:center">
          <span style="font-weight:700;color:#16a34a;font-size:14px">✓ Plan actuel</span>
        </div>
      @endif
    </div>

    <div style="height:1px;background:#f1f5f9;margin:0 24px"></div>

    {{-- Features incluses --}}
    <div style="padding:20px 24px;flex:1">
      <p style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#374151;margin:0 0 14px">
        Ce qui est inclus
      </p>
      <ul style="list-style:none;padding:0;margin:0 0 18px;display:flex;flex-direction:column;gap:9px">
        @foreach($plan['features'] as $f)
          <li style="font-size:13.5px;color:#374151;display:flex;align-items:flex-start;gap:10px">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="#16a34a" stroke-width="2.5" style="flex-shrink:0;margin-top:2px"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
            {{ $f }}
          </li>
        @endforeach
      </ul>

      @if(!empty($plan['options']))
      <div style="border-top:1px dashed #e2e8f0;padding-top:14px">
        <ul style="list-style:none;padding:0;margin:0;display:flex;flex-direction:column;gap:9px">
          @foreach($plan['options'] as $opt)
            <li style="font-size:13px;color:#64748b;display:flex;align-items:flex-start;gap:10px">
              <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="#94a3b8" stroke-width="2.5" style="flex-shrink:0;margin-top:2px"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
              {{ $opt }} <span style="font-size:11px;color:#94a3b8;font-weight:600">(Option)</span>
            </li>
          @endforeach
        </ul>
      </div>
      @endif
    </div>

  </div>
  @endforeach

</div>

{{-- Comment ça marche --}}
<div style="margin-top:28px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:14px;padding:22px 26px">
  <h4 style="font-size:14px;font-weight:700;color:#042C53;margin:0 0 12px">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="vertical-align:middle;margin-right:6px"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01"/></svg>
    Comment ça marche ?
  </h4>
  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:14px">
    @foreach([
      ['1', 'Choisissez votre plan', 'Sélectionnez Premium ou Illimité selon vos besoins.'],
      ['2', 'Paiement Mobile Money', 'Un conseiller vous contacte pour finaliser le paiement (MTN, Moov, Wave).'],
      ['3', 'Activé immédiatement', 'Dès confirmation, publiez vos annonces et accédez à tous les outils.'],
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
