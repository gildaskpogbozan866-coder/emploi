@extends('layouts.candidat')
@section('title', 'Conversation')

@section('sidebar')
@include('candidat._sidebar')
@endsection

@section('content')
<div class="cand-page-header">
  <div class="cand-page-header__left">
    <a href="{{ route('candidat.messagerie') }}" style="color:#185FA5;text-decoration:none;font-size:13px"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg> Messagerie</a>
    <h1 class="cand-page-header__title" style="margin-top:8px">{{ $autre->nom_complet }}</h1>
    <p class="cand-page-header__sub">{{ $autre->entreprise ?? ucfirst($autre->role) }}</p>
  </div>
</div>

<div class="cand-card">
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

  <form method="POST" action="{{ route('candidat.messagerie.store', $conversation) }}" style="display:flex;gap:10px">
    @csrf
    <input type="text" name="contenu" required placeholder="Votre message…"
           style="flex:1;padding:11px 16px;border:1.5px solid #d1d5db;border-radius:10px;font-size:14px">
    <button type="submit" class="cand-btn cand-btn--primary">Envoyer</button>
  </form>
</div>
@endsection
