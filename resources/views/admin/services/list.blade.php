@extends('layouts.admin')
@section('title', 'Services — Administration')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <h1>Gestion des services</h1>
    <p>{{ count($services) }} service{{ count($services) > 1 ? 's' : '' }} disponible{{ count($services) > 1 ? 's' : '' }}</p>
  </div>
  <a href="{{ route('admin.services.create') }}" class="adm-btn adm-btn--primary">
    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Nouveau service
  </a>
</div>

<div class="adm-card">
  <div class="adm-table-wrap">
    <table class="adm-table">
      <thead>
        <tr><th>Nom</th><th>Type</th><th>Prix</th><th>Délai</th><th>Commandes</th><th>Actif</th><th>Actions</th></tr>
      </thead>
      <tbody>
        @forelse($services as $service)
        <tr>
          <td>
            <div style="font-weight:600;color:#042C53">{{ $service->nom }}</div>
            @if($service->description)
              <div style="font-size:12px;color:#94a3b8">{{ Str::limit($service->description, 60) }}</div>
            @endif
          </td>
          <td style="color:#64748b;font-size:13px">{{ $service->type ?? '—' }}</td>
          <td style="font-weight:700;color:#185FA5">{{ number_format($service->prix, 0, ',', ' ') }} FCFA</td>
          <td style="color:#64748b;font-size:13px">{{ $service->delai ?? '—' }}</td>
          <td style="text-align:center"><strong>{{ $service->commandes_count }}</strong></td>
          <td>
            <span class="adm-badge adm-badge--{{ $service->actif ? 'green' : 'red' }}">
              {{ $service->actif ? 'Actif' : 'Inactif' }}
            </span>
          </td>
          <td>
            <div class="actions">
              <a href="{{ route('admin.services.edit', $service) }}" class="adm-btn adm-btn--ghost adm-btn--sm">Modifier</a>
              <a href="{{ route('service.detail', $service) }}" target="_blank" class="adm-btn adm-btn--ghost adm-btn--sm">Voir</a>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7">
            <div class="adm-empty">
              <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
              <h3>Aucun service créé</h3>
              <p><a href="{{ route('admin.services.create') }}" class="adm-btn adm-btn--primary adm-btn--sm" style="margin-top:8px">Créer un service</a></p>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
