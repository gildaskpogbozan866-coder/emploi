@extends('layouts.recruteur')
@section('title', 'Statistiques')

@section('sidebar')
@include('recruteur._sidebar')
@endsection

@section('content')
<div class="rec-topbar">
  <div class="rec-topbar__left">
    <h1>Mes statistiques</h1>
    <p>Analysez les performances de vos offres d'emploi</p>
  </div>
</div>

{{-- Stat cards --}}
<div class="rec-stats">
  <div class="rec-stat">
    <div class="rec-stat__icon rec-stat__icon--blue">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg>
    </div>
    <div class="rec-stat__val">{{ $stats['offres_total'] }}</div>
    <div class="rec-stat__label">Offres publiées</div>
  </div>
  <div class="rec-stat">
    <div class="rec-stat__icon rec-stat__icon--green">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
    </div>
    <div class="rec-stat__val" style="color:#38A169">{{ $stats['offres_actives'] }}</div>
    <div class="rec-stat__label">Offres actives</div>
  </div>
  <div class="rec-stat">
    <div class="rec-stat__icon rec-stat__icon--purple">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
    </div>
    <div class="rec-stat__val">{{ $stats['candidatures_total'] }}</div>
    <div class="rec-stat__label">Candidatures reçues</div>
  </div>
  <div class="rec-stat">
    <div class="rec-stat__icon rec-stat__icon--yellow">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/></svg>
    </div>
    <div class="rec-stat__val" style="color:#c79a00">{{ $stats['nouvelles_candid'] }}</div>
    <div class="rec-stat__label">Non lues</div>
  </div>
</div>

{{-- Candidatures par offre --}}
<div class="rec-card">
  <div class="rec-card__head">
    <span class="rec-card__title">Candidatures par offre</span>
  </div>
  <div class="rec-card__body">
    @if($dernieres_offres->isEmpty())
      <div class="rec-empty" style="padding:24px">
        <h3>Aucune offre publiée</h3>
        <p>Publiez des offres pour voir vos statistiques ici.</p>
      </div>
    @else
      @php $max = $dernieres_offres->max('candidatures_count') ?: 1; @endphp
      @foreach($dernieres_offres as $offre)
      <div style="display:flex;align-items:center;gap:14px;padding:12px 0;border-bottom:1px solid #f0f2f5">
        <div style="flex:1;min-width:0">
          <p style="font-size:13.5px;color:#042C53;font-weight:500;margin:0 0 6px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $offre->titre }}</p>
          <div class="rec-progress" style="height:8px">
            <div class="rec-progress__fill" style="width:{{ round($offre->candidatures_count / $max * 100) }}%"></div>
          </div>
        </div>
        <div style="text-align:right;flex-shrink:0;min-width:60px">
          <span style="font-size:1.1rem;font-weight:800;color:#185FA5">{{ $offre->candidatures_count }}</span>
          <p style="font-size:11px;color:#94a3b8;margin:2px 0 0">candidature{{ $offre->candidatures_count > 1 ? 's' : '' }}</p>
        </div>
      </div>
      @endforeach
    @endif
  </div>
</div>
@endsection
