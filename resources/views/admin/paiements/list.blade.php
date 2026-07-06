@extends('layouts.admin')
@section('title', 'Paiements | Administration')

@section('css')
<style>
.pai-filters-grid { padding: 18px 20px; display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 14px; }
.pai-filters-actions { grid-column: span 2; }
@media (max-width: 900px) {
  .pai-filters-grid { grid-template-columns: 1fr 1fr; }
}
@media (max-width: 600px) {
  .pai-filters-grid { grid-template-columns: 1fr; }
  .pai-filters-grid .pai-filters-actions { grid-column: span 1; }
}
</style>
@endsection

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <h1>Paiements</h1>
    <p>{{ $paiements->total() }} paiement{{ $paiements->total() > 1 ? 's' : '' }} enregistré{{ $paiements->total() > 1 ? 's' : '' }}</p>
  </div>
</div>

@if($stats['count_credits_attente'] > 0)
<div style="background:#fef3c7;border:1.5px solid #fde68a;border-radius:12px;padding:14px 20px;display:flex;align-items:center;gap:12px;margin-bottom:20px">
  <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#d97706" stroke-width="2" style="flex-shrink:0"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
  <p style="margin:0;font-size:13.5px;color:#92400e;font-weight:600">
    {{ $stats['count_credits_attente'] }} achat{{ $stats['count_credits_attente'] > 1 ? 's' : '' }} de crédits CVthèque en attente de confirmation.
    <a href="?categorie=cv_credits&statut=en_attente" style="color:#92400e;text-decoration:underline">Voir</a>
  </p>
</div>
@endif

{{-- Stats --}}
<div class="adm-stats" style="grid-template-columns:repeat(auto-fit,minmax(200px,1fr));margin-bottom:24px">
  <div class="adm-stat">
    <div class="adm-stat__icon adm-stat__icon--green">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
    </div>
    <div>
      <div class="adm-stat__val">{{ number_format($stats['total_confirme'], 0, ',', ' ') }} FCFA</div>
      <div class="adm-stat__label">Revenus confirmés</div>
    </div>
  </div>
  <div class="adm-stat">
    <div class="adm-stat__icon adm-stat__icon--green">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><polyline points="22 7 13.5 15.5 8.5 10.5 2 17"/><polyline points="16 7 22 7 22 13"/></svg>
    </div>
    <div>
      <div class="adm-stat__val">{{ $stats['count_confirme'] }}</div>
      <div class="adm-stat__label">Paiements confirmés</div>
    </div>
  </div>
  <div class="adm-stat">
    <div class="adm-stat__icon adm-stat__icon--yellow">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    </div>
    <div>
      <div class="adm-stat__val">{{ $stats['count_attente'] }}</div>
      <div class="adm-stat__label">En attente</div>
    </div>
  </div>
  <div class="adm-stat">
    <div class="adm-stat__icon" style="background:#fef9c3;color:#854d0e">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
    </div>
    <div>
      <div class="adm-stat__val">{{ number_format($stats['total_attente'], 0, ',', ' ') }} FCFA</div>
      <div class="adm-stat__label">Montant en attente</div>
    </div>
  </div>
</div>

{{-- ── FILTRES ── --}}
@php
  $filtresActifs = collect([
    'q'          => request('q')          ? 'Recherche : "'.request('q').'"'            : null,
    'statut'     => request('statut')     ? 'Statut : '.match(request('statut')) { 'confirme'=>'Confirmé','en_attente'=>'En attente','echec'=>'Échec','rembourse'=>'Remboursé',default=>request('statut')} : null,
    'categorie'  => request('categorie')  ? 'Catégorie : '.match(request('categorie')) { 'abonnement'=>'Abonnements','cv_credits'=>'Crédits CVthèque','service'=>'Services',default=>request('categorie')} : null,
    'methode'    => request('methode')    ? 'Méthode : '.ucfirst(str_replace('_',' ',request('methode'))) : null,
    'date_debut' => request('date_debut') ? 'Depuis : '.request('date_debut')           : null,
    'date_fin'   => request('date_fin')   ? 'Jusqu\'au : '.request('date_fin')          : null,
  ])->filter();
  $hasFilters = $filtresActifs->isNotEmpty();
