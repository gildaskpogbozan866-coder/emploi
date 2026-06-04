@extends('layouts.admin')
@section('title', 'Commandes — Administration')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <h1>Commandes de services</h1>
    <p>{{ $commandes->total() }} commande{{ $commandes->total() > 1 ? 's' : '' }} au total</p>
  </div>
</div>

<div class="adm-card">
  <div class="adm-table-wrap">
    <table class="adm-table">
      <thead>
        <tr><th>Référence</th><th>Client</th><th>Service</th><th>Montant</th><th>Statut</th><th>Paiement</th><th>Date</th><th>Actions</th></tr>
      </thead>
      <tbody>
        @forelse($commandes as $commande)
        <tr>
          <td style="font-family:monospace;font-size:12px;color:#64748b">{{ $commande->reference }}</td>
          <td>
            <div style="font-weight:600;color:#042C53">{{ $commande->user->nom_complet }}</div>
            <div style="font-size:12px;color:#94a3b8">{{ $commande->user->email }}</div>
          </td>
          <td style="font-weight:500;color:#042C53">{{ $commande->service->nom }}</td>
          <td style="font-weight:700;color:#185FA5">{{ number_format($commande->montant, 0, ',', ' ') }} FCFA</td>
          <td>
            <form method="POST" action="{{ route('admin.commandes.statut', $commande) }}" style="margin:0">
              @csrf @method('PATCH')
              <select name="statut" onchange="this.form.submit()" class="adm-select" style="padding:5px 8px;font-size:12px">
                @foreach(['en_attente' => 'En attente','en_cours' => 'En cours','livree' => 'Livrée','annulee' => 'Annulée'] as $val => $label)
                  <option value="{{ $val }}" {{ $commande->statut === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
              </select>
            </form>
          </td>
          <td>
            <span class="adm-badge adm-badge--{{ match($commande->paiement_statut ?? 'non_paye') {
              'paye'      => 'green',
              'non_paye'  => 'gray',
              default     => 'gray'
            } }}">
              {{ $commande->paiement_statut === 'paye' ? 'Payé' : 'Non payé' }}
            </span>
          </td>
          <td style="color:#94a3b8;font-size:12px">{{ $commande->created_at->format('d/m/Y') }}</td>
          <td>
            <a href="{{ route('admin.commandes.detail', $commande) }}" class="adm-btn adm-btn--ghost adm-btn--sm">Voir</a>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8">
            <div class="adm-empty">
              <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg>
              <h3>Aucune commande</h3>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($commandes->hasPages())
    <div style="padding:16px 22px">{{ $commandes->links() }}</div>
  @endif
</div>
@endsection
