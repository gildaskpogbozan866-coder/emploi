@extends('layouts.admin')
@section('title', 'Publicités — Administration')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <h1>Publicités</h1>
    <p>Modérez les annonces soumises par les annonceurs.</p>
  </div>
</div>

{{-- Compteurs --}}
<div style="display:flex;gap:12px;margin-bottom:24px;flex-wrap:wrap">
  @foreach([
    ['label' => 'En attente', 'value' => $counts['en_attente'], 'color' => '#d97706', 'bg' => '#fef3c7', 'statut' => 'en_attente'],
    ['label' => 'Approuvées', 'value' => $counts['approuve'],   'color' => '#16a34a', 'bg' => '#dcfce7', 'statut' => 'approuve'],
    ['label' => 'Rejetées',   'value' => $counts['rejete'],     'color' => '#dc2626', 'bg' => '#fee2e2', 'statut' => 'rejete'],
  ] as $c)
  <a href="{{ request()->fullUrlWithQuery(['statut' => $c['statut']]) }}"
     style="display:flex;align-items:center;gap:10px;padding:12px 18px;background:{{ $c['bg'] }};border-radius:10px;text-decoration:none;flex:1;min-width:140px">
    <span style="font-size:1.5rem;font-weight:800;color:{{ $c['color'] }}">{{ $c['value'] }}</span>
    <span style="font-size:13px;font-weight:600;color:{{ $c['color'] }}">{{ $c['label'] }}</span>
  </a>
  @endforeach
</div>

<div class="adm-card">
  <div class="adm-card__body" style="padding:0">
    @if($publicites->isEmpty())
      <div class="adm-empty" style="padding:48px 20px">
        <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><rect x="3" y="3" width="18" height="13" rx="2"/><path d="M8 21h8M12 17v4"/></svg>
        <h3>Aucune publicité</h3>
        <p>Aucune annonce ne correspond aux filtres sélectionnés.</p>
        <a href="{{ route('admin.publicites.index') }}" class="adm-btn adm-btn--outline adm-btn--sm">Voir tout</a>
      </div>
    @else
      <table class="adm-table">
        <thead>
          <tr>
            <th>Annonce</th>
            <th>Annonceur</th>
            <th>Statut</th>
            <th>Diffusion</th>
            <th>Soumise le</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          @foreach($publicites as $pub)
          <tr>
            <td>
              <div style="display:flex;align-items:center;gap:12px">
                <img src="{{ asset('storage/' . $pub->image) }}"
                     alt="{{ $pub->titre }}"
                     style="width:64px;height:48px;object-fit:cover;border-radius:6px;flex-shrink:0">
                <div>
                  <div style="font-weight:600;color:#042C53;font-size:13.5px">{{ $pub->titre }}</div>
                  @if($pub->lien)
                    <div style="font-size:12px;color:#94a3b8">{{ Str::limit($pub->lien, 35) }}</div>
                  @endif
                </div>
              </div>
            </td>
            <td>
              <div style="font-weight:600;color:#042C53;font-size:13px">{{ $pub->user->nom_complet }}</div>
              <div style="font-size:12px;color:#94a3b8">{{ $pub->user->email }}</div>
            </td>
            <td>
              <span class="adm-badge adm-badge--{{ $pub->statut_badge }}">{{ $pub->statut_label }}</span>
            </td>
            <td style="font-size:12.5px;color:#64748b">
              @if($pub->date_debut || $pub->date_fin)
                {{ $pub->date_debut?->format('d/m/Y') ?? '—' }}→{{ $pub->date_fin?->format('d/m/Y') ?? '∞' }}
              @else
                <span style="color:#94a3b8">Sans limite</span>
              @endif
            </td>
            <td style="font-size:13px;color:#64748b;white-space:nowrap">{{ $pub->created_at->format('d/m/Y') }}</td>
            <td>
              <a href="{{ route('admin.publicites.show', $pub) }}" class="adm-btn adm-btn--outline adm-btn--sm">Examiner</a>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
      <div style="padding:16px 24px">
        {{ $publicites->links() }}
      </div>
    @endif
  </div>
</div>
@endsection
