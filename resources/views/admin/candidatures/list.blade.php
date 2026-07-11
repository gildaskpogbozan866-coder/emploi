@extends('layouts.admin')
@section('title', 'Candidatures | Administration')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <h1>Gestion des candidatures</h1>
    <p>{{ $candidatures->total() }} candidature{{ $candidatures->total() > 1 ? 's' : '' }} au total</p>
  </div>
</div>

<div class="adm-filters">
  <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;width:100%">
    <div class="adm-search" style="flex:1;max-width:300px">
      <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" name="q" value="{{ request('q') }}" placeholder="Candidat, offre, entreprise…">
    </div>
    <select name="statut" class="adm-select">
      <option value="">Tous les statuts</option>
      @foreach(['envoyee' => 'Envoyée','vue' => 'Vue','entretien' => 'Entretien','retenue' => 'Retenue','refusee' => 'Refusée'] as $val => $label)
        <option value="{{ $val }}" {{ request('statut') === $val ? 'selected' : '' }}>{{ $label }}</option>
      @endforeach
    </select>
    <button type="submit" class="adm-btn adm-btn--primary">Filtrer</button>
    @if(request()->hasAny(['q','statut']))
      <a href="{{ route('admin.candidatures.list') }}" class="adm-btn adm-btn--outline">Effacer</a>
    @endif
  </form>
</div>

<div class="adm-card">
  <div class="adm-table-wrap">
    <table class="adm-table">
      <thead>
        <tr><th>Candidat</th><th>Offre</th><th>Entreprise</th><th>Statut</th><th>Date</th><th>Actions</th></tr>
      </thead>
      <tbody>
        @forelse($candidatures as $candidature)
        <tr>
          <td>
            <p style="font-weight:600;color:#042C53;margin:0">{{ $candidature->candidat->nom_complet }}</p>
            <p style="color:#94a3b8;font-size:12px;margin:0">{{ $candidature->candidat->email }}</p>
          </td>
          <td style="max-width:200px">
            <p style="margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $candidature->offre->titre ?? 'Offre supprimée' }}</p>
          </td>
          <td style="color:#64748b">{{ $candidature->offre->entreprise ?? '—' }}</td>
          <td>
            <span class="adm-badge adm-badge--{{ match($candidature->statut) {
              'retenue'   => 'green',
              'refusee'   => 'red',
              'entretien' => 'violet',
              'vue'       => 'blue',
              default     => 'gray'
            } }}">{{ ucfirst(str_replace('_',' ',$candidature->statut)) }}</span>
          </td>
          <td style="color:#94a3b8;font-size:12px">{{ $candidature->created_at->format('d/m/Y') }}</td>
          <td>
            <div class="actions">
              <a href="{{ route('admin.candidatures.detail', $candidature) }}" class="adm-btn adm-btn--outline adm-btn--sm">Voir</a>
              <form method="POST" action="{{ route('admin.candidatures.destroy', $candidature) }}" data-confirm="Supprimer cette candidature ?" data-confirm-btn="Supprimer">
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
              <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
              <h3>Aucune candidature trouvée</h3>
              <p>Essayez d'ajuster vos critères.</p>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($candidatures->hasPages())
    <div style="padding:16px 22px">{{ $candidatures->links() }}</div>
  @endif
</div>
@endsection
