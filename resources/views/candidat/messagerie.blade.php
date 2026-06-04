@extends('layouts.candidat')
@section('title', 'Messagerie')

@section('sidebar')
@include('candidat._sidebar')
@endsection

@section('content')
<div class="cand-page-header">
  <div class="cand-page-header__left">
    <h1 class="cand-page-header__title">Messagerie</h1>
    <p class="cand-page-header__sub">Vos échanges avec les recruteurs</p>
  </div>
</div>

@if($conversations->isEmpty())
  <div class="cand-card">
    <div class="cand-empty">
      <div class="cand-empty__icon">
        <svg width="26" height="26" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
      </div>
      <p class="cand-empty__title">Aucune conversation</p>
      <p class="cand-empty__text">Vous n'avez pas encore de conversation. Les recruteurs pourront vous contacter depuis votre CV ou profil.</p>
    </div>
  </div>
@else
  <div style="display:flex;flex-direction:column;gap:8px">
    @foreach($conversations as $conv)
      @php $autre = $conv->autreParticipant(auth()->id()); @endphp
      <a href="{{ route('candidat.messagerie.show', $conv) }}" style="text-decoration:none">
        <div class="cand-card" style="margin-bottom:0;display:flex;gap:14px;align-items:center;padding:16px 22px;transition:box-shadow .15s"
             onmouseover="this.style.boxShadow='0 2px 14px rgba(55,138,221,.12)'"
             onmouseout="this.style.boxShadow='none'">
          <div style="width:46px;height:46px;border-radius:50%;background:rgba(55,138,221,0.12);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1.1rem;color:#185FA5;flex-shrink:0">
            {{ strtoupper(substr($autre->prenom ?? '?', 0, 1)) }}
          </div>
          <div style="flex:1;min-width:0">
            <p style="font-weight:700;color:#042C53;margin:0;font-size:14px">{{ $autre->nom_complet }}</p>
            <p style="font-size:12.5px;color:#94a3b8;margin:2px 0 0">{{ $autre->entreprise ?? ucfirst($autre->role) }}</p>
            @if($conv->dernierMessage)
              <p style="font-size:12.5px;color:#64748b;margin:5px 0 0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap">
                {{ Str::limit($conv->dernierMessage->contenu, 65) }}
              </p>
            @endif
          </div>
          <div style="text-align:right;flex-shrink:0">
            <p style="font-size:11.5px;color:#94a3b8">{{ $conv->dernier_message_at?->diffForHumans() ?? '' }}</p>
            @if(isset($conv->non_lus) && $conv->non_lus > 0)
              <span class="cand-nav__badge" style="margin-top:4px">{{ $conv->non_lus }}</span>
            @endif
          </div>
        </div>
      </a>
    @endforeach
  </div>
@endif
@endsection
