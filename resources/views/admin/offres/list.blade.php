@extends('layouts.admin')
@section('title', 'Offres — Administration')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <h1>Gestion des offres</h1>
    <p>{{ $offres->total() }} offre{{ $offres->total() > 1 ? 's' : '' }} au total</p>
  </div>
</div>

<div class="adm-filters">
  <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap;width:100%">
    <div class="adm-search" style="flex:1;max-width:300px">
      <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      <input type="text" name="q" value="{{ request('q') }}" placeholder="Titre, entreprise…">
    </div>
    <select name="statut" class="adm-select">
      <option value="">Tous les statuts</option>
      @foreach(['en_attente' => 'En attente','active' => 'Active','expiree' => 'Expirée','suspendue' => 'Suspendue'] as $val => $label)
        <option value="{{ $val }}" {{ request('statut') === $val ? 'selected' : '' }}>{{ $label }}</option>
      @endforeach
    </select>
    <button type="submit" class="adm-btn adm-btn--primary">Filtrer</button>
    @if(request()->hasAny(['q','statut']))
      <a href="{{ route('admin.offres.list') }}" class="adm-btn adm-btn--outline">Effacer</a>
    @endif
  </form>
</div>

<div class="adm-card">
  <div class="adm-table-wrap">
    <table class="adm-table">
      <thead>
        <tr><th>Titre</th><th>Entreprise</th><th>Type</th><th>Candidatures</th><th>Statut</th><th>Date</th><th>Actions</th></tr>
      </thead>
      <tbody>
        @forelse($offres as $offre)
        <tr>
          <td style="max-width:200px">
            <p style="font-weight:500;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $offre->titre }}</p>
          </td>
          <td style="color:#64748b">{{ $offre->entreprise }}</td>
          <td><span class="tag tag--type">{{ $offre->type }}</span></td>
          <td><strong>{{ $offre->candidatures_count }}</strong></td>
          <td>
            <form method="POST" action="{{ route('admin.offres.statut', $offre) }}">
              @csrf @method('PATCH')
              <select name="statut" onchange="this.form.submit()" class="adm-select" style="padding:5px 8px;font-size:12px">
                @foreach(['en_attente' => 'En attente','active' => 'Active','expiree' => 'Expirée','suspendue' => 'Suspendue'] as $val => $label)
                  <option value="{{ $val }}" {{ $offre->statut === $val ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
              </select>
            </form>
          </td>
          <td style="color:#94a3b8;font-size:12px">{{ $offre->created_at->format('d/m/Y') }}</td>
          <td>
            <div class="actions">
              <a href="{{ route('offre.detail', $offre) }}" target="_blank" class="adm-btn adm-btn--outline adm-btn--sm">Voir</a>
              <form method="POST" action="{{ route('admin.offres.destroy', $offre) }}" data-confirm="Supprimer cette offre ?" data-confirm-btn="Supprimer">
                @csrf @method('DELETE')
                <button type="submit" class="adm-btn adm-btn--danger adm-btn--sm">Supprimer</button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7">
            <div class="adm-empty">
              <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/></svg>
              <h3>Aucune offre trouvée</h3>
              <p>Essayez d'ajuster vos critères.</p>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($offres->hasPages())
    <div style="padding:16px 22px">{{ $offres->links() }}</div>
  @endif
</div>
@endsection
