@extends('layouts.admin')
@section('title', 'Tableau de bord | Administration')

@php
  $stats              = $stats ?? [];
  $chartData          = $chartData ?? [
    'utilisateurs'  => ['labels' => [], 'values' => []],
    'offresStatut'  => ['labels' => [], 'values' => []],
    'moisLabels'    => [],
    'inscriptions'  => [],
    'candidatures'  => [],
    'revenus'       => [],
    'offresParType' => ['labels' => [], 'values' => []],
    'candParStatut' => ['labels' => [], 'values' => []],
    'topOffres'     => ['labels' => [], 'values' => []],
  ];
  $tauxConversion       = $tauxConversion ?? 0;
  $derniers_utilisateurs = $derniers_utilisateurs ?? collect();
  $dernieres_offres      = $dernieres_offres ?? collect();
  $dernieres_commandes   = $dernieres_commandes ?? collect();
  $derniers_cvs          = $derniers_cvs ?? collect();
  $derniers_signalements = $derniers_signalements ?? collect();
  $topRecruteurs         = $topRecruteurs ?? collect();
  $maintenanceMode       = $maintenanceMode ?? false;
@endphp

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <h1>Tableau de bord</h1>
    <p>Vue d'ensemble de la plateforme Emploi Bouge Bénin</p>
  </div>
  <div class="adm-topbar__right" style="display:flex;align-items:center;gap:10px;flex-wrap:wrap">
    @if($maintenanceMode)
      <form method="POST" action="{{ route('admin.parametres.maintenance.desactiver') }}">
        @csrf
        <button type="submit" class="adm-btn adm-btn--yellow" style="display:flex;align-items:center;gap:6px">
          <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
          Désactiver la maintenance
        </button>
      </form>
    @else
      <form method="POST" action="{{ route('admin.parametres.maintenance.activer') }}"
            data-confirm="Activer le mode maintenance ? Le site affichera une page d'erreur 503 à tous les visiteurs (l'espace admin et la connexion restent accessibles)."
            data-confirm-btn="Activer" data-confirm-color="#dc2626">
        @csrf
        <button type="submit" class="adm-btn" style="display:flex;align-items:center;gap:6px;background:#f8fafc;color:#64748b;border:1.5px solid #e2e8f0">
          <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          Activer la maintenance
        </button>
      </form>
    @endif
    <div style="display:inline-flex;border:1.5px solid #e2e8f0;border-radius:8px;overflow:hidden">
      <button id="btn-tables" onclick="switchView('tables')"
              style="padding:7px 16px;font-size:13px;font-weight:600;border:none;cursor:pointer;display:flex;align-items:center;gap:6px;background:#042C53;color:#fff;transition:background .15s">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18M3 15h18M9 3v18"/></svg>
        Tableaux
      </button>
      <button id="btn-charts" onclick="switchView('charts')"
              style="padding:7px 16px;font-size:13px;font-weight:600;border:none;cursor:pointer;display:flex;align-items:center;gap:6px;background:#f8fafc;color:#64748b;transition:background .15s">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
        Graphiques
      </button>
    </div>
  </div>
</div>

