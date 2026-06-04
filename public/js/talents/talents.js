'use strict';

var pillState = { sexe: '', niveau: '', dispo: '', temps: '' };
var photoDataURL = null;

/* ── Utilitaires ── */
function escHtml(s) {
  return (s || '').toString()
    .replace(/&/g,'&amp;').replace(/</g,'&lt;')
    .replace(/>/g,'&gt;').replace(/"/g,'&quot;').replace(/'/g,'&#39;');
}

function showError(id, msg) {
  var el = document.getElementById(id);
  var msgEl = document.getElementById(id + 'Msg');
  if (!el) return;
  if (msg) {
    el.style.display = 'flex';
    if (msgEl) msgEl.textContent = msg;
    el.scrollIntoView({ behavior: 'smooth', block: 'center' });
  } else {
    el.style.display = 'none';
  }
}

function updateStepIndicators(n) {
  for (var i = 1; i <= 3; i++) {
    var el = document.getElementById('stepIndic' + i);
    if (!el) continue;
    el.className = 'talent-step';
    if (i < n) el.classList.add('talent-step--done');
    if (i === n) el.classList.add('talent-step--active');
  }
  var titles = { 1: 'Mon compte', 2: 'Mon profil & compétences', 3: 'Confirmation' };
  var titleEl = document.getElementById('cardTitle');
  if (titleEl && titles[n]) titleEl.textContent = titles[n];
}

/* ── Auth ── */
function initAuthCheck() {
  var user = (typeof App !== 'undefined') ? App.getCurrentUser() : null;
  if (!user) try { user = JSON.parse(localStorage.getItem('current_user') || 'null'); } catch (e) {}
  var loggedIn = document.getElementById('authLoggedIn');
  var authForms = document.getElementById('authForms');
  if (user && user.prenom) {
    if (loggedIn) loggedIn.style.display = 'flex';
    if (authForms) authForms.style.display = 'none';
    var avatar = document.getElementById('authAvatar');
    var name   = document.getElementById('authName');
    if (avatar) avatar.textContent = ((user.prenom[0] || '') + (user.nom ? user.nom[0] : '')).toUpperCase();
    if (name)   name.textContent = user.prenom + (user.nom ? ' ' + user.nom : '');
    var nomEl = document.getElementById('tNomComplet');
    var telEl = document.getElementById('tWhatsapp');
    if (nomEl && !nomEl.value) nomEl.value = (user.prenom || '') + (user.nom ? ' ' + user.nom : '');
    if (telEl && !telEl.value && user.tel) telEl.value = user.tel;
  } else {
    if (loggedIn) loggedIn.style.display = 'none';
    if (authForms) authForms.style.display = 'block';
  }
}

function seDeconnecter() {
  if (typeof App !== 'undefined') App.logout();
  else { localStorage.removeItem('current_user'); initAuthCheck(); }
}

function switchAuthMode(mode) {
  ['tabRegister','tabLogin'].forEach(function(id, i) {
    var el = document.getElementById(id);
    if (el) el.classList.toggle('active', (mode === 'register') === (i === 0));
  });
  ['panelRegister','panelLogin'].forEach(function(id, i) {
    var el = document.getElementById(id);
    if (el) el.classList.toggle('active', (mode === 'register') === (i === 0));
  });
}

function togglePwd(id, btn) {
  var inp = document.getElementById(id);
  if (!inp) return;
  inp.type = (inp.type === 'text') ? 'password' : 'text';
}

/* ── Navigation étapes ── */
function goStep(n) {
  if (n === 2 && !validateStep1()) return;
  updateStepIndicators(n);
  var step1 = document.getElementById('step1Content');
  var step2 = document.getElementById('step2Content');
  if (step1) step1.hidden = (n !== 1);
  if (step2) step2.hidden = (n !== 2);
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

/* ── Validation étape 1 ── */
function validateStep1() {
  /* Déjà connecté */
  var user = (typeof App !== 'undefined') ? App.getCurrentUser() : null;
  if (!user) try { user = JSON.parse(localStorage.getItem('current_user') || 'null'); } catch (e) {}
  if (user && user.prenom) { showError('err1', ''); return true; }

  var loggedIn = document.getElementById('authLoggedIn');
  if (loggedIn && loggedIn.style.display === 'flex') { return true; }

  var isLogin = document.getElementById('panelLogin') &&
    document.getElementById('panelLogin').classList.contains('active');

  if (isLogin) {
    /* Connexion sans mot de passe — email/téléphone uniquement */
    var id = ((document.getElementById('fLoginEmail') || {}).value || '').trim();
    if (!id) { showError('err1', 'Veuillez renseigner votre email ou téléphone.'); return false; }
    var found = (typeof App !== 'undefined') ? App.findAccount(id) : null;
    if (!found) {
      var comptes = [];
      try { comptes = JSON.parse(localStorage.getItem('comptes_candidats') || '[]'); } catch (e) {}
      found = comptes.find(function(c) { return c.email === id || c.tel === id; });
    }
    if (!found) { showError('err1', 'Aucun compte trouvé avec cet email / téléphone.'); return false; }
    if (typeof App !== 'undefined') App.createSession(found);
    else localStorage.setItem('current_user', JSON.stringify(found));
    initAuthCheck();
    showError('err1', '');
    return true;
  }

  /* Inscription sans mot de passe */
  var nom    = ((document.getElementById('fNom')    || {}).value || '').trim();
  var prenom = ((document.getElementById('fPrenom') || {}).value || '').trim();
  var email2 = ((document.getElementById('fEmail')  || {}).value || '').trim();
  var tel    = ((document.getElementById('fTel')    || {}).value || '').trim();

  if (!nom || !prenom || !email2 || !tel) {
    showError('err1', 'Veuillez remplir tous les champs obligatoires.'); return false;
  }
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email2)) {
    showError('err1', 'Veuillez saisir une adresse email valide.'); return false;
  }

  var comptes2 = [];
  try { comptes2 = JSON.parse(localStorage.getItem('comptes_candidats') || '[]'); } catch (e) {}
  if (comptes2.find(function(c) { return c.email === email2; })) {
    showError('err1', 'Un compte existe déjà avec cet email. Connectez-vous.'); return false;
  }
  var newUser = { id: Date.now(), nom: nom, prenom: prenom, email: email2, tel: tel, role: 'candidat', plan: 'gratuit', created_at: new Date().toISOString() };
  if (typeof App !== 'undefined') { App.saveAccount(newUser); App.createSession(newUser); }
  else { comptes2.push(newUser); localStorage.setItem('comptes_candidats', JSON.stringify(comptes2)); localStorage.setItem('current_user', JSON.stringify(newUser)); }
  initAuthCheck();
  showError('err1', '');
  return true;
}

