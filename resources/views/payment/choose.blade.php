@extends('layouts.app')
@section('title', 'Choisir le mode de paiement')

@section('content')
<div style="min-height:80vh;background:#f8fafc;display:flex;align-items:center;justify-content:center;padding:40px 20px">
  <div style="width:100%;max-width:520px">

    {{-- En-tête --}}
    <div style="text-align:center;margin-bottom:32px">
      <div style="width:56px;height:56px;border-radius:16px;background:linear-gradient(135deg,#042C53,#185FA5);display:flex;align-items:center;justify-content:center;margin:0 auto 16px">
        <svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="#fff" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
      </div>
      <h1 style="font-size:1.5rem;font-weight:800;color:#042C53;margin:0 0 8px">Finaliser le paiement</h1>
      <p style="font-size:14px;color:#64748b;margin:0">Choisissez votre mode de paiement préféré</p>
    </div>

    {{-- Récapitulatif --}}
    <div style="background:linear-gradient(135deg,#042C53,#185FA5);border-radius:14px;padding:20px 24px;margin-bottom:24px;display:flex;align-items:center;justify-content:space-between">
      <div>
        <p style="font-size:12px;color:rgba(255,255,255,.6);margin:0 0 4px;text-transform:uppercase;letter-spacing:.06em">
          {{ $paiement->type === 'cv_credits' ? 'Crédits CVthèque' : 'Abonnement' }}
        </p>
        <p style="font-size:1.1rem;font-weight:700;color:#fff;margin:0">
          @if($paiement->type === 'cv_credits')
            {{ $paiement->credits_cv }} crédit{{ $paiement->credits_cv > 1 ? 's' : '' }}
          @else
            {{ $paiement->abonnement?->plan?->name ?? 'Abonnement' }}
          @endif
        </p>
      </div>
      <div style="text-align:right">
        <p style="font-size:12px;color:rgba(255,255,255,.6);margin:0 0 2px">Montant</p>
        <p style="font-size:1.6rem;font-weight:900;color:#F5C842;margin:0">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</p>
      </div>
    </div>

    @if(session('error'))
      <div style="background:#fee2e2;border:1px solid #fecaca;border-radius:10px;padding:12px 16px;margin-bottom:16px;font-size:13px;color:#dc2626">
        {{ session('error') }}
      </div>
    @endif

    {{-- Choix gateway --}}
    <div style="display:flex;flex-direction:column;gap:12px;margin-bottom:24px">

      {{-- FedaPay --}}
      @if($gateways['fedapay']['available'])
      <form method="POST" action="{{ route('payment.initiate', $paiement) }}">
        @csrf
        <input type="hidden" name="gateway" value="fedapay">
        <button type="submit" style="width:100%;background:#fff;border:2px solid #e2e8f0;border-radius:14px;padding:18px 20px;display:flex;align-items:center;gap:16px;cursor:pointer;font-family:inherit;transition:border-color .15s;text-align:left" onmouseover="this.style.borderColor='#185FA5'" onmouseout="this.style.borderColor='#e2e8f0'">
          <div style="width:46px;height:46px;border-radius:10px;background:#f0f9ff;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg width="24" height="24" viewBox="0 0 40 40" fill="none"><rect width="40" height="40" rx="8" fill="#0057b7"/><text x="4" y="26" font-size="11" font-weight="800" fill="#fff" font-family="sans-serif">FEDA</text></svg>
          </div>
          <div style="flex:1">
            <p style="font-size:14.5px;font-weight:700;color:#042C53;margin:0 0 2px">FedaPay</p>
            <p style="font-size:12px;color:#94a3b8;margin:0">Mobile Money · Carte bancaire (Visa, Mastercard)</p>
          </div>
          <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 18l6-6-6-6"/></svg>
        </button>
      </form>
      @endif

      {{-- KKiaPay --}}
      @if($gateways['kkiapay']['available'])
      <div id="kkiapay-trigger" style="background:#fff;border:2px solid #e2e8f0;border-radius:14px;padding:18px 20px;display:flex;align-items:center;gap:16px;cursor:pointer;transition:border-color .15s" onmouseover="this.style.borderColor='#185FA5'" onmouseout="this.style.borderColor='#e2e8f0'">
        <div style="width:46px;height:46px;border-radius:10px;background:#fff7ed;display:flex;align-items:center;justify-content:center;flex-shrink:0">
          <svg width="24" height="24" viewBox="0 0 40 40" fill="none"><rect width="40" height="40" rx="8" fill="#f97316"/><text x="5" y="26" font-size="10" font-weight="800" fill="#fff" font-family="sans-serif">KKIA</text></svg>
        </div>
        <div style="flex:1">
          <p style="font-size:14.5px;font-weight:700;color:#042C53;margin:0 0 2px">KKiaPay</p>
          <p style="font-size:12px;color:#94a3b8;margin:0">Mobile Money (MTN, Moov, Celtiis)</p>
        </div>
        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 18l6-6-6-6"/></svg>
      </div>
      @endif

      {{-- Manuel --}}
      <form method="POST" action="{{ route('payment.initiate', $paiement) }}">
        @csrf
        <input type="hidden" name="gateway" value="manuel">
        <button type="submit" style="width:100%;background:#fff;border:2px dashed #e2e8f0;border-radius:14px;padding:18px 20px;display:flex;align-items:center;gap:16px;cursor:pointer;font-family:inherit;text-align:left" onmouseover="this.style.borderColor='#94a3b8'" onmouseout="this.style.borderColor='#e2e8f0'">
          <div style="width:46px;height:46px;border-radius:10px;background:#f8fafc;display:flex;align-items:center;justify-content:center;flex-shrink:0">
            <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#64748b" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
          </div>
          <div style="flex:1">
            <p style="font-size:14.5px;font-weight:700;color:#042C53;margin:0 0 2px">Virement / Manuel</p>
            <p style="font-size:12px;color:#94a3b8;margin:0">Un conseiller vous contactera sous 24h</p>
          </div>
          <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#94a3b8" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 18l6-6-6-6"/></svg>
        </button>
      </form>

    </div>

    <p style="text-align:center;font-size:12px;color:#cbd5e1">
      Paiements sécurisés · Vos données sont protégées
    </p>

  </div>
</div>
@endsection

@if($gateways['kkiapay']['available'] && $kkiaConfig)
@section('scripts')
<script src="https://cdn.kkiapay.me/k.js"></script>
<script>
const kkiaConfig = @json($kkiaConfig);
const paiementId = {{ $paiement->id }};

document.getElementById('kkiapay-trigger').addEventListener('click', function () {
  openKkiapayWidget({
    amount:    kkiaConfig.amount,
    name:      kkiaConfig.name,
    email:     kkiaConfig.email,
    phone:     kkiaConfig.phone,
    key:       kkiaConfig.publicApiKey,
    sandbox:   kkiaConfig.sandbox,
    theme:     kkiaConfig.theme,
    data:      JSON.stringify(kkiaConfig.data),
    callback:  kkiaConfig.callback,
  });
});

addSuccessListener(function (response) {
  fetch('{{ route("payment.callback.kkiapay") }}', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({
      transactionId: response.transactionId,
      paiement_id:   paiementId,
    }),
  })
  .then(r => r.json())
  .then(data => {
    if (data.redirect) window.location.href = data.redirect;
  })
  .catch(() => window.location.reload());
});

addFailedListener(function () {
  window.location.href = '{{ route("payment.failed", $paiement) }}';
});
</script>
@endsection
@endif
