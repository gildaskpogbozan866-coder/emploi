@extends('layouts.recruteur')
@section('title', 'Notifications | Recruteur')

@section('sidebar')
@include('recruteur._sidebar')
@endsection

@section('content')
<div class="rec-topbar">
  <div class="rec-topbar__left">
    <h1>Notifications</h1>
    <p>Vos dernières activités et alertes</p>
  </div>
  <div class="rec-topbar__actions">
    <form method="POST" action="{{ route('recruteur.notifications.lues') }}">
      @csrf
      <button type="submit" class="rec-btn rec-btn--outline">Tout marquer comme lu</button>
    </form>
  </div>
</div>

<div class="rec-card" style="padding:0">
  @forelse($notifications as $notif)
    <div style="display:flex;gap:14px;align-items:flex-start;padding:18px 24px;border-bottom:1px solid #f0f2f5;background:{{ $notif->lu ? 'transparent' : 'rgba(55,138,221,0.03)' }}">
      <div style="width:10px;height:10px;border-radius:50%;background:{{ $notif->lu ? '#e2e8f0' : '#378ADD' }};flex-shrink:0;margin-top:6px"></div>
      <div style="flex:1;min-width:0">
        <p style="font-weight:{{ $notif->lu ? '500' : '700' }};color:#042C53;margin:0 0 3px;font-size:14px">{{ $notif->titre }}</p>
        <p style="font-size:13px;color:#475569;margin:0;line-height:1.5">{{ $notif->contenu }}</p>
        @if($notif->lien)
          <a href="{{ $notif->lien }}" style="display:inline-flex;align-items:center;gap:5px;margin-top:8px;font-size:13px;color:#185FA5;font-weight:600;text-decoration:none">
            Voir
            <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
          </a>
        @endif
      </div>
      <span style="font-size:11.5px;color:#94a3b8;white-space:nowrap;flex-shrink:0">{{ $notif->created_at->diffForHumans() }}</span>
    </div>
  @empty
    <div style="text-align:center;padding:60px 20px;color:#94a3b8">
      <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="display:block;margin:0 auto 14px;opacity:.4">
        <path stroke-linecap="round" stroke-linejoin="round" d="M18 8A6 6 0 006 8c0 7-3 9-3 9h18s-3-2-3-9M13.73 21a2 2 0 01-3.46 0"/>
      </svg>
      <p style="font-weight:600;color:#64748b;margin:0 0 6px">Aucune notification</p>
      <p style="font-size:13px;margin:0">Vous n'avez pas de nouvelles notifications pour l'instant.</p>
    </div>
  @endforelse
</div>

@if($notifications->hasPages())
  <div style="margin-top:20px">{{ $notifications->links() }}</div>
@endif
@endsection
