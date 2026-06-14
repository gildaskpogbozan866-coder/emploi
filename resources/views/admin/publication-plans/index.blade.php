@extends('layouts.admin')
@section('title', 'Plans de publication — Administration')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <h1>Plans de publication d'offres</h1>
    <p>Tarifs pour la mise en ligne des annonces (annonceurs / recruteurs)</p>
  </div>
  <div class="adm-topbar__right">
    <a href="{{ route('admin.publication-plans.create') }}" class="adm-btn adm-btn--yellow">
      <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="display:inline-block;vertical-align:-2px;margin-right:5px"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
      Ajouter un plan
    </a>
  </div>
</div>

@if(session('success'))
  <div class="adm-alert adm-alert--success" style="margin-bottom:20px">{{ session('success') }}</div>
@endif
@if(session('error'))
  <div class="adm-alert adm-alert--danger" style="margin-bottom:20px">{{ session('error') }}</div>
@endif

<div class="adm-card">
  <div style="padding:18px 22px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between">
    <h3 style="font-size:14px;font-weight:700;color:#042C53;margin:0">Plans configurés</h3>
    <span style="font-size:12px;color:#94a3b8">{{ $plans->count() }} plan{{ $plans->count() > 1 ? 's' : '' }}</span>
  </div>

  @if($plans->isEmpty())
    <div style="padding:60px;text-align:center;color:#94a3b8">
      <svg width="36" height="36" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="margin:0 auto 14px;display:block"><rect x="3" y="3" width="18" height="18" rx="3"/><path d="M8 12h8M12 8v8"/></svg>
      <p style="margin:0 0 16px;font-size:14px">Aucun plan défini pour le moment.</p>
      <a href="{{ route('admin.publication-plans.create') }}" class="adm-btn adm-btn--yellow">Créer le premier plan</a>
    </div>
  @else
  <div class="adm-table-wrap">
    <table class="adm-table">
      <thead>
        <tr>
          <th>Nom</th>
          <th>Durée</th>
          <th>Prix</th>
          <th style="text-align:center">Offres liées</th>
          <th style="text-align:center">Statut</th>
          <th style="text-align:right">Actions</th>
        </tr>
      </thead>
      <tbody>
        @foreach($plans as $plan)
        <tr>
          <td>
            <span style="font-weight:600;color:#042C53">{{ $plan->name }}</span>
            @if($plan->is_free)
              <span class="adm-badge adm-badge--green" style="margin-left:6px">Gratuit</span>
            @endif
          </td>

          <td style="color:#374151">
            @if($plan->isUnlimited())
              <span style="color:#94a3b8;font-style:italic">Illimité</span>
            @elseif($plan->duration_days == 1)
              1 jour
            @elseif($plan->duration_days == 7)
              1 semaine
            @elseif($plan->duration_days == 30)
              1 mois
            @else
              {{ $plan->duration_days }} jours
            @endif
          </td>

          <td>
            @if($plan->is_free)
              <span style="color:#16a34a;font-weight:600">Gratuit</span>
            @else
              <span style="font-weight:700;color:#185FA5">{{ number_format($plan->price, 0, ',', ' ') }} FCFA</span>
            @endif
          </td>

          <td style="text-align:center">
            <span style="font-weight:700;color:#042C53">{{ $plan->offres_count }}</span>
          </td>

          <td style="text-align:center">
            <span class="adm-badge adm-badge--{{ $plan->is_active ? 'green' : 'red' }}">
              {{ $plan->is_active ? 'Actif' : 'Inactif' }}
            </span>
          </td>

          <td>
            <div class="actions" style="justify-content:flex-end">
              <a href="{{ route('admin.publication-plans.edit', $plan) }}"
                 class="adm-btn adm-btn--outline adm-btn--sm">Modifier</a>

              <form method="POST" action="{{ route('admin.publication-plans.toggle', $plan) }}" style="display:inline">
                @csrf @method('PATCH')
                <button type="submit" class="adm-btn adm-btn--outline adm-btn--sm"
                        style="color:{{ $plan->is_active ? '#d97706' : '#16a34a' }}">
                  {{ $plan->is_active ? 'Désactiver' : 'Activer' }}
                </button>
              </form>

              @if($plan->offres_count === 0)
              <form method="POST" action="{{ route('admin.publication-plans.destroy', $plan) }}" style="display:inline"
                    onsubmit="return confirm('Supprimer « {{ $plan->name }} » ?')">
                @csrf @method('DELETE')
                <button type="submit" class="adm-btn adm-btn--danger adm-btn--sm">Supprimer</button>
              </form>
              @endif
            </div>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  @endif
</div>
@endsection
