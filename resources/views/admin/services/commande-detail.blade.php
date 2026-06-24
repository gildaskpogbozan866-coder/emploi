@extends('layouts.admin')
@section('title', 'Détail commande | Administration')

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
    @php
      $clientNom   = $commande->user?->nom_complet ?? 'Invité';
      $clientEmail = $commande->user?->email ?? $commande->email_contact ?? '—';
      $clientPrenom = $commande->user?->prenom ?? '';
      $clientTel   = $commande->user?->tel ?? null;
    @endphp
    <div style="padding:20px 24px;display:flex;flex-direction:column;gap:12px">
      <div><p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Nom</p><p style="font-weight:600;color:#042C53;margin:0">{{ $clientNom }}</p></div>
      <div><p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Email</p><p style="font-weight:600;color:#042C53;margin:0">{{ $clientEmail }}</p></div>
      @if($clientTel)
      <div><p style="font-size:12px;color:#94a3b8;margin:0 0 2px">Téléphone</p><p style="font-weight:600;color:#042C53;margin:0">{{ $clientTel }}</p></div>
      @endif
      @if($clientTel)
      @php
        $telWa = preg_replace('/[^0-9]/', '', $clientTel);
        if (strlen($telWa) === 8) $telWa = '229' . $telWa;
        $msgWa = urlencode('Bonjour ' . $clientPrenom . ', nous avons bien reçu votre commande « ' . $commande->service->nom . ' » (réf. ' . $commande->reference . '). Notre équipe va vous contacter très prochainement.');
      @endphp
      <div style="padding-top:4px">
        <a href="https://wa.me/{{ $telWa }}?text={{ $msgWa }}"
           target="_blank" rel="noopener noreferrer"
           style="display:inline-flex;align-items:center;gap:7px;background:#25D366;color:#fff;padding:8px 16px;border-radius:8px;font-weight:700;font-size:13px;text-decoration:none">
          <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
          Contacter sur WhatsApp
        </a>
      </div>
      @endif
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
