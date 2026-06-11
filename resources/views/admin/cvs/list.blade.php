@extends('layouts.admin')
@section('title', 'CVs & Documents — Administration')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <h1>CVs & Documents</h1>
    <p>{{ $items->total() }} {{ $type === 'documents' ? 'document' : 'CV' }}{{ $items->total() > 1 ? 's' : '' }} déposé{{ $items->total() > 1 ? 's' : '' }}</p>
  </div>
</div>

{{-- Onglets --}}
<div style="display:flex;gap:4px;margin-bottom:18px;border-bottom:2px solid #e2e8f0">
  <a href="{{ route('admin.cvs.list', ['type'=>'cvs'] + request()->except('type','page')) }}"
     style="padding:8px 20px;font-size:13.5px;font-weight:600;border-radius:6px 6px 0 0;border:2px solid transparent;border-bottom:none;text-decoration:none;
            {{ $type === 'cvs' ? 'background:#fff;border-color:#e2e8f0;color:#042C53;margin-bottom:-2px' : 'color:#64748b' }}">
    CVs
  </a>
  <a href="{{ route('admin.cvs.list', ['type'=>'documents'] + request()->except('type','page')) }}"
     style="padding:8px 20px;font-size:13.5px;font-weight:600;border-radius:6px 6px 0 0;border:2px solid transparent;border-bottom:none;text-decoration:none;
            {{ $type === 'documents' ? 'background:#fff;border-color:#e2e8f0;color:#042C53;margin-bottom:-2px' : 'color:#64748b' }}">
    Documents
  </a>
</div>

{{-- Filtres --}}
<div class="adm-filters">
  <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;width:100%">
    <input type="hidden" name="type" value="{{ $type }}">
    <div class="adm-search" style="flex:1;max-width:320px">
      <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" name="q" value="{{ request('q') }}" placeholder="{{ $type === 'documents' ? 'Nom, candidat, pays…' : 'Titre de poste, pays…' }}">
    </div>

    @if($type === 'cvs')
      <select name="plan" class="adm-select">
        <option value="">Tous les plans</option>
        <option value="gratuit" {{ request('plan') === 'gratuit' ? 'selected' : '' }}>Gratuit</option>
        <option value="premium" {{ request('plan') === 'premium' ? 'selected' : '' }}>Premium</option>
      </select>
    @else
      <select name="type_doc" class="adm-select">
        <option value="">Tous les types</option>
        @foreach($typeDocs as $td)
          <option value="{{ $td->id }}" {{ request('type_doc') == $td->id ? 'selected' : '' }}>{{ $td->nom }}</option>
        @endforeach
      </select>
    @endif

    <button type="submit" class="adm-btn adm-btn--primary">Filtrer</button>
    @if(request()->hasAny(['q','plan','type_doc']))
      <a href="{{ route('admin.cvs.list', ['type'=>$type]) }}" class="adm-btn adm-btn--outline">Effacer</a>
    @endif
  </form>
</div>

<div class="adm-card">
  <div class="adm-table-wrap">

    @if($type === 'cvs')
    <table class="adm-table">
      <thead>
        <tr><th>Candidat</th><th>Poste visé</th><th>Pays</th><th>Plan</th><th>Vues</th><th>Visible</th><th>Déposé le</th><th>Actions</th></tr>
      </thead>
      <tbody>
        @forelse($items as $cv)
        <tr>
          <td>
            <div style="font-weight:600;color:#042C53">{{ $cv->candidat->nom_complet }}</div>
            <div style="font-size:12px;color:#94a3b8">{{ $cv->candidat->email }}</div>
          </td>
          <td style="font-weight:500;color:#042C53">{{ $cv->titre_poste }}</td>
          <td style="color:#64748b">{{ $cv->pays }}</td>
          <td>
            <span class="adm-badge adm-badge--{{ $cv->plan === 'premium' ? 'yellow' : 'gray' }}">{{ ucfirst($cv->plan) }}</span>
          </td>
          <td style="text-align:center"><strong>{{ $cv->vues }}</strong></td>
          <td style="text-align:center">
            <span class="adm-badge adm-badge--{{ $cv->visible ? 'green' : 'red' }}">{{ $cv->visible ? 'Oui' : 'Non' }}</span>
          </td>
          <td style="color:#94a3b8;font-size:12px">{{ $cv->created_at->format('d/m/Y') }}</td>
          <td>
            <div class="actions">
              <a href="{{ route('admin.cvs.detail', $cv) }}" class="adm-btn adm-btn--ghost adm-btn--sm">Voir</a>
              <form method="POST" action="{{ route('admin.cvs.destroy', $cv) }}" data-confirm="Supprimer ce CV ?" data-confirm-btn="Supprimer">
                @csrf @method('DELETE')
                <button type="submit" class="adm-btn adm-btn--danger adm-btn--sm">Supprimer</button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr><td colspan="8"><div class="adm-empty"><svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg><h3>Aucun CV trouvé</h3></div></td></tr>
        @endforelse
      </tbody>
    </table>

    @else
    <table class="adm-table">
      <thead>
        <tr><th>Candidat</th><th>Nom du document</th><th>Type</th><th>Pays</th><th>Déposé le</th><th>Actions</th></tr>
      </thead>
      <tbody>
        @forelse($items as $doc)
        <tr>
          <td>
            <div style="font-weight:600;color:#042C53">{{ $doc->user->nom_complet }}</div>
            <div style="font-size:12px;color:#94a3b8">{{ $doc->user->email }}</div>
          </td>
          <td style="font-weight:500;color:#042C53">{{ $doc->nom }}</td>
          <td><span class="adm-badge adm-badge--blue">{{ $doc->type->nom ?? '—' }}</span></td>
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
        <tr><td colspan="6"><div class="adm-empty"><svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg><h3>Aucun document trouvé</h3></div></td></tr>
        @endforelse
      </tbody>
    </table>
    @endif

  </div>
  @if($items->hasPages())
    <div style="padding:16px 22px">{{ $items->links() }}</div>
  @endif
</div>
@endsection
