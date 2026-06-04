/* list-offre.js — version Laravel (rendu serveur, pas de localStorage) */
document.addEventListener('DOMContentLoaded', function () {

  // Filtres sidebar accordion
  document.querySelectorAll('.lo-filter-btn').forEach(function (btn) {
    btn.addEventListener('click', function () {
      var body = document.getElementById(btn.dataset.target);
      if (body) {
        var open = body.classList.toggle('open');
        btn.classList.toggle('open', open);
      }
    });
  });

  // Sauvegarder une offre au clic (AJAX-like avec form submit)
  document.querySelectorAll('.btn-sauvegarder').forEach(function (btn) {
    btn.addEventListener('click', function (e) {
      btn.textContent = '♥ Sauvegardée';
      btn.style.color = '#185FA5';
    });
  });

});
