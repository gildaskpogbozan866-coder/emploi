@extends('layouts.app')
@section('title', 'Paiement en attente')

@section('content')
<style>
.pay-contact-btns { display: flex; gap: 10px; flex-wrap: wrap; }
@media (max-width: 480px) {
  .pay-contact-btns { flex-direction: column; }
  .pay-contact-btns > a, .pay-contact-btns > span { width: 100%; justify-content: center; }
}
</style>
<div style="min-height:75vh;background:#f8fafc;display:flex;align-items:center;justify-content:center;padding:40px 20px">
  <div style="text-align:center;max-width:480px">
    <div style="width:80px;height:80px;border-radius:50%;background:#fef9c3;display:flex;align-items:center;justify-content:center;margin:0 auto 24px">
      <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="#ca8a04" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
    </div>
    <h1 style="font-size:1.6rem;font-weight:800;color:#042C53;margin:0 0 12px">Paiement en attente !</h1>
    <p style="font-size:14px;color:#64748b;margin:0 0 16px;line-height:1.65">
      Votre paiement de <strong style="color:#042C53">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</strong> a bien été annulé.
    </p>
    @if($paiement->type === 'service')
      @php $serviceNom = $paiement->payable?->service?->nom ?? 'votre service'; @endphp
      <p style="font-size:14px;color:#16a34a;font-weight:600;margin:0 0 12px">
        Commande : <strong>{{ $serviceNom }}</strong>
      </p>
      <div style="background:#fffbeb;border:1.5px solid #fde68a;border-radius:12px;padding:14px 18px;margin-bottom:24px;text-align:left;max-width:380px;margin-left:auto;margin-right:auto">
        <p style="font-size:13.5px;color:#92400e;margin:0;line-height:1.65">
          <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="display:inline-block;vertical-align:-3px;margin-right:5px"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
          Finissez votre paiement pour recevoir votre service.
        </p>
      </div>
    @else
      <div style="background:#fffbeb;border:1.5px solid #fde68a;border-radius:12px;padding:16px 20px;margin-bottom:28px;text-align:left">
        <p style="font-size:13.5px;color:#92400e;margin:0;line-height:1.6">
                    Finissez votre paiement pour recevoir votre service.

        </p>
      </div>
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
      @if($paiementsRoute)
      <a href="{{ $paiementsRoute }}" style="display:inline-flex;align-items:center;gap:8px;padding:12px 24px;background:#185FA5;color:#fff;border-radius:10px;font-weight:700;font-size:13.5px;text-decoration:none">
        Mes paiements
      </a>
      @endif
      <a href="{{ $dashRoute }}" style="display:inline-flex;align-items:center;gap:8px;padding:12px 24px;background:#f1f5f9;color:#042C53;border-radius:10px;font-weight:700;font-size:13.5px;text-decoration:none">
        {{ auth()->check() ? 'Tableau de bord' : 'Retour à l\'accueil' }}
      </a>
    </div>

    @if($paiement->type === 'service')
    <div style="margin-top:20px;padding-top:20px;border-top:1px solid #e2e8f0">
      <p style="font-size:13px;color:#64748b;margin:0 0 12px">Une question sur votre commande ?</p>
      <div class="pay-contact-btns">
      <a href="mailto:contact@emploibougebenin.com?subject={{ urlencode('Question sur ma commande (réf. ' . $paiement->reference . ')') }}&body={{ urlencode('Bonjour, je viens de commander « ' . ($paiement->payable?->service?->nom ?? 'un service') . ' » (réf. ' . $paiement->reference . '). J\'aimerais avoir des informations.') }}"
         style="display:inline-flex;align-items:center;gap:10px;background:#185FA5;color:#fff;padding:12px 24px;border-radius:10px;font-weight:700;font-size:14px;text-decoration:none">
        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        Contacter par email
      </a>
      <span style="display:inline-flex;align-items:center;gap:10px;background:#f0fdf4;border:1.5px solid #bbf7d0;color:#16a34a;padding:12px 24px;border-radius:10px;font-weight:700;font-size:14px">
        <svg width="18" height="18" fill="currentColor" viewBox="0 0 24 24"><path d="M12.04 2c-5.52 0-10 4.48-10 10 0 1.77.46 3.5 1.34 5.02L2 22l5.13-1.34A9.96 9.96 0 0012.04 22c5.52 0 10-4.48 10-10s-4.48-10-10-10zm0 18.2c-1.6 0-3.16-.43-4.52-1.24l-.32-.19-3.35.88.89-3.27-.21-.33a8.17 8.17 0 01-1.26-4.35c0-4.52 3.68-8.2 8.2-8.2 2.19 0 4.25.85 5.79 2.4a8.13 8.13 0 012.4 5.8c0 4.52-3.68 8.2-8.2 8.2zm4.5-6.14c-.25-.12-1.46-.72-1.68-.8-.23-.08-.39-.12-.56.12-.16.25-.64.8-.78.96-.14.16-.29.18-.53.06-.25-.12-1.04-.38-1.98-1.22-.73-.65-1.23-1.46-1.37-1.7-.14-.25-.02-.38.11-.5.11-.11.25-.29.37-.43.12-.15.16-.25.24-.41.08-.16.04-.31-.02-.43-.06-.12-.56-1.35-.77-1.85-.2-.48-.41-.42-.56-.43-.14-.01-.31-.01-.47-.01-.16 0-.43.06-.65.31-.23.25-.86.84-.86 2.05 0 1.2.88 2.37 1 2.53.12.16 1.73 2.64 4.19 3.7.59.25 1.04.4 1.4.51.59.19 1.12.16 1.54.1.47-.07 1.46-.6 1.66-1.18.21-.58.21-1.07.14-1.18-.06-.1-.22-.16-.47-.28z"/></svg>
        WhatsApp : +229 42 00 43 72
      </span>
      </div>
    </div>
    @endif
    <p style="margin-top:20px;font-size:12px;color:#94a3b8">Référence : <code>{{ $paiement->reference }}</code></p>
  </div>
</div>
@endsection
