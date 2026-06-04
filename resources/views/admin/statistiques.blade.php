@extends('layouts.admin')
@section('title', 'Statistiques — Administration')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <h1>Statistiques de la plateforme</h1>
    <p>Vue d'ensemble des données clés — Emploi Bouge Bénin</p>
  </div>
</div>

{{-- KPIs --}}
<div class="adm-stats" style="grid-template-columns:repeat(auto-fit,minmax(150px,1fr));margin-bottom:28px">
  <div class="adm-stat">
    <div class="adm-stat__icon adm-stat__icon--blue">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
    </div>
    <div>
      <div class="adm-stat__val">{{ $totaux['users'] }}</div>
      <div class="adm-stat__label">Utilisateurs</div>
    </div>
  </div>
  <div class="adm-stat">
    <div class="adm-stat__icon adm-stat__icon--orange">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/></svg>
    </div>
    <div>
      <div class="adm-stat__val">{{ $totaux['offres'] }}</div>
      <div class="adm-stat__label">Offres publiées</div>
    </div>
  </div>
  <div class="adm-stat">
    <div class="adm-stat__icon adm-stat__icon--violet">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
    </div>
    <div>
      <div class="adm-stat__val">{{ $totaux['candidatures'] }}</div>
      <div class="adm-stat__label">Candidatures</div>
    </div>
  </div>
  <div class="adm-stat">
    <div class="adm-stat__icon adm-stat__icon--yellow">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg>
    </div>
    <div>
      <div class="adm-stat__val">{{ $totaux['cvs'] }}</div>
      <div class="adm-stat__label">CVs déposés</div>
    </div>
  </div>
  <div class="adm-stat">
    <div class="adm-stat__icon adm-stat__icon--orange">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/></svg>
    </div>
    <div>
      <div class="adm-stat__val">{{ $totaux['commandes'] }}</div>
      <div class="adm-stat__label">Commandes</div>
    </div>
  </div>
  <div class="adm-stat">
    <div class="adm-stat__icon adm-stat__icon--green">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
    </div>
    <div>
      <div class="adm-stat__val" style="color:#38A169;font-size:1rem">{{ number_format($totaux['revenus_30j'],0,',',' ') }}</div>
      <div class="adm-stat__label">FCFA / 30 jours</div>
    </div>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">

  {{-- Offres par type --}}
  <div class="adm-card">
    <div class="adm-card__header"><h2>Offres par type de contrat</h2></div>
    <div class="adm-card__body">
      @php $totalOffres = $offresParType->sum('total') ?: 1; @endphp
      @foreach($offresParType as $item)
        <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px">
          <span style="width:100px;font-size:13px;font-weight:600;color:#475569;flex-shrink:0">{{ $item->type }}</span>
          <div style="flex:1;height:10px;background:#f1f5f9;border-radius:5px;overflow:hidden">
            <div style="height:100%;background:var(--bleu-clair);border-radius:5px;width:{{ round($item->total / $totalOffres * 100) }}%"></div>
          </div>
          <span style="font-size:13px;font-weight:700;color:#042C53;min-width:30px;text-align:right">{{ $item->total }}</span>
        </div>
      @endforeach
      @if($offresParType->isEmpty())
        <p style="color:#94a3b8;font-size:13px">Aucune donnée disponible.</p>
      @endif
    </div>
  </div>

  {{-- Offres par statut --}}
  <div class="adm-card">
    <div class="adm-card__header"><h2>Offres par statut</h2></div>
    <div class="adm-card__body">
      @foreach($offresParStatut as $item)
        <div style="display:flex;align-items:center;justify-content:space-between;padding:10px 0;border-bottom:1px solid #f0f2f5">
          <span class="badge-statut badge-statut--{{ $item->statut }}">{{ ucfirst(str_replace('_',' ',$item->statut)) }}</span>
          <strong>{{ $item->total }}</strong>
        </div>
      @endforeach
      @if($offresParStatut->isEmpty())
        <p style="color:#94a3b8;font-size:13px">Aucune donnée disponible.</p>
      @endif
    </div>
  </div>

  {{-- Inscriptions par mois --}}
  <div class="adm-card" style="grid-column:1/-1">
    <div class="adm-card__header"><h2>Nouvelles inscriptions (6 derniers mois)</h2></div>
    <div class="adm-card__body">
      @if($inscriptions->isEmpty())
        <p style="color:#94a3b8;font-size:13px">Aucune inscription sur les 6 derniers mois.</p>
      @else
        @php $maxIns = $inscriptions->max('total') ?: 1; @endphp
        <div style="display:flex;align-items:flex-end;gap:14px;height:130px">
          @foreach($inscriptions as $insc)
            <div style="flex:1;display:flex;flex-direction:column;align-items:center;gap:6px">
              <span style="font-size:12px;font-weight:700;color:#042C53">{{ $insc->total }}</span>
              <div style="width:100%;background:#185FA5;border-radius:5px 5px 0 0;height:{{ round($insc->total / $maxIns * 100) }}px;min-height:4px;opacity:.8"></div>
              <span style="font-size:11px;color:#94a3b8">{{ str_pad($insc->mois,2,'0',STR_PAD_LEFT) }}/{{ $insc->annee }}</span>
            </div>
          @endforeach
        </div>
      @endif
    </div>
  </div>

</div>
@endsection
