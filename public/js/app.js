/* ─── app.js — version Laravel (sans localStorage) ─── */

// Hamburger menu
document.addEventListener('DOMContentLoaded', function () {
  var hamburger  = document.getElementById('hamburger');
  var mobileMenu = document.getElementById('mobileMenu');

  if (hamburger && mobileMenu) {
    hamburger.addEventListener('click', function () {
      var isOpen = mobileMenu.classList.toggle('open');
      hamburger.classList.toggle('open', isOpen);
      hamburger.setAttribute('aria-expanded', String(isOpen));
    });

    mobileMenu.querySelectorAll('a').forEach(function (link) {
      link.addEventListener('click', function () {
        mobileMenu.classList.remove('open');
        hamburger.classList.remove('open');
        hamburger.setAttribute('aria-expanded', 'false');
      });
    });
  }

  // Auth modals (connexion / inscription)
  var overlayConnexion   = document.getElementById('overlayConnexion');
  var overlayInscription = document.getElementById('overlayInscription');
  var closeConnexion     = document.getElementById('closeConnexion');
  var closeInscription   = document.getElementById('closeInscription');
  var switchToInscription= document.getElementById('switchToInscription');
  var switchToConnexion  = document.getElementById('switchToConnexion');

  function openModal(overlay) {
    if (overlay) { overlay.style.display = 'flex'; document.body.style.overflow = 'hidden'; }
  }
  function closeModal(overlay) {
    if (overlay) { overlay.style.display = 'none'; document.body.style.overflow = ''; }
  }

  if (closeConnexion)    closeConnexion.addEventListener('click', function () { closeModal(overlayConnexion); });
  if (closeInscription)  closeInscription.addEventListener('click', function () { closeModal(overlayInscription); });
  if (switchToInscription) switchToInscription.addEventListener('click', function (e) {
    e.preventDefault(); closeModal(overlayConnexion); openModal(overlayInscription);
  });
  if (switchToConnexion) switchToConnexion.addEventListener('click', function (e) {
    e.preventDefault(); closeModal(overlayInscription); openModal(overlayConnexion);
  });

  // Fermer modal au clic sur l'overlay
  [overlayConnexion, overlayInscription].forEach(function (overlay) {
    if (overlay) overlay.addEventListener('click', function (e) {
      if (e.target === overlay) closeModal(overlay);
    });
  });
});
