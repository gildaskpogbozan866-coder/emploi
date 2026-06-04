/* ─── index.js — version Laravel ─── */

document.addEventListener('DOMContentLoaded', function () {

  // ── Recherche hero → route Laravel offre.list ──
  var heroSearch    = document.getElementById('heroSearch');
  var heroSearchBtn = document.getElementById('heroSearchBtn');

  function lancerRecherche() {
    var q = heroSearch ? heroSearch.value.trim() : '';
    var url = '/emploi-bouge-benin/public/offres';
    if (q) url += '?q=' + encodeURIComponent(q);
    window.location.href = url;
  }

  if (heroSearchBtn) heroSearchBtn.addEventListener('click', lancerRecherche);
  if (heroSearch) {
    heroSearch.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') { e.preventDefault(); lancerRecherche(); }
    });
  }

  // ── Redirection "Accéder aux CV" → cvthèque Laravel ──
  var btnVoirCV = document.getElementById('btnVoirCV');
  if (btnVoirCV) {
    btnVoirCV.addEventListener('click', function (e) {
      e.preventDefault();
      window.location.href = '/emploi-bouge-benin/public/cvs';
    });
  }

  // ── Redirection "Voir tous les talents" ──
  var btnVoirProfils = document.getElementById('btnVoirProfils');
  if (btnVoirProfils) {
    btnVoirProfils.addEventListener('click', function (e) {
      e.preventDefault();
      window.location.href = '/emploi-bouge-benin/public/profiltheque';
    });
  }

  // ── Compteur animé lectures blog ──
  document.querySelectorAll('.blog-read-count').forEach(function (el) {
    var target = parseInt(el.getAttribute('data-init') || '0', 10);
    el.textContent = target.toLocaleString('fr-FR');
  });

  // ── Newsletter feedback ──
  var newsletterForm = document.querySelector('.newsletter__form');
  if (newsletterForm) {
    newsletterForm.addEventListener('submit', function (e) {
      var btn = newsletterForm.querySelector('.newsletter__btn');
      if (btn) {
        btn.textContent = 'Envoi…';
        btn.disabled = true;
      }
    });
  }

});
