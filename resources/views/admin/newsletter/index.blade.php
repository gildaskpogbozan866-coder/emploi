@extends('layouts.admin')
@section('title', 'Newsletter | Administration')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <h1>Newsletter</h1>
    <p>Gestion des abonnés à la newsletter</p>
  </div>
  <div class="adm-topbar__actions">
    <a href="{{ route('admin.newsletter.export') }}" class="adm-btn adm-btn--outline">
      <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
      Exporter CSV
    </a>
  </div>
</div>

{{-- Stats --}}
<div class="adm-stats" style="margin-bottom:24px">
  <div class="adm-stat">
    <div class="adm-stat__icon adm-stat__icon--blue">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
    </div>
    <div>
      <p style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;font-weight:700;margin:0 0 2px">Total abonnés</p>
      <p class="adm-stat__val">{{ $stats['total'] }}</p>
    </div>
  </div>
  <div class="adm-stat">
    <div class="adm-stat__icon adm-stat__icon--green">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
    </div>
    <div>
      <p style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;font-weight:700;margin:0 0 2px">Actifs</p>
      <p class="adm-stat__val" style="color:#16a34a">{{ $stats['actifs'] }}</p>
    </div>
  </div>
  <div class="adm-stat">
    <div class="adm-stat__icon adm-stat__icon--red">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </div>
    <div>
      <p style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;font-weight:700;margin:0 0 2px">Désabonnés</p>
      <p class="adm-stat__val" style="color:#dc2626">{{ $stats['inactifs'] }}</p>
    </div>
  </div>
  <div class="adm-stat">
    <div class="adm-stat__icon adm-stat__icon--yellow">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
    </div>
    <div>
      <p style="font-size:11px;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;font-weight:700;margin:0 0 2px">Nouveaux (7j)</p>
      <p class="adm-stat__val" style="color:#d97706">{{ $stats['recents'] }}</p>
    </div>
  </div>
</div>

{{-- Flash --}}
@if(session('success'))
  <div style="background:#f0fdf4;border:1px solid #bbf7d0;color:#166534;padding:12px 18px;border-radius:10px;margin-bottom:18px;font-size:14px;font-weight:500">
    ✓ {{ session('success') }}
  </div>
@endif

{{-- Filtres --}}
<div class="adm-filters">
  <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;width:100%">
    <input type="text" name="q" value="{{ request('q') }}" placeholder="Rechercher e-mail, prénom…" class="adm-input" style="flex:1;min-width:200px">
    <select name="statut" class="adm-select">
      <option value="">Tous les statuts</option>
      <option value="actif"   {{ request('statut') === 'actif'   ? 'selected' : '' }}>Actifs</option>
      <option value="inactif" {{ request('statut') === 'inactif' ? 'selected' : '' }}>Désabonnés</option>
    </select>
    <button type="submit" class="adm-btn adm-btn--primary">Filtrer</button>
    @if(request()->hasAny(['q','statut']))
      <a href="{{ route('admin.newsletter.index') }}" class="adm-btn adm-btn--outline">Effacer</a>
    @endif
  </form>
</div>

<div class="adm-card">
  <div class="adm-table-wrap">
    <table class="adm-table">
      <thead>
        <tr>
          <th>Email</th>
          <th>Prénom</th>
          <th>Source</th>
          <th>Statut</th>
          <th>Date d'inscription</th>
          <th style="text-align:center">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($abonnes as $ab)
          <tr>
            <td style="font-weight:500;color:#042C53">{{ $ab->email }}</td>
            <td style="color:#475569">{{ $ab->prenom ?? '—' }}</td>
            <td>
              <span class="adm-badge adm-badge--blue">{{ $ab->source ?? 'footer' }}</span>
            </td>
            <td>
              @if($ab->statut === 'actif')
                <span class="adm-badge adm-badge--green">Actif</span>
              @else
                <span class="adm-badge adm-badge--red">Désabonné</span>
              @endif
            </td>
            <td style="color:#94a3b8;font-size:13px">{{ $ab->created_at->format('d/m/Y') }}</td>
            <td>
              <div class="actions">
                <form method="POST" action="{{ route('admin.newsletter.toggle', $ab) }}">
                  @csrf @method('PATCH')
                  <button type="submit" class="adm-btn adm-btn--sm adm-btn--outline">
                    {{ $ab->statut === 'actif' ? 'Désactiver' : 'Réactiver' }}
                  </button>
                </form>
                <form method="POST" action="{{ route('admin.newsletter.destroy', $ab) }}" onsubmit="return confirm('Supprimer cet abonné ?')">
                  @csrf @method('DELETE')
                  <button type="submit" class="adm-btn adm-btn--sm adm-btn--danger">Supprimer</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" style="text-align:center;padding:40px;color:#94a3b8">Aucun abonné pour l'instant.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@if($abonnes->hasPages())
  <div style="margin-top:20px">{{ $abonnes->links() }}</div>
@endif
@endsection
