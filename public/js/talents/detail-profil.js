/* ── Mobile nav ── */
    const hamburger = document.getElementById('hamburger');
    const mobileMenu = document.getElementById('mobileMenu');
    hamburger.addEventListener('click', () => {
      const open = mobileMenu.classList.toggle('open');
      hamburger.classList.toggle('open', open);
      hamburger.setAttribute('aria-expanded', open);
    });

    /* ── Modal ── */
    function ouvrirModalContact() {
      const modal = document.getElementById('modalContact');
      modal.classList.add('open');
      modal.setAttribute('aria-hidden', 'false');
    }
    function fermerModalContact() {
      const modal = document.getElementById('modalContact');
      modal.classList.remove('open');
      modal.setAttribute('aria-hidden', 'true');
    }
    document.getElementById('modalContact').addEventListener('click', function(e) {
      if (e.target === this) fermerModalContact();
    });
    document.addEventListener('keydown', e => { if (e.key === 'Escape') fermerModalContact(); });

    /* ── Seed data ── */
    const SEED_TALENTS = [
      { id: 0, initiales: 'A. K.', avatarBg: '#d1fae5', competence: 'Cuisine & Restauration',
        niveau: 'Expérimenté', dureeExp: '8 ans', ville: 'Cotonou', dispo: 'Disponible immédiatement',
        temps: 'Temps plein', salaire: '80 000', mobilite: true, badge: 'recommande',
        description: 'Cuisinier professionnel avec 8 ans d\'expérience en restauration africaine et européenne. Spécialiste des buffets d\'entreprise et traiteur événementiel. Maîtrise des cuisines française, africaine et internationale.',
        lieuTravail: 'En entreprise · Traiteur · Domicile', hasPhoto: true, hasVideo: false, update: '10.05.2026', vues: 14,
        nomComplet: 'Adjovi Kossou', tel: '+229 01 97 23 45', email: 'adjovi.kossou@gmail.com', age: '34', quartier: 'Akpakpa' },
      { id: 1, initiales: 'M. D.', avatarBg: '#dbeafe', competence: 'Graphisme & Création',
        niveau: 'Intermédiaire', dureeExp: '3 ans', ville: 'Porto-Novo', dispo: 'Dans 1 mois',
        temps: 'Temps plein', salaire: '120 000', mobilite: false, badge: 'verifie',
        description: 'Designer graphique autodidacte maîtrisant Canva Pro, Photoshop et Illustrator. Création de logos, affiches et flyers pour PME béninoises. Portfolio disponible sur demande.',
        lieuTravail: 'Télétravail · En agence', hasPhoto: true, hasVideo: false, update: '12.05.2026', vues: 9,
        nomComplet: 'Mesmin Dossou', tel: '+229 01 64 88 79', email: 'm.dossou@yahoo.fr', age: '27', quartier: 'Fidjrossè' },
      { id: 2, initiales: 'F. A.', avatarBg: '#fce7f3', competence: 'Coiffure & Beauté',
        niveau: 'Expérimenté', dureeExp: '5 ans', ville: 'Cotonou', dispo: 'Disponible immédiatement',
        temps: 'Les deux', salaire: '60 000', mobilite: true, badge: 'verifie',
        description: 'Coiffeuse spécialisée en tresses africaines, tissages et soins capillaires naturels. Disponible en salon ou à domicile. Formation aux dernières tendances capillaires.',
        lieuTravail: 'Salon de coiffure · Domicile client', hasPhoto: true, hasVideo: true, update: '08.05.2026', vues: 21,
        nomComplet: 'Fatou Ahouandjinou', tel: '+229 01 55 37 12', email: 'fatou.ah@gmail.com', age: '30', quartier: 'Cadjèhoun' },
      { id: 3, initiales: 'J. H.', avatarBg: '#fef9c3', competence: 'Électricité B€timent',
        niveau: 'Intermédiaire', dureeExp: '4 ans', ville: 'Abomey-Calavi', dispo: 'Disponible immédiatement',
        temps: 'Temps plein', salaire: '100 000', mobilite: true, badge: 'competence',
        description: 'Électricien qualifié pour installations résidentielles et commerciales. Mise aux normes, dépannage rapide, habilitation électrique. Expérience sur chantiers de 10 à 500 m².',
        lieuTravail: 'Chantiers · Entreprises · Résidentiel', hasPhoto: true, hasVideo: false, update: '15.05.2026', vues: 17,
        nomComplet: 'Joël Hounkpè', tel: '+229 01 48 72 90', email: 'joel.hk@gmail.com', age: '29', quartier: 'Godomey' },
      { id: 4, initiales: 'S. M.', avatarBg: '#e0f2fe', competence: 'Community Management',
        niveau: 'Intermédiaire', dureeExp: '2 ans', ville: 'Cotonou', dispo: 'Temps partiel',
        temps: 'Temps partiel', salaire: '70 000', mobilite: false, badge: 'verifie',
        description: 'Community manager réseaux sociaux (Facebook, TikTok, WhatsApp Business). Gestion de 8 pages clients avec croissance organique mensuelle. Création de contenus visuels et textes.',
        lieuTravail: 'Télétravail', hasPhoto: false, hasVideo: false, update: '11.05.2026', vues: 12,
        nomComplet: 'Sylvie Médédé', tel: '+229 01 73 56 34', email: 'sylvie.m@gmail.com', age: '26', quartier: 'Cotonou Centre' },
      { id: 5, initiales: 'B. T.', avatarBg: '#fef3c7', competence: 'Mécanique Auto',
        niveau: 'Expérimenté', dureeExp: '10 ans', ville: 'Parakou', dispo: 'Disponible immédiatement',
        temps: 'Temps plein', salaire: '90 000', mobilite: false, badge: 'recommande',
        description: 'Mécanicien automobile polyvalent avec 10 ans d\'expérience. Diagnostics électroniques OBD, moteur, boîte de vitesses, freins. Spécialiste marques Toyota, Renault, Mercedes.',
        lieuTravail: 'Garage · Atelier', hasPhoto: true, hasVideo: false, update: '07.05.2026', vues: 19,
        nomComplet: 'Basile Tossavi', tel: '+229 01 90 14 77', email: 'btossavi@gmail.com', age: '38', quartier: 'Parakou Centre' },
      { id: 6, initiales: 'R. A.', avatarBg: '#ede9fe', competence: 'Couture & Mode',
        niveau: 'Expérimenté', dureeExp: '6 ans', ville: 'Cotonou', dispo: 'Dans 1 mois',
        temps: 'Temps plein', salaire: '75 000', mobilite: false, badge: 'verifie',
        description: 'Couturière créatrice de mode africaine contemporaine. Confection de tenues sur mesure, pagnes, robes de cérémonie et tenues de mariée. Atelier propre avec machines à coudre professionnelles.',
        lieuTravail: 'Atelier · Boutique', hasPhoto: true, hasVideo: true, update: '14.05.2026', vues: 8,
        nomComplet: 'Rose Assou', tel: '+229 01 61 29 83', email: 'rose.assou@gmail.com', age: '33', quartier: 'Avotrou' },
      { id: 7, initiales: 'O. B.', avatarBg: '#d1fae5', competence: 'Plomberie & Sanitaire',
        niveau: 'Intermédiaire', dureeExp: '3 ans', ville: 'Porto-Novo', dispo: 'Disponible immédiatement',
        temps: 'Temps plein', salaire: '85 000', mobilite: true, badge: 'verifie',
        description: 'Plombier qualifié pour installation sanitaire complète, dépannage fuites d\'eau, pose de chauffe-eau et équipements sanitaires. Intervention rapide et garantie sur les travaux.',
        lieuTravail: 'Résidentiel · Chantiers', hasPhoto: false, hasVideo: false, update: '06.05.2026', vues: 6,
        nomComplet: 'Olive Bossou', tel: '+229 01 44 67 25', email: 'o.bossou@gmail.com', age: '31', quartier: 'Porto-Novo Centre' },
      { id: 8, initiales: 'K. L.', avatarBg: '#fee2e2', competence: 'Menuiserie & Bois',
        niveau: 'Expérimenté', dureeExp: '7 ans', ville: 'Abomey-Calavi', dispo: 'Disponible immédiatement',
        temps: 'Temps plein', salaire: '95 000', mobilite: false, badge: 'competence',
        description: 'Menuisier ébéniste spécialisé en fabrication de meubles sur mesure, portes, fenêtres et parquets. Travail soigné avec bois local et importé, respect strict des délais.',
        lieuTravail: 'Atelier · Chantiers', hasPhoto: true, hasVideo: false, update: '09.05.2026', vues: 11,
        nomComplet: 'Kodjo Lègba', tel: '+229 01 82 43 58', email: 'kodjo.l@gmail.com', age: '35', quartier: 'Calavi Centre' },
      { id: 9, initiales: 'Y. D.', avatarBg: '#dcfce7', competence: 'Maçonnerie & BTP',
        niveau: 'Expérimenté', dureeExp: '12 ans', ville: 'Cotonou', dispo: 'Dans 3 mois',
        temps: 'Temps plein', salaire: '110 000', mobilite: true, badge: 'recommande',
        description: 'Maçon chef de chantier avec 12 ans d\'expérience. Gros œuvre et finitions, construction résidentielle, carrelage, enduit et peinture. Gestion d\'équipes de 5 à 10 ouvriers.',
        lieuTravail: 'Chantiers résidentiels · BTP', hasPhoto: true, hasVideo: false, update: '13.05.2026', vues: 16,
        nomComplet: 'Yannick Djabirou', tel: '+229 01 77 38 92', email: 'y.djabirou@gmail.com', age: '42', quartier: 'Zongo' },
      { id: 10, initiales: 'C. N.', avatarBg: '#fce7f3', competence: 'Photographie',
        niveau: 'Intermédiaire', dureeExp: '4 ans', ville: 'Cotonou', dispo: 'Temps partiel',
        temps: 'Temps partiel', salaire: '150 000', mobilite: true, badge: 'verifie',
        description: 'Photographe événementiel et portrait professionnel. Retouche Lightroom et Photoshop. Couverture de mariages, baptêmes, événements corporate et shooting produits.',
        lieuTravail: 'Studio · Événementiel · Déplacement', hasPhoto: true, hasVideo: true, update: '16.05.2026', vues: 23,
        nomComplet: 'Christine Noudéhou', tel: '+229 01 56 84 13', email: 'c.noudehou@gmail.com', age: '28', quartier: 'Cadjèhoun' },
      { id: 11, initiales: 'E. K.', avatarBg: '#fef9c3', competence: 'Jardinage & Paysagisme',
        niveau: 'Intermédiaire', dureeExp: '5 ans', ville: 'Porto-Novo', dispo: 'Disponible immédiatement',
        temps: 'Temps partiel', salaire: '55 000', mobilite: true, badge: 'verifie',
        description: 'Jardinier paysagiste pour création et entretien d\'espaces verts résidentiels et d\'entreprises. Taille, désherbage, arrosage automatique, plantation et aménagement paysager.',
        lieuTravail: 'Résidentiel · Entreprises', hasPhoto: false, hasVideo: false, update: '05.05.2026', vues: 4,
        nomComplet: 'Éric Kagbani', tel: '+229 01 33 75 46', email: 'e.kagbani@gmail.com', age: '31', quartier: 'Fidjrossè' }
    ];

    function escHtml(s) {
      return String(s || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function padId(n) { return String(n).padStart(6, '0'); }

    function badgeHtml(badge) {
      if (badge === 'recommande') return '<span class="tpr-badge tpr-badge--recommande">★ Talent recommandé</span>';
      if (badge === 'competence') return '<span class="tpr-badge tpr-badge--competence">✓ Compétence validée</span>';
      return '<span class="tpr-badge tpr-badge--verifie">✓ Profil vérifié</span>';
    }

    function html(id, content) {
      const el = document.getElementById(id);
      if (el) el.innerHTML = content;
    }
    function text(id, content) {
      const el = document.getElementById(id);
      if (el) el.textContent = content;
    }

    (function () {
      const params = new URLSearchParams(window.location.search);
      const rawId  = params.get('id');

      /* Chercher d'abord dans les talents soumis (localStorage), puis seeds */
      let t = null;
      const submitted = (function() {
        try { return JSON.parse(localStorage.getItem('talents_deposes') || '[]'); } catch(e) { return []; }
      })();

      t = submitted.find(x => String(x.id) === String(rawId));
      if (!t) {
        const numId = parseInt(rawId, 10);
        t = isNaN(numId) ? SEED_TALENTS[0] : (SEED_TALENTS.find(x => x.id === numId) || SEED_TALENTS[0]);
      } else {
        /* Normaliser le talent soumis */
        t = Object.assign({ initiales: 'T', avatarBg: '#d1fae5', lieuTravail: t.lieuTravail || '—', hasPhoto: !!t.photo, hasVideo: !!t.video, update: new Date(t.date || Date.now()).toLocaleDateString('fr-FR'), vues: 0 }, t);
        t.initiales = (t.nomComplet || '').split(' ').filter(Boolean).map(n => n[0].toUpperCase() + '.').join(' ') || 'T';
      }

      const numStr = typeof t.id === 'number' ? padId(t.id + 1) : t.id;

      /* Header */
      text('talentNumTitle', 'Talent N°' + numStr);
      text('talentUpdateLabel', 'Profil mis à jour le ' + (t.update || '—'));
      document.title = 'Talent N°' + numStr + ' — ' + escHtml(t.competence) + ' — Emploi Bouge Bénin';

      /* Contact card */
      const photoEl = document.getElementById('contactPhoto');
      if (photoEl) photoEl.style.background = t.avatarBg || '#d1fae5';
      text('contactInitials', t.initiales || 'T');
      text('contactVille', t.ville || '—');
      html('contactBadge', badgeHtml(t.badge));

      /* Section : Compétence & Niveau */
      html('sCompetence', `
        <div class="tpr-row"><span class="tpr-label">Compétence principale :</span> <span class="tpr-val" style="font-weight:700;color:#0a3d20;">${escHtml(t.competence)}</span></div>
        <div class="tpr-row"><span class="tpr-label">Niveau :</span> <span class="tpr-val">${escHtml(t.niveau)}</span></div>
        <div class="tpr-row"><span class="tpr-label">Durée de pratique :</span> <span class="tpr-val">${escHtml(t.dureeExp)}</span></div>
        <div class="tpr-row"><span class="tpr-label">Lieu de travail préféré :</span> <span class="tpr-val">${escHtml(t.lieuTravail || '—')}</span></div>
      `);

      /* Section : Description */
      html('sDescription', t.description
        ? `<div class="tpr-description">${escHtml(t.description)}</div>`
        : `<div class="tpr-val--muted tpr-val">Aucune description renseignée.</div>`);

      /* Section : Expérience */
      const expBullets = [
        t.dureeExp ? `${escHtml(t.dureeExp)} d\'expérience dans le domaine : <strong>${escHtml(t.competence)}</strong>` : null,
        t.niveau === 'Expérimenté' ? 'Profil confirmé avec une solide pratique terrain' : null,
        t.niveau === 'Intermédiaire' ? 'Bonnes bases pratiques, en progression constante' : null,
        t.niveau === 'Débutant' ? 'Profil motivé, débutant avec potentiel' : null
      ].filter(Boolean);
      html('sExperience', expBullets.map(b => `<div class="tpr-bullet">${b}</div>`).join('') || '<div class="tpr-val--muted tpr-val">Non renseigné.</div>');

      /* Section : Conditions */
      const dispoColor = t.dispo === 'Disponible immédiatement' ? 'tpr-val--green' : '';
      const salaireStr = t.salaire ? escHtml(t.salaire) + ' FCFA / mois' : 'Non renseigné';
      html('sConditions', `
        <div class="tpr-row"><span class="tpr-label">Disponibilité :</span> <span class="${dispoColor} tpr-val">${escHtml(t.dispo)}</span></div>
        <div class="tpr-row"><span class="tpr-label">Type de temps :</span> <span class="tpr-val">${escHtml(t.temps)}</span></div>
        <div class="tpr-row"><span class="tpr-label">Salaire souhaité :</span> <span class="tpr-val">${salaireStr}</span></div>
        <div class="tpr-row"><span class="tpr-label">Mobilité géographique :</span> <span class="tpr-val">${t.mobilite ? '<span class="tpr-val--green">Oui, mobile</span>' : 'Limitée à la ville'}</span></div>
        <div class="tpr-row"><span class="tpr-label">Ville :</span> <span class="tpr-val">${escHtml(t.ville)}</span></div>
        <div class="tpr-row"><span class="tpr-label">Consultations du profil :</span> <span class="tpr-val">${t.vues || 0} vue(s)</span></div>
      `);

      /* Section : Documents */
      const docs = [
        { label: 'Photo de profil', ok: !!t.hasPhoto },
        { label: 'Vidéo de démonstration', ok: !!t.hasVideo },
        { label: 'Pièce d\'identité', ok: false },
        { label: 'Justificatif de compétence', ok: false }
      ];
      html('sDocuments', `
        <div class="tpr-doc-grid">
          ${docs.map(d => `
            <div class="tpr-doc-item${d.ok ? '' : ' tpr-doc-item--manquant'}">
              <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                ${d.ok
                  ? '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'
                  : '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>'}
              </svg>
              ${escHtml(d.label)}
            </div>`).join('')}
        </div>
        <p style="font-family:var(--font-body);font-size:12px;color:#94a3b8;margin-top:10px;">Les documents d'identité sont visibles uniquement après contact avec l'équipe.</p>
      `);

      /* ── Subscription detection ── */
      var sub = null;
      try { sub = JSON.parse(localStorage.getItem('talent_subscription') || 'null'); } catch(e) {}

      if (sub) {
        /* Unmask contact info */
        var nom = t.nomComplet || t.nom || t.initiales;
        var tel = t.tel || t.whatsapp || '—';
        var email = t.email || '—';
        var age  = t.age ? t.age + ' ans' : '—';
        var qrt  = t.quartier || '—';

        function unm(id, val) {
          var el = document.getElementById(id);
          if (!el) return;
          el.textContent = val;
          el.classList.remove('tpr-masked');
        }
        unm('cNom',    nom);
        unm('cTel',    tel);
        unm('cEmail',  email);
        unm('cAge',    age);
        unm('cQuartier', qrt);

        /* Replace footer with download section */
        var footer = document.getElementById('contactFooter');
        if (footer) {
          footer.innerHTML =
            '<div class="tpr-dl-bar">' +
              '<div class="tpr-dl-credits">' +
                '<svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>' +
                'Abonnement <strong>' + escHtml(sub.pack || 'Pro') + '</strong> — ' +
                '<span id="creditsDisplay">' + (sub.remaining !== undefined ? sub.remaining : '—') + ' téléchargement(s) restant(s)</span>' +
              '</div>' +
              '<div class="tpr-dl-btns">' +
                '<button class="tpr-dl-btn tpr-dl-btn--csv" onclick="handleDownload(\'excel\')">' +
                  '<svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>' +
                  'Télécharger Excel' +
                '</button>' +
                '<button class="tpr-dl-btn tpr-dl-btn--pdf" onclick="handleDownload(\'pdf\')">' +
                  '<svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>' +
                  'Télécharger PDF' +
                '</button>' +
              '</div>' +
            '</div>';
        }

        /* Update header CTA */
        var hdrBtn = document.getElementById('headerCtaBtn');
        if (hdrBtn) {
          hdrBtn.removeAttribute('href');
          hdrBtn.setAttribute('onclick', 'handleDownload(\'pdf\')');
          hdrBtn.innerHTML =
            '<svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>' +
            ' Télécharger ce profil';
        }

        /* Store talent ref for download functions */
        window.__dlTalent = t;
        window.__dlSub    = sub;
      }
    })();

    /* ── Download functions ── */
    function handleDownload(format) {
      var t   = window.__dlTalent;
      var sub = window.__dlSub;
      if (!t || !sub) return;

      if (sub.remaining !== undefined && sub.remaining <= 0) {
        alert('Vous n\'avez plus de téléchargements disponibles. Veuillez renouveler votre abonnement.');
        return;
      }

      if (format === 'excel') {
        downloadCSV(t);
      } else {
        downloadPDF(t);
      }

      /* Decrement credits */
      if (sub.remaining !== undefined && sub.remaining > 0) {
        sub.remaining--;
        localStorage.setItem('talent_subscription', JSON.stringify(sub));
        var el = document.getElementById('creditsDisplay');
        if (el) el.textContent = sub.remaining + ' téléchargement(s) restant(s)';
        window.__dlSub = sub;
      }
    }

    function downloadCSV(t) {
      var rows = [
        ['Champ', 'Valeur'],
        ['Nom complet', t.nomComplet || t.nom || t.initiales || ''],
        ['Téléphone / WhatsApp', t.tel || t.whatsapp || ''],
        ['Email', t.email || ''],
        ['©ge', t.age || ''],
        ['Ville', t.ville || ''],
        ['Quartier', t.quartier || ''],
        ['Compétence principale', t.competence || ''],
        ['Niveau', t.niveau || ''],
        ['Expérience', t.dureeExp || ''],
        ['Lieu de travail', t.lieuTravail || ''],
        ['Disponibilité', t.dispo || ''],
        ['Type de temps', t.temps || ''],
        ['Salaire souhaité (FCFA)', t.salaire || ''],
        ['Mobilité', t.mobilite ? 'Oui' : 'Non'],
        ['Description', t.description || ''],
        ['Profil mis à jour', t.update || ''],
      ];
      var csv = rows.map(function(row) {
        return row.map(function(cell) {
          return '"' + String(cell).replace(/"/g, '""') + '"';
        }).join(',');
      }).join('\r\n');
      var blob = new Blob(['﻿' + csv], { type: 'text/csv;charset=utf-8;' });
      var url = URL.createObjectURL(blob);
      var a = document.createElement('a');
      a.href = url;
      a.download = 'profil-talent-' + padId(typeof t.id === 'number' ? t.id + 1 : 1) + '.csv';
      document.body.appendChild(a);
      a.click();
      document.body.removeChild(a);
      URL.revokeObjectURL(url);
    }

    function downloadPDF(t) {
      var nom       = t.nomComplet || t.nom || t.initiales || 'Talent';
      var numStr    = padId(typeof t.id === 'number' ? t.id + 1 : 1);
      var badgeLabel = t.badge === 'recommande' ? 'Talent recommandé' : t.badge === 'competence' ? 'Compétence validée' : 'Profil vérifié';

      var html = '<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8">' +
        '<title>Profil Talent N°' + numStr + '</title>' +
        '<style>' +
          'body{font-family:Arial,sans-serif;padding:40px 48px;color:#1a1a1a;max-width:700px;margin:0 auto;}' +
          'h1{font-size:22px;color:#0a3d20;margin-bottom:4px;}' +
          '.sub{font-size:13px;color:#64748b;margin-bottom:24px;}' +
          '.badge{display:inline-block;background:#dcfce7;color:#15803d;font-size:11px;font-weight:700;' +
                 'padding:3px 10px;border-radius:20px;margin-bottom:24px;}' +
          'table{width:100%;border-collapse:collapse;font-size:13px;}' +
          'tr:nth-child(even){background:#f7faf8;}' +
          'td{padding:8px 12px;border-bottom:1px solid #e2e8f0;vertical-align:top;}' +
          'td:first-child{font-weight:700;color:#0a3d20;width:38%;white-space:nowrap;}' +
          '.desc{margin-top:20px;font-size:13px;color:#475569;line-height:1.6;border-top:1px solid #e2e8f0;padding-top:16px;}' +
          '.footer{margin-top:32px;font-size:11px;color:#94a3b8;border-top:1px solid #e2e8f0;padding-top:12px;}' +
          '@media print{body{padding:20px;}' +
            '@page{margin:20mm;size:A4 portrait;}}' +
        '</style></head><body>' +
        '<h1>Profil Talent N°' + escHtml(numStr) + ' — ' + escHtml(t.competence) + '</h1>' +
        '<div class="sub">Emploi Bouge Bénin · Profilthèque · ' + (t.update || '') + '</div>' +
        '<span class="badge">' + escHtml(badgeLabel) + '</span>' +
        '<table>' +
          '<tr><td>Nom complet</td><td>' + escHtml(nom) + '</td></tr>' +
          '<tr><td>Téléphone / WhatsApp</td><td>' + escHtml(t.tel || t.whatsapp || '—') + '</td></tr>' +
          '<tr><td>Email</td><td>' + escHtml(t.email || '—') + '</td></tr>' +
          '<tr><td>©ge</td><td>' + escHtml(t.age ? t.age + ' ans' : '—') + '</td></tr>' +
          '<tr><td>Ville</td><td>' + escHtml(t.ville || '—') + '</td></tr>' +
          '<tr><td>Quartier</td><td>' + escHtml(t.quartier || '—') + '</td></tr>' +
          '<tr><td>Compétence</td><td>' + escHtml(t.competence || '—') + '</td></tr>' +
          '<tr><td>Niveau</td><td>' + escHtml(t.niveau || '—') + '</td></tr>' +
          '<tr><td>Expérience</td><td>' + escHtml(t.dureeExp || '—') + '</td></tr>' +
          '<tr><td>Disponibilité</td><td>' + escHtml(t.dispo || '—') + '</td></tr>' +
          '<tr><td>Type de temps</td><td>' + escHtml(t.temps || '—') + '</td></tr>' +
          '<tr><td>Salaire souhaité</td><td>' + escHtml(t.salaire ? t.salaire + ' FCFA/mois' : '—') + '</td></tr>' +
          '<tr><td>Mobilité</td><td>' + (t.mobilite ? 'Oui, mobile' : 'Limitée à la ville') + '</td></tr>' +
        '</table>' +
        (t.description ? '<div class="desc"><strong>Description :</strong><br>' + escHtml(t.description) + '</div>' : '') +
        '<div class="footer">Document généré par Emploi Bouge Bénin — emploibougebenin.com</div>' +
        '<script>window.onload=function(){window.print();}<\/script>' +
        '</body></html>';

      var win = window.open('', '_blank');
      if (!win) { alert('Autorisez les popups pour télécharger le PDF.'); return; }
      win.document.write(html);
      win.document.close();
    }
