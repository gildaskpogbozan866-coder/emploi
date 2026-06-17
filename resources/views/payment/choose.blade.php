@extends('layouts.app')
@section('title', 'Paiement')

@section('content')
@php
  $isService  = $paiement->type === 'service';
  $isCvCredit = $paiement->type === 'cv_credits';
  $fees       = $paiement->gateway_fees ?? 0;
  $total      = $paiement->montant + $fees;
  $user       = auth()->user();

  $description = $isCvCredit
    ? ($paiement->credits_cv . ' crédit' . ($paiement->credits_cv > 1 ? 's' : '') . ' CVthèque')
    : ($isService
      ? ($paiement->payable?->service?->nom ?? 'Service')
      : ($paiement->abonnement?->plan?->name ?? 'Abonnement'));

  $typeLabel = $isCvCredit ? 'Crédits CVthèque'
    : ($isService ? 'Commande de service' : 'Abonnement');
@endphp

<div style="min-height:80vh;background:#f0f4f8;display:flex;align-items:center;justify-content:center;padding:40px 20px">
  <div style="width:100%;max-width:560px">

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
        <p style="font-size:11.5px;color:rgba(255,255,255,.55);margin:0 0 3px;text-transform:uppercase;letter-spacing:.07em">{{ $typeLabel }}</p>
        <p style="font-size:1rem;font-weight:700;color:#fff;margin:0">{{ $description }}</p>
      </div>
      <div style="text-align:right">
        <p style="font-size:11.5px;color:rgba(255,255,255,.55);margin:0 0 2px">À payer</p>
        <p style="font-size:1.7rem;font-weight:900;color:#F5C842;margin:0;letter-spacing:-0.02em">
          {{ number_format($total, 0, ',', ' ') }}<span style="font-size:1rem;font-weight:600;margin-left:4px">FCFA</span>
        </p>
      </div>
    </div>

    @if(session('error'))
      <div style="background:#fee2e2;border:1px solid #fecaca;border-radius:10px;padding:12px 16px;margin-bottom:16px;font-size:13px;color:#dc2626">
        {{ session('error') }}
      </div>
    @endif

    {{-- ══ Méthodes de paiement en colonnes (en haut) ══ --}}
    <p style="font-size:11px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.07em;margin:0 0 10px">Choisir votre méthode</p>

    <form id="pay-form" method="POST" action="{{ route('payment.initiate', $paiement) }}">
      @csrf
      <input type="hidden" id="inp-gateway" name="gateway" value="">
      <input type="hidden" id="inp-methode"  name="methode"  value="">

      <div class="pay-grid" style="margin-bottom:22px">

        {{-- ══ MTN Mobile Money ══ --}}
        @if(in_array($mobileVia, ['kkiapay', 'fedapay']))
          <label class="pay-col" for="method-mtn">
            <input type="radio" name="pay_method" id="method-mtn"
                   data-gateway="{{ $mobileVia }}" data-methode="mtn_money"
                   data-label="MTN Mobile Money"
                   onchange="onMethodChange(this)">
            <div class="pay-col__check">
              <svg width="9" height="9" viewBox="0 0 12 12" fill="none">
                <path d="M2 6l3 3 5-5" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
            <div class="pay-col__icon">
              <img src="{{ asset('images/payment/mtn.svg') }}" alt="MTN" style="width:44px;height:44px;object-fit:contain">
            </div>
            <p class="pay-col__name">MTN<br>Mobile Money</p>
          </label>
        @endif

        {{-- ══ Moov Money ══ --}}
        @if(in_array($mobileVia, ['kkiapay', 'fedapay']))
          <label class="pay-col" for="method-moov">
            <input type="radio" name="pay_method" id="method-moov"
                   data-gateway="{{ $mobileVia }}" data-methode="moov_money"
                   data-label="Moov Money (Flooz)"
                   onchange="onMethodChange(this)">
            <div class="pay-col__check">
              <svg width="9" height="9" viewBox="0 0 12 12" fill="none">
                <path d="M2 6l3 3 5-5" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
            <div class="pay-col__icon">
              <img src="{{ asset('images/payment/moov.png') }}" alt="Moov" style="width:44px;height:44px;object-fit:contain">
            </div>
            <p class="pay-col__name">Moov Money<br><span style="font-size:10px;color:#94a3b8;font-weight:500">Flooz</span></p>
          </label>
        @endif

        {{-- ══ Celtiis Money ══ --}}
        @if(in_array($mobileVia, ['kkiapay', 'fedapay']))
          <label class="pay-col" for="method-celtiis">
            <input type="radio" name="pay_method" id="method-celtiis"
                   data-gateway="{{ $mobileVia }}" data-methode="celtiis_money"
                   data-label="Celtiis Money"
                   onchange="onMethodChange(this)">
            <div class="pay-col__check">
              <svg width="9" height="9" viewBox="0 0 12 12" fill="none">
                <path d="M2 6l3 3 5-5" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
            <div class="pay-col__icon">
              <img src="{{ asset('images/payment/celtiis.svg') }}" alt="Celtiis" style="width:44px;height:44px;object-fit:contain">
            </div>
            <p class="pay-col__name">Celtiis<br>Money</p>
          </label>
        @endif

        {{-- ══ Carte bancaire (FedaPay seulement) ══ --}}
        @if($cardVia === 'fedapay')
          <label class="pay-col" for="method-carte">
            <input type="radio" name="pay_method" id="method-carte"
                   data-gateway="fedapay" data-methode="carte_bancaire"
                   data-label="Carte bancaire"
                   onchange="onMethodChange(this)">
            <div class="pay-col__check">
              <svg width="9" height="9" viewBox="0 0 12 12" fill="none">
                <path d="M2 6l3 3 5-5" stroke="#fff" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </div>
            <div class="pay-col__icon" style="flex-direction:row;gap:4px">
              <img src="{{ asset('images/payment/visa.svg') }}" alt="Visa" style="height:22px;object-fit:contain">
              <img src="{{ asset('images/payment/mastercard.svg') }}" alt="Mastercard" style="height:22px;object-fit:contain">
            </div>
            <p class="pay-col__name">Carte<br>bancaire</p>
          </label>
        @endif

      </div>
    </form>

    {{-- ══ Bloc facturation ══ --}}
    <div style="background:#fff;border:1.5px solid #e2e8f0;border-radius:14px;overflow:hidden;margin-bottom:20px">

      {{-- Référence --}}
      <div style="padding:11px 20px;background:#f8fafc;border-bottom:1px solid #e2e8f0;display:flex;align-items:center;justify-content:space-between">
        <span style="font-size:12px;color:#64748b;font-weight:500">Référence de commande</span>
        <span style="font-size:12.5px;font-weight:700;color:#042C53;font-family:monospace;letter-spacing:.05em">{{ $paiement->reference }}</span>
      </div>

      {{-- Facturé à / Payable à --}}
      <div style="display:grid;grid-template-columns:1fr 1fr;border-bottom:1px solid #e2e8f0">
        <div style="padding:16px 20px;border-right:1px solid #e2e8f0">
          <p style="font-size:10.5px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.07em;margin:0 0 8px">Facturé à</p>
        
          <p style="font-size:13.5px;font-weight:700;color:#042C53;margin:0 0 3px">{{ $user->nom . ' ' .$user->prenom }}</p>
          <p style="font-size:12px;color:#64748b;margin:0;word-break:break-all">{{ $user->email }}</p>
        </div>
        <div style="padding:16px 20px">
          <p style="font-size:10.5px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.07em;margin:0 0 8px">Payable à</p>
          <p style="font-size:13.5px;font-weight:700;color:#042C53;margin:0 0 3px">Emploi Bouge Bénin</p>
          <p style="font-size:12px;color:#64748b;margin:0;line-height:1.6">Cotonou<br>+229 · BÉNIN</p>
        </div>
      </div>

      {{-- Tableau description / montant --}}
      <table style="width:100%;border-collapse:collapse">
        <thead>
          <tr style="background:#f8fafc">
            <th style="padding:9px 20px;text-align:left;font-size:10.5px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.07em;border-bottom:1px solid #e2e8f0">Description</th>
            <th style="padding:9px 20px;text-align:right;font-size:10.5px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.07em;border-bottom:1px solid #e2e8f0">Montant</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td style="padding:13px 20px;font-size:13.5px;font-weight:500;color:#042C53;border-bottom:1px solid #f1f5f9">{{ $description }}</td>
            <td style="padding:13px 20px;text-align:right;font-size:13.5px;font-weight:600;color:#042C53;border-bottom:1px solid #f1f5f9">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</td>
          </tr>
        </tbody>
      </table>

      {{-- Totaux --}}
      <div style="padding:0 20px">
        <div style="display:flex;justify-content:space-between;align-items:center;padding:9px 0;border-bottom:1px solid #f1f5f9;font-size:13px">
          <span style="color:#64748b">Méthode de paiement</span>
          <span id="display-methode" style="font-weight:600;color:#94a3b8;font-style:italic;font-size:12.5px">Non sélectionnée</span>
        </div>
        <div style="display:flex;justify-content:space-between;align-items:center;padding:9px 0;border-bottom:1px solid #f1f5f9;font-size:13px">
          <span style="color:#64748b">Devise de la passerelle</span>
          <span style="font-weight:600;color:#042C53">{{ strtoupper($paiement->devise ?? 'XOF') }}</span>
        </div>
        <div style="display:flex;justify-content:space-between;align-items:center;padding:9px 0;border-bottom:1px solid #f1f5f9;font-size:13px">
          <span style="color:#64748b">Sous-total</span>
          <span style="font-weight:600;color:#042C53">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</span>
        </div>
        <div style="display:flex;justify-content:space-between;align-items:center;padding:9px 0;border-bottom:1px solid #f1f5f9;font-size:13px">
          <span style="color:#64748b">Frais de passerelle</span>
          <span style="font-weight:600;color:{{ $fees > 0 ? '#042C53' : '#16a34a' }}">
            {{ $fees > 0 ? number_format($fees, 0, ',', ' ').' FCFA' : 'Offerts' }}
          </span>
        </div>
        <div style="display:flex;justify-content:space-between;align-items:center;padding:13px 0;font-size:15px">
          <span style="font-weight:800;color:#042C53">Total</span>
          <span style="font-weight:900;color:#185FA5;font-size:1.15rem">{{ number_format($total, 0, ',', ' ') }} FCFA</span>
        </div>
      </div>

      {{-- Instruction --}}
      <div style="background:#f0f6ff;border-top:1px solid #dbeafe;padding:12px 20px;display:flex;align-items:flex-start;gap:9px">
        <svg style="flex-shrink:0;margin-top:1px" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
        <p style="font-size:12px;color:#1e40af;margin:0;line-height:1.5"><strong>Instruction :</strong> Paiement par mobile money (MTN, Moov, Celtiis) ou carte bancaire (Visa / Mastercard).</p>
      </div>
    </div>

    {{-- Bouton Payer (hors form, déclenche submit via JS) --}}
    <button type="button" id="btn-payer" onclick="handlePayment()" disabled
      style="width:100%;padding:16px 20px;background:#042C53;color:#fff;border:none;border-radius:14px;font-size:15px;font-weight:700;cursor:not-allowed;opacity:.45;transition:opacity .2s,transform .1s,box-shadow .2s;letter-spacing:.01em;display:flex;align-items:center;justify-content:center;gap:10px;margin-bottom:20px">
      <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="1" y="4" width="22" height="16" rx="2" ry="2"/><line x1="1" y1="10" x2="23" y2="10"/></svg>
      Payer maintenant
    </button>

    <p style="text-align:center;font-size:12px;color:#94a3b8;display:flex;align-items:center;justify-content:center;gap:5px">
      <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
      Paiement 100 % sécurisé · Données cryptées
    </p>

  </div>
