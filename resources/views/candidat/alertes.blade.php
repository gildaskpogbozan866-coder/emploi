@extends('layouts.candidat')
@section('title', 'Mes alertes emploi')

@section('sidebar')
@include('candidat._sidebar')
@endsection

@section('content')
<div class="cand-page-header">
  <div class="cand-page-header__left">
    <h1 class="cand-page-header__title">Mes alertes emploi</h1>
    <p class="cand-page-header__sub">Recevez les offres correspondant à votre profil directement par email</p>
  </div>
</div>

<div class="alertes-grid" style="display:grid;grid-template-columns:1fr 380px;gap:22px;align-items:start">

  {{-- Liste des alertes --}}
  <div>
    @forelse($alertes as $alerte)
    <div class="cand-card" style="margin-bottom:12px">
      <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:14px;flex-wrap:wrap">
        <div style="flex:1;min-width:0">
          <p style="font-weight:700;color:#042C53;margin:0 0 8px;font-size:15px">{{ $alerte->nom }}</p>
          <div style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:10px">
            @if($alerte->mots_cles)     <span class="cand-badge cand-badge--gray">{{ $alerte->mots_cles }}</span> @endif
            @if($alerte->localisation)  <span class="cand-badge cand-badge--gray">{{ $alerte->localisation }}</span> @endif
            @if($alerte->type_contrat)  <span class="cand-badge cand-badge--blue">{{ $alerte->type_contrat }}</span> @endif
            @if($alerte->secteur)       <span class="cand-badge cand-badge--gray">{{ $alerte->secteur }}</span> @endif
          </div>
          <p style="font-size:12px;color:#94a3b8;margin:0">
            Fréquence : <strong>{{ ucfirst($alerte->frequence) }}</strong> ·
            Créée le {{ $alerte->created_at->format('d/m/Y') }}
          </p>
        </div>
        <div style="display:flex;gap:8px;align-items:center;flex-shrink:0">
          <span class="cand-badge cand-badge--{{ $alerte->active ? 'green' : 'gray' }}">
            {{ $alerte->active ? 'Active' : 'Désactivée' }}
          </span>
          <form method="POST" action="{{ route('candidat.alertes.destroy', $alerte) }}" data-confirm="Supprimer cette alerte ?" data-confirm-btn="Supprimer">
            @csrf @method('DELETE')
            <button type="submit" class="cand-btn cand-btn--danger cand-btn--sm">Supprimer</button>
          </form>
        </div>
      </div>
    </div>
    @empty
      <div class="cand-card">
        <div class="cand-empty">
          <div class="cand-empty__icon">
            <svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
          </div>
          <p class="cand-empty__title">Aucune alerte configurée</p>
          <p class="cand-empty__text">Créez une alerte pour être notifié dès qu'une offre correspondant à votre profil est publiée.</p>
        </div>
      </div>
    @endforelse
  </div>

  {{-- Formulaire nouvelle alerte --}}
  <div class="cand-card" style="position:sticky;top:80px">
    <div class="cand-card__head">
      <h2 class="cand-card__title">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Créer une alerte
      </h2>
    </div>
    <form method="POST" action="{{ route('candidat.alertes.store') }}">
      @csrf
      <div class="cand-form-group">
        <label class="cand-form-label">Nom de l'alerte <span class="req">*</span></label>
        <input class="cand-form-input" type="text" name="nom" value="{{ old('nom') }}" placeholder="Ex : Dev Web Cotonou" required>
      </div>
      <div class="cand-form-group">
        <label class="cand-form-label">Mots-clés</label>
        <input class="cand-form-input" type="text" name="mots_cles" value="{{ old('mots_cles') }}" placeholder="Développeur, Marketing…">
      </div>
      <div class="cand-form-group">
        <label class="cand-form-label">Localisation</label>
        <input class="cand-form-input" type="text" name="localisation" value="{{ old('localisation') }}" placeholder="Cotonou, Bénin…">
      </div>
      <div class="cand-form-group">
        <label class="cand-form-label">Type de contrat</label>
        <select class="cand-form-select" name="type_contrat">
          <option value="">Tous les types</option>
          @foreach(['CDI','CDD','Stage','Bourse','Freelance'] as $t)
            <option value="{{ $t }}" {{ old('type_contrat') === $t ? 'selected' : '' }}>{{ $t }}</option>
          @endforeach
        </select>
      </div>
      <div class="cand-form-group">
        <label class="cand-form-label">Fréquence de notification <span class="req">*</span></label>
        <select class="cand-form-select" name="frequence" required>
          <option value="immediat" {{ old('frequence') === 'immediat' ? 'selected' : '' }}>Immédiat</option>
          <option value="quotidien" {{ old('frequence', 'quotidien') === 'quotidien' ? 'selected' : '' }}>Quotidien</option>
          <option value="hebdomadaire" {{ old('frequence') === 'hebdomadaire' ? 'selected' : '' }}>Hebdomadaire</option>
        </select>
      </div>
      <div class="cand-form-actions">
        <button type="submit" class="cand-btn cand-btn--primary" style="width:100%">Créer l'alerte</button>
      </div>
    </form>
  </div>

</div>

<style>
@media (max-width: 900px) {
  .alertes-grid { grid-template-columns: 1fr !important; }
}
</style>
@endsection
