var currentStep = 1;
    var STEP_TITLES = ['', 'Informations personnelles', 'Votre CV & Profil', 'Confirmer & Payer'];

    function updateStepsBar(n) {
      document.querySelectorAll('.depot-step').forEach(function(el, i) {
        var num = i + 1;
        el.classList.remove('depot-step--active','depot-step--done');
        if (num < n) el.classList.add('depot-step--done');
        else if (num === n) el.classList.add('depot-step--active');
      });
      var t = document.getElementById('cardTitle');
      if (t) t.textContent = STEP_TITLES[n] || '';
    }

    function showErr(step, msg) {
      var e = document.getElementById('err' + step);
      var m = document.getElementById('err' + step + 'Msg');
      if (!e || !m) return;
      m.textContent = msg;
      e.classList.add('show');
      setTimeout(function() { e.classList.remove('show'); }, 5000);
    }

    function validateStep1() {
      var nom = document.getElementById('fNom').value.trim();
      var pre = document.getElementById('fPrenom').value.trim();
      var em  = document.getElementById('fEmail').value.trim();
      var tel = document.getElementById('fTel').value.trim();
      return true;
    }

    function validateStep2() {
      var file    = document.getElementById('cvFile').files[0];
      var metiers = document.getElementById('metierTagsList').children.length;
      var contrat = document.querySelector('input[name="contrat"]:checked');
      var dispo   = document.getElementById('fDispo').value;
      var res     = document.getElementById('fResidence').value.trim();
      if (!file)    { showErr(2,'Veuillez téléverser votre CV.'); return false; }
      if (!metiers) { showErr(2,'Ajoutez au moins un métier recherché.'); document.getElementById('metierInput').focus(); return false; }
      if (!contrat) { showErr(2,'Veuillez choisir un type de contrat.'); return false; }
      if (!dispo)   { showErr(2,'Veuillez indiquer votre disponibilité.'); document.getElementById('fDispo').focus(); return false; }
      if (!res)     { showErr(2,'Veuillez indiquer votre résidence.'); document.getElementById('fResidence').focus(); return false; }
      return true;
    }

    function buildConfirmation() {
      var nom  = document.getElementById('fNom').value.trim();
      var pre  = document.getElementById('fPrenom').value.trim();
      var em   = document.getElementById('fEmail').value.trim();
      var tel  = document.getElementById('fTel').value.trim();
      var file = document.getElementById('cvFile').files[0];

      document.getElementById('confNom').textContent   = pre + ' ' + nom;
      document.getElementById('confEmail').textContent  = em;
      document.getElementById('confTel').textContent    = tel;

      var cvEl = document.getElementById('confCV');
      if (file) {
        var ext  = file.name.split('.').pop().toLowerCase();
        var size = file.size < 1048576 ? (file.size/1024).toFixed(1)+' Ko' : (file.size/1048576).toFixed(2)+' Mo';
        cvEl.innerHTML = '<div style="display:flex;align-items:center;gap:10px;background:#fff;border:1.5px solid #dde9fb;border-radius:10px;padding:12px 14px;">'
          + '<svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#042C53" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>'
          + '<div><div style="font-size:13.5px;font-weight:700;color:#042C53">' + file.name + '</div>'
          + '<div style="font-size:12px;color:#64748b">' + ext.toUpperCase() + ' · ' + size + '</div></div></div>';
      } else {
        cvEl.innerHTML = '<span style="font-size:13px;color:#64748b;">Aucun fichier</span>';
      }

      var metierPills = document.getElementById('metierTagsList').querySelectorAll('.tag-pill');
      var metierTexts = Array.from(metierPills).map(function(p) { return p.textContent.replace('✕','').trim(); });
      var contratEl   = document.querySelector('input[name="contrat"]:checked');
      var dispoSel    = document.getElementById('fDispo');
      var dispoText   = dispoSel.options[dispoSel.selectedIndex] ? dispoSel.options[dispoSel.selectedIndex].text : '—';
      var residence   = document.getElementById('fResidence').value.trim();

      document.getElementById('confProfil').innerHTML = '<div class="conf-grid">'
        + '<div class="conf-row"><span class="conf-lbl">Métier(s)</span><span class="conf-val">' + (metierTexts.length ? metierTexts.join(', ') : '—') + '</span></div>'
        + '<div class="conf-row"><span class="conf-lbl">Contrat</span><span class="conf-val">' + (contratEl ? contratEl.value : '—') + '</span></div>'
        + '<div class="conf-row"><span class="conf-lbl">Disponibilité</span><span class="conf-val">' + dispoText + '</span></div>'
        + '<div class="conf-row"><span class="conf-lbl">Résidence</span><span class="conf-val">' + (residence || '—') + '</span></div>'
        + '</div>';
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

    /* ── Upload ── */
    (function() {
      var input   = document.getElementById('cvFile');
      var zone    = document.getElementById('uploadZone');
      var preview = document.getElementById('filePreview');
      var nameEl  = document.getElementById('previewName');
      var metaEl  = document.getElementById('previewMeta');
      var rmBtn   = document.getElementById('removeFile');
      function formatSize(b) { return b < 1024 ? b+' o' : b < 1048576 ? (b/1024).toFixed(1)+' Ko' : (b/1048576).toFixed(2)+' Mo'; }
      function showPreview(f) {
        var ext = f.name.split('.').pop().toLowerCase();
        nameEl.textContent = f.name;
        metaEl.textContent = (ext ? ext.toUpperCase() : '') + ' • ' + formatSize(f.size);
        zone.classList.add('has-file');
        preview.classList.add('visible');
      }
      function clearPreview() { input.value = ''; zone.classList.remove('has-file'); preview.classList.remove('visible'); }
      input.addEventListener('change', function() { if (this.files && this.files[0]) showPreview(this.files[0]); });
      zone.addEventListener('dragover', function(e) { e.preventDefault(); zone.style.borderColor='#042C53'; });
      zone.addEventListener('dragleave', function() { zone.style.borderColor=''; });
      zone.addEventListener('drop', function(e) {
        e.preventDefault(); zone.style.borderColor='';
        var f = e.dataTransfer.files[0];
        if (f) { var dt = new DataTransfer(); dt.items.add(f); input.files = dt.files; showPreview(f); }
      });
      rmBtn.addEventListener('click', clearPreview);
    })();

    function addMetierTag() {
      var sel   = document.getElementById('metierSelect');
      var input = document.getElementById('metierInput');
      var list  = document.getElementById('metierTagsList');
      var text  = (sel.value && sel.value !== 'Autre') ? sel.value : input.value.trim();
      if (!text) return;
      var pill  = document.createElement('span');
      pill.className = 'tag-pill';
      pill.innerHTML = text + '<button type="button" class="tag-pill__remove">&#10005;</button>';
      pill.querySelector('.tag-pill__remove').addEventListener('click', function() { pill.remove(); });
      list.appendChild(pill);
      sel.value = ''; input.value = ''; input.focus();
    }
    document.getElementById('metierInput').addEventListener('keydown', function(e) {
      if (e.key === 'Enter') { e.preventDefault(); addMetierTag(); }
    });

    function selectContrat(label) {
      document.querySelectorAll('[id^="pillCD"]').forEach(function(el) {
        el.classList.remove('selected');
        var r = el.querySelector('input[type="radio"]'); if (r) r.checked = false;
      });
      label.classList.add('selected');
      var r = label.querySelector('input[type="radio"]'); if (r) r.checked = true;
    }

    var EYE_OPEN  = '<svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>';
    var EYE_CLOSE = '<svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>';
    function togglePwd(id, btn) {
      var inp = document.getElementById(id);
      if (inp.type === 'password') { inp.type = 'text'; btn.innerHTML = EYE_CLOSE; }
      else { inp.type = 'password'; btn.innerHTML = EYE_OPEN; }
    }

    var expCount = 0, compCount = 0, formCount = 0;
    function addExperience() {
      expCount++;
      var c = document.getElementById('experiencesContainer');
      var d = document.createElement('div'); d.className = 'exp-block';
      d.innerHTML = '<div class="exp-block__header"><span class="exp-block__title">Expérience ' + expCount + '</span><button type="button" class="exp-remove-btn" onclick="this.closest(\'.exp-block\').remove()">&#10005;</button></div>'
        + '<div class="form-row form-row--2"><div><label class="field__label">Poste <span class="req">*</span></label><input class="field__input" type="text" name="exp_poste[]" placeholder="Ex : Responsable RH" /></div>'
        + '<div><label class="field__label">Employeur <span class="req">*</span></label><input class="field__input" type="text" name="exp_employeur[]" placeholder="Entreprise XYZ" /></div></div>'
        + '<div class="form-row form-row--2"><div><label class="field__label">Début</label><input class="field__input" type="month" name="exp_debut[]" /></div>'
        + '<div><label class="field__label">Fin <small style="font-weight:400">(vide = en cours)</small></label><input class="field__input" type="month" name="exp_fin[]" /></div></div>'
        + '<div class="form-row form-row--1"><div><label class="field__label">Missions</label><textarea class="field__textarea" name="exp_missions[]" style="min-height:80px" placeholder="Décrivez vos missions…"></textarea></div></div>';
      c.appendChild(d);
    }
    function addCompetence() {
      compCount++;
      var c = document.getElementById('competencesContainer');
      var d = document.createElement('div'); d.className = 'exp-block'; d.style.cssText = 'padding:14px 16px;margin-bottom:10px;';
      d.innerHTML = '<div class="exp-block__header"><span class="exp-block__title">Compétence ' + compCount + '</span><button type="button" class="exp-remove-btn" onclick="this.closest(\'.exp-block\').remove()">&#10005;</button></div>'
        + '<input class="field__input" type="text" name="competence[]" placeholder="Ex : Gestion administrative, Rédaction…" />';
      c.appendChild(d);
    }
    function addFormation() {
      formCount++;
      var c = document.getElementById('formationsContainer');
      var d = document.createElement('div'); d.className = 'exp-block';
      d.innerHTML = '<div class="exp-block__header"><span class="exp-block__title">Formation ' + formCount + '</span><button type="button" class="exp-remove-btn" onclick="this.closest(\'.exp-block\').remove()">&#10005;</button></div>'
        + '<div class="form-row form-row--2"><div><label class="field__label">Diplôme <span class="req">*</span></label><input class="field__input" type="text" name="form_diplome[]" placeholder="BTS, Licence…" /></div>'
        + '<div><label class="field__label">Établissement <span class="req">*</span></label><input class="field__input" type="text" name="form_etablissement[]" placeholder="Université de…" /></div></div>'
        + '<div class="form-row form-row--2"><div><label class="field__label">Début</label><input class="field__input" type="number" name="form_debut[]" placeholder="2019" min="1970" max="2030" /></div>'
        + '<div><label class="field__label">Fin</label><input class="field__input" type="number" name="form_fin[]" placeholder="2022" min="1970" max="2030" /></div></div>';
      c.appendChild(d);
    }
    function addLangue() {
      var c = document.getElementById('languesContainer');
      var d = document.createElement('div'); d.className = 'lang-row';
      d.innerHTML = '<input class="field__input" type="text" name="langue[]" placeholder="Langue" style="flex:1" />'
        + '<span class="lang-sep">|</span>'
        + '<select class="field__select" name="langue_niveau[]" style="flex:1"><option value="">— Niveau —</option><option>Débutant</option><option>Intermédiaire</option><option>Bon niveau</option><option>Courant</option><option>Bilingue / Natif</option></select>'
        + '<button type="button" class="exp-remove-btn" onclick="this.closest(\'.lang-row\').remove()">&#10005;</button>';
      c.appendChild(d);
    }

    function soumettreCV() {
      var nom   = document.getElementById('fNom').value.trim();
      var prenom= document.getElementById('fPrenom').value.trim();
      var email = document.getElementById('fEmail').value.trim();
      var tel   = document.getElementById('fTel').value.trim();
      var btn   = document.getElementById('submitBtn');
      btn.disabled = true;
      btn.innerHTML = '<svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="animation:spin 1s linear infinite"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg> Redirection…';
      try {
        localStorage.setItem('cv_premium_data', JSON.stringify({
          nom, prenom, email, tel,
          date: new Date().toLocaleDateString('fr-FR', { day:'numeric', month:'long', year:'numeric' })
        }));
      } catch(e) {}
      setTimeout(function() { window.location.href = 'paiement-premium.html'; }, 1500);
    }

    const hamburger  = document.getElementById('hamburger');
    const mobileMenu = document.getElementById('mobileMenu');
    hamburger.addEventListener('click', () => {
      const open = mobileMenu.classList.toggle('open');
      hamburger.classList.toggle('open', open);
      hamburger.setAttribute('aria-expanded', open);
    });
    mobileMenu.querySelectorAll('a').forEach(l => l.addEventListener('click', () => {
      mobileMenu.classList.remove('open'); hamburger.classList.remove('open');
      hamburger.setAttribute('aria-expanded', 'false');
    }));