</div>

<style>
/* ── Grille de méthodes ── */
.pay-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(110px, 1fr));
  gap: 10px;
}

/* ── Carte colonne ── */
.pay-col {
  background: #fff;
  border: 2px solid #e2e8f0;
  border-radius: 14px;
  padding: 18px 12px 14px;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 10px;
  cursor: pointer;
  position: relative;
  text-align: center;
  transition: border-color .15s, box-shadow .15s, background .15s;
  box-sizing: border-box;
}
.pay-col:hover {
  border-color: #93c5fd;
  box-shadow: 0 2px 10px rgba(24,95,165,.08);
}
.pay-col input[type="radio"] {
  position: absolute;
  opacity: 0;
  width: 0;
  height: 0;
}

/* État sélectionné */
.pay-col--selected {
  border-color: #185FA5 !important;
  background: #f0f6ff;
  box-shadow: 0 4px 16px rgba(24,95,165,.14) !important;
}
.pay-col--selected .pay-col__name { color: #185FA5; }

/* Indicateur radio en haut à droite */
.pay-col__check {
  position: absolute;
  top: 8px;
  right: 8px;
  width: 18px;
  height: 18px;
  border-radius: 50%;
  border: 2px solid #cbd5e1;
  background: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: border-color .15s, background .15s;
}
.pay-col--selected .pay-col__check {
  border-color: #185FA5;
  background: #185FA5;
}

/* Icône */
.pay-col__icon {
  width: 56px;
  height: 56px;
  border-radius: 12px;
  background: #f8fafc;
  border: 1.5px solid #e2e8f0;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
}
.pay-col--selected .pay-col__icon {
  background: #fff;
  border-color: #bfdbfe;
}

/* Nom */
.pay-col__name {
  font-size: 11.5px;
  font-weight: 700;
  color: #042C53;
  margin: 0;
  line-height: 1.4;
  transition: color .15s;
}

/* ── Bouton actif ── */
#btn-payer:not([disabled]) {
  cursor: pointer;
  opacity: 1;
}
#btn-payer:not([disabled]):hover {
  background: #185FA5;
  box-shadow: 0 6px 20px rgba(24,95,165,.28);
  transform: translateY(-1px);
}
#btn-payer:not([disabled]):active {
  transform: translateY(0);
}
</style>

