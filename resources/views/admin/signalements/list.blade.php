@extends('layouts.admin')
@section('title', 'Signalements — Administration')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <h1>Signalements</h1>
    <p>Modérez les contenus signalés par les utilisateurs</p>
  </div>
</div>

<div class="adm-filters">
  <form method="GET" style="display:flex;gap:10px;flex-wrap:wrap">
    <select name="statut" class="adm-select">
      <option value="">Tous les statuts</option>
      @foreach(['en_attente' => 'En attente','traite' => 'Traité','rejete' => 'Rejeté'] as $val => $label)
        <option value="{{ $val }}" {{ request('statut') === $val ? 'selected' : '' }}>{{ $label }}</option>
      @endforeach
    </select>
    <button type="submit" class="adm-btn adm-btn--primary">Filtrer</button>
    @if(request('statut'))
      <a href="{{ route('admin.signalements.list') }}" class="adm-btn adm-btn--outline">Effacer</a>
    @endif
  </form>
</div>

<div class="adm-card">
  <div class="adm-table-wrap">
    <table class="adm-table">
      <thead>
        <tr><th>Type</th><th>Raison</th><th>Signalé par</th><th>Statut</th><th>Date</th><th>Action</th></tr>
      </thead>
      <tbody>
        @forelse($signalements as $s)
        <tr>
          <td><span class="tag">{{ ucfirst($s->type) }}</span></td>
          <td style="color:#64748b;max-width:240px">{{ Str::limit($s->raison, 50) }}</td>
          <td style="font-weight:500">{{ $s->user->nom_complet }}</td>
          <td>
            <span class="adm-badge adm-badge--{{ match($s->statut) {
              'en_attente' => 'yellow',
              'traite'     => 'green',
              'rejete'     => 'gray',
              default      => 'gray'
            } }}">
              {{ ucfirst(str_replace('_',' ',$s->statut)) }}
            </span>
          </td>
          <td style="color:#94a3b8;font-size:12px">{{ $s->created_at->format('d/m/Y') }}</td>
          <td>
            <a href="{{ route('admin.signalements.detail', $s) }}" class="adm-btn adm-btn--outline adm-btn--sm">Traiter <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></a>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6">
            <div class="adm-empty">
              <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
              <h3>Aucun signalement en attente</h3>
              <p>La plateforme est propre ✓</p>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($signalements->hasPages())
    <div style="padding:16px 22px">{{ $signalements->links() }}</div>
  @endif
</div>
@endsection
