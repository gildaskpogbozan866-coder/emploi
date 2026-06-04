@extends('layouts.admin')
@section('title', 'Abonnements — Administration')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <h1>Abonnements</h1>
    <p>Gestion de tous les abonnements de la plateforme</p>
  </div>
</div>

{{-- Stats --}}
<div class="adm-stats" style="grid-template-columns:repeat(auto-fit,minmax(180px,1fr));margin-bottom:24px">
  <div class="adm-stat">
    <div class="adm-stat__icon adm-stat__icon--green">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
    </div>
    <div>
      <div class="adm-stat__val">{{ $stats['total_actif'] }}</div>
      <div class="adm-stat__label">Abonnements actifs</div>
    </div>
  </div>
  <div class="adm-stat">
    <div class="adm-stat__icon adm-stat__icon--yellow">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
    </div>
    <div>
      <div class="adm-stat__val">{{ $stats['premium_actif'] }}</div>
      <div class="adm-stat__label">Abonnements Premium</div>
    </div>
  </div>
</div>

<div class="adm-filters">
  <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;width:100%">
    <select name="type" class="adm-select">
      <option value="">Tous les types</option>
      <option value="cv" {{ request('type') === 'cv' ? 'selected' : '' }}>CV (Candidat)</option>
      <option value="recruteur" {{ request('type') === 'recruteur' ? 'selected' : '' }}>Recruteur</option>
      <option value="talent" {{ request('type') === 'talent' ? 'selected' : '' }}>Talent</option>
    </select>
    <select name="statut" class="adm-select">
      <option value="">Tous les statuts</option>
      <option value="actif" {{ request('statut') === 'actif' ? 'selected' : '' }}>Actif</option>
      <option value="expire" {{ request('statut') === 'expire' ? 'selected' : '' }}>Expiré</option>
      <option value="annule" {{ request('statut') === 'annule' ? 'selected' : '' }}>Annulé</option>
    </select>
    <button type="submit" class="adm-btn adm-btn--primary">Filtrer</button>
    @if(request()->hasAny(['type','statut']))
      <a href="{{ route('admin.abonnements') }}" class="adm-btn adm-btn--outline">Effacer</a>
    @endif
  </form>
</div>

<div class="adm-card">
  <div class="adm-table-wrap">
    <table class="adm-table">
      <thead>
        <tr><th>Utilisateur</th><th>Type</th><th>Plan</th><th>Prix</th><th>Début</th><th>Expiration</th><th>Statut</th></tr>
      </thead>
      <tbody>
        @forelse($abonnements as $ab)
        <tr>
          <td>
            <div style="font-weight:600;color:#042C53">{{ $ab->user->nom_complet }}</div>
            <div style="font-size:12px;color:#94a3b8">{{ $ab->user->email }}</div>
          </td>
          <td>
            <span class="adm-badge adm-badge--{{ match($ab->type) {
              'cv'        => 'blue',
              'recruteur' => 'violet',
              'talent'    => 'orange',
              default     => 'gray'
            } }}">
              {{ ucfirst($ab->type) }}
            </span>
          </td>
          <td style="font-weight:600;color:#042C53">{{ ucfirst(str_replace('_', ' ', $ab->plan)) }}</td>
          <td>{{ $ab->prix > 0 ? number_format($ab->prix, 0, ',', ' ').' FCFA' : 'Gratuit' }}</td>
          <td style="color:#64748b;font-size:12px">{{ $ab->debut_le?->format('d/m/Y') ?? '—' }}</td>
          <td style="color:#64748b;font-size:12px">{{ $ab->expire_le?->format('d/m/Y') ?? 'Illimité' }}</td>
          <td>
            <span class="adm-badge adm-badge--{{ match($ab->statut) {
              'actif'  => 'green',
              'expire' => 'gray',
              'annule' => 'red',
              default  => 'gray'
            } }}">
              {{ ucfirst($ab->statut) }}
            </span>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7">
            <div class="adm-empty">
              <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
              <h3>Aucun abonnement trouvé</h3>
              <p>Essayez d'ajuster vos filtres.</p>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($abonnements->hasPages())
    <div style="padding:16px 22px">{{ $abonnements->links() }}</div>
  @endif
</div>
@endsection
