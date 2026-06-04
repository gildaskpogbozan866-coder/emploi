@extends('layouts.admin')
@section('title', 'Recruteurs — Administration')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <h1>Recruteurs inscrits</h1>
    <p>{{ $recruteurs->total() }} recruteur{{ $recruteurs->total() > 1 ? 's' : '' }} enregistré{{ $recruteurs->total() > 1 ? 's' : '' }}</p>
  </div>
</div>

<div class="adm-filters">
  <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;width:100%">
    <div class="adm-search" style="flex:1;max-width:340px">
      <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" name="q" value="{{ request('q') }}" placeholder="Nom, entreprise, email…">
    </div>
    <button type="submit" class="adm-btn adm-btn--primary">Rechercher</button>
    @if(request('q'))
      <a href="{{ route('admin.utilisateurs.recruteurs') }}" class="adm-btn adm-btn--outline">Effacer</a>
    @endif
  </form>
</div>

<div class="adm-card">
  <div class="adm-table-wrap">
    <table class="adm-table">
      <thead>
        <tr><th>Recruteur</th><th>Entreprise</th><th>Email</th><th>Offres</th><th>Abonnement</th><th>Statut</th><th>Inscrit le</th><th>Actions</th></tr>
      </thead>
      <tbody>
        @forelse($recruteurs as $user)
        <tr>
          <td>
            <div style="display:flex;align-items:center;gap:10px">
              <div class="adm-avatar">{{ strtoupper(substr($user->prenom ?? '?', 0, 1)) }}</div>
              <a href="{{ route('admin.utilisateurs.recruteurs.detail', $user) }}" style="font-weight:600;color:#042C53;text-decoration:none">{{ $user->nom_complet }}</a>
            </div>
          </td>
          <td style="color:#64748b">{{ $user->entreprise ?? '—' }}</td>
          <td style="color:#64748b;font-size:12.5px">{{ $user->email }}</td>
          <td><strong>{{ $user->offres_count }}</strong></td>
          <td>
            @if($user->premium)
              <span class="adm-badge adm-badge--yellow">★ Premium</span>
            @else
              <span style="color:#94a3b8;font-size:12px">Gratuit</span>
            @endif
          </td>
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
          <td colspan="8">
            <div class="adm-empty">
              <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
              <h3>Aucun recruteur trouvé</h3>
              <p>Essayez d'ajuster vos critères de recherche.</p>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($recruteurs->hasPages())
    <div style="padding:16px 22px">{{ $recruteurs->links() }}</div>
  @endif
</div>
@endsection
