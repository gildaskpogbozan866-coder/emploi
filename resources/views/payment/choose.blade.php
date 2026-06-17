@extends('layouts.app')
@section('title', 'Paiement')

@section('content')
<div style="min-height:80vh;background:#f0f4f8;display:flex;align-items:center;justify-content:center;padding:40px 20px">
  <div style="width:100%;max-width:480px">

    @php
      $isService  = $paiement->type === 'service';
      $isCvCredit = $paiement->type === 'cv_credits';
    @endphp

    {{-- En-tête --}}
    <div style="text-align:center;margin-bottom:28px">
      <div style="width:54px;height:54px;border-radius:16px;background:linear-gradient(135deg,#042C53,#185FA5);display:flex;align-items:center;justify-content:center;margin:0 auto 14px">
        <svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="#fff" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
      </div>
      <h1 style="font-size:1.45rem;font-weight:800;color:#042C53;margin:0 0 6px;letter-spacing:-0.02em">Choisir le moyen de paiement</h1>
      <p style="font-size:13.5px;color:#64748b;margin:0">Sécurisé · Instantané · Sans frais cachés</p>
    </div>

    {{-- Récapitulatif montant --}}
    <div style="background:linear-gradient(135deg,#042C53,#185FA5);border-radius:14px;padding:18px 22px;margin-bottom:22px;display:flex;align-items:center;justify-content:space-between">
      <div>
        <p style="font-size:11.5px;color:rgba(255,255,255,.55);margin:0 0 3px;text-transform:uppercase;letter-spacing:.07em">
          @if($isCvCredit) Crédits CVthèque
          @elseif($isService) Commande de service
          @else Abonnement
          @endif
        </p>
        <p style="font-size:1rem;font-weight:700;color:#fff;margin:0">
          @if($isCvCredit) {{ $paiement->credits_cv }} crédit{{ $paiement->credits_cv > 1 ? 's' : '' }}
          @elseif($isService) {{ $paiement->payable?->service?->nom ?? 'Service' }}
          @else {{ $paiement->abonnement?->plan?->name ?? 'Abonnement' }}
          @endif
        </p>
      </div>
      <div style="text-align:right">
        <p style="font-size:11.5px;color:rgba(255,255,255,.55);margin:0 0 2px">À payer</p>
        <p style="font-size:1.7rem;font-weight:900;color:#F5C842;margin:0;letter-spacing:-0.02em">
          {{ number_format($paiement->montant, 0, ',', ' ') }}<span style="font-size:1rem;font-weight:600;margin-left:4px">FCFA</span>
        </p>
      </div>
    </div>

    @if(session('error'))
      <div style="background:#fee2e2;border:1px solid #fecaca;border-radius:10px;padding:12px 16px;margin-bottom:16px;font-size:13px;color:#dc2626">
        {{ session('error') }}
      </div>
    @endif

    {{-- Méthodes de paiement --}}
    <div style="display:flex;flex-direction:column;gap:10px;margin-bottom:20px">

      {{-- ══ MTN Mobile Money ══ --}}
      @if($mobileVia === 'kkiapay')
        <div class="pay-card" id="btn-mtn" onclick="launchKkiaPay('mtn_money')">
          <div class="pay-card__icon pay-card__icon--white">
            <img src="{{ asset('images/payment/mtn.svg') }}" alt="MTN Mobile Money" style="width:40px;height:40px;object-fit:contain">
          </div>
          <div class="pay-card__body">
            <p class="pay-card__label">MTN Mobile Money</p>
            <p class="pay-card__sub">Numéro MTN Bénin (96, 97, 66, 67…)</p>
          </div>
          <svg class="pay-card__arrow" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 18l6-6-6-6"/></svg>
        </div>
      @elseif($mobileVia === 'fedapay')
        <form method="POST" action="{{ route('payment.initiate', $paiement) }}">
          @csrf
          <input type="hidden" name="gateway" value="fedapay">
          <input type="hidden" name="methode" value="mtn_money">
          <button type="submit" class="pay-card pay-card--btn">
            <div class="pay-card__icon pay-card__icon--white">
              <img src="{{ asset('images/payment/mtn.svg') }}" alt="MTN Mobile Money" style="width:40px;height:40px;object-fit:contain">
            </div>
            <div class="pay-card__body">
              <p class="pay-card__label">MTN Mobile Money</p>
              <p class="pay-card__sub">Numéro MTN Bénin (96, 97, 66, 67…)</p>
            </div>
            <svg class="pay-card__arrow" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 18l6-6-6-6"/></svg>
          </button>
        </form>
      @endif

      {{-- ══ Moov Money ══ --}}
      @if($mobileVia === 'kkiapay')
        <div class="pay-card" id="btn-moov" onclick="launchKkiaPay('moov_money')">
          <div class="pay-card__icon pay-card__icon--white">
            <img src="{{ asset('images/payment/moov.png') }}" alt="Moov Money" style="width:40px;height:40px;object-fit:contain">
          </div>
          <div class="pay-card__body">
            <p class="pay-card__label">Moov Money <span style="font-size:11px;color:#94a3b8;font-weight:500">(Flooz)</span></p>
            <p class="pay-card__sub">Numéro Moov Bénin (61, 62, 95…)</p>
          </div>
          <svg class="pay-card__arrow" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 18l6-6-6-6"/></svg>
        </div>
      @elseif($mobileVia === 'fedapay')
        <form method="POST" action="{{ route('payment.initiate', $paiement) }}">
          @csrf
          <input type="hidden" name="gateway" value="fedapay">
          <input type="hidden" name="methode" value="moov_money">
          <button type="submit" class="pay-card pay-card--btn">
            <div class="pay-card__icon pay-card__icon--white">
              <img src="{{ asset('images/payment/moov.png') }}" alt="Moov Money" style="width:40px;height:40px;object-fit:contain">
            </div>
            <div class="pay-card__body">
              <p class="pay-card__label">Moov Money <span style="font-size:11px;color:#94a3b8;font-weight:500">(Flooz)</span></p>
              <p class="pay-card__sub">Numéro Moov Bénin (61, 62, 95…)</p>
            </div>
            <svg class="pay-card__arrow" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 18l6-6-6-6"/></svg>
          </button>
        </form>
      @endif

      {{-- ══ Celtiis Money ══ --}}
      @if($mobileVia === 'kkiapay')
        <div class="pay-card" id="btn-celtiis" onclick="launchKkiaPay('celtiis_money')">
          <div class="pay-card__icon pay-card__icon--white">
            <img src="{{ asset('images/payment/celtiis.svg') }}" alt="Celtiis Money" style="width:40px;height:40px;object-fit:contain">
          </div>
          <div class="pay-card__body">
            <p class="pay-card__label">Celtiis Money</p>
            <p class="pay-card__sub">Numéro Celtiis Bénin</p>
          </div>
          <svg class="pay-card__arrow" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 18l6-6-6-6"/></svg>
        </div>
      @elseif($mobileVia === 'fedapay')
        <form method="POST" action="{{ route('payment.initiate', $paiement) }}">
          @csrf
          <input type="hidden" name="gateway" value="fedapay">
          <input type="hidden" name="methode" value="celtiis_money">
          <button type="submit" class="pay-card pay-card--btn">
            <div class="pay-card__icon pay-card__icon--white">
              <img src="{{ asset('images/payment/celtiis.svg') }}" alt="Celtiis Money" style="width:40px;height:40px;object-fit:contain">
            </div>
            <div class="pay-card__body">
              <p class="pay-card__label">Celtiis Money</p>
              <p class="pay-card__sub">Numéro Celtiis Bénin</p>
            </div>
            <svg class="pay-card__arrow" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 18l6-6-6-6"/></svg>
          </button>
        </form>
      @endif

      {{-- ══ Carte bancaire (FedaPay seulement) ══ --}}
      @if($cardVia === 'fedapay')
        <form method="POST" action="{{ route('payment.initiate', $paiement) }}">
          @csrf
          <input type="hidden" name="gateway" value="fedapay">
          <input type="hidden" name="methode" value="carte_bancaire">
          <button type="submit" class="pay-card pay-card--btn">
            <div class="pay-card__icon pay-card__icon--white" style="gap:4px;flex-direction:row">
              <img src="{{ asset('images/payment/visa.svg') }}" alt="Visa" style="height:20px;object-fit:contain">
              <img src="{{ asset('images/payment/mastercard.svg') }}" alt="Mastercard" style="height:20px;object-fit:contain">
            </div>
            <div class="pay-card__body">
              <p class="pay-card__label">Carte bancaire</p>
              <p class="pay-card__sub">Visa, Mastercard — paiement 3D Secure</p>
            </div>
            <svg class="pay-card__arrow" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 18l6-6-6-6"/></svg>
          </button>
        </form>
      @endif

    </div>

    <p style="text-align:center;font-size:12px;color:#94a3b8;display:flex;align-items:center;justify-content:center;gap:5px">
      <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
      Paiement 100 % sécurisé · Données cryptées
    </p>

  </div>
