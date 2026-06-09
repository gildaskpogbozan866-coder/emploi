@extends('layouts.admin')
@section('title', 'Détail paiement — Administration')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <a href="{{ route('admin.paiements.list') }}" class="adm-btn adm-btn--outline adm-btn--sm" style="margin-bottom:8px"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg> Retour</a>
    <h1>Paiement #{{ $paiement->reference }}</h1>
    <p>Créé le {{ $paiement->created_at->format('d/m/Y à H:i') }}</p>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;max-width:900px">
  <div class="adm-card">
    <div style="padding:22px 24px;border-bottom:1px solid #f1f5f9">
      <h3 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0">Informations</h3>
    </div>
    <div style="padding:22px 24px;display:flex;flex-direction:column;gap:14px">
      <div><p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Référence</p><p style="font-weight:600;color:#042C53;font-family:monospace;margin:0">{{ $paiement->reference }}</p></div>
      <div><p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Type</p><p style="font-weight:600;color:#042C53;margin:0">{{ ucfirst(str_replace(['abonnement_','_'],' ',$paiement->type)) }}</p></div>
      <div><p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Montant</p><p style="font-size:1.2rem;font-weight:800;color:#185FA5;margin:0">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</p></div>
      <div><p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Méthode</p><p style="font-weight:600;color:#042C53;margin:0">{{ ucfirst(str_replace('_',' ',$paiement->methode ?? '—')) }}</p></div>
    </div>
  </div>

  <div class="adm-card">
    <div style="padding:22px 24px;border-bottom:1px solid #f1f5f9">
      <h3 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0">Utilisateur</h3>
    </div>
    <div style="padding:22px 24px;display:flex;flex-direction:column;gap:14px">
      <div><p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Nom</p><p style="font-weight:600;color:#042C53;margin:0">{{ $paiement->user->nom_complet }}</p></div>
      <div><p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Email</p><p style="font-weight:600;color:#042C53;margin:0">{{ $paiement->user->email }}</p></div>
      <div><p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Rôle</p><p style="font-weight:600;color:#042C53;margin:0">{{ ucfirst($paiement->user->role) }}</p></div>
    </div>
  </div>
</div>

<div class="adm-card" style="max-width:900px;margin-top:20px">
  <div style="padding:22px 24px;border-bottom:1px solid #f1f5f9">
    <h3 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0">Modifier le statut</h3>
  </div>
  <div style="padding:22px 24px">
    <form method="POST" action="{{ route('admin.paiements.statut', $paiement) }}" style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap">
      @csrf @method('PATCH')
      <div>
        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Statut actuel</label>
        <select name="statut" class="adm-select" style="min-width:180px">
          @foreach(['en_attente' => 'En attente','confirme' => 'Confirmé','echec' => 'Échec','rembourse' => 'Remboursé'] as $val => $label)
            <option value="{{ $val }}" {{ $paiement->statut === $val ? 'selected' : '' }}>{{ $label }}</option>
          @endforeach
        </select>
      </div>
      <button type="submit" class="adm-btn adm-btn--primary">Mettre à jour</button>
    </form>
    <p style="font-size:12px;color:#94a3b8;margin:10px 0 0">La confirmation d'un paiement active automatiquement le statut Premium de l'utilisateur.</p>
  </div>
</div>
@endsection
