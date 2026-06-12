@extends('layouts.admin')
@section('title', 'Conversation — Administration')

@section('content')
<div class="adm-topbar">
  <div class="adm-topbar__left" style="display:flex;align-items:center;gap:16px">
    <a href="{{ route('admin.messagerie') }}" class="adm-btn adm-btn--outline adm-btn--sm">
      <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
      Messagerie
    </a>
    <div>
      <h1>Conversation #{{ $conversation->id }}</h1>
      <p>{{ $conversation->user1->nom_complet }} &amp; {{ $conversation->user2->nom_complet }}</p>
    </div>
  </div>
  <div style="display:flex;gap:10px">
    <div style="background:#f0f9ff;border:1px solid #bae6fd;border-radius:10px;padding:8px 16px;text-align:center">
      <p style="font-size:11px;color:#0369a1;font-weight:600;text-transform:uppercase;margin:0 0 2px">Messages</p>
      <p style="font-size:1.1rem;font-weight:800;color:#0369a1;margin:0">{{ $messages->count() }}</p>
    </div>
  </div>
</div>

{{-- Info participants --}}
<div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px">
  @foreach([$conversation->user1, $conversation->user2] as $participant)
  <div class="adm-card" style="padding:16px 22px;display:flex;align-items:center;gap:14px">
    <div style="width:46px;height:46px;border-radius:50%;background:rgba(55,138,221,0.12);display:flex;align-items:center;justify-content:center;font-weight:800;font-size:1.1rem;color:#185FA5;flex-shrink:0">
      {{ strtoupper(substr($participant->prenom ?? '?', 0, 1)) }}
    </div>
    <div>
      <p style="font-weight:700;color:#042C53;margin:0;font-size:14px">{{ $participant->nom_complet }}</p>
      <p style="font-size:12.5px;color:#94a3b8;margin:2px 0 0">{{ ucfirst($participant->role) }}</p>
      <p style="font-size:12px;color:#64748b;margin:2px 0 0">{{ $participant->email }}</p>
    </div>
  </div>
  @endforeach
</div>

{{-- Messages (lecture seule) --}}
<div class="adm-card">
  <div style="padding:16px 22px;border-bottom:1px solid #e2e8f0">
    <h2 style="font-size:1rem;font-weight:700;color:#042C53;margin:0">Historique des messages</h2>
  </div>
  <div style="padding:18px 22px;display:flex;flex-direction:column;gap:10px;max-height:560px;overflow-y:auto">
    @forelse($messages as $msg)
      @php $auteur = $msg->expediteur; @endphp
      <div style="display:flex;gap:12px;align-items:flex-start;padding:12px 14px;border-radius:10px;background:{{ $msg->expediteur_id === $conversation->user1_id ? '#f0f7ff' : '#f8fafc' }};border:1px solid {{ $msg->expediteur_id === $conversation->user1_id ? '#dbeafe' : '#e2e8f0' }}">
        <div style="width:34px;height:34px;border-radius:50%;background:{{ $msg->expediteur_id === $conversation->user1_id ? '#dbeafe' : '#f1f5f9' }};display:flex;align-items:center;justify-content:center;font-weight:800;font-size:12px;color:#185FA5;flex-shrink:0">
          {{ strtoupper(substr($auteur->prenom ?? '?', 0, 1)) }}
        </div>
        <div style="flex:1;min-width:0">
          <div style="display:flex;align-items:baseline;gap:8px;margin-bottom:4px">
            <span style="font-weight:700;font-size:13px;color:#042C53">{{ $auteur->nom_complet }}</span>
            <span style="font-size:11px;color:#94a3b8">{{ $msg->created_at->format('d/m/Y H:i') }}</span>
            @if(!$msg->lu)
              <span style="font-size:10px;background:#fef9c3;color:#92400e;padding:1px 6px;border-radius:10px;font-weight:600">Non lu</span>
            @endif
          </div>
          @if($msg->contenu)
            <p style="font-size:13.5px;color:#374151;margin:0;line-height:1.55">{{ $msg->contenu }}</p>
          @endif
          @if($msg->fichier)
            <div style="margin-top:6px">
              @if(str_starts_with($msg->mime_type ?? '', 'image/'))
                <img src="{{ asset('storage/'.$msg->fichier) }}"
                     style="max-width:220px;border-radius:8px;cursor:pointer"
                     onclick="window.open(this.src,'_blank')"
                     alt="image">
              @else
                <a href="{{ asset('storage/'.$msg->fichier) }}" target="_blank" rel="noopener"
                   style="display:inline-flex;align-items:center;gap:5px;font-size:12.5px;font-weight:600;color:#185FA5;text-decoration:none">
                  <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
                  Pièce jointe
                </a>
              @endif
            </div>
          @endif
        </div>
      </div>
    @empty
      <p style="color:#94a3b8;text-align:center;padding:24px">Aucun message dans cette conversation.</p>
    @endforelse
  </div>
</div>
@endsection
