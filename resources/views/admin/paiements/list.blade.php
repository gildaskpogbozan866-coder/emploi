@extends('layouts.admin')
@section('title', 'Paiements — Administration')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <h1>Paiements</h1>
    <p>{{ $paiements->total() }} paiement{{ $paiements->total() > 1 ? 's' : '' }} enregistré{{ $paiements->total() > 1 ? 's' : '' }}</p>
  </div>
</div>

{{-- Stats --}}
<div class="adm-stats" style="grid-template-columns:repeat(auto-fit,minmax(200px,1fr));margin-bottom:24px">
  <div class="adm-stat">
    <div class="adm-stat__icon adm-stat__icon--green">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
    </div>
    <div>
      <div class="adm-stat__val">{{ number_format($stats['total_confirme'], 0, ',', ' ') }} FCFA</div>
      <div class="adm-stat__label">Revenus confirmés</div>
    </div>
  </div>
  <div class="adm-stat">
    <div class="adm-stat__icon adm-stat__icon--green">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
    </div>
    <div>
      <div class="adm-stat__val">{{ $stats['count_confirme'] }}</div>
      <div class="adm-stat__label">Paiements confirmés</div>
    </div>
  </div>
  <div class="adm-stat">
    <div class="adm-stat__icon adm-stat__icon--yellow">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    </div>
    <div>
      <div class="adm-stat__val">{{ $stats['count_attente'] }}</div>
      <div class="adm-stat__label">En attente</div>
    </div>
  </div>
  <div class="adm-stat">
    <div class="adm-stat__icon" style="background:#fef9c3;color:#854d0e">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
    </div>
    <div>
      <div class="adm-stat__val">{{ number_format($stats['total_attente'], 0, ',', ' ') }} FCFA</div>
      <div class="adm-stat__label">Montant en attente</div>
    </div>
  </div>
</div>

{{-- Filtres --}}
<div class="adm-filters" style="margin-bottom:16px">
  <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;width:100%;align-items:center">

    <input type="text" name="q" value="{{ request('q') }}" placeholder="Rechercher un utilisateur…"
           class="adm-input" style="flex:1;min-width:200px;max-width:280px">

    <select name="statut" class="adm-select">
      <option value="">Tous les statuts</option>
      <option value="en_attente" {{ request('statut') === 'en_attente' ? 'selected' : '' }}>En attente</option>
      <option value="confirme"   {{ request('statut') === 'confirme'   ? 'selected' : '' }}>Confirmé</option>
      <option value="echec"      {{ request('statut') === 'echec'      ? 'selected' : '' }}>Échec</option>
      <option value="rembourse"  {{ request('statut') === 'rembourse'  ? 'selected' : '' }}>Remboursé</option>
    </select>

    <select name="categorie" class="adm-select">
      <option value="">Toutes catégories</option>
      <option value="abonnement" {{ request('categorie') === 'abonnement' ? 'selected' : '' }}>Abonnements</option>
      <option value="service"    {{ request('categorie') === 'service'    ? 'selected' : '' }}>Services / Commandes</option>
    </select>

    <button type="submit" class="adm-btn adm-btn--primary">Filtrer</button>

    @if(request()->hasAny(['q', 'statut', 'categorie']))
      <a href="{{ route('admin.paiements.list') }}" class="adm-btn adm-btn--outline">Effacer</a>
    @endif
  </form>
</div>

<div class="adm-card">
  <div class="adm-table-wrap">
    <table class="adm-table">
      <thead>
        <tr>
          <th>Référence</th>
          <th>Utilisateur</th>
          <th>Plan / Type</th>
          <th>Montant</th>
          <th>Méthode</th>
          <th>Statut</th>
          <th>Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($paiements as $paiement)
        <tr>
          <td style="font-family:monospace;font-size:12px;color:#64748b">{{ $paiement->reference }}</td>

          <td>
            @if($paiement->user)
              <div style="font-weight:600;color:#042C53">{{ $paiement->user->nom_complet }}</div>
              <div style="font-size:12px;color:#94a3b8">{{ $paiement->user->email }}</div>
            @else
              <span style="color:#cbd5e1">Utilisateur supprimé</span>
            @endif
          </td>

          <td>
            @if($paiement->abonnement?->plan)
              <div style="font-weight:600;color:#042C53;font-size:13px">{{ $paiement->abonnement->plan->name }}</div>
              <span class="adm-badge adm-badge--blue" style="font-size:11px">Abonnement</span>
            @elseif($paiement->type)
              <span class="adm-badge adm-badge--gray" style="font-size:11px">
                {{ ucfirst(str_replace(['abonnement_', '_'], ['' , ' '], $paiement->type)) }}
              </span>
            @else
              <span style="color:#cbd5e1">—</span>
            @endif
          </td>

          <td style="font-weight:700;color:#042C53">
            {{ number_format($paiement->montant, 0, ',', ' ') }} {{ $paiement->devise ?? 'FCFA' }}
          </td>

          <td style="font-size:13px;color:#64748b">
            {{ ucfirst(str_replace('_', ' ', $paiement->methode ?? '—')) }}
          </td>

          <td>
            <span class="adm-badge adm-badge--{{ match($paiement->statut) {
              'confirme'   => 'green',
              'en_attente' => 'yellow',
              'echec'      => 'red',
              'rembourse'  => 'gray',
              default      => 'gray'
            } }}">
              {{ match($paiement->statut) {
                'confirme'   => 'Confirmé',
                'en_attente' => 'En attente',
                'echec'      => 'Échec',
                'rembourse'  => 'Remboursé',
                default      => $paiement->statut
              } }}
            </span>
          </td>

          <td style="color:#94a3b8;font-size:12px">
            {{ ($paiement->paid_at ?? $paiement->created_at)?->format('d/m/Y H:i') }}
          </td>

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
