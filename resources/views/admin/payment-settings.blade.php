@extends('layouts.admin')
@section('title', 'Gateways de paiement — Administration')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <h1>Gateways de paiement</h1>
    <p>Configuration de FedaPay et KKiaPay</p>
  </div>
</div>

@if(session('success'))
  <div style="background:#dcfce7;border:1.5px solid #bbf7d0;border-radius:10px;padding:12px 18px;margin-bottom:20px;font-size:13.5px;color:#15803d;display:flex;align-items:center;gap:10px">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
    {{ session('success') }}
  </div>
@endif

<div style="display:flex;flex-direction:column;gap:24px;max-width:680px">

  @foreach(['fedapay' => ['label' => 'FedaPay', 'color' => '#0057b7', 'bg' => '#eff6ff', 'desc' => 'Mobile Money + Carte bancaire (Visa, Mastercard)'], 'kkiapay' => ['label' => 'KKiaPay', 'color' => '#f97316', 'bg' => '#fff7ed', 'desc' => 'Mobile Money (MTN, Moov, Celtiis)']] as $key => $meta)
  @php $setting = $gateways[$key]; @endphp

  <div class="adm-card">
    {{-- En-tête --}}
    <div style="padding:20px 24px;border-bottom:1px solid #f1f5f9;display:flex;align-items:center;justify-content:space-between">
      <div style="display:flex;align-items:center;gap:12px">
        <div style="width:40px;height:40px;border-radius:10px;background:{{ $meta['bg'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0">
          <span style="font-size:10px;font-weight:900;color:{{ $meta['color'] }}">{{ strtoupper($key) }}</span>
        </div>
        <div>
          <p style="font-weight:700;color:#042C53;margin:0;font-size:15px">{{ $meta['label'] }}</p>
          <p style="font-size:12px;color:#94a3b8;margin:0">{{ $meta['desc'] }}</p>
        </div>
      </div>
      <span style="font-size:11.5px;font-weight:700;padding:4px 12px;border-radius:20px;background:{{ $setting->is_active ? '#dcfce7' : '#f1f5f9' }};color:{{ $setting->is_active ? '#16a34a' : '#94a3b8' }}">
        {{ $setting->is_active ? 'Actif' : 'Inactif' }}
      </span>
    </div>

    {{-- Formulaire --}}
    <div style="padding:24px">
      <form method="POST" action="{{ route('admin.payment-settings.update', $key) }}">
        @csrf @method('PUT')

        {{-- Activation --}}
        <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 16px;background:#f8fafc;border-radius:10px;margin-bottom:20px">
          <div>
            <p style="font-size:13.5px;font-weight:600;color:#042C53;margin:0">Activer {{ $meta['label'] }}</p>
            <p style="font-size:12px;color:#94a3b8;margin:2px 0 0">Les utilisateurs pourront payer via ce gateway.</p>
          </div>
          <label class="gw-toggle" style="position:relative;display:inline-block;width:44px;height:24px;flex-shrink:0;cursor:pointer">
            <input type="checkbox" name="is_active" value="1"
                   {{ $setting->is_active ? 'checked' : '' }}
                   style="position:absolute;opacity:0;width:100%;height:100%;margin:0;cursor:pointer;z-index:1"
                   onchange="this.closest('label').querySelector('.gw-track').style.background=this.checked?'#185FA5':'#cbd5e1';this.closest('label').querySelector('.gw-thumb').style.left=this.checked?'23px':'3px'">
            <span class="gw-track" style="position:absolute;inset:0;border-radius:24px;transition:background .2s;background:{{ $setting->is_active ? '#185FA5' : '#cbd5e1' }}"></span>
            <span class="gw-thumb" style="position:absolute;height:18px;width:18px;bottom:3px;border-radius:50%;background:#fff;transition:left .2s;left:{{ $setting->is_active ? '23px' : '3px' }}"></span>
          </label>
        </div>

        <div style="display:flex;flex-direction:column;gap:16px">

          {{-- Environnement --}}
          <div>
            <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:6px">Environnement</label>
            <div style="display:flex;gap:10px">
              <label style="display:flex;align-items:center;gap:8px;cursor:pointer;padding:10px 16px;border:1.5px solid {{ $setting->env === 'sandbox' ? '#185FA5' : '#e2e8f0' }};border-radius:8px;flex:1">
                <input type="radio" name="env" value="sandbox" {{ $setting->env === 'sandbox' ? 'checked' : '' }}>
                <span style="font-size:13px;font-weight:600;color:{{ $setting->env === 'sandbox' ? '#185FA5' : '#374151' }}">Sandbox (test)</span>
              </label>
              <label style="display:flex;align-items:center;gap:8px;cursor:pointer;padding:10px 16px;border:1.5px solid {{ $setting->env === 'live' ? '#16a34a' : '#e2e8f0' }};border-radius:8px;flex:1">
                <input type="radio" name="env" value="live" {{ $setting->env === 'live' ? 'checked' : '' }}>
                <span style="font-size:13px;font-weight:600;color:{{ $setting->env === 'live' ? '#16a34a' : '#374151' }}">Live (production)</span>
              </label>
            </div>
          </div>

          {{-- Public key --}}
          <div>
            <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">Clé publique</label>
            <input type="text" name="public_key" value="{{ $setting->public_key }}" autocomplete="off"
                   placeholder="pk_sandbox_xxxxxxxx"
                   style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:13px;font-family:monospace;box-sizing:border-box">
          </div>

          {{-- Private key (KKiaPay uniquement — vérification de transactions) --}}
          @if($key === 'kkiapay')
          <div>
            <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">
              Clé privée <span style="font-size:11px;font-weight:400;color:#94a3b8">(vérification transactions)</span>
            </label>
            <input type="password" name="private_key" autocomplete="new-password"
                   placeholder="{{ $setting->private_key ? '••••••••••••••••' : 'Clé privée KKiaPay' }}"
                   style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:13px;font-family:monospace;box-sizing:border-box">
            <p style="font-size:11.5px;color:#94a3b8;margin:4px 0 0">Laisser vide pour conserver la valeur actuelle.</p>
          </div>
          @endif

          {{-- Secret key --}}
          <div>
            <label style="display:block;font-size:13px;font-weight:600;color:#374151;margin-bottom:5px">
              @if($key === 'kkiapay')
                Clé secrète <span style="font-size:11px;font-weight:400;color:#94a3b8">(modifications compte)</span>
              @else
                Clé secrète
              @endif
            </label>
            <input type="password" name="secret_key" autocomplete="new-password"
                   placeholder="{{ $setting->secret_key ? '••••••••••••••••' : 'sk_sandbox_xxxxxxxx' }}"
                   style="width:100%;padding:10px 14px;border:1.5px solid #d1d5db;border-radius:8px;font-size:13px;font-family:monospace;box-sizing:border-box">
            <p style="font-size:11.5px;color:#94a3b8;margin:4px 0 0">Laisser vide pour conserver la valeur actuelle.</p>
          </div>

        </div>

        <div style="margin-top:22px;display:flex;justify-content:flex-end">
          <button type="submit" class="adm-btn adm-btn--yellow">Enregistrer {{ $meta['label'] }}</button>
        </div>
      </form>
    </div>
  </div>
  @endforeach

</div>
@endsection
