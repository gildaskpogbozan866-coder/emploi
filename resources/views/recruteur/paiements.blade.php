@extends('layouts.recruteur')
@section('title', 'Mes paiements')

@section('sidebar')
@include('recruteur._sidebar')
@endsection

@section('content')
<div class="rec-topbar">
  <div class="rec-topbar__left">
    <h1>Mes paiements</h1>
    <p>Historique complet de vos transactions</p>
  </div>
  <div class="rec-topbar__actions">
    <a href="{{ route('recruteur.paiements.export', request()->query()) }}" class="rec-btn rec-btn--outline rec-btn--sm">
      <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
      Exporter CSV
    </a>
  </div>
</div>

{{-- Stats --}}
<div class="rec-stats" style="margin-bottom:24px">
  <div class="rec-stat">
    <div class="rec-stat__icon rec-stat__icon--green">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
    </div>
    <div class="rec-stat__val">{{ number_format($stats['total_paye'], 0, ',', ' ') }} FCFA</div>
    <div class="rec-stat__label">Total payé</div>
  </div>
  <div class="rec-stat">
    <div class="rec-stat__icon rec-stat__icon--blue">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/></svg>
    </div>
    <div class="rec-stat__val">{{ $stats['nb_confirme'] }}</div>
    <div class="rec-stat__label">Confirmés</div>
  </div>
  <div class="rec-stat">
    <div class="rec-stat__icon rec-stat__icon--yellow">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    </div>
    <div class="rec-stat__val" style="color:#c79a00">{{ $stats['nb_attente'] }}</div>
    <div class="rec-stat__label">En attente</div>
  </div>
</div>

{{-- Filtres --}}
<div class="rec-card" style="margin-bottom:16px">
  <div class="rec-card__body" style="padding:14px 20px">
    <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;align-items:center">
      <select name="statut" class="rec-select" style="min-width:150px">
        <option value="">Tous les statuts</option>
        <option value="en_attente" {{ request('statut') === 'en_attente' ? 'selected' : '' }}>En attente</option>
        <option value="confirme"   {{ request('statut') === 'confirme'   ? 'selected' : '' }}>Confirmé</option>
        <option value="echec"      {{ request('statut') === 'echec'      ? 'selected' : '' }}>Échec</option>
        <option value="rembourse"  {{ request('statut') === 'rembourse'  ? 'selected' : '' }}>Remboursé</option>
      </select>
      <select name="type" class="rec-select" style="min-width:170px">
        <option value="">Tous les types</option>
        <option value="abonnement" {{ request('type') === 'abonnement' ? 'selected' : '' }}>Abonnements</option>
        <option value="cv_credits" {{ request('type') === 'cv_credits' ? 'selected' : '' }}>Crédits CVthèque</option>
      </select>
      <select name="gateway" class="rec-select" style="min-width:150px">
        <option value="">Tous les gateways</option>
        <option value="fedapay" {{ request('gateway') === 'fedapay' ? 'selected' : '' }}>FedaPay</option>
        <option value="kkiapay" {{ request('gateway') === 'kkiapay' ? 'selected' : '' }}>KKiaPay</option>
        <option value="manuel"  {{ request('gateway') === 'manuel'  ? 'selected' : '' }}>Manuel</option>
      </select>
      <input type="date" name="date_from" value="{{ request('date_from') }}" class="rec-input" style="width:150px" placeholder="Du">
      <input type="date" name="date_to"   value="{{ request('date_to') }}"   class="rec-input" style="width:150px" placeholder="Au">
      <button type="submit" class="rec-btn rec-btn--primary rec-btn--sm">Filtrer</button>
      @if(request()->hasAny(['statut','type','gateway','date_from','date_to']))
        <a href="{{ route('recruteur.paiements') }}" class="rec-btn rec-btn--outline rec-btn--sm">Effacer</a>
      @endif
    </form>
  </div>
</div>