</div>

<style>
.pay-card {
  width: 100%;
  background: #fff;
  border: 2px solid #e2e8f0;
  border-radius: 14px;
  padding: 15px 18px;
  display: flex;
  align-items: center;
  gap: 14px;
  cursor: pointer;
  transition: border-color .15s, box-shadow .15s, transform .1s;
  text-align: left;
  box-sizing: border-box;
}
.pay-card:hover {
  border-color: #185FA5;
  box-shadow: 0 4px 16px rgba(24,95,165,.12);
  transform: translateY(-1px);
}
.pay-card--btn { font-family: inherit; }

.pay-card__icon {
  width: 52px;
  height: 52px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.pay-card__icon--white {
  background: #fff;
  border: 1.5px solid #e2e8f0;
}
.pay-card__body { flex: 1; min-width: 0; }
.pay-card__label {
  font-size: 14.5px;
  font-weight: 700;
  color: #042C53;
  margin: 0 0 2px;
  line-height: 1.3;
}
.pay-card__sub {
  font-size: 12px;
  color: #94a3b8;
  margin: 0;
}
.pay-card__arrow { flex-shrink: 0; }
</style>
@endsection

@if($mobileVia === 'kkiapay' && $kkiaConfig)
@section('scripts')
<script src="https://cdn.kkiapay.me/k.js"></script>
<script>
const kkiaConfig = @json($kkiaConfig);
const paiementId = {{ $paiement->id }};
let selectedMethode = 'mobile_money';

function launchKkiaPay(methode) {
  selectedMethode = methode;
  openKkiapayWidget({
    amount:  kkiaConfig.amount,
    name:    kkiaConfig.name,
    email:   kkiaConfig.email,
    phone:   kkiaConfig.phone,
    key:     kkiaConfig.publicApiKey,
    sandbox: kkiaConfig.sandbox,
    theme:   kkiaConfig.theme,
    data:    { ...kkiaConfig.data, methode: methode },
  });
}

addSuccessListener(function (response) {
  fetch('{{ route("payment.callback.kkiapay") }}', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
    body: JSON.stringify({
      transactionId: response.transactionId,
      paiement_id:   paiementId,
      methode:       selectedMethode,
    }),
  })
  .then(r => r.json())
  .then(data => { if (data.redirect) window.location.href = data.redirect; })
  .catch(() => window.location.reload());
});

addFailedListener(function () {
  window.location.href = '{{ route("payment.failed", $paiement) }}';
});
</script>
@endsection
@endif