/* ── Validation étape 2 ── */
function validateStep2() {
  var nom = ((document.getElementById('tNomComplet') || {}).value || '').trim();
  if (!nom) { showError('err2', 'Veuillez saisir votre nom complet.'); return false; }
  if (!pillState.sexe) { showError('err2', 'Veuillez sélectionner votre sexe.'); return false; }
  var age = parseInt((document.getElementById('tAge') || {}).value || '0');
  if (!age || age < 14 || age > 80) { showError('err2', 'Veuillez indiquer un €ge valide (14–80 ans).'); return false; }
  var tel = ((document.getElementById('tWhatsapp') || {}).value || '').trim();
  if (!tel) { showError('err2', 'Veuillez saisir votre numéro WhatsApp.'); return false; }
  var ville = ((document.getElementById('tVille') || {}).value || '').trim();
  if (!ville) { showError('err2', 'Veuillez indiquer votre ville.'); return false; }
  var comp = (document.getElementById('tCompetence') || {}).value || '';
  if (!comp) { showError('err2', 'Veuillez sélectionner votre compétence principale.'); return false; }
  if (comp === 'Autre') {
    var autre = ((document.getElementById('tCompetenceAutre') || {}).value || '').trim();
    if (!autre) { showError('err2', 'Veuillez préciser votre compétence.'); return false; }
  }
  if (!pillState.niveau) { showError('err2', 'Veuillez sélectionner votre niveau.'); return false; }
  var duree = (document.getElementById('tDureeExp') || {}).value || '';
  if (!duree) { showError('err2', 'Veuillez indiquer depuis combien de temps vous exercez.'); return false; }
  var desc = ((document.getElementById('tDescription') || {}).value || '').trim();
  if (!desc || desc.length < 20) { showError('err2', 'Veuillez décrire votre savoir-faire (20 caractères minimum).'); return false; }
  if (!pillState.dispo) { showError('err2', 'Veuillez indiquer votre disponibilité.'); return false; }
  if (!pillState.temps) { showError('err2', 'Veuillez indiquer votre temps de travail.'); return false; }
  showError('err2', '');
  return true;
}

