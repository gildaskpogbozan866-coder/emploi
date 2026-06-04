@extends('layouts.admin')
@section('title', 'Profil candidat — Administration')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <a href="{{ route('admin.utilisateurs.candidats') }}" class="adm-btn adm-btn--outline adm-btn--sm" style="margin-bottom:8px">← Retour</a>
    <h1>{{ $user->nom_complet }}</h1>
    <p>Candidat · Inscrit le {{ $user->created_at->format('d/m/Y') }}</p>
  </div>
  <div style="display:flex;gap:10px">
    <form method="POST" action="{{ route('admin.utilisateurs.statut', $user) }}">
      @csrf @method('PATCH')
      <button type="submit" class="adm-btn adm-btn--{{ $user->actif ? 'danger' : 'primary' }}">
        {{ $user->actif ? 'Suspendre' : 'Réactiver' }}
      </button>
    </form>
    <form method="POST" action="{{ route('admin.utilisateurs.destroy', $user) }}" onsubmit="return confirm('Supprimer définitivement ce compte ?')">
      @csrf @method('DELETE')
      <button type="submit" class="adm-btn adm-btn--danger">Supprimer</button>
    </form>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px">
  <div class="adm-card">
    <div style="padding:20px 24px;border-bottom:1px solid #f1f5f9">
      <h3 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0">Informations personnelles</h3>
    </div>
    <div style="padding:20px 24px;display:flex;flex-direction:column;gap:12px">
      <div><p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Nom complet</p><p style="font-weight:600;color:#042C53;margin:0">{{ $user->nom_complet }}</p></div>
      <div><p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Email</p><p style="font-weight:600;color:#042C53;margin:0">{{ $user->email }}</p></div>
      <div><p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Téléphone</p><p style="font-weight:600;color:#042C53;margin:0">{{ $user->tel ?? '—' }}</p></div>
      <div><p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Pays</p><p style="font-weight:600;color:#042C53;margin:0">{{ $user->pays ?? '—' }}</p></div>
      <div><p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Statut Premium</p>
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
      <h3 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0">CVs déposés ({{ $user->cvs->count() }})</h3>
    </div>
    <div style="padding:20px 24px">
      @forelse($user->cvs as $cv)
        <div style="padding:10px 0;border-bottom:1px solid #f1f5f9">
          <p style="font-weight:600;color:#042C53;margin:0 0 2px">{{ $cv->titre_poste }}</p>
          <p style="font-size:12px;color:#94a3b8;margin:0">{{ $cv->pays }}{{ $cv->ville ? ' · '.$cv->ville : '' }} · {{ ucfirst($cv->plan) }}</p>
        </div>
      @empty
        <p style="color:#94a3b8;font-size:13.5px">Aucun CV déposé.</p>
      @endforelse
    </div>
  </div>
</div>

<div class="adm-card" style="margin-top:20px">
  <div style="padding:20px 24px;border-bottom:1px solid #f1f5f9">
    <h3 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0">Candidatures ({{ $user->candidatures->count() }})</h3>
  </div>
  <div class="adm-table-wrap">
    <table class="adm-table">
      <thead><tr><th>Offre</th><th>Entreprise</th><th>Statut</th><th>Date</th></tr></thead>
      <tbody>
        @forelse($user->candidatures as $candidature)
        <tr>
          <td style="font-weight:500;color:#042C53">{{ $candidature->offre->titre }}</td>
          <td style="color:#64748b">{{ $candidature->offre->entreprise }}</td>
          <td>
            <span class="adm-badge adm-badge--{{ match($candidature->statut) {
              'retenue'  => 'green',
              'refusee'  => 'red',
              'entretien'=> 'violet',
              'vue'      => 'blue',
              default    => 'gray'
            } }}">{{ ucfirst(str_replace('_',' ',$candidature->statut)) }}</span>
          </td>
          <td style="color:#94a3b8;font-size:12px">{{ $candidature->created_at->format('d/m/Y') }}</td>
        </tr>
        @empty
        <tr><td colspan="4" style="text-align:center;color:#94a3b8;padding:20px">Aucune candidature.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="adm-card" style="margin-top:20px">
  <div style="padding:20px 24px;border-bottom:1px solid #f1f5f9">
    <h3 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0">Historique abonnements</h3>
  </div>
  <div class="adm-table-wrap">
    <table class="adm-table">
      <thead><tr><th>Plan</th><th>Type</th><th>Prix</th><th>Début</th><th>Expiration</th><th>Statut</th></tr></thead>
      <tbody>
        @forelse($user->abonnements as $ab)
        <tr>
          <td style="font-weight:600">{{ ucfirst(str_replace('_',' ',$ab->plan)) }}</td>
          <td>{{ ucfirst($ab->type) }}</td>
          <td>{{ $ab->prix > 0 ? number_format($ab->prix,0,',',' ').' FCFA' : 'Gratuit' }}</td>
          <td style="color:#64748b;font-size:12px">{{ $ab->debut_le?->format('d/m/Y') ?? '—' }}</td>
          <td style="color:#64748b;font-size:12px">{{ $ab->expire_le?->format('d/m/Y') ?? 'Illimité' }}</td>
          <td><span class="adm-badge adm-badge--{{ $ab->statut === 'actif' ? 'green' : 'gray' }}">{{ ucfirst($ab->statut) }}</span></td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;color:#94a3b8;padding:20px">Aucun abonnement.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
