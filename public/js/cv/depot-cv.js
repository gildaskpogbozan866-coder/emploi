﻿/* ══ MULTI-STEP ══ */
    var currentStep = 1;
    var authMode = 'register';
    var STEP_TITLES = ['', 'Votre compte', 'Votre CV & Profil', 'Confirmer & Envoyer'];

    /* ── Gestion des comptes (localStorage) ── */
    function getComptes() { try { return JSON.parse(localStorage.getItem('comptes_candidats') || '[]'); } catch(e) { return []; } }
    function getCurrentUser() { try { return JSON.parse(localStorage.getItem('current_user') || 'null'); } catch(e) { return null; } }
    function setCurrentUser(u) { localStorage.setItem('current_user', JSON.stringify(u)); }

    function switchAuthMode(mode) {
      authMode = mode;
      document.getElementById('tabRegister').classList.toggle('active', mode === 'register');
      document.getElementById('tabLogin').classList.toggle('active', mode === 'login');
      document.getElementById('panelRegister').classList.toggle('active', mode === 'register');
      document.getElementById('panelLogin').classList.toggle('active', mode === 'login');
    }

    function seDeconnecter() {
      localStorage.removeItem('current_user');
      document.getElementById('authLoggedIn').classList.remove('show');
      document.getElementById('authForms').style.display = '';
      document.querySelector('.depot-nav').style.display = '';
    }

    /* ── Détection connexion existante au chargement ── */
    (function() {
      var u = getCurrentUser();
      if (u) {
        var initials = ((u.nom||'')[0]||(u.prenom||'')[0]||'?').toUpperCase() + ((u.prenom||'')[0]||'').toUpperCase();
        document.getElementById('authAvatar').textContent = initials || '?';
        document.getElementById('authName').textContent = (u.prenom || '') + ' ' + (u.nom || '');
        document.getElementById('authLoggedIn').classList.add('show');
        document.getElementById('authForms').style.display = 'none';
      }
    })();

    function updateStepsBar(n) {
      var steps = document.querySelectorAll('.depot-step');
      steps.forEach(function(el, idx) {
        var num = idx + 1;
        el.classList.remove('depot-step--active', 'depot-step--done');
        if (num < n)       el.classList.add('depot-step--done');
        else if (num === n) el.classList.add('depot-step--active');
      });
      var titleEl = document.getElementById('cardTitle');
      if (titleEl) titleEl.textContent = STEP_TITLES[n] || '';
    }

    function showErr(step, msg) {
      var err   = document.getElementById('err' + step);
      var msgEl = document.getElementById('err' + step + 'Msg');
      if (!err || !msgEl) return;
      err.style.background = '';
      err.style.borderColor = '';
      err.style.color = '';
      msgEl.textContent = msg;
      err.classList.add('show');
      setTimeout(function() { err.classList.remove('show'); }, 5000);
    }

    function validateStep1() {
      if (getCurrentUser()) return true;
      localStorage.setItem('auth_redirect', window.location.href);
      window.location.href = '../auth/connexion.html';
      return false;
    }

    function validateStep2() {
      var file    = document.getElementById('cvFile').files[0];
      var metiers = document.getElementById('metierTagsList').children.length;
      var contrat = document.querySelector('input[name="contrat"]:checked');
      var dispo   = document.getElementById('fDispo').value;
      var res     = document.getElementById('fResidence').value.trim();

      if (!file)    { showErr(2, 'Veuillez téléverser votre CV avant de continuer.'); return false; }
      if (!metiers) { showErr(2, 'Veuillez ajouter au moins un métier recherché.'); document.getElementById('metierInput').focus(); return false; }
      if (!contrat) { showErr(2, 'Veuillez choisir un type de contrat (CDI ou CDD).'); return false; }
      if (!dispo)   { showErr(2, 'Veuillez indiquer votre disponibilité.'); document.getElementById('fDispo').focus(); return false; }
      if (!res)     { showErr(2, 'Veuillez indiquer votre ville de résidence.'); document.getElementById('fResidence').focus(); return false; }
      return true;
    }

    function buildConfirmation() {
      var u    = getCurrentUser();
      var nom  = u ? u.nom    : document.getElementById('fNom').value.trim();
      var pre  = u ? u.prenom : document.getElementById('fPrenom').value.trim();
      var em   = u ? u.email  : document.getElementById('fEmail').value.trim();
      var tel  = u ? (u.tel || '—') : document.getElementById('fTel').value.trim();
      var file = document.getElementById('cvFile').files[0];

      document.getElementById('confNom').textContent   = pre + ' ' + nom;
      document.getElementById('confEmail').textContent  = em;
      document.getElementById('confTel').textContent    = tel;

      /* ── CV ── */
      var cvEl = document.getElementById('confCV');
      if (file) {
        var ext  = file.name.split('.').pop().toLowerCase();
        var size = file.size < 1048576
          ? (file.size / 1024).toFixed(1) + ' Ko'
          : (file.size / 1048576).toFixed(2) + ' Mo';
        var wrap = document.createElement('div');
        wrap.style.cssText = 'display:flex;align-items:center;gap:10px;background:#fff;border:1.5px solid #dde9fb;border-radius:10px;padding:12px 14px;';
        var svg = '<svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#185FA5" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>';
        var info = '<div><div style="font-size:13.5px;font-weight:700;color:#042C53">' + file.name + '</div><div style="font-size:12px;color:#64748b">' + ext.toUpperCase() + ' · ' + size + '</div></div>';
        wrap.innerHTML = svg + info;
        cvEl.innerHTML = '';
        cvEl.appendChild(wrap);
      } else {
        cvEl.innerHTML = '<span style="font-size:13px;color:#64748b;">Aucun fichier sélectionné</span>';
      }

      /* ── Profil ── */
      var metierPills = document.getElementById('metierTagsList').querySelectorAll('.tag-pill');
      var metierTexts = Array.from(metierPills).map(function(p) { return p.textContent.replace('✕','').trim(); });
      var contratEl   = document.querySelector('input[name="contrat"]:checked');
      var dispoSel    = document.getElementById('fDispo');
      var dispoText   = dispoSel.options[dispoSel.selectedIndex] ? dispoSel.options[dispoSel.selectedIndex].text : '—';
      var residence   = document.getElementById('fResidence').value.trim();
      var mobPills    = document.getElementById('mobiliteTagsList').querySelectorAll('.tag-pill');
      var mobTexts    = Array.from(mobPills).map(function(p) { return p.textContent.replace('✕','').trim(); });

      var confProfil = document.getElementById('confProfil');
      confProfil.innerHTML = [
        '<div class="conf-grid">',
          '<div class="conf-row">',
            '<span class="conf-lbl">Métier(s) recherché(s)</span>',
            '<span class="conf-val">' + (metierTexts.length ? metierTexts.join(', ') : '—') + '</span>',
          '</div>',
          '<div class="conf-row">',
            '<span class="conf-lbl">Type de contrat</span>',
            '<span class="conf-val">' + (contratEl ? contratEl.value : '—') + '</span>',
          '</div>',
          '<div class="conf-row">',
            '<span class="conf-lbl">Disponibilité</span>',
            '<span class="conf-val">' + dispoText + '</span>',
          '</div>',
          '<div class="conf-row">',
            '<span class="conf-lbl">Résidence</span>',
            '<span class="conf-val">' + (residence || '—') + '</span>',
          '</div>',
          mobTexts.length ? (
            '<div class="conf-row conf-row--full">' +
              '<span class="conf-lbl">Zones de mobilité</span>' +
              '<span class="conf-val">' + mobTexts.join(', ') + '</span>' +
            '</div>'
          ) : '',
        '</div>'
      ].join('');
    }

    function goStep(n) {
      if (n > currentStep) {
        if (currentStep === 1 && !validateStep1()) return;
        if (currentStep === 2 && !validateStep2()) return;
      }
      if (n === 3) buildConfirmation();

      document.getElementById('step1Content').hidden = (n !== 1);
      document.getElementById('step2Content').hidden = (n !== 2);
      document.getElementById('step3Content').hidden = (n !== 3);

      updateStepsBar(n);
      currentStep = n;

      document.getElementById('depotForm').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    /* ── Upload fichier ── */
    (function() {
      const input   = document.getElementById('cvFile');
      const zone    = document.getElementById('uploadZone');
      const preview = document.getElementById('filePreview');
      const iconEl  = document.getElementById('previewIcon');
      const nameEl  = document.getElementById('previewName');
      const metaEl  = document.getElementById('previewMeta');
      const rmBtn   = document.getElementById('removeFile');

      function formatSize(b) {
        return b < 1024 ? b + ' o' : b < 1048576 ? (b/1024).toFixed(1)+' Ko' : (b/1048576).toFixed(2)+' Mo';
      }

      function showPreview(file) {
        const ext = file.name.split('.').pop().toLowerCase();
        nameEl.textContent  = file.name;
        metaEl.textContent  = (ext ? ext.toUpperCase() : '') + ' • ' + formatSize(file.size);
        zone.classList.add('has-file');
        preview.classList.add('visible');
      }

      function clearPreview() {
        input.value = '';
        zone.classList.remove('has-file');
        preview.classList.remove('visible');
      }

      input.addEventListener('change', function() {
        if (this.files && this.files[0]) showPreview(this.files[0]);
      });

      zone.addEventListener('dragover', e => { e.preventDefault(); zone.style.borderColor='#378ADD'; });
      zone.addEventListener('dragleave', () => { zone.style.borderColor=''; });
      zone.addEventListener('drop', e => {
        e.preventDefault(); zone.style.borderColor='';
        const file = e.dataTransfer.files[0];
        if (file) {
          const dt = new DataTransfer(); dt.items.add(file); input.files = dt.files;
          showPreview(file);
        }
      });

      rmBtn.addEventListener('click', clearPreview);
    })();

    /* ── Métiers : ajouter depuis select OU champ libre ── */
    function addMetierTag() {
      const sel   = document.getElementById('metierSelect');
      const input = document.getElementById('metierInput');
      const list  = document.getElementById('metierTagsList');
      const text  = (sel.value && sel.value !== 'Autre') ? sel.value : input.value.trim();
      if (!text) return;
      const pill = document.createElement('span');
      pill.className = 'tag-pill';
      pill.innerHTML = `${text}<button type="button" class="tag-pill__remove">✕</button>`;
      pill.querySelector('.tag-pill__remove').addEventListener('click', () => pill.remove());
      list.appendChild(pill);
      sel.value = '';
      input.value = '';
      input.focus();
    }
    document.getElementById('metierInput').addEventListener('keydown', function(e) {
      if (e.key === 'Enter') { e.preventDefault(); addMetierTag(); }
    });

    /* ── Check pills (checkbox) ── */
    function togglePill(label) {
      label.classList.toggle('selected');
      const cb = label.querySelector('input[type="checkbox"]');
      if (cb) cb.checked = label.classList.contains('selected');
    }

    /* ── Contrat : choix exclusif CDI / CDD ── */
    function selectContrat(label, value) {
      document.querySelectorAll('[id^="pillCD"]').forEach(function(el) {
        el.classList.remove('selected');
        const radio = el.querySelector('input[type="radio"]');
        if (radio) radio.checked = false;
      });
      label.classList.add('selected');
      const radio = label.querySelector('input[type="radio"]');
      if (radio) radio.checked = true;
    }

    /* ── Mobilité : tags dynamiques ── */
    function addMobiliteTag() {
      const input = document.getElementById('mobiliteInput');
      const list  = document.getElementById('mobiliteTagsList');
      const text  = input.value.trim();
      if (!text) return;
      const pill = document.createElement('span');
      pill.className = 'tag-pill';
      pill.innerHTML = `${text}<button type="button" class="tag-pill__remove">✕</button>`;
      pill.querySelector('.tag-pill__remove').addEventListener('click', () => pill.remove());
      list.appendChild(pill);
      input.value = '';
      input.focus();
    }
    document.getElementById('mobiliteInput').addEventListener('keydown', function(e) {
      if (e.key === 'Enter') { e.preventDefault(); addMobiliteTag(); }
    });

    /* ── Toggle password ── */
    const EYE_OPEN  = `<svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>`;
    const EYE_CLOSE = `<svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>`;
    function togglePwd(id, btn) {
      const input = document.getElementById(id);
      if (input.type === 'password') { input.type = 'text'; btn.innerHTML = EYE_CLOSE; }
      else { input.type = 'password'; btn.innerHTML = EYE_OPEN; }
    }

    /* ── Add compétence ── */
    let compCount = 0;
    function addCompetence() {
      compCount++;
      const container = document.getElementById('competencesContainer');
      const div = document.createElement('div');
      div.className = 'exp-block';
      div.style.cssText = 'padding:14px 16px;margin-bottom:10px;';
      div.innerHTML = `
        <div class="exp-block__header">
          <span class="exp-block__title">Compétence ${compCount}</span>
          <button type="button" class="exp-remove-btn" onclick="this.closest('.exp-block').remove()">✕</button>
        </div>
        <input class="field__input" type="text" name="competence[]" placeholder="Ex : Gestion administrative, Rédaction de contrats…" />`;
      container.appendChild(div);
    }

    /* ── Add qualité personnelle ── */
    let qualCount = 0;
    function addQualite() {
      qualCount++;
      const container = document.getElementById('qualitesContainer');
      const div = document.createElement('div');
      div.className = 'exp-block';
      div.style.cssText = 'padding:14px 16px;margin-bottom:10px;';
      div.innerHTML = `
        <div class="exp-block__header">
          <span class="exp-block__title">Qualité personnelle ${qualCount}</span>
          <button type="button" class="exp-remove-btn" onclick="this.closest('.exp-block').remove()">✕</button>
        </div>
        <input class="field__input" type="text" name="qualite[]" placeholder="Ex : Aisance relationnelle, Sens de l'organisation…" />`;
      container.appendChild(div);
    }

    /* ── Add expérience — numéroté, conteneur vide au départ ── */
    let expCount = 0;
    function addExperience() {
      expCount++;
      const container = document.getElementById('experiencesContainer');
      const div = document.createElement('div');
      div.className = 'exp-block';
      div.innerHTML = `
        <div class="exp-block__header">
          <span class="exp-block__title">Expérience ${expCount}</span>
          <button type="button" class="exp-remove-btn" onclick="this.closest('.exp-block').remove()">✕</button>
        </div>
        <div class="form-row form-row--2">
          <div><label class="field__label">Intitulé du poste <span class="req">*</span></label><input class="field__input" type="text" name="exp_poste[]" placeholder="Ex : Responsable RH" /></div>
          <div><label class="field__label">Employeur / Structure <span class="req">*</span></label><input class="field__input" type="text" name="exp_employeur[]" placeholder="Ex : Entreprise XYZ" /></div>
        </div>
        <div class="form-row form-row--2">
          <div><label class="field__label">Date de début</label><input class="field__input" type="month" name="exp_debut[]" /></div>
          <div><label class="field__label">Date de fin <small style="font-weight:400;text-transform:none">(vide = en cours)</small></label><input class="field__input" type="month" name="exp_fin[]" /></div>
        </div>
        <div class="form-row form-row--1">
          <div><label class="field__label">Missions principales</label><textarea class="field__textarea" name="exp_missions[]" style="min-height:80px" placeholder="Décrivez vos missions…"></textarea></div>
        </div>`;
      container.appendChild(div);
    }

    /* ── Add formation — conteneur vide au départ, numéroté ── */
    let formCount = 0;
    function addFormation() {
      formCount++;
      const container = document.getElementById('formationsContainer');
      const div = document.createElement('div');
      div.className = 'exp-block';
      div.innerHTML = `
        <div class="exp-block__header">
          <span class="exp-block__title">Formation ${formCount}</span>
          <button type="button" class="exp-remove-btn" onclick="this.closest('.exp-block').remove()">✕</button>
        </div>
        <div class="form-row form-row--2">
          <div><label class="field__label">Diplôme / Intitulé <span class="req">*</span></label><input class="field__input" type="text" name="form_diplome[]" placeholder="Ex : BTS, Licence, Master…" /></div>
          <div><label class="field__label">Établissement <span class="req">*</span></label><input class="field__input" type="text" name="form_etablissement[]" placeholder="Ex : Université de…" /></div>
        </div>
        <div class="form-row form-row--2">
          <div><label class="field__label">Année de début</label><input class="field__input" type="number" name="form_debut[]" placeholder="Ex : 2019" min="1970" max="2030" /></div>
          <div><label class="field__label">Année de fin</label><input class="field__input" type="number" name="form_fin[]" placeholder="Ex : 2022" min="1970" max="2030" /></div>
        </div>`;
      container.appendChild(div);
    }

    /* ── Add langue ── */
    function addLangue() {
      const container = document.getElementById('languesContainer');
      const div = document.createElement('div');
      div.className = 'lang-row';
      div.innerHTML = `
        <input class="field__input" type="text" name="langue[]" placeholder="Langue" style="flex:1" />
        <span class="lang-sep">|</span>
        <select class="field__select" name="langue_niveau[]" style="flex:1">
          <option value="">— Niveau —</option>
          <option value="debutant">Débutant</option>
          <option value="intermediaire">Intermédiaire</option>
          <option value="bon">Bon niveau</option>
          <option value="courant">Courant</option>
          <option value="bilingue">Bilingue / Natif</option>
        </select>
        <button type="button" class="exp-remove-btn" onclick="this.closest('.lang-row').remove()">✕</button>`;
      container.appendChild(div);
    }

    /* ── Envoi simulation ── */
    function soumettreCV() {
      const u     = getCurrentUser();
      const nom   = u ? u.nom    : document.getElementById('fNom').value.trim();
      const prenom= u ? u.prenom : document.getElementById('fPrenom').value.trim();
      const email = u ? u.email  : document.getElementById('fEmail').value.trim();
      const file  = document.getElementById('cvFile').files[0];

      const btn = document.getElementById('submitBtn');
      btn.disabled = true;
      btn.innerHTML = '<svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="animation:spin 1s linear infinite"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Envoi en cours…';

      /* ── Collecte des données du profil ── */
      const metierPills = Array.from(document.querySelectorAll('#metierTagsList .tag-pill'))
        .map(p => p.textContent.replace('✕','').trim()).filter(Boolean);
      const contratEl   = document.querySelector('input[name="contrat"]:checked');
      const contrat     = contratEl ? contratEl.value : '';
      const residence   = document.getElementById('fResidence').value.trim();
      const dispoSel    = document.getElementById('fDispo');
      const dispoVal    = dispoSel.value;
      const mobPills    = Array.from(document.querySelectorAll('#mobiliteTagsList .tag-pill'))
        .map(p => p.textContent.replace('✕','').trim()).filter(Boolean);

      const competences = Array.from(document.querySelectorAll('input[name="competence[]"]'))
        .map(i => i.value.trim()).filter(Boolean);

      const formations = [];
      const diplomes    = document.querySelectorAll('input[name="form_diplome[]"]');
      const etabs       = document.querySelectorAll('input[name="form_etablissement[]"]');
      const formFins    = document.querySelectorAll('input[name="form_fin[]"]');
      diplomes.forEach((d, i) => {
        if (d.value.trim()) formations.push({
          titre: d.value.trim(),
          ecole: etabs[i] ? etabs[i].value.trim() : '',
          date:  formFins[i] ? formFins[i].value.trim() : ''
        });
      });

      const niveauLabels = { debutant:'Débutant', intermediaire:'Intermédiaire', bon:'Bon niveau', courant:'Courant', bilingue:'Bilingue / Natif' };
      const langues = [];
      document.querySelectorAll('#languesContainer .lang-row').forEach(row => {
        const nom_l = row.querySelector('input[name="langue[]"]');
        const niv_l = row.querySelector('select[name="langue_niveau[]"]');
        if (nom_l && nom_l.value.trim()) langues.push({
          nom: nom_l.value.trim(),
          niveau: niv_l && niv_l.value ? (niveauLabels[niv_l.value] || niv_l.value) : ''
        });
      });

      const bgColors = ['#dbeafe','#fce7f3','#d1fae5','#ede9fe','#fef3c7','#fee2e2','#e0f2fe','#dcfce7'];
      const initials = ((nom[0]||'') + (prenom[0]||'')).toUpperCase();
      const avatarBg = bgColors[Math.floor(Math.random() * bgColors.length)];
      const dateStr  = new Date().toLocaleDateString('fr-FR', { day:'numeric', month:'long', year:'numeric' });

      const profil = {
        id: Date.now(),
        nom, prenom, email,
        avatar: initials,
        avatarBg,
        pays: 'Bénin',
        ville: residence || 'Cotonou',
        secteur: metierPills[0] || 'Autre',
        secteurLabel: metierPills[0] || 'Non précisé',
        metier: metierPills[0] || '',
        metiers: metierPills,
        contrats: contrat ? [contrat] : ['CDI'],
        experience: '',
        disponible: dispoVal === 'immediate',
        niveau: formations[0] ? formations[0].titre : 'Non précisé',
        formations: formations.length ? formations : [{ titre: 'Non précisé', ecole: '', date: '' }],
        langues: langues.length ? langues : [{ nom: 'Français', niveau: 'Courant' }],
        competences: competences.length ? competences : [],
        mobilite: mobPills,
        date: dateStr
      };

      try {
        const existing = JSON.parse(localStorage.getItem('cv_deposes') || '[]');
        existing.unshift(profil);
        localStorage.setItem('cv_deposes', JSON.stringify(existing));
        localStorage.setItem('cv_soumis_data', JSON.stringify({ nom, prenom, email, date: dateStr, profilId: profil.id }));
        /* Lier le profil au compte connecté */
        const cu = getCurrentUser();
        if (cu) { cu.profilId = profil.id; setCurrentUser(cu); }
      } catch(err) {}

      setTimeout(() => {
        window.location.href = 'cvtheque.html';
      }, 1800);
    }

    /* Menu mobile */
    const hamburgerBtn = document.getElementById('hamburger');
    const mobileMenuEl = document.getElementById('mobileMenu');
    hamburgerBtn.addEventListener('click', () => {
      const isOpen = mobileMenuEl.classList.toggle('open');
      hamburgerBtn.classList.toggle('open', isOpen);
      hamburgerBtn.setAttribute('aria-expanded', isOpen);
    });
