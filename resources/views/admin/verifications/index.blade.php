@extends('layouts.admin')
@section('title', 'Vérifications recruteurs')

@section('content')
<div class="dash-content">
  <div class="dash-content__header">
    <h1 class="dash-content__title">Vérifications recruteurs</h1>
    <p class="dash-content__sub">Dossiers soumis par les entreprises pour validation de leur compte.</p>
  </div>

  {{-- Compteurs globaux (indépendants des filtres) --}}
  <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:16px;margin-bottom:20px">
    <div style="background:#fffbeb;border:1px solid #fde68a;border-radius:10px;padding:16px;text-align:center">
      <div style="font-size:1.6rem;font-weight:700;color:#d97706">{{ $counts['en_attente'] }}</div>
      <div style="font-size:.82rem;color:#92400e;margin-top:4px">En attente</div>
    </div>
    <div style="background:#d1fae5;border:1px solid #6ee7b7;border-radius:10px;padding:16px;text-align:center">
      <div style="font-size:1.6rem;font-weight:700;color:#059669">{{ $counts['approuve'] }}</div>
      <div style="font-size:.82rem;color:#065f46;margin-top:4px">Approuvés</div>
    </div>
    <div style="background:#fef2f2;border:1px solid #fca5a5;border-radius:10px;padding:16px;text-align:center">
      <div style="font-size:1.6rem;font-weight:700;color:#dc2626">{{ $counts['rejete'] }}</div>
      <div style="font-size:.82rem;color:#7f1d1d;margin-top:4px">Rejetés</div>
    </div>
  </div>

  @include('partials._search-bar', [
    'route'       => 'admin.verifications.list',
    'placeholder' => 'Nom, entreprise ou email…',
    'filters'     => [
      ['name' => 'statut', 'label' => 'Tous les statuts', 'options' => ['en_attente' => 'En attente', 'approuve' => 'Approuvé', 'rejete' => 'Rejeté']],
    ],
  ])

  <div style="background:#fff;border-radius:12px;border:1px solid #e5e7eb;overflow:hidden">
    <table style="width:100%;border-collapse:collapse;font-size:.88rem">
      <thead>
        <tr style="background:#f8fafc;border-bottom:2px solid #e5e7eb">
          <th style="padding:12px 16px;text-align:left;font-weight:600;color:#374151">Recruteur</th>
          <th style="padding:12px 16px;text-align:left;font-weight:600;color:#374151">Entreprise</th>
          <th style="padding:12px 16px;text-align:left;font-weight:600;color:#374151">Soumis le</th>
          <th style="padding:12px 16px;text-align:center;font-weight:600;color:#374151">Statut</th>
          <th style="padding:12px 16px;text-align:center;font-weight:600;color:#374151">Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($verifications as $v)
        <tr style="border-bottom:1px solid #f1f5f9;{{ $loop->even ? 'background:#fafafa' : '' }}">
          <td style="padding:12px 16px">
            <div style="font-weight:600;color:#111827">{{ $v->user->prenom }} {{ $v->user->nom }}</div>
            <div style="font-size:.78rem;color:#6b7280">{{ $v->user->email }}</div>
          </td>
          <td style="padding:12px 16px;color:#374151">{{ $v->user->entreprise ?? '—' }}</td>
          <td style="padding:12px 16px;color:#6b7280;font-size:.82rem">{{ $v->created_at->format('d/m/Y H:i') }}</td>
          <td style="padding:12px 16px;text-align:center">
            @if($v->statut === 'en_attente')
              <span style="background:#fef3c7;color:#d97706;padding:3px 10px;border-radius:20px;font-size:.78rem;font-weight:600">En attente</span>
            @elseif($v->statut === 'approuve')
              <span style="background:#d1fae5;color:#059669;padding:3px 10px;border-radius:20px;font-size:.78rem;font-weight:600">Approuvé</span>
            @else
              <span style="background:#fef2f2;color:#dc2626;padding:3px 10px;border-radius:20px;font-size:.78rem;font-weight:600">Rejeté</span>
            @endif
          </td>
          <td style="padding:12px 16px;text-align:center">
            <a href="{{ route('admin.verifications.show', $v) }}"
               style="background:#2563eb;color:#fff;padding:6px 14px;border-radius:6px;font-size:.8rem;text-decoration:none;font-weight:600">
              Examiner
            </a>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" style="padding:40px;text-align:center;color:#6b7280">Aucun dossier soumis pour l'instant.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div style="margin-top:20px">
    {{ $verifications->links() }}
  </div>
</div>
@endsection
