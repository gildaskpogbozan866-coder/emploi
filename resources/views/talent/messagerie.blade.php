@extends('layouts.candidat')
@section('title', 'Messagerie — Talent')
@section('sidebar')
@include('candidat._sidebar')
@endsection

@section('content')
<div class="dash-content">
  <div class="dash-content__header">
    <h1 class="dash-content__title">Messagerie</h1>
    <p style="color:#6b7a8d;margin:0">Vos échanges avec les recruteurs</p>
  </div>

  @if($conversations->isEmpty())
    <div style="background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:48px 32px;text-align:center">
      <svg width="44" height="44" fill="none" viewBox="0 0 24 24" stroke="#94a3b8" stroke-width="1.5" style="margin-bottom:14px"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
      <h3 style="font-size:1.05rem;font-weight:700;color:#042C53;margin:0 0 8px">Aucune conversation</h3>
      <p style="color:#64748b;font-size:13.5px;margin:0">Les recruteurs pourront vous contacter depuis votre profil public.</p>
    </div>
  @else
    <div style="display:flex;flex-direction:column;gap:8px">
      @foreach($conversations as $conv)
        @php $autre = $conv->autreParticipant(auth()->id()); @endphp
        <a href="{{ route('talent.messagerie.show', $conv) }}" style="text-decoration:none">
          <div style="background:#fff;border:1px solid #e2e8f0;border-radius:12px;padding:16px 22px;display:flex;gap:14px;align-items:center;transition:box-shadow .15s"
               onmouseover="this.style.boxShadow='0 2px 14px rgba(55,138,221,.10)'"
               onmouseout="this.style.boxShadow='none'">
            <div style="width:46px;height:46px;border-radius:50%;background:#d1fae5;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1.1rem;color:#065f46;flex-shrink:0">
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
            </div>
          </div>
        </a>
      @endforeach
    </div>
  @endif
</div>
@endsection
