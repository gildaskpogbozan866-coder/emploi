@extends('layouts.admin')
@section('title', 'Paiements — Administration')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <h1>Paiements</h1>
    <p>{{ $paiements->total() }} paiement{{ $paiements->total() > 1 ? 's' : '' }} enregistré{{ $paiements->total() > 1 ? 's' : '' }}</p>
  </div>
  <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:10px;padding:12px 20px;text-align:right">
    <p style="font-size:11.5px;color:#15803d;font-weight:600;text-transform:uppercase;margin:0 0 2px">Total confirmé</p>
    <p style="font-size:1.4rem;font-weight:800;color:#15803d;margin:0">{{ number_format($totalConfirme, 0, ',', ' ') }} FCFA</p>
  </div>
</div>

<div class="adm-filters">
  <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;width:100%">
    <select name="statut" class="adm-select">
      <option value="">Tous les statuts</option>
      @foreach(['en_attente' => 'En attente','confirme' => 'Confirmé','echec' => 'Échec','rembourse' => 'Remboursé'] as $val => $label)
        <option value="{{ $val }}" {{ request('statut') === $val ? 'selected' : '' }}>{{ $label }}</option>
      @endforeach
    </select>
    <select name="type" class="adm-select">
      <option value="">Tous les types</option>
      @foreach(['abonnement_cv' => 'Abonnement CV','abonnement_recruteur' => 'Abonnement Recruteur','abonnement_talent' => 'Abonnement Talent','service' => 'Service'] as $val => $label)
        <option value="{{ $val }}" {{ request('type') === $val ? 'selected' : '' }}>{{ $label }}</option>
      @endforeach
    </select>
    <button type="submit" class="adm-btn adm-btn--primary">Filtrer</button>
    @if(request()->hasAny(['statut','type']))
      <a href="{{ route('admin.paiements.list') }}" class="adm-btn adm-btn--outline">Effacer</a>
    @endif
  </form>
</div>

<div class="adm-card">
  <div class="adm-table-wrap">
    <table class="adm-table">
      <thead>
        <tr><th>Référence</th><th>Utilisateur</th><th>Type</th><th>Montant</th><th>Méthode</th><th>Statut</th><th>Date</th><th>Actions</th></tr>
      </thead>
      <tbody>
        @forelse($paiements as $paiement)
        <tr>
          <td style="font-family:monospace;font-size:12px;color:#64748b">{{ $paiement->reference }}</td>
          <td>
            <div style="font-weight:600;color:#042C53">{{ $paiement->user->nom_complet }}</div>
            <div style="font-size:12px;color:#94a3b8">{{ $paiement->user->email }}</div>
          </td>
          <td>
            <span class="adm-badge adm-badge--{{ match(true) {
              str_contains($paiement->type,'cv') => 'blue',
              str_contains($paiement->type,'recruteur') => 'violet',
              str_contains($paiement->type,'talent') => 'orange',
              default => 'gray'
            } }}">
              {{ ucfirst(str_replace(['abonnement_','_'],' ',$paiement->type)) }}
            </span>
          </td>
          <td style="font-weight:700;color:#042C53">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</td>
          <td style="font-size:13px;color:#64748b">{{ ucfirst(str_replace('_', ' ', $paiement->methode ?? '—')) }}</td>
          <td>
            <span class="adm-badge adm-badge--{{ match($paiement->statut) {
              'confirme'   => 'green',
              'en_attente' => 'yellow',
              'echec'      => 'red',
              'rembourse'  => 'gray',
              default      => 'gray'
            } }}">
              {{ ucfirst(str_replace('_',' ',$paiement->statut)) }}
            </span>
          </td>
          <td style="color:#94a3b8;font-size:12px">{{ $paiement->created_at->format('d/m/Y H:i') }}</td>
          <td>
            <a href="{{ route('admin.paiements.detail', $paiement) }}" class="adm-btn adm-btn--ghost adm-btn--sm">Voir</a>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8">
            <div class="adm-empty">
              <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
              <h3>Aucun paiement trouvé</h3>
              <p>Essayez d'ajuster vos filtres.</p>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($paiements->hasPages())
    <div style="padding:16px 22px">{{ $paiements->links() }}</div>
  @endif
</div>
@endsection
