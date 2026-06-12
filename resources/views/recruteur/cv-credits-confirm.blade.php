@extends('layouts.recruteur')
@section('title', 'Confirmer l\'achat — ' . $pack['credits'] . ' crédits CVthèque')

@section('sidebar')
@include('recruteur._sidebar')
@endsection

@section('content')
<div class="rec-topbar">
  <div class="rec-topbar__left">
    <a href="{{ route('recruteur.cv-credits.index') }}" style="font-size:13px;color:#185FA5;text-decoration:none;display:inline-flex;align-items:center;gap:5px;margin-bottom:8px">
      <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 18l-6-6 6-6"/></svg>
      Retour aux crédits
    </a>
    <h1>Confirmer l'achat</h1>
  </div>
</div>

<div style="max-width:540px;margin:0 auto">

  {{-- Récapitulatif --}}
  <div style="background:linear-gradient(135deg,#042C53,#185FA5);border-radius:16px;padding:28px;margin-bottom:24px;text-align:center">
    <p style="font-size:3rem;font-weight:900;color:#F5C842;margin:0;line-height:1">{{ $pack['credits'] }}</p>
    <p style="font-size:14px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:rgba(255,255,255,.6);margin:4px 0 16px">crédits CVthèque</p>
    <p style="font-size:1.8rem;font-weight:800;color:#fff;margin:0 0 4px">{{ number_format($pack['prix'], 0, ',', ' ') }} FCFA</p>
    <p style="font-size:13px;color:rgba(255,255,255,.5);margin:0">
      soit {{ number_format($pack['prix'] / $pack['credits'], 0, ',', ' ') }} FCFA / crédit
    </p>
  </div>

  {{-- Rappel règles --}}
  <div style="background:#f0f9ff;border:1.5px solid #bae6fd;border-radius:12px;padding:14px 18px;margin-bottom:24px">
    <p style="font-size:13px;color:#0369a1;margin:0;line-height:1.6">
      <strong>Comment fonctionnent les crédits ?</strong><br>
      1 crédit = accès aux informations personnelles d'un candidat + téléchargement de son CV.<br>
      Chaque téléchargement coûte 1 crédit, même si vous retéléchargez le même CV.<br>
      Les crédits ne s'expirent jamais.
    </p>
  </div>

  {{-- Formulaire --}}
  <div class="rec-card">
    <div class="rec-card__head">
      <span class="rec-card__title">Choisir le mode de paiement</span>
    </div>
    <div class="rec-card__body">
      <form method="POST" action="{{ route('recruteur.cv-credits.store') }}" id="payment-form">
        @csrf
        <input type="hidden" name="credits" value="{{ $pack['credits'] }}">

        {{-- Méthode --}}
        <div style="margin-bottom:20px">
          <label style="font-size:12px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.06em;display:block;margin-bottom:10px">Méthode de paiement</label>
          <div style="display:flex;flex-direction:column;gap:10px">

            <label style="display:flex;align-items:center;gap:12px;padding:14px 16px;border:2px solid #e2e8f0;border-radius:10px;cursor:pointer;transition:.15s" id="opt-momo">
              <input type="radio" name="methode" value="mobile_money" required
                     onchange="toggleTel(true)" style="accent-color:#185FA5;width:16px;height:16px">
              <div>
                <p style="font-size:14px;font-weight:700;color:#042C53;margin:0">Mobile Money</p>
                <p style="font-size:12px;color:#94a3b8;margin:0">MTN MoMo · Moov Money</p>
              </div>
            </label>

            <label style="display:flex;align-items:center;gap:12px;padding:14px 16px;border:2px solid #e2e8f0;border-radius:10px;cursor:pointer;transition:.15s" id="opt-card">
              <input type="radio" name="methode" value="carte_bancaire"
                     onchange="toggleTel(false)" style="accent-color:#185FA5;width:16px;height:16px">
              <div>
                <p style="font-size:14px;font-weight:700;color:#042C53;margin:0">Carte bancaire</p>
                <p style="font-size:12px;color:#94a3b8;margin:0">Visa · Mastercard</p>
              </div>
            </label>

          </div>
        </div>

        {{-- Téléphone Mobile Money --}}
        <div id="tel-field" style="margin-bottom:20px;display:none">
          <label style="font-size:12px;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:.06em;display:block;margin-bottom:6px">
            Numéro Mobile Money
          </label>
          <input type="tel" name="telephone" id="telephone"
                 placeholder="Ex: 97 00 00 00"
                 style="width:100%;box-sizing:border-box;padding:10px 14px;border:1.5px solid #e2e8f0;border-radius:8px;font-family:inherit;font-size:14px;color:#042C53;outline:none">
          @error('telephone')
            <p style="color:#dc2626;font-size:12px;margin:4px 0 0">{{ $message }}</p>
          @enderror
        </div>

        {{-- Message info --}}
        <div style="background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;padding:12px 14px;margin-bottom:20px;font-size:13px;color:#475569;line-height:1.5">
          Après confirmation de votre demande, un conseiller vous contactera sous <strong>24h</strong> pour finaliser le paiement. Vos crédits seront ajoutés dès réception du paiement.
        </div>

        @if ($errors->any())
          <div style="background:#fee2e2;border:1px solid #fecaca;border-radius:8px;padding:12px 14px;margin-bottom:16px">
            <ul style="margin:0;padding-left:18px;font-size:13px;color:#dc2626">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <button type="submit" class="rec-btn rec-btn--primary" style="width:100%;justify-content:center;padding:13px;font-size:15px">
          <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          Confirmer — {{ number_format($pack['prix'], 0, ',', ' ') }} FCFA
        </button>

      </form>
    </div>
  </div>

</div>

@endsection

@section('scripts')
<script>
function toggleTel(show) {
  const field = document.getElementById('tel-field');
  const input = document.getElementById('telephone');
  field.style.display = show ? 'block' : 'none';
  input.required = show;
}
// Highlight selected option
document.querySelectorAll('input[name="methode"]').forEach(radio => {
  radio.addEventListener('change', () => {
    document.querySelectorAll('label[id^="opt-"]').forEach(l => l.style.borderColor = '#e2e8f0');
    const selected = document.querySelector('input[name="methode"]:checked');
    if (selected) selected.closest('label').style.borderColor = '#185FA5';
  });
});
</script>
@endsection
