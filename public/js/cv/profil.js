﻿/* ── Mobile nav ── */
    const hamburger = document.getElementById('hamburger');
    const mobileMenu = document.getElementById('mobileMenu');
    hamburger.addEventListener('click', () => {
      const open = mobileMenu.classList.toggle('open');
      hamburger.classList.toggle('open', open);
      hamburger.setAttribute('aria-expanded', open);
    });

    /* ── Profile data ── */
    const PROFILS_DATA = [
      {
        id: 0,
        avatar: "KA", avatarBg: "#dbeafe",
        metier: "Développeur Web Full-Stack",
        secteurLabel: "Technologie · Web",
        pays: "Bénin", ville: "Cotonou",
        disponible: true, dispoLabel: "Immédiatement",
        contrats: ["CDI", "Freelance"],
        experience: "3 ans", niveau: "Bac+4",
        secteurs: ["Technologie", "Startups", "E-commerce"],
        experiences: [
          { poste: "Développeur Full-Stack", entreprise: "Digital Solutions Bénin", periode: "01.2022 – présent", desc: ["Développement d'applications React / Node.js", "Architecture API REST et GraphQL", "Déploiement Docker sur VPS"] },
          { poste: "Développeur Frontend Junior", entreprise: "WebAgency Cotonou", periode: "06.2020 – 12.2021", desc: ["Intégration HTML/CSS responsive", "Animations et micro-interactions JavaScript"] }
        ],
        competences: ["React", "Node.js", "PostgreSQL", "Docker", "TypeScript", "REST API"],
        competencesCles: ["Architecture Full-Stack", "Développement Agile"],
        formations: [
          { titre: "Master Informatique — Génie Logiciel", ecole: "Université d'Abomey-Calavi", date: "2018 – 2020", desc: "Développement logiciel, bases de données, systèmes distribués" },
          { titre: "Licence Informatique", ecole: "UAC — Faculté des Sciences", date: "2015 – 2018", desc: "" }
        ],
        langues: [
          { nom: "Français", niveau: "Langue maternelle" },
          { nom: "Anglais", niveau: "Courant (B2)" },
          { nom: "Fon", niveau: "Notions" }
        ],
        mobilite: "Cotonou · Abomey-Calavi",
        teletravail: "Hybride",
        residence: "Cotonou",
        update: "10.05.2026",
        vues: 14
      },
      {
        id: 1,
        avatar: "AD", avatarBg: "#fce7f3",
        metier: "Designer UI/UX",
        secteurLabel: "Design · Numérique",
        pays: "Sénégal", ville: "Dakar",
        disponible: true, dispoLabel: "Immédiatement",
        contrats: ["CDI", "Freelance"],
        experience: "4 ans", niveau: "Bac+3",
        secteurs: ["Agences digitales", "Tech", "Médias"],
        experiences: [
          { poste: "Designer UI/UX Senior", entreprise: "CreativeHub Dakar", periode: "03.2021 – présent", desc: ["Conception d'interfaces mobiles et web sur Figma", "Conduite de sessions de recherche utilisateur"] },
          { poste: "Designer Graphique", entreprise: "Agence 360 Communication", periode: "01.2019 – 02.2021", desc: ["Créations print et digitales", "Identités visuelles et chartes graphiques"] }
        ],
        competences: ["Figma", "Adobe XD", "Illustrator", "Prototypage", "Design System", "Recherche UX"],
        competencesCles: ["Product Design", "User Research"],
        formations: [
          { titre: "Licence Design Graphique & Communication Visuelle", ecole: "Institut Supérieur de Design — Dakar", date: "2015 – 2018", desc: "Conception graphique, typographie, design d'interaction" }
        ],
        langues: [
          { nom: "Français", niveau: "Langue maternelle" },
          { nom: "Anglais", niveau: "Professionnel (B2)" },
          { nom: "Wolof", niveau: "Courant" }
        ],
        mobilite: "Dakar · Thiès",
        teletravail: "Télétravail complet",
        residence: "Dakar",
        update: "12.05.2026",
        vues: 21
      },
      {
        id: 2,
        avatar: "JK", avatarBg: "#d1fae5",
        metier: "Comptable Senior",
        secteurLabel: "Finance · Audit",
        pays: "Côte d'Ivoire", ville: "Abidjan",
        disponible: false, dispoLabel: "Actuellement en poste",
        contrats: ["CDI"],
        experience: "5 ans", niveau: "Bac+5",
        secteurs: ["Cabinet d'audit", "Finance", "Banque"],
        experiences: [
          { poste: "Comptable Senior", entreprise: "Cabinet Deloitte CI", periode: "09.2019 – présent", desc: ["Révision des comptes SYSCOHADA", "Audit financier PME et grandes entreprises", "Déclarations fiscales et liasses"] },
          { poste: "Assistant Comptable", entreprise: "Groupe Bolloré Africa", periode: "07.2017 – 08.2019", desc: ["Saisie comptable et rapprochements bancaires"] }
        ],
        competences: ["SYSCOHADA", "Sage Comptabilité", "Audit", "Fiscalité", "Cegid", "Excel avancé"],
        competencesCles: ["Audit financier", "Contrôle de gestion"],
        formations: [
          { titre: "Master CCA — Comptabilité Contrôle Audit", ecole: "ESCA Abidjan", date: "2015 – 2017", desc: "Audit, consolidation, fiscalité des entreprises" },
          { titre: "Licence Gestion & Finance", ecole: "Université Houphouët-Boigny", date: "2012 – 2015", desc: "" }
        ],
        langues: [
          { nom: "Français", niveau: "Langue maternelle" },
          { nom: "Anglais", niveau: "Intermédiaire (B1)" }
        ],
        mobilite: "Abidjan · Plateau · Cocody",
        teletravail: "Présentiel",
        residence: "Abidjan",
        update: "05.05.2026",
        vues: 9
      },
      {
        id: 3,
        avatar: "FT", avatarBg: "#ede9fe",
        metier: "Chargée de Communication",
        secteurLabel: "Marketing · Communication",
        pays: "Mali", ville: "Bamako",
        disponible: true, dispoLabel: "Dans 1 mois",
        contrats: ["CDI", "CDD"],
        experience: "6 ans", niveau: "Bac+3",
        secteurs: ["Agences de communication", "ONG", "Médias"],
        experiences: [
          { poste: "Responsable Communication Digitale", entreprise: "ONG Sahel Solidarité", periode: "01.2020 – présent", desc: ["Gestion des réseaux sociaux (80k abonnés)", "Campagnes Meta Ads et Google Ads", "Production de contenus vidéo et photo"] },
          { poste: "Community Manager", entreprise: "Agence Communik360", periode: "05.2017 – 12.2019", desc: ["Animation de 12 comptes clients", "Rédaction web et newsletter hebdomadaire"] }
        ],
        competences: ["Meta Ads", "Google Ads", "Community Management", "Rédaction web", "Canva Pro", "Hootsuite"],
        competencesCles: ["Marketing Digital", "Gestion de contenu"],
        formations: [
          { titre: "Licence Communication et Sciences de l'Information", ecole: "Université des Sciences Sociales — Bamako", date: "2014 – 2017", desc: "Journalisme, communication institutionnelle, relations publiques" }
        ],
        langues: [
          { nom: "Français", niveau: "Courant" },
          { nom: "Bambara", niveau: "Langue maternelle" },
          { nom: "Anglais", niveau: "Notions (A2)" }
        ],
        mobilite: "Bamako · Sikasso",
        teletravail: "Hybride",
        residence: "Bamako",
        update: "14.05.2026",
        vues: 17
      },
      {
        id: 4,
        avatar: "KM", avatarBg: "#fef9c3",
        metier: "Ingénieur Électrique",
        secteurLabel: "Ingénierie · Énergie",
        pays: "Cameroun", ville: "Yaoundé",
        disponible: true, dispoLabel: "Immédiatement",
        contrats: ["CDI"],
        experience: "7 ans", niveau: "Bac+5",
        secteurs: ["Énergie", "BTP", "Mines"],
        experiences: [
          { poste: "Chef de Projet Énergie Solaire", entreprise: "SolarTech Cameroun", periode: "03.2018 – présent", desc: ["Conception et déploiement de systèmes photovoltaïques", "Supervision de chantiers (10 MW installés)"] },
          { poste: "Ingénieur Électricien", entreprise: "AES-Sonel", periode: "09.2015 – 02.2018", desc: ["Maintenance des réseaux HTB et BT", "Diagnostic et dépannage d'équipements électriques"] }
        ],
        competences: ["AutoCAD", "HOMER Energy", "Photovoltaïque", "Gestion de projet", "MS Project", "PVsyst"],
        competencesCles: ["Énergies renouvelables", "Gestion de chantier"],
        formations: [
          { titre: "Master Génie Électrique — option Énergies Renouvelables", ecole: "École Nationale Supérieure Polytechnique — Yaoundé", date: "2013 – 2015", desc: "Réseaux électriques, énergies renouvelables, automatique" },
          { titre: "Licence Génie Électrique", ecole: "Université de Yaoundé I", date: "2010 – 2013", desc: "" }
        ],
        langues: [
          { nom: "Français", niveau: "Langue maternelle" },
          { nom: "Anglais", niveau: "Professionnel (B1)" }
        ],
        mobilite: "Yaoundé · Douala · Déplacements terrain",
        teletravail: "Présentiel",
        residence: "Yaoundé",
        update: "08.05.2026",
        vues: 11
      },
      {
        id: 5,
        avatar: "AB", avatarBg: "#dcfce7",
        metier: "Assistante RH",
        secteurLabel: "Ressources Humaines",
        pays: "Togo", ville: "Lomé",
        disponible: true, dispoLabel: "Immédiatement",
        contrats: ["CDI", "CDD"],
        experience: "4 ans", niveau: "Bac+3",
        secteurs: ["Services", "Industrie", "ONG"],
        experiences: [
          { poste: "Assistante RH", entreprise: "Groupe Ecobank Togo", periode: "06.2021 – présent", desc: ["Gestion de la paie (Sage Paie)", "Recrutement et intégration des nouvelles recrues"] },
          { poste: "Chargée de Recrutement", entreprise: "Cabinet RH Lomé Conseil", periode: "01.2019 – 05.2021", desc: ["Sourcing et tri de CV", "Entretiens téléphoniques et en présentiel"] }
        ],
        competences: ["Recrutement", "Formation", "Sage Paie", "SIRH", "Droit du travail", "ATS"],
        competencesCles: ["Gestion des talents", "Administration du personnel"],
        formations: [
          { titre: "Licence Gestion des Ressources Humaines", ecole: "Université de Lomé", date: "2016 – 2019", desc: "Droit social, paie, gestion des carrières" }
        ],
        langues: [
          { nom: "Français", niveau: "Langue maternelle" },
          { nom: "Anglais", niveau: "Courant (B2)" },
          { nom: "Ewe", niveau: "Courant" }
        ],
        mobilite: "Lomé · Kpalimé",
        teletravail: "Hybride",
        residence: "Lomé",
        update: "15.05.2026",
        vues: 19
      },
      {
        id: 6,
        avatar: "MB", avatarBg: "#fee2e2",
        metier: "Médecin Généraliste",
        secteurLabel: "Santé · Médecine",
        pays: "Bénin", ville: "Porto-Novo",
        disponible: true, dispoLabel: "Immédiatement",
        contrats: ["CDI", "CDD"],
        experience: "8 ans", niveau: "Bac+7",
        secteurs: ["Santé publique", "Hôpital", "Clinique"],
        experiences: [
          { poste: "Médecin Généraliste", entreprise: "Hôpital de Zone de Porto-Novo", periode: "09.2017 – présent", desc: ["Consultations générales et urgences", "Suivi de patients chroniques"] },
          { poste: "Médecin Stagiaire", entreprise: "CNHU-HKM Cotonou", periode: "01.2015 – 08.2017", desc: ["Rotations en médecine interne, pédiatrie et chirurgie"] }
        ],
        competences: ["Médecine générale", "Urgences", "Télémédecine", "Santé publique", "Pédiatrie"],
        competencesCles: ["Médecine clinique", "Santé communautaire"],
        formations: [
          { titre: "Doctorat en Médecine", ecole: "Faculté des Sciences de la Santé — UAC", date: "2008 – 2015", desc: "Médecine interne, chirurgie, pédiatrie, gynécologie" }
        ],
        langues: [
          { nom: "Français", niveau: "Langue maternelle" },
          { nom: "Anglais", niveau: "Médical (B1)" },
          { nom: "Fon", niveau: "Courant" }
        ],
        mobilite: "Porto-Novo · Cotonou · Bohicon",
        teletravail: "Présentiel",
        residence: "Porto-Novo",
        update: "11.05.2026",
        vues: 6
      },
      {
        id: 7,
        avatar: "SO", avatarBg: "#e0f2fe",
        metier: "Développeur Mobile",
        secteurLabel: "Technologie · Mobile",
        pays: "Bénin", ville: "Cotonou",
        disponible: false, dispoLabel: "Actuellement en poste",
        contrats: ["CDI", "Freelance"],
        experience: "5 ans", niveau: "Bac+4",
        secteurs: ["Technologie", "Fintech", "E-commerce"],
        experiences: [
          { poste: "Lead Developer Mobile", entreprise: "Fintech Hub Bénin", periode: "07.2021 – présent", desc: ["Développement d'une app de paiement mobile (Flutter)", "+50 000 téléchargements sur Play Store"] },
          { poste: "Développeur React Native", entreprise: "AppFactory Cotonou", periode: "01.2019 – 06.2021", desc: ["Développement cross-platform iOS/Android"] }
        ],
        competences: ["Flutter", "React Native", "Firebase", "iOS / Android", "Dart", "REST API"],
        competencesCles: ["Développement Mobile", "Fintech"],
        formations: [
          { titre: "Licence Informatique — Systèmes Embarqués", ecole: "EPAC — Université d'Abomey-Calavi", date: "2015 – 2018", desc: "" },
          { titre: "Bootcamp Mobile Development", ecole: "Andela Bénin", date: "2018", desc: "Flutter, React Native, publication stores" }
        ],
        langues: [
          { nom: "Français", niveau: "Langue maternelle" },
          { nom: "Anglais", niveau: "Courant (B2)" }
        ],
        mobilite: "Cotonou · Abomey-Calavi",
        teletravail: "Télétravail complet",
        residence: "Cotonou",
        update: "03.05.2026",
        vues: 28
      },
      {
        id: 8,
        avatar: "ND", avatarBg: "#fef3c7",
        metier: "Juriste d'Entreprise",
        secteurLabel: "Juridique · Compliance",
        pays: "Sénégal", ville: "Dakar",
        disponible: true, dispoLabel: "Immédiatement",
        contrats: ["CDI"],
        experience: "6 ans", niveau: "Bac+5",
        secteurs: ["Cabinet juridique", "Finance", "Industrie"],
        experiences: [
          { poste: "Juriste d'Entreprise", entreprise: "Banque Atlantique Sénégal", periode: "03.2019 – présent", desc: ["Rédaction et négociation de contrats commerciaux", "Veille juridique et conformité OHADA"] },
          { poste: "Juriste Junior", entreprise: "Cabinet Baba & Associés", periode: "09.2016 – 02.2019", desc: ["Conseil juridique entreprises", "Due diligence pour opérations M&A"] }
        ],
        competences: ["Droit OHADA", "Droit du travail", "Contrats commerciaux", "Compliance", "Due Diligence"],
        competencesCles: ["Droit des affaires", "Gestion des risques juridiques"],
        formations: [
          { titre: "Master Droit des Affaires et Fiscalité", ecole: "Université Cheikh Anta Diop — Dakar", date: "2014 – 2016", desc: "Droit OHADA, fiscalité, droit des sociétés" },
          { titre: "Licence Droit Privé", ecole: "Université Cheikh Anta Diop", date: "2011 – 2014", desc: "" }
        ],
        langues: [
          { nom: "Français", niveau: "Langue maternelle" },
          { nom: "Anglais", niveau: "Professionnel (C1)" },
          { nom: "Wolof", niveau: "Courant" }
        ],
        mobilite: "Dakar — Mobilité Afrique de l'Ouest",
        teletravail: "Hybride",
        residence: "Dakar",
        update: "09.05.2026",
        vues: 8
      },
      {
        id: 9,
        avatar: "TC", avatarBg: "#f3e8ff",
        metier: "Responsable Logistique",
        secteurLabel: "Logistique · Supply Chain",
        pays: "Côte d'Ivoire", ville: "Abidjan",
        disponible: true, dispoLabel: "Dans 2 semaines",
        contrats: ["CDI", "CDD"],
        experience: "9 ans", niveau: "Bac+4",
        secteurs: ["Import-export", "Distribution", "Industrie"],
        experiences: [
          { poste: "Responsable Logistique Régionale", entreprise: "Nestlé Côte d'Ivoire", periode: "01.2018 – présent", desc: ["Coordination des flux logistiques pour 6 pays UEMOA", "Réduction des coûts logistiques de 18% en 2 ans"] },
          { poste: "Gestionnaire de Stock", entreprise: "CFAO Motors CI", periode: "04.2014 – 12.2017", desc: ["Gestion stocks pièces détachées (15 000 références)", "Déclarations douanières et transit"] }
        ],
        competences: ["Supply chain", "WMS", "Douanes", "Gestion d'entrepôt", "SAP MM", "MS Project"],
        competencesCles: ["Optimisation logistique", "Management d'équipe"],
        formations: [
          { titre: "Master Logistique et Transport International", ecole: "ISTC Abidjan", date: "2012 – 2014", desc: "Supply chain, gestion portuaire, commerce international" },
          { titre: "Licence Gestion des Entreprises", ecole: "Université Houphouët-Boigny", date: "2009 – 2012", desc: "" }
        ],
        langues: [
          { nom: "Français", niveau: "Langue maternelle" },
          { nom: "Anglais", niveau: "Professionnel (B2)" }
        ],
        mobilite: "Abidjan · Zone portuaire · Déplacements régionaux",
        teletravail: "Présentiel",
        residence: "Abidjan",
        update: "13.05.2026",
        vues: 13
      },
      {
        id: 10,
        avatar: "EK", avatarBg: "#d1fae5",
        metier: "Enseignant-Formateur",
        secteurLabel: "Éducation · Formation",
        pays: "Togo", ville: "Lomé",
        disponible: true, dispoLabel: "Immédiatement",
        contrats: ["CDI", "Freelance"],
        experience: "10 ans", niveau: "Bac+5",
        secteurs: ["Éducation", "Formation professionnelle", "ONG"],
        experiences: [
          { poste: "Formateur Senior", entreprise: "Institut Africain de Management — Lomé", periode: "09.2016 – présent", desc: ["Cours de management et entrepreneuriat (Bac+3/+5)", "Tutorat de +200 étudiants"] },
          { poste: "Enseignant de Mathématiques", entreprise: "Lycée Technique de Lomé", periode: "09.2012 – 08.2016", desc: ["Classes de terminale technique A et B"] }
        ],
        competences: ["Pédagogie active", "E-learning", "Moodle", "Conception de curriculum", "Gestion de salle", "Evaluation"],
        competencesCles: ["Ingénierie pédagogique", "Formation professionnelle"],
        formations: [
          { titre: "Master Sciences de l'Éducation", ecole: "Université de Lomé", date: "2010 – 2012", desc: "Pédagogie, didactique, évaluation des apprentissages" },
          { titre: "Licence Mathématiques", ecole: "Université de Lomé", date: "2007 – 2010", desc: "" }
        ],
        langues: [
          { nom: "Français", niveau: "Courant" },
          { nom: "Anglais", niveau: "Intermédiaire (B1)" },
          { nom: "Ewe", niveau: "Langue maternelle" }
        ],
        mobilite: "Lomé · Kpalimé · Atakpamé",
        teletravail: "Hybride",
        residence: "Lomé",
        update: "07.05.2026",
        vues: 7
      },
      {
        id: 11,
        avatar: "PG", avatarBg: "#fce7f3",
        metier: "Analyste Financier",
        secteurLabel: "Finance · Investissement",
        pays: "Cameroun", ville: "Douala",
        disponible: false, dispoLabel: "Actuellement en poste",
        contrats: ["CDI"],
        experience: "7 ans", niveau: "Bac+5",
        secteurs: ["Banque d'investissement", "Private Equity", "Finance"],
        experiences: [
          { poste: "Analyste Financier Senior", entreprise: "Afriland First Bank — Douala", periode: "01.2019 – présent", desc: ["Modélisation financière et valorisation d'entreprises", "Préparation des comités de crédit"] },
          { poste: "Analyste Junior", entreprise: "Cabinet EY Cameroun", periode: "06.2016 – 12.2018", desc: ["Due diligence financières", "Audit des états financiers IFRS"] }
        ],
        competences: ["Modélisation financière", "Excel avancé", "Bloomberg Terminal", "Private Equity", "IFRS", "VBA"],
        competencesCles: ["Analyse financière", "Évaluation d'actifs"],
        formations: [
          { titre: "Master Finance d'Entreprise et Marchés", ecole: "ESSEC Business School Afrique — Douala", date: "2014 – 2016", desc: "Finance de marché, private equity, modélisation" },
          { titre: "Licence Économie et Gestion", ecole: "Université de Douala", date: "2011 – 2014", desc: "" }
        ],
        langues: [
          { nom: "Français", niveau: "Langue maternelle" },
          { nom: "Anglais", niveau: "Courant (C1)" }
        ],
        mobilite: "Douala · Yaoundé",
        teletravail: "Présentiel",
        residence: "Douala",
        update: "06.05.2026",
        vues: 10
      }
    ];

    /* ── Populate page ── */
    (function () {
      const params = new URLSearchParams(window.location.search);
      const id = parseInt(params.get('id'), 10);
      const p  = isNaN(id) ? PROFILS_DATA[0] : (PROFILS_DATA.find(x => x.id === id) || PROFILS_DATA[0]);

      function html(id, content) {
        const el = document.getElementById(id);
        if (el) el.innerHTML = content;
      }
      function text(id, content) {
        const el = document.getElementById(id);
        if (el) el.textContent = content;
      }

      const numStr = String(p.id + 1).padStart(6, '0');

      /* Header */
      text('profNumTitle', 'Profil CV N°' + numStr);
      text('profUpdateLabel', 'Profil mis à jour le ' + p.update);
      document.title = 'Profil CV N°' + numStr + ' — ' + p.metier + ' — Emploi Bouge Bénin';

      /* Contact photo */
      const photoEl = document.getElementById('contactPhoto');
      if (photoEl) photoEl.style.background = p.avatarBg;
      text('contactInitials', p.avatar);
      text('cvFileName', 'cv_profil_' + numStr + '.pdf');

      /* Métiers */
      html('sMetiers', p.competences.slice(0, 3).map(c =>
        `<div class="pr-bullet">${c}</div>`
      ).join(''));

      /* Expérience */
      let expHtml = '';
      if (p.secteurs && p.secteurs.length) {
        expHtml += `<div class="pr-sub-label">Expérience dans les secteurs d'activité suivants :</div>`;
        expHtml += p.secteurs.map(s => `<div class="pr-bullet">${s}</div>`).join('');
      }
      p.experiences.forEach(e => {
        expHtml += `<div class="pr-sub-label" style="margin-top:10px;">${e.poste}</div>`;
        expHtml += `<div class="pr-form-ecole">${e.entreprise} · ${e.periode}</div>`;
        e.desc.forEach(d => { expHtml += `<div class="pr-bullet">${d}</div>`; });
      });
      html('sExperience', expHtml);

      /* Compétences */
      html('sCompetences', `<div class="pr-val">${p.competences.join(', ')}.</div>`);

      /* Formation */
      let formHtml = `<div class="pr-form-niveau"><strong>Niveau d'études :</strong> ${p.niveau}</div>`;
      p.formations.forEach(f => {
        formHtml += `<div class="pr-form-name">${f.titre}</div>`;
        formHtml += `<div class="pr-form-ecole">${f.ecole}</div>`;
        formHtml += `<div class="pr-form-date">${f.date}</div>`;
        if (f.desc) formHtml += `<div class="pr-form-desc">${f.desc}</div>`;
      });
      html('sFormation', formHtml);

      /* Compétences clés */
      html('sCompCles', p.competencesCles && p.competencesCles.length
        ? `<div class="pr-val">${p.competencesCles.join(' · ')}</div>`
        : `<div class="pr-val--muted">non renseigné</div>`);

      /* Langues */
      html('sLangues', p.langues.map(l =>
        `<div class="pr-lang-item">${l.nom} <span class="pr-lang-arrow">›</span> ${l.niveau}</div>`
      ).join(''));

      /* Plus d'informations */
      const dispoColor = p.disponible ? 'pr-val--green' : '';
      html('sPlusInfo', `
        <div class="pr-row"><span class="pr-label">Disponibilité :</span> <span class="${dispoColor} pr-val">${p.dispoLabel}</span></div>
        <div class="pr-row"><span class="pr-label">Mobilité géographique :</span> <span class="pr-val--link pr-val">${p.mobilite}</span></div>
        <div class="pr-row"><span class="pr-label">Lieu de résidence :</span> <span class="pr-val">${p.residence}</span></div>
        <div class="pr-row"><span class="pr-label">Types de contrats acceptés :</span> <span class="pr-val">${p.contrats.join(' · ')}</span></div>
        <div class="pr-row"><span class="pr-label">Télétravail :</span> <span class="pr-val">${p.teletravail}</span></div>
        <div class="pr-row"><span class="pr-label">Dernière mise à jour :</span> <span class="pr-val">${p.update}</span></div>
        <div class="pr-row"><span class="pr-label">Nombre de consultations du CV :</span> <span class="pr-val">${p.vues}</span></div>
      `);
    })();
