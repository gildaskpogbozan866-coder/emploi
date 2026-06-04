﻿/* ═══════════════════════════════════════════════════════
     DONNÉES
  ═══════════════════════════════════════════════════════ */
  const METIERS = [
    "Administrateur réseau","Agent commercial","Agent de sécurité","Analyste financier",
    "Architecte","Assistant comptable","Assistant de direction","Auditeur","Biologiste",
    "Chargé de communication","Chargé de recrutement","Chargé marketing","Chef de projet",
    "Chef de projet IT","Chimiste","Commercial terrain","Community Manager","Comptable",
    "Consultant","Contrôleur de gestion","Coordinateur logistique","Data Analyst",
    "Data Scientist","Designer graphique","Designer UI/UX","Développeur mobile",
    "Développeur web","Directeur commercial","Directeur financier","Directeur RH",
    "Économiste","Électricien","Enseignant","Formateur","Géomètre","Gestionnaire paie",
    "Illustrateur","Infirmier","Ingénieur BTP","Ingénieur électrique","Ingénieur logiciel",
    "Ingénieur mécanique","Ingénieur réseau","Journaliste","Juriste","Logisticien",
    "Manager","Médecin","Pharmacien","Photographe","Plombier","Product Manager",
    "Responsable achats","Responsable commercial","Responsable communication",
    "Responsable financier","Responsable IT","Responsable logistique","Responsable qualité",
    "Responsable RH","Secrétaire","Technicien de maintenance","Technicien informatique",
    "Technico-commercial","Traducteur","Vidéaste"
  ];

  const SECTEURS = [
    "Administration publique","Agroalimentaire","Agriculture","Assurance","Audit & Conseil",
    "Banque & Finance","BTP & Construction","Commerce & Distribution","Communication",
    "Culture & Arts","Économie solidaire","Éducation & Formation","Énergie","Environnement",
    "Hôtellerie & Tourisme","Immobilier","Import/Export","Industrie","Informatique & Numérique",
    "Juridique","Logistique & Transport","Marketing","Médias","Médical & Santé",
    "Mining & Ressources naturelles","ONG & Associations","Pétrole & Gaz","Recherche",
    "Ressources humaines","Restauration","Sécurité","Télécommunications","Textile & Mode"
  ];

  const REGIONS = [
    "Abomey","Abomey-Calavi","Aplahoué","Cotonou","Djougou","Kandi","Lokossa",
    "Natitingou","Parakou","Pobè","Porto-Novo","Savalou","International"
  ];

  const LANGUES = [
    "Français","Anglais","Arabe","Espagnol","Portugais","Allemand","Chinois",
    "Fon","Yoruba","Haoussa","Dendi","Bariba"
  ];

  /* ═══════════════════════════════════════════════════════
     INIT DROPDOWNS
  ═══════════════════════════════════════════════════════ */
  const dropState = {
    metier:  { list: METIERS,  max: 3, selected: [] },
    secteur: { list: SECTEURS, max: 5, selected: [] },
    region:  { list: REGIONS,  max: 99, selected: [] },
    langue:  { list: LANGUES,  max: 99, selected: [] }
  };

  function buildOptions(key) {
    const s = dropState[key];
    const search = document.getElementById('cs-' + key).value.toLowerCase();
    const cont = document.getElementById('co-' + key);
    const filtered = s.list.filter(o => o.toLowerCase().includes(search));
    cont.innerHTML = filtered.map(o => {
      const sel = s.selected.includes(o);
      return `<div class="chip-option${sel ? ' selected' : ''}" onclick="toggleOption('${key}','${o.replace(/'/g,"\\'")}',this)">${o}</div>`;
    }).join('');
  }

  function openDrop(key) {
    buildOptions(key);
    document.getElementById('co-' + key).classList.add('open');
  }
  function toggleDrop(key, e) {
    const cont = document.getElementById('co-' + key);
    if (cont.classList.contains('open')) { cont.classList.remove('open'); }
    else { openDrop(key); document.getElementById('cs-' + key).focus(); }
  }
  function filterOptions(key) { buildOptions(key); openDrop(key); }

  function toggleOption(key, val, el) {
    const s = dropState[key];
    const idx = s.selected.indexOf(val);
    if (idx > -1) {
      s.selected.splice(idx, 1);
    } else {
      if (s.selected.length >= s.max) {
        shakeBadge(key); return;
      }
      s.selected.push(val);
    }
    renderChips(key);
    buildOptions(key);
    updateProgress();
  }

  function renderChips(key) {
    const s = dropState[key];
    const field = document.getElementById('cf-' + key);
    const input = document.getElementById('cs-' + key);
    field.querySelectorAll('.chip').forEach(c => c.remove());
    s.selected.forEach(val => {
      const chip = document.createElement('span');
      chip.className = 'chip';
      chip.innerHTML = `${val}<button type="button" class="chip__remove" onclick="toggleOption('${key}','${val.replace(/'/g,"\\'")}',null)">×</button>`;
      field.insertBefore(chip, input);
    });
    input.placeholder = s.selected.length ? '' : (key === 'metier' ? 'Rechercher un métier…' : key === 'secteur' ? 'Rechercher un secteur…' : key === 'region' ? 'Sélectionner une région…' : 'Ajouter une langue…');
  }

  function shakeBadge(key) {
    const field = document.getElementById('cf-' + key);
    field.style.borderColor = '#185FA5';
    field.style.boxShadow = '0 0 0 3px rgba(24,95,165,0.15)';
    setTimeout(() => { field.style.borderColor = ''; field.style.boxShadow = ''; }, 1000);
  }

  /* Close dropdowns on outside click */
  document.addEventListener('click', e => {
    Object.keys(dropState).forEach(key => {
      const wrap = document.getElementById('cd-' + key);
      if (wrap && !wrap.contains(e.target)) {
        document.getElementById('co-' + key).classList.remove('open');
      }
    });
  });

  /* ═══════════════════════════════════════════════════════
     CHECKBOX GROUPS
  ═══════════════════════════════════════════════════════ */
  const cbgOrder = {
    exp:    ['aucune','debutant','1-2','3-5','5+'],
    etudes: ['aucun','cep','bepc','bac','bts','licence','master']
  };

  function cbgAll(group, state) {
    document.querySelectorAll('#cbg-' + group + ' input[type="checkbox"]').forEach(cb => {
      cb.checked = state;
      cb.closest('.cbg-item').classList.toggle('checked', state);
    });
    updateProgress();
  }

  function cbgMode(group, mode, btn) {
    const items = cbgOrder[group];
    const all = document.querySelectorAll('#cbg-' + group + ' input[type="checkbox"]');

    // Reset active buttons
    btn.closest('.cbg-controls').querySelectorAll('.cbg-ctrl-btn').forEach(b => b.classList.remove('cbg-ctrl-btn--active'));
    btn.classList.add('cbg-ctrl-btn--active');

    all.forEach(cb => { cb.checked = false; cb.closest('.cbg-item').classList.remove('checked'); });

    // Find first checked index (default: 0)
    let anchor = 0;
    all.forEach((cb, i) => { if (cb.checked) anchor = i; });

    if (mode === 'min') {
      // Check from anchor to end
      all.forEach((cb, i) => {
        if (i >= anchor) { cb.checked = true; cb.closest('.cbg-item').classList.add('checked'); }
      });
    } else if (mode === 'max') {
      // Check from start to anchor
      all.forEach((cb, i) => {
        if (i <= anchor) { cb.checked = true; cb.closest('.cbg-item').classList.add('checked'); }
      });
    } else {
      // exact: check first item only
      if (all[0]) { all[0].checked = true; all[0].closest('.cbg-item').classList.add('checked'); }
    }
    updateProgress();
  }

  // Sync visual state when user clicks manually
  document.querySelectorAll('.cbg-item').forEach(item => {
    item.addEventListener('change', () => {
      const cb = item.querySelector('input[type="checkbox"]');
      item.classList.toggle('checked', cb.checked);
      updateProgress();
    });
  });

  /* ═══════════════════════════════════════════════════════
     TAG INPUT (compétences)
  ═══════════════════════════════════════════════════════ */
  const tags = [];

  function addTag(e) {
    if (e.key === 'Enter' || e.key === ',') {
      e.preventDefault();
      const val = e.target.value.replace(',', '').trim();
      if (val && !tags.includes(val)) {
        tags.push(val);
        renderTags();
        updateProgress();
      }
      e.target.value = '';
    } else if (e.key === 'Backspace' && e.target.value === '' && tags.length) {
      tags.pop();
      renderTags();
      updateProgress();
    }
  }

  function renderTags() {
    const field = document.getElementById('tagField');
    const input = document.getElementById('tagInput');
    field.querySelectorAll('.tag').forEach(t => t.remove());
    tags.forEach((t, i) => {
      const tag = document.createElement('span');
      tag.className = 'tag';
      tag.innerHTML = `${t}<button type="button" class="tag__remove" onclick="removeTag(${i})">×</button>`;
      field.insertBefore(tag, input);
    });
    input.placeholder = tags.length ? '' : 'Tapez une compétence et appuyez sur Entrée…';
  }

  function removeTag(i) { tags.splice(i, 1); renderTags(); updateProgress(); }

  /* ═══════════════════════════════════════════════════════
     WYSIWYG
  ═══════════════════════════════════════════════════════ */

  // Sauvegarde/restauration de la sélection pour que execCommand fonctionne
  // même après que le bouton ait reçu le focus.
  let _savedRange = null;

  function saveSelection() {
    const sel = window.getSelection();
    if (sel && sel.rangeCount > 0) _savedRange = sel.getRangeAt(0).cloneRange();
  }

  function restoreSelection(el) {
    el.focus();
    if (!_savedRange) return;
    const sel = window.getSelection();
    if (sel) { sel.removeAllRanges(); sel.addRange(_savedRange); }
  }

  // Empêche les boutons de toolbar de voler le focus (solution universelle)
  document.addEventListener('mousedown', function(e) {
    if (e.target.closest('.wysiwyg-toolbar')) e.preventDefault();
  });

  // Enregistre la sélection à chaque interaction dans les éditeurs
  ['wb-desc', 'wb-profil'].forEach(function(eid) {
    const el = document.getElementById(eid);
    if (!el) return;
    el.addEventListener('mouseup', saveSelection);
    el.addEventListener('keyup',   saveSelection);
    el.addEventListener('focus',   saveSelection);
  });

  function wfmt(id, cmd) {
    const el = document.getElementById('wb-' + id);
    restoreSelection(el);
    if (cmd === 'h2') {
      document.execCommand('formatBlock', false, '<h2>');
    } else if (cmd === 'ul') {
      document.execCommand('insertUnorderedList', false, null);
    } else {
      document.execCommand(cmd, false, null);
    }
    saveSelection();
    updateWCounter(id);
    // Indicateur visuel actif sur les boutons toggle
    const toolbar = el.closest('.po-card').querySelector('.wysiwyg-toolbar');
    if (toolbar) {
      toolbar.querySelectorAll('.wysiwyg-btn').forEach(b => {
        const bcmd = b.getAttribute('data-cmd');
        if (!bcmd) return;
        try { b.classList.toggle('active', document.queryCommandState(bcmd)); } catch(e) {}
      });
    }
  }

  function updateWCounter(id) {
    const el = document.getElementById('wb-' + id);
    const len = el.innerText.replace(/\n/g, '').length;
    const counter = document.getElementById('wc-' + id);
    counter.textContent = len;
    counter.style.color = len > 2500 ? '#185FA5' : len > 2000 ? '#d97706' : '#94a3b8';
    updateProgress();
  }

  /* AI Generate (simulation) */
  const AI_TEMPLATES = {
    desc: `<h2>À propos du poste</h2><p>Nous recherchons un(e) professionnel(le) motivé(e) pour rejoindre notre équipe dynamique. Vous serez responsable de contribuer activement à nos projets stratégiques.</p><h2>Vos missions</h2><ul><li>Piloter et coordonner les activités liées au poste</li><li>Collaborer avec les équipes internes pour atteindre les objectifs</li><li>Analyser les résultats et proposer des améliorations</li><li>Assurer le reporting auprès de la direction</li></ul><h2>Ce que nous offrons</h2><ul><li>Un environnement de travail stimulant et bienveillant</li><li>Des opportunités de formation et d'évolution</li><li>Une rémunération attractive selon profil</li></ul>`,
    profil: `<h2>Votre profil</h2><p>Vous êtes une personne rigoureuse, proactive et orientée résultats. Vous disposez d'excellentes capacités relationnelles et appréciez le travail en équipe.</p><h2>Compétences requises</h2><ul><li>Maîtrise des outils bureautiques et numériques</li><li>Capacité d'analyse et de synthèse</li><li>Sens de l'organisation et gestion des priorités</li><li>Excellente communication écrite et orale</li></ul><h2>Qualités appréciées</h2><ul><li>Autonomie et sens de l'initiative</li><li>Adaptabilité et ouverture d'esprit</li><li>Rigueur et respect des délais</li></ul>`
  };

  const AI_REFINE = {
    style:     (t) => t.replace(/<p>/g, '<p style="line-height:1.9">'),
    clarity:   (t) => `<p><strong>En résumé :</strong> Ce poste vous offrira l'opportunité d'évoluer dans un cadre professionnel stimulant.</p>` + t,
    enrich:    (t) => t + `<h2>Avantages</h2><ul><li>Tickets restaurant</li><li>Mutuelle d'entreprise</li><li>Télétravail partiel possible</li></ul>`,
    format:    (t) => t,
    structure: (t) => t
  };

  function aiGenerate(id) {
    const el = document.getElementById('wb-' + id);
    const btn = el.closest('.po-card').querySelector('.wysiwyg-ai-btn');

    const titre      = ((document.getElementById('fTitre')      || {}).value || '').trim();
    const entreprise = ((document.getElementById('fEntreprise') || {}).value || '').trim();
    const contrat    = (document.getElementById('fContrat')    || {}).value || '';
    const paysEl     = document.getElementById('fPays');
    const paysText   = paysEl ? paysEl.options[paysEl.selectedIndex].text : '';
    const ville      = ((document.getElementById('fVille')      || {}).value || paysText || '').trim();
    const salaire    = ((document.getElementById('fSalaire')    || {}).value || '').trim();
    const metiers    = dropState.metier.selected.join(', ');
    const secteurs   = dropState.secteur.selected.join(', ');
    const tagsList   = tags.join(', ');

    /* Validation : champs requis selon le type */
    const manquants = [];
    if (!titre)     manquants.push('Titre du poste');
    if (!entreprise) manquants.push('Nom de l\'entreprise');
    if (id === 'desc' && !contrat) manquants.push('Type de contrat');

    if (manquants.length) {
      _showAiError(el, 'Veuillez remplir les champs suivants afin que l\'IA puisse générer un contenu personnalisé : ' + manquants.join(', ') + '.');
      return;
    }

    const orig = btn.innerHTML;
    btn.innerHTML = '<svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" style="animation:spin 1s linear infinite"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Génération…';
    btn.disabled = true;

    setTimeout(() => {
      if (id === 'desc') {
        el.innerHTML = buildDescIA(titre, entreprise, contrat, ville, secteurs, salaire);
      } else {
        el.innerHTML = buildProfilIA(titre, metiers || titre, tagsList);
      }
      updateWCounter(id);
      btn.innerHTML = orig;
      btn.disabled = false;
    }, 1400);
  }

  function _showAiError(el, msg) {
    var existing = el.parentNode.querySelector('.ai-field-error');
    if (existing) existing.remove();
    var err = document.createElement('p');
    err.className = 'ai-field-error';
    err.style.cssText = 'color:#185FA5;font-size:0.8rem;background:rgba(24,95,165,0.07);border:1px solid rgba(24,95,165,0.2);border-radius:8px;padding:9px 13px;margin-top:8px;font-family:var(--font-body,Jost,sans-serif);';
    err.textContent = msg;
    el.parentNode.insertBefore(err, el.nextSibling);
    setTimeout(function() { if (err.parentNode) err.remove(); }, 5000);
  }

  function buildDescIA(titre, entreprise, contrat, ville, secteurs, salaire) {
    var lieuStr    = ville    ? ' à ' + ville    : '';
    var contratStr = contrat  ? ' (' + contrat + ')' : '';
    var salStr     = salaire  ? '<li>Rémunération : ' + salaire + ' FCFA</li>' : '';
    var sectStr    = secteurs ? '<p>Secteur : ' + secteurs + '</p>'            : '';
    return '<h2>À propos du poste</h2>' +
      '<p>' + entreprise + ' recherche un(e) <strong>' + titre + '</strong>' + contratStr + lieuStr + '. Rejoignez une équipe engagée.</p>' +
      sectStr +
      '<h2>Vos missions</h2><ul>' +
      '<li>Assurer les responsabilités liées au poste de ' + titre + '</li>' +
      '<li>Collaborer avec les équipes internes pour atteindre les objectifs fixés</li>' +
      '<li>Veiller au respect des procédures et des standards de qualité</li>' +
      '<li>Rendre compte de votre activité à votre responsable hiérarchique</li>' +
      '</ul><h2>Ce que nous offrons</h2><ul>' +
      salStr +
      '<li>Environnement de travail motivant et structuré</li>' +
      '<li>Opportunités de formation et d\'évolution de carrière</li>' +
      '<li>Intégration dans une équipe professionnelle bienveillante</li>' +
      '</ul>';
  }

  function buildProfilIA(titre, metiers, competences) {
    var compStr = competences ? '<li>Maîtrise de : ' + competences + '</li>' : '';
    return '<h2>Profil recherché</h2>' +
      '<p>Nous recherchons un(e) candidat(e) sérieux(se), motivé(e) et organisé(e) pour le poste de <strong>' + titre + '</strong>.</p>' +
      '<h2>Compétences requises</h2><ul>' +
      compStr +
      '<li>Bonne capacité de communication orale et écrite</li>' +
      '<li>Autonomie, rigueur et sens des responsabilités</li>' +
      '<li>Esprit d\'équipe et adaptabilité</li>' +
      '</ul><h2>Qualités appréciées</h2><ul>' +
      '<li>Ponctualité et sérieux dans le travail</li>' +
      '<li>Proactivité et sens de l\'initiative</li>' +
      '<li>Respect des consignes et des délais</li>' +
      '</ul>';
  }

  function aiRefine(id, action) {
    const el = document.getElementById('wb-' + id);
    if (!el.innerHTML.trim() || el.innerText.trim().length < 10) {
      _showAiError(el, 'Rédigez d\'abord une description avant d\'utiliser cette option.');
      return;
    }
    el.innerHTML = AI_REFINE[action](el.innerHTML);
    updateWCounter(id);
  }

  /* ═══════════════════════════════════════════════════════
     NOTIFICATIONS
  ═══════════════════════════════════════════════════════ */
  let notifOn = false;
  function toggleNotif() {
    notifOn = !notifOn;
    document.getElementById('notifToggle').classList.toggle('on', notifOn);
    document.getElementById('notifFreq').classList.toggle('visible', notifOn);
    var costNote = document.getElementById('notifCostNote');
    if (costNote) costNote.style.display = notifOn ? 'flex' : 'none';
    updateTotal();
  }

  /* ═══════════════════════════════════════════════════════
     PROGRESSION SIDEBAR
  ═══════════════════════════════════════════════════════ */
  function updateProgress() {
    const checks = [
      // info
      () => document.getElementById('fTitre').value.trim() !== '' && document.getElementById('fEntreprise').value.trim() !== '',
      // critères
      () => {
        const expChecked = document.querySelectorAll('#cbg-exp input:checked').length > 0;
        const etudesChecked = document.querySelectorAll('#cbg-etudes input:checked').length > 0;
        return expChecked || etudesChecked;
      },
      // contrat
      () => document.getElementById('fContrat').value !== '' && document.getElementById('fNbPostes').value !== '',
      // compétences
      () => tags.length > 0 || dropState.metier.selected.length > 0,
      // descriptions
      () => {
        const d = document.getElementById('wb-desc').innerText.trim();
        const p = document.getElementById('wb-profil').innerText.trim();
        return d.length > 20 && p.length > 20;
      }
    ];

    const steps = ['info','criteres','contrat','competences','description'];
    let done = 0;
    checks.forEach((fn, i) => {
      const el = document.getElementById('ps-' + steps[i]);
      const ok = fn();
      if (ok) { el.className = 'po-progress-step done'; done++; }
      else     { el.className = 'po-progress-step'; }
    });

    const pct = Math.round((done / checks.length) * 100);
    document.getElementById('progressFill').style.width = pct + '%';
    document.getElementById('progressPct').textContent  = pct + ' %';
  }

  // Live progress sur les champs texte
  ['fTitre','fEntreprise','fEmail','fVille','fSalaire'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.addEventListener('input', updateProgress);
  });
  // Pour les <select>, utiliser 'change' (l'événement 'input' n'est pas fiable)
  ['fContrat','fNbPostes','fSalairePeriode','notifSelect'].forEach(id => {
    const el = document.getElementById(id);
    if (el) el.addEventListener('change', updateProgress);
  });
  // fNbPostes est un input number → écoute les deux
  const nbEl = document.getElementById('fNbPostes');
  if (nbEl) nbEl.addEventListener('input', updateProgress);

  /* ═══════════════════════════════════════════════════════
     VALIDATION + SUBMIT
  ═══════════════════════════════════════════════════════ */
  function publierOffre() {
    const titre      = document.getElementById('fTitre').value.trim();
    const entreprise = document.getElementById('fEntreprise').value.trim();
    const email      = document.getElementById('fEmail').value.trim();
    const contrat    = document.getElementById('fContrat').value;
    const nbPostes   = document.getElementById('fNbPostes').value;
    const desc       = document.getElementById('wb-desc').innerText.trim();
    const profil     = document.getElementById('wb-profil').innerText.trim();

    // Mise en évidence des champs vides
    const required = [
      { id: 'fTitre',      label: "Titre de l'annonce" },
      { id: 'fEntreprise', label: "Nom de l'entreprise" },
      { id: 'fEmail',      label: "Email de contact" },
      { id: 'fContrat',    label: "Type de contrat" },
      { id: 'fNbPostes',   label: "Nombre de postes" }
    ];
    const errors = [];
    required.forEach(({ id, label }) => {
      const el = document.getElementById(id);
      if (!el || !el.value.trim()) {
        errors.push(label);
        if (el) { el.style.borderColor = '#185FA5'; el.style.boxShadow = '0 0 0 3px rgba(24,95,165,0.15)'; }
      } else {
        if (el) { el.style.borderColor = ''; el.style.boxShadow = ''; }
      }
    });
    // Vérifier email format
    if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
      errors.push("Format d'email invalide");
      const el = document.getElementById('fEmail');
      el.style.borderColor = '#185FA5'; el.style.boxShadow = '0 0 0 3px rgba(24,95,165,0.15)';
    }
    if (desc.length < 20)   errors.push('Description du poste (minimum 20 caractères)');
    if (profil.length < 20) errors.push('Description du profil (minimum 20 caractères)');

    if (errors.length) {
      alert('Veuillez corriger les champs suivants :\n\n• ' + errors.join('\n• '));
      return;
    }

    // Sauvegarde les données de l'offre en attente de paiement
    const offrePending = {
      titre:       document.getElementById('fTitre').value.trim(),
      entreprise:  document.getElementById('fEntreprise').value.trim(),
      email:       document.getElementById('fEmail').value.trim(),
      contrat:     document.getElementById('fContrat').value,
      nbPostes:    document.getElementById('fNbPostes').value,
      ville:       document.getElementById('fVille').value,
      salaire:     document.getElementById('fSalaire').value,
      salPeriode:  document.getElementById('fSalairePeriode').value,
      desc:        document.getElementById('wb-desc').innerHTML,
      profil:      document.getElementById('wb-profil').innerHTML,
      tags:        [...tags],
      metier:      [...dropState.metier.selected],
      secteur:     [...dropState.secteur.selected],
      region:      [...dropState.region.selected],
      langue:      [...dropState.langue.selected],
      status:      'pending_payment',
      createdAt:   new Date().toISOString()
    };
    try {
      localStorage.setItem('offre_pending', JSON.stringify(offrePending));
    } catch(e) {}

    // Redirection vers la page de paiement
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="animation:spin 1s linear infinite"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Redirection paiement…';
    setTimeout(() => { window.location.href = 'paiement-offre.html'; }, 900);
  }

  function saveDraft() {
    const data = {
      titre:       document.getElementById('fTitre').value,
      entreprise:  document.getElementById('fEntreprise').value,
      email:       document.getElementById('fEmail').value,
      contrat:     document.getElementById('fContrat').value,
      nbPostes:    document.getElementById('fNbPostes').value,
      ville:       document.getElementById('fVille').value,
      salaire:     document.getElementById('fSalaire').value,
      salPeriode:  document.getElementById('fSalairePeriode').value,
      desc:        document.getElementById('wb-desc').innerHTML,
      profil:      document.getElementById('wb-profil').innerHTML,
      tags:        [...tags],
      metier:      [...dropState.metier.selected],
      secteur:     [...dropState.secteur.selected],
      region:      [...dropState.region.selected],
      langue:      [...dropState.langue.selected],
      notifOn:     notifOn,
      notifFreq:   document.getElementById('notifSelect').value,
      savedAt:     new Date().toISOString()
    };
    try {
      localStorage.setItem('offre_draft', JSON.stringify(data));
      const btn = document.querySelector('.po-save-btn');
      const ts  = new Date().toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' });
      btn.textContent = '✓ Brouillon enregistré à ' + ts;
      btn.style.color = '#38A169';
      btn.style.borderColor = 'rgba(56,161,105,0.4)';
      setTimeout(() => {
        btn.textContent = 'Enregistrer le brouillon';
        btn.style.color = '';
        btn.style.borderColor = '';
      }, 2500);
    } catch(e) {
      alert('Impossible de sauvegarder le brouillon (stockage local indisponible).');
    }
  }

  function restoreDraft() {
    try {
      const saved = localStorage.getItem('offre_draft');
      if (!saved) return;
      const d = JSON.parse(saved);
      const fields = { fTitre:'titre', fEntreprise:'entreprise', fEmail:'email',
        fContrat:'contrat', fNbPostes:'nbPostes', fVille:'ville',
        fSalaire:'salaire', fSalairePeriode:'salPeriode' };
      Object.entries(fields).forEach(([id, key]) => {
        const el = document.getElementById(id);
        if (el && d[key] !== undefined) el.value = d[key];
      });
      if (d.desc)   { document.getElementById('wb-desc').innerHTML   = d.desc;   updateWCounter('desc'); }
      if (d.profil) { document.getElementById('wb-profil').innerHTML = d.profil; updateWCounter('profil'); }
      if (Array.isArray(d.tags) && d.tags.length) { tags.push(...d.tags); renderTags(); }
      ['metier','secteur','region','langue'].forEach(key => {
        if (Array.isArray(d[key]) && d[key].length) {
          dropState[key].selected = [...d[key]];
          renderChips(key);
        }
      });
      if (d.notifOn) {
        notifOn = false; toggleNotif(); // active le toggle
        const sel = document.getElementById('notifSelect');
        if (sel && d.notifFreq) sel.value = d.notifFreq;
      }
      updateProgress();
    } catch(e) {}
  }

  /* ═══════════════════════════════════════════════════════
     NAV MOBILE
  ═══════════════════════════════════════════════════════ */
  const hamburgerBtn = document.getElementById('hamburger');
  const mobileMenuEl = document.getElementById('mobileMenu');
  hamburgerBtn.addEventListener('click', () => {
    const isOpen = mobileMenuEl.classList.toggle('open');
    hamburgerBtn.classList.toggle('open', isOpen);
    hamburgerBtn.setAttribute('aria-expanded', isOpen);
  });
  mobileMenuEl.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', () => {
      mobileMenuEl.classList.remove('open');
      hamburgerBtn.classList.remove('open');
      hamburgerBtn.setAttribute('aria-expanded', 'false');
    });
  });

  /* Init dropdowns + restauration brouillon */
  Object.keys(dropState).forEach(k => buildOptions(k));
  restoreDraft();
  updateProgress();

  /* ═══════════════════════════════════════════════════════
     PAYS & VILLES
  ═══════════════════════════════════════════════════════ */
  const VILLES_PAR_PAYS = {
    'BJ': ['Abomey','Abomey-Calavi','Aplahoué','Bohicon','Cotonou','Djougou','Kandi','Lokossa','Natitingou','Parakou','Pobè','Porto-Novo','Savalou','International'],
    'SN': ['Dakar','Thiès','Mbour','Ziguinchor','Saint-Louis','Touba','Kaolack','Tambacounda'],
    'CI': ['Abidjan','Bouaké','Daloa','Korhogo','San-Pédro','Yamoussoukro','Man'],
    'ML': ['Bamako','Gao','Kayes','Mopti','Sikasso','Tombouctou','Ségou'],
    'BF': ['Bobo-Dioulasso','Koudougou','Ouagadougou','Ouahigouya','Dédougou'],
    'TG': ['Lomé','Atakpamé','Kara','Sokodé','Palimé'],
    'NE': ['Agadez','Diffa','Dosso','Maradi','Niamey','Tahoua','Zinder'],
    'GH': ['Accra','Cape Coast','Kumasi','Tamale','Takoradi'],
    'NG': ['Abuja','Ibadan','Kano','Lagos','Port Harcourt','Benin City'],
    'CM': ['Bafoussam','Bamenda','Douala','Garoua','Maroua','Yaoundé','Kribi'],
    'MA': ['Agadir','Casablanca','Fès','Marrakech','Meknès','Rabat','Tanger'],
    'TN': ['Sfax','Sousse','Tunis','Bizerte','Monastir'],
    'DZ': ['Alger','Annaba','Constantine','Oran','Sétif','Tlemcen'],
    'KE': ['Kisumu','Mombasa','Nairobi','Eldoret','Nakuru'],
    'TZ': ['Dar es Salaam','Dodoma','Mwanza','Arusha','Moshi'],
    'CD': ['Bukavu','Goma','Kinshasa','Lubumbashi','Mbuji-Mayi','Kisangani'],
    'MG': ['Antananarivo','Fianarantsoa','Toamasina','Mahajanga'],
    'ET': ['Addis-Abeba','Bahir Dar','Dire Dawa','Gondar','Mekele'],
    'GN': ['Conakry','Kankan','Labé','N\'Zérékoré'],
    'GA': ['Libreville','Port-Gentil','Franceville'],
    'CG': ['Brazzaville','Pointe-Noire','Dolisie'],
    'RW': ['Kigali','Butare','Gisenyi'],
    'MZ': ['Beira','Maputo','Nampula','Pemba'],
    'AO': ['Huambo','Lobito','Luanda','Lubango'],
    'ZA': ['Cape Town','Durban','Johannesburg','Pretoria','Port Elizabeth'],
    'GH': ['Accra','Cape Coast','Kumasi','Tamale'],
    'MU': ['Beau Bassin','Curepipe','Port Louis','Quatre Bornes'],
    'SL': ['Bo','Freetown','Kenema','Makeni'],
    'LR': ['Gbarnga','Kakata','Monrovia','Buchanan'],
    'BW': ['Francistown','Gaborone','Maun','Serowe'],
    'NA': ['Rundu','Swakopmund','Walvis Bay','Windhoek'],
    'ZM': ['Kabwe','Kitwe','Lusaka','Ndola'],
    'ZW': ['Bulawayo','Harare','Mutare'],
    'TD': ['Abéché','Moundou','N\'Djaména','Sarh'],
    'SD': ['Khartoum','Omdurman','Port-Soudan'],
    'SS': ['Djouba','Malakal','Wau'],
    'ER': ['Asmara','Keren','Massawa'],
    'SO': ['Hargeisa','Kismayo','Mogadiscio'],
    'DJ': ['Djibouti-Ville'],
    'BI': ['Bujumbura','Gitega','Ngozi'],
    'CF': ['Bambari','Bangui','Berbérati'],
    'GQ': ['Bata','Malabo'],
    'CV': ['Mindelo','Praia'],
    'KM': ['Moroni','Mutsamudu'],
    'ST': ['São Tomé'],
    'SC': ['Victoria'],
    'MW': ['Blantyre','Lilongwe','Mzuzu'],
    'LS': ['Maseru'],
    'SZ': ['Lobamba','Manzini','Mbabane'],
    'GW': ['Bissau','Bafatá'],
    'GM': ['Banjul','Serekunda','Brikama'],
    'LY': ['Benghazi','Misrata','Tripoli'],
    'EG': ['Alexandrie','Assouan','Le Caire','Louxor','Port-Saïd'],
    'UG': ['Entebbe','Gulu','Kampala','Mbarara']
  };

  function updateVillesByPays() {
    var pays = document.getElementById('fPays').value;
    var villes = (VILLES_PAR_PAYS[pays] || ['Ville principale','Autre ville','International']);
    dropState.region.selected = [];
    dropState.region.list = villes;
    renderChips('region');
    buildOptions('region');
    updateProgress();
  }

  // Init villes avec Bénin au chargement
  updateVillesByPays();

  /* ═══════════════════════════════════════════════════════
     TOTAL DYNAMIQUE (plan + notification)
  ═══════════════════════════════════════════════════════ */
  var _urlParams  = new URLSearchParams(window.location.search);
  var BASE_PRICE  = parseInt(_urlParams.get('prix') || '0', 10);
  var PLAN_LABEL  = _urlParams.get('plan') || '';
  var NOTIF_FEE   = 500;

  function updateTotal() {
    var totalEl = document.getElementById('totalDisplay');
    var totalRow = document.getElementById('totalRow');
    if (!totalEl) return;
    if (BASE_PRICE > 0) {
      var total = BASE_PRICE + (notifOn ? NOTIF_FEE : 0);
      totalEl.textContent = total.toLocaleString('fr-FR') + ' FCFA';
      if (totalRow) totalRow.style.display = 'flex';
    } else {
      totalEl.textContent = notifOn ? '+500 FCFA (notification)' : '—';
      if (totalRow) totalRow.style.display = notifOn ? 'flex' : 'none';
    }
  }

  // Affichage initial
  if (BASE_PRICE > 0) updateTotal();
  else { var tr = document.getElementById('totalRow'); if (tr) tr.style.display = 'none'; }

  // Redirection paiement avec prix final
  var _origPublier = publierOffre;
  publierOffre = function() {
    // Calcule le prix final avant de sauvegarder
    var finalPrix = BASE_PRICE + (notifOn ? NOTIF_FEE : 0);
    var pending = null;
    try { pending = JSON.parse(localStorage.getItem('offre_pending') || 'null'); } catch(e) {}
    // On laisse la fonction originale faire son travail, puis on surcharge la redirection
    _origPublier();
    // Note : la redirection se fait dans _origPublier via setTimeout
    // Pour passer le prix : on stocke dans localStorage avant
    try {
      var extra = { finalPrix: finalPrix, planLabel: PLAN_LABEL, notifOn: notifOn };
      localStorage.setItem('offre_extra', JSON.stringify(extra));
    } catch(e) {}
  };

  /* ═══════════════════════════════════════════════════════
     FICHIER JOINT
  ═══════════════════════════════════════════════════════ */
  var uploadedFile = null;

  function handleFile(input) {
    var file = input.files[0];
    if (!file) return;
    var maxSize = 5 * 1024 * 1024;
    if (file.size > maxSize) {
      alert('Fichier trop volumineux. Maximum 5 Mo.');
      input.value = '';
      return;
    }
    uploadedFile = file;
    var preview = document.getElementById('filePreview');
    var drop    = document.getElementById('fileDrop');
    var sizeKo  = (file.size / 1024).toFixed(0);
    preview.className = 'file-preview visible';
    preview.innerHTML =
      '<svg class="file-preview__icon" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>' +
      '<span class="file-preview__name">' + file.name + '</span>' +
      '<span class="file-preview__size">' + sizeKo + ' Ko</span>' +
      '<button type="button" class="file-preview__remove" onclick="removeFile()">Supprimer</button>';
    drop.style.display = 'none';
  }

  function removeFile() {
    uploadedFile = null;
    var input = document.getElementById('fileInput');
    if (input) input.value = '';
    var preview = document.getElementById('filePreview');
    var drop    = document.getElementById('fileDrop');
    if (preview) { preview.className = 'file-preview'; preview.innerHTML = ''; }
    if (drop)    { drop.style.display = ''; }
  }

  function fileDragOver(e) {
    e.preventDefault();
    var drop = document.getElementById('fileDrop');
    if (drop) drop.classList.add('dragover');
  }

  function fileDrop2(e) {
    e.preventDefault();
    var drop = document.getElementById('fileDrop');
    if (drop) drop.classList.remove('dragover');
    var files = e.dataTransfer && e.dataTransfer.files;
    if (files && files.length) {
      var input = document.getElementById('fileInput');
      // Simule le changement via handleFile directement
      var fakeInput = { files: files };
      handleFile(fakeInput);
    }
  }

  // Ajouter fPays aux listeners de progression
  var paysEl = document.getElementById('fPays');
  if (paysEl) paysEl.addEventListener('change', updateProgress);
