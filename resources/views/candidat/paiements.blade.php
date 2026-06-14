@extends('layouts.candidat')
@section('title', 'Mes paiements')

@section('sidebar')
@include('candidat._sidebar')
@endsection

@section('content')
<div class="cand-page-header">
  <div class="cand-page-header__left">
    <h1 class="cand-page-header__title">Mes paiements</h1>
    <p class="cand-page-header__sub">Historique complet de vos transactions</p>
  </div>
</div>

{{-- Stats --}}
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:14px;margin-bottom:20px">
  <div class="cand-card" style="padding:16px 20px">
    <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0 0 4px">Total payé</p>
    <p style="font-size:1.3rem;font-weight:800;color:#042C53;margin:0">{{ number_format($stats['total_paye'], 0, ',', ' ') }} FCFA</p>
  </div>
  <div class="cand-card" style="padding:16px 20px">
    <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0 0 4px">Confirmés</p>
    <p style="font-size:1.3rem;font-weight:800;color:#16a34a;margin:0">{{ $stats['nb_confirme'] }}</p>
  </div>
  <div class="cand-card" style="padding:16px 20px">
    <p style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0 0 4px">En attente</p>
    <p style="font-size:1.3rem;font-weight:800;color:#d97706;margin:0">{{ $stats['nb_attente'] }}</p>
  </div>
</div>

{{-- Filtres --}}
<div class="cand-card" style="padding:14px 20px;margin-bottom:16px">
  <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center">
    <select name="statut" class="cand-select" style="min-width:150px">
      <option value="">Tous les statuts</option>
      <option value="en_attente" {{ request('statut') === 'en_attente' ? 'selected' : '' }}>En attente</option>
      <option value="confirme"   {{ request('statut') === 'confirme'   ? 'selected' : '' }}>Confirmé</option>
      <option value="echec"      {{ request('statut') === 'echec'      ? 'selected' : '' }}>Échec</option>
      <option value="rembourse"  {{ request('statut') === 'rembourse'  ? 'selected' : '' }}>Remboursé</option>
    </select>
    <button type="submit" class="cand-btn cand-btn--primary cand-btn--sm">Filtrer</button>
    @if(request('statut'))
      <a href="{{ route('candidat.paiements') }}" class="cand-btn cand-btn--outline cand-btn--sm">Effacer</a>
    @endif
  </form>
</div>

{{-- Tableau --}}
<div class="cand-card">
  @if($paiements->isEmpty())
    <div class="cand-empty">
      <div class="cand-empty__icon">
        <svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
      </div>
      <p class="cand-empty__title">Aucun paiement enregistré</p>
      <p class="cand-empty__text">Vos paiements et transactions apparaîtront ici.</p>
    </div>
  @else
    <div class="cand-table-wrap">
      <table class="cand-table">
        <thead>
          <tr>
            <th>Référence</th>
            <th>Type</th>
            <th>Gateway</th>
            <th>Montant</th>
            <th>Statut</th>
            <th>Date</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($paiements as $p)
          <tr>
            <td style="font-family:monospace;font-size:12px;color:#94a3b8">{{ $p->reference }}</td>
            <td>
              @if($p->abonnement?->plan)
                <span style="font-weight:600;color:#042C53;font-size:13px">{{ $p->abonnement->plan->name }}</span>
                <br><span style="font-size:11px;color:#94a3b8">Abonnement</span>
              @else
                <span style="color:#6b7a8d">{{ ucfirst(str_replace('_', ' ', $p->type)) }}</span>
              @endif
            </td>
            <td>
              @php
                $g = match($p->gateway ?? 'manuel') {
                  'fedapay' => ['bg' => '#eff6ff', 'color' => '#1d4ed8', 'label' => 'FedaPay'],
                  'kkiapay' => ['bg' => '#fff7ed', 'color' => '#c2410c', 'label' => 'KKiaPay'],
                  default   => ['bg' => '#f1f5f9', 'color' => '#475569', 'label' => 'Manuel'],
                };
              @endphp
              <span style="font-size:11px;font-weight:700;padding:2px 8px;border-radius:20px;background:{{ $g['bg'] }};color:{{ $g['color'] }}">
                {{ $g['label'] }}
              </span>
            </td>
            <td><strong>{{ number_format($p->montant, 0, ',', ' ') }} FCFA</strong></td>
            <td>
              <span class="cand-badge cand-badge--{{ match($p->statut) {
                'confirme'   => 'green',
                'en_attente' => 'yellow',
                'echec'      => 'red',
                default      => 'gray'
              } }}">
                {{ match($p->statut) {
                  'confirme'   => 'Confirmé',
                  'en_attente' => 'En attente',
                  'echec'      => 'Échec',
                  'rembourse'  => 'Remboursé',
                  default      => ucfirst($p->statut)
                } }}
              </span>
            </td>
            <td style="color:#6b7a8d;font-size:12px">{{ $p->created_at->format('d/m/Y H:i') }}</td>
            <td>
              @if($p->statut === 'en_attente')
                <a href="{{ route('payment.choose', $p) }}" style="font-size:12px;font-weight:600;color:#185FA5;text-decoration:none">Payer</a>
              @else
                <span style="font-size:12px;color:#cbd5e1">—</span>
              @endif
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @if($paiements->hasPages())
      <div style="padding:16px 0 4px">{{ $paiements->links() }}</div>
    @endif
  @endif
</div>
@endsection
