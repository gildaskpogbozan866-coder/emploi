/* ── Mobile nav ── */
    const hamburger = document.getElementById('hamburger');
    const mobileMenu = document.getElementById('mobileMenu');
    if (hamburger) {
      hamburger.addEventListener('click', () => {
        const open = mobileMenu.classList.toggle('open');
        hamburger.classList.toggle('open', open);
        hamburger.setAttribute('aria-expanded', open);
      });
    }

    /* ── Accordion sidebar ── */
    document.querySelectorAll('.cvt-filter-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const targetId = btn.dataset.target;
        const body = document.getElementById(targetId);
        const isOpen = body.classList.toggle('open');
        btn.classList.toggle('open', isOpen);
      });
    });

    /* ── Crédits CVthèque ── */
    function getCvCredits() {
      try { return JSON.parse(localStorage.getItem('cv_credits') || '{"total":0,"remaining":0}'); } catch(e) { return {total:0,remaining:0}; }
    }
    function setCvCredits(c) { localStorage.setItem('cv_credits', JSON.stringify(c)); }

    function decrementCvCredit() {
      const c = getCvCredits();
      if (c.remaining <= 0) {
        showToastCvt('Aucun crédit restant — achetez un pack pour continuer.');
        return false;
      }
      c.remaining = Math.max(0, c.remaining - 1);
      setCvCredits(c);
      updateCvCreditsBar();
      return true;
    }

    function showToastCvt(msg) {
      let t = document.getElementById('cvtToast');
      if (!t) {
        t = document.createElement('div');
        t.id = 'cvtToast';
        t.style.cssText = 'position:fixed;bottom:24px;right:24px;background:#042C53;color:#fff;font-family:var(--font-body);font-size:13.5px;font-weight:600;padding:12px 20px;border-radius:10px;box-shadow:0 8px 32px rgba(4,44,83,0.3);z-index:9999;opacity:0;transform:translateY(8px);transition:opacity .25s,transform .25s;';
        document.body.appendChild(t);
      }
      t.textContent = msg;
      t.style.opacity = '1'; t.style.transform = 'translateY(0)';
      setTimeout(() => { t.style.opacity = '0'; t.style.transform = 'translateY(8px)'; }, 3200);
    }

    function updateCvCreditsBar() {
      const c = getCvCredits();
      const bar = document.getElementById('cvtCreditsBar');
      const cnt = document.getElementById('cvtCreditsCount');
      if (!bar) return;
      if (c.total > 0) {
        bar.style.display = 'flex';
        cnt.textContent = c.remaining + ' crédit' + (c.remaining > 1 ? 's' : '');
        cnt.style.color = c.remaining === 0 ? '#ff8a80' : c.remaining <= 3 ? '#FFD740' : '#F5C842';
      } else {
        bar.style.display = 'none';
      }
    }

    /* ── CV soumis via le formulaire ── */
    function getUserCvs() {
      try { return JSON.parse(localStorage.getItem('cv_deposes') || '[]'); } catch(e) { return []; }
    }

    function escHtml(s) {
      return String(s || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    /* ── Data (enrichie avec coordonnées complètes) ── */
    const PROFILS_DATA = [
      {
        id: 0,
        prenom: 'Kolade', nom: 'Amara',
        tel: '+229 97 12 34 56', email: 'kolade.amara@gmail.com',
        age: 26, quartier: 'Haie Vive',
        avatar: "KA", avatarBg: "#dbeafe",
        metier: "Développeur Web Full-Stack",
        secteur: "tech", secteurLabel: "Technologie · Web",
        pays: "Bénin", ville: "Cotonou",
        disponible: true, contrats: ["CDI", "Freelance"],
        experience: "3 ans", expLabel: "3 à 5 ans",
        niveau: "Bac+4",
        competences: ["React", "Node.js", "PostgreSQL", "Docker"],
        langues: [{ nom: "Français", niveau: "Langue maternelle" }, { nom: "Anglais", niveau: "Courant (B2)" }],
        formations: [{ titre: "Master Informatique — Génie Logiciel", ecole: "Université d'Abomey-Calavi", date: "2018 – 2020" }],
        attestations: ["Diplôme Master Informatique — UAC 2020", "Certificat React Developer 2023", "Attestation stage Société Télécoms Bénin 2021"]
      },
      {
        id: 1,
        prenom: 'Aminata', nom: 'Diallo',
        tel: '+221 77 234 56 78', email: 'aminata.diallo@outlook.com',
        age: 29, quartier: 'Almadies',
        avatar: "AD", avatarBg: "#fce7f3",
        metier: "Designer UI/UX",
        secteur: "design", secteurLabel: "Design · Numérique",
        pays: "Sénégal", ville: "Dakar",
        disponible: true, contrats: ["CDI", "Freelance"],
        experience: "4 ans", expLabel: "3 à 5 ans",
        niveau: "Bac+3",
        competences: ["Figma", "Adobe XD", "Illustrator", "Prototypage"],
        langues: [{ nom: "Français", niveau: "Langue maternelle" }, { nom: "Anglais", niveau: "Professionnel (B2)" }],
        formations: [{ titre: "Licence Design Graphique", ecole: "Institut Supérieur de Design — Dakar", date: "2015 – 2018" }],
        attestations: ["Licence Design Graphique — Dakar 2018", "Certification Google UX Design 2022", "Portfolio professionnel disponible sur demande"]
      },
      {
        id: 2,
        prenom: 'Jules', nom: 'Kouamé',
        tel: '+225 07 123 45 67', email: 'jules.kouame@yahoo.fr',
        age: 31, quartier: 'Cocody',
        avatar: "JK", avatarBg: "#d1fae5",
        metier: "Comptable Senior",
        secteur: "finance", secteurLabel: "Finance · Audit",
        pays: "Côte d'Ivoire", ville: "Abidjan",
        disponible: false, contrats: ["CDI"],
        experience: "5 ans", expLabel: "5 à 10 ans",
        niveau: "Bac+5",
        competences: ["SYSCOHADA", "Sage Comptabilité", "Audit", "Fiscalité"],
        langues: [{ nom: "Français", niveau: "Langue maternelle" }, { nom: "Anglais", niveau: "Intermédiaire (B1)" }],
        formations: [{ titre: "Master CCA — Comptabilité Contrôle Audit", ecole: "ESCA Abidjan", date: "2015 – 2017" }],
        attestations: ["Master CCA — ESCA Abidjan 2017", "Attestation cabinet audit EY 2019-2022", "Certificat SYSCOHADA révisé 2021"]
      },
      {
        id: 3,
        prenom: 'Fatoumata', nom: 'Traoré',
        tel: '+223 76 12 34 56', email: 'fatoumata.traore@gmail.com',
        age: 34, quartier: 'ACI 2000',
        avatar: "FT", avatarBg: "#ede9fe",
        metier: "Chargée de Communication",
        secteur: "marketing", secteurLabel: "Marketing · Communication",
        pays: "Mali", ville: "Bamako",
        disponible: true, contrats: ["CDI", "CDD"],
        experience: "6 ans", expLabel: "5 à 10 ans",
        niveau: "Bac+3",
        competences: ["Meta Ads", "Google Ads", "Community Management", "Rédaction web"],
        langues: [{ nom: "Français", niveau: "Courant" }, { nom: "Bambara", niveau: "Langue maternelle" }],
        formations: [{ titre: "Licence Communication", ecole: "Université des Sciences Sociales — Bamako", date: "2014 – 2017" }],
        attestations: ["Licence Communication — Bamako 2017", "Certification Google Ads 2023", "Certification Meta Blueprint 2022"]
      },
      {
        id: 4,
        prenom: 'Kwame', nom: 'Mbida',
        tel: '+237 690 12 34 56', email: 'kwame.mbida@gmail.com',
        age: 34, quartier: 'Bastos',
        avatar: "KM", avatarBg: "#fef9c3",
        metier: "Ingénieur Électrique",
        secteur: "ingenierie", secteurLabel: "Ingénierie · Énergie",
        pays: "Cameroun", ville: "Yaoundé",
        disponible: true, contrats: ["CDI"],
        experience: "7 ans", expLabel: "5 à 10 ans",
        niveau: "Bac+5",
        competences: ["AutoCAD", "Photovoltaïque", "Gestion de projet", "PVsyst"],
        langues: [{ nom: "Français", niveau: "Langue maternelle" }, { nom: "Anglais", niveau: "Professionnel (B1)" }],
        formations: [{ titre: "Master Génie Électrique", ecole: "École Nationale Supérieure Polytechnique — Yaoundé", date: "2013 – 2015" }],
        attestations: ["Master Génie Électrique — ENSP Yaoundé 2015", "Certification PVsyst 2021", "Attestation ingénieur senior SONEL 2016-2021"]
      },
      {
        id: 5,
        prenom: 'Abena', nom: 'Bedi',
        tel: '+228 90 12 34 56', email: 'abena.bedi@gmail.com',
        age: 28, quartier: 'Bè',
        avatar: "AB", avatarBg: "#dcfce7",
        metier: "Assistante RH",
        secteur: "rh", secteurLabel: "Ressources Humaines",
        pays: "Togo", ville: "Lomé",
        disponible: true, contrats: ["CDI", "CDD"],
        experience: "4 ans", expLabel: "3 à 5 ans",
        niveau: "Bac+3",
        competences: ["Recrutement", "Formation", "Sage Paie", "SIRH"],
        langues: [{ nom: "Français", niveau: "Langue maternelle" }, { nom: "Anglais", niveau: "Courant (B2)" }],
        formations: [{ titre: "Licence Gestion des Ressources Humaines", ecole: "Université de Lomé", date: "2016 – 2019" }],
        attestations: ["Licence GRH — Université de Lomé 2019", "Certification Sage Paie 2022", "Attestation RH entreprise GNTT Togo 2021-2023"]
      },
      {
        id: 6,
        prenom: 'Martin', nom: 'Bello',
        tel: '+229 96 98 76 54', email: 'martin.bello@hotmail.fr',
        age: 38, quartier: 'Ouando',
        avatar: "MB", avatarBg: "#fee2e2",
        metier: "Médecin Généraliste",
        secteur: "sante", secteurLabel: "Santé · Médecine",
        pays: "Bénin", ville: "Porto-Novo",
        disponible: true, contrats: ["CDI", "CDD"],
        experience: "8 ans", expLabel: "5 à 10 ans",
        niveau: "Bac+7",
        competences: ["Médecine générale", "Urgences", "Télémédecine", "Santé publique"],
        langues: [{ nom: "Français", niveau: "Langue maternelle" }, { nom: "Anglais", niveau: "Médical (B1)" }],
        formations: [{ titre: "Doctorat en Médecine", ecole: "Faculté des Sciences de la Santé — UAC", date: "2008 – 2015" }],
        attestations: ["Doctorat en Médecine — FSS UAC 2015", "Spécialisation Urgences 2017", "Inscription Ordre des Médecins Bénin"]
      },
      {
        id: 7,
        prenom: 'Serge', nom: 'Ouassa',
        tel: '+229 61 55 66 77', email: 'serge.ouassa@gmail.com',
        age: 30, quartier: 'Akpakpa',
        avatar: "SO", avatarBg: "#e0f2fe",
        metier: "Développeur Mobile",
        secteur: "tech", secteurLabel: "Technologie · Mobile",
        pays: "Bénin", ville: "Cotonou",
        disponible: false, contrats: ["CDI", "Freelance"],
        experience: "5 ans", expLabel: "5 à 10 ans",
        niveau: "Bac+4",
        competences: ["Flutter", "React Native", "Firebase", "iOS / Android"],
        langues: [{ nom: "Français", niveau: "Langue maternelle" }, { nom: "Anglais", niveau: "Courant (B2)" }],
        formations: [{ titre: "Licence Informatique — Systèmes Embarqués", ecole: "EPAC — UAC", date: "2015 – 2018" }],
        attestations: ["Licence Informatique — EPAC 2018", "Certification Flutter Developer 2022", "Attestation développeur senior ONG Bénin Tech 2020-2022"]
      },
      {
        id: 8,
        prenom: 'Ndeye', nom: 'Diop',
        tel: '+221 77 987 65 43', email: 'ndeye.diop@gmail.com',
        age: 33, quartier: 'Plateau',
        avatar: "ND", avatarBg: "#fef3c7",
        metier: "Juriste d'Entreprise",
        secteur: "juridique", secteurLabel: "Juridique · Compliance",
        pays: "Sénégal", ville: "Dakar",
        disponible: true, contrats: ["CDI"],
        experience: "6 ans", expLabel: "5 à 10 ans",
        niveau: "Bac+5",
        competences: ["Droit OHADA", "Droit du travail", "Contrats commerciaux", "Compliance"],
        langues: [{ nom: "Français", niveau: "Langue maternelle" }, { nom: "Anglais", niveau: "Professionnel (C1)" }],
        formations: [{ titre: "Master Droit des Affaires et Fiscalité", ecole: "Université Cheikh Anta Diop — Dakar", date: "2014 – 2016" }],
        attestations: ["Master Droit des Affaires — UCAD Dakar 2016", "Barreau de Dakar (inscrite) 2017", "Certification Compliance & Governance 2023"]
      },
      {
        id: 9,
        prenom: 'Théodore', nom: 'Coulibaly',
        tel: '+225 05 678 90 12', email: 'theodore.coulibaly@outlook.com',
        age: 36, quartier: 'Marcory',
        avatar: "TC", avatarBg: "#f3e8ff",
        metier: "Responsable Logistique",
        secteur: "logistique", secteurLabel: "Logistique · Supply Chain",
        pays: "Côte d'Ivoire", ville: "Abidjan",
        disponible: true, contrats: ["CDI", "CDD"],
        experience: "9 ans", expLabel: "5 à 10 ans",
        niveau: "Bac+4",
        competences: ["Supply chain", "WMS", "Douanes", "Gestion d'entrepôt"],
        langues: [{ nom: "Français", niveau: "Langue maternelle" }, { nom: "Anglais", niveau: "Professionnel (B2)" }],
        formations: [{ titre: "Master Logistique et Transport International", ecole: "ISTC Abidjan", date: "2012 – 2014" }],
        attestations: ["Master Logistique — ISTC Abidjan 2014", "Certification Supply Chain Management 2021", "Attestation responsable logistique CFAO 2015-2023"]
      },
      {
        id: 10,
        prenom: 'Emmanuel', nom: 'Koffi',
        tel: '+228 91 23 45 67', email: 'emmanuel.koffi.ek@gmail.com',
        age: 39, quartier: 'Lom',
        avatar: "EK", avatarBg: "#d1fae5",
        metier: "Enseignant-Formateur",
        secteur: "education", secteurLabel: "Éducation · Formation",
        pays: "Togo", ville: "Lomé",
        disponible: true, contrats: ["CDI", "Freelance"],
        experience: "10 ans", expLabel: "10+",
        niveau: "Bac+5",
        competences: ["Pédagogie active", "E-learning", "Moodle", "Conception de curriculum"],
        langues: [{ nom: "Français", niveau: "Courant" }, { nom: "Anglais", niveau: "Intermédiaire (B1)" }],
        formations: [{ titre: "Master Sciences de l'Éducation", ecole: "Université de Lomé", date: "2010 – 2012" }],
        attestations: ["Master Sciences de l'Éducation — UL 2012", "Certification e-learning Moodle 2021", "Attestation 10 ans enseignement Lycée Zinsou 2013-2023"]
      },
      {
        id: 11,
        prenom: 'Pascal', nom: 'Gbede',
        tel: '+237 697 89 01 23', email: 'pascal.gbede@gmail.com',
        age: 35, quartier: 'Akwa',
        avatar: "PG", avatarBg: "#fce7f3",
        metier: "Analyste Financier",
        secteur: "finance", secteurLabel: "Finance · Investissement",
        pays: "Cameroun", ville: "Douala",
        disponible: false, contrats: ["CDI"],
        experience: "7 ans", expLabel: "5 à 10 ans",
        niveau: "Bac+5",
        competences: ["Modélisation financière", "Excel avancé", "Bloomberg Terminal", "Private Equity"],
        langues: [{ nom: "Français", niveau: "Langue maternelle" }, { nom: "Anglais", niveau: "Courant (C1)" }],
        formations: [{ titre: "Master Finance d'Entreprise et Marchés", ecole: "ESSEC Business School Afrique — Douala", date: "2014 – 2016" }],
        attestations: ["Master Finance — ESSEC Douala 2016", "CFA Level 1 2020", "Attestation analyste senior ArcelorMittal 2017-2023"]
      }
    ];

    function padId(n) { return String(n).padStart(8, '0'); }

    /* ── Carte VERROUILLÉE (sans pack) ── */
    function buildCardLocked(p) {
      const langStr  = (p.langues || []).map(l => `${l.nom} (${l.niveau})`).join(', ');
      const form     = (p.formations || [])[0];
      const dispoColor = p.disponible ? '#16a34a' : '#185FA5';
      const dispoText  = p.disponible ? 'Disponible' : 'En poste';

      return `
        <div class="cvt-card" onclick="window.location='profil.html?id=${p.id}'">
          <div class="cvt-card__inner">
            <div class="cvt-card__id">Profil n°${padId(p.id + 1)}</div>
            <div class="cvt-card__body">
              <div class="cvt-card__photo" style="background:${p.avatarBg}">
                <svg class="cvt-card__photo-icon" xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                  <path d="M12 12c2.7 0 4.8-2.15 4.8-4.8S14.7 2.4 12 2.4 7.2 4.55 7.2 7.2 9.3 12 12 12zm0 2.4c-3.21 0-9.6 1.61-9.6 4.8v2.4h19.2v-2.4c0-3.19-6.39-4.8-9.6-4.8z"/>
                </svg>
              </div>
              <div class="cvt-card__info">
                <div style="font-family:var(--font-body);font-size:17px;font-weight:800;color:#042C53;line-height:1.2;margin-bottom:3px;letter-spacing:-0.01em;">${escHtml(p.metier || p.secteurLabel || 'Profil candidat')}</div>
                <div style="font-family:var(--font-body);font-size:12px;color:#64748b;margin-bottom:8px;">${escHtml(p.pays)} · ${escHtml(p.ville)} · <span style="color:${dispoColor};font-weight:700;">${dispoText}</span></div>
                <div class="cvt-card__row">
                  <span class="cvt-card__label">Expérience :</span>
                  <span class="cvt-card__val">${escHtml(p.experience)}</span>
                </div>
                <div class="cvt-card__row">
                  <span class="cvt-card__label">Secteur :</span>
                  <span class="cvt-card__val">${escHtml(p.secteurLabel)}</span>
                </div>
                ${form ? `<div class="cvt-card__row"><span class="cvt-card__label">Formation :</span><span class="cvt-card__val">${escHtml(p.niveau)}</span></div><div class="cvt-card__formation-name">${escHtml(form.titre)}</div><div class="cvt-card__formation-date">${escHtml(form.date)} · ${escHtml(form.ecole)}</div>` : ''}
                <div class="cvt-card__row">
                  <span class="cvt-card__label">Langues :</span>
                  <span class="cvt-card__val">${escHtml(langStr)}</span>
                </div>
              </div>
            </div>
            <div class="cvt-card__footer">
              <a href="achat-cv.html" class="cvt-card__btn" onclick="event.stopPropagation()">
                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" d="M7 11V7a5 5 0 0110 0v4"/></svg>
                Débloquer ce profil CV
              </a>
            </div>
          </div>
        </div>`;
    }

    /* ── Carte DÉBLOQUÉE (avec pack) ── */
    function buildCardUnlocked(p) {
      const langStr  = (p.langues || []).map(l => `${l.nom} (${l.niveau})`).join(', ');
      const compStr  = (p.competences || []).join(', ');
      const form     = (p.formations || [])[0];
      const attests  = p.attestations || [];
      const dispoColor = p.disponible ? '#16a34a' : '#185FA5';
      const dispoText  = p.disponible ? 'Disponible' : 'En poste';
      const noCredit   = getCvCredits().remaining <= 0;
      const disabledAttr  = noCredit ? 'disabled' : '';
      const disabledStyle = noCredit ? 'opacity:.4;cursor:not-allowed;' : 'cursor:pointer;';

      const compsHtml = (p.competences || []).map(c => `<span style="background:#eef3fb;color:#185FA5;font-size:10.5px;font-weight:600;padding:2px 9px;border-radius:20px;">${escHtml(c)}</span>`).join('');
      const attestsHtml = attests.length
        ? attests.map(a => `<li style="font-size:12px;color:#374151;padding:3px 0;border-bottom:1px solid #f1f5f9;">${escHtml(a)}</li>`).join('')
        : '<li style="font-size:12px;color:#94a3b8;">Aucune attestation renseignée</li>';

      return `
        <div class="cvt-card" style="border:2px solid #c6f6d5;">
          <div class="cvt-card__inner">
            <div class="cvt-card__id">Profil n°${padId(p.id + 1)}</div>
            <div class="cvt-card__body">
              <div class="cvt-card__photo" style="background:linear-gradient(135deg,#042C53,#185FA5);">
                <span style="font-family:var(--font-body);font-size:18px;font-weight:700;color:#fff;">${escHtml(p.avatar || 'CV')}</span>
              </div>
              <div class="cvt-card__info">
                <div style="font-family:var(--font-body);font-size:18px;font-weight:800;color:#042C53;line-height:1.2;margin-bottom:2px;letter-spacing:-0.01em;">${escHtml(p.metier || p.secteurLabel)}</div>
                <div style="font-family:var(--font-body);font-size:13px;font-weight:700;color:#185FA5;margin-bottom:2px;">${escHtml(p.prenom)} ${escHtml(p.nom)}</div>
                <div style="font-size:12px;color:#64748b;margin-bottom:7px;">${escHtml(p.ville)} · <span style="color:${dispoColor};font-weight:700;">${dispoText}</span></div>
                <div class="cvt-card__row">
                  <span class="cvt-card__label">Expérience :</span>
                  <span class="cvt-card__val">${escHtml(p.experience)}</span>
                </div>
                <div class="cvt-card__row">
                  <span class="cvt-card__label">Secteur :</span>
                  <span class="cvt-card__val">${escHtml(p.secteurLabel)}</span>
                </div>
                <div class="cvt-card__row">
                  <span class="cvt-card__label">Formation :</span>
                  <span class="cvt-card__val">${escHtml(p.niveau)}</span>
                </div>
              </div>
            </div>

            <!-- Coordonnées débloquées -->
            <div style="background:#f8faff;border:1px solid #dbeafe;border-radius:10px;padding:12px 14px;margin:10px 0;">
              <div style="font-family:var(--font-body);font-size:10px;font-weight:700;color:#378ADD;text-transform:uppercase;letter-spacing:.08em;margin-bottom:8px;">
                <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="vertical-align:middle;margin-right:3px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Coordonnées débloquées
              </div>
              <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;">
                <div><div style="font-size:10px;color:#94a3b8;">Tél / WhatsApp</div><div style="font-size:12.5px;font-weight:600;color:#042C53;">${escHtml(p.tel||'—')}</div></div>
                <div><div style="font-size:10px;color:#94a3b8;">Email</div><div style="font-size:12px;font-weight:600;color:#042C53;word-break:break-all;">${escHtml(p.email||'—')}</div></div>
                <div><div style="font-size:10px;color:#94a3b8;">©ge</div><div style="font-size:12.5px;font-weight:600;color:#042C53;">${p.age ? p.age + ' ans' : '—'}</div></div>
                <div><div style="font-size:10px;color:#94a3b8;">Quartier</div><div style="font-size:12.5px;font-weight:600;color:#042C53;">${escHtml(p.quartier||'—')}</div></div>
              </div>
            </div>

            <!-- Compétences -->
            ${compsHtml ? `<div style="display:flex;flex-wrap:wrap;gap:5px;margin-bottom:10px;">${compsHtml}</div>` : ''}

            <!-- Langues & Formation -->
            <div style="font-size:11.5px;color:#374151;margin-bottom:8px;">
              <span style="font-weight:600;color:#64748b;">Langues : </span>${escHtml(langStr)}
            </div>
            ${form ? `<div style="font-size:11.5px;color:#374151;margin-bottom:10px;"><span style="font-weight:600;color:#64748b;">Formation : </span>${escHtml(form.titre)} — ${escHtml(form.ecole)}</div>` : ''}

            <!-- Attestations -->
            <div style="margin-bottom:12px;">
              <div style="font-family:var(--font-body);font-size:10px;font-weight:700;color:#38A169;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;">Attestations &amp; Justificatifs</div>
              <ul style="list-style:none;padding:0;margin:0;">${attestsHtml}</ul>
            </div>

            <!-- Bouton PDF -->
            <div>
              <button ${disabledAttr} onclick="event.stopPropagation();telechargerCvPDF(${p.id})"
                style="display:inline-flex;align-items:center;justify-content:center;gap:5px;width:100%;font-family:var(--font-body);font-size:12px;font-weight:700;background:#042C53;color:#fff;border:none;padding:9px 12px;border-radius:8px;${disabledStyle}"
                title="Télécharger le dossier complet PDF (−1 crédit)">
                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                Télécharger dossier PDF complet
              </button>
            </div>
            ${noCredit ? '<p style="font-family:var(--font-body);font-size:10.5px;color:#c53030;margin-top:7px;text-align:center;"><a href="achat-cv.html" style="color:#c53030;font-weight:700;">Rechargez vos crédits</a> pour télécharger</p>' : '<p style="font-family:var(--font-body);font-size:10px;color:#94a3b8;margin-top:7px;text-align:center;">Chaque téléchargement utilise 1 crédit</p>'}
          </div>
        </div>`;
    }

    /* ── Téléchargement PDF ── */
    function findCvProfil(id) {
      const all = [...getUserCvs(), ...PROFILS_DATA];
      return all.find(p => String(p.id) === String(id)) || null;
    }

    function telechargerCvPDF(id) {
      const p = findCvProfil(id);
      if (!p) return;
      if (!decrementCvCredit()) return;

      const comps   = (p.competences || []);
      const attests = (p.attestations || []);
      const langues = (p.langues || []);
      const forms   = (p.formations || []).filter(f => f.titre && f.titre !== 'Non précisé');
      const numStr  = padId(typeof p.id === 'number' ? p.id + 1 : p.id);
      const nom     = (p.prenom || '') + ' ' + (p.nom || '');

      const html = `<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8">
<title>CV — ${nom}</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
body{font-family:Arial,Helvetica,sans-serif;color:#042C53;background:#fff}
.header{background:linear-gradient(135deg,#042C53 0%,#185FA5 100%);padding:28px 36px;display:flex;align-items:flex-start;justify-content:space-between;gap:16px}
.header__brand{color:rgba(255,255,255,.55);font-size:10px;font-weight:700;letter-spacing:.1em;text-transform:uppercase;margin-bottom:6px}
.header__name{font-size:22px;font-weight:700;color:#fff}
.header__metier{display:inline-block;background:rgba(245,200,66,.2);color:#F5C842;font-size:11px;font-weight:700;padding:4px 14px;border-radius:20px;margin-top:8px}
.header__id{font-size:11px;color:rgba(255,255,255,.5);margin-top:4px}
.body{padding:32px 36px}
.section{margin-bottom:28px}
.section__title{font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:#378ADD;border-bottom:2px solid #e2e8f0;padding-bottom:6px;margin-bottom:14px}
.grid{display:grid;grid-template-columns:1fr 1fr;gap:10px 32px}
.field{display:flex;flex-direction:column;gap:2px}
.field__lbl{font-size:10px;color:#94a3b8}
.field__val{font-size:13px;font-weight:600;color:#042C53}
.chips{display:flex;flex-wrap:wrap;gap:6px}
.chip{background:#eef3fb;color:#185FA5;font-size:11px;font-weight:600;padding:3px 10px;border-radius:20px}
.attest-item{font-size:12px;color:#374151;padding:5px 0;border-bottom:1px solid #f1f5f9}
.pj-note{font-size:11px;color:#94a3b8;margin-top:10px;padding:9px 12px;background:#f8faff;border-left:3px solid #dbeafe;border-radius:0 6px 6px 0;font-style:italic;line-height:1.5}
.footer{text-align:center;font-size:10px;color:#94a3b8;border-top:1px solid #e2e8f0;padding:16px 36px;margin-top:8px}
@media print{body{-webkit-print-color-adjust:exact;print-color-adjust:exact}}
</style></head><body>
<div class="header">
  <div>
    <div class="header__brand">Emploi Bouge Bénin · CVthèque</div>
    <div class="header__name">${nom}</div>
    <div class="header__metier">${p.metier || '—'}</div>
    <div class="header__id">Référence : CV-${numStr}</div>
  </div>
  <div style="text-align:right;color:rgba(255,255,255,.7);font-size:11px;">Téléchargé le<br>${new Date().toLocaleDateString('fr-FR')}</div>
</div>
<div class="body">
  <div class="section">
    <div class="section__title">Coordonnées</div>
    <div class="grid">
      <div class="field"><span class="field__lbl">Nom complet</span><span class="field__val">${nom}</span></div>
      <div class="field"><span class="field__lbl">WhatsApp / Tél.</span><span class="field__val">${p.tel||'—'}</span></div>
      <div class="field"><span class="field__lbl">Email</span><span class="field__val">${p.email||'—'}</span></div>
      <div class="field"><span class="field__lbl">©ge</span><span class="field__val">${p.age ? p.age + ' ans' : '—'}</span></div>
      <div class="field"><span class="field__lbl">Ville</span><span class="field__val">${p.ville||'—'}, ${p.pays||'—'}</span></div>
      <div class="field"><span class="field__lbl">Quartier</span><span class="field__val">${p.quartier||'—'}</span></div>
    </div>
  </div>
  <div class="section">
    <div class="section__title">Profil professionnel</div>
    <div class="grid">
      <div class="field"><span class="field__lbl">Métier</span><span class="field__val">${p.metier||'—'}</span></div>
      <div class="field"><span class="field__lbl">Expérience</span><span class="field__val">${p.experience||'—'}</span></div>
      <div class="field"><span class="field__lbl">Disponibilité</span><span class="field__val">${p.disponible ? 'Disponible immédiatement' : 'En poste'}</span></div>
      <div class="field"><span class="field__lbl">Niveau d'études</span><span class="field__val">${p.niveau||'—'}</span></div>
      <div class="field"><span class="field__lbl">Contrat recherché</span><span class="field__val">${(p.contrats||[]).join(', ')||'—'}</span></div>
    </div>
  </div>
  ${forms.length ? `<div class="section"><div class="section__title">Formation</div>${forms.map(f=>`<div class="attest-item"><strong>${f.titre}</strong> — ${f.ecole}${f.date ? ' ('+f.date+')' : ''}</div>`).join('')}</div>` : ''}
  ${comps.length ? `<div class="section"><div class="section__title">Compétences</div><div class="chips">${comps.map(c=>`<span class="chip">${c}</span>`).join('')}</div></div>` : ''}
  ${langues.length ? `<div class="section"><div class="section__title">Langues</div><div class="chips">${langues.map(l=>`<span class="chip">${l.nom}${l.niveau ? ' — '+l.niveau : ''}</span>`).join('')}</div></div>` : ''}
  ${attests.length ? `<div class="section"><div class="section__title">Attestations &amp; Justificatifs</div>${attests.map(a=>`<div class="attest-item">• ${a}</div>`).join('')}</div>` : ''}
  <div class="section"><div class="section__title">Pièces jointes &amp; Annexes</div>${attests.length ? attests.map(a=>`<div class="attest-item" style="display:flex;align-items:flex-start;gap:6px;">&#128206; ${a}</div>`).join('') : '<p style="font-size:12px;color:#94a3b8;">Aucun document joint déclaré</p>'}<p class="pj-note">Les fichiers physiques (diplômes, certificats scannés) sont à demander directement au candidat. Cette section liste les justificatifs déclarés lors de l'inscription.</p></div>
</div>
<div class="footer">Emploi Bouge Bénin · emploibougebenin.com · +229 01 51 92 98 56 · Cotonou, Bénin<br>Ce CV est confidentiel — usage interne uniquement</div>
</body></html>`;

      const win = window.open('', '_blank');
      win.document.write(html);
      win.document.close();
      setTimeout(() => { win.focus(); win.print(); }, 600);
      showToastCvt('Dossier PDF généré pour ' + nom);
    }

    /* ── Active filters state ── */
    const filters = { secteur: new Set(), pays: new Set(), contrat: new Set(), niveau: new Set(), ville: new Set() };

    document.querySelectorAll('.cvt-filter-opt input').forEach(cb => {
      cb.addEventListener('change', () => {
        const key = cb.dataset.filter;
        if (key && filters[key] !== undefined) {
          if (cb.checked) filters[key].add(cb.value);
          else            filters[key].delete(cb.value);
        }
        render();
      });
    });

    document.getElementById('searchInput').addEventListener('input', render);

    function render() {
      const credits    = getCvCredits();
      const hasCredits = credits.total > 0;
      const buildCard  = hasCredits ? buildCardUnlocked : buildCardLocked;

      const q = document.getElementById('searchInput').value.toLowerCase().trim();
      const ALL_PROFILS = [...getUserCvs(), ...PROFILS_DATA];

      const result = ALL_PROFILS.filter(p => {
        if (filters.secteur.size > 0 && !filters.secteur.has(p.secteur)) return false;
        if (filters.pays.size    > 0 && !filters.pays.has(p.pays))       return false;
        if (filters.contrat.size > 0 && !(p.contrats||[]).some(c => filters.contrat.has(c))) return false;
        if (filters.niveau.size  > 0 && !filters.niveau.has(p.niveau))   return false;
        if (filters.ville.size   > 0 && !filters.ville.has(p.ville))     return false;
        if (q) {
          const hay = [p.metier, p.secteurLabel, p.pays, p.ville, p.niveau, ...(p.competences||[]), (p.langues||[]).map(l=>l.nom).join(' ')].join(' ').toLowerCase();
          if (!hay.includes(q)) return false;
        }
        return true;
      });

      const list  = document.getElementById('cvtList');
      const title = document.getElementById('cvtCountTitle');
      const n = result.length;
      title.textContent = n + ' Profil' + (n > 1 ? 's' : '') + ' trouvé' + (n > 1 ? 's' : '');

      if (n === 0) {
        list.innerHTML = `<div class="cvt-empty"><div class="cvt-empty__title">Aucun profil ne correspond</div><div class="cvt-empty__sub">Modifiez vos filtres ou votre recherche.</div></div>`;
      } else {
        list.innerHTML = result.map(buildCard).join('');
      }
    }

    updateCvCreditsBar();
    render();
