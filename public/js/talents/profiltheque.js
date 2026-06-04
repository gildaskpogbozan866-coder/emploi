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
    document.querySelectorAll('.pft-filter-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const body = document.getElementById(btn.dataset.target);
        const isOpen = body.classList.toggle('open');
        btn.classList.toggle('open', isOpen);
      });
    });

    /* ── Crédits ── */
    function getCredits() {
      try { return JSON.parse(localStorage.getItem('profils_credits') || '{"total":0,"remaining":0}'); } catch(e) { return {total:0,remaining:0}; }
    }
    function setCredits(c) { localStorage.setItem('profils_credits', JSON.stringify(c)); }

    function decrementCredit(idLabel) {
      const credits = getCredits();
      if (credits.remaining <= 0) {
        showToastPft('Aucun crédit restant — achetez un pack pour continuer.');
        return false;
      }
      credits.remaining = Math.max(0, credits.remaining - 1);
      setCredits(credits);
      updateCreditsBar();
      return true;
    }

    function showToastPft(msg) {
      let t = document.getElementById('pftToast');
      if (!t) {
        t = document.createElement('div');
        t.id = 'pftToast';
        t.style.cssText = 'position:fixed;bottom:24px;right:24px;background:#042C53;color:#fff;font-family:var(--font-body);font-size:13.5px;font-weight:600;padding:12px 20px;border-radius:10px;box-shadow:0 8px 32px rgba(4,44,83,0.3);z-index:9999;opacity:0;transform:translateY(8px);transition:opacity .25s,transform .25s;';
        document.body.appendChild(t);
      }
      t.textContent = msg;
      t.style.opacity = '1'; t.style.transform = 'translateY(0)';
      setTimeout(() => { t.style.opacity = '0'; t.style.transform = 'translateY(8px)'; }, 3200);
    }

    function updateCreditsBar() {
      const credits = getCredits();
      const bar = document.getElementById('pftCreditsBar');
      const cnt = document.getElementById('pftCreditsCount');
      if (!bar) return;
      if (credits.total > 0) {
        bar.style.display = 'flex';
        cnt.textContent = credits.remaining + ' crédit' + (credits.remaining > 1 ? 's' : '');
        cnt.style.color = credits.remaining === 0 ? '#ff8a80' : credits.remaining <= 3 ? '#FFD740' : '#F5C842';
      } else {
        bar.style.display = 'none';
      }
      render();
    }

    /* ── Liste complète des métiers ── */
    const ALL_METIERS = [
      'Cuisine & Restauration','Graphisme & Création','Coiffure & Beauté','Électricité',
      'Community Management','Mécanique Auto','Couture & Mode','Plomberie & Sanitaire',
      'Menuiserie & Bois','Maçonnerie & BTP','Photographie','Jardinage & Paysagisme',
      'Développement Web','Développement Mobile','Informatique & Maintenance','Réseaux & Télécoms',
      'Cybersécurité','Saisie & Secrétariat','Assistance Administrative','Comptabilité',
      'Finance & Gestion','Marketing Digital','Vente & Commerce','Service Client',
      'Téléconseil & Call Center','Livraison & Transport','Conduite Automobile','Chauffeur Poids Lourds',
      'Taxi & VTC','Moto-taxi (Zémidjan)','Logistique & Magasinage','Transit & Douane',
      'Import-Export','Agriculture','Élevage','Pisciculture','Agroalimentaire',
      'Transformation Alimentaire','Boulangerie & P€tisserie','P€tisserie Moderne',
      'Décoration Événementielle','Organisation d\'Événements','Sonorisation & DJ',
      'Animation Événementielle','Vidéographie','Montage Vidéo','Impression & Sérigraphie',
      'Communication & Médias','Journalisme','Rédaction Web','Traduction & Interprétariat',
      'Enseignement & Formation','Soutien Scolaire','Coaching Professionnel','Santé & Soins',
      'Aide-soignant','Garde Malade','Assistance Sociale','Nettoyage & Entretien',
      'Lavage Auto','Pressing & Blanchisserie','Sécurité & Gardiennage','Surveillance CCTV',
      'Immobilier','Gestion Locative','Architecture','Dessin Technique','Topographie',
      'Carrelage','Peinture B€timent','Climatisation & Froid','Soudure','Ferronnerie',
      'Aluminium & Vitrerie','Installation Solaire','Électronique','Réparation Téléphone',
      'Réparation Électroménager','Réparation Informatique','Imprimerie','Artisanat',
      'Sculpture','Broderie','Bijouterie','Savonnerie','Cosmétique Artisanale',
      'Fabrication de Meubles','Tapisserie','Cordonnier','Tannage & Maroquinerie',
      'Musique & Production Audio','Chant & Chorale','Danse','Thé€tre & Animation Culturelle',
      'Tourisme & Hôtellerie','Réception Hôtelière','Guide Touristique','Bar & Cocktails',
      'Gestion de Stock','Achat & Approvisionnement','Ressources Humaines',
      'Juridique & Assistance Légale','ONG & Humanitaire','Collecte de Données',
      'Enquête Terrain','Agent Commercial','Prospection Terrain','Agent Immobilier',
      'Caissier / Caissière','Gestion de Supermarché','Vente en Ligne (E-commerce)',
      'Influence & Création de Contenu','Community Builder','Social Media Manager',
      'Data Entry','Infographie Publicitaire','Motion Design','UX/UI Design',
      'SEO & Référencement','Publicité Facebook & Google Ads','Streaming & Podcast',
      'Installation Caméra de Surveillance','Domotique','Forage & Hydraulique',
      'Maintenance Industrielle','Génie Civil','Énergie Renouvelable','Conducteur d\'Engins',
      'Manutention','Aide-Maçon','Ouvrier Qualifié','Repassage Professionnel',
      'Baby-sitting & Garde d\'Enfants','Cuisine Africaine','Fast-food & Street Food',
      'Transformation de Produits Locaux','Vente de Produits Agricoles','Commerce Général',
      'Vente de Téléphones & Accessoires','Vente de Matériaux de Construction',
      'Vente de Produits Cosmétiques','Vente Pharmaceutique','Assistance Technique',
      'Réparation Moto','Mécanique Moto','Vulcanisation','Carrosserie & Peinture Auto',
      'Électricité Automobile','Diagnostic Automobile','Lavage Industriel','Gestion de Cybercafé',
      'Services Funéraires','Gestion de Caisse Mobile Money','Microfinance & Crédit',
      'Agent Mobile Money','Installation Internet & Fibre Optique','Livraison E-commerce',
      'Agent de Terrain','Contrôle Qualité','Emballage & Conditionnement','Gestion de Production',
      'Opérateur Machine','Facturation & Recouvrement','Archivage & Documentation',
      'Assistance Virtuelle','Télétravail & Freelance','Intelligence Artificielle & Automatisation',
      'Formation Numérique','Création de Sites Web','Gestion de Plateformes Web',
      'Support Technique IT','Maintenance Réseau','Gestion de Bases de Données',
      'Analyse de Données','Dessinateur Graphique','Stylisme & Modélisme','Esthétique & Spa',
      'Manucure & Pédicure','Barber & Coupe Homme','Maquillage Professionnel',
      'Pose de Perruques','Tresses Africaines','Extension de Cils',
      'Décoration Intérieure','Ameublement & Design Intérieur'
    ];

    /* ── Build dynamic métier filter options ── */
    (function() {
      const container = document.getElementById('pftMetierOptions');
      if (!container) return;
      container.innerHTML = ALL_METIERS.map(m => {
        const safe = m.replace(/&/g, '&amp;');
        return `<label class="pft-filter-opt"><input type="checkbox" value="${safe}" data-filter="competence" /><label>${safe}</label></label>`;
      }).join('');
    })();

    /* ── Filter search inputs ── */
    const metierSearchEl = document.getElementById('metierSearch');
    if (metierSearchEl) {
      metierSearchEl.addEventListener('input', function() {
        const q = this.value.toLowerCase().trim();
        document.querySelectorAll('#pftMetierOptions .pft-filter-opt').forEach(opt => {
          opt.style.display = q ? (opt.textContent.toLowerCase().includes(q) ? '' : 'none') : '';
        });
      });
    }
    const villeSearchEl = document.getElementById('villeSearch');
    if (villeSearchEl) {
      villeSearchEl.addEventListener('input', function() {
        const q = this.value.toLowerCase().trim();
        document.querySelectorAll('#f-ville .pft-filter-opt').forEach(opt => {
          opt.style.display = q ? (opt.textContent.toLowerCase().includes(q) ? '' : 'none') : '';
        });
      });
    }

    /* ── Seed data (avec infos complètes pour mode débloqué) ── */
    const SEED_TALENTS = [
      { id: 0, prenom: 'Amidou', nom: 'Kounouté', initiales: 'A. K.', avatarBg: '#d1fae5', premium: true,
        competence: 'Cuisine & Restauration', competenceKey: 'Cuisine & Restauration',
        niveau: 'Expérimenté', dureeExp: '8 ans', ville: 'Cotonou', quartier: 'Akpakpa',
        dispo: 'Disponible immédiatement', temps: 'Temps plein', salaire: '80 000',
        mobilite: true, badge: 'recommande', age: 34,
        tel: '+229 97 11 22 33', email: 'amidou.kounoute@gmail.com',
        competences: ['Cuisine africaine', 'Cuisine européenne', 'Traiteur événementiel', 'Gestion cuisine', 'HACCP'],
        attestations: ['Certificat de formation culinaire EBB 2023', 'Attestation d\'expérience Hôtel Bénin Marina 2020'],
        description: 'Cuisinier professionnel avec 8 ans d\'expérience en restauration africaine et européenne. Spécialiste des buffets d\'entreprise et traiteur événementiel.',
        update: '10.05.2026', vues: 14 },
      { id: 1, prenom: 'Madeleine', nom: 'Dossou', initiales: 'M. D.', avatarBg: '#dbeafe',
        competence: 'Graphisme & Création', competenceKey: 'Graphisme & Création',
        niveau: 'Intermédiaire', dureeExp: '3 ans', ville: 'Porto-Novo', quartier: 'Ouando',
        dispo: 'Dans 1 mois', temps: 'Temps plein', salaire: '120 000',
        mobilite: false, badge: 'verifie', age: 27,
        tel: '+229 96 44 55 66', email: 'madeleine.dossou@yahoo.fr',
        competences: ['Canva Pro', 'Photoshop', 'Illustrator', 'Création de logos', 'Affiches & Flyers'],
        attestations: ['Certificat Canva for Business 2024', 'Attestation formation Photoshop EBB 2022'],
        description: 'Designer graphique autodidacte maîtrisant Canva Pro et Photoshop. Création de logos, affiches et flyers pour PME béninoises.',
        update: '12.05.2026', vues: 9 },
      { id: 2, prenom: 'Fatimata', nom: 'Alabi', initiales: 'F. A.', avatarBg: '#fce7f3',
        competence: 'Coiffure & Beauté', competenceKey: 'Coiffure & Beauté',
        niveau: 'Expérimenté', dureeExp: '5 ans', ville: 'Cotonou', quartier: 'Cadjèhoun',
        dispo: 'Disponible immédiatement', temps: 'Les deux', salaire: '60 000',
        mobilite: true, badge: 'verifie', age: 29,
        tel: '+229 95 77 88 99', email: 'fatimata.alabi@gmail.com',
        competences: ['Tresses africaines', 'Tissage', 'Soins capillaires naturels', 'Coiffure à domicile', 'Lissage'],
        attestations: ['Attestation CAP Coiffure 2021', 'Certificat soins capillaires naturels 2023'],
        description: 'Coiffeuse spécialisée en tresses africaines, tissages et soins capillaires naturels. Disponible en salon ou à domicile.',
        update: '08.05.2026', vues: 21 },
      { id: 3, prenom: 'Justin', nom: 'Houessou', initiales: 'J. H.', avatarBg: '#fef9c3',
        competence: 'Électricité B€timent', competenceKey: 'Électricité',
        niveau: 'Intermédiaire', dureeExp: '4 ans', ville: 'Abomey-Calavi', quartier: 'Godomey',
        dispo: 'Disponible immédiatement', temps: 'Temps plein', salaire: '100 000',
        mobilite: true, badge: 'competence', age: 31,
        tel: '+229 61 33 44 55', email: 'justin.houessou@outlook.com',
        competences: ['Installations résidentielles', 'Installations commerciales', 'Mise aux normes', 'Dépannage', 'Habilitation électrique'],
        attestations: ['Habilitation électrique B1V BR 2022', 'Certificat installation solaire 2024'],
        description: 'Électricien qualifié pour installations résidentielles et commerciales. Mise aux normes, dépannage rapide, habilitation électrique.',
        update: '15.05.2026', vues: 17 },
      { id: 4, prenom: 'Sylvie', nom: 'Mèdégan', initiales: 'S. M.', avatarBg: '#e0f2fe',
        competence: 'Community Management', competenceKey: 'Community Management',
        niveau: 'Intermédiaire', dureeExp: '2 ans', ville: 'Cotonou', quartier: 'Fidjrossè',
        dispo: 'Temps partiel', temps: 'Temps partiel', salaire: '70 000',
        mobilite: false, badge: 'verifie', age: 25,
        tel: '+229 97 00 11 22', email: 'sylvie.medeganbb@gmail.com',
        competences: ['Facebook & Instagram', 'TikTok', 'WhatsApp Business', 'Création de contenu', 'Reporting'],
        attestations: ['Certification Meta Blueprint 2024', 'Attestation Community Management EBB 2023'],
        description: 'Community manager réseaux sociaux (Facebook, TikTok, WhatsApp Business). Gestion de 8 pages clients avec croissance organique mensuelle.',
        update: '11.05.2026', vues: 12 },
      { id: 5, prenom: 'Brice', nom: 'Tomètin', initiales: 'B. T.', avatarBg: '#fef3c7', premium: true,
        competence: 'Mécanique Auto', competenceKey: 'Mécanique Auto',
        niveau: 'Expérimenté', dureeExp: '10 ans', ville: 'Parakou', quartier: 'Zongo',
        dispo: 'Disponible immédiatement', temps: 'Temps plein', salaire: '90 000',
        mobilite: false, badge: 'recommande', age: 38,
        tel: '+229 96 55 66 77', email: 'brice.tometin@gmail.com',
        competences: ['Diagnostic électronique', 'Moteur', 'Boîte de vitesses', 'Marques japonaises', 'Marques européennes'],
        attestations: ['Certificat mécanique automobile CFPA 2017', 'Attestation formation diagnostic OBD2 2022'],
        description: 'Mécanicien automobile polyvalent. Diagnostics électroniques, moteur, boîte de vitesses. Expérience confirmée sur marques japonaises et européennes.',
        update: '07.05.2026', vues: 19 },
      { id: 6, prenom: 'Rachelle', nom: 'Akplo', initiales: 'R. A.', avatarBg: '#ede9fe',
        competence: 'Couture & Mode', competenceKey: 'Couture & Mode',
        niveau: 'Expérimenté', dureeExp: '6 ans', ville: 'Cotonou', quartier: 'Akpakpa',
        dispo: 'Dans 1 mois', temps: 'Temps plein', salaire: '75 000',
        mobilite: false, badge: 'verifie', age: 32,
        tel: '+229 95 88 99 00', email: 'rachelle.akplo@yahoo.fr',
        competences: ['Mode africaine', 'Tenues sur mesure', 'Pagnes', 'Robes de cérémonie', 'Modélisme'],
        attestations: ['Diplôme couture CAP 2020', 'Attestation formation stylisme EBB 2023'],
        description: 'Couturière créatrice de mode africaine contemporaine. Confection de tenues sur mesure, pagnes, robes de cérémonie.',
        update: '14.05.2026', vues: 8 },
      { id: 7, prenom: 'Omer', nom: 'Blahoué', initiales: 'O. B.', avatarBg: '#d1fae5',
        competence: 'Plomberie & Sanitaire', competenceKey: 'Plomberie & Sanitaire',
        niveau: 'Intermédiaire', dureeExp: '3 ans', ville: 'Porto-Novo', quartier: 'Missèbo',
        dispo: 'Disponible immédiatement', temps: 'Temps plein', salaire: '85 000',
        mobilite: true, badge: 'verifie', age: 28,
        tel: '+229 61 22 33 44', email: 'omer.blahoue@gmail.com',
        competences: ['Installation sanitaire', 'Dépannage fuites', 'Pose chauffe-eau', 'Tuyauterie PVC', 'Équipements sanitaires'],
        attestations: ['Certificat plomberie CFP Porto-Novo 2023'],
        description: 'Plombier qualifié pour installation sanitaire, dépannage fuites, pose de chauffe-eau et équipements sanitaires résidentiels.',
        update: '06.05.2026', vues: 6 },
      { id: 8, prenom: 'Kossou', nom: 'Ligan', initiales: 'K. L.', avatarBg: '#fee2e2',
        competence: 'Menuiserie & Bois', competenceKey: 'Menuiserie & Bois',
        niveau: 'Expérimenté', dureeExp: '7 ans', ville: 'Abomey-Calavi', quartier: 'Tankpè',
        dispo: 'Disponible immédiatement', temps: 'Temps plein', salaire: '95 000',
        mobilite: false, badge: 'competence', age: 36,
        tel: '+229 97 44 55 66', email: 'kossou.ligan@gmail.com',
        competences: ['Meubles sur mesure', 'Portes & Fenêtres', 'Parquets', 'Ébénisterie', 'Finitions bois'],
        attestations: ['Certificat menuiserie CFPA 2019', 'Attestation compétence ébénisterie 2022'],
        description: 'Menuisier ébéniste, fabrication meubles sur mesure, portes, fenêtres et parquets. Travail soigné, respect des délais.',
        update: '09.05.2026', vues: 11 },
      { id: 9, prenom: 'Yves', nom: 'Dantoumè', initiales: 'Y. D.', avatarBg: '#dcfce7', premium: true,
        competence: 'Maçonnerie & BTP', competenceKey: 'Maçonnerie & BTP',
        niveau: 'Expérimenté', dureeExp: '12 ans', ville: 'Cotonou', quartier: 'Cadjèhoun',
        dispo: 'Dans 3 mois', temps: 'Temps plein', salaire: '110 000',
        mobilite: true, badge: 'recommande', age: 42,
        tel: '+229 96 66 77 88', email: 'yves.dantoume@outlook.com',
        competences: ['Gros œuvre', 'Carrelage', 'Enduit', 'Chef de chantier', 'Lecture de plans'],
        attestations: ['Certificat chef de chantier BTP 2018', 'Attestation formation sécurité chantier 2023'],
        description: 'Maçon chef de chantier, gros œuvre et finitions. Construction résidentielle, carrelage, enduit. Chef d\'équipes de 5 à 10 ouvriers.',
        update: '13.05.2026', vues: 16 },
      { id: 10, prenom: 'Christelle', nom: 'Nicoue', initiales: 'C. N.', avatarBg: '#fce7f3',
        competence: 'Photographie', competenceKey: 'Photographie',
        niveau: 'Intermédiaire', dureeExp: '4 ans', ville: 'Cotonou', quartier: 'Fidjrossè',
        dispo: 'Temps partiel', temps: 'Temps partiel', salaire: '150 000',
        mobilite: true, badge: 'verifie', age: 26,
        tel: '+229 95 99 00 11', email: 'christelle.nicoue@gmail.com',
        competences: ['Photographie événementielle', 'Portrait', 'Lightroom', 'Photoshop', 'Couverture mariage'],
        attestations: ['Certification photographie numérique EBB 2023', 'Attestation formation Lightroom 2022'],
        description: 'Photographe événementiel et portrait. Retouche Lightroom/Photoshop. Couverture de mariages, baptêmes et événements corporate.',
        update: '16.05.2026', vues: 23 },
      { id: 11, prenom: 'Emile', nom: 'Kingnon', initiales: 'E. K.', avatarBg: '#fef9c3',
        competence: 'Jardinage & Paysagisme', competenceKey: 'Jardinage & Paysagisme',
        niveau: 'Intermédiaire', dureeExp: '5 ans', ville: 'Porto-Novo', quartier: 'Agblangandan',
        dispo: 'Disponible immédiatement', temps: 'Temps partiel', salaire: '55 000',
        mobilite: true, badge: 'verifie', age: 30,
        tel: '+229 61 11 22 33', email: 'emile.kingnon@gmail.com',
        competences: ['Jardins résidentiels', 'Taille arbustes', 'Arrosage automatique', 'Espaces verts', 'Plantes tropicales'],
        attestations: ['Certificat paysagisme CFA Cotonou 2022'],
        description: 'Jardinier paysagiste pour création et entretien de jardins résidentiels et entreprises. Taille, arrosage automatique, espaces verts.',
        update: '05.05.2026', vues: 4 }
    ];

    function escHtml(s) {
      return String(s || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function padId(n) { return String(n).padStart(6, '0'); }

    function badgeHtml(badge) {
      if (badge === 'recommande') return '<span class="pft-badge pft-badge--recommande"><svg width="10" height="10" viewBox="0 0 24 24" fill="currentColor"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg> Talent recommandé</span>';
      if (badge === 'competence') return '<span class="pft-badge pft-badge--competence"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Compétence validée</span>';
      return '<span class="pft-badge pft-badge--verifie"><svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Profil vérifié</span>';
    }

    function dispoClass(dispo) {
      if (dispo === 'Disponible immédiatement') return 'pft-card__val--green';
      if (dispo === 'Dans 3 mois')             return 'pft-card__val--orange';
      return '';
    }

    function premiumBadgeHtml() {
      return '<span style="display:inline-flex;align-items:center;gap:4px;background:linear-gradient(135deg,#d97706,#f59e0b);color:#fff;font-size:10px;font-weight:700;padding:3px 9px;border-radius:20px;letter-spacing:.03em"><svg width="9" height="9" viewBox="0 0 24 24" fill="currentColor"><path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>Premium</span>';
    }

    /* ── Carte VERROUILLÉE (sans pack) ── */
    function buildCardLocked(t) {
      const numStr = typeof t.id === 'number' ? padId(t.id + 1) : t.id;
      return `
        <div class="pft-card${t.premium ? ' pft-card--premium' : ''}" onclick="window.location='detail-profil.html?id=${escHtml(String(t.id))}'">
          <div class="pft-card__inner">
            <div class="pft-card__top">
              <div class="pft-card__id">Talent n°${numStr}</div>
              <div style="display:flex;gap:6px;align-items:center;flex-wrap:wrap">${t.premium ? premiumBadgeHtml() : ''}${badgeHtml(t.badge)}</div>
            </div>
            <div class="pft-card__body">
              <div class="pft-card__avatar" style="background:${escHtml(t.avatarBg || '#dcfce7')}">
                <span class="pft-card__avatar-initials">${escHtml(t.initiales || 'T')}</span>
              </div>
              <div class="pft-card__info">
                <div class="pft-card__competence">${escHtml(t.competence)}</div>
                <div class="pft-card__row">
                  <span class="pft-card__label">Niveau :</span>
                  <span class="pft-card__val">${escHtml(t.niveau)} · ${escHtml(t.dureeExp)} de pratique</span>
                </div>
                <div class="pft-card__row">
                  <span class="pft-card__label">Ville :</span>
                  <span class="pft-card__val">${escHtml(t.ville)}</span>
                </div>
                <div class="pft-card__row">
                  <span class="pft-card__label">Disponibilité :</span>
                  <span class="pft-card__val ${dispoClass(t.dispo)}">${escHtml(t.dispo)}</span>
                </div>
                <div class="pft-card__row">
                  <span class="pft-card__label">Type :</span>
                  <span class="pft-card__val">${escHtml(t.temps)}</span>
                </div>
              </div>
            </div>
            <div class="pft-card__footer">
              <a href="achat-profil.html" class="pft-card__btn" onclick="event.stopPropagation()">
                Débloquer ce profil
                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><rect x="3" y="11" width="18" height="11" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" d="M7 11V7a5 5 0 0110 0v4"/></svg>
              </a>
            </div>
          </div>
        </div>`;
    }

    /* ── Carte DÉBLOQUÉE (avec pack) ── */
    function buildCardUnlocked(t) {
      const numStr = typeof t.id === 'number' ? padId(t.id + 1) : t.id;
      const comps = Array.isArray(t.competences) ? t.competences : [];
      const attests = Array.isArray(t.attestations) ? t.attestations : [];
      const noCredit = getCredits().remaining <= 0;
      const disabledAttr = noCredit ? 'disabled' : '';
      const disabledStyle = noCredit ? 'opacity:.4;cursor:not-allowed;' : 'cursor:pointer;';

      const compsHtml = comps.length
        ? comps.map(c => `<span style="background:#eef3fb;color:#185FA5;font-size:10.5px;font-weight:600;padding:2px 9px;border-radius:20px;">${escHtml(c)}</span>`).join('')
        : '';

      const attestsHtml = attests.length
        ? attests.map(a => `<li style="font-size:12px;color:#374151;padding:3px 0;border-bottom:1px solid #f1f5f9;">${escHtml(a)}</li>`).join('')
        : '<li style="font-size:12px;color:#94a3b8;">Aucune attestation renseignée</li>';

      return `
        <div class="pft-card${t.premium ? ' pft-card--premium' : ''}" style="border:2px solid ${t.premium ? '#fde68a' : '#c6f6d5'};">
          <div class="pft-card__inner">
            <div class="pft-card__top">
              <div class="pft-card__id">Talent n°${numStr}</div>
              <div style="display:flex;gap:6px;align-items:center;flex-wrap:wrap">${t.premium ? premiumBadgeHtml() : ''}${badgeHtml(t.badge)}</div>
            </div>

            <div class="pft-card__body">
              <div class="pft-card__avatar" style="background:linear-gradient(135deg,#042C53,#185FA5);">
                <span class="pft-card__avatar-initials" style="color:#fff;">${escHtml(t.initiales || 'T')}</span>
              </div>
              <div class="pft-card__info">
                <div class="pft-card__competence">${escHtml(t.competence)}</div>
                <div style="font-family:var(--font-body);font-size:13px;font-weight:700;color:#185FA5;margin-bottom:2px;">${escHtml(t.prenom || '')} ${escHtml(t.nom || '')}</div>
                <div style="font-family:var(--font-body);font-size:12px;color:#64748b;margin-bottom:6px;">${escHtml(t.ville)} · <span class="pft-card__val ${dispoClass(t.dispo)}">${escHtml(t.dispo)}</span></div>
                <div class="pft-card__row">
                  <span class="pft-card__label">Exp. :</span>
                  <span class="pft-card__val">${escHtml(t.niveau)} · ${escHtml(t.dureeExp)}</span>
                </div>
                <div class="pft-card__row">
                  <span class="pft-card__label">Dispo :</span>
                  <span class="pft-card__val ${dispoClass(t.dispo)}">${escHtml(t.dispo)}</span>
                </div>
              </div>
            </div>

            <!-- Coordonnées -->
            <div style="background:#f8faff;border:1px solid #dbeafe;border-radius:10px;padding:12px 14px;margin:10px 0;">
              <div style="font-family:var(--font-body);font-size:10px;font-weight:700;color:#378ADD;text-transform:uppercase;letter-spacing:.08em;margin-bottom:8px;">
                <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" style="vertical-align:middle;margin-right:3px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Coordonnées débloquées
              </div>
              <div style="display:grid;grid-template-columns:1fr 1fr;gap:6px;">
                <div><div style="font-size:10px;color:#94a3b8;">Tél / WhatsApp</div><div style="font-size:12.5px;font-weight:600;color:#042C53;">${escHtml(t.tel||'—')}</div></div>
                <div><div style="font-size:10px;color:#94a3b8;">Email</div><div style="font-size:12px;font-weight:600;color:#042C53;word-break:break-all;">${escHtml(t.email||'—')}</div></div>
                <div><div style="font-size:10px;color:#94a3b8;">©ge</div><div style="font-size:12.5px;font-weight:600;color:#042C53;">${t.age ? t.age + ' ans' : '—'}</div></div>
                <div><div style="font-size:10px;color:#94a3b8;">Quartier</div><div style="font-size:12.5px;font-weight:600;color:#042C53;">${escHtml(t.quartier||'—')}</div></div>
              </div>
            </div>

            <!-- Compétences -->
            ${compsHtml ? `<div style="display:flex;flex-wrap:wrap;gap:5px;margin-bottom:10px;">${compsHtml}</div>` : ''}

            <!-- Attestations -->
            <div style="margin-bottom:12px;">
              <div style="font-family:var(--font-body);font-size:10px;font-weight:700;color:#38A169;text-transform:uppercase;letter-spacing:.08em;margin-bottom:6px;">Attestations &amp; Justificatifs</div>
              <ul style="list-style:none;padding:0;margin:0;">${attestsHtml}</ul>
            </div>

            <!-- Bouton téléchargement PDF -->
            <div>
              <button ${disabledAttr} onclick="event.stopPropagation();telechargerPDF(${t.id})"
                style="display:inline-flex;align-items:center;justify-content:center;gap:5px;width:100%;font-family:var(--font-body);font-size:12px;font-weight:700;background:#042C53;color:#fff;border:none;padding:9px 12px;border-radius:8px;${disabledStyle}"
                title="Télécharger le dossier complet PDF (−1 crédit)">
                <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                Télécharger dossier PDF complet
              </button>
            </div>
            ${noCredit ? '<p style="font-family:var(--font-body);font-size:10.5px;color:#c53030;margin-top:7px;text-align:center;"><a href="achat-profil.html" style="color:#c53030;font-weight:700;">Rechargez vos crédits</a> pour télécharger</p>' : '<p style="font-family:var(--font-body);font-size:10px;color:#94a3b8;margin-top:7px;text-align:center;">Chaque téléchargement utilise 1 crédit</p>'}

          </div>
        </div>`;
    }

    /* ── Téléchargements ── */
    function findTalent(id) {
      return SEED_TALENTS.find(t => String(t.id) === String(id)) || null;
    }

    function telechargerPDF(id) {
      const t = findTalent(id);
      if (!t) return;
      if (!decrementCredit(t.prenom + ' ' + t.nom)) return;
      const comps = Array.isArray(t.competences) ? t.competences : [];
      const attests = Array.isArray(t.attestations) ? t.attestations : [];
      const numStr = padId(typeof t.id === 'number' ? t.id + 1 : t.id);
      const html = `<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8">
<title>Profil Talent — ${t.prenom} ${t.nom}</title>
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
.footer{text-align:center;font-size:10px;color:#94a3b8;border-top:1px solid #e2e8f0;padding:16px 36px;margin-top:8px}
@media print{body{-webkit-print-color-adjust:exact;print-color-adjust:exact}}
</style></head><body>
<div class="header">
  <div>
    <div class="header__brand">Emploi Bouge Bénin · Profil Talent</div>
    <div class="header__name">${t.prenom} ${t.nom}</div>
    <div class="header__metier">${t.competence}</div>
    <div class="header__id">Référence : T-${numStr}</div>
  </div>
  <div style="text-align:right;color:rgba(255,255,255,.7);font-size:11px;">Téléchargé le<br>${new Date().toLocaleDateString('fr-FR')}</div>
</div>
<div class="body">
  <div class="section">
    <div class="section__title">Coordonnées</div>
    <div class="grid">
      <div class="field"><span class="field__lbl">Nom complet</span><span class="field__val">${t.prenom} ${t.nom}</span></div>
      <div class="field"><span class="field__lbl">WhatsApp / Tél.</span><span class="field__val">${t.tel||'—'}</span></div>
      <div class="field"><span class="field__lbl">Email</span><span class="field__val">${t.email||'—'}</span></div>
      <div class="field"><span class="field__lbl">©ge</span><span class="field__val">${t.age ? t.age + ' ans' : '—'}</span></div>
      <div class="field"><span class="field__lbl">Ville</span><span class="field__val">${t.ville||'—'}</span></div>
      <div class="field"><span class="field__lbl">Quartier</span><span class="field__val">${t.quartier||'—'}</span></div>
    </div>
  </div>
  <div class="section">
    <div class="section__title">Profil professionnel</div>
    <div class="grid">
      <div class="field"><span class="field__lbl">Métier</span><span class="field__val">${t.competence||'—'}</span></div>
      <div class="field"><span class="field__lbl">Expérience</span><span class="field__val">${t.niveau} · ${t.dureeExp}</span></div>
      <div class="field"><span class="field__lbl">Disponibilité</span><span class="field__val">${t.dispo||'—'}</span></div>
      <div class="field"><span class="field__lbl">Type de temps</span><span class="field__val">${t.temps||'—'}</span></div>
      <div class="field"><span class="field__lbl">Prétentions salariales</span><span class="field__val">${t.salaire ? t.salaire + ' FCFA' : '—'}</span></div>
      <div class="field"><span class="field__lbl">Mobilité</span><span class="field__val">${t.mobilite ? 'Oui' : 'Non'}</span></div>
    </div>
  </div>
  ${comps.length ? `<div class="section"><div class="section__title">Compétences</div><div class="chips">${comps.map(c=>`<span class="chip">${c}</span>`).join('')}</div></div>` : ''}
  ${attests.length ? `<div class="section"><div class="section__title">Attestations &amp; Justificatifs</div>${attests.map(a=>`<div class="attest-item">• ${a}</div>`).join('')}</div>` : ''}
  <div class="section"><div class="section__title">Pièces jointes &amp; Annexes</div>${attests.length ? attests.map(a=>`<div class="attest-item" style="display:flex;align-items:flex-start;gap:6px;padding:5px 0;border-bottom:1px solid #f1f5f9;"><span style="color:#378ADD;flex-shrink:0;font-size:13px;">&#128206;</span><span style="font-size:12px;">${a}</span></div>`).join('') : '<p style="font-size:12px;color:#94a3b8;">Aucun document joint déclaré</p>'}<p style="font-size:11px;color:#94a3b8;margin-top:10px;padding:9px 12px;background:#f8faff;border-left:3px solid #dbeafe;border-radius:0 6px 6px 0;font-style:italic;line-height:1.5;">Les fichiers physiques (certificats scannés, diplômes originaux) sont à demander directement au candidat. Cette section liste les justificatifs déclarés lors de l'inscription.</p></div>
  ${t.description ? `<div class="section"><div class="section__title">Description</div><p style="font-size:13px;color:#374151;line-height:1.7;">${t.description}</p></div>` : ''}
</div>
<div class="footer">Emploi Bouge Bénin · emploibougebenin.com · +229 01 51 92 98 56 · Cotonou, Bénin<br>Ce profil est confidentiel — usage interne uniquement</div>
</body></html>`;
      const win = window.open('', '_blank');
      win.document.write(html);
      win.document.close();
      setTimeout(() => { win.focus(); win.print(); }, 600);
      showToastPft('Dossier PDF généré pour ' + t.prenom + ' ' + t.nom);
    }

    /* ── Active filters ── */
    const filters = { competence: new Set(), niveau: new Set(), dispo: new Set(), temps: new Set(), ville: new Set() };

    document.querySelector('.pft-sidebar').addEventListener('change', function(e) {
      const cb = e.target;
      if (cb.type === 'checkbox' && cb.dataset.filter) {
        const key = cb.dataset.filter;
        if (filters[key] !== undefined) {
          if (cb.checked) filters[key].add(cb.value);
          else            filters[key].delete(cb.value);
        }
        render();
      }
    });

    document.getElementById('pftSearch').addEventListener('input', render);

    function getUserTalents() {
      try {
        return JSON.parse(localStorage.getItem('talents_deposes') || '[]')
          .filter(t => t.statut === 'verifie')
          .map(t => ({
            id: t.id,
            prenom: (t.nomComplet || '').split(' ')[0] || '',
            nom: (t.nomComplet || '').split(' ').slice(1).join(' ') || '',
            initiales: (t.nomComplet || '').split(' ').filter(Boolean).map(n => n[0].toUpperCase() + '.').join(' '),
            avatarBg: '#d1fae5',
            competence: t.competence, competenceKey: t.competence,
            niveau: t.niveau, dureeExp: t.dureeExp || '—',
            ville: t.ville, quartier: t.quartier || '—',
            dispo: t.dispo, temps: t.temps, salaire: t.salaire,
            mobilite: !!t.mobilite, badge: t.badge || 'verifie',
            age: t.age || null, tel: t.tel || '', email: t.email || '',
            competences: Array.isArray(t.competences) ? t.competences : [t.competence],
            attestations: t.attestations || [],
            description: t.description || '',
            update: new Date(t.date).toLocaleDateString('fr-FR'), vues: 0
          }));
      } catch(e) { return []; }
    }

    function render() {
      const credits = getCredits();
      const hasCredits = credits.total > 0;
      const buildCard = hasCredits ? buildCardUnlocked : buildCardLocked;

      const q = document.getElementById('pftSearch').value.toLowerCase().trim();
      const ALL = [...getUserTalents(), ...SEED_TALENTS];

      const result = ALL.filter(t => {
        if (filters.competence.size > 0 && !filters.competence.has(t.competenceKey)) return false;
        if (filters.niveau.size    > 0 && !filters.niveau.has(t.niveau))             return false;
        if (filters.dispo.size     > 0 && !filters.dispo.has(t.dispo))               return false;
        if (filters.temps.size     > 0 && !filters.temps.has(t.temps))               return false;
        if (filters.ville.size     > 0 && !filters.ville.has(t.ville))               return false;
        if (q) {
          const hay = [t.competence, t.niveau, t.ville, t.dispo, t.description].join(' ').toLowerCase();
          if (!hay.includes(q)) return false;
        }
        return true;
      });

      /* Tri : Premium en tête */
      result.sort((a, b) => (b.premium ? 1 : 0) - (a.premium ? 1 : 0));

      const list  = document.getElementById('pftList');
      const title = document.getElementById('pftCountTitle');
      const n = result.length;
      title.textContent = n + ' Talent' + (n > 1 ? 's' : '') + ' trouvé' + (n > 1 ? 's' : '');

      if (n === 0) {
        list.innerHTML = '<div class="pft-empty"><div class="pft-empty__title">Aucun talent ne correspond</div><div class="pft-empty__sub">Modifiez vos filtres ou votre recherche.</div></div>';
      } else {
        list.innerHTML = result.map(buildCard).join('');
      }
    }

    updateCreditsBar();
