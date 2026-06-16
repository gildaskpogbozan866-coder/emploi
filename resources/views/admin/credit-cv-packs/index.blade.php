@extends('layouts.admin')
@section('title', 'Packs Crédits CVthèque — Administration')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <h1>Packs Crédits CVthèque</h1>
    <p>{{ $packs->count() }} pack{{ $packs->count() > 1 ? 's' : '' }} configuré{{ $packs->count() > 1 ? 's' : '' }}</p>
  </div>
  <a href="{{ route('admin.credit-cv-packs.create') }}" class="adm-btn adm-btn--yellow">
    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
    Nouveau pack
  </a>
</div>

@if(session('success'))
  <div class="adm-alert adm-alert--success" style="margin-bottom:16px">{{ session('success') }}</div>
@endif
@if(session('error'))
  <div class="adm-alert adm-alert--danger" style="margin-bottom:16px">{{ session('error') }}</div>
@endif

<div class="adm-card">
  <div style="overflow-x:auto">
    <table style="width:100%;border-collapse:collapse;font-size:13.5px">
      <thead>
        <tr style="background:#f8fafc">
          <th style="padding:11px 18px;text-align:left;font-size:11.5px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0">Ordre</th>
          <th style="padding:11px 18px;text-align:left;font-size:11.5px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0">Label</th>
          <th style="padding:11px 18px;text-align:left;font-size:11.5px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0">Crédits</th>
          <th style="padding:11px 18px;text-align:left;font-size:11.5px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0">Prix (FCFA)</th>
          <th style="padding:11px 18px;text-align:left;font-size:11.5px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0">Prix / crédit</th>
          <th style="padding:11px 18px;text-align:left;font-size:11.5px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0">Badge</th>
          <th style="padding:11px 18px;text-align:left;font-size:11.5px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0">Vedette</th>
          <th style="padding:11px 18px;text-align:left;font-size:11.5px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0">Statut</th>
          <th style="padding:11px 18px;text-align:right;font-size:11.5px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.05em;border-bottom:1px solid #e2e8f0">Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($packs as $pack)
        <tr style="border-bottom:1px solid #f1f5f9;{{ !$pack->actif ? 'opacity:.55' : '' }}">
          <td style="padding:12px 18px;color:#94a3b8;font-size:13px">{{ $pack->ordre }}</td>
          <td style="padding:12px 18px;font-weight:700;color:#042C53">{{ $pack->label }}</td>
          <td style="padding:12px 18px">
            <span style="font-size:1.2rem;font-weight:900;color:#042C53">{{ $pack->credits }}</span>
          </td>
          <td style="padding:12px 18px;font-weight:700;color:#185FA5">{{ number_format($pack->prix, 0, ',', ' ') }}</td>
          <td style="padding:12px 18px;font-size:12px;color:#64748b">{{ number_format($pack->prixParCredit(), 0, ',', ' ') }} / CV</td>
          <td style="padding:12px 18px">
            @if($pack->badge)
              <span style="background:#fef3c7;color:#92400e;font-size:11px;font-weight:700;padding:2px 10px;border-radius:20px">{{ $pack->badge }}</span>
            @else
              <span style="color:#d1d5db">—</span>
            @endif
          </td>
          <td style="padding:12px 18px">
            @if($pack->featured)
              <span style="background:#042C53;color:#F5C842;font-size:11px;font-weight:700;padding:2px 10px;border-radius:20px">Vedette</span>
            @else
              <span style="color:#d1d5db">—</span>
            @endif
          </td>
          <td style="padding:12px 18px">
            <form method="POST" action="{{ route('admin.credit-cv-packs.toggle', $pack) }}" style="display:inline">
              @csrf @method('PATCH')
              <button type="submit" style="border:none;background:none;cursor:pointer;padding:0">
                @if($pack->actif)
                  <span class="adm-badge adm-badge--green">Actif</span>
                @else
                  <span class="adm-badge adm-badge--gray">Inactif</span>
                @endif
              </button>
            </form>
          </td>
          <td style="padding:12px 18px;text-align:right;white-space:nowrap">
            <a href="{{ route('admin.credit-cv-packs.edit', $pack) }}" class="adm-btn adm-btn--sm adm-btn--outline" style="margin-right:6px">Modifier</a>
            <form method="POST" action="{{ route('admin.credit-cv-packs.destroy', $pack) }}" style="display:inline"
                  onsubmit="return confirm('Supprimer le pack « {{ $pack->label }} » ?')">
              @csrf @method('DELETE')
              <button type="submit" class="adm-btn adm-btn--sm adm-btn--danger">Supprimer</button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="9" style="padding:40px;text-align:center;color:#94a3b8">Aucun pack configuré.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
