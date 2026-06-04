/* connexion.js — version Laravel (auth gérée par le serveur via OTP) */
/* Aucune logique localStorage — le formulaire soumet directement au contrôleur */
document.addEventListener('DOMContentLoaded', function () {
  // Auto-focus sur le champ email
  var emailInput = document.getElementById('email') || document.getElementById('cx-email');
  if (emailInput) emailInput.focus();
});
