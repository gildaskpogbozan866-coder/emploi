@extends('layouts.admin')
@section('title', $label . ' — Administration')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <h1>{{ $label }}</h1>
    <p>{{ $items->count() }} entrée{{ $items->count() > 1 ? 's' : '' }}</p>
  </div>
  <div class="adm-topbar__actions">
    <a href="{{ route($routeCreate) }}" class="adm-btn adm-btn--yellow">
      <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
      Ajouter
    </a>
  </div>
</div>

@if(session('success'))
  <div class="adm-alert adm-alert--success" style="margin-bottom:16px">{{ session('success') }}</div>
@endif
@if(session('error'))
  <div class="adm-alert adm-alert--danger" style="margin-bottom:16px">{{ session('error') }}</div>
@endif

<div class="adm-card">
  <div class="adm-table-wrap">
    <table class="adm-table">
      <thead>
        <tr>
          @if($hasCode)
            <th>Code</th>
            <th>Libellé</th>
          @else
            <th>Nom</th>
            @if($hasDesc)<th>Description</th>@endif
          @endif
          @if($hasOrdre)<th style="width:80px">Ordre</th>@endif
          <th style="width:110px">Candidats</th>
          <th style="width:140px">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($items as $item)
        <tr>
          @if($hasCode)
            <td><code style="background:#f1f5f9;padding:2px 6px;border-radius:4px;font-size:12px">{{ $item->code }}</code></td>
            <td style="font-weight:600">{{ $item->libelle }}</td>
          @else
            <td style="font-weight:600">{{ $item->nom }}</td>
            @if($hasDesc)<td style="color:#64748b;font-size:13px">{{ $item->description ?? '—' }}</td>@endif
          @endif
          @if($hasOrdre)<td style="color:#64748b;text-align:center">{{ $item->ordre }}</td>@endif
          <td style="text-align:center">
            <span class="adm-badge adm-badge--gray">{{ $item->{$countKey} ?? 0 }}</span>
          </td>
          <td>
            <div class="actions">
              <a href="{{ route($routeEdit, $item) }}" class="adm-btn adm-btn--outline adm-btn--sm">Modifier</a>
              <form method="POST" action="{{ route($routeDestroy, $item) }}"
                    onsubmit="return confirm('Supprimer ce(tte) {{ $singular }} ?')">
                @csrf @method('DELETE')
                <button type="submit" class="adm-btn adm-btn--danger adm-btn--sm">Supprimer</button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="10">
            <div class="adm-empty">
              <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
              <h3>Aucun(e) {{ $singular }}</h3>
              <p>Cliquez sur "Ajouter" pour créer le premier.</p>
            </div>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
