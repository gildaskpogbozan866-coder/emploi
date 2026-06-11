@extends('layouts.candidat')
@section('title', 'Mes candidatures')

@section('sidebar')
@include('candidat._sidebar')
@endsection

@section('content')
<div class="cand-page-header">
  <div class="cand-page-header__left">
    <h1 class="cand-page-header__title">Mes candidatures</h1>
    <p class="cand-page-header__sub">Suivi de toutes vos candidatures envoyées</p>
  </div>
  <div class="cand-page-header__actions">
    <a href="{{ route('offre.list') }}" class="cand-btn cand-btn--yellow">
      <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      Postuler à une offre
    </a>
  </div>
</div>

<div class="cand-card">
  <div style="padding:16px 20px 0">
    @include('partials._search-bar', [
      'route'       => 'candidat.candidatures',
      'placeholder' => 'Rechercher par poste ou entreprise…',
      'filters'     => [
        ['name' => 'statut', 'label' => 'Tous les statuts', 'options' => ['envoyee' => 'Envoyée', 'vue' => 'Vue', 'retenue' => 'Retenue', 'entretien' => 'Entretien', 'refusee' => 'Refusée']],
      ],
    ])
  </div>
  <div class="cand-table-wrap">
    <table class="cand-table">
      <thead>
        <tr><th>Poste</th><th>Entreprise</th><th>Type</th><th>Statut</th><th>Date</th><th>Action</th></tr>
      </thead>
      <tbody>
        @forelse($candidatures as $c)
        <tr>
          <td>
            <a href="{{ route('offre.detail', $c->offre) }}" style="color:#185FA5;font-weight:600;text-decoration:none">{{ $c->offre->titre }}</a>
          </td>
          <td style="color:#6b7a8d">{{ $c->offre->entreprise }}</td>
          <td><span class="cand-badge cand-badge--gray">{{ $c->offre->type }}</span></td>
          <td>
            <span class="cand-badge cand-badge--{{ match($c->statut) {
              'envoyee'   => 'blue',
              'vue'       => 'yellow',
              'retenue'   => 'green',
              'refusee'   => 'red',
              'entretien' => 'green',
              default     => 'gray'
            } }}">
              {{ match($c->statut) {
                'envoyee'   => 'Envoyée',
                'vue'       => 'Vue',
                'retenue'   => '✓ Retenue',
                'refusee'   => 'Refusée',
                'entretien' => 'Entretien',
                default     => ucfirst($c->statut)
              } }}
            </span>
          </td>
          <td style="color:#6b7a8d;font-size:12px">{{ $c->created_at->format('d/m/Y') }}</td>
          <td>
            <a href="{{ route('candidat.candidatures.detail', $c) }}" class="cand-btn cand-btn--outline cand-btn--sm">Détail <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></a>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6">
            <div class="cand-empty">
              <div class="cand-empty__icon">
                <svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
              </div>
              <p class="cand-empty__title">Aucune candidature</p>
              <p class="cand-empty__text">Vous n'avez pas encore postulé à une offre d'emploi.</p>
              <a href="{{ route('offre.list') }}" class="cand-btn cand-btn--primary">Parcourir les offres</a>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($candidatures->hasPages())
    <div style="padding:16px 0 4px">{{ $candidatures->links() }}</div>
  @endif
</div>
@endsection
