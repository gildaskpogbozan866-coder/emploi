@extends('layouts.recruteur')
@section('title', 'Statistiques — ' . $offre->titre)

@section('sidebar')
@include('recruteur._sidebar')
@endsection

@section('content')
<div class="rec-topbar">
  <div class="rec-topbar__left">
    <a href="{{ route('recruteur.offres') }}" class="rec-btn rec-btn--outline rec-btn--sm" style="margin-bottom:8px">
      <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg> Retour
    </a>
    <h1>{{ $offre->titre }}</h1>
    <p>{{ $offre->entreprise }} · Statistiques de l'offre</p>
  </div>
  <div class="rec-topbar__actions">
    <a href="{{ route('recruteur.offres.edit', $offre) }}" class="rec-btn rec-btn--outline">Modifier l'offre</a>
    <a href="{{ route('offre.detail', $offre) }}" target="_blank" class="rec-btn rec-btn--outline">Voir public</a>
  </div>
</div>

{{-- KPIs --}}
<div class="rec-stats" style="margin-bottom:20px">
  <div class="rec-stat">
    <div class="rec-stat__icon rec-stat__icon--blue">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
    </div>
    <div class="rec-stat__val">{{ number_format($offre->vues) }}</div>
    <div class="rec-stat__label">Vues</div>
  </div>
  <div class="rec-stat">
    <div class="rec-stat__icon rec-stat__icon--purple">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
    </div>
    <div class="rec-stat__val">{{ $candidatures->count() }}</div>
    <div class="rec-stat__label">Candidatures</div>
  </div>
  <div class="rec-stat">
    <div class="rec-stat__icon rec-stat__icon--green">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
    </div>
    <div class="rec-stat__val" style="color:#38A169">{{ $parStatut->get('retenue', 0) + $parStatut->get('entretien', 0) }}</div>
    <div class="rec-stat__label">Retenues / Entretiens</div>
  </div>
  <div class="rec-stat">
    <div class="rec-stat__icon rec-stat__icon--yellow">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    </div>
    @php $taux = $offre->vues > 0 ? round($candidatures->count() / $offre->vues * 100, 1) : 0; @endphp
    <div class="rec-stat__val" style="color:#c79a00">{{ $taux }}%</div>
    <div class="rec-stat__label">Taux de conversion</div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start">

  {{-- Tableau candidatures --}}
  <div class="rec-card">
    <div class="rec-card__head">
      <span class="rec-card__title">Candidatures reçues ({{ $candidatures->count() }})</span>
    </div>
    <div class="rec-table-wrap">
      <table class="rec-table">
        <thead>
          <tr><th>Candidat</th><th>Email</th><th>Statut</th><th>Date</th></tr>
        </thead>
        <tbody>
          @forelse($candidatures as $c)
          <tr>
            <td style="font-weight:600;color:#042C53">{{ $c->candidat->nom_complet }}</td>
            <td style="color:#64748b;font-size:12.5px">{{ $c->candidat->email }}</td>
            <td>
              <span class="rec-badge rec-badge--{{ match($c->statut) {
                'retenue'   => 'green',
                'refusee'   => 'red',
                'entretien' => 'green',
                'vue'       => 'yellow',
                default     => 'blue'
              } }}">{{ ucfirst($c->statut) }}</span>
            </td>
            <td style="color:#94a3b8;font-size:12px">{{ $c->created_at->format('d/m/Y') }}</td>
          </tr>
          @empty
          <tr>
            <td colspan="4">
              <div class="rec-empty" style="padding:24px">
                <h3>Aucune candidature</h3>
                <p>Cette offre n'a pas encore reçu de candidatures.</p>
              </div>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- Répartition par statut --}}
  <div class="rec-card">
    <div class="rec-card__head">
      <span class="rec-card__title">Répartition</span>
    </div>
    <div class="rec-card__body">
      @foreach(['envoyee' => ['Envoyées','#3B82F6'], 'vue' => ['Vues','#F59E0B'], 'retenue' => ['Retenues','#10B981'], 'entretien' => ['Entretiens','#8B5CF6'], 'refusee' => ['Refusées','#EF4444']] as $statut => [$label, $color])
      @php $n = $parStatut->get($statut, 0); $pct = $candidatures->count() > 0 ? round($n / $candidatures->count() * 100) : 0; @endphp
      <div style="margin-bottom:14px">
        <div style="display:flex;justify-content:space-between;font-size:13px;margin-bottom:4px">
          <span style="color:#374151;font-weight:500">{{ $label }}</span>
          <span style="font-weight:700;color:{{ $color }}">{{ $n }}</span>
        </div>
        <div style="background:#f1f5f9;border-radius:4px;height:6px;overflow:hidden">
          <div style="width:{{ $pct }}%;background:{{ $color }};height:100%;border-radius:4px;transition:width .3s"></div>
        </div>
      </div>
      @endforeach

      <div style="border-top:1px solid #f1f5f9;padding-top:14px;margin-top:6px">
        <p style="font-size:12px;color:#94a3b8;margin:0 0 4px">Publiée</p>
        <p style="font-size:13px;font-weight:600;color:#374151;margin:0">{{ $offre->created_at->format('d/m/Y') }}</p>
        @if($offre->date_limite)
        <p style="font-size:12px;color:#94a3b8;margin:8px 0 4px">Date limite</p>
        <p style="font-size:13px;font-weight:600;color:#374151;margin:0">{{ $offre->date_limite->format('d/m/Y') }}</p>
        @endif
        <p style="font-size:12px;color:#94a3b8;margin:8px 0 4px">Statut</p>
        <span class="rec-badge rec-badge--{{ match($offre->statut) { 'active' => 'green', 'clos' => 'gray', 'expiree' => 'gray', default => 'yellow' } }}">
          {{ ucfirst(str_replace('_',' ',$offre->statut)) }}
        </span>
      </div>
    </div>
  </div>

</div>
@endsection
