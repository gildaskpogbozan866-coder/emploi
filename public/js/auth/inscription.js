/* inscription.js — version Laravel */
document.addEventListener('DOMContentLoaded', function () {
  // La sélection de rôle est gérée directement dans la vue Blade
  // via la fonction selectRole() définie dans @section('scripts')
  // Ce fichier peut être vide ou ajouter des interactions UI supplémentaires.
});

// Fonction selectRole exposée globalement (appelée par les boutons du formulaire Blade)
function selectRole(role) {
  document.querySelectorAll('.role-card').forEach(function (c) { c.classList.remove('selected'); });

  var card = document.getElementById('role' + role.charAt(0).toUpperCase() + role.slice(1));
  if (card) card.classList.add('selected');

  var input = document.getElementById('roleInput');
  if (input) input.value = role;

  var metierField    = document.getElementById('metierField');
  var entrepriseField= document.getElementById('entrepriseField');

  if (metierField)    metierField.style.display    = role === 'talent'    ? '' : 'none';
  if (entrepriseField) entrepriseField.style.display = role === 'recruteur' ? '' : 'none';
}
