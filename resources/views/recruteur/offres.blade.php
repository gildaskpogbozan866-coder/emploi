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
  <div class="rec-topbar__actions" style="display:flex;align-items:center;gap:14px">
    {{-- Compteur mise en avant --}}
    @if($miseEnAvantInfo['limite'] !== null)
      <div style="font-size:12.5px;color:#64748b;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:6px 12px;white-space:nowrap">
        ★ En avant : <strong style="color:{{ $miseEnAvantInfo['utilisees'] >= $miseEnAvantInfo['limite'] ? '#dc2626' : '#185FA5' }}">
          {{ $miseEnAvantInfo['utilisees'] }}/{{ $miseEnAvantInfo['limite'] }}
        </strong>
      </div>
    @elseif($miseEnAvantInfo['limite'] === null && $miseEnAvantInfo['disponible'])
      <div style="font-size:12.5px;color:#16a34a;background:#f0fdf4;border:1px solid #bbf7d0;border-radius:8px;padding:6px 12px;white-space:nowrap">
        ★ Mise en avant illimitée
      </div>
    @endif
    <a href="{{ route('recruteur.offres.create') }}" class="rec-btn rec-btn--yellow">
      <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Publier une offre
    </a>
  </div>
</div>

<div class="rec-card">
  <div style="padding:16px 22px 0">
    @include('partials._search-bar', [
      'route'       => 'recruteur.offres',
      'placeholder' => 'Rechercher par titre…',
      'filters'     => [
        ['name' => 'statut', 'label' => 'Tous les statuts', 'options' => ['active' => 'Active', 'clos' => 'Clôturée', 'en_attente' => 'En attente', 'expiree' => 'Expirée', 'suspendue' => 'Suspendue']],
        ['name' => 'type',   'label' => 'Tous les types',   'options' => $typeContrats->pluck('libelle', 'code')->all()],
      ],
    ])
  </div>
  <div class="rec-table-wrap">
    <table class="rec-table">
      <thead>
        <tr>
          <th>Titre du poste</th>
          <th>Type</th>
          <th>Candidatures</th>
          <th>Statut</th>
          <th>Date limite</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($offres as $offre)
        <tr style="{{ $offre->premium ? 'background:#fffbeb' : '' }}">
          <td>
            <div style="display:flex;align-items:center;gap:8px;flex-wrap:wrap">
              <a href="{{ route('offre.detail', $offre) }}" style="font-weight:600;color:#042C53;text-decoration:none">{{ $offre->titre }}</a>
              @if($offre->premium)
                <span style="font-size:11px;font-weight:700;color:#92400e;background:#fef3c7;border:1px solid #fde68a;border-radius:4px;padding:1px 7px;white-space:nowrap">★ En avant</span>
              @endif
            </div>
            @if($offre->localisation)
              <div style="font-size:11.5px;color:#94a3b8;margin-top:2px">{{ $offre->localisation }}</div>
            @endif
          </td>
          <td><span class="rec-badge rec-badge--blue">{{ $offre->type }}</span></td>
          <td><strong>{{ $offre->candidatures_count }}</strong></td>
          <td>
            <span class="rec-badge rec-badge--{{ match($offre->statut) {
              'active'     => 'green',
              'clos'       => 'gray',
              'en_attente' => 'yellow',
              'expiree'    => 'gray',
              'suspendue'  => 'red',
              default      => 'gray'
            } }}">
              {{ ucfirst(str_replace('_',' ',$offre->statut)) }}
            </span>
          </td>
          <td style="color:#94a3b8;font-size:12.5px">{{ $offre->date_limite?->format('d/m/Y') ?? '—' }}</td>
          <td>
            <div class="actions">

              {{-- Mise en avant --}}
              @if($offre->statut === 'active')
                @if($offre->premium)
                  <form method="POST" action="{{ route('recruteur.offres.mettre-en-avant', $offre) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="rec-btn rec-btn--outline rec-btn--sm"
                            style="color:#92400e;border-color:#fde68a;background:#fffbeb"
                            title="Retirer de la mise en avant">★ Retirer</button>
                  </form>
                @elseif($miseEnAvantInfo['disponible'])
                  <form method="POST" action="{{ route('recruteur.offres.mettre-en-avant', $offre) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="rec-btn rec-btn--outline rec-btn--sm"
                            style="color:#92400e;border-color:#fde68a"
                            title="Mettre en avant">★ Mettre en avant</button>
                  </form>
                @else
                  <span style="font-size:12px;color:#cbd5e1;padding:0 4px" title="Limite de mise en avant atteinte">★ —</span>
                @endif
              @endif

              <a href="{{ route('recruteur.offres.stats', $offre) }}" class="rec-btn rec-btn--outline rec-btn--sm" title="Statistiques">
                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
              </a>

              <a href="{{ route('recruteur.offres.edit', $offre) }}" class="rec-btn rec-btn--outline rec-btn--sm">Modifier</a>

              @if($offre->statut === 'active')
              <form method="POST" action="{{ route('recruteur.offres.cloturer', $offre) }}" data-confirm="Clôturer cette offre ? Elle ne sera plus visible par les candidats." data-confirm-btn="Clôturer">
                @csrf @method('PATCH')
                <button type="submit" class="rec-btn rec-btn--outline rec-btn--sm" style="color:#d97706;border-color:#fde68a">Clôturer</button>
              </form>
              @endif

              <form method="POST" action="{{ route('recruteur.offres.dupliquer', $offre) }}">
                @csrf
                <button type="submit" class="rec-btn rec-btn--outline rec-btn--sm" title="Dupliquer">
                  <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                </button>
              </form>

              <form method="POST" action="{{ route('recruteur.offres.destroy', $offre) }}" data-confirm="Supprimer cette offre définitivement ?" data-confirm-btn="Supprimer">
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