@endphp

<div class="adm-card" style="margin-bottom:20px;padding:0;overflow:visible">

  {{-- En-tête filtres --}}
  <div style="padding:14px 20px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between;gap:12px">
    <div style="display:flex;align-items:center;gap:8px">
      <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="2.5"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/></svg>
      <span style="font-size:13px;font-weight:700;color:#042C53">Filtres</span>
      @if($hasFilters)
        <span style="background:#eff6ff;color:#185FA5;font-size:11px;font-weight:700;padding:2px 8px;border-radius:99px">{{ $filtresActifs->count() }} actif{{ $filtresActifs->count() > 1 ? 's' : '' }}</span>
      @endif
    </div>
    @if($hasFilters)
      <a href="{{ route('admin.paiements.list') }}" style="font-size:12.5px;color:#94a3b8;font-weight:600;text-decoration:none;display:flex;align-items:center;gap:4px">
        <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        Tout effacer
      </a>
    @endif
  </div>

  {{-- Formulaire --}}
  <form method="GET" id="filterForm">
    <div class="pai-filters-grid">

      {{-- Recherche --}}
      <div>
        <label style="display:block;font-size:11.5px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em;margin-bottom:6px">Recherche</label>
        <div class="adm-search" style="max-width:100%;flex:none">
          <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          <input type="text" name="q" value="{{ request('q') }}" placeholder="Nom, email, référence…">
        </div>
      </div>

      {{-- Statut --}}
      <div>
        <label style="display:block;font-size:11.5px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em;margin-bottom:6px">Statut</label>
        <select name="statut" class="adm-select" style="width:100%">
          <option value="">Tous les statuts</option>
          <option value="en_attente" {{ request('statut') === 'en_attente' ? 'selected' : '' }}>⏳ En attente</option>
          <option value="confirme"   {{ request('statut') === 'confirme'   ? 'selected' : '' }}>✅ Confirmé</option>
          <option value="echec"      {{ request('statut') === 'echec'      ? 'selected' : '' }}>❌ Échec</option>
          <option value="rembourse"  {{ request('statut') === 'rembourse'  ? 'selected' : '' }}>↩ Remboursé</option>
        </select>
      </div>

      {{-- Catégorie --}}
      <div>
        <label style="display:block;font-size:11.5px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em;margin-bottom:6px">Catégorie</label>
        <select name="categorie" class="adm-select" style="width:100%">
          <option value="">Toutes catégories</option>
          <option value="abonnement" {{ request('categorie') === 'abonnement' ? 'selected' : '' }}>Abonnements</option>
          <option value="cv_credits" {{ request('categorie') === 'cv_credits' ? 'selected' : '' }}>Crédits CVthèque</option>
          <option value="service"    {{ request('categorie') === 'service'    ? 'selected' : '' }}>Services</option>
        </select>
      </div>

      {{-- Méthode --}}
      <div>
        <label style="display:block;font-size:11.5px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em;margin-bottom:6px">Méthode</label>
        <select name="methode" class="adm-select" style="width:100%">
          <option value="">Toutes méthodes</option>
          @foreach($methodes as $m)
            <option value="{{ $m }}" {{ request('methode') === $m ? 'selected' : '' }}>
              {{ ucfirst(str_replace('_', ' ', $m)) }}
            </option>
          @endforeach
        </select>
      </div>

      {{-- Date début --}}
      <div>
        <label style="display:block;font-size:11.5px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em;margin-bottom:6px">Date début</label>
        <input type="date" name="date_debut" value="{{ request('date_debut') }}"
               class="adm-select" style="width:100%">
      </div>

      {{-- Date fin --}}
      <div>
        <label style="display:block;font-size:11.5px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em;margin-bottom:6px">Date fin</label>
        <input type="date" name="date_fin" value="{{ request('date_fin') }}"
               class="adm-select" style="width:100%">
      </div>

      {{-- Boutons --}}
      <div class="pai-filters-actions" style="display:flex;align-items:flex-end;gap:10px">
        <button type="submit" class="adm-btn adm-btn--primary" style="flex:1">
          <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="margin-right:5px;vertical-align:-2px"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
          Appliquer
        </button>
        @if($hasFilters)
          <a href="{{ route('admin.paiements.list') }}" class="adm-btn adm-btn--outline" style="flex:1;text-align:center">Réinitialiser</a>
        @endif
      </div>

    </div>

    {{-- Raccourcis rapides --}}
    <div style="padding:12px 20px;border-top:1px solid #f8fafc;background:#fafbff;display:flex;align-items:center;gap:8px;flex-wrap:wrap;border-radius:0 0 12px 12px">
      <span style="font-size:11.5px;color:#94a3b8;font-weight:600;margin-right:4px">Accès rapide :</span>
      <a href="{{ route('admin.paiements.list', ['statut' => 'en_attente']) }}"
         style="display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:99px;font-size:12px;font-weight:600;text-decoration:none;border:1.5px solid {{ request('statut') === 'en_attente' && !$hasFilters || (request()->all() == ['statut'=>'en_attente']) ? '#fde68a' : '#e2e8f0' }};background:{{ request('statut') === 'en_attente' && count(request()->all()) === 1 ? '#fef3c7' : '#fff' }};color:#92400e">
        <span style="width:7px;height:7px;border-radius:50%;background:#f59e0b;display:inline-block"></span>
        En attente ({{ $stats['count_attente'] }})
      </a>
      <a href="{{ route('admin.paiements.list', ['statut' => 'confirme']) }}"
         style="display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:99px;font-size:12px;font-weight:600;text-decoration:none;border:1.5px solid {{ count(request()->all()) === 1 && request('statut') === 'confirme' ? '#bbf7d0' : '#e2e8f0' }};background:{{ count(request()->all()) === 1 && request('statut') === 'confirme' ? '#f0fdf4' : '#fff' }};color:#166534">
        <span style="width:7px;height:7px;border-radius:50%;background:#22c55e;display:inline-block"></span>
        Confirmés ({{ $stats['count_confirme'] }})
      </a>
      <a href="{{ route('admin.paiements.list', ['categorie' => 'cv_credits', 'statut' => 'en_attente']) }}"
         style="display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:99px;font-size:12px;font-weight:600;text-decoration:none;border:1.5px solid #e2e8f0;background:#fff;color:#0369a1">
        <span style="width:7px;height:7px;border-radius:50%;background:#0284c7;display:inline-block"></span>
        Crédits CVthèque en attente ({{ $stats['count_credits_attente'] }})
      </a>
      @php
        $today = now()->format('Y-m-d');
        $firstOfMonth = now()->startOfMonth()->format('Y-m-d');
      @endphp
      <a href="{{ route('admin.paiements.list', ['date_debut' => $firstOfMonth, 'date_fin' => $today]) }}"
         style="display:inline-flex;align-items:center;gap:5px;padding:5px 12px;border-radius:99px;font-size:12px;font-weight:600;text-decoration:none;border:1.5px solid #e2e8f0;background:#fff;color:#64748b">
        <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
        Ce mois-ci
      </a>
    </div>
  </form>

  {{-- Chips filtres actifs --}}
  @if($hasFilters)
  <div style="padding:10px 20px;border-top:1px solid #f1f5f9;display:flex;align-items:center;gap:8px;flex-wrap:wrap">
    <span style="font-size:11.5px;color:#64748b;font-weight:600">Filtres actifs :</span>
    @foreach($filtresActifs as $key => $label)
      @php
        $urlSansFiltre = request()->except($key);
      @endphp
      <span style="display:inline-flex;align-items:center;gap:6px;background:#eff6ff;color:#185FA5;font-size:12px;font-weight:600;padding:4px 10px;border-radius:99px;border:1px solid #bfdbfe">
        {{ $label }}
        <a href="{{ route('admin.paiements.list', $urlSansFiltre) }}"
           style="display:flex;align-items:center;color:#185FA5;text-decoration:none;opacity:.7;margin-left:2px"
           title="Retirer ce filtre">
          <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
        </a>
      </span>
    @endforeach
  </div>
  @endif