/* ── Pills ── */
function selectPill(group, value) {
  pillState[group] = value;
  var containerMap = { sexe: 'sexePills', niveau: 'niveauPills', dispo: 'dispoPills', temps: 'tempsPills' };
  var hiddenMap    = { sexe: 'tSexe',     niveau: 'tNiveau',     dispo: 'tDispo',     temps: 'tTemps' };
  var container = document.getElementById(containerMap[group]);
  if (container) {
    container.querySelectorAll('.t-pill').forEach(function(p) {
      p.classList.toggle('t-pill--active', p.dataset.val === value);
    });
  }
  var hidden = document.getElementById(hiddenMap[group]);
  if (hidden) hidden.value = value;
}

/* ── Compétence "Autre" ── */
function handleCompetenceChange() {
  var val = (document.getElementById('tCompetence') || {}).value || '';
  var altreInput = document.getElementById('tCompetenceAutre');
  if (altreInput) altreInput.style.display = (val === 'Autre') ? 'block' : 'none';
}

/* ── Photo upload ── */
function initPhotoUpload() {
  var input = document.getElementById('tPhoto');
  if (!input) return;
  input.addEventListener('change', function() {
    var file = this.files && this.files[0];
    if (!file) return;
    if (file.size > 3 * 1024 * 1024) { alert('La photo ne doit pas dépasser 3 Mo.'); return; }
    var reader = new FileReader();
    reader.onload = function(e) {
      photoDataURL = e.target.result;
      var avatar = document.getElementById('photoAvatar');
      if (avatar) avatar.innerHTML = '<img src="' + e.target.result + '" alt="Photo" style="width:100%;height:100%;object-fit:cover;border-radius:50%" />';
    };
    reader.readAsDataURL(file);
  });
}

/* ── Doc uploads ── */
function initDocUploads() {
  [
    { inputId: 'fileAttestation', zoneId: 'docAttestation', fnId: 'fnAttestation' },
    { inputId: 'fileCertificat',  zoneId: 'docCertificat',  fnId: 'fnCertificat'  },
    { inputId: 'filePhotos',      zoneId: 'docPhotos',       fnId: 'fnPhotos'       },
    { inputId: 'fileCV',          zoneId: 'docCV',           fnId: 'fnCV'           }
  ].forEach(function(d) {
    var input = document.getElementById(d.inputId);
    var zone  = document.getElementById(d.zoneId);
    var fn    = document.getElementById(d.fnId);
    if (!input || !zone || !fn) return;
    input.addEventListener('change', function() {
      if (this.files && this.files.length > 0) {
        var names = Array.prototype.slice.call(this.files).map(function(f) { return f.name; });
        fn.textContent = names.join(', ');
        zone.classList.add('t-doc-zone--loaded');
      }
    });
    input.addEventListener('click', function(e) { e.stopPropagation(); });
  });
}

/* ── Compteur description ── */
function initDescCounter() {
  var desc  = document.getElementById('tDescription');
  var count = document.getElementById('descCount');
  if (!desc || !count) return;
  desc.addEventListener('input', function() {
    var n = this.value.length;
    count.textContent = n + ' / 500 caractères';
    count.style.color = n > 500 ? '#dc2626' : '#64748b';
  });
}

