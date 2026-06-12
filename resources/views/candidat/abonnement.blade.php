@extends('layouts.candidat')
@section('title', 'Mon abonnement')

@section('sidebar')
@include('candidat._sidebar')
@endsection

@section('content')
<div class="cand-page-header">
  <div class="cand-page-header__left">
    <h1 class="cand-page-header__title">Mon abonnement</h1>
    <p class="cand-page-header__sub">Historique et plan actif de votre compte</p>
  </div>
  <a href="{{ route('candidat.abonnement.plans') }}" class="cand-btn cand-btn--yellow">
    + Choisir un plan
  </a>
</div>

@if(session('success'))
<div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:14px 18px;margin-bottom:20px;display:flex;gap:10px;align-items:center">
  <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#16a34a" stroke-width="2.5" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
  <p style="color:#16a34a;font-size:13.5px;font-weight:600;margin:0">{{ session('success') }}</p>
</div>
@endif

{{-- Plan actif --}}
@if($abonnement)
<div style="background:linear-gradient(135deg,#021e3a 0%,#185FA5 100%);border-radius:14px;padding:24px 28px;margin-bottom:28px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:16px">
  <div>
    <p style="font-size:11.5px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.55);margin:0 0 5px">Plan actif</p>
    <h2 style="font-size:1.7rem;font-weight:800;margin:0;color:#fff">{{ $abonnement->plan?->name ?? '—' }}</h2>
    @if($abonnement->ends_at)
      <p style="font-size:13px;margin:6px 0 0;color:rgba(255,255,255,.65)">
        Expire le {{ $abonnement->ends_at->format('d/m/Y') }}
        <span style="color:rgba(255,255,255,.4)"> — {{ $abonnement->ends_at->diffForHumans() }}</span>
      </p>
    @else
      <p style="font-size:13px;margin:6px 0 0;color:rgba(255,255,255,.55)">Sans limite de durée</p>
    @endif
  </div>
  <div style="background:rgba(255,255,255,.12);border-radius:10px;padding:14px 22px;text-align:right">
    @if($abonnement->plan?->is_free)
      <p style="font-size:1.4rem;font-weight:800;color:#86efac;margin:0">Gratuit</p>
    @else
      <p style="font-size:1.4rem;font-weight:800;color:#F5C842;margin:0">
        {{ number_format($abonnement->plan->price, 0, ',', ' ') }} {{ $abonnement->plan->currency }}
      </p>
      @if($abonnement->plan->duration_days)
        <p style="font-size:12px;color:rgba(255,255,255,.55);margin:2px 0 0">/ {{ $abonnement->plan->duration_days }} jours</p>
      @endif
    @endif
  </div>
</div>
@else
<div style="background:#fffbeb;border:1px solid #fde68a;border-radius:12px;padding:18px 22px;margin-bottom:28px;display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap">
  <div style="display:flex;align-items:center;gap:12px">
    <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#92400e" stroke-width="2" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <p style="font-size:13.5px;color:#92400e;margin:0;font-weight:600">
      Vous n'avez pas d'abonnement actif. Souscrivez pour accéder à toutes les fonctionnalités.
    </p>
  </div>
  <a href="{{ route('candidat.abonnement.plans') }}" class="cand-btn cand-btn--yellow" style="white-space:nowrap">
    Voir les plans →
  </a>
</div>
@endif

{{-- Historique --}}
<div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;overflow:hidden">
  <div style="padding:18px 22px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between">
    <h3 style="font-size:14px;font-weight:700;color:#042C53;margin:0">Historique des abonnements</h3>
    <span style="font-size:12px;color:#94a3b8">{{ $abonnements->count() }} abonnement{{ $abonnements->count() > 1 ? 's' : '' }}</span>
  </div>

  @if($abonnements->isEmpty())
    <div style="padding:40px;text-align:center;color:#94a3b8">
      <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="margin:0 auto 10px;display:block"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
      <p style="margin:0;font-size:13.5px">Aucun abonnement pour le moment.</p>
    </div>
  @else
  <div style="overflow-x:auto">
    <table style="width:100%;border-collapse:collapse;font-size:13.5px">
      <thead>
        <tr style="background:#f8fafc">
          <th style="padding:11px 18px;text-align:left;font-size:11.5px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0">Plan</th>
          <th style="padding:11px 18px;text-align:left;font-size:11.5px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0">Prix</th>
          <th style="padding:11px 18px;text-align:left;font-size:11.5px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0">Début</th>
          <th style="padding:11px 18px;text-align:left;font-size:11.5px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0">Expiration</th>
          <th style="padding:11px 18px;text-align:left;font-size:11.5px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0">Statut</th>
        </tr>
      </thead>
      <tbody>
        @foreach($abonnements as $ab)
        <tr style="border-bottom:1px solid #f1f5f9;{{ $ab->status === 'active' ? 'background:#f0fdf4' : '' }}">
          <td style="padding:13px 18px">
            <span style="font-weight:{{ $ab->status === 'active' ? '700' : '500' }};color:#042C53">
              {{ $ab->plan?->name ?? '—' }}
            </span>
            @if($ab->status === 'active')
              <span style="margin-left:6px;font-size:11px;background:#dcfce7;color:#16a34a;font-weight:700;padding:2px 7px;border-radius:20px">actif</span>
            @endif
          </td>
          <td style="padding:13px 18px;color:#374151">
            @if($ab->plan?->is_free)
              <span style="color:#16a34a;font-weight:600">Gratuit</span>
            @elseif($ab->plan)
              {{ number_format($ab->plan->price, 0, ',', ' ') }} {{ $ab->plan->currency }}
            @else
              —
            @endif
          </td>
          <td style="padding:13px 18px;color:#64748b">
            {{ $ab->starts_at?->format('d/m/Y') ?? '—' }}
          </td>
          <td style="padding:13px 18px;color:#64748b">
            @if($ab->ends_at === null)
              <span style="color:#94a3b8">Illimité</span>
            @elseif($ab->ends_at->isPast())
              <span style="color:#ef4444">{{ $ab->ends_at->format('d/m/Y') }}</span>
            @else
              {{ $ab->ends_at->format('d/m/Y') }}
            @endif
          </td>
          <td style="padding:13px 18px">
            @php
              $badge = match($ab->status) {
                'active'    => ['bg' => '#dcfce7', 'color' => '#16a34a', 'label' => 'Actif'],
                'expired'   => ['bg' => '#f1f5f9', 'color' => '#64748b', 'label' => 'Expiré'],
                'cancelled' => ['bg' => '#fee2e2', 'color' => '#dc2626', 'label' => 'Annulé'],
                default     => ['bg' => '#f1f5f9', 'color' => '#64748b', 'label' => $ab->status],
              };
            @endphp
            <span style="font-size:12px;font-weight:700;padding:3px 10px;border-radius:20px;background:{{ $badge['bg'] }};color:{{ $badge['color'] }}">
              {{ $badge['label'] }}
            </span>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  @endif
</div>

@endsection
