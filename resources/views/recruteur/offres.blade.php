@extends('layouts.recruteur')
@section('title', 'Mes offres d\'emploi')

@section('sidebar')
@include('recruteur._sidebar')
@endsection

@section('content')
<div class="rec-topbar">
  <div class="rec-topbar__left">
    <h1>Mes offres d'emploi</h1>
    <p>Gérez toutes vos annonces publiées sur la plateforme</p>
  </div>
  <div class="rec-topbar__actions">
    <a href="{{ route('recruteur.offres.create') }}" class="rec-btn rec-btn--yellow">
      <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Publier une offre
    </a>
  </div>
</div>

<div class="rec-card">
  <div class="rec-table-wrap">
    <table class="rec-table">
      <thead>
        <tr><th>Titre du poste</th><th>Type</th><th>Candidatures</th><th>Statut</th><th>Date limite</th><th>Actions</th></tr>
      </thead>
      <tbody>
        @forelse($offres as $offre)
        <tr>
          <td>
            <a href="{{ route('offre.detail', $offre) }}" style="font-weight:600;color:#042C53;text-decoration:none">{{ $offre->titre }}</a>
            @if($offre->localisation)
              <div style="font-size:11.5px;color:#94a3b8;margin-top:2px">{{ $offre->localisation }}</div>
            @endif
          </td>
          <td><span class="rec-badge rec-badge--blue">{{ $offre->type }}</span></td>
          <td>
            <strong>{{ $offre->candidatures_count }}</strong>
          </td>
          <td>
            <span class="rec-badge rec-badge--{{ match($offre->statut) {
              'active'     => 'green',
              'en_attente' => 'yellow',
              'expiree'    => 'gray',
              'suspendue'  => 'red',
              'brouillon'  => 'gray',
              default      => 'gray'
            } }}">
              {{ ucfirst(str_replace('_',' ',$offre->statut)) }}
            </span>
          </td>
          <td style="color:#94a3b8;font-size:12.5px">{{ $offre->date_limite?->format('d/m/Y') ?? '—' }}</td>
          <td>
            <div class="actions">
              <a href="{{ route('recruteur.offres.edit', $offre) }}" class="rec-btn rec-btn--outline rec-btn--sm">Modifier</a>
              <form method="POST" action="{{ route('recruteur.offres.destroy', $offre) }}" onsubmit="return confirm('Supprimer cette offre définitivement ?')">
                @csrf @method('DELETE')
                <button type="submit" class="rec-btn rec-btn--danger rec-btn--sm">Supprimer</button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6">
            <div class="rec-empty">
              <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg>
              <h3>Aucune offre publiée</h3>
              <p>Créez votre première offre d'emploi et commencez à recevoir des candidatures.</p>
              <a href="{{ route('recruteur.offres.create') }}" class="rec-btn rec-btn--yellow">Publier une offre</a>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($offres->hasPages())
    <div style="padding:16px 22px">{{ $offres->links() }}</div>
  @endif
</div>
@endsection
