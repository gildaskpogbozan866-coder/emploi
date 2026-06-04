/* ── Accès magique / OTP recovery ── */

function demanderOTP(event) {
  event.preventDefault();

  var identifiant = (document.getElementById('email') || {}).value || '';
  identifiant = identifiant.trim();
  var errorMsg  = document.getElementById('errorMsg');
  var submitBtn = document.getElementById('submitBtn');

  if (errorMsg) errorMsg.textContent = '';

  if (!identifiant) {
    if (errorMsg) errorMsg.textContent = 'Veuillez renseigner votre e-mail ou téléphone.';
    return;
  }

  var compte = App.findAccount(identifiant);
  if (!compte) {
    if (errorMsg) errorMsg.textContent = 'Aucun compte associé à cet e-mail / téléphone.';
    return;
  }

  if (submitBtn) { submitBtn.disabled = true; submitBtn.textContent = 'Génération…'; }

  var otp = App.generateOTP();
  App.savePendingOTP({
    action: 'magic',
    code  : otp,
    userId: compte.id,
    email : compte.email
  });

  /* Show confirmation */
  var confirmedEl   = document.getElementById('confirmedEmail');
  var demoHint      = document.getElementById('demoCodeHint');
  var goBtn         = document.getElementById('goVerifBtn');

  if (confirmedEl) confirmedEl.textContent = compte.email;
  if (demoHint)    demoHint.textContent = 'Code de démonstration : ' + otp;
  if (goBtn)       goBtn.href = 'verification-email.html?action=magic&email=' + encodeURIComponent(compte.email);

  document.getElementById('formStep').hidden    = true;
  document.getElementById('successStep').hidden = false;
}