/* ── Soumission ── */
function soumettreProfile() {
  if (!validateStep2()) return;

  var btn = document.getElementById('submitBtn');
  if (btn) { btn.disabled = true; btn.textContent = 'Enregistrement…'; }

  var user = null;
  try { user = JSON.parse(localStorage.getItem('current_user') || 'null'); } catch (e) {}

  var competence = (document.getElementById('tCompetence') || {}).value || '';
  if (competence === 'Autre') {
    competence = ((document.getElementById('tCompetenceAutre') || {}).value || '').trim() || 'Autre';
  }

  var talent = {
    id: 'talent-' + Date.now(),
    date: new Date().toISOString(),
    userId: user ? user.id : null,
    nomComplet: ((document.getElementById('tNomComplet') || {}).value || '').trim(),
    sexe: pillState.sexe,
    age: (document.getElementById('tAge') || {}).value || '',
    whatsapp: ((document.getElementById('tWhatsapp') || {}).value || '').trim(),
    ville: ((document.getElementById('tVille') || {}).value || '').trim(),
    quartier: ((document.getElementById('tQuartier') || {}).value || '').trim(),
    email: ((document.getElementById('tEmailFacultatif') || {}).value || '').trim(),
    photo: photoDataURL,
    competence: competence,
    niveau: pillState.niveau,
    dureeExp: (document.getElementById('tDureeExp') || {}).value || '',
    lieuTravail: ((document.getElementById('tLieuTravail') || {}).value || '').trim(),
    description: ((document.getElementById('tDescription') || {}).value || '').trim(),
    video: ((document.getElementById('tVideo') || {}).value || '').trim(),
    dispo: pillState.dispo,
    temps: pillState.temps,
    salaire: ((document.getElementById('tSalaire') || {}).value || '').trim(),
    mobilite: (document.getElementById('tMobilite') || {}).checked || false,
    statut: 'en_verification',
    badge: null
  };

  var talents = [];
  try { talents = JSON.parse(localStorage.getItem('talents_deposes') || '[]'); } catch (e) {}
  talents.unshift(talent);
  try { localStorage.setItem('talents_deposes', JSON.stringify(talents)); } catch (e) {}

  var formCard = document.getElementById('talentFormCard');
  var successCard = document.getElementById('successCard');
  if (formCard) formCard.hidden = true;
  if (successCard) successCard.hidden = false;

  updateStepIndicators(3);
  afficherTalents();
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

/* ── Galerie ── */
var SEED_TALENTS = [
  { id: 's1', initiales: 'A. K.', competence: 'Cuisine',               niveau: 'Expérimenté',    ville: 'Cotonou',        dispo: 'Disponible immédiatement', dureeExp: '8 ans',  badge: 'recommande' },
  { id: 's2', initiales: 'M. D.', competence: 'Graphisme',             niveau: 'Intermédiaire',  ville: 'Porto-Novo',     dispo: 'Dans 1 mois',              dureeExp: '3 ans',  badge: 'verifie'    },
  { id: 's3', initiales: 'F. A.', competence: 'Coiffure',              niveau: 'Expérimenté',    ville: 'Cotonou',        dispo: 'Disponible immédiatement', dureeExp: '5 ans',  badge: 'verifie'    },
  { id: 's4', initiales: 'J. H.', competence: 'Électricité',           niveau: 'Intermédiaire',  ville: 'Abomey-Calavi',  dispo: 'Disponible immédiatement', dureeExp: '4 ans',  badge: 'competence' },
  { id: 's5', initiales: 'S. M.', competence: 'Community management',  niveau: 'Intermédiaire',  ville: 'Cotonou',        dispo: 'Temps partiel',            dureeExp: '2 ans',  badge: 'verifie'    },
  { id: 's6', initiales: 'B. T.', competence: 'Mécanique',             niveau: 'Expérimenté',    ville: 'Parakou',        dispo: 'Disponible immédiatement', dureeExp: '10 ans', badge: 'recommande' }
];

var AVATAR_COLORS = ['#042C53','#185FA5','#38A169','#d97706','#7c3aed','#db2777'];

function getBadge(badge) {
  if (badge === 'recommande') return { text: 'Talent recommandé',  cls: 'tpc-badge--gold'  };
  if (badge === 'competence') return { text: 'Compétence validée', cls: 'tpc-badge--blue'  };
  return                              { text: 'Profil vérifié',    cls: 'tpc-badge--green' };
}

function niveauLabel(n) {
  return { debutant: 'Débutant', intermediaire: 'Intermédiaire', experimente: 'Expérimenté' }[n] || (n || 'N/A');
}

function maskInitials(nom) {
  if (!nom) return '?';
  var p = nom.trim().split(/\s+/);
  return p.length > 1 ? p[0][0].toUpperCase() + '. ' + p[p.length-1][0].toUpperCase() + '.' : p[0][0].toUpperCase() + '.';
}

function renderCard(t, idx) {
  var badge = getBadge(t.badge);
  var init  = t.initiales || maskInitials(t.nomComplet || '');
  var niv   = niveauLabel(t.niveau);
  var bg    = AVATAR_COLORS[idx % AVATAR_COLORS.length];
  var duree = t.dureeExp ? ' · ' + escHtml(t.dureeExp) : '';

  return '<div class="talent-profile-card">'
    + '<div class="tpc-header">'
    +   '<div class="tpc-avatar" style="background:' + bg + '">' + escHtml(init) + '</div>'
    +   '<div class="tpc-info">'
    +     '<div class="tpc-name">' + escHtml(init) + ' <span class="tpc-masked-tag">identité masquée</span></div>'
    +     '<div class="tpc-competence">' + escHtml(t.competence) + '</div>'
    +   '</div>'
    + '</div>'
    + '<div class="tpc-meta"><span class="tpc-badge ' + badge.cls + '">' + badge.text + '</span></div>'
    + '<div class="tpc-body">'
    +   '<div class="tpc-detail"><svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>' + escHtml(t.ville) + '</div>'
    +   '<div class="tpc-detail"><svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>' + escHtml(niv) + duree + '</div>'
    +   '<div class="tpc-detail"><svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>' + escHtml(t.dispo) + '</div>'
    +   '<div class="tpc-contact-masked"><svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>+229 ••• •••••</div>'
    + '</div>'
    + '<div class="tpc-footer"><a href="../auth/connexion.html" class="tpc-btn">Voir le profil complet</a></div>'
    + '</div>';
}

function afficherTalents() {
  var grid = document.getElementById('talentsGrid');
  if (!grid) return;

  var stored = [];
  try { stored = JSON.parse(localStorage.getItem('talents_deposes') || '[]'); } catch (e) {}

  var usedIds = {};
  SEED_TALENTS.forEach(function(s) { usedIds[s.id] = true; });

  var display = [];
  stored.filter(function(t) { return t.statut === 'verifie'; }).forEach(function(t) { display.push(t); });
  SEED_TALENTS.forEach(function(s) { display.push(s); });
  display = display.slice(0, 6);

  if (display.length === 0) {
    grid.innerHTML = '<p class="talents-grid__empty">Aucun talent vérifié pour le moment. Soyez le premier !</p>';
    return;
  }
  grid.innerHTML = display.map(function(t, i) { return renderCard(t, i); }).join('');
}

/* ── Menu mobile ── */
function initHamburger() {
  var btn  = document.getElementById('hamburger');
  var menu = document.getElementById('mobileMenu');
  if (!btn || !menu) return;
  btn.addEventListener('click', function() {
    var open = menu.classList.toggle('open');
    btn.classList.toggle('open', open);
    btn.setAttribute('aria-expanded', open);
  });
}

/* ── Redirection tableau de bord ── */
function allerAuTableauDeBord() {
  window.location.href = 'tableau-de-bord.html';
}

/* ── Init ── */
document.addEventListener('DOMContentLoaded', function() {
  initHamburger();
  initAuthCheck();
  initPhotoUpload();
  initDocUploads();
  initDescCounter();
  afficherTalents();

  var heroBtn = document.getElementById('heroCta');
  if (heroBtn) {
    heroBtn.addEventListener('click', function(e) {
      e.preventDefault();
      var target = document.getElementById('formulaire');
      if (target) target.scrollIntoView({ behavior: 'smooth' });
    });
  }
});