{{-- KPIs --}}
<div class="adm-stats" style="grid-template-columns:repeat(auto-fit,minmax(150px,1fr))">
  <a class="adm-stat adm-stat--link" href="{{ route('admin.utilisateurs.candidats') }}">
    <div class="adm-stat__icon adm-stat__icon--blue">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
    </div>
    <div>
      <div class="adm-stat__val">{{ $stats['candidats'] ?? 0 }}</div>
      <div class="adm-stat__label">Candidats</div>
    </div>
  </a>
  <a class="adm-stat adm-stat--link" href="{{ route('admin.utilisateurs.recruteurs') }}">
    <div class="adm-stat__icon adm-stat__icon--violet">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
    </div>
    <div>
      <div class="adm-stat__val">{{ $stats['recruteurs'] ?? 0 }}</div>
      <div class="adm-stat__label">Recruteurs</div>
    </div>
  </a>
  <a class="adm-stat adm-stat--link" href="{{ route('admin.utilisateurs.list', ['role' => 'annonceur']) }}">
    <div class="adm-stat__icon adm-stat__icon--orange">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
    </div>
    <div>
      <div class="adm-stat__val">{{ $stats['annonceurs'] ?? 0 }}</div>
      <div class="adm-stat__label">Annonceurs</div>
    </div>
  </a>
  <a class="adm-stat adm-stat--link" href="{{ route('admin.offres.list', ['statut' => 'active']) }}">
    <div class="adm-stat__icon adm-stat__icon--blue">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/></svg>
    </div>
    <div>
      <div class="adm-stat__val">{{ $stats['offres_actives'] ?? 0 }}</div>
      <div class="adm-stat__label">Offres actives</div>
    </div>
  </a>
  <div class="adm-stat" title="Aucune page de gestion dédiée aux candidatures côté admin pour l'instant">
    <div class="adm-stat__icon adm-stat__icon--violet">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg>
    </div>
    <div>
      <div class="adm-stat__val">{{ $stats['candidatures'] ?? 0 }}</div>
      <div class="adm-stat__label">Candidatures</div>
    </div>
  </div>
  <a class="adm-stat adm-stat--link" href="{{ route('admin.cvs.list') }}" style="position:relative">
    @if(($stats['cvs_nouveaux'] ?? 0) > 0)
    <span style="position:absolute;top:10px;right:10px;background:#dc2626;color:#fff;font-size:10px;font-weight:800;border-radius:99px;padding:2px 7px;line-height:1.4">
      {{ $stats['cvs_nouveaux'] }} nouveau{{ $stats['cvs_nouveaux'] > 1 ? 'x' : '' }}
    </span>
    @endif
    <div class="adm-stat__icon adm-stat__icon--orange">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/></svg>
    </div>
    <div>
      <div class="adm-stat__val">{{ $stats['cvs'] ?? 0 }}</div>
      <div class="adm-stat__label">CVs</div>
    </div>
  </a>
  <a class="adm-stat adm-stat--link" href="{{ route('admin.commandes.list') }}">
    <div class="adm-stat__icon adm-stat__icon--yellow">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg>
    </div>
    <div>
      <div class="adm-stat__val">{{ $stats['commandes'] ?? 0 }}</div>
      <div class="adm-stat__label">Commandes</div>
    </div>
  </a>
  <a class="adm-stat adm-stat--link" href="{{ route('admin.paiements.list') }}">
    <div class="adm-stat__icon adm-stat__icon--green">
      <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
    </div>
    <div>
      <div class="adm-stat__val" style="color:#38A169;font-size:1.1rem">{{ number_format($stats['paiements'] ?? 0, 0, ',', ' ') }}</div>
      <div class="adm-stat__label">FCFA confirmés</div>
    </div>
  </a>
</div>

@if(($stats['signalements'] ?? 0) > 0)
  <div class="flash flash--warning" style="margin-bottom:24px">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
    <span>{{ $stats['signalements'] ?? 0 }} signalement(s) en attente.
      <a href="{{ route('admin.signalements.list') }}" style="font-weight:700;margin-left:6px;color:inherit">Traiter →</a>
    </span>
  </div>
@endif

