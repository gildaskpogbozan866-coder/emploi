@extends('layouts.app')
@section('title', 'Paiement confirmé')

@section('content')
<div style="min-height:75vh;background:#f8fafc;display:flex;align-items:center;justify-content:center;padding:40px 20px">
  <div style="text-align:center;max-width:460px">
    <div style="width:80px;height:80px;border-radius:50%;background:#dcfce7;display:flex;align-items:center;justify-content:center;margin:0 auto 24px">
      <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="#16a34a" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
    </div>
    <h1 style="font-size:1.6rem;font-weight:800;color:#042C53;margin:0 0 12px">Paiement confirmé !</h1>
    <p style="font-size:14px;color:#64748b;margin:0 0 8px;line-height:1.65">
      Votre paiement de <strong style="color:#042C53">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</strong> a été reçu.
    </p>
    @if($paiement->type === 'cv_credits')
      <p style="font-size:14px;color:#16a34a;font-weight:600;margin:0 0 28px">
        {{ $paiement->credits_cv }} crédit{{ $paiement->credits_cv > 1 ? 's' : '' }} CVthèque ajouté{{ $paiement->credits_cv > 1 ? 's' : '' }} à votre compte.
      </p>
    @elseif($paiement->type === 'service')
      @php $serviceNom = $paiement->payable?->service?->nom ?? 'votre service'; @endphp
      <p style="font-size:14px;color:#16a34a;font-weight:600;margin:0 0 12px">
        Votre commande <strong>{{ $serviceNom }}</strong> a bien été reçue.
      </p>
      <div style="background:#fffbeb;border:1.5px solid #fde68a;border-radius:12px;padding:14px 18px;margin-bottom:24px;text-align:left;max-width:380px;margin-left:auto;margin-right:auto">
        <p style="font-size:13.5px;color:#92400e;margin:0;line-height:1.65">
          <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display:inline-block;vertical-align:-3px;margin-right:5px"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          <strong>Livraison dans 1h.</strong> Notre équipe traite votre commande et vous livrera dans l'heure.
        </p>
      </div>
    @elseif($paiement->abonnement?->plan)
      <p style="font-size:14px;color:#16a34a;font-weight:600;margin:0 0 28px">
        Votre abonnement <strong>{{ $paiement->abonnement->plan->name }}</strong> est maintenant actif.
      </p>
    @else
      <p style="margin:0 0 28px"></p>
    @endif
    @php
      $user = auth()->user();
      if ($user) {
        $role = $user->role;
        $dashRoute = match($role) {
          'recruteur' => route('recruteur.dashboard'),
          'annonceur' => route('annonceur.dashboard'),
          'admin'     => route('admin.dashboard'),
          default     => route('candidat.dashboard'),
        };
        $paiementsRoute = match($role) {
          'recruteur' => route('recruteur.paiements'),
          'candidat'  => route('candidat.paiements'),
          default     => null,
        };
      } else {
        $dashRoute = route('home');
        $paiementsRoute = null;
      }
    @endphp
    <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
      <a href="{{ $dashRoute }}" style="display:inline-flex;align-items:center;gap:8px;padding:12px 24px;background:#185FA5;color:#fff;border-radius:10px;font-weight:700;font-size:13.5px;text-decoration:none">
        {{ auth()->check() ? 'Tableau de bord' : 'Retour à l\'accueil' }}
      </a>
      @if($paiementsRoute)
      <a href="{{ $paiementsRoute }}" style="display:inline-flex;align-items:center;gap:8px;padding:12px 24px;background:#f1f5f9;color:#042C53;border-radius:10px;font-weight:700;font-size:13.5px;text-decoration:none">
        Mes paiements
      </a>
      @endif
    </div>

    @if($paiement->type === 'service')
    <div style="margin-top:20px;padding-top:20px;border-top:1px solid #e2e8f0">
      <p style="font-size:13px;color:#64748b;margin:0 0 12px">Une question sur votre commande ?</p>
      <a href="mailto:contact@emploibougebenin.com?subject={{ urlencode('Question sur ma commande (réf. ' . $paiement->reference . ')') }}&body={{ urlencode('Bonjour, je viens de commander « ' . ($paiement->payable?->service?->nom ?? 'un service') . ' » (réf. ' . $paiement->reference . '). J\'aimerais avoir des informations.') }}"
         style="display:inline-flex;align-items:center;gap:10px;background:#185FA5;color:#fff;padding:12px 24px;border-radius:10px;font-weight:700;font-size:14px;text-decoration:none">
        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        Contacter par email
      </a>
    </div>
    @endif
    <p style="margin-top:20px;font-size:12px;color:#94a3b8">Référence : <code>{{ $paiement->reference }}</code></p>
  </div>
</div>
@endsection
