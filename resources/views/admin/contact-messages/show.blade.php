@extends('layouts.admin')
@section('title', 'Message de ' . $message->prenom)

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left">
    <a href="{{ route('admin.contact-messages.index') }}" style="font-size:13px;color:#64748b;text-decoration:none;display:inline-flex;align-items:center;gap:6px;margin-bottom:10px">
      <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
      Retour aux messages
    </a>
    <h1>Message de {{ $message->prenom }} {{ $message->nom }}</h1>
  </div>
  <div class="adm-topbar__right">
    <form method="POST" action="{{ route('admin.contact-messages.destroy', $message) }}"
          onsubmit="return confirm('Supprimer définitivement ce message ?')">
      @csrf @method('DELETE')
      <button type="submit" class="adm-btn adm-btn--danger">Supprimer</button>
    </form>
  </div>
</div>

<div style="display:grid;grid-template-columns:1fr 320px;gap:24px;align-items:start">

  {{-- Message principal --}}
  <div class="adm-card" style="padding:28px">
    <div style="display:flex;align-items:center;gap:12px;margin-bottom:22px;padding-bottom:18px;border-bottom:1.5px solid #f1f5f9">
      <div style="width:44px;height:44px;border-radius:50%;background:linear-gradient(135deg,#042C53,#378ADD);display:flex;align-items:center;justify-content:center;color:#fff;font-size:16px;font-weight:700;flex-shrink:0">
        {{ strtoupper(substr($message->prenom, 0, 1)) }}
      </div>
      <div>
        <div style="font-size:15px;font-weight:700;color:#042C53">{{ $message->prenom }} {{ $message->nom }}</div>
        <a href="mailto:{{ $message->email }}" style="font-size:13px;color:#3b82f6;text-decoration:none">{{ $message->email }}</a>
      </div>
      @if($message->lu)
        <span style="margin-left:auto;font-size:11px;padding:3px 10px;border-radius:20px;background:#f1f5f9;color:#94a3b8;font-weight:600">Lu</span>
      @else
        <span style="margin-left:auto;font-size:11px;padding:3px 10px;border-radius:20px;background:#fef9c3;color:#92400e;font-weight:700">Nouveau</span>
      @endif
    </div>

    <div style="margin-bottom:16px">
      <span style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Sujet</span>
      <p style="font-size:14px;font-weight:600;color:#042C53;margin:5px 0 0">{{ $message->sujet_label }}</p>
    </div>

    <div>
      <span style="font-size:11px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em">Message</span>
      <div style="margin-top:10px;padding:18px;background:#f8fafc;border-radius:10px;border:1.5px solid #e2e8f0">
        <p style="font-size:14px;color:#374151;line-height:1.7;margin:0;white-space:pre-wrap">{{ $message->message }}</p>
      </div>
    </div>
  </div>

  {{-- Infos --}}
  <div style="display:flex;flex-direction:column;gap:16px">
    <div class="adm-card" style="padding:20px">
      <h3 style="font-size:12px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin:0 0 14px">Informations</h3>
      <div style="display:flex;flex-direction:column;gap:10px">
        <div>
          <span style="font-size:11px;color:#94a3b8;font-weight:600;display:block;margin-bottom:2px">Reçu le</span>
          <span style="font-size:13px;color:#374151;font-weight:500">{{ $message->created_at->format('d/m/Y à H:i') }}</span>
        </div>
        @if($message->lu && $message->lu_at)
        <div>
          <span style="font-size:11px;color:#94a3b8;font-weight:600;display:block;margin-bottom:2px">Lu le</span>
          <span style="font-size:13px;color:#374151;font-weight:500">{{ $message->lu_at->format('d/m/Y à H:i') }}</span>
        </div>
        @endif
      </div>
    </div>

    <div class="adm-card" style="padding:20px">
      <h3 style="font-size:12px;font-weight:700;color:#94a3b8;text-transform:uppercase;letter-spacing:.06em;margin:0 0 14px">Répondre</h3>
      <a href="mailto:{{ $message->email }}?subject=Re: {{ urlencode($message->sujet_label) }}"
         class="adm-btn adm-btn--yellow" style="width:100%;justify-content:center;text-decoration:none;display:flex">
        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        Répondre par email
      </a>
    </div>
  </div>

</div>

@endsection
