@extends('layouts.recruteur')
@section('title', 'Mon espace Recruteur')

@section('sidebar')
@include('recruteur._sidebar')
@endsection

@section('content')
<div class="rec-topbar">
  <div class="rec-topbar__left">
    <h1>Bonjour, {{ auth()->user()->prenom }}</h1>
    <p>Vue d'ensemble de votre activité de recrutement</p>
  </div>
  <div class="rec-topbar__actions">
    <a href="{{ route('recruteur.offres.create') }}" class="rec-btn rec-btn--yellow">
      <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Publier une offre
    </a>
  </div>
</div>

{{-- Stats --}}
<div class="rec-stats">
  <div class="rec-stat">
    <div class="rec-stat__icon rec-stat__icon--blue">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg>
    </div>
    <div class="rec-stat__val">{{ $stats['offres_actives'] }}</div>
    <div class="rec-stat__label">Offres actives</div>
  </div>

  <div class="rec-stat">
    <div class="rec-stat__icon rec-stat__icon--green">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
    </div>
    <div class="rec-stat__val">{{ $stats['offres_total'] }}</div>
    <div class="rec-stat__label">Offres publiées</div>
  </div>

  <div class="rec-stat">
    <div class="rec-stat__icon rec-stat__icon--purple">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
    </div>
    <div class="rec-stat__val">{{ $stats['candidatures_total'] }}</div>
    <div class="rec-stat__label">Candidatures reçues</div>
  </div>

  <div class="rec-stat">
    <div class="rec-stat__icon rec-stat__icon--yellow">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
    </div>
    <div class="rec-stat__val" style="color:#c79a00">{{ $stats['nouvelles_candid'] }}</div>
    <div class="rec-stat__label">Nouvelles (non lues)</div>
  </div>
</div>

{{-- Bloc CVthèque crédits --}}
<div class="rec-card" style="margin-bottom:20px;border-color:{{ $cvStats['credits_restants'] > 0 ? '#bae6fd' : '#fde68a' }};background:{{ $cvStats['credits_restants'] > 0 ? '#f0f9ff' : '#fffbeb' }}">
  <div class="rec-card__body" style="display:flex;align-items:center;gap:20px;flex-wrap:wrap">
    <div style="width:46px;height:46px;border-radius:12px;background:{{ $cvStats['credits_restants'] > 0 ? '#0284c7' : '#d97706' }};display:flex;align-items:center;justify-content:center;flex-shrink:0">
      <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#fff" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
    </div>
    <div style="flex:1">
      <p style="font-size:13px;font-weight:700;color:#042C53;margin:0 0 6px">CVthèque — Crédits</p>
      <div style="display:flex;gap:24px;flex-wrap:wrap">
        <div>
          <span style="font-size:1.6rem;font-weight:800;color:{{ $cvStats['credits_restants'] > 0 ? '#0284c7' : '#d97706' }}">{{ $cvStats['credits_restants'] }}</span>
          <span style="font-size:12px;color:#64748b;margin-left:4px">crédit{{ $cvStats['credits_restants'] > 1 ? 's' : '' }} restant{{ $cvStats['credits_restants'] > 1 ? 's' : '' }}</span>
        </div>
        <div>
          <span style="font-size:1.6rem;font-weight:800;color:#042C53">{{ $cvStats['cvs_telecharges'] }}</span>
          <span style="font-size:12px;color:#64748b;margin-left:4px">CV{{ $cvStats['cvs_telecharges'] > 1 ? 's' : '' }} téléchargé{{ $cvStats['cvs_telecharges'] > 1 ? 's' : '' }}</span>
        </div>
        <div>
          <span style="font-size:1.6rem;font-weight:800;color:#042C53">{{ $cvStats['credits_total'] }}</span>
          <span style="font-size:12px;color:#64748b;margin-left:4px">crédit{{ $cvStats['credits_total'] > 1 ? 's' : '' }} achetés au total</span>
        </div>
      </div>
    </div>
    <div style="display:flex;flex-direction:column;gap:8px;min-width:160px">
      <a href="{{ route('recruteur.cvtheque') }}" class="rec-btn rec-btn--primary rec-btn--sm" style="text-align:center;justify-content:center">Parcourir la CVthèque</a>
      <a href="{{ route('recruteur.cv-credits.index') }}" class="rec-btn rec-btn--outline rec-btn--sm" style="text-align:center;justify-content:center">
        {{ $cvStats['credits_restants'] > 0 ? 'Acheter plus de crédits' : '⚠ Acheter des crédits' }}
      </a>
    </div>
  </div>
