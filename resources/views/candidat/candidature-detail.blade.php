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
            <p style="font-size:12px;font-weight:700;text-transform:uppercase;color:#64748b;margin:0 0 6px">Note du recruteur</p>
            <p style="font-size:13.5px;color:#374151;margin:0">{{ $candidature->note_recruteur }}</p>
          </div>
        @endif

        {{-- CV joint à la candidature --}}
        <div style="margin-top:20px;padding-top:20px;border-top:1px solid #e2e8f0">
          <p style="font-size:12px;font-weight:700;text-transform:uppercase;color:#64748b;margin:0 0 10px">CV joint</p>
          @if($candidature->cv)
            <div style="display:flex;align-items:center;gap:12px;padding:12px 16px;background:#f0f7ff;border:1.5px solid #bfdbfe;border-radius:10px">
              <div style="width:36px;height:36px;border-radius:8px;background:#dbeafe;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
              </div>
              <div style="flex:1;min-width:0">
                <p style="font-weight:700;color:#042C53;margin:0 0 2px;font-size:14px">{{ $candidature->cv->titre_poste }}</p>
                <p style="font-size:12px;color:#64748b;margin:0">{{ $candidature->cv->pays }}{{ $candidature->cv->ville ? ' · '.$candidature->cv->ville : '' }}</p>
              </div>
              <span style="font-size:11px;background:#dbeafe;color:#1e40af;padding:2px 10px;border-radius:20px;font-weight:600;flex-shrink:0">Profil</span>
            </div>
          @elseif($candidature->cv_path)
            <div style="display:flex;align-items:center;gap:12px;padding:12px 16px;background:#f8fafc;border:1.5px solid #e2e8f0;border-radius:10px">
              <div style="width:36px;height:36px;border-radius:8px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;flex-shrink:0">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#64748b" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
              </div>
              <div style="flex:1;min-width:0">
                <p style="font-weight:600;color:#042C53;margin:0 0 2px;font-size:14px">Fichier CV joint</p>
                <p style="font-size:12px;color:#64748b;margin:0">{{ basename($candidature->cv_path) }}</p>
              </div>
              <a href="{{ asset('storage/' . $candidature->cv_path) }}" target="_blank"
                 style="font-size:12.5px;font-weight:600;color:#185FA5;text-decoration:none;white-space:nowrap;flex-shrink:0">
                Télécharger
              </a>
            </div>
          @else
            <p style="font-size:13.5px;color:#64748b;font-style:italic;margin:0">Aucun CV joint à cette candidature.</p>
          @endif
        </div>
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
