@extends('layouts.recruteur')
@section('title', 'Conversations archivées | Recruteur')

@section('sidebar')
@include('recruteur._sidebar')
@endsection

@section('content')
<div class="rec-topbar">
  <div class="rec-topbar__left">
    <a href="{{ route('recruteur.messagerie') }}" style="color:#185FA5;text-decoration:none;font-size:13px"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg> Messagerie</a>
    <h1 style="margin-top:8px">Conversations archivées</h1>
  </div>
</div>

@if($conversations->isEmpty())
  <div class="rec-card">
    <div class="rec-empty" style="padding:48px 32px">
      <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="21 8 21 21 3 21 3 8"/><rect x="1" y="3" width="22" height="5"/><line x1="10" y1="12" x2="14" y2="12"/></svg>
      <h3>Aucune conversation archivée</h3>
    </div>
  </div>
@else
  <div style="display:flex;flex-direction:column;gap:8px">
    @foreach($conversations as $conv)
      @php $autre = $conv->autreParticipant(auth()->id()); @endphp
      <div class="rec-card" style="margin-bottom:0;display:flex;gap:14px;align-items:center;padding:16px 22px">
        <div style="display:flex;gap:14px;align-items:center;flex:1;min-width:0">
          <div style="width:46px;height:46px;border-radius:50%;background:rgba(148,163,184,0.15);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1.1rem;color:#64748b;flex-shrink:0">
            {{ strtoupper(substr($autre->prenom ?? '?', 0, 1)) }}
          </div>
          <div style="flex:1;min-width:0">
            <p style="font-weight:700;color:#042C53;margin:0;font-size:14px">{{ $autre->nom_complet }}</p>
            <p style="font-size:12.5px;color:#94a3b8;margin:2px 0 0">{{ ucfirst($autre->role) }}</p>
            @if($conv->dernierMessage)
              <p style="font-size:12.5px;color:#64748b;margin:5px 0 0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                {{ Str::limit($conv->dernierMessage->contenu ?? '📎 Pièce jointe', 65) }}
              </p>
            @endif
          </div>
          <p style="font-size:11.5px;color:#94a3b8;flex-shrink:0">{{ $conv->dernier_message_at?->diffForHumans() ?? '' }}</p>
        </div>
        <form method="POST" action="{{ route('recruteur.messagerie.restaurer', $conv) }}">
          @csrf
          <button type="submit" class="rec-btn rec-btn--outline">
            Restaurer
          </button>
        </form>
      </div>
    @endforeach
  </div>
@endif
@endsection