<script>
const methodeLabels = {
  mtn_money:      'MTN Mobile Money',
  moov_money:     'Moov Money (Flooz)',
  celtiis_money:  'Celtiis Money',
  carte_bancaire: 'Carte bancaire (Visa / Mastercard)',
};

function onMethodChange(input) {
  document.querySelectorAll('.pay-col').forEach(c => c.classList.remove('pay-col--selected'));
  input.closest('.pay-col').classList.add('pay-col--selected');

  const display = document.getElementById('display-methode');
  display.textContent = methodeLabels[input.dataset.methode] ?? input.dataset.label;
  display.style.color      = '#042C53';
  display.style.fontStyle  = 'normal';
  display.style.fontWeight = '600';
  display.style.fontSize   = '13px';

  const btn = document.getElementById('btn-payer');
  btn.disabled = false;
  btn.style.opacity = '1';
  btn.style.cursor  = 'pointer';
}

function handlePayment() {
  const selected = document.querySelector('input[name="pay_method"]:checked');
  if (!selected) return;

  const gateway = selected.dataset.gateway;
  const methode = selected.dataset.methode;

  if (gateway === 'kkiapay') {
    launchKkiaPay(methode);
  } else {
    document.getElementById('inp-gateway').value = gateway;
    document.getElementById('inp-methode').value  = methode;
    document.getElementById('pay-form').submit();
  }
}
</script>

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
