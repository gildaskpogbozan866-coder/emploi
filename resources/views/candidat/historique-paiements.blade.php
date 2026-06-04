@extends('layouts.candidat')
@section('title', 'Historique des paiements')

@section('sidebar')
@include('candidat._sidebar')
@endsection

@section('content')
<div class="cand-page-header">
  <div class="cand-page-header__left">
    <h1 class="cand-page-header__title">Historique des paiements</h1>
    <p class="cand-page-header__sub">Toutes vos transactions sur la plateforme</p>
  </div>
</div>

<div class="cand-card">
  @if($paiements->isEmpty())
    <div class="cand-empty">
      <div class="cand-empty__icon">
        <svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="1" y="4" width="22" height="16" rx="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
      </div>
      <p class="cand-empty__title">Aucun paiement enregistré</p>
      <p class="cand-empty__text">Vos paiements et transactions apparaîtront ici.</p>
    </div>
  @else
    <div class="cand-table-wrap">
      <table class="cand-table">
        <thead>
          <tr><th>Référence</th><th>Montant</th><th>Type</th><th>Méthode</th><th>Statut</th><th>Date</th></tr>
        </thead>
        <tbody>
          @foreach($paiements as $p)
          <tr>
            <td style="font-family:monospace;font-size:12px;color:#94a3b8">{{ $p->reference }}</td>
            <td><strong>{{ number_format($p->montant,0,',',' ') }} {{ $p->devise }}</strong></td>
            <td style="color:#6b7a8d">{{ ucfirst(str_replace('_',' ',$p->type)) }}</td>
            <td style="color:#6b7a8d">{{ $p->methode ?? '—' }}</td>
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
                  default      => ucfirst($p->statut)
                } }}
              </span>
            </td>
            <td style="color:#6b7a8d;font-size:12px">{{ $p->created_at->format('d/m/Y H:i') }}</td>
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
