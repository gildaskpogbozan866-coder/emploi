@extends('layouts.candidat')
@section('title', 'Notifications')

@section('sidebar')
@include('candidat._sidebar')
@endsection

@section('content')
<div class="cand-page-header">
  <div class="cand-page-header__left">
    <h1 class="cand-page-header__title">Notifications</h1>
    <p class="cand-page-header__sub">Vos dernières activités et alertes</p>
  </div>
  <div class="cand-page-header__actions">
    <form method="POST" action="{{ route('candidat.notifications.lues') }}">
      @csrf
      <button type="submit" class="cand-btn cand-btn--outline">Tout marquer comme lu</button>
    </form>
  </div>
</div>

<div class="cand-card">
  @forelse($notifications as $notif)
    <div style="display:flex;gap:14px;align-items:flex-start;padding:16px 0;border-bottom:1px solid #f0f2f5;background:{{ $notif->lu ? 'transparent' : 'rgba(55,138,221,0.03)' }}">
      <div style="width:10px;height:10px;border-radius:50%;background:{{ $notif->lu ? '#e2e8f0' : '#378ADD' }};flex-shrink:0;margin-top:6px"></div>
      <div style="flex:1;min-width:0">
        <p style="font-weight:{{ $notif->lu ? '500' : '700' }};color:#042C53;margin:0 0 3px;font-size:14px">{{ $notif->titre }}</p>
        <p style="font-size:13px;color:#475569;margin:0;line-height:1.5">{{ $notif->contenu }}</p>
        @if($notif->lien)
          <a href="{{ $notif->lien }}" class="cand-btn cand-btn--outline cand-btn--sm" style="margin-top:8px;display:inline-flex">Voir <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg></a>
        @endif
      </div>
      <span style="font-size:11.5px;color:#94a3b8;white-space:nowrap;flex-shrink:0">{{ $notif->created_at->diffForHumans() }}</span>
    </div>
  @empty
    <div class="cand-empty">
      <div class="cand-empty__icon">
        <svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
      </div>
      <p class="cand-empty__title">Aucune notification</p>
      <p class="cand-empty__text">Vous n'avez pas de nouvelles notifications pour l'instant.</p>
    </div>
  @endforelse
</div>

@if($notifications->hasPages())
  <div style="margin-top:20px">{{ $notifications->links() }}</div>
@endif
@endsection
