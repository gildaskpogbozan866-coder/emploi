/* ── Partage de l'article ── */
(function () {
  var pageUrl = encodeURIComponent(window.location.href);
  var title   = encodeURIComponent(document.getElementById('articleTitle')
                  ? document.getElementById('articleTitle').textContent.trim()
                  : document.title);

  var fbBtn = document.getElementById('shareFb');
  if (fbBtn) {
    fbBtn.href = 'https://www.facebook.com/sharer/sharer.php?u=' + pageUrl;
  }

  var waBtn = document.getElementById('shareWa');
  if (waBtn) {
    waBtn.href = 'https://api.whatsapp.com/send?text=' + title + '%20' + pageUrl;
  }
})();

/* ── Hamburger mobile ── */
(function () {
  var btn  = document.getElementById('hamburger');
  var menu = document.getElementById('mobileMenu');
  if (!btn || !menu) return;

  btn.addEventListener('click', function () {
    var open = menu.classList.toggle('nav__mobile--open');
    btn.setAttribute('aria-expanded', String(open));
  });
})();
