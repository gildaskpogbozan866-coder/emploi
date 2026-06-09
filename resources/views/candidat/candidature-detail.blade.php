@extends('layouts.candidat')
@section('title', 'Détail candidature')

@section('sidebar')
@include('candidat._sidebar')
@endsection

@section('content')
<div class="cand-page-header">
  <div class="cand-page-header__left">
    <a href="{{ route('candidat.candidatures') }}" style="color:#185FA5;text-decoration:none;font-size:13px"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg> Mes candidatures</a>
    <h1 class="cand-page-header__title" style="margin-top:8px">{{ $candidature->offre->titre }}</h1>
    <p class="cand-page-header__sub">{{ $candidature->offre->entreprise }} · {{ $candidature->offre->localisation }}</p>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 280px;gap:20px;align-items:start">
  <div>
    <div class="cand-card" style="margin-bottom:16px">
      <div class="cand-card__head">
        <h2 class="cand-card__title">Votre candidature</h2>
        <span class="cand-badge cand-badge--{{ match($candidature->statut) {
          'retenue'   => 'green',
          'refusee'   => 'red',
          'entretien' => 'green',
          'vue'       => 'blue',
          default     => 'gray'
        } }}">{{ ucfirst(str_replace('_',' ',$candidature->statut)) }}</span>
      </div>
      <div class="cand-card__body">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:16px">
          <div><p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Type de contrat</p><p style="font-weight:600;color:#042C53;margin:0">{{ $candidature->offre->type }}</p></div>
          <div><p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Date de candidature</p><p style="font-weight:600;color:#042C53;margin:0">{{ $candidature->created_at->format('d/m/Y') }}</p></div>
        </div>

        @if($candidature->message_motivation)
          <div>
            <p style="font-size:12px;font-weight:700;text-transform:uppercase;color:#94a3b8;margin:0 0 8px">Votre message de motivation</p>
            <p style="font-size:14px;color:#374151;line-height:1.65;margin:0">{{ $candidature->message_motivation }}</p>
          </div>
        @endif

        @if($candidature->note_recruteur)
          <div style="margin-top:16px;padding:14px 18px;background:{{ $candidature->statut === 'retenue' ? '#f0fdf4' : ($candidature->statut === 'refusee' ? '#fef2f2' : '#f8fafc') }};border-radius:10px;border:1px solid {{ $candidature->statut === 'retenue' ? '#bbf7d0' : ($candidature->statut === 'refusee' ? '#fecaca' : '#e2e8f0') }}">
            <p style="font-size:12px;font-weight:700;text-transform:uppercase;color:#94a3b8;margin:0 0 6px">Note du recruteur</p>
            <p style="font-size:13.5px;color:#374151;margin:0">{{ $candidature->note_recruteur }}</p>
          </div>
        @endif
      </div>
    </div>
  </div>

  <div>
    <div class="cand-card">
      <div class="cand-card__head">
        <h2 class="cand-card__title">L'offre</h2>
      </div>
      <div class="cand-card__body">
        <p style="font-weight:700;color:#042C53;margin:0 0 4px">{{ $candidature->offre->titre }}</p>
        <p style="font-size:13px;color:#64748b;margin:0 0 12px">{{ $candidature->offre->entreprise }}</p>
        <p style="font-size:13px;color:#94a3b8;margin:0 0 16px">
          <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="vertical-align:middle;margin-right:3px"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
          {{ $candidature->offre->localisation }}
        </p>
        <a href="{{ route('offre.detail', $candidature->offre) }}" target="_blank" class="cand-btn cand-btn--outline" style="width:100%;justify-content:center">
          Voir l'offre complète
        </a>
      </div>
    </div>
  </div>
</div>
@endsection
