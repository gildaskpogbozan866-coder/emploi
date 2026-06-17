{{-- PWA : bannière d'installation + enregistrement du service worker --}}
{{-- À inclure juste avant </body> dans tous les layouts --}}

<div id="pwa-banner" style="display:none;position:fixed;bottom:0;left:0;right:0;z-index:99998;background:#042C53;color:#fff;padding:14px 16px;box-shadow:0 -4px 24px rgba(0,0,0,.3)">
  <div style="max-width:600px;margin:0 auto;display:flex;align-items:center;gap:12px">
    <img src="{{ asset('images/pwa-icon-192.png') }}" alt="" width="42" height="42" style="border-radius:10px;flex-shrink:0;object-fit:cover">
    <div style="flex:1;min-width:0">
      <p style="margin:0;font-size:13.5px;font-weight:700;color:#fff;line-height:1.3">Emploi Bouge Bénin</p>
      <p style="margin:0;font-size:12px;color:rgba(255,255,255,.75);line-height:1.4" id="pwa-sub">Installez l'application sur votre appareil.</p>
    </div>
    <div style="display:flex;gap:8px;flex-shrink:0;align-items:center">
      <button id="pwa-install-btn" style="display:none;padding:10px 20px;background:#F5C842;color:#042C53;border:none;border-radius:8px;font-size:13px;font-weight:800;cursor:pointer;white-space:nowrap">
        Installer l'application
      </button>
      <button id="pwa-ios-btn" style="display:none;padding:10px 16px;background:#F5C842;color:#042C53;border:none;border-radius:8px;font-size:13px;font-weight:800;cursor:pointer;white-space:nowrap">
        Installer l'application
      </button>
      <button id="pwa-close-btn" aria-label="Fermer" style="background:none;border:none;cursor:pointer;color:rgba(255,255,255,.6);padding:4px;display:flex;align-items:center">
        <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
      </button>
    </div>
  </div>
  {{-- Guide iOS --}}
  <div id="pwa-ios-guide" style="display:none;max-width:600px;margin:10px auto 0;background:rgba(255,255,255,.1);border-radius:10px;padding:12px 14px">
    <p style="margin:0;font-size:12.5px;color:#fff;line-height:2">
      <strong>iPhone / iPad :</strong>
      Appuyez sur
      <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="#F5C842" stroke-width="2.2" style="display:inline-block;vertical-align:-2px"><path d="M4 12v8a2 2 0 002 2h12a2 2 0 002-2v-8"/><polyline points="16 6 12 2 8 6"/><line x1="12" y1="2" x2="12" y2="15"/></svg>
      <strong>"Partager"</strong> (en bas de Safari) &rarr; <strong>"Sur l'écran d'accueil"</strong> &rarr; <strong>"Ajouter"</strong>
    </p>
  </div>
</div>

<script>
(function () {
  var KEY        = 'pwa_dismissed';
  var banner     = document.getElementById('pwa-banner');
  var btnInstall = document.getElementById('pwa-install-btn');
  var btnIos     = document.getElementById('pwa-ios-btn');
  var btnClose   = document.getElementById('pwa-close-btn');
  var iosGuide   = document.getElementById('pwa-ios-guide');
  var deferred   = null;

  // Déjà en mode standalone (app installée)
  if (window.navigator.standalone === true || window.matchMedia('(display-mode: standalone)').matches) return;
  // Déjà fermé cette session
  if (sessionStorage.getItem(KEY)) return;

  var isIos = /iphone|ipad|ipod/i.test(navigator.userAgent);

  // ── Android / Chrome Desktop : attend le prompt natif ────────
  window.addEventListener('beforeinstallprompt', function (e) {
    e.preventDefault();
    deferred = e;
    btnInstall.style.display = 'inline-block';
    banner.style.display = 'block';
  });

  // ── iOS Safari : guide manuel ─────────────────────────────────
  if (isIos && !window.navigator.standalone) {
    btnIos.style.display = 'inline-block';
    banner.style.display = 'block';
  }

  // ── Clic "Installer" ─────────────────────────────────────────
  btnInstall.addEventListener('click', function () {
    if (!deferred) return;
    deferred.prompt();
    deferred.userChoice.then(function (r) {
      if (r.outcome === 'accepted') banner.style.display = 'none';
      deferred = null;
    });
  });

  // ── Clic iOS ─────────────────────────────────────────────────
  btnIos.addEventListener('click', function () {
    iosGuide.style.display = iosGuide.style.display === 'none' ? 'block' : 'none';
  });

  // ── Fermer ────────────────────────────────────────────────────
  btnClose.addEventListener('click', function () {
    banner.style.display = 'none';
    sessionStorage.setItem(KEY, '1');
  });

  // ── App installée ─────────────────────────────────────────────
  window.addEventListener('appinstalled', function () {
    banner.style.display = 'none';
  });

  // ── Service Worker : rechargement silencieux à la première activation ──
  if ('serviceWorker' in navigator) {
    window.addEventListener('load', function () {
      navigator.serviceWorker.register('{{ asset("sw.js") }}')
        .then(function (reg) {
          reg.addEventListener('updatefound', function () {
            var sw = reg.installing;
            if (!sw) return;
            sw.addEventListener('statechange', function () {
              if (sw.state === 'activated' && !navigator.serviceWorker.controller) {
                // Premier démarrage : rechargement silencieux pour activer le prompt
                window.location.reload();
              }
            });
          });
        })
        .catch(function () {});
    });
  }
})();
</script>
