@extends('layouts.admin')
@section('title', 'Abonnements souscrits — Administration')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <h1>Abonnements souscrits</h1>
    <p>Historique et suivi de tous les abonnements de la plateforme</p>
  </div>
  <a href="{{ route('admin.plans.list') }}" class="adm-btn adm-btn--outline adm-btn--sm">
    Gérer les plans →
  </a>
</div>

{{-- Stats --}}
<div class="adm-stats" style="grid-template-columns:repeat(auto-fit,minmax(180px,1fr));margin-bottom:24px">
  <div class="adm-stat">
    <div class="adm-stat__icon adm-stat__icon--green">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
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
      <div class="adm-stat__label">Abonnements payants</div>
    </div>
  </div>
  <div class="adm-stat">
    <div class="adm-stat__icon" style="background:#f1f5f9;color:#64748b">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    </div>
    <div>
      <div class="adm-stat__val">{{ $stats['gratuit_actif'] }}</div>
      <div class="adm-stat__label">Abonnements gratuits</div>
    </div>
  </div>
</div>

{{-- Filtres --}}
<div class="adm-filters" style="margin-bottom:16px">
  <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;width:100%;align-items:center">

    <input type="text" name="q" value="{{ request('q') }}" placeholder="Rechercher un utilisateur…"
           class="adm-input" style="flex:1;min-width:200px;max-width:280px">

    <select name="status" class="adm-select">
      <option value="">Tous les statuts</option>
      <option value="active"    {{ request('status') === 'active'    ? 'selected' : '' }}>Actif</option>
      <option value="expired"   {{ request('status') === 'expired'   ? 'selected' : '' }}>Expiré</option>
      <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Annulé</option>
    </select>

    <select name="target_type" class="adm-select">
      <option value="">Tous les profils</option>
      <option value="candidat"  {{ request('target_type') === 'candidat'  ? 'selected' : '' }}>Candidats</option>
      <option value="recruteur" {{ request('target_type') === 'recruteur' ? 'selected' : '' }}>Recruteurs</option>
      <option value="both"      {{ request('target_type') === 'both'      ? 'selected' : '' }}>Les deux</option>
    </select>

    <button type="submit" class="adm-btn adm-btn--primary">Filtrer</button>

    @if(request()->hasAny(['q', 'status', 'target_type']))
      <a href="{{ route('admin.abonnements') }}" class="adm-btn adm-btn--outline">Effacer</a>
    @endif
  </form>
</div>

<div class="adm-card">
  <div class="adm-table-wrap">
    <table class="adm-table">
      <thead>
        <tr>
          <th>Utilisateur</th>
          <th>Plan</th>
          <th>Prix</th>
          <th>Début</th>
          <th>Expiration</th>
          <th>Renouvellement</th>
          <th>Statut</th>
        </tr>
      </thead>
      <tbody>
        @forelse($abonnements as $ab)
        <tr>

          {{-- Utilisateur --}}
          <td>
            <div style="font-weight:600;color:#042C53">{{ $ab->user?->nom_complet ?? '—' }}</div>
            <div style="font-size:12px;color:#94a3b8">{{ $ab->user?->email }}</div>
          </td>

          {{-- Plan --}}
          <td>
            <div style="font-weight:600;color:#042C53">{{ $ab->plan?->name ?? '—' }}</div>
            @if($ab->plan)
              <span class="adm-badge adm-badge--{{ match($ab->plan->target_type) {
                'candidat'  => 'blue',
                'recruteur' => 'violet',
                'both'      => 'orange',
                default     => 'gray'
              } }}" style="font-size:11px">
                {{ match($ab->plan->target_type) {
                  'candidat'  => 'Candidat',
                  'recruteur' => 'Recruteur',
                  'both'      => 'Tous',
                  default     => $ab->plan->target_type
                } }}
              </span>
            @endif
          </td>

          {{-- Prix --}}
          <td>
            @if($ab->plan?->is_free)
              <span class="adm-badge adm-badge--green">Gratuit</span>
            @elseif($ab->plan)
              <span style="font-weight:700;color:#185FA5">
                {{ number_format($ab->plan->price, 0, ',', ' ') }} {{ $ab->plan->currency }}
              </span>
            @else
              <span style="color:#cbd5e1">—</span>
            @endif
          </td>

          {{-- Début --}}
          <td style="color:#64748b;font-size:12.5px">
            {{ $ab->starts_at?->format('d/m/Y') ?? '—' }}
          </td>

          {{-- Expiration --}}
          <td style="font-size:12.5px">
            @if($ab->ends_at === null)
              <span style="color:#64748b">Illimité</span>
            @elseif($ab->ends_at->isPast())
              <span style="color:#ef4444">{{ $ab->ends_at->format('d/m/Y') }}</span>
            @else
              <span style="color:#64748b">{{ $ab->ends_at->format('d/m/Y') }}</span>
              <div style="font-size:11px;color:#94a3b8">dans {{ $ab->ends_at->diffForHumans() }}</div>
            @endif
          </td>

          {{-- Renouvellement --}}
          <td style="text-align:center">
            @if($ab->auto_renew)
              <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="#16a34a" stroke-width="2.5" title="Renouvellement auto"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
            @else
              <span style="color:#cbd5e1;font-size:12px">—</span>
            @endif
          </td>

          {{-- Statut --}}
          <td>
            <span class="adm-badge adm-badge--{{ match($ab->status) {
              'active'    => 'green',
              'expired'   => 'gray',
              'cancelled' => 'red',
              default     => 'gray'
            } }}">
              {{ match($ab->status) {
                'active'    => 'Actif',
                'expired'   => 'Expiré',
                'cancelled' => 'Annulé',
                default     => $ab->status
              } }}
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
