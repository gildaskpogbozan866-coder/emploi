/* ── Détection du plan (recruteur vs candidat) ── */
(function() {
  var recPlan = localStorage.getItem('pending_rec_plan');
  if (recPlan) {
    var plans = {
      premium    : { title: 'Premium 30 300 F — Recruteur', sub: '10 annonces · Multidiffusion · Matching · 60 jours',              amount: '30 300', total: '30 300 F' },
      premium_30 : { title: 'Premium 30 300 F — Recruteur', sub: '10 annonces · Multidiffusion · Matching · 60 jours',              amount: '30 300', total: '30 300 F' },
      illimite   : { title: 'Premium 50 500 F — Recruteur', sub: 'Annonces illimitées · Remontée tête de liste · Support prioritaire', amount: '50 500', total: '50 500 F' },
      premium_50 : { title: 'Premium 50 500 F — Recruteur', sub: 'Annonces illimitées · Remontée tête de liste · Support prioritaire', amount: '50 500', total: '50 500 F' }
    };
    var p = plans[recPlan] || plans.premium_30;
    var el;
    el = document.getElementById('ordTitle');    if (el) el.textContent = p.title;
    el = document.getElementById('ordSub');      if (el) el.textContent = p.sub;
    el = document.getElementById('ordAmount');   if (el) el.textContent = p.amount;
    el = document.getElementById('ordTotal');    if (el) el.textContent = p.total;
    el = document.getElementById('payBtnLabel'); if (el) el.textContent = 'Confirmer le paiement — ' + p.total;
  }
})();

/* ── Infos utilisateur depuis localStorage ── */
(function() {
  var data = {};
  try { data = JSON.parse(localStorage.getItem('cv_premium_data') || '{}'); } catch(e) {}
  // Fallback : lire current_user si cv_premium_data est vide
  if (!data.prenom && !data.nom && !data.email) {
    var user = {};
    try { user = JSON.parse(localStorage.getItem('current_user') || '{}'); } catch(e) {}
    data = {
      nom: user.nom || '',
      prenom: user.prenom || '',
      email: user.email || '',
      tel: user.telephone || user.tel || ''
    };
    if (data.prenom || data.nom || data.email) {
      localStorage.setItem('cv_premium_data', JSON.stringify(data));
    }
  }
  var recap = document.getElementById('candidatRecap');
  if (data.prenom || data.nom || data.email) {
    var fullName = [data.prenom, data.nom].filter(Boolean).join(' ');
    document.getElementById('candidatName').textContent   = fullName || 'Utilisateur';
    document.getElementById('candidatEmail').textContent  = data.email || '';
    var initials = ((data.prenom || '').charAt(0) + (data.nom || '').charAt(0)).toUpperCase() || '?';
    document.getElementById('candidatInitials').textContent = initials;
    recap.style.display = 'flex';
    if (data.tel) { document.getElementById('fPhone').value = data.tel; }
  }
})();

/* ── Sélection méthode ── */
function selectMethod(el) {
  document.querySelectorAll('.pay-method').forEach(function(m) { m.classList.remove('selected'); });
  el.classList.add('selected');
  var r = el.querySelector('input[type="radio"]'); if (r) r.checked = true;
}

