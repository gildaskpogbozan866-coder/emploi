@extends('layouts.admin')
@section('title', 'CVs — Administration')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <h1>Gestion des CVs</h1>
    <p>{{ $cvs->total() }} CV{{ $cvs->total() > 1 ? 's' : '' }} déposé{{ $cvs->total() > 1 ? 's' : '' }}</p>
  </div>
</div>

<div class="adm-filters">
  <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;width:100%">
    <div class="adm-search" style="flex:1;max-width:320px">
      <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" name="q" value="{{ request('q') }}" placeholder="Titre de poste, pays…">
    </div>
    <select name="plan" class="adm-select">
      <option value="">Tous les plans</option>
      <option value="gratuit" {{ request('plan') === 'gratuit' ? 'selected' : '' }}>Gratuit</option>
      <option value="premium" {{ request('plan') === 'premium' ? 'selected' : '' }}>Premium</option>
    </select>
    <button type="submit" class="adm-btn adm-btn--primary">Filtrer</button>
    @if(request()->hasAny(['q','plan']))
      <a href="{{ route('admin.cvs.list') }}" class="adm-btn adm-btn--outline">Effacer</a>
    @endif
  </form>
</div>

<div class="adm-card">
  <div class="adm-table-wrap">
    <table class="adm-table">
      <thead>
        <tr><th>Candidat</th><th>Poste visé</th><th>Pays</th><th>Plan</th><th>Vues</th><th>Visible</th><th>Déposé le</th><th>Actions</th></tr>
      </thead>
      <tbody>
        @forelse($cvs as $cv)
        <tr>
          <td>
            <div style="font-weight:600;color:#042C53">{{ $cv->candidat->nom_complet }}</div>
            <div style="font-size:12px;color:#94a3b8">{{ $cv->candidat->email }}</div>
          </td>
          <td style="font-weight:500;color:#042C53">{{ $cv->titre_poste }}</td>
          <td style="color:#64748b">{{ $cv->pays }}</td>
          <td>
            <span class="adm-badge adm-badge--{{ $cv->plan === 'premium' ? 'yellow' : 'gray' }}">
              {{ ucfirst($cv->plan) }}
            </span>
          </td>
          <td style="text-align:center"><strong>{{ $cv->vues }}</strong></td>
          <td style="text-align:center">
            <span class="adm-badge adm-badge--{{ $cv->visible ? 'green' : 'red' }}">
              {{ $cv->visible ? 'Oui' : 'Non' }}
            </span>
          </td>
          <td style="color:#94a3b8;font-size:12px">{{ $cv->created_at->format('d/m/Y') }}</td>
          <td>
            <div class="actions">
              <a href="{{ route('admin.cvs.detail', $cv) }}" class="adm-btn adm-btn--ghost adm-btn--sm">Voir</a>
              <form method="POST" action="{{ route('admin.cvs.destroy', $cv) }}" onsubmit="return confirm('Supprimer ce CV ?')">
                @csrf @method('DELETE')
                <button type="submit" class="adm-btn adm-btn--danger adm-btn--sm">Supprimer</button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8">
            <div class="adm-empty">
              <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg>
              <h3>Aucun CV trouvé</h3>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($cvs->hasPages())
    <div style="padding:16px 22px">{{ $cvs->links() }}</div>
  @endif
</div>
@endsection
