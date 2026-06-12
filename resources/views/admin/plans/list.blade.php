@extends('layouts.admin')
@section('title', 'Plans d\'abonnement — Administration')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <h1>Plans d'abonnement</h1>
    <p>{{ $plans->count() }} plan{{ $plans->count() > 1 ? 's' : '' }} configuré{{ $plans->count() > 1 ? 's' : '' }}</p>
  </div>
  <a href="{{ route('admin.plans.create') }}" class="adm-btn adm-btn--primary">
    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Nouveau plan
  </a>
</div>

@if(session('success'))
  <div class="adm-alert adm-alert--success" style="margin-bottom:16px">{{ session('success') }}</div>
@endif
@if(session('error'))
  <div class="adm-alert adm-alert--danger" style="margin-bottom:16px">{{ session('error') }}</div>
@endif

{{-- Groupes par cible --}}
@foreach(['candidat' => 'Candidats', 'recruteur' => 'Recruteurs', 'both' => 'Tous (candidats & recruteurs)'] as $type => $label)
  @php $group = $plans->where('target_type', $type) @endphp
  @if($group->count())
  <div style="margin-bottom:32px">
    <h3 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.05em;color:#64748b;margin:0 0 12px">
      {{ $label }}
    </h3>
    <div class="adm-card">
      <div class="adm-table-wrap">
        <table class="adm-table">
          <thead>
            <tr>
              <th>Plan</th>
              <th>Prix / Durée</th>
              <th>Fonctionnalités</th>
              <th style="text-align:center">Abonnements</th>
              <th style="text-align:center">Statut</th>
              <th style="text-align:right">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($group as $plan)
            <tr>
              {{-- Nom --}}
              <td>
                <div style="font-weight:600;color:#042C53">{{ $plan->name }}</div>
                <div style="font-size:11.5px;color:#94a3b8;font-family:monospace">{{ $plan->slug }}</div>
                @if($plan->description)
                  <div style="font-size:12px;color:#64748b;margin-top:2px">{{ Str::limit($plan->description, 70) }}</div>
                @endif
              </td>

              {{-- Prix / Durée --}}
              <td>
                @if($plan->is_free)
                  <span class="adm-badge adm-badge--green">Gratuit</span>
                @else
                  <div style="font-weight:700;color:#185FA5">{{ number_format($plan->price, 0, ',', ' ') }} {{ $plan->currency }}</div>
                @endif
                <div style="font-size:12px;color:#64748b;margin-top:3px">
                  {{ $plan->duration_days ? $plan->duration_days . ' jours' : 'Illimité' }}
                </div>
              </td>

              {{-- Features --}}
              <td>
                @if($plan->features->isEmpty())
                  <span style="color:#cbd5e1;font-size:12px">—</span>
                @else
                  <div style="display:flex;flex-wrap:wrap;gap:4px">
                    @foreach($plan->features as $f)
                      <span style="background:#f1f5f9;color:#334155;font-size:11.5px;padding:2px 7px;border-radius:99px;white-space:nowrap">
                        {{ $f->feature_key }} : {{ $f->feature_value }}
                      </span>
                    @endforeach
                  </div>
                @endif
              </td>

              {{-- Abonnements --}}
              <td style="text-align:center">
                <div style="font-weight:700;font-size:15px;color:#042C53">{{ $plan->abonnements_actifs_count }}</div>
                <div style="font-size:11px;color:#94a3b8">actifs / {{ $plan->abonnements_count }} total</div>
              </td>

              {{-- Statut --}}
              <td style="text-align:center">
                <span class="adm-badge adm-badge--{{ $plan->is_active ? 'green' : 'red' }}">
                  {{ $plan->is_active ? 'Actif' : 'Inactif' }}
                </span>
              </td>

              {{-- Actions --}}
              <td>
                <div class="actions" style="justify-content:flex-end">
                  <a href="{{ route('admin.plans.edit', $plan) }}" class="adm-btn adm-btn--ghost adm-btn--sm">Modifier</a>

                  <form method="POST" action="{{ route('admin.plans.toggle', $plan) }}" style="display:inline">
                    @csrf @method('PATCH')
                    <button type="submit" class="adm-btn adm-btn--ghost adm-btn--sm"
                            style="color:{{ $plan->is_active ? '#d97706' : '#16a34a' }}">
                      {{ $plan->is_active ? 'Désactiver' : 'Activer' }}
                    </button>
                  </form>

                  @if($plan->abonnements_count === 0)
                  <form method="POST" action="{{ route('admin.plans.destroy', $plan) }}" style="display:inline"
                        onsubmit="return confirm('Supprimer le plan « {{ $plan->name }} » ? Cette action est irréversible.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="adm-btn adm-btn--ghost adm-btn--sm" style="color:#ef4444">Supprimer</button>
                  </form>
                  @endif
                </div>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
  @endif
@endforeach

@if($plans->isEmpty())
  <div class="adm-card">
    <div class="adm-empty" style="padding:48px">
      <svg width="36" height="36" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
      <h3>Aucun plan configuré</h3>
      <p>Créez votre premier plan d'abonnement pour commencer.</p>
      <a href="{{ route('admin.plans.create') }}" class="adm-btn adm-btn--primary" style="margin-top:12px">Créer un plan</a>
    </div>
  </div>
@endif

@endsection