</div>

<div class="adm-card">
  <div class="adm-table-wrap">
    <table class="adm-table">
      <thead>
        <tr>
          <th>Référence</th>
          <th>Utilisateur</th>
          <th>Plan / Type</th>
          <th>Montant</th>
          <th>Méthode</th>
          <th>Statut</th>
          <th>Date</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($paiements as $paiement)
        <tr>
          <td style="font-family:monospace;font-size:12px;color:#64748b">{{ $paiement->reference }}</td>

          <td>
            @if($paiement->user)
              <div style="font-weight:600;color:#042C53">{{ $paiement->user->nom_complet }}</div>
              <div style="font-size:12px;color:#94a3b8">{{ $paiement->user->email }}</div>
            @elseif($paiement->payable?->email_contact)
              <div style="font-weight:600;color:#042C53">Invité</div>
              <div style="font-size:12px;color:#94a3b8">{{ $paiement->payable->email_contact }}</div>
            @else
              <span style="color:#cbd5e1">—</span>
            @endif
          </td>

          <td>
            @if($paiement->type === 'cv_credits')
              <div style="font-weight:700;color:#042C53;font-size:13px">{{ $paiement->credits_cv }} crédit{{ $paiement->credits_cv > 1 ? 's' : '' }}</div>
              <span class="adm-badge adm-badge--blue" style="font-size:11px;background:#e0f2fe;color:#0369a1">CVthèque</span>
            @elseif($paiement->abonnement?->plan)
              <div style="font-weight:600;color:#042C53;font-size:13px">{{ $paiement->abonnement->plan->name }}</div>
              <span class="adm-badge adm-badge--blue" style="font-size:11px">Abonnement</span>
            @elseif($paiement->type)
              <span class="adm-badge adm-badge--gray" style="font-size:11px">
                {{ ucfirst(str_replace(['abonnement_', '_'], ['', ' '], $paiement->type)) }}
              </span>
            @else
              <span style="color:#cbd5e1">-</span>
            @endif
          </td>

          <td style="font-weight:700;color:#042C53">
            {{ number_format($paiement->montant, 0, ',', ' ') }} {{ $paiement->devise ?? 'FCFA' }}
          </td>

          <td style="font-size:13px;color:#64748b">
            {{ ucfirst(str_replace('_', ' ', $paiement->methode ?? '-')) }}
          </td>

          <td>
            <span class="adm-badge adm-badge--{{ match($paiement->statut) {
              'confirme'   => 'green',
              'en_attente' => 'yellow',
              'echec'      => 'red',
              'rembourse'  => 'gray',
              default      => 'gray'
            } }}">
              {{ match($paiement->statut) {
                'confirme'   => 'Confirmé',
                'en_attente' => 'En attente',
                'echec'      => 'Échec',
                'rembourse'  => 'Remboursé',
                default      => $paiement->statut
              } }}
            </span>
          </td>

          <td style="color:#94a3b8;font-size:12px">
            {{ ($paiement->paid_at ?? $paiement->created_at)?->format('d/m/Y H:i') }}
          </td>

          <td>
            <a href="{{ route('admin.paiements.detail', $paiement) }}" class="adm-btn adm-btn--outline adm-btn--sm">Voir</a>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="8">
            <div class="adm-empty">
              <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
              <h3>Aucun paiement trouvé</h3>
              <p>Essayez d'ajuster vos filtres.</p>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($paiements->hasPages())
    <div style="padding:16px 22px">{{ $paiements->links() }}</div>
  @endif
</div>
@endsection
