@php
  $markAllRoute = match(auth()->user()->role) {
    'recruteur' => 'recruteur.notifications.lues',
    'admin'     => 'admin.notifications.lues',
    default     => 'candidat.notifications.lues',
  };
  $seeAllRoute = auth()->user()->role === 'candidat' ? route('candidat.notifications') : null;
@endphp

<div class="notif-bell" id="notifBell">
  <button class="notif-bell__btn" id="notifBellBtn" type="button" aria-label="Notifications">
    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
      <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
    </svg>
    @if($notifNonLues > 0)
      <span class="notif-bell__badge">{{ $notifNonLues > 9 ? '9+' : $notifNonLues }}</span>
    @endif
  </button>

  <div class="notif-bell__dropdown" id="notifDropdown">
    <div class="notif-bell__header">
      <span class="notif-bell__header-title">Notifications</span>
      @if($notifNonLues > 0)
        <form method="POST" action="{{ route($markAllRoute) }}" style="display:inline">
          @csrf
          <button type="submit" class="notif-bell__markall">Tout marquer lu</button>
        </form>
      @endif
    </div>
    <div class="notif-bell__list">
      @forelse($dernierNotifs as $notif)
        <a href="{{ $notif->lien ?: '#' }}" class="notif-bell__item{{ $notif->lu ? '' : ' notif-bell__item--unread' }}">
          <div class="notif-bell__dot{{ $notif->lu ? '' : ' notif-bell__dot--active' }}"></div>
          <div class="notif-bell__body">
            <p class="notif-bell__titre">{{ $notif->titre }}</p>
            <p class="notif-bell__contenu">{{ \Illuminate\Support\Str::limit($notif->contenu, 80) }}</p>
            <span class="notif-bell__time">{{ $notif->created_at->diffForHumans() }}</span>
          </div>
        </a>
      @empty
        <div class="notif-bell__empty">
          <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="display:block;margin:0 auto 8px;opacity:.4"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
          Aucune notification
        </div>
      @endforelse
    </div>
    @if($seeAllRoute)
      <a href="{{ $seeAllRoute }}" class="notif-bell__seeall">Voir toutes les notifications</a>
    @endif
  </div>
</div>

<script>
(function () {
  const bell = document.getElementById('notifBell');
  const btn  = document.getElementById('notifBellBtn');
  const dd   = document.getElementById('notifDropdown');
  if (!btn || !dd) return;
  btn.addEventListener('click', function (e) {
    e.stopPropagation();
    dd.classList.toggle('open');
  });
  document.addEventListener('click', function (e) {
    if (!bell.contains(e.target)) dd.classList.remove('open');
  });
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') dd.classList.remove('open');
  });
})();
</script>
