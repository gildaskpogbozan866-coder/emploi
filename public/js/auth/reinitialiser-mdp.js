/* Indicateur force mot de passe */
var newPwdInput = document.getElementById('newPwd');
var bars = [document.getElementById('bar1'), document.getElementById('bar2'), document.getElementById('bar3')];
var pwdHint = document.getElementById('pwdHint');
var hints = ['', 'Mot de passe faible', 'Mot de passe moyen', 'Mot de passe fort'];

newPwdInput.addEventListener('input', function() {
  var v = newPwdInput.value;
  var score = 0;
  if (v.length >= 6) score++;
  if (v.length >= 10 && /[A-Z]/.test(v)) score++;
  if (/[0-9]/.test(v) && /[^A-Za-z0-9]/.test(v)) score++;

  bars.forEach(function(b, i) {
    b.className = 'aform__pwd-bar';
    if (i < score) {
      b.classList.add(score === 1 ? 'active-weak' : score === 2 ? 'active-medium' : 'active-strong');
    }
  });

  pwdHint.textContent = hints[score] || '';
  pwdHint.style.color = score === 1 ? '#fc8181' : score === 2 ? '#f59e0b' : '#38A169';
  checkMatch();
});

/* Vérification concordance */
function checkMatch() {
  var v1 = newPwdInput.value;
  var v2 = document.getElementById('confirmPwd').value;
  var hint = document.getElementById('matchHint');
  var input = document.getElementById('confirmPwd');
  if (!v2) { hint.textContent = ''; input.className = 'aform__input'; return; }
  if (v1 === v2) {
    hint.textContent = 'Les mots de passe correspondent ✓';
    hint.className = 'aform__match-hint match-ok';
    input.className = 'aform__input input--ok';
  } else {
    hint.textContent = 'Les mots de passe ne correspondent pas';
    hint.className = 'aform__match-hint match-error';
    input.className = 'aform__input input--error';
  }
}

/* Afficher/masquer mot de passe */
function toggleVis(inputId, btn) {
  var input = document.getElementById(inputId);
  var isText = input.type === 'text';
  input.type = isText ? 'password' : 'text';
  btn.style.color = isText ? '#94a3b8' : 'var(--bleu-clair)';
}

/* Afficher l'email en attente dans le sous-titre */
var pendingEmail = '';
try { pendingEmail = localStorage.getItem('pending_reset_email') || ''; } catch(e) {}
if (pendingEmail) {
  document.getElementById('emailInfo').textContent = 'Nouveau mot de passe pour ' + pendingEmail;
}

/* Soumission */
function reinitialiserMdp(event) {
  event.preventDefault();

  var newPwd    = document.getElementById('newPwd').value;
  var confirmPwd = document.getElementById('confirmPwd').value;
  var errorMsg  = document.getElementById('errorMsg');
  var submitBtn = document.getElementById('submitBtn');

  errorMsg.textContent = '';

  if (newPwd.length < 6) {
    errorMsg.textContent = 'Le mot de passe doit contenir au moins 6 caractères.';
    return;
  }

  if (newPwd !== confirmPwd) {
    errorMsg.textContent = 'Les deux mots de passe ne correspondent pas.';
    document.getElementById('confirmPwd').focus();
    return;
  }

  submitBtn.disabled = true;
  submitBtn.textContent = 'Enregistrement…';

  /* Mettre à jour le mot de passe dans localStorage */
  var email = pendingEmail;
  if (email) {
    var keys = ['comptes_candidats', 'comptes_recruteurs'];
    keys.forEach(function(key) {
      try {
        var comptes = JSON.parse(localStorage.getItem(key) || '[]');
        var updated = false;
        comptes = comptes.map(function(c) {
          if (c.email === email) { c.pwd = newPwd; updated = true; }
          return c;
        });
        if (updated) localStorage.setItem(key, JSON.stringify(comptes));
      } catch(e) {}
    });
    localStorage.removeItem('pending_reset_email');
  }

  /* Afficher l'écran succès avec compte à rebours */
  setTimeout(function() {
    document.getElementById('formStep').hidden   = true;
    document.getElementById('successStep').hidden = false;

    var count = 3;
    var el = document.getElementById('countdown');
    var timer = setInterval(function() {
      count--;
      if (el) el.textContent = count;
      if (count <= 0) {
        clearInterval(timer);
        window.location.href = 'connexion.html';
      }
    }, 1000);
  }, 1000);
}
