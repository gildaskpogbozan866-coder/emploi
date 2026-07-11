/**
 * initEmailCheck(emailId, hintId)
 * Feedback instantané sur le champ email : format invalide (bordure rouge)
 * ou domaine probablement mal orthographié (ex: "gmial.com") avec suggestion
 * cliquable pour corriger. Purement client-side, ne remplace pas la
 * validation serveur (email:rfc,dns côté Laravel).
 */
function initEmailCheck(emailId, hintId) {

  var emailEl = document.getElementById(emailId);
  var hintEl  = document.getElementById(hintId);
  if (!emailEl || !hintEl) return;

  var knownDomains = [
    'gmail.com', 'yahoo.com', 'yahoo.fr', 'hotmail.com', 'hotmail.fr',
    'outlook.com', 'outlook.fr', 'live.com', 'live.fr', 'icloud.com', 'aol.com'
  ];

  var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;

  function levenshtein(a, b) {
    var m = a.length, n = b.length;
    var d = [];
    for (var i = 0; i <= m; i++) d[i] = [i];
    for (var j = 0; j <= n; j++) d[0][j] = j;
    for (i = 1; i <= m; i++) {
      for (j = 1; j <= n; j++) {
        d[i][j] = a[i - 1] === b[j - 1]
          ? d[i - 1][j - 1]
          : Math.min(d[i - 1][j - 1], d[i][j - 1], d[i - 1][j]) + 1;
      }
    }
    return d[m][n];
  }

  function suggestDomain(domain) {
    var best = null, bestDist = 3;
    knownDomains.forEach(function (known) {
      if (known === domain) return;
      var dist = levenshtein(domain, known);
      if (dist > 0 && dist <= 2 && dist < bestDist) {
        best = known;
        bestDist = dist;
      }
    });
    return best;
  }

  function reset() {
    emailEl.classList.remove('aform__input--valid', 'aform__input--error');
    hintEl.textContent = '';
    hintEl.style.display = 'none';
  }

  function showHint(text, clickableDomain) {
    hintEl.style.display = 'block';
    if (clickableDomain) {
      var local = emailEl.value.split('@')[0];
      hintEl.innerHTML = text + ' <a href="#" class="aform__hint-fix">' + local + '@' + clickableDomain + '</a> ?';
      hintEl.querySelector('.aform__hint-fix').addEventListener('click', function (e) {
        e.preventDefault();
        emailEl.value = local + '@' + clickableDomain;
        reset();
        emailEl.classList.add('aform__input--valid');
      });
    } else {
      hintEl.textContent = text;
    }
  }

  emailEl.addEventListener('blur', function () {
    var value = emailEl.value.trim();
    reset();
    if (!value) return;

    if (!emailRegex.test(value)) {
      emailEl.classList.add('aform__input--error');
      showHint('Adresse e-mail incomplète ou mal formée.');
      return;
    }

    var domain = value.split('@')[1].toLowerCase();
    var suggestion = suggestDomain(domain);
    if (suggestion) {
      showHint('Vouliez-vous dire', suggestion);
      return;
    }

    emailEl.classList.add('aform__input--valid');
  });

  emailEl.addEventListener('input', function () {
    if (emailEl.classList.contains('aform__input--error') || emailEl.classList.contains('aform__input--valid')) {
      reset();
    }
  });
}
