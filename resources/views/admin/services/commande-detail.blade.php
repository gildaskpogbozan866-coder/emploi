@extends('layouts.admin')
@section('title', 'Détail commande — Administration')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <a href="{{ route('admin.commandes.list') }}" class="adm-btn adm-btn--outline adm-btn--sm" style="margin-bottom:8px">← Retour</a>
    <h1>Commande #{{ $commande->reference }}</h1>
    <p>{{ $commande->service->nom }} · {{ $commande->created_at->format('d/m/Y à H:i') }}</p>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;max-width:900px">
  <div class="adm-card">
    <div style="padding:20px 24px;border-bottom:1px solid #f1f5f9">
      <h3 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0">Client</h3>
    </div>
    <div style="padding:20px 24px;display:flex;flex-direction:column;gap:12px">
      <div><p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Nom</p><p style="font-weight:600;color:#042C53;margin:0">{{ $commande->user->nom_complet }}</p></div>
      <div><p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Email</p><p style="font-weight:600;color:#042C53;margin:0">{{ $commande->user->email }}</p></div>
    </div>
  </div>

  <div class="adm-card">
    <div style="padding:20px 24px;border-bottom:1px solid #f1f5f9">
      <h3 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0">Commande</h3>
    </div>
    <div style="padding:20px 24px;display:flex;flex-direction:column;gap:12px">
      <div><p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Service</p><p style="font-weight:600;color:#042C53;margin:0">{{ $commande->service->nom }}</p></div>
      <div><p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Montant</p><p style="font-size:1.2rem;font-weight:800;color:#185FA5;margin:0">{{ number_format($commande->montant, 0, ',', ' ') }} FCFA</p></div>
      <div><p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Paiement</p>
        <span class="adm-badge adm-badge--{{ ($commande->paiement_statut ?? 'non_paye') === 'paye' ? 'green' : 'gray' }}">
          {{ ($commande->paiement_statut ?? 'non_paye') === 'paye' ? 'Payé' : 'Non payé' }}
        </span>
      </div>
    </div>
  </div>
</div>

<div class="adm-card" style="max-width:900px;margin-top:20px">
  <div style="padding:20px 24px;border-bottom:1px solid #f1f5f9">
    <h3 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0">Détails de la demande</h3>
  </div>
  <div style="padding:20px 24px">
    <p style="color:#374151;font-size:14px;line-height:1.65;margin:0">{{ $commande->details_demande }}</p>
  </div>
</div>

@if($commande->fichier_joint)
<div class="adm-card" style="max-width:900px;margin-top:20px;padding:20px 24px">
  <h4 style="font-size:13px;font-weight:700;text-transform:uppercase;color:#94a3b8;margin:0 0 12px">Fichier joint</h4>
  <a href="{{ asset('storage/'.$commande->fichier_joint) }}" target="_blank" class="adm-btn adm-btn--outline">
    Télécharger le fichier joint
  </a>
</div>
@endif

<div class="adm-card" style="max-width:900px;margin-top:20px">
  <div style="padding:20px 24px;border-bottom:1px solid #f1f5f9">
    <h3 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0">Modifier le statut</h3>
  </div>
  <div style="padding:20px 24px">
    <form method="POST" action="{{ route('admin.commandes.statut', $commande) }}" style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap">
      @csrf @method('PATCH')
      <div>
        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Statut</label>
        <select name="statut" class="adm-select" style="min-width:180px">
          @foreach(['en_attente' => 'En attente','en_cours' => 'En cours','livree' => 'Livrée','annulee' => 'Annulée'] as $val => $label)
            <option value="{{ $val }}" {{ $commande->statut === $val ? 'selected' : '' }}>{{ $label }}</option>
          @endforeach
        </select>
      </div>
      <button type="submit" class="adm-btn adm-btn--yellow">Mettre à jour</button>
    </form>
  </div>
</div>
@endsection
