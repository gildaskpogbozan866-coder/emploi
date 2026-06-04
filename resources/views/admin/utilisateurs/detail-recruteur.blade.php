@extends('layouts.admin')
@section('title', 'Profil recruteur — Administration')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <a href="{{ route('admin.utilisateurs.recruteurs') }}" class="adm-btn adm-btn--outline adm-btn--sm" style="margin-bottom:8px">← Retour</a>
    <h1>{{ $user->nom_complet }}</h1>
    <p>Recruteur{{ $user->entreprise ? ' · '.$user->entreprise : '' }} · Inscrit le {{ $user->created_at->format('d/m/Y') }}</p>
  </div>
  <div style="display:flex;gap:10px">
    <form method="POST" action="{{ route('admin.utilisateurs.statut', $user) }}">
      @csrf @method('PATCH')
      <button type="submit" class="adm-btn adm-btn--{{ $user->actif ? 'danger' : 'primary' }}">
        {{ $user->actif ? 'Suspendre' : 'Réactiver' }}
      </button>
    </form>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
  <div class="adm-card">
    <div style="padding:20px 24px;border-bottom:1px solid #f1f5f9">
      <h3 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0">Informations</h3>
    </div>
    <div style="padding:20px 24px;display:flex;flex-direction:column;gap:12px">
      <div><p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Nom complet</p><p style="font-weight:600;color:#042C53;margin:0">{{ $user->nom_complet }}</p></div>
      <div><p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Email</p><p style="font-weight:600;color:#042C53;margin:0">{{ $user->email }}</p></div>
      <div><p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Entreprise</p><p style="font-weight:600;color:#042C53;margin:0">{{ $user->entreprise ?? '—' }}</p></div>
      <div><p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Abonnement</p>
        @if($user->premium)
          <span class="adm-badge adm-badge--yellow">★ Premium</span>
        @else
          <span style="color:#94a3b8;font-size:12px">Gratuit</span>
        @endif
      </div>
      <div><p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Compte</p>
        <span class="adm-badge adm-badge--{{ $user->actif ? 'green' : 'red' }}">{{ $user->actif ? 'Actif' : 'Suspendu' }}</span>
      </div>
    </div>
  </div>

  <div class="adm-card">
    <div style="padding:20px 24px;border-bottom:1px solid #f1f5f9">
      <h3 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0">Résumé activité</h3>
    </div>
    <div style="padding:20px 24px;display:grid;grid-template-columns:1fr 1fr;gap:16px">
      <div style="text-align:center;padding:16px;background:#f8fafc;border-radius:10px">
        <p style="font-size:1.6rem;font-weight:800;color:#185FA5;margin:0">{{ $user->offres->count() }}</p>
        <p style="font-size:12px;color:#64748b;margin:4px 0 0">Offres publiées</p>
      </div>
      <div style="text-align:center;padding:16px;background:#f8fafc;border-radius:10px">
        <p style="font-size:1.6rem;font-weight:800;color:#38A169;margin:0">{{ $user->offres->sum(fn($o) => $o->candidatures_count) }}</p>
        <p style="font-size:12px;color:#64748b;margin:4px 0 0">Candidatures reçues</p>
      </div>
    </div>
  </div>
</div>

<div class="adm-card" style="margin-top:20px">
  <div style="padding:20px 24px;border-bottom:1px solid #f1f5f9">
    <h3 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0">Offres publiées ({{ $user->offres->count() }})</h3>
  </div>
  <div class="adm-table-wrap">
    <table class="adm-table">
      <thead><tr><th>Titre</th><th>Type</th><th>Candidatures</th><th>Statut</th><th>Date</th></tr></thead>
      <tbody>
        @forelse($user->offres as $offre)
        <tr>
          <td style="font-weight:500;color:#042C53">{{ $offre->titre }}</td>
          <td><span class="adm-badge adm-badge--blue">{{ $offre->type }}</span></td>
          <td><strong>{{ $offre->candidatures_count }}</strong></td>
          <td>
            <span class="adm-badge adm-badge--{{ match($offre->statut) {
              'active'     => 'green',
              'en_attente' => 'yellow',
              'expiree'    => 'gray',
              'suspendue'  => 'red',
              default      => 'gray'
            } }}">{{ ucfirst(str_replace('_',' ',$offre->statut)) }}</span>
          </td>
          <td style="color:#94a3b8;font-size:12px">{{ $offre->created_at->format('d/m/Y') }}</td>
        </tr>
        @empty
        <tr><td colspan="5" style="text-align:center;color:#94a3b8;padding:20px">Aucune offre publiée.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
