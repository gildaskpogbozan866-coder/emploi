@extends('layouts.admin')
@section('title', 'Tableau de bord — Administration')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <h1>Tableau de bord</h1>
    <p>Vue d'ensemble de la plateforme Emploi Bouge Bénin</p>
  </div>
</div>

{{-- KPIs principaux --}}
<div class="adm-stats" style="grid-template-columns:repeat(auto-fit,minmax(150px,1fr))">
  <div class="adm-stat">
    <div class="adm-stat__icon adm-stat__icon--blue">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
    </div>
    <div>
      <div class="adm-stat__val">{{ $stats['candidats'] }}</div>
      <div class="adm-stat__label">Candidats</div>
    </div>
  </div>
  <div class="adm-stat">
    <div class="adm-stat__icon adm-stat__icon--violet">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
    </div>
    <div>
      <div class="adm-stat__val">{{ $stats['recruteurs'] }}</div>
      <div class="adm-stat__label">Recruteurs</div>
    </div>
  </div>
  <div class="adm-stat">
    <div class="adm-stat__icon adm-stat__icon--orange">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="8" r="7"/><polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"/></svg>
    </div>
    <div>
      <div class="adm-stat__val">{{ $stats['talents'] }}</div>
      <div class="adm-stat__label">Talents</div>
    </div>
  </div>
  <div class="adm-stat">
    <div class="adm-stat__icon adm-stat__icon--blue">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg>
    </div>
    <div>
      <div class="adm-stat__val">{{ $stats['offres_actives'] }}</div>
      <div class="adm-stat__label">Offres actives</div>
    </div>
  </div>
  <div class="adm-stat">
    <div class="adm-stat__icon adm-stat__icon--orange">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg>
    </div>
    <div>
      <div class="adm-stat__val">{{ $stats['cvs'] }}</div>
      <div class="adm-stat__label">CVs déposés</div>
    </div>
  </div>
  <div class="adm-stat">
    <div class="adm-stat__icon adm-stat__icon--violet">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
    </div>
    <div>
      <div class="adm-stat__val">{{ $stats['candidatures'] }}</div>
      <div class="adm-stat__label">Candidatures</div>
    </div>
  </div>
  <div class="adm-stat">
    <div class="adm-stat__icon adm-stat__icon--yellow">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg>
    </div>
    <div>
      <div class="adm-stat__val">{{ $stats['commandes'] }}</div>
      <div class="adm-stat__label">Commandes</div>
    </div>
  </div>
  <div class="adm-stat">
    <div class="adm-stat__icon adm-stat__icon--green">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
    </div>
    <div>
      <div class="adm-stat__val" style="color:#38A169;font-size:1.1rem">{{ number_format($stats['paiements'], 0, ',', ' ') }}</div>
      <div class="adm-stat__label">FCFA confirmés</div>
    </div>
  </div>
</div>

{{-- Alerte signalements --}}
@if($stats['signalements'] > 0)
  <div class="flash flash--warning" style="margin-bottom:24px">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
    <span>{{ $stats['signalements'] }} signalement(s) en attente de traitement.
      <a href="{{ route('admin.signalements.list') }}" style="font-weight:700;margin-left:6px;color:inherit">Traiter <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></a>
    </span>
  </div>
@endif

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-top:4px">

  {{-- Derniers inscrits --}}
  <div class="adm-card">
    <div class="adm-card__header">
      <h2>Derniers inscrits</h2>
      <a href="{{ route('admin.utilisateurs.candidats') }}" class="adm-btn adm-btn--ghost adm-btn--sm">Voir tout <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></a>
    </div>
    <div class="adm-table-wrap">
      <table class="adm-table">
        <thead><tr><th>Nom</th><th>Rôle</th><th>Date</th></tr></thead>
        <tbody>
          @foreach($derniers_utilisateurs as $u)
          <tr>
            <td style="font-weight:500">{{ $u->nom_complet }}</td>
            <td><span class="badge-role badge-role--{{ $u->role }}">{{ ucfirst($u->role) }}</span></td>
            <td style="color:#94a3b8;font-size:12px">{{ $u->created_at->format('d/m/Y') }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  {{-- Dernières offres --}}
  <div class="adm-card">
    <div class="adm-card__header">
      <h2>Dernières offres</h2>
      <a href="{{ route('admin.offres.list') }}" class="adm-btn adm-btn--ghost adm-btn--sm">Voir tout <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></a>
    </div>
    <div class="adm-table-wrap">
      <table class="adm-table">
        <thead><tr><th>Titre</th><th>Statut</th><th>Date</th></tr></thead>
        <tbody>
          @foreach($dernieres_offres as $offre)
          <tr>
            <td style="max-width:180px">
              <p style="font-weight:500;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $offre->titre }}</p>
              <p style="font-size:11.5px;color:#94a3b8;margin:2px 0 0">{{ $offre->entreprise }}</p>
            </td>
            <td><span class="badge-statut badge-statut--{{ $offre->statut }}">{{ ucfirst(str_replace('_',' ',$offre->statut)) }}</span></td>
            <td style="color:#94a3b8;font-size:12px">{{ $offre->created_at->format('d/m/Y') }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  {{-- Dernières commandes --}}
  <div class="adm-card">
    <div class="adm-card__header">
      <h2>Dernières commandes</h2>
      <a href="{{ route('admin.commandes.list') }}" class="adm-btn adm-btn--ghost adm-btn--sm">Voir tout <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></a>
    </div>
    <div class="adm-table-wrap">
      <table class="adm-table">
        <thead><tr><th>Client</th><th>Service</th><th>Statut</th></tr></thead>
        <tbody>
          @foreach($dernieres_commandes as $cmd)
          <tr>
            <td style="font-weight:500">{{ $cmd->user->nom_complet }}</td>
            <td style="color:#6b7a8d">{{ $cmd->service->nom }}</td>
            <td><span class="badge-statut badge-statut--{{ $cmd->statut }}">{{ ucfirst(str_replace('_',' ',$cmd->statut)) }}</span></td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>

  {{-- Signalements --}}
  <div class="adm-card">
    <div class="adm-card__header">
      <h2>Signalements en attente</h2>
      <a href="{{ route('admin.signalements.list') }}" class="adm-btn adm-btn--ghost adm-btn--sm">Voir tout <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></a>
    </div>
    @if($derniers_signalements->isEmpty())
      <div class="adm-empty" style="padding:24px 22px">
        <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
        <p style="font-size:13.5px;color:#64748b">Aucun signalement en attente</p>
      </div>
    @else
      <div class="adm-table-wrap">
        <table class="adm-table">
          <thead><tr><th>Type</th><th>Raison</th><th>Signalé par</th></tr></thead>
          <tbody>
            @foreach($derniers_signalements as $s)
            <tr>
              <td><span class="tag">{{ ucfirst($s->type) }}</span></td>
              <td style="color:#6b7a8d">{{ Str::limit($s->raison, 32) }}</td>
              <td style="font-weight:500">{{ $s->user->nom_complet }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>

</div>
@endsection
