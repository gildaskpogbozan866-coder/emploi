@extends('layouts.admin')
@section('title', 'Détail paiement — Administration')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <a href="{{ route('admin.paiements.list') }}" class="adm-btn adm-btn--outline adm-btn--sm" style="margin-bottom:8px">
      <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
      Retour
    </a>
    <h1>Paiement #{{ $paiement->reference }}</h1>
    <p>Créé le {{ $paiement->created_at->format('d/m/Y à H:i') }}</p>
  </div>
  <span class="adm-badge adm-badge--{{ match($paiement->statut) {
    'confirme'   => 'green',
    'en_attente' => 'yellow',
    'echec'      => 'red',
    'rembourse'  => 'gray',
    default      => 'gray'
  } }}" style="font-size:14px;padding:8px 16px">
    {{ match($paiement->statut) {
      'confirme'   => 'Confirmé',
      'en_attente' => 'En attente',
      'echec'      => 'Échec',
      'rembourse'  => 'Remboursé',
      default      => $paiement->statut
    } }}
  </span>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;max-width:900px">

  {{-- Informations financières --}}
  <div class="adm-card">
    <div style="padding:22px 24px;border-bottom:1px solid #f1f5f9">
      <h3 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0">Informations financières</h3>
    </div>
    <div style="padding:22px 24px;display:flex;flex-direction:column;gap:14px">
      <div>
        <p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Référence interne</p>
        <p style="font-weight:600;color:#042C53;font-family:monospace;margin:0">{{ $paiement->reference }}</p>
      </div>
      @if($paiement->transaction_reference)
      <div>
        <p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Référence transaction opérateur</p>
        <p style="font-weight:600;color:#042C53;font-family:monospace;margin:0">{{ $paiement->transaction_reference }}</p>
      </div>
      @endif
      <div>
        <p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Montant</p>
        <p style="font-size:1.2rem;font-weight:800;color:#185FA5;margin:0">
          {{ number_format($paiement->montant, 0, ',', ' ') }} {{ $paiement->devise ?? 'FCFA' }}
        </p>
      </div>
      <div>
        <p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Méthode de paiement</p>
        <p style="font-weight:600;color:#042C53;margin:0">{{ ucfirst(str_replace('_', ' ', $paiement->methode ?? '—')) }}</p>
      </div>
      @if($paiement->type)
      <div>
        <p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Type</p>
        <p style="font-weight:600;color:#042C53;margin:0">{{ ucfirst(str_replace(['abonnement_','_'], ['','  '], $paiement->type)) }}</p>
      </div>
      @endif
      <div>
        <p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Date de création</p>
        <p style="font-weight:600;color:#042C53;margin:0">{{ $paiement->created_at->format('d/m/Y à H:i') }}</p>
      </div>
      @if($paiement->paid_at)
      <div>
        <p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Date de confirmation</p>
        <p style="font-weight:600;color:#16a34a;margin:0">{{ $paiement->paid_at->format('d/m/Y à H:i') }}</p>
      </div>
      @endif
      @if($paiement->notes)
      <div>
        <p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Notes</p>
        <p style="color:#374151;margin:0;font-size:13px">{{ $paiement->notes }}</p>
      </div>
      @endif
    </div>
  </div>

  {{-- Utilisateur --}}
  <div class="adm-card">
    <div style="padding:22px 24px;border-bottom:1px solid #f1f5f9">
      <h3 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0">Utilisateur</h3>
    </div>
    <div style="padding:22px 24px;display:flex;flex-direction:column;gap:14px">
      @if($paiement->user)
        <div>
          <p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Nom complet</p>
          <p style="font-weight:600;color:#042C53;margin:0">{{ $paiement->user->nom_complet }}</p>
        </div>
        <div>
          <p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Email</p>
          <p style="font-weight:600;color:#042C53;margin:0">{{ $paiement->user->email }}</p>
        </div>
        <div>
          <p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Rôle</p>
          <p style="font-weight:600;color:#042C53;margin:0">{{ ucfirst($paiement->user->role ?? '—') }}</p>
        </div>
      @else
        <p style="color:#94a3b8;font-style:italic;margin:0">Utilisateur supprimé.</p>
      @endif
    </div>
  </div>
</div>

{{-- Abonnement lié --}}
@if($paiement->abonnement)
<div class="adm-card" style="max-width:900px;margin-top:20px">
  <div style="padding:22px 24px;border-bottom:1px solid #f1f5f9">
    <h3 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0">Abonnement associé</h3>
  </div>
  <div style="padding:22px 24px;display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:14px">
    <div>
      <p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Plan</p>
      <p style="font-weight:700;color:#042C53;margin:0">{{ $paiement->abonnement->plan?->name ?? '—' }}</p>
    </div>
    <div>
      <p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Statut abonnement</p>
      <span class="adm-badge adm-badge--{{ match($paiement->abonnement->status) {
        'active'    => 'green',
        'expired'   => 'gray',
        'cancelled' => 'red',
        default     => 'gray'
      } }}">
        {{ match($paiement->abonnement->status) {
          'active'    => 'Actif',
          'expired'   => 'Expiré',
          'cancelled' => 'Annulé',
          default     => $paiement->abonnement->status
        } }}
      </span>
    </div>
    <div>
      <p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Début</p>
      <p style="font-weight:600;color:#042C53;margin:0">{{ $paiement->abonnement->starts_at?->format('d/m/Y') ?? '—' }}</p>
    </div>
    <div>
      <p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Expiration</p>
      <p style="font-weight:600;color:#042C53;margin:0">
        {{ $paiement->abonnement->ends_at?->format('d/m/Y') ?? 'Illimité' }}
      </p>
    </div>
  </div>
</div>
@endif

{{-- Modifier le statut --}}
<div class="adm-card" style="max-width:900px;margin-top:20px">
  <div style="padding:22px 24px;border-bottom:1px solid #f1f5f9">
    <h3 style="font-size:13px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:#94a3b8;margin:0">Modifier le statut</h3>
  </div>
  <div style="padding:22px 24px">
    <form method="POST" action="{{ route('admin.paiements.statut', $paiement) }}" style="display:flex;gap:12px;align-items:flex-end;flex-wrap:wrap">
      @csrf @method('PATCH')
      <div>
        <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Nouveau statut</label>
        <select name="statut" class="adm-select" style="min-width:180px">
          <option value="en_attente" {{ $paiement->statut === 'en_attente' ? 'selected' : '' }}>En attente</option>
          <option value="confirme"   {{ $paiement->statut === 'confirme'   ? 'selected' : '' }}>Confirmé</option>
          <option value="echec"      {{ $paiement->statut === 'echec'      ? 'selected' : '' }}>Échec</option>
          <option value="rembourse"  {{ $paiement->statut === 'rembourse'  ? 'selected' : '' }}>Remboursé</option>
        </select>
      </div>
      <button type="submit" class="adm-btn adm-btn--yellow">Mettre à jour</button>
    </form>
    @if($paiement->subscription_id)
      <p style="font-size:12px;color:#94a3b8;margin:10px 0 0">
        Passer en <strong>Confirmé</strong> activera automatiquement l'abonnement et calculera la date d'expiration.<br>
        Passer en <strong>Échec</strong> ou <strong>Remboursé</strong> annulera l'abonnement.
      </p>
    @endif
  </div>
</div>
@endsection
