﻿/* ══════════════════════════════════════════
     DONNÉES DES OFFRES
  ══════════════════════════════════════════ */
  const OFFRES = [
    { id:0, titre:"Développeur Web Full-Stack",          contrat:"CDI",   secteur:"Informatique",           entreprise:"TechAfrique",      localisation:"Cotonou, Bénin",        teletravail:"Partiel", niveauExp:"3 à 5 ans",      niveauEtudes:"Bac+3",          date:"12 avril 2026" },
    { id:1, titre:"Chargé(e) de Communication",          contrat:"CDI",   secteur:"Communication",           entreprise:"MediaGroup BJ",    localisation:"Cotonou, Bénin",        teletravail:"Non",     niveauExp:"2 à 4 ans",      niveauEtudes:"Bac+3",          date:"10 avril 2026" },
    { id:2, titre:"Stage en Marketing Digital",           contrat:"Stage", secteur:"Marketing",               entreprise:"StartupHub Lomé",  localisation:"Lomé, Togo",            teletravail:"Oui",     niveauExp:"Débutant",       niveauEtudes:"Bac+2",          date:"8 avril 2026"  },
    { id:3, titre:"Comptable Senior",                     contrat:"CDI",   secteur:"Finance / Comptabilité",  entreprise:"Finance & Co",     localisation:"Porto-Novo, Bénin",     teletravail:"Non",     niveauExp:"5 à 10 ans",     niveauEtudes:"Bac+4",          date:"5 avril 2026"  },
    { id:4, titre:"Responsable Ressources Humaines",      contrat:"CDI",   secteur:"Ressources humaines",     entreprise:"BTP Construct",    localisation:"Cotonou, Bénin",        teletravail:"Non",     niveauExp:"4 à 7 ans",      niveauEtudes:"Bac+4",          date:"3 avril 2026"  },
    { id:5, titre:"Designer UI/UX",                       contrat:"CDD",   secteur:"Design / Créatif",        entreprise:"DigitalLab Africa",localisation:"Cotonou, Bénin",        teletravail:"Partiel", niveauExp:"2 à 5 ans",      niveauEtudes:"Bac+3",          date:"1 avril 2026"  },
    { id:6, titre:"Ingénieur Électrique",                 contrat:"CDI",   secteur:"Génie civil / Électricité",entreprise:"EnergySol",       localisation:"Parakou, Bénin",        teletravail:"Non",     niveauExp:"3 à 6 ans",      niveauEtudes:"Bac+5",          date:"28 mars 2026"  },
    { id:7, titre:"Assistant(e) de Direction",            contrat:"CDD",   secteur:"Administration",          entreprise:"Groupe Benin SA",  localisation:"Cotonou, Bénin",        teletravail:"Non",     niveauExp:"2 à 3 ans",      niveauEtudes:"Bac+3",          date:"25 mars 2026"  },
    { id:8, titre:"Journaliste",                          contrat:"CDI",   secteur:"Médias / Presse",         entreprise:"ECOMA",            localisation:"Cotonou, Bénin",        teletravail:"Partiel", niveauExp:"1 à 5 ans",      niveauEtudes:"Bac+3",          date:"20 mars 2026"  },
    { id:9, titre:"Développeur Mobile Flutter (H/F)",     contrat:"Stage", secteur:"Informatique",            entreprise:"TechAfrique",      localisation:"Cotonou · Gbégamey",   teletravail:"Non",     niveauExp:"Débutant à 5 ans",niveauEtudes:"Bac+3 minimum",  date:"10 avril 2026" }
  ];

  /* ── Chargement du panneau droit ── */
  function loadOffreSummary() {
    const params  = new URLSearchParams(window.location.search);
    const id      = parseInt(params.get('id'), 10);
    const offre   = OFFRES.find(o => o.id === id) || OFFRES[9];

    document.getElementById('sumLogo').textContent        = offre.entreprise.charAt(0).toUpperCase();
    document.getElementById('sumContrat').textContent     = offre.contrat;
    document.getElementById('sumTitre').textContent       = offre.titre + '\n' + offre.localisation;
    document.getElementById('sumEntreprise').textContent  = offre.entreprise;
    document.getElementById('sumDate').textContent        = offre.date;
    document.getElementById('sumSecteur').textContent     = offre.secteur;
    document.getElementById('sumLocalisation').textContent= offre.localisation;
    document.getElementById('sumTeletravail').textContent = offre.teletravail;
    document.getElementById('sumExp').textContent         = offre.niveauExp;
    document.getElementById('sumEtudes').textContent      = offre.niveauEtudes;
    document.getElementById('sumContratRow').textContent  = offre.contrat;
    document.getElementById('btnVoirOffre').href          = 'detail-offre.html?id=' + offre.id;

    /* Pré-remplir les champs depuis la session */
    if (typeof App !== 'undefined') {
      var u = App.getCurrentUser();
      if (u) {
        var _set = function(id, v) { var el = document.getElementById(id); if (el && !el.value && v) el.value = v; };
        _set('inputPrenom', u.prenom);
        _set('inputNom',    u.nom);
        _set('inputEmail',  u.email);
        _set('inputTel',    u.tel);
      }
    }

    /* Pré-remplir le message avec le titre de l'offre */
    const msgArea = document.getElementById('messageArea');
    msgArea.value = `Bonjour,\n\nJe vous adresse ma candidature pour le poste de ${offre.titre} au sein de votre entreprise.\n\nPassionné(e) par mon domaine et convaincu(e) que mon profil correspond à vos attentes, je reste disponible pour un entretien à votre convenance.\n\nCordialement,`;
    updateCounter();
  }

  /* ══════════════════════════════════════════
     GESTION DES ÉTAPES
  ══════════════════════════════════════════ */
  let currentStep = 1;

  function updateStepsBar(n) {
    for (let i = 1; i <= 3; i++) {
      const el  = document.getElementById('stepDot' + i);
      const dot = el.querySelector('.step__dot');
      el.classList.remove('step--active', 'step--done');
      if (i < n) {
        el.classList.add('step--done');
        dot.innerHTML = '<i class="fa-solid fa-check" style="font-size:10px;"></i>';
      } else if (i === n) {
        el.classList.add('step--active');
        dot.textContent = i;
      } else {
        dot.textContent = i;
      }
    }
  }

  function showError(step, msg) {
    const err    = document.getElementById('err' + step);
    const msgEl  = document.getElementById('err' + step + 'Msg');
    msgEl.textContent = msg;
    err.classList.add('show');
    setTimeout(() => err.classList.remove('show'), 5000);
  }

  function validateStep1() {
    const civ  = document.getElementById('inputCivilite');
    const pre  = document.getElementById('inputPrenom');
    const nom  = document.getElementById('inputNom');
    const em   = document.getElementById('inputEmail');
    const tel  = document.getElementById('inputTel');
    const msg  = document.getElementById('messageArea');
    const cgu  = document.getElementById('cguCheck');

    if (!civ.value)               { showError(1, 'Veuillez sélectionner votre civilité.'); civ.focus(); return false; }
    if (!pre.value.trim())        { showError(1, 'Veuillez saisir votre prénom.'); pre.focus(); return false; }
    if (!nom.value.trim())        { showError(1, 'Veuillez saisir votre nom.'); nom.focus(); return false; }
    if (!em.value.trim() || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(em.value)) { showError(1, 'Veuillez saisir un e-mail valide.'); em.focus(); return false; }
    if (!tel.value.trim())        { showError(1, 'Veuillez saisir votre numéro de téléphone.'); tel.focus(); return false; }
    if (msg.value.trim().length < 20) { showError(1, 'Votre message doit comporter au moins 20 caractères.'); msg.focus(); return false; }
    if (!cgu.checked)             { showError(1, 'Veuillez accepter les CGU pour continuer.'); return false; }
    return true;
  }

  function validateStep2() {
    const cv = document.getElementById('cvInput');
    if (!cv.files || cv.files.length === 0) {
      showError(2, 'Veuillez téléverser votre CV avant de continuer.');
      return false;
    }
    return true;
  }

  function populateConfirmation() {
    const civ   = document.getElementById('inputCivilite').value;
    const pre   = document.getElementById('inputPrenom').value.trim();
    const nom   = document.getElementById('inputNom').value.trim();
    const em    = document.getElementById('inputEmail').value.trim();
    const tel   = document.getElementById('inputTel').value.trim();
    const msg   = document.getElementById('messageArea').value.trim();
    const cv    = document.getElementById('cvInput');
    const lm    = document.getElementById('lmInput');

    document.getElementById('confNom').textContent   = civ + ' ' + pre + ' ' + nom;
    document.getElementById('confEmail').textContent  = em;
    document.getElementById('confTel').textContent    = '+229 ' + tel;
    document.getElementById('confMessage').textContent = msg;

    /* Documents */
    const docsEl = document.getElementById('confDocs');
    docsEl.innerHTML = '';

    function docItem(file, label, cls) {
      const ext = file.name.split('.').pop().toLowerCase();
      const size = file.size < 1024*1024
        ? (file.size/1024).toFixed(1) + ' Ko'
        : (file.size/(1024*1024)).toFixed(2) + ' Mo';
      const iconMap = { pdf: 'fa-file-pdf', doc: 'fa-file-word', docx: 'fa-file-word', png: 'fa-file-image', jpg: 'fa-file-image', jpeg: 'fa-file-image' };
      const icon = iconMap[ext] || 'fa-file';
      return `<div class="conf-doc">
        <i class="fa-solid ${icon}"></i>
        <div class="conf-doc__info">
          <div class="conf-doc__name">${file.name}</div>
          <div class="conf-doc__meta">${ext.toUpperCase()} · ${size}</div>
        </div>
        <span class="conf-doc__badge ${cls}">${label}</span>
      </div>`;
    }

    if (cv.files && cv.files[0]) docsEl.innerHTML += docItem(cv.files[0], 'CV', 'conf-doc__badge--req');
    if (lm.files && lm.files[0]) docsEl.innerHTML += docItem(lm.files[0], 'Lettre', 'conf-doc__badge--opt');
    if (!docsEl.innerHTML) docsEl.innerHTML = '<p style="font-size:13px;color:var(--text-muted);">Aucun document</p>';
  }

  function goStep(n) {
    if (n > currentStep) {
      if (currentStep === 1 && !validateStep1()) return;
      if (currentStep === 2 && !validateStep2()) return;
    }
    if (n === 3) populateConfirmation();

    document.getElementById('step1Panel').hidden = (n !== 1);
    document.getElementById('step2Panel').hidden = (n !== 2);
    document.getElementById('step3Panel').hidden = (n !== 3);

    updateStepsBar(n);
    currentStep = n;

    /* Scroll vers le haut du formulaire */
    document.getElementById('formWrapper').scrollIntoView({ behavior: 'smooth', block: 'start' });
  }

  /* ══════════════════════════════════════════
     UPLOAD DE FICHIERS
  ══════════════════════════════════════════ */
  const EXT_MAP = {
    pdf:  { cls: 'file-preview__icon--pdf', icon: 'fa-file-pdf',   label: 'PDF'   },
    doc:  { cls: 'file-preview__icon--doc', icon: 'fa-file-word',  label: 'Word'  },
    docx: { cls: 'file-preview__icon--doc', icon: 'fa-file-word',  label: 'Word'  },
    png:  { cls: 'file-preview__icon--img', icon: 'fa-file-image', label: 'Image' },
    jpg:  { cls: 'file-preview__icon--img', icon: 'fa-file-image', label: 'Image' },
    jpeg: { cls: 'file-preview__icon--img', icon: 'fa-file-image', label: 'Image' },
  };

  function formatSize(b) {
    if (b < 1024)      return b + ' o';
    if (b < 1048576)   return (b/1024).toFixed(1) + ' Ko';
    return (b/1048576).toFixed(2) + ' Mo';
  }

  function initUpload(inputId, zoneId, previewId, imgId, iconId, iconIId, nameId, metaId, removeId) {
    const input     = document.getElementById(inputId);
    const zone      = document.getElementById(zoneId);
    const preview   = document.getElementById(previewId);
    const imgEl     = document.getElementById(imgId);
    const iconWrap  = document.getElementById(iconId);
    const iconI     = document.getElementById(iconIId);
    const nameEl    = document.getElementById(nameId);
    const metaEl    = document.getElementById(metaId);
    const removeBtn = document.getElementById(removeId);

    function show(file) {
      const ext  = file.name.split('.').pop().toLowerCase();
      const info = EXT_MAP[ext] || { cls: 'file-preview__icon--doc', icon: 'fa-file', label: ext.toUpperCase() };
      const isImg = ['png','jpg','jpeg'].includes(ext);

      nameEl.textContent = file.name;
      metaEl.textContent = info.label + ' · ' + formatSize(file.size);
      iconWrap.className = 'file-preview__icon ' + info.cls;
      iconI.className    = 'fa-solid ' + info.icon;
      imgEl.style.display   = 'none';
      iconWrap.style.display = 'flex';

      if (isImg) {
        const r = new FileReader();
        r.onload = e => { imgEl.src = e.target.result; imgEl.style.display = 'block'; iconWrap.style.display = 'none'; };
        r.readAsDataURL(file);
      }
      zone.classList.add('has-file');
      preview.classList.add('visible');
    }

    function clear() {
      input.value = '';
      preview.classList.remove('visible');
      zone.classList.remove('has-file');
      imgEl.src = '';
      imgEl.style.display   = 'none';
      iconWrap.style.display = 'flex';
    }

    input.addEventListener('change', () => { if (input.files[0]) show(input.files[0]); });
    removeBtn.addEventListener('click', clear);
    zone.addEventListener('dragover', e => { e.preventDefault(); zone.style.borderColor = 'var(--blue-light)'; });
    zone.addEventListener('dragleave', () => { zone.style.borderColor = ''; });
    zone.addEventListener('drop', e => {
      e.preventDefault(); zone.style.borderColor = '';
      const f = e.dataTransfer.files[0];
      if (f) { const dt = new DataTransfer(); dt.items.add(f); input.files = dt.files; show(f); }
    });
  }

  /* ── Compteur de caractères ── */
  function updateCounter() {
    const ta  = document.getElementById('messageArea');
    const cnt = document.getElementById('charCount');
    const len = ta.value.length;
    cnt.textContent = len;
    cnt.style.color = len > 1900 ? '#185FA5' : len > 1500 ? '#d97706' : '#64748b';
  }

  /* ══════════════════════════════════════════
     SOUMISSION
  ══════════════════════════════════════════ */
  function submitCandidature() {
    const params  = new URLSearchParams(window.location.search);
    const offreId = parseInt(params.get('id'), 10);
    const offre   = OFFRES.find(o => o.id === offreId);
    const prenom  = document.getElementById('inputPrenom').value.trim();
    const nom     = document.getElementById('inputNom').value.trim();
    const date    = new Date().toLocaleDateString('fr-FR', { day:'numeric', month:'long', year:'numeric' });

    const data = { offreId: isNaN(offreId) ? null : offreId, prenom, nom, date };
    localStorage.setItem('candidature_data', JSON.stringify(data));

    const liste = JSON.parse(localStorage.getItem('mes_candidatures') || '[]');
    liste.push({
      uid: Date.now(),
      offreId: data.offreId,
      titre:      offre ? offre.titre      : 'Poste non précisé',
      entreprise: offre ? offre.entreprise : 'Entreprise non précisée',
      prenom, nom, date,
      statut: 'En attente'
    });
    localStorage.setItem('mes_candidatures', JSON.stringify(liste));
    window.location.href = 'candidature-succes.html';
  }

  /* ══════════════════════════════════════════
     INIT
  ══════════════════════════════════════════ */
  (function init() {
    /* Auth check */
    const isLoggedIn = true;
    const gate = document.getElementById('authGate');
    const form = document.getElementById('formWrapper');
    if (!isLoggedIn) {
      form.style.display = 'none';
      gate.classList.add('visible');
      document.querySelector('.steps-bar').style.display = 'none';
    } else {
      gate.style.display = 'none';
    }

    /* Upload zones */
    initUpload('cvInput','uploadZoneCV','previewCV','previewCVImg','previewCVIcon','previewCVIconI','previewCVName','previewCVMeta','removeCVBtn');
    initUpload('lmInput','uploadZoneLM','previewLM','previewLMImg','previewLMIcon','previewLMIconI','previewLMName','previewLMMeta','removeLMBtn');

    /* Panneau droit */
    loadOffreSummary();

    /* Menu mobile */
    const hamburgerBtn = document.getElementById('hamburger');
    const mobileMenuEl = document.getElementById('mobileMenu');
    if (hamburgerBtn && mobileMenuEl) {
      hamburgerBtn.addEventListener('click', () => {
        const open = mobileMenuEl.classList.toggle('open');
        hamburgerBtn.classList.toggle('open', open);
        hamburgerBtn.setAttribute('aria-expanded', open);
      });
      mobileMenuEl.querySelectorAll('a').forEach(a => a.addEventListener('click', () => {
        mobileMenuEl.classList.remove('open');
        hamburgerBtn.classList.remove('open');
        hamburgerBtn.setAttribute('aria-expanded', 'false');
      }));
    }
  })();
