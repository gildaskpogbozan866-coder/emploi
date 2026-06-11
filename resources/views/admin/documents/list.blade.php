@extends('layouts.admin')
@section('title', 'Documents — Administration')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <h1>Documents déposés</h1>
    <p>{{ $documents->total() }} document{{ $documents->total() > 1 ? 's' : '' }}</p>
  </div>
</div>

<div class="adm-filters">
  <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;width:100%">
    <div class="adm-search" style="flex:1;max-width:320px">
      <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" name="q" value="{{ request('q') }}" placeholder="Nom, candidat, pays…">
    </div>
    <select name="type" class="adm-select">
      <option value="">Tous les types</option>
      @foreach($types as $type)
        <option value="{{ $type->id }}" {{ request('type') == $type->id ? 'selected' : '' }}>{{ $type->nom }}</option>
      @endforeach
    </select>
    <button type="submit" class="adm-btn adm-btn--primary">Filtrer</button>
    @if(request()->hasAny(['q','type']))
      <a href="{{ route('admin.documents.list') }}" class="adm-btn adm-btn--outline">Effacer</a>
    @endif
  </form>
</div>

<div class="adm-card">
  <div class="adm-table-wrap">
    <table class="adm-table">
      <thead>
        <tr><th>Candidat</th><th>Nom du document</th><th>Type</th><th>Pays</th><th>Déposé le</th><th>Actions</th></tr>
      </thead>
      <tbody>
        @forelse($documents as $doc)
        <tr>
          <td>
            <div style="font-weight:600;color:#042C53">{{ $doc->user->nom_complet }}</div>
            <div style="font-size:12px;color:#94a3b8">{{ $doc->user->email }}</div>
          </td>
          <td style="font-weight:500;color:#042C53">{{ $doc->nom }}</td>
          <td>
            <span class="adm-badge adm-badge--blue">{{ $doc->type->nom ?? '—' }}</span>
          </td>
          <td style="color:#64748b">{{ $doc->pays ?? '—' }}</td>
          <td style="color:#94a3b8;font-size:12px">{{ $doc->created_at->format('d/m/Y') }}</td>
          <td>
            <div class="actions">
              <a href="{{ route('admin.documents.detail', $doc) }}" class="adm-btn adm-btn--ghost adm-btn--sm">Voir</a>
              <form method="POST" action="{{ route('admin.documents.destroy', $doc) }}" data-confirm="Supprimer ce document ?" data-confirm-btn="Supprimer">
                @csrf @method('DELETE')
                <button type="submit" class="adm-btn adm-btn--danger adm-btn--sm">Supprimer</button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6">
            <div class="adm-empty">
              <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg>
              <h3>Aucun document trouvé</h3>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($documents->hasPages())
    <div style="padding:16px 22px">{{ $documents->links() }}</div>
  @endif
</div>
@endsection
