@extends('layouts.recruteur')
@section('title', 'Conversation — Recruteur')

@section('sidebar')
@include('recruteur._sidebar')
@endsection

@section('content')
<div class="rec-topbar">
  <div class="rec-topbar__left" style="display:flex;align-items:center;gap:14px">
    <a href="{{ route('recruteur.messagerie') }}" class="rec-btn rec-btn--outline rec-btn--sm"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg> Retour</a>
    <div style="width:42px;height:42px;border-radius:50%;background:rgba(55,138,221,0.12);display:flex;align-items:center;justify-content:center;font-weight:800;color:#185FA5">
      {{ strtoupper(substr($autre->prenom ?? '?', 0, 1)) }}
    </div>
    <div>
      <h1 style="font-size:1.1rem">{{ $autre->nom_complet }}</h1>
      <p>Candidat</p>
    </div>
  </div>
</div>

<div class="rec-card">
  <div style="min-height:300px;max-height:480px;overflow-y:auto;display:flex;flex-direction:column;gap:12px;margin-bottom:20px;padding:4px">
    @forelse($messages as $msg)
      @php $isMine = $msg->expediteur_id === auth()->id(); @endphp
      <div style="display:flex;flex-direction:column;align-items:{{ $isMine ? 'flex-end' : 'flex-start' }}">
        <div style="background:{{ $isMine ? '#185FA5' : '#f1f5f9' }};color:{{ $isMine ? '#fff' : '#1e293b' }};padding:10px 16px;border-radius:{{ $isMine ? '14px 14px 4px 14px' : '14px 14px 14px 4px' }};max-width:72%;font-size:13.5px;line-height:1.55">
          {{ $msg->contenu }}
        </div>
        <p style="font-size:11px;color:#94a3b8;margin:3px 8px 0">{{ $msg->created_at->format('d/m/Y H:i') }}</p>
      </div>
    @empty
      <p style="color:#94a3b8;text-align:center;margin:auto">Démarrez la conversation…</p>
    @endforelse
  </div>

  <form method="POST" action="{{ route('recruteur.messagerie.store', $conversation) }}" style="display:flex;gap:10px">
    @csrf
    <input type="text" name="contenu" required placeholder="Votre message…"
           style="flex:1;padding:11px 16px;border:1.5px solid #d1d5db;border-radius:10px;font-size:14px">
    <button type="submit" class="rec-btn rec-btn--primary">Envoyer</button>
  </form>
</div>
@endsection
