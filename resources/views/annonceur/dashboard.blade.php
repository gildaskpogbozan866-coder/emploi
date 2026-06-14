@extends('layouts.annonceur')
@section('title', 'Tableau de bord — Espace Annonceur')

@section('content')
<div class="cand-page-header">
  <div class="cand-page-header__left">
    <h1 class="cand-page-header__title">Bonjour, {{ auth()->user()->prenom }}</h1>
    <p class="cand-page-header__sub">Gérez vos annonces publicitaires sur Emploi Bouge Bénin.</p>
  </div>
  <div class="cand-page-header__actions">
    <a href="{{ route('annonceur.publicites') }}" class="cand-btn cand-btn--yellow">
      <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Nouvelle annonce
    </a>
  </div>
</div>

{{-- Stats --}}
<div class="cand-stats">
  <div class="cand-stat">
    <div class="cand-stat__icon cand-stat__icon--blue">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="13" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
    </div>
    <div>
      <div class="cand-stat__val">{{ $stats['total'] }}</div>
      <div class="cand-stat__label">Total annonces</div>
    </div>
  </div>

  <div class="cand-stat">
    <div class="cand-stat__icon cand-stat__icon--yellow">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    </div>
    <div>
      <div class="cand-stat__val" style="color:#b8860b">{{ $stats['en_attente'] }}</div>
      <div class="cand-stat__label">En attente</div>
    </div>
  </div>

  <div class="cand-stat">
    <div class="cand-stat__icon cand-stat__icon--green">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
    </div>
    <div>
      <div class="cand-stat__val" style="color:#38A169">{{ $stats['approuve'] }}</div>
      <div class="cand-stat__label">Approuvées</div>
    </div>
  </div>

  <div class="cand-stat">
    <div class="cand-stat__icon" style="background:rgba(220,38,38,.1);color:#dc2626">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
    </div>
    <div>
      <div class="cand-stat__val" style="color:#dc2626">{{ $stats['rejete'] }}</div>
      <div class="cand-stat__label">Rejetées</div>
    </div>
  </div>
</div>

{{-- Dernières annonces --}}
<div class="cand-card">
  <div class="cand-card__head">
    <h2 class="cand-card__title">
      <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="13" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
      Dernières annonces
    </h2>
    <a href="{{ route('annonceur.publicites') }}" class="cand-btn cand-btn--outline cand-btn--sm">
      Voir tout
      <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
    </a>
  </div>

  @if($dernieres->isEmpty())
    <div class="cand-empty">
      <div class="cand-empty__icon">
        <svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="13" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
      </div>
      <p class="cand-empty__title">Aucune annonce soumise</p>
      <p class="cand-empty__text">Soumettez votre première annonce pour la faire apparaître sur la plateforme.</p>
      <a href="{{ route('annonceur.publicites') }}" class="cand-btn cand-btn--yellow">Soumettre une annonce</a>
    </div>
  @else
    <div class="cand-table-wrap">
      <table class="cand-table">
        <thead>
          <tr>
            <th>Annonce</th>
            <th>Statut</th>
            <th>Soumise le</th>
          </tr>
        </thead>
        <tbody>
          @foreach($dernieres as $pub)
          <tr>
            <td>
              <div style="display:flex;align-items:center;gap:12px">
                <img src="{{ asset('storage/' . $pub->image) }}"
                     alt="{{ $pub->titre }}"
                     style="width:48px;height:36px;object-fit:cover;border-radius:6px;flex-shrink:0;border:1px solid #e2e8f0">
                <div>
                  <div style="font-weight:600;color:#042C53;font-size:13.5px">{{ $pub->titre }}</div>
                  @if($pub->lien)
                    <div style="font-size:12px;color:#94a3b8;max-width:220px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">{{ $pub->lien }}</div>
                  @endif
                </div>
              </div>
            </td>
            <td>
              <span class="cand-badge cand-badge--{{ $pub->statut_badge }}">{{ $pub->statut_label }}</span>
            </td>
            <td style="font-size:13px;color:#6b7a8d">{{ $pub->created_at->format('d/m/Y') }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  @endif
</div>
@endsection
