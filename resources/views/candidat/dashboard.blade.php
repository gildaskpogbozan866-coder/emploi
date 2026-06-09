@extends('layouts.candidat')
@section('title', 'Mon espace Candidat')

@section('sidebar')
@include('candidat._sidebar')
@endsection

@section('content')
<div class="cand-page-header">
  <div class="cand-page-header__left">
    <h1 class="cand-page-header__title">Bonjour, {{ auth()->user()->prenom }}</h1>
    <p class="cand-page-header__sub">Voici un résumé de votre activité sur la plateforme.</p>
  </div>
  <div class="cand-page-header__actions">
    <a href="{{ route('offre.list') }}" class="cand-btn cand-btn--primary">
      <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      Chercher des offres
    </a>
  </div>
</div>

{{-- Stats --}}
<div class="cand-stats">
  <div class="cand-stat">
    <div class="cand-stat__icon cand-stat__icon--blue">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.41 2 2 0 0 1 3.6 1.21h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.68 2.81a2 2 0 0 1-.45 2.11L7.91 8.76A16 16 0 0 0 12 12a16 16 0 0 0 3.24 1.91l1.91-1.91a2 2 0 0 1 2.11-.45c.91.32 1.85.55 2.81.68A2 2 0 0 1 24 14.21v2.71Z"/></svg>
    </div>
    <div>
      <div class="cand-stat__val">{{ $stats['candidatures'] }}</div>
      <div class="cand-stat__label">Candidatures envoyées</div>
    </div>
  </div>

  <div class="cand-stat">
    <div class="cand-stat__icon cand-stat__icon--dark">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
    </div>
    <div>
      <div class="cand-stat__val">{{ $stats['cvs'] }}</div>
      <div class="cand-stat__label">CVs déposés</div>
    </div>
  </div>

  <div class="cand-stat">
    <div class="cand-stat__icon cand-stat__icon--yellow">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
    </div>
    <div>
      <div class="cand-stat__val">{{ $stats['offres_vues'] }}</div>
      <div class="cand-stat__label">Candidatures vues</div>
    </div>
  </div>

  <div class="cand-stat">
    <div class="cand-stat__icon cand-stat__icon--green">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
    </div>
    <div>
      <div class="cand-stat__val" style="color:#38A169">{{ $stats['retenues'] }}</div>
      <div class="cand-stat__label">Retenues</div>
    </div>
  </div>
</div>

{{-- Actions rapides --}}
<div class="cand-card">
  <div class="cand-card__head">
    <h2 class="cand-card__title">Actions rapides</h2>
  </div>
  <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:12px">
    <a href="{{ route('offre.list') }}" style="display:flex;flex-direction:column;align-items:center;gap:10px;background:#f8fafc;border:1.5px solid #e2e6ed;border-radius:10px;padding:18px 14px;text-decoration:none;color:#042C53;font-size:13px;font-weight:600;transition:border-color .2s,box-shadow .2s" onmouseover="this.style.borderColor='#378ADD';this.style.boxShadow='0 2px 12px rgba(55,138,221,.12)'" onmouseout="this.style.borderColor='#e2e6ed';this.style.boxShadow='none'">
      <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#378ADD" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
      Chercher des offres
    </a>
    <a href="{{ route('cv.public.depot') }}" style="display:flex;flex-direction:column;align-items:center;gap:10px;background:#f8fafc;border:1.5px solid #e2e6ed;border-radius:10px;padding:18px 14px;text-decoration:none;color:#042C53;font-size:13px;font-weight:600;transition:border-color .2s,box-shadow .2s" onmouseover="this.style.borderColor='#378ADD';this.style.boxShadow='0 2px 12px rgba(55,138,221,.12)'" onmouseout="this.style.borderColor='#e2e6ed';this.style.boxShadow='none'">
      <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#378ADD" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" y1="3" x2="12" y2="15"/></svg>
      Déposer un CV
    </a>
    <a href="{{ route('candidat.alertes') }}" style="display:flex;flex-direction:column;align-items:center;gap:10px;background:#f8fafc;border:1.5px solid #e2e6ed;border-radius:10px;padding:18px 14px;text-decoration:none;color:#042C53;font-size:13px;font-weight:600;transition:border-color .2s,box-shadow .2s" onmouseover="this.style.borderColor='#378ADD';this.style.boxShadow='0 2px 12px rgba(55,138,221,.12)'" onmouseout="this.style.borderColor='#e2e6ed';this.style.boxShadow='none'">
      <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#378ADD" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
      Créer une alerte emploi
    </a>
    <a href="{{ route('candidat.profil') }}" style="display:flex;flex-direction:column;align-items:center;gap:10px;background:#f8fafc;border:1.5px solid #e2e6ed;border-radius:10px;padding:18px 14px;text-decoration:none;color:#042C53;font-size:13px;font-weight:600;transition:border-color .2s,box-shadow .2s" onmouseover="this.style.borderColor='#378ADD';this.style.boxShadow='0 2px 12px rgba(55,138,221,.12)'" onmouseout="this.style.borderColor='#e2e6ed';this.style.boxShadow='none'">
      <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#378ADD" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
      Compléter mon profil
    </a>
  </div>
</div>

{{-- Dernières candidatures --}}
<div class="cand-card">
  <div class="cand-card__head">
    <h2 class="cand-card__title">
      <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
      Dernières candidatures
    </h2>
    <a href="{{ route('candidat.candidatures') }}" class="cand-btn cand-btn--outline cand-btn--sm">Voir tout <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></a>
  </div>

  @if($dernieres_candidatures->isEmpty())
    <div class="cand-empty">
      <div class="cand-empty__icon">
        <svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
      </div>
      <p class="cand-empty__title">Aucune candidature</p>
      <p class="cand-empty__text">Vous n'avez pas encore postulé à une offre. Parcourez les annonces disponibles.</p>
      <a href="{{ route('offre.list') }}" class="cand-btn cand-btn--primary">Parcourir les offres</a>
    </div>
  @else
    <div class="cand-table-wrap">
      <table class="cand-table">
        <thead>
          <tr><th>Poste</th><th>Entreprise</th><th>Statut</th><th>Date</th></tr>
        </thead>
        <tbody>
          @foreach($dernieres_candidatures as $c)
          <tr>
            <td><a href="{{ route('offre.detail', $c->offre) }}" style="color:#185FA5;font-weight:600;text-decoration:none">{{ $c->offre->titre }}</a></td>
            <td style="color:#6b7a8d">{{ $c->offre->entreprise }}</td>
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
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif
</div>
@endsection