/* ── Confirmation paiement ── */
function confirmerPaiement() {
  var phone   = document.getElementById('fPhone').value.trim();
  var methode = document.querySelector('input[name="methode"]:checked');
  var err     = document.getElementById('payError');
  var errMsg  = document.getElementById('payErrorMsg');

  err.classList.remove('show');

  if (!phone) {
    errMsg.textContent = 'Veuillez saisir votre numéro Mobile Money.';
    err.classList.add('show');
    document.getElementById('fPhone').focus();
    setTimeout(function() { err.classList.remove('show'); }, 5000);
    return;
  }
  if (!/^(\+229|229)?[ ]?0?[0-9 ]{8,}$/.test(phone.replace(/\s/g,''))) {
    errMsg.textContent = 'Numéro de téléphone invalide. Vérifiez le format.';
    err.classList.add('show');
    document.getElementById('fPhone').focus();
    setTimeout(function() { err.classList.remove('show'); }, 5000);
    return;
  }

  var btn = document.getElementById('payBtn');
  btn.disabled = true;
  btn.innerHTML = '<svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="animation:spin 1s linear infinite"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Traitement en cours…';

  try {
    var data = {};
    try { data = JSON.parse(localStorage.getItem('cv_premium_data') || '{}'); } catch(e) {}
    data.methode_paiement = methode ? methode.value : 'mtn';
    data.telephone_paiement = phone;
    localStorage.setItem('cv_premium_data', JSON.stringify(data));
  } catch(e) {}

  setTimeout(function() {
    var recPlan = localStorage.getItem('pending_rec_plan');
    var now = new Date();
    var expire = new Date(now);
    expire.setMonth(expire.getMonth() + 1);

    if (recPlan) {
      /* ── Flux Recruteur ── */
      /* Normaliser les plan IDs : 'premium'→'premium_30', 'illimite'→'premium_50' */
      var normPlan = (recPlan === 'illimite' || recPlan === 'premium_50') ? 'premium_50' : 'premium_30';
      var prixRec  = normPlan === 'premium_50' ? '50 500 F' : '30 300 F';
      var labelRec = normPlan === 'premium_50' ? 'Premium 50 500 F' : 'Premium 30 300 F';
      var subRec   = { plan: normPlan, label: labelRec, prix: prixRec, date: now.toISOString().slice(0,10), expire: expire.toISOString().slice(0,10) };
      /* Sauvegarder sous les deux clés pour compatibilité */
      localStorage.setItem('rec_subscription',      JSON.stringify(subRec));
      localStorage.setItem('recruteur_subscription', JSON.stringify(subRec));
      /* Historique */
      var histo = [];
      try { histo = JSON.parse(localStorage.getItem('paiements_rec') || '[]'); } catch(e) {}
      histo.unshift({ date: now.toLocaleDateString('fr-FR'), plan: labelRec, montant: prixRec });
      localStorage.setItem('paiements_rec', JSON.stringify(histo));
      /* Mise à jour session */
      var u = {};
      try { u = JSON.parse(localStorage.getItem('current_user') || '{}'); } catch(e) {}
      u.premium = true; u.plan = normPlan; u.premiumExpiry = expire.toLocaleDateString('fr-FR');
      localStorage.setItem('current_user', JSON.stringify(u));
      /* Flags actions */
      localStorage.setItem('action_abonnement', '1');
      localStorage.setItem('action_offre_publiee', '1');
      localStorage.removeItem('pending_rec_plan');
      /* Redirection dashboard recruteur */
      window.location.href = '../recruteur/tableau-de-bord.html';

    } else {
      /* ── Flux Candidat ── */
      var subCand = { plan: 'Premium', label: 'Premium 1 200 F', prix: '1 200 F', date: now.toISOString().slice(0,10), expire: expire.toISOString().slice(0,10) };
      localStorage.setItem('cv_subscription', JSON.stringify(subCand));
      /* Mise à jour session */
      var u2 = {};
      try { u2 = JSON.parse(localStorage.getItem('current_user') || '{}'); } catch(e) {}
      u2.premium = true; u2.premiumExpiry = expire.toLocaleDateString('fr-FR');
      localStorage.setItem('current_user', JSON.stringify(u2));
      /* Flags actions */
      localStorage.setItem('action_abonnement', '1');
      /* Redirection dashboard candidat */
      window.location.href = '../candidat/tableau-de-bord.html';
    }
  }, 2200);
}

const hamburger  = document.getElementById('hamburger');
const mobileMenu = document.getElementById('mobileMenu');
if (hamburger && mobileMenu) {
  hamburger.addEventListener('click', () => {
    const open = mobileMenu.classList.toggle('open');
    hamburger.classList.toggle('open', open);
    hamburger.setAttribute('aria-expanded', open);
  });
  mobileMenu.querySelectorAll('a').forEach(l => l.addEventListener('click', () => {
    mobileMenu.classList.remove('open'); hamburger.classList.remove('open');
    hamburger.setAttribute('aria-expanded', 'false');
  }));
}
