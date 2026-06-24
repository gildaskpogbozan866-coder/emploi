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
        ['name' => 'tri',    'label' => 'Trier par',        'options' => ['recent' => 'Plus récentes', 'date_limite' => 'Date limite', 'salaire_asc' => 'Salaire croissant', 'salaire_desc' => 'Salaire décroissant', 'candidatures_desc' => 'Plus de candidatures']],
      ],
    ])
    <div style="display:flex;gap:8px;padding:10px 0 12px;align-items:center;flex-wrap:wrap">
      <form method="GET" style="display:flex;gap:8px;align-items:center;flex-wrap:wrap">
        @foreach(request()->except(['salaire_min','salaire_max']) as $k => $v)
          <input type="hidden" name="{{ $k }}" value="{{ $v }}">
        @endforeach
        <span style="font-size:12px;color:#6b7a8d;font-weight:600">Salaire (FCFA) :</span>
        <input type="number" name="salaire_min" value="{{ request('salaire_min') }}" placeholder="Min" min="0" step="10000"
               style="width:110px;padding:6px 10px;border:1.5px solid #e2e8f0;border-radius:7px;font-size:13px">
        <span style="color:#94a3b8">–</span>
        <input type="number" name="salaire_max" value="{{ request('salaire_max') }}" placeholder="Max" min="0" step="10000"
               style="width:110px;padding:6px 10px;border:1.5px solid #e2e8f0;border-radius:7px;font-size:13px">
        <button type="submit" class="rec-btn rec-btn--outline rec-btn--sm">Filtrer</button>
        @if(request()->hasAny(['salaire_min','salaire_max']))
          <a href="{{ route('recruteur.offres', request()->except(['salaire_min','salaire_max'])) }}" class="rec-btn rec-btn--outline rec-btn--sm">✕</a>
        @endif
      </form>
    </div>
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
              <div style="font-size:11.5px;color:#94a3b8;margin-top:2px">
                {{ $offre->localisation }}
                @if($offre->vues)
                  · <span title="Vues">{{ number_format($offre->vues, 0, ',', ' ') }} vue(s)</span>
                @endif
              </div>
            @endif
          </td>
          <td><span class="rec-badge rec-badge--blue">{{ $offre?->type?->libelle }}</span></td>
          <td>
            <strong>{{ $offre->candidatures_count }}</strong>
            @if($offre->candidatures_nouvelles_count > 0)
              <span style="display:inline-block;margin-left:5px;font-size:11px;font-weight:700;color:#fff;background:#dc2626;border-radius:10px;padding:1px 7px;white-space:nowrap">
                {{ $offre->candidatures_nouvelles_count }} new
              </span>
            @endif
          </td>
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
          <td style="color:#94a3b8;font-size:12.5px">{{ $offre->date_limite?->format('d/m/Y') ?? '-' }}</td>
          <td>
            <div class="actions" style="display:flex;gap:6px;align-items:center">

              {{-- Action principale --}}
              <a href="{{ route('recruteur.offres.edit', $offre) }}" class="rec-btn rec-btn--outline rec-btn--sm">Modifier</a>

              {{-- Mise en avant (visible si active) --}}
              @if($offre->statut === 'active')
                @if($offre->premium)
                  <form method="POST" action="{{ route('recruteur.offres.mettre-en-avant', $offre) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="rec-btn rec-btn--outline rec-btn--sm"
                            style="color:#92400e;border-color:#fde68a;background:#fffbeb"
                            title="Retirer de la mise en avant">★</button>
                  </form>
                @elseif($miseEnAvantInfo['disponible'])
                  <form method="POST" action="{{ route('recruteur.offres.mettre-en-avant', $offre) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="rec-btn rec-btn--outline rec-btn--sm"
                            style="color:#92400e;border-color:#fde68a"
                            title="Mettre en avant">★</button>
                  </form>
                @endif
              @endif

              {{-- Menu ••• --}}
              <div style="position:relative">
                <button onclick="toggleDropdown('dd-{{ $offre->id }}')"
                        class="rec-btn rec-btn--outline rec-btn--sm"
                        style="padding:5px 9px;font-size:15px;line-height:1">•••</button>
                <div id="dd-{{ $offre->id }}"
                     style="display:none;position:absolute;right:0;top:calc(100% + 4px);background:#fff;border:1.5px solid #e2e8f0;border-radius:10px;box-shadow:0 4px 16px rgba(0,0,0,.08);min-width:160px;z-index:50;overflow:hidden">

                  <a href="{{ route('recruteur.offres.stats', $offre) }}" class="dd-item">
                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><line x1="18" y1="20" x2="18" y2="10"/><line x1="12" y1="20" x2="12" y2="4"/><line x1="6" y1="20" x2="6" y2="14"/></svg>
                    Statistiques
                  </a>

                  <form method="POST" action="{{ route('recruteur.offres.dupliquer', $offre) }}">
                    @csrf
                    <button type="submit" class="dd-item">
                      <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"/><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"/></svg>
                      Dupliquer
                    </button>
                  </form>

                  @if($offre->statut === 'active')
                  <form method="POST" action="{{ route('recruteur.offres.cloturer', $offre) }}"
                        data-confirm="Clôturer cette offre ? Elle ne sera plus visible par les candidats." data-confirm-btn="Clôturer">
                    @csrf @method('PATCH')
                    <button type="submit" class="dd-item dd-item--warn">
                      <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="4.93" y1="4.93" x2="19.07" y2="19.07"/></svg>
                      Clôturer
                    </button>
                  </form>
                  @endif

                  <div style="border-top:1px solid #f1f5f9;margin:4px 0"></div>

                  <form method="POST" action="{{ route('recruteur.offres.destroy', $offre) }}"
                        data-confirm="Supprimer cette offre définitivement ?" data-confirm-btn="Supprimer">
                    @csrf @method('DELETE')
                    <button type="submit" class="dd-item dd-item--danger">
                      <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/></svg>
                      Supprimer
                    </button>
                  </form>

                </div>
              </div>

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

<style>
.dd-item { display:flex;align-items:center;gap:8px;width:100%;padding:9px 14px;font-size:13px;color:#374151;background:none;border:none;cursor:pointer;text-align:left;text-decoration:none }
.dd-item:hover { background:#f8fafc }
.dd-item--danger { color:#dc2626 }
.dd-item--warn   { color:#d97706 }
</style>
<script>
function toggleDropdown(id) {
    document.querySelectorAll('[id^="dd-"]').forEach(el => {
        if (el.id !== id) el.style.display = 'none';
    });
    var el = document.getElementById(id);
    el.style.display = el.style.display === 'none' ? 'block' : 'none';
}
document.addEventListener('click', function(e) {
    if (!e.target.closest('[id^="dd-"]') && !e.target.closest('button[onclick^="toggleDropdown"]')) {
        document.querySelectorAll('[id^="dd-"]').forEach(el => el.style.display = 'none');
    }
});
</script>
@endsection
