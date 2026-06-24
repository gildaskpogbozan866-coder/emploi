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
      <a href="https://wa.me/22951929856?text={{ urlencode('Bonjour, je viens de commander « ' . ($paiement->payable?->service?->nom ?? 'un service') . ' » (réf. ' . $paiement->reference . '). J\'aimerais avoir des informations.') }}"
         target="_blank" rel="noopener noreferrer"
         style="display:inline-flex;align-items:center;gap:10px;background:#25D366;color:#fff;padding:12px 24px;border-radius:10px;font-weight:700;font-size:14px;text-decoration:none">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
        Contacter sur WhatsApp
      </a>
    </div>
    @endif
    <p style="margin-top:20px;font-size:12px;color:#94a3b8">Référence : <code>{{ $paiement->reference }}</code></p>
  </div>
</div>
@endsection
