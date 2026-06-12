@extends('layouts.candidat')
@section('title', 'Conversation')

@section('sidebar')
@include('candidat._sidebar')
@endsection

@section('content')
<div class="cand-page-header">
  <div class="cand-page-header__left">
    <a href="{{ route('candidat.messagerie') }}" style="color:#185FA5;text-decoration:none;font-size:13px">
      <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="display:inline-block;vertical-align:-2px"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
      Messagerie
    </a>
    <h1 class="cand-page-header__title" style="margin-top:8px">{{ $autre->nom_complet }}</h1>
    <p class="cand-page-header__sub">{{ $autre->entreprise ?? ucfirst($autre->role) }}</p>
  </div>
</div>

<div class="cand-card">
  {{-- Zone messages --}}
  <div id="chatMessages" style="min-height:300px;max-height:480px;overflow-y:auto;display:flex;flex-direction:column;gap:10px;margin-bottom:20px;padding:4px">
    @forelse($messages as $msg)
      @include('partials._message-bubble')
    @empty
      <p id="chatEmpty" style="color:#94a3b8;text-align:center;margin:auto">Démarrez la conversation…</p>
    @endforelse
  </div>

  {{-- Formulaire d'envoi --}}
  <form id="msgForm" action="{{ route('candidat.messagerie.store', $conversation) }}" method="POST" enctype="multipart/form-data" class="msg-form">
    @csrf
    <div id="filePreview" class="msg-form__preview" style="display:none">
      <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
      <span id="fileName">fichier.pdf</span>
      <button type="button" class="msg-form__preview-remove" id="removeFile">×</button>
    </div>
    <div class="msg-form__row">
      <input type="file" name="fichier" id="fileInput" accept=".pdf,.jpg,.jpeg,.png,.webp,.doc,.docx" style="display:none">
      <button type="button" class="msg-form__attach-btn" id="attachBtn" title="Joindre un fichier">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#64748b" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
      </button>
      <input type="text" name="contenu" id="msgInput" class="msg-form__input"
             placeholder="Votre message…" autocomplete="off">
      <button type="submit" class="cand-btn cand-btn--primary" id="sendBtn">Envoyer</button>
    </div>
  </form>
</div>
@endsection

@section('scripts')
<script>
(function () {
  const chat      = document.getElementById('chatMessages');
  const form      = document.getElementById('msgForm');
  const input     = document.getElementById('msgInput');
  const sendBtn   = document.getElementById('sendBtn');
  const fileInput = document.getElementById('fileInput');
  const attachBtn = document.getElementById('attachBtn');
  const preview   = document.getElementById('filePreview');
  const fileName  = document.getElementById('fileName');
  const removeBtn = document.getElementById('removeFile');
  const myId      = {{ auth()->id() }};
  let lastId      = {{ $messages->last()?->id ?? 0 }};

  // ── Scroll initial ────────────────────────────────────
  function scrollDown() { chat.scrollTop = chat.scrollHeight; }
  scrollDown();

  // ── Pièce jointe ──────────────────────────────────────
  attachBtn.addEventListener('click', () => fileInput.click());
  fileInput.addEventListener('change', () => {
    if (fileInput.files[0]) {
      fileName.textContent = fileInput.files[0].name;
      preview.style.display = 'flex';
    }
  });
  removeBtn.addEventListener('click', () => {
    fileInput.value = '';
    preview.style.display = 'none';
  });

  // ── Envoi AJAX ────────────────────────────────────────
  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    if (!input.value.trim() && !fileInput.files[0]) return;

    sendBtn.disabled = true;
    sendBtn.textContent = '…';

    try {
      const body = new FormData(form);
      const res  = await fetch(form.action, {
        method: 'POST',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': form.querySelector('[name=_token]').value },
        body,
      });
      if (!res.ok) throw new Error();
      const data = await res.json();
      appendMsg(data.message);
      input.value = '';
      fileInput.value = '';
      preview.style.display = 'none';
      lastId = data.message.id;
      scrollDown();
      document.getElementById('chatEmpty')?.remove();
    } catch {
      alert('Erreur lors de l\'envoi. Veuillez réessayer.');
    } finally {
      sendBtn.disabled = false;
      sendBtn.textContent = 'Envoyer';
    }
  });

  // ── Polling (nouveaux messages toutes les 10 s) ───────
  setInterval(async () => {
    try {
      const res = await fetch(
        '{{ route('candidat.messagerie.rafraichir', $conversation) }}?depuis=' + lastId,
        { headers: { 'Accept': 'application/json' } }
      );
      if (!res.ok) return;
      const data = await res.json();
      if (data.messages?.length) {
        data.messages.forEach(m => appendMsg(m));
        lastId = data.messages.at(-1).id;
        scrollDown();
        document.getElementById('chatEmpty')?.remove();
      }
    } catch {}
  }, 10000);

  // ── Rendu d'une bulle ─────────────────────────────────
  function appendMsg(msg) {
    const isMine = msg.expediteur_id === myId;
    const side   = isMine ? 'mine' : 'their';
    let inner    = '';

    if (msg.contenu) {
      inner += `<span>${escHtml(msg.contenu)}</span>`;
    }
    if (msg.fichier) {
      if ((msg.mime_type || '').startsWith('image/')) {
        inner += `<img src="/storage/${msg.fichier}" class="msg-img" onclick="window.open(this.src,'_blank')" alt="image">`;
      } else {
        inner += `<a href="/storage/${msg.fichier}" target="_blank" rel="noopener" class="msg-file">
          <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/></svg>
          Pièce jointe</a>`;
      }
    }

    const wrap = document.createElement('div');
    wrap.className = `msg-wrap msg-wrap--${side}`;
    wrap.innerHTML = `
      <div class="msg-bubble msg-bubble--${side}">${inner}</div>
      <p class="msg-time">${formatDate(msg.created_at)}</p>`;
    chat.appendChild(wrap);
  }

  function escHtml(s) {
    return String(s).replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');
  }
  function formatDate(iso) {
    const d = new Date(iso);
    return d.toLocaleDateString('fr-FR') + ' ' + d.toLocaleTimeString('fr-FR', {hour:'2-digit',minute:'2-digit'});
  }
})();
</script>
@endsection
