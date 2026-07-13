@extends('layouts.admin')
@section('title', 'Offrir un abonnement | Administration')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <h1>Offrir un abonnement</h1>
    <p>Active immédiatement un abonnement pour un utilisateur, sans passer par un paiement.</p>
  </div>
  <a href="{{ route('admin.abonnements') }}" class="adm-btn adm-btn--outline adm-btn--sm">
    ← Retour aux abonnements
  </a>
</div>

<div class="adm-card" style="max-width:640px;padding:24px 26px">
  <p style="margin:0 0 22px;font-size:13px;color:#64748b">
    Un email et une notification in-app sont envoyés à l'utilisateur dès l'activation. Un abonnement déjà
    en cours (ou déjà programmé) n'est jamais écrasé — celui-ci prendra le relais à son expiration.
  </p>

  <form method="POST" action="{{ route('admin.abonnements.store') }}">
    @csrf

    <div style="margin-bottom:18px">
      <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Email de l'utilisateur</label>
      <input type="email" name="email" required value="{{ old('email') }}" placeholder="utilisateur@email.com"
             class="adm-input" style="width:100%;box-sizing:border-box">
      @error('email')
        <p style="font-size:12px;color:#dc2626;margin:6px 0 0">{{ $message }}</p>
      @enderror
    </div>

    <div style="margin-bottom:22px">
      <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Plan</label>
      <select name="plan_id" required class="adm-select" style="width:100%;box-sizing:border-box">
        <option value="">Choisir un plan…</option>
        @foreach($plansDisponibles as $p)
          <option value="{{ $p->id }}" {{ (string) old('plan_id') === (string) $p->id ? 'selected' : '' }}>
            {{ $p->name }} — {{ match($p->target_type) { 'candidat' => 'Candidat', 'recruteur' => 'Recruteur', 'annonceur' => 'Annonceur', 'both' => 'Tous', default => $p->target_type } }} —
            {{ $p->is_free ? 'Gratuit' : number_format($p->price, 0, ',', ' ').' '.$p->currency }}
          </option>
        @endforeach
      </select>
      @error('plan_id')
        <p style="font-size:12px;color:#dc2626;margin:6px 0 0">{{ $message }}</p>
      @enderror
    </div>

    <div style="display:flex;justify-content:flex-end;gap:10px">
      <a href="{{ route('admin.abonnements') }}" class="adm-btn adm-btn--outline">Annuler</a>
      <button type="submit" class="adm-btn adm-btn--primary">Activer l'abonnement</button>
    </div>
  </form>
</div>
@endsection
