@extends('layouts.recruteur')
@section('title', 'Crédits CVthèque')

@section('sidebar')
@include('recruteur._sidebar')
@endsection

@section('content')
<div class="rec-topbar">
  <div class="rec-topbar__left">
    <h1>Crédits CVthèque</h1>
    <p>Achetez des crédits pour accéder aux profils complets et télécharger des CVs</p>
  </div>
</div>

{{-- Solde --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:16px;margin-bottom:28px">
  <div class="rec-stat" style="border:2px solid {{ $credits > 0 ? '#bae6fd' : '#fde68a' }};background:{{ $credits > 0 ? '#f0f9ff' : '#fffbeb' }}">
    <div class="rec-stat__icon" style="background:{{ $credits > 0 ? '#0284c7' : '#d97706' }}">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#fff" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    </div>
    <div class="rec-stat__val" style="color:{{ $credits > 0 ? '#0284c7' : '#d97706' }}">{{ $credits }}</div>
    <div class="rec-stat__label">Crédit{{ $credits > 1 ? 's' : '' }} disponible{{ $credits > 1 ? 's' : '' }}</div>
  </div>
  <div class="rec-stat">
    <div class="rec-stat__icon rec-stat__icon--green">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
    </div>
    <div class="rec-stat__val">{{ $historique->where('statut','confirme')->sum('credits_cv') }}</div>
    <div class="rec-stat__label">Crédits achetés au total</div>
  </div>
  <div class="rec-stat">
    <div class="rec-stat__icon rec-stat__icon--purple">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
    </div>
    <div class="rec-stat__val">{{ $historique->where('statut','confirme')->sum('credits_cv') - $credits }}</div>
    <div class="rec-stat__label">CVs téléchargés</div>
  </div>
</div>

{{-- Alerte si 0 crédits --}}
@if($credits <= 0)
<div style="background:#fffbeb;border:1.5px solid #fde68a;border-radius:12px;padding:16px 20px;display:flex;align-items:center;gap:12px;margin-bottom:24px">
  <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#d97706" stroke-width="2" style="flex-shrink:0"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
  <p style="margin:0;font-size:13.5px;color:#92400e;font-weight:600">
    Vous n'avez plus de crédits. Achetez un pack ci-dessous pour débloquer des profils.
  </p>
</div>
@endif

{{-- Packs --}}
<div class="rec-card" style="margin-bottom:28px">
  <div class="rec-card__head">
    <span class="rec-card__title">Choisir un pack</span>
    <span style="font-size:12px;color:#94a3b8">1 crédit = 1 CV téléchargé · Les crédits ne s'expirent pas</span>
  </div>
  <div class="rec-card__body">
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:14px">

      @php
      $packs = [
          ['credits' => 5,  'prix' => '5 000',  'unit' => '1 000 / CV', 'badge' => null,           'featured' => false],
          ['credits' => 10, 'prix' => '9 000',  'unit' => '900 / CV',   'badge' => null,           'featured' => false],
          ['credits' => 25, 'prix' => '20 000', 'unit' => '800 / CV',   'badge' => 'Populaire',    'featured' => false],
          ['credits' => 50, 'prix' => '35 000', 'unit' => '700 / CV',   'badge' => 'Meilleure valeur', 'featured' => true],
      ];
      @endphp

      @foreach($packs as $pack)
      <div style="border:2px solid {{ $pack['featured'] ? 'transparent' : ($pack['badge'] === 'Populaire' ? '#93c5fd' : '#e2e8f0') }};border-radius:14px;padding:22px 16px;text-align:center;position:relative;background:{{ $pack['featured'] ? 'linear-gradient(145deg,#042C53,#185FA5)' : '#fff' }}">
        @if($pack['badge'])
          <div style="position:absolute;top:-11px;left:50%;transform:translateX(-50%);background:#F5C842;color:#042C53;font-size:10px;font-weight:800;padding:2px 12px;border-radius:20px;white-space:nowrap;text-transform:uppercase">{{ $pack['badge'] }}</div>
        @endif
        <p style="font-size:2.4rem;font-weight:900;color:{{ $pack['featured'] ? '#F5C842' : '#042C53' }};margin:0;line-height:1">{{ $pack['credits'] }}</p>
        <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:{{ $pack['featured'] ? 'rgba(255,255,255,.5)' : '#94a3b8' }};margin:2px 0 14px">crédits</p>
        <p style="font-size:1.2rem;font-weight:800;color:{{ $pack['featured'] ? '#fff' : '#042C53' }};margin:0 0 2px">{{ $pack['prix'] }} FCFA</p>
        <p style="font-size:11px;color:{{ $pack['featured'] ? 'rgba(255,255,255,.45)' : '#94a3b8' }};margin:0 0 18px">{{ $pack['unit'] }}</p>
        <a href="{{ route('recruteur.cv-credits.confirm', ['credits' => $pack['credits']]) }}"
           style="display:block;padding:9px 14px;background:{{ $pack['featured'] ? '#F5C842' : '#185FA5' }};color:{{ $pack['featured'] ? '#042C53' : '#fff' }};border-radius:8px;font-weight:700;font-size:13px;text-decoration:none">
          Acheter
        </a>
      </div>
      @endforeach

    </div>
  </div>
</div>

{{-- Historique des achats --}}
<div class="rec-card">
  <div class="rec-card__head">
    <span class="rec-card__title">Historique des achats</span>
    <span style="font-size:12px;color:#94a3b8">{{ $historique->count() }} commande{{ $historique->count() > 1 ? 's' : '' }}</span>
  </div>
  <div class="rec-card__body" style="padding:0">
    @if($historique->isEmpty())
      <div style="padding:36px;text-align:center;color:#94a3b8">
        <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="margin:0 auto 10px;display:block"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        <p style="margin:0;font-size:13.5px">Aucun achat de crédits pour le moment.</p>
      </div>
    @else
    <div style="overflow-x:auto">
      <table style="width:100%;border-collapse:collapse;font-size:13.5px">
        <thead>
          <tr style="background:#f8fafc">
            <th style="padding:11px 18px;text-align:left;font-size:11.5px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0">Référence</th>
            <th style="padding:11px 18px;text-align:left;font-size:11.5px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0">Crédits</th>
            <th style="padding:11px 18px;text-align:left;font-size:11.5px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0">Montant</th>
            <th style="padding:11px 18px;text-align:left;font-size:11.5px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0">Méthode</th>
            <th style="padding:11px 18px;text-align:left;font-size:11.5px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0">Statut</th>
            <th style="padding:11px 18px;text-align:left;font-size:11.5px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0">Date</th>
          </tr>
        </thead>
        <tbody>
          @foreach($historique as $p)
          <tr style="border-bottom:1px solid #f1f5f9">
            <td style="padding:12px 18px;font-family:monospace;font-size:12px;color:#64748b">{{ $p->reference }}</td>
            <td style="padding:12px 18px">
              <span style="font-size:1.1rem;font-weight:800;color:#042C53">{{ $p->credits_cv }}</span>
              <span style="font-size:12px;color:#94a3b8;margin-left:3px">crédits</span>
            </td>
            <td style="padding:12px 18px;font-weight:700;color:#042C53">{{ number_format($p->montant, 0, ',', ' ') }} FCFA</td>
            <td style="padding:12px 18px;font-size:13px;color:#64748b">{{ ucfirst(str_replace('_', ' ', $p->methode ?? '—')) }}</td>
            <td style="padding:12px 18px">
              @php
                $badge = match($p->statut) {
                  'confirme'   => ['bg' => '#dcfce7', 'color' => '#16a34a', 'label' => 'Confirmé ✓'],
                  'en_attente' => ['bg' => '#fef9c3', 'color' => '#854d0e', 'label' => 'En attente…'],
                  'echec'      => ['bg' => '#fee2e2', 'color' => '#dc2626', 'label' => 'Échec'],
                  default      => ['bg' => '#f1f5f9', 'color' => '#64748b', 'label' => $p->statut],
                };
              @endphp
              <span style="font-size:11.5px;font-weight:700;padding:3px 10px;border-radius:20px;background:{{ $badge['bg'] }};color:{{ $badge['color'] }}">
                {{ $badge['label'] }}
              </span>
            </td>
            <td style="padding:12px 18px;font-size:12px;color:#94a3b8">{{ $p->created_at->format('d/m/Y H:i') }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @endif
  </div>
</div>

@endsection
