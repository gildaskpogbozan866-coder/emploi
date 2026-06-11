@extends('layouts.admin')
@section('title', 'Tous les utilisateurs — Administration')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <h1>Tous les utilisateurs</h1>
    <p>{{ $users->total() }} utilisateur{{ $users->total() > 1 ? 's' : '' }} enregistré{{ $users->total() > 1 ? 's' : '' }}</p>
  </div>
  <div style="display:flex;gap:10px">
    <a href="{{ route('admin.utilisateurs.candidats') }}" class="adm-btn adm-btn--outline">Candidats</a>
    <a href="{{ route('admin.utilisateurs.recruteurs') }}" class="adm-btn adm-btn--outline">Recruteurs</a>
  </div>
</div>

<div class="adm-card">
  <div style="padding:16px 22px 0">
    @include('partials._search-bar', [
      'route'       => 'admin.utilisateurs.list',
      'placeholder' => 'Nom, prénom ou email…',
      'filters'     => [
        ['name' => 'role',   'label' => 'Tous les rôles',   'options' => ['candidat' => 'Candidat', 'recruteur' => 'Recruteur', 'admin' => 'Admin']],
        ['name' => 'statut', 'label' => 'Tous les statuts', 'options' => ['actif' => 'Actif', 'suspendu' => 'Suspendu']],
      ],
    ])
  </div>
  <div class="adm-table-wrap">
    <table class="adm-table">
      <thead>
        <tr><th>Nom</th><th>Email</th><th>Rôle</th><th>Premium</th><th>Statut</th><th>Inscription</th><th>Actions</th></tr>
      </thead>
      <tbody>
        @forelse($users as $user)
        <tr>
          <td>
            <div style="display:flex;align-items:center;gap:10px">
              <div class="adm-avatar">{{ strtoupper(substr($user->prenom ?? '?', 0, 1)) }}</div>
              <span style="font-weight:600;color:#042C53">{{ $user->nom_complet }}</span>
            </div>
          </td>
          <td style="color:#64748b;font-size:12.5px">{{ $user->email }}</td>
          <td>
            <span class="adm-badge adm-badge--{{ match($user->role) {
              'admin'     => 'red',
              'recruteur' => 'violet',
              'talent'    => 'orange',
              default     => 'blue'
            } }}">{{ ucfirst($user->role) }}</span>
          </td>
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
              @if($user->role !== 'admin')
              <form method="POST" action="{{ route('admin.utilisateurs.statut', $user) }}">
                @csrf @method('PATCH')
                <button type="submit" class="adm-btn adm-btn--ghost adm-btn--sm" style="color:{{ $user->actif ? '#e53e3e' : '#38A169' }}">
                  {{ $user->actif ? 'Suspendre' : 'Réactiver' }}
                </button>
              </form>
              @endif
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7">
            <div class="adm-empty">
              <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
              <h3>Aucun utilisateur</h3>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($users->hasPages())
    <div style="padding:16px 22px">{{ $users->links() }}</div>
  @endif
</div>
@endsection