</div>

{{-- Dernières offres --}}
<div class="rec-card">
  <div class="rec-card__head">
    <span class="rec-card__title">Mes dernières offres d'emploi</span>
    <a href="{{ route('recruteur.offres') }}" class="rec-btn rec-btn--outline rec-btn--sm">Voir tout →</a>
  </div>
  <div class="rec-card__body" style="padding:0">
    @if($dernieres_offres->isEmpty())
      <div class="rec-empty" style="padding:40px 24px">
        <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg>
        <h3>Aucune offre publiée</h3>
        <p>Publiez votre première offre d'emploi pour commencer à recevoir des candidatures.</p>
        <a href="{{ route('recruteur.offres.create') }}" class="rec-btn rec-btn--yellow" style="margin-top:4px">Publier une offre</a>
      </div>
    @else
      <div class="rec-table-wrap">
        <table class="rec-table">
          <thead>
            <tr><th>Titre du poste</th><th>Type</th><th>Candidatures</th><th>Statut</th><th>Date</th><th></th></tr>
          </thead>
          <tbody>
            @foreach($dernieres_offres as $offre)
            <tr>
              <td style="font-weight:600;color:#042C53">{{ $offre->titre }}</td>
              <td><span class="rec-badge rec-badge--blue">{{ $offre->type }}</span></td>
              <td>
                <strong>{{ $offre->candidatures_count }}</strong>
                <span style="color:#94a3b8;font-size:12px"> candidature{{ $offre->candidatures_count > 1 ? 's' : '' }}</span>
              </td>
              <td>
                <span class="rec-badge rec-badge--{{ match($offre->statut) {
                  'active'     => 'green',
                  'en_attente' => 'yellow',
                  'expiree'    => 'gray',
                  'suspendue'  => 'red',
                  default      => 'gray'
                } }}">
                  {{ ucfirst(str_replace('_',' ',$offre->statut)) }}
                </span>
              </td>
              <td style="color:#94a3b8;font-size:12px">{{ $offre->created_at->format('d/m/Y') }}</td>
              <td>
                <a href="{{ route('offre.detail', $offre) }}" class="rec-btn rec-btn--outline rec-btn--sm">Voir</a>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>
</div>

{{-- Actions rapides --}}
<div class="rec-grid-2" style="margin-top:20px">
  <div class="rec-card">
    <div class="rec-card__head">
      <span class="rec-card__title">Actions rapides</span>
    </div>
    <div class="rec-card__body" style="display:grid;grid-template-columns:1fr 1fr;gap:10px">
      <a href="{{ route('recruteur.offres.create') }}" class="rec-btn rec-btn--primary" style="justify-content:center">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Nouvelle offre
      </a>
      <a href="{{ route('recruteur.candidatures') }}" class="rec-btn rec-btn--outline" style="justify-content:center">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
        Candidatures
      </a>
      <a href="{{ route('recruteur.cvtheque') }}" class="rec-btn rec-btn--outline" style="justify-content:center">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg>
        CVthèque
      </a>
      <a href="{{ route('recruteur.statistiques') }}" class="rec-btn rec-btn--outline" style="justify-content:center">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
        Statistiques
      </a>
    </div>
  </div>

  @if($stats['nouvelles_candid'] > 0)
  <div class="rec-card" style="border-color:rgba(245,200,66,0.5);background:linear-gradient(135deg,#fffdf0,#fffbeb)">
    <div class="rec-card__body" style="display:flex;align-items:center;gap:16px">
      <div style="width:50px;height:50px;border-radius:12px;background:rgba(245,200,66,0.2);display:flex;align-items:center;justify-content:center;flex-shrink:0">
        <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#c79a00" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
      </div>
      <div>
        <p style="font-size:1.5rem;font-weight:800;color:#042C53;margin:0;line-height:1">{{ $stats['nouvelles_candid'] }}</p>
        <p style="font-size:13px;color:#6b7a8d;margin:3px 0 10px">nouvelle{{ $stats['nouvelles_candid'] > 1 ? 's' : '' }} candidature{{ $stats['nouvelles_candid'] > 1 ? 's' : '' }} non lue{{ $stats['nouvelles_candid'] > 1 ? 's' : '' }}</p>
        <a href="{{ route('recruteur.candidatures') }}" class="rec-btn rec-btn--yellow rec-btn--sm">Consulter</a>
      </div>
    </div>
  </div>
  @endif
</div>
@endsection