{{-- ══════════════════════ VUE TABLEAUX ══════════════════════ --}}
<div id="view-tables">
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-top:4px">

    {{-- Derniers inscrits --}}
    <div class="adm-card" style="grid-column:1/-1">
      <div class="adm-card__header" style="flex-wrap:wrap;gap:8px">
        <h2>Derniers inscrits</h2>
        <div style="display:flex;align-items:center;gap:8px;margin-left:auto">
          <select class="adm-select" onchange="filterTable('tbl-users', this.value, 1)">
            <option value="">Tous les rôles</option>
            <option value="candidat">Candidats</option>
            <option value="recruteur">Recruteurs</option>
            <option value="annonceur">Annonceurs</option>
          </select>
          <a href="{{ route('admin.utilisateurs.candidats') }}" class="adm-btn adm-btn--outline adm-btn--sm">Voir tout</a>
        </div>
      </div>
      <div class="adm-table-wrap">
        <table class="adm-table">
          <thead><tr><th>Nom</th><th>Rôle</th><th>Email</th><th>Date</th></tr></thead>
          <tbody id="tbl-users">
            @foreach($derniers_utilisateurs ?? [] as $u)
            <tr>
              <td style="font-weight:500;white-space:nowrap">{{ $u->nom_complet ?? $u->email }}</td>
              <td><span class="badge-role badge-role--{{ $u->role }}">{{ ucfirst($u->role) }}</span></td>
              <td style="font-size:12px;color:#64748b">{{ $u->email }}</td>
              <td style="color:#94a3b8;font-size:12px;white-space:nowrap">{{ $u->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    {{-- Dernières offres --}}
    <div class="adm-card" style="grid-column:1/-1">
      <div class="adm-card__header" style="flex-wrap:wrap;gap:8px">
        <h2>Dernières offres</h2>
        <div style="display:flex;align-items:center;gap:8px;margin-left:auto">
          <select class="adm-select" onchange="filterTable('tbl-offres', this.value, 1)">
            <option value="">Tous statuts</option>
            <option value="active">Active</option>
            <option value="en_attente">En attente</option>
            <option value="expiree">Expirée</option>
            <option value="rejetee">Rejetée</option>
          </select>
          <a href="{{ route('admin.offres.list') }}" class="adm-btn adm-btn--outline adm-btn--sm">Voir tout</a>
        </div>
      </div>
      <div class="adm-table-wrap">
        <table class="adm-table">
          <thead><tr><th>Titre</th><th>Statut</th><th>Recruteur</th><th>Date</th></tr></thead>
          <tbody id="tbl-offres">
            @foreach($dernieres_offres ?? [] as $offre)
            <tr>
              <td style="max-width:160px">
                <p style="font-weight:500;margin:0;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">{{ $offre->titre }}</p>
                <p style="font-size:11px;color:#94a3b8;margin:2px 0 0">{{ $offre->entreprise }}</p>
              </td>
              <td><span class="badge-statut badge-statut--{{ $offre->statut }}">{{ ucfirst(str_replace('_',' ',$offre->statut)) }}</span></td>
              <td style="font-size:12px;color:#64748b">{{ $offre->recruteur?->nom_complet ?? '-' }}</td>
              <td style="color:#94a3b8;font-size:12px;white-space:nowrap">{{ $offre->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    {{-- Dernières commandes --}}
    <div class="adm-card" style="grid-column:1/-1">
      <div class="adm-card__header" style="flex-wrap:wrap;gap:8px">
        <h2>Dernières commandes</h2>
        <div style="display:flex;align-items:center;gap:8px;margin-left:auto">
          <select class="adm-select" onchange="filterTable('tbl-commandes', this.value, 2)">
            <option value="">Tous statuts</option>
            <option value="en attente">En attente</option>
            <option value="confirme">Confirmée</option>
            <option value="annule">Annulée</option>
          </select>
          <a href="{{ route('admin.commandes.list') }}" class="adm-btn adm-btn--outline adm-btn--sm">Voir tout</a>
        </div>
      </div>
      <div class="adm-table-wrap">
        <table class="adm-table">
          <thead><tr><th>Client</th><th>Service</th><th>Statut</th><th>Date</th></tr></thead>
          <tbody id="tbl-commandes">
            @foreach($dernieres_commandes ?? [] as $cmd)
            <tr>
              <td style="font-weight:500;white-space:nowrap">{{ $cmd->user?->nom_complet ?? $cmd->email_contact ?? 'Invité' }}</td>
              <td style="color:#6b7a8d;font-size:12.5px">{{ $cmd->service->nom ?? '-' }}</td>
              <td><span class="badge-statut badge-statut--{{ $cmd->statut }}">{{ ucfirst(str_replace('_',' ',$cmd->statut)) }}</span></td>
              <td style="color:#94a3b8;font-size:12px;white-space:nowrap">{{ $cmd->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>

    {{-- Derniers CVs déposés --}}
    <div class="adm-card" style="grid-column:1/-1">
      <div class="adm-card__header">
        <h2>Derniers CVs déposés</h2>
        <a href="{{ route('admin.cvs.list') }}" class="adm-btn adm-btn--outline adm-btn--sm">Voir tout</a>
      </div>
      @if(($derniers_cvs ?? collect())->isEmpty())
        <div class="adm-empty" style="padding:24px 22px">
          <p style="font-size:13.5px;color:#64748b">Aucun CV déposé pour l'instant.</p>
        </div>
      @else
        <div class="adm-table-wrap">
          <table class="adm-table">
            <thead>
              <tr><th>Candidat</th><th>Métier</th><th>Ville</th><th>Visible</th><th>Déposé le</th><th>Actions</th></tr>
            </thead>
            <tbody>
              @foreach($derniers_cvs ?? [] as $cv)
              <tr>
                <td>
                  <div style="font-weight:600;color:#042C53;display:flex;align-items:center;gap:6px">
                    {{ $cv->candidat?->nom_complet ?? '—' }}
                    @if(!$cv->vu_admin && $cv->publie_le)
                    <span style="background:#dc2626;color:#fff;font-size:9px;font-weight:800;border-radius:99px;padding:1px 6px;text-transform:uppercase;letter-spacing:.04em">Nouveau</span>
                    @endif
                  </div>
                  <div style="font-size:11px;color:#94a3b8">{{ $cv->candidat?->email }}</div>
                </td>
                <td style="color:#374151">{{ $cv->metier ?: '—' }}</td>
                <td style="color:#64748b">{{ $cv->ville ?: '—' }}</td>
                <td>
                  <span class="adm-badge adm-badge--{{ $cv->visible ? 'green' : 'red' }}">
                    {{ $cv->visible ? 'Visible' : 'Masqué' }}
                  </span>
                </td>
                <td style="color:#94a3b8;font-size:12px;white-space:nowrap">
                  {{ $cv->publie_le ? $cv->publie_le->format('d/m/Y') : ($cv->created_at->format('d/m/Y').' (non publié)') }}
                </td>
                <td>
                  <div class="actions">
                    <a href="{{ route('admin.cvs.detail', $cv) }}" class="adm-btn adm-btn--outline adm-btn--sm">Voir</a>
                    <form method="POST" action="{{ route('admin.cvs.toggle', $cv) }}">
                      @csrf @method('PATCH')
                      <button type="submit" class="adm-btn adm-btn--sm adm-btn--{{ $cv->visible ? 'danger' : 'primary' }}">
                        {{ $cv->visible ? 'Masquer' : 'Afficher' }}
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>

    {{-- Signalements --}}
    <div class="adm-card" style="grid-column:1/-1">
      <div class="adm-card__header">
        <h2>Signalements en attente</h2>
        <a href="{{ route('admin.signalements.list') }}" class="adm-btn adm-btn--outline adm-btn--sm">Voir tout</a>
      </div>
      @if(($derniers_signalements ?? collect())->isEmpty())
        <div class="adm-empty" style="padding:24px 22px">
          <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><polyline points="20 6 9 17 4 12"/></svg>
          <p style="font-size:13.5px;color:#64748b">Aucun signalement en attente</p>
        </div>
      @else
        <div class="adm-table-wrap">
          <table class="adm-table">
            <thead><tr><th>Type</th><th>Raison</th><th>Signalé par</th><th>Date</th></tr></thead>
            <tbody>
              @foreach($derniers_signalements ?? [] as $s)
              <tr>
                <td><span class="tag">{{ ucfirst($s->type) }}</span></td>
                <td style="color:#6b7a8d;font-size:12.5px">{{ Str::limit($s->raison, 36) }}</td>
                <td style="font-weight:500">{{ $s->user?->nom_complet ?? '—' }}</td>
                <td style="color:#94a3b8;font-size:12px;white-space:nowrap">{{ $s->created_at->format('d/m/Y') }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>

  </div>
</div>

{{-- ══════════════════════ VUE GRAPHIQUES ══════════════════════ --}}
<div id="view-charts" style="display:none;margin-top:4px">

  {{-- Taux de conversion --}}
  <div class="adm-card" style="margin-bottom:20px;display:flex;flex-direction:column;align-items:center;padding:32px 24px;text-align:center">
    <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="1.5" style="margin-bottom:12px"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
    <div style="font-size:3rem;font-weight:800;color:#185FA5;line-height:1">{{ $tauxConversion ?? 0 }}%</div>
    <div style="font-size:13px;font-weight:600;color:#64748b;margin-top:8px">Taux de conversion</div>
    <div style="font-size:11.5px;color:#94a3b8;margin-top:4px">vues → candidatures</div>
    <div style="margin-top:16px;font-size:11.5px;color:#042C53;background:#f0f7ff;padding:6px 14px;border-radius:20px">
      {{ number_format($stats['candidatures'] ?? 0) }} cand. / {{ number_format(app(\App\Models\Offre::class)->sum('vues') ?: 0) }} vues
    </div>
  </div>

  {{-- Répartition utilisateurs --}}
  <div class="adm-card" style="margin-bottom:20px">
    <div class="adm-card__header"><h2>Répartition utilisateurs</h2></div>
    <div style="padding:12px 22px 20px;display:flex;align-items:center;justify-content:center">
      <canvas id="chartUtilisateurs" style="max-width:300px;max-height:300px"></canvas>
    </div>
  </div>

  {{-- Offres par statut --}}
  <div class="adm-card" style="margin-bottom:20px">
    <div class="adm-card__header"><h2>Offres par statut</h2></div>
    <div style="padding:12px 22px 20px;display:flex;align-items:center;justify-content:center">
      <canvas id="chartOffresStatut" style="max-width:300px;max-height:300px"></canvas>
    </div>
  </div>

  {{-- Inscriptions --}}
  <div class="adm-card" style="margin-bottom:20px">
    <div class="adm-card__header"><h2>Nouvelles inscriptions, 6 derniers mois</h2></div>
    <div style="padding:16px 22px 20px">
      <canvas id="chartInscriptions" height="80"></canvas>
    </div>
  </div>

  {{-- Candidatures par mois --}}
  <div class="adm-card" style="margin-bottom:20px">
    <div class="adm-card__header"><h2>Candidatures par mois</h2></div>
    <div style="padding:16px 22px 20px">
      <canvas id="chartCandidatures" height="100"></canvas>
    </div>
  </div>

  {{-- Revenus --}}
  <div class="adm-card" style="margin-bottom:20px">
    <div class="adm-card__header"><h2>Revenus confirmés, 6 mois (FCFA)</h2></div>
    <div style="padding:16px 22px 20px">
      <canvas id="chartRevenus" height="100"></canvas>
    </div>
  </div>

  {{-- Offres par type de contrat --}}
  <div class="adm-card" style="margin-bottom:20px">
    <div class="adm-card__header"><h2>Offres par type de contrat</h2></div>
    <div style="padding:16px 22px 20px">
      <canvas id="chartOffresType"
              height="{{ max(80, count($chartData['offresParType']['labels']) * 36) }}"></canvas>
    </div>
  </div>

  {{-- Candidatures par statut --}}
  <div class="adm-card" style="margin-bottom:20px">
    <div class="adm-card__header"><h2>Candidatures par statut</h2></div>
    <div style="padding:16px 22px 20px">
      <canvas id="chartCandStatut"
              height="{{ max(80, count($chartData['candParStatut']['labels']) * 36) }}"></canvas>
    </div>
  </div>

  {{-- Top 5 offres les plus vues --}}
  @if(!empty($chartData['topOffres']['labels']))
  <div class="adm-card" style="margin-bottom:20px">
    <div class="adm-card__header"><h2>Top 5 offres les plus vues</h2></div>
    <div style="padding:16px 22px 20px">
      <canvas id="chartTopOffres" height="{{ count($chartData['topOffres']['labels']) * 40 }}"></canvas>
    </div>
  </div>
  @endif

  {{-- Top recruteurs --}}
  @if(($topRecruteurs ?? collect())->isNotEmpty())
  <div class="adm-card" style="margin-bottom:20px">
    <div class="adm-card__header"><h2>Top recruteurs par candidatures reçues</h2></div>
    <div class="adm-table-wrap">
      <table class="adm-table">
        <thead>
          <tr><th>#</th><th>Recruteur</th><th>Entreprise</th><th>Email</th><th style="text-align:right">Candidatures</th></tr>
        </thead>
        <tbody>
          @foreach($topRecruteurs ?? [] as $i => $rec)
          <tr>
            <td style="font-weight:700;color:#185FA5">{{ $i + 1 }}</td>
            <td style="font-weight:500">{{ $rec->nom_complet ?? $rec->email }}</td>
            <td style="color:#64748b">{{ $rec->entreprise ?? '-' }}</td>
            <td style="font-size:12.5px;color:#64748b">{{ $rec->email }}</td>
            <td style="text-align:right;font-weight:700;color:#042C53">{{ $rec->total_candidatures }}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
  @endif

</div>

@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const chartData = @json($chartData);
let chartsInitialized = false;

function switchView(mode) {
  const isCharts = mode === 'charts';
  document.getElementById('view-tables').style.display = isCharts ? 'none' : '';
  document.getElementById('view-charts').style.display = isCharts ? '' : 'none';
  document.getElementById('btn-tables').style.background = isCharts ? '#f8fafc' : '#042C53';
  document.getElementById('btn-tables').style.color     = isCharts ? '#64748b' : '#fff';
  document.getElementById('btn-charts').style.background = isCharts ? '#042C53' : '#f8fafc';
  document.getElementById('btn-charts').style.color     = isCharts ? '#fff' : '#64748b';
  if (isCharts && !chartsInitialized) { initCharts(); chartsInitialized = true; }
}

function filterTable(tbodyId, val, colIndex) {
  document.querySelectorAll('#' + tbodyId + ' tr').forEach(function(row) {
    var cell = row.cells[colIndex];
    if (!val || (cell && cell.textContent.trim().toLowerCase().includes(val.toLowerCase()))) {
      row.style.display = '';
    } else {
      row.style.display = 'none';
    }
  });
}

function initCharts() {
  const bleu   = '#185FA5';
  const fonce  = '#042C53';
  const jaune  = '#F5C842';
  const vert   = '#38A169';
  const orange = '#D97706';
  const rouge  = '#DC2626';
  const violet = '#7C3AED';
  const teal   = '#0891b2';
  const rose   = '#db2777';

  const palette = [bleu, violet, teal, orange, vert, rouge, rose, jaune];
  const paletteAlpha = palette.map(c => c + 'cc');

  Chart.defaults.font.family = 'inherit';
  Chart.defaults.font.size   = 12;
  Chart.defaults.color       = '#64748b';

  const gridColor = '#f1f5f9';

  // ① Répartition utilisateurs, doughnut
  new Chart(document.getElementById('chartUtilisateurs'), {
    type: 'doughnut',
    data: {
      labels: chartData.utilisateurs.labels,
      datasets: [{ data: chartData.utilisateurs.values, backgroundColor: [bleu, violet, orange], borderWidth: 2, borderColor: '#fff' }]
    },
    options: {
      responsive: true,
      plugins: { legend: { position: 'bottom', labels: { padding: 14, usePointStyle: true, font: { size: 12 } } } },
      cutout: '60%',
    }
  });

  // ② Offres par statut, doughnut
  new Chart(document.getElementById('chartOffresStatut'), {
    type: 'doughnut',
    data: {
      labels: chartData.offresStatut.labels,
      datasets: [{ data: chartData.offresStatut.values, backgroundColor: [vert, orange, '#94a3b8', rouge], borderWidth: 2, borderColor: '#fff' }]
    },
    options: {
      responsive: true,
      plugins: { legend: { position: 'bottom', labels: { padding: 14, usePointStyle: true, font: { size: 12 } } } },
      cutout: '60%',
    }
  });

  // ③ Inscriptions, bar
  new Chart(document.getElementById('chartInscriptions'), {
    type: 'bar',
    data: {
      labels: chartData.moisLabels,
      datasets: [{
        label: 'Inscriptions',
        data: chartData.inscriptions,
        backgroundColor: bleu + 'cc',
        borderColor: bleu,
        borderWidth: 1.5,
        borderRadius: 6,
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => ctx.parsed.y + ' inscription(s)' } } },
      scales: {
        y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: gridColor } },
        x: { grid: { display: false } }
      }
    }
  });

  // ④ Candidatures par mois, line
  new Chart(document.getElementById('chartCandidatures'), {
    type: 'line',
    data: {
      labels: chartData.moisLabels,
      datasets: [{
        label: 'Candidatures',
        data: chartData.candidatures,
        borderColor: violet,
        backgroundColor: violet + '22',
        borderWidth: 2.5,
        tension: 0.4,
        fill: true,
        pointBackgroundColor: violet,
        pointRadius: 4,
      }]
    },
    options: {
      responsive: true,
      plugins: { legend: { display: false } },
      scales: {
        y: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: gridColor } },
        x: { grid: { display: false } }
      }
    }
  });

  // ⑤ Revenus par mois, bar
  new Chart(document.getElementById('chartRevenus'), {
    type: 'bar',
    data: {
      labels: chartData.moisLabels,
      datasets: [{
        label: 'FCFA',
        data: chartData.revenus,
        backgroundColor: jaune + 'cc',
        borderColor: '#d97706',
        borderWidth: 1.5,
        borderRadius: 6,
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: false },
        tooltip: { callbacks: { label: ctx => ctx.parsed.y.toLocaleString('fr-FR') + ' FCFA' } }
      },
      scales: {
        y: { beginAtZero: true, grid: { color: gridColor }, ticks: { callback: v => v.toLocaleString('fr-FR') } },
        x: { grid: { display: false } }
      }
    }
  });

  // ⑥ Offres par type de contrat, horizontal bar
  if (chartData.offresParType.labels.length) {
    new Chart(document.getElementById('chartOffresType'), {
      type: 'bar',
      data: {
        labels: chartData.offresParType.labels,
        datasets: [{
          label: 'Offres',
          data: chartData.offresParType.values,
          backgroundColor: paletteAlpha,
          borderWidth: 0,
          borderRadius: 4,
        }]
      },
      options: {
        indexAxis: 'y',
        responsive: true,
        plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => ctx.parsed.x + ' offre(s)' } } },
        scales: {
          x: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: gridColor } },
          y: { grid: { display: false } }
        }
      }
    });
  }

  // ⑦ Candidatures par statut, horizontal bar
  if (chartData.candParStatut.labels.length) {
    new Chart(document.getElementById('chartCandStatut'), {
      type: 'bar',
      data: {
        labels: chartData.candParStatut.labels,
        datasets: [{
          label: 'Candidatures',
          data: chartData.candParStatut.values,
          backgroundColor: [bleu, orange, vert, rouge, violet, teal].map(c => c + 'cc'),
          borderWidth: 0,
          borderRadius: 4,
        }]
      },
      options: {
        indexAxis: 'y',
        responsive: true,
        plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => ctx.parsed.x + ' candidature(s)' } } },
        scales: {
          x: { beginAtZero: true, ticks: { stepSize: 1 }, grid: { color: gridColor } },
          y: { grid: { display: false } }
        }
      }
    });
  }

  // ⑧ Top 5 offres les plus vues, horizontal bar
  const elTopOffres = document.getElementById('chartTopOffres');
  if (elTopOffres && chartData.topOffres.labels.length) {
    new Chart(elTopOffres, {
      type: 'bar',
      data: {
        labels: chartData.topOffres.labels,
        datasets: [{
          label: 'Vues',
          data: chartData.topOffres.values,
          backgroundColor: bleu + 'cc',
          borderWidth: 0,
          borderRadius: 4,
        }]
      },
      options: {
        indexAxis: 'y',
        responsive: true,
        plugins: {
          legend: { display: false },
          tooltip: { callbacks: { label: ctx => ctx.parsed.x.toLocaleString('fr-FR') + ' vue(s)' } }
        },
        scales: {
          x: { beginAtZero: true, grid: { color: gridColor }, ticks: { callback: v => v.toLocaleString('fr-FR') } },
          y: { grid: { display: false } }
        }
      }
    });
  }
}
</script>
@endsection
