@php $isMine = $msg->expediteur_id === auth()->id(); @endphp
<div class="msg-wrap msg-wrap--{{ $isMine ? 'mine' : 'their' }}">
  <div class="msg-bubble msg-bubble--{{ $isMine ? 'mine' : 'their' }}">
    @if($msg->contenu)
      <span>{{ $msg->contenu }}</span>
    @endif
    @if($msg->fichier)
      @if(str_starts_with($msg->mime_type ?? '', 'image/'))
        <img src="{{ asset('storage/'.$msg->fichier) }}"
             class="msg-img"
             onclick="window.open(this.src,'_blank')"
             alt="image">
      @else
        <a href="{{ asset('storage/'.$msg->fichier) }}" target="_blank" rel="noopener" class="msg-file">
          <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
          Pièce jointe
        </a>
      @endif
    @endif
  </div>
  <p class="msg-time">{{ $msg->created_at->format('d/m/Y H:i') }}</p>
</div>
