@extends('layouts.admin')
@section('title', 'Candidats — Administration')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <h1>Candidats inscrits</h1>
    <p>{{ $candidats->total() }} candidat{{ $candidats->total() > 1 ? 's' : '' }} enregistré{{ $candidats->total() > 1 ? 's' : '' }} sur la plateforme</p>
  </div>
</div>

<div class="adm-filters">
  <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;width:100%">
    <div class="adm-search" style="flex:1;max-width:340px">
      <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" name="q" value="{{ request('q') }}" placeholder="Rechercher par nom, email…">
    </div>
    <button type="submit" class="adm-btn adm-btn--primary">Rechercher</button>
    @if(request('q'))
      <a href="{{ route('admin.utilisateurs.candidats') }}" class="adm-btn adm-btn--outline">Effacer</a>
    @endif
  </form>
</div>

<div class="adm-card">
  <div class="adm-table-wrap">
    <table class="adm-table">
      <thead>
        <tr><th>Nom</th><th>Email</th><th>Pays</th><th>CVs</th><th>Statut</th><th>Inscrit le</th><th>Actions</th></tr>
      </thead>
      <tbody>
        @forelse($candidats as $user)
        <tr>
          <td>
            <div style="display:flex;align-items:center;gap:10px">
              <div class="adm-avatar">{{ strtoupper(substr($user->prenom ?? '?', 0, 1)) }}</div>
              <div>
                <a href="{{ route('admin.utilisateurs.candidats.detail', $user) }}" style="font-weight:600;color:#042C53;text-decoration:none">{{ $user->nom_complet }}</a>
              </div>
            </div>
          </td>
          <td style="color:#64748b;font-size:12.5px">{{ $user->email }}</td>
          <td style="color:#64748b">{{ $user->pays ?? '—' }}</td>
          <td><strong>{{ $user->cvs->count() }}</strong></td>
          <td>
            <span class="adm-badge adm-badge--{{ $user->actif ? 'green' : 'red' }}">
              {{ $user->actif ? 'Actif' : 'Suspendu' }}
            </span>
          </td>
          <td style="color:#94a3b8;font-size:12px">{{ $user->created_at->format('d/m/Y') }}</td>
          <td>
            <div class="actions">
              <form method="POST" action="{{ route('admin.utilisateurs.statut', $user) }}">
                @csrf @method('PATCH')
                <button type="submit" class="adm-btn adm-btn--ghost adm-btn--sm" style="color:{{ $user->actif ? '#e53e3e' : '#38A169' }}">
                  {{ $user->actif ? 'Suspendre' : 'Réactiver' }}
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7">
            <div class="adm-empty">
              <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
              <h3>Aucun candidat trouvé</h3>
              <p>Essayez d'ajuster vos critères de recherche.</p>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($candidats->hasPages())
    <div style="padding:16px 22px">{{ $candidats->links() }}</div>
  @endif
</div>
@endsection