{{-- Tableau --}}
<div class="rec-card">
  <div class="rec-card__body" style="padding:0">
    @if($paiements->isEmpty())
      <div style="padding:48px;text-align:center;color:#94a3b8">
        <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="display:block;margin:0 auto 12px"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
        <h3 style="font-size:15px;color:#042C53;margin:0 0 4px">Aucun paiement</h3>
        <p style="font-size:13px;margin:0">Vos transactions apparaîtront ici.</p>
      </div>
    @else
    <div style="overflow-x:auto">
      <table style="width:100%;border-collapse:collapse;font-size:13.5px">
        <thead>
          <tr style="background:#f8fafc;border-bottom:1px solid #e2e8f0">
            <th style="padding:11px 18px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em">Référence</th>
            <th style="padding:11px 18px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em">Type</th>
            <th style="padding:11px 18px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em">Gateway</th>
            <th style="padding:11px 18px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em">Montant</th>
            <th style="padding:11px 18px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em">Statut</th>
            <th style="padding:11px 18px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em">Date</th>
            <th style="padding:11px 18px;text-align:left;font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em">Action</th>
          </tr>
        </thead>
        <tbody>
          @foreach($paiements as $p)
          <tr style="border-bottom:1px solid #f1f5f9">
            <td style="padding:12px 18px;font-family:monospace;font-size:12px;color:#64748b">{{ $p->reference }}</td>
            <td style="padding:12px 18px">
              @if($p->type === 'cv_credits')
                <div style="font-weight:600;color:#042C53;font-size:13px">{{ $p->credits_cv }} crédits CVthèque</div>
              @elseif($p->abonnement?->plan)
                <div style="font-weight:600;color:#042C53;font-size:13px">{{ $p->abonnement->plan->name }}</div>
                <span style="font-size:11px;color:#64748b">Abonnement</span>
              @else
                <span style="font-size:13px;color:#64748b">{{ $p->type }}</span>
              @endif
            </td>
            <td style="padding:12px 18px">
              @php
                $gColor = match($p->gateway) {
                  'fedapay' => ['bg' => '#eff6ff', 'color' => '#1d4ed8', 'label' => 'FedaPay'],
                  'kkiapay' => ['bg' => '#fff7ed', 'color' => '#c2410c', 'label' => 'KKiaPay'],
                  default   => ['bg' => '#f1f5f9', 'color' => '#475569', 'label' => 'Manuel'],
                };
              @endphp
              <span style="font-size:11.5px;font-weight:700;padding:3px 10px;border-radius:20px;background:{{ $gColor['bg'] }};color:{{ $gColor['color'] }}">
                {{ $gColor['label'] }}
              </span>
            </td>
            <td style="padding:12px 18px;font-weight:700;color:#042C53">{{ number_format($p->montant, 0, ',', ' ') }} FCFA</td>
            <td style="padding:12px 18px">
              @php
                $badge = match($p->statut) {
                  'confirme'   => ['bg' => '#dcfce7', 'color' => '#16a34a', 'label' => 'Confirmé'],
                  'en_attente' => ['bg' => '#fef9c3', 'color' => '#854d0e', 'label' => 'En attente'],
                  'echec'      => ['bg' => '#fee2e2', 'color' => '#dc2626', 'label' => 'Échec'],
                  'rembourse'  => ['bg' => '#f1f5f9', 'color' => '#64748b', 'label' => 'Remboursé'],
                  default      => ['bg' => '#f1f5f9', 'color' => '#64748b', 'label' => $p->statut],
                };
              @endphp
              <span style="font-size:11.5px;font-weight:700;padding:3px 10px;border-radius:20px;background:{{ $badge['bg'] }};color:{{ $badge['color'] }}">
                {{ $badge['label'] }}
              </span>
            </td>
            <td style="padding:12px 18px;font-size:12px;color:#94a3b8">{{ $p->created_at->format('d/m/Y H:i') }}</td>
            <td style="padding:12px 18px">
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
      <div style="padding:16px 18px">{{ $paiements->links() }}</div>
    @endif
    @endif
  </div>
</div>
@endsection
