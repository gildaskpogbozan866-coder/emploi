(function () {
  /* Pages où le bouton retour n'a pas de sens */
  var excluded = [
    /[/\\](index|Index)\.html$/,
    /[/\\]list-offre\.html$/,
    /[/\\]cvtheque\.html$/,
    /[/\\]list-service\.html$/,
    /[/\\]connexion\.html$/,
    /[/\\]inscription\.html$/
  ];
  var path = window.location.pathname;
  for (var i = 0; i < excluded.length; i++) {
    if (excluded[i].test(path)) return;
  }

  var btn = document.createElement('button');
  btn.className = 'back-fab';
  btn.setAttribute('aria-label', 'Retour à la page précédente');
  btn.innerHTML =
    '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>' +
    '<span>Retour</span>';
  btn.onclick = function () { history.back(); };

  function inject() { document.body.appendChild(btn); }
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', inject);
  } else {
    inject();
  }
})();
