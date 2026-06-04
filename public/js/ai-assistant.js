/**
 * AIAssistant — Aide intelligente à la saisie des formulaires
 * Module auto-contenu: styles injectés, aucune dépendance externe.
 * Usage: charger le script, puis ajouter data-ai="bio|competences|description|missions|reformuler"
 * sur les textarea/input souhaités, ou appeler AIAssistant.init() manuellement.
 */
window.AIAssistant = (function () {
  'use strict';

  /* ══════════════════════════════════════════════════════════
     BASE DE DONNÉES DE CONTENU (templates personnalisables)
  ══════════════════════════════════════════════════════════ */

  const COMPETENCES_PAR_SECTEUR = {
    informatique: ['JavaScript / TypeScript', 'React.js / Vue.js', 'Node.js / Express', 'Python', 'PHP / Laravel', 'Flutter / Dart', 'SQL / PostgreSQL', 'MongoDB', 'Git & GitHub', 'API RESTful', 'Docker / DevOps', 'Firebase', 'Linux / Bash', 'Méthodes Agile / Scrum', 'Architecture logicielle'],
    marketing: ['Community Management', 'Google Ads / Meta Ads', 'SEO / SEA', 'Email marketing', 'Rédaction web', 'Canva / Adobe Creative', 'Google Analytics', 'CRM (Hubspot, Salesforce)', 'Gestion de campagnes', 'Stratégie digitale', 'Branding', 'Relations presse', 'Analyse de KPIs', 'Gestion de contenu', 'Wordpress / CMS'],
    finance: ['SYSCOHADA / OHADA', 'Sage Comptabilité', 'Excel avancé', 'Fiscalité (Bénin / UEMOA)', 'Audit interne', 'Contrôle de gestion', 'Trésorerie & cash-flow', 'Analyse financière', 'Élaboration de budgets', 'Rapports financiers', 'Comptabilité générale', 'Comptabilité analytique', 'Gestion des immobilisations', 'Clôtures comptables', 'Liasse fiscale'],
    rh: ['Recrutement & onboarding', 'Gestion de la paie', 'Droit du travail OHADA', 'GPEC / GEPP', 'Formation professionnelle', 'Évaluation des performances', 'Communication interne', 'Gestion des conflits', 'Administration du personnel', 'Logiciel RH (SIRH)', 'Marque employeur', 'Relations sociales', 'Politique salariale', 'Plan de formation', 'Entretiens annuels'],
    commerce: ['Prospection commerciale', 'Négociation & closing', 'Gestion portefeuille clients', 'CRM commercial', 'Vente B2B / B2C', 'Reporting commercial', 'Analyse marché', 'Fidélisation clients', 'Plans d\'action commerciaux', 'Présentation & pitch', 'E-commerce', 'Distribution & réseau', 'Force de vente terrain', 'Traitement des objections', 'Suivi KPIs commerciaux'],
    logistique: ['Gestion de la chaîne d\'approvisionnement', 'Logistique transport', 'Gestion des stocks (WMS)', 'ERP (SAP, Sage)', 'Import / Export & douane', 'Planification des approvisionnements', 'Optimisation des coûts logistiques', 'Gestion des fournisseurs', 'Suivi des livraisons', 'Inventaires & traçabilité', 'Lean management', 'Supply chain', 'Négociation achat', 'Gestion entrepôt', 'Réglementation douanière'],
    design: ['Figma (maîtrise)', 'Adobe XD / Illustrator', 'Photoshop / InDesign', 'Design system', 'Prototypage interactif', 'Tests utilisateurs (UX Research)', 'Responsive design', 'Notions HTML/CSS', 'Motion design (After Effects)', 'Création de logos & identités visuelles', 'Maquettage / wireframing', 'Accessibilité web (WCAG)', 'Design print', 'Charte graphique', 'Vidéo & montage'],
    juridique: ['Droit des affaires OHADA', 'Droit du travail', 'Droit des contrats', 'Contentieux commercial', 'Conformité & compliance', 'Rédaction d\'actes juridiques', 'Conseil juridique', 'Droit des sociétés', 'Propriété intellectuelle', 'Procédures judiciaires', 'Veille juridique', 'Arbitrage & médiation', 'Droit fiscal', 'Due diligence', 'Gestion des litiges'],
    administration: ['Pack Office (Word, Excel, PowerPoint)', 'Rédaction professionnelle', 'Gestion d\'agenda & planification', 'Accueil & communication', 'Gestion administrative', 'Archivage & classement', 'Organisation de réunions & événements', 'Rédaction de comptes rendus', 'Gestion du courrier', 'Outils collaboratifs (Google Workspace)', 'Discrétion & confidentialité', 'Traitement des appels', 'Suivi de dossiers', 'Polyvalence administrative', 'Saisie & mise en forme de documents'],
    sante: ['Soins infirmiers / médicaux', 'Protocoles de soins', 'Pharmacologie de base', 'Gestion des urgences', 'Dossiers patients électroniques', 'Prévention & éducation à la santé', 'Hygiène hospitalière', 'Premiers secours (BLS/ACLS)', 'Relations patients & familles', 'Travail en équipe pluridisciplinaire', 'Gestion des médicaments', 'Stérilisation & asepsie', 'Suivi des traitements', 'Épidémiologie de base', 'Santé communautaire'],
    education: ['Pédagogie active', 'Conception de programmes', 'Animation de formations', 'Évaluation des acquis', 'Gestion de classe', 'Outils numériques éducatifs', 'Accompagnement des apprenants', 'Rédaction de supports de cours', 'Suivi pédagogique', 'Relations parents-enseignants', 'Différenciation pédagogique', 'E-learning & blended learning', 'Langues d\'enseignement', 'Tutorat & mentorat', 'Recherche pédagogique'],
    energie: ['Énergie solaire / photovoltaïque', 'AutoCAD Électrique', 'Normes IEC / NFPA', 'Supervision de chantier', 'Réseaux électriques BT/MT/HT', 'Dimensionnement de systèmes solaires', 'Maintenance préventive & curative', 'Études techniques & devis', 'Gestion de projet énergie', 'Smart grid & stockage', 'Formation terrain', 'HSE (Hygiène, Sécurité, Environnement)', 'SCADA / Automation', 'Énergies renouvelables', 'Audit énergétique'],
    autre: ['Organisation & rigueur', 'Communication professionnelle', 'Travail en équipe', 'Autonomie & prise d\'initiative', 'Gestion de projet', 'Résolution de problèmes', 'Adaptabilité', 'Maîtrise des outils bureautiques', 'Sens du service', 'Esprit analytique']
  };

  const QUALITES_UNIVERSELLES = [
    'Rigueur et sens du détail', 'Autonomie et prise d\'initiative', 'Excellent sens de la communication',
    'Capacité d\'adaptation', 'Esprit d\'équipe et collaboration', 'Résistance au stress et aux délais serrés',
    'Orienté résultats et performance', 'Créativité et sens de l\'innovation', 'Discrétion et sens des responsabilités',
    'Force de proposition', 'Sens de l\'organisation', 'Leadership naturel', 'Empathie et intelligence émotionnelle',
    'Curiosité intellectuelle', 'Persévérance et détermination'
  ];

  const MISSIONS_PAR_SECTEUR = {
    informatique: [
      'Développer et maintenir des applications web performantes et responsive',
      'Concevoir et implémenter des API RESTful sécurisées',
      'Participer à l\'architecture technique des projets et aux revues de code',
      'Optimiser les performances des bases de données et des requêtes',
      'Rédiger la documentation technique et les spécifications fonctionnelles',
      'Collaborer avec les équipes produit et design pour livrer des fonctionnalités',
      'Assurer la veille technologique et proposer des améliorations continues',
      'Identifier et corriger les bugs, garantir la qualité du code (tests unitaires)'
    ],
    marketing: [
      'Élaborer et piloter la stratégie de communication digitale multicanale',
      'Créer et publier des contenus engageants sur les réseaux sociaux',
      'Gérer et optimiser les campagnes publicitaires (Google Ads, Meta Ads)',
      'Analyser les performances marketing et produire des rapports KPIs',
      'Développer la notoriété de la marque et fidéliser la communauté',
      'Collaborer avec les équipes commerciales pour générer des leads qualifiés',
      'Réaliser des études de marché et analyses de la concurrence',
      'Coordonner les actions de communication print et digitale'
    ],
    finance: [
      'Assurer la tenue de la comptabilité générale et analytique',
      'Préparer et contrôler les états financiers mensuels et annuels',
      'Gérer les déclarations fiscales et sociales dans les délais impartis',
      'Superviser la trésorerie et les flux de paiements',
      'Participer aux audits internes et aux clôtures comptables',
      'Analyser les écarts budgétaires et proposer des actions correctives',
      'Assurer la conformité avec les normes SYSCOHADA et la réglementation locale',
      'Préparer les dossiers de financement et les états de synthèse'
    ],
    rh: [
      'Piloter les processus de recrutement de A à Z (annonces, tri, entretiens, intégration)',
      'Gérer l\'administration du personnel et les dossiers salariés',
      'Élaborer et suivre le plan de formation annuel',
      'Conduire les entretiens annuels d\'évaluation et de développement',
      'Assurer la gestion de la paie et des déclarations sociales',
      'Veiller au respect du droit du travail et de la réglementation OHADA',
      'Développer la marque employeur et les politiques d\'engagement',
      'Gérer les relations sociales et prévenir les conflits'
    ],
    commerce: [
      'Développer et fidéliser un portefeuille clients B2B/B2C',
      'Prospecter de nouveaux marchés et identifier des opportunités commerciales',
      'Négocier les contrats et finaliser les ventes dans le respect des objectifs',
      'Suivre les indicateurs de performance (CA, taux de conversion, marge)',
      'Réaliser des présentations commerciales et démonstrations produits',
      'Collaborer avec les équipes marketing pour aligner les campagnes',
      'Gérer les réclamations clients et assurer leur satisfaction',
      'Rédiger des rapports d\'activité commerciale hebdomadaires'
    ],
    logistique: [
      'Coordonner l\'ensemble de la chaîne d\'approvisionnement',
      'Gérer les stocks et optimiser les niveaux d\'inventaire',
      'Négocier avec les fournisseurs et prestataires logistiques',
      'Planifier et superviser les flux de transport et de livraison',
      'Assurer la conformité des opérations douanières et réglementaires',
      'Optimiser les coûts logistiques et identifier des économies potentielles',
      'Mettre en place et suivre les indicateurs de performance supply chain',
      'Former et encadrer les équipes opérationnelles'
    ],
    autre: [
      'Assurer la bonne réalisation des missions confiées dans les délais',
      'Collaborer efficacement avec les équipes internes',
      'Produire des livrables de qualité et veiller à leur conformité',
      'Participer aux réunions de suivi et contribuer aux décisions',
      'Gérer les priorités et s\'adapter aux évolutions des besoins',
      'Proposer des axes d\'amélioration continue'
    ]
  };

  const BIO_TEMPLATES = [
    '{metier} passionné(e) avec {exp} d\'expérience dans le secteur {secteur}, je suis reconnu(e) pour {qualite1}. Mon parcours m\'a permis de développer une expertise solide en {comp1} et {comp2}. Rigoureux(se) et orienté(e) résultats, je cherche à apporter de la valeur à une équipe dynamique et à m\'épanouir dans un environnement stimulant.',
    'Fort(e) d\'une expérience significative en {secteur}, j\'ai évolué en tant que {metier} au sein de structures variées, ce qui m\'a permis de maîtriser {comp1} ainsi que {comp2}. Je suis connu(e) pour {qualite1} et ma capacité à {qualite2}. Je recherche aujourd\'hui un nouveau défi professionnel qui me permettra de contribuer pleinement à votre croissance.',
    'Professionnel(le) {metier} avec une solide maîtrise de {comp1} et {comp2}, j\'évolue depuis {exp} dans le domaine {secteur}. {qualite1} et {qualite2} sont les valeurs qui guident mon approche au quotidien. Orienté(e) vers la collaboration et la performance, je suis prêt(e) à m\'engager pleinement dans de nouveaux défis.',
    'Diplômé(e) en {secteur} et fort(e) de {exp} d\'expérience terrain, je me suis spécialisé(e) en {metier}. Mon profil allie maîtrise technique de {comp1} et {comp2} à des qualités humaines telles que {qualite1}. Motivé(e) par les défis complexes et l\'impact concret, je souhaite rejoindre une organisation où l\'excellence et l\'innovation sont des valeurs centrales.'
  ];

  const DESCRIPTION_OFFRE_TEMPLATES = [
    'Dans le cadre de son développement, {entreprise} recherche un(e) {titre} pour renforcer ses équipes.\n\nVous serez en charge de missions clés au sein de notre structure, notamment :\n• Piloter les projets liés au poste de {titre}\n• Collaborer étroitement avec les équipes internes\n• Contribuer activement aux objectifs de performance de la structure\n• Assurer un reporting régulier à la hiérarchie\n\nProfil recherché :\n• Diplôme(s) requis dans le domaine concerné\n• Expérience significative dans un poste similaire\n• Maîtrise des outils et techniques du métier\n• Excellent sens de l\'organisation et des responsabilités\n• Capacité à travailler en équipe et sous pression\n\nNous offrons un environnement de travail stimulant, une rémunération attractive et de réelles perspectives d\'évolution.',
    '{entreprise} est à la recherche d\'un(e) {titre} talentueux(se) et motivé(e) pour rejoindre notre équipe en pleine croissance.\n\nMissions principales :\n• Prendre en charge les responsabilités opérationnelles du poste\n• Participer au développement des activités de l\'entreprise\n• Mettre en place des processus d\'amélioration continue\n• Représenter l\'entreprise avec professionnalisme\n\nVotre profil :\n• Minimum {exp} d\'expérience dans un poste similaire\n• Solides compétences dans les domaines clés du poste\n• Autonomie, rigueur et sens des priorités\n• Très bonne maîtrise du français, l\'anglais est un plus\n\nSi vous êtes passionné(e) par votre métier et souhaitez contribuer à un projet ambitieux, envoyez-nous votre candidature.',
    'Nous recrutons un(e) {titre} pour accompagner la croissance de {entreprise}.\n\nContexte :\n{entreprise} est une entreprise dynamique dont l\'activité est en forte expansion. Dans ce cadre, nous cherchons à renforcer nos équipes avec un(e) professionnel(le) confirmé(e).\n\nVos responsabilités :\n• Assurer les missions opérationnelles liées au poste de {titre}\n• Développer et maintenir des relations de qualité avec les parties prenantes\n• Proposer et mettre en œuvre des solutions innovantes\n• Garantir la qualité et la conformité des livrables\n\nCe que nous attendons :\n• Une formation pertinente dans le domaine\n• Une expérience démontrée et des résultats mesurables\n• Une personnalité dynamique, proactive et orientée client\n\nRejoignez-nous et participez à notre aventure !'
  ];

  /* ══════════════════════════════════════════════════════════
     STYLES INJECTÉS
  ══════════════════════════════════════════════════════════ */
  function injectStyles() {
    if (document.getElementById('ai-assistant-styles')) return;
    const style = document.createElement('style');
    style.id = 'ai-assistant-styles';
    style.textContent = `
      .ai-btn {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 5px 12px; border-radius: 20px; border: none; cursor: pointer;
        font-size: 12px; font-weight: 600; font-family: 'Jost', sans-serif;
        background: linear-gradient(135deg, #7c3aed, #a855f7);
        color: #fff; box-shadow: 0 2px 8px rgba(124,58,237,.3);
        transition: all .2s; margin-top: 6px; vertical-align: middle;
      }
      .ai-btn:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(124,58,237,.45); }
      .ai-btn svg { width: 13px; height: 13px; }
      .ai-btn-row { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-top: 6px; }

      /* ── Overlay ── */
      .ai-overlay {
        position: fixed; inset: 0; z-index: 9998;
        background: rgba(4,44,83,.45); backdrop-filter: blur(4px);
        display: flex; align-items: center; justify-content: center;
        padding: 16px; opacity: 0; pointer-events: none;
        transition: opacity .25s;
      }
      .ai-overlay.active { opacity: 1; pointer-events: all; }

      /* ── Panel ── */
      .ai-panel {
        background: #fff; border-radius: 18px; width: 100%; max-width: 620px;
        max-height: 88vh; overflow-y: auto; box-shadow: 0 24px 64px rgba(4,44,83,.22);
        transform: scale(.95) translateY(20px); transition: transform .28s cubic-bezier(.34,1.56,.64,1);
        padding: 28px 28px 20px;
        scrollbar-width: thin; scrollbar-color: #e2e8f0 transparent;
      }
      .ai-overlay.active .ai-panel { transform: scale(1) translateY(0); }

      .ai-panel__header {
        display: flex; align-items: center; justify-content: space-between;
        margin-bottom: 18px; gap: 12px;
      }
      .ai-panel__title {
        font-size: 17px; font-weight: 700; color: #042C53;
        display: flex; align-items: center; gap: 8px;
      }
      .ai-panel__title .ai-badge {
        background: linear-gradient(135deg, #7c3aed, #a855f7);
        color: #fff; font-size: 10px; font-weight: 700; padding: 2px 8px;
        border-radius: 20px; text-transform: uppercase; letter-spacing: .5px;
      }
      .ai-panel__close {
        width: 32px; height: 32px; border-radius: 50%; border: none; cursor: pointer;
        background: #f1f5f9; color: #64748b; font-size: 16px; display: flex;
        align-items: center; justify-content: center; flex-shrink: 0;
        transition: background .15s;
      }
      .ai-panel__close:hover { background: #e2e8f0; }

      .ai-panel__context {
        background: #f8faff; border: 1px solid #dde9fb; border-radius: 10px;
        padding: 12px 14px; margin-bottom: 18px; font-size: 13px; color: #64748b;
        line-height: 1.5;
      }
      .ai-panel__context strong { color: #185FA5; }

      .ai-panel__tabs {
        display: flex; gap: 6px; flex-wrap: wrap; margin-bottom: 16px;
      }
      .ai-tab {
        padding: 6px 14px; border-radius: 20px; border: 1.5px solid #e2e8f0;
        background: #f8faff; color: #64748b; font-size: 12.5px; font-weight: 600;
        cursor: pointer; transition: all .15s;
      }
      .ai-tab.active, .ai-tab:hover {
        background: linear-gradient(135deg, #7c3aed, #a855f7);
        color: #fff; border-color: transparent;
      }

      .ai-suggestions { display: flex; flex-direction: column; gap: 12px; }

      .ai-suggestion {
        border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 14px 16px;
        cursor: pointer; transition: all .18s; background: #fafcff;
      }
      .ai-suggestion:hover { border-color: #a855f7; box-shadow: 0 0 0 3px rgba(168,85,247,.1); }
      .ai-suggestion.selected { border-color: #7c3aed; background: #faf5ff; box-shadow: 0 0 0 3px rgba(124,58,237,.12); }
      .ai-suggestion__text { font-size: 13.5px; color: #334155; line-height: 1.6; white-space: pre-wrap; }
      .ai-suggestion__footer { display: flex; align-items: center; justify-content: space-between; margin-top: 10px; }
      .ai-suggestion__use {
        padding: 5px 14px; border-radius: 20px; border: none; cursor: pointer;
        background: #7c3aed; color: #fff; font-size: 12px; font-weight: 600;
        transition: background .15s;
      }
      .ai-suggestion__use:hover { background: #6d28d9; }
      .ai-suggestion__label { font-size: 11px; color: #94a3b8; }

      .ai-chips-section { margin-top: 4px; }
      .ai-chips-section__title { font-size: 13px; font-weight: 700; color: #042C53; margin-bottom: 10px; }
      .ai-chips { display: flex; flex-wrap: wrap; gap: 8px; }
      .ai-chip {
        padding: 6px 14px; border-radius: 20px;
        border: 1.5px solid #e2e8f0; background: #f8faff;
        font-size: 12.5px; color: #334155; cursor: pointer; transition: all .15s;
      }
      .ai-chip:hover { border-color: #7c3aed; background: #faf5ff; color: #7c3aed; }
      .ai-chip.selected { background: #7c3aed; color: #fff; border-color: #7c3aed; }
      .ai-chips-insert {
        margin-top: 14px; padding: 8px 18px; border-radius: 20px;
        background: linear-gradient(135deg, #7c3aed, #a855f7);
        color: #fff; border: none; cursor: pointer; font-size: 13px; font-weight: 600;
        transition: opacity .15s;
      }
      .ai-chips-insert:hover { opacity: .85; }

      .ai-loading {
        text-align: center; padding: 28px;
        color: #7c3aed; font-size: 14px; font-weight: 600;
      }
      .ai-loading__dots { animation: aiDots 1.2s infinite; }
      @keyframes aiDots {
        0%,100% { opacity: .3; } 50% { opacity: 1; }
      }
      @keyframes aiSpin {
        from { transform: rotate(0deg); } to { transform: rotate(360deg); }
      }
      .ai-spinner { animation: aiSpin .8s linear infinite; display: inline-block; }

      @media (max-width: 600px) {
        .ai-panel { padding: 20px 16px 16px; border-radius: 16px; }
        .ai-panel__title { font-size: 15px; }
      }
    `;
    document.head.appendChild(style);
  }

  /* ══════════════════════════════════════════════════════════
     LECTURE DU CONTEXTE FORMULAIRE
  ══════════════════════════════════════════════════════════ */
  function readContext() {
    const ctx = {};

    // Session utilisateur
    try {
      const cu = JSON.parse(localStorage.getItem('current_user') || 'null');
      if (cu) { ctx.nom = cu.nom; ctx.prenom = cu.prenom; ctx.email = cu.email; ctx.role = cu.role; ctx.plan = cu.plan; }
    } catch(e) {}

    // Métier (plusieurs sources possibles)
    ctx.metier = _readField(['fMetier','metierInput','fPoste','fTitre','titre','fRole']) ||
                 _readPills('#metierTagsList') ||
                 _readPills('#posteTagsList') || '';

    // Secteur / Domaine
    ctx.secteur = _readField(['fSecteur','fDomaine','secteurSelect','fDomain']) ||
                  _readSelect(['fSecteur','fDomaine','secteurSelect']) || '';

    // Expérience
    ctx.experience = _readField(['fExp','fExperience','niveauExp']) ||
                     _readSelect(['fExp','niveauExp','fExperience']) || '';

    // Titre de l'offre (pour formulaire recruteur)
    ctx.titre = _readField(['fTitre','titre','fPoste']) || ctx.metier || '';

    // Entreprise (pour formulaire recruteur)
    ctx.entreprise = _readField(['fEntreprise','entreprise','fCompany']) || '';

    // Niveau études
    ctx.etudes = _readSelect(['niveauEtudes','fEtudes','fNiveauEtudes']) || '';

    // Détecter le secteur depuis le métier si non renseigné
    if (!ctx.secteur && ctx.metier) {
      ctx.secteur = detectSecteur(ctx.metier);
    }

    return ctx;
  }

  function _readField(ids) {
    for (const id of ids) {
      const el = document.getElementById(id);
      if (el && el.value && el.value.trim()) return el.value.trim();
    }
    return '';
  }

  function _readSelect(ids) {
    for (const id of ids) {
      const el = document.getElementById(id);
      if (el && el.value && el.selectedIndex > 0) {
        return el.options[el.selectedIndex].text.trim();
      }
    }
    return '';
  }

  function _readPills(selector) {
    const pills = document.querySelectorAll(selector + ' .tag-pill');
    if (!pills.length) return '';
    return Array.from(pills).map(p => p.textContent.replace('✕','').trim()).join(', ');
  }

  function detectSecteur(metier) {
    const m = metier.toLowerCase();
    if (/dev|code|program|logiciel|web|mobile|flutter|react|data|cyber|réseau|systèm/i.test(m)) return 'informatique';
    if (/market|communit|digital|seo|pub|communic|relation|presse|content/i.test(m)) return 'marketing';
    if (/compta|financ|audit|budget|trésor|fiscal|paie|bilan|tax/i.test(m)) return 'finance';
    if (/rh|ressource|recrutement|talent|humain|paie|formation|grh/i.test(m)) return 'rh';
    if (/commercial|vente|vendeur|business|client|account|b2b|sales/i.test(m)) return 'commerce';
    if (/logistique|supply|stock|transport|douane|achat|appro|entrepôt/i.test(m)) return 'logistique';
    if (/design|ux|ui|graphi|figma|creatif|créatif|illustr|visuel/i.test(m)) return 'design';
    if (/juridi|droit|avocat|notaire|lgal|legal|contrat/i.test(m)) return 'juridique';
    if (/admin|secrét|assist|direct|bureau|archiv/i.test(m)) return 'administration';
    if (/santé|infirm|médecin|pharma|soins|clinic/i.test(m)) return 'sante';
    if (/enseignant|prof|pedago|formateur|educateur|école/i.test(m)) return 'education';
    if (/énergi|électri|solaire|plombier|génie civi|btp|travaux/i.test(m)) return 'energie';
    return 'autre';
  }

  function getSecteurKey(secteur) {
    const s = (secteur || '').toLowerCase();
    if (/info|tech|web|dev|num|digital/i.test(s)) return 'informatique';
    if (/market|comm|pub|digital/i.test(s)) return 'marketing';
    if (/financ|compta|audit|fisca/i.test(s)) return 'finance';
    if (/rh|ressource|humain|recrutement/i.test(s)) return 'rh';
    if (/commerce|vente|commercial|business/i.test(s)) return 'commerce';
    if (/logistique|supply|transport/i.test(s)) return 'logistique';
    if (/design|ux|graph/i.test(s)) return 'design';
    if (/juridi|droit/i.test(s)) return 'juridique';
    if (/admin|secrét/i.test(s)) return 'administration';
    if (/santé|médec|pharma/i.test(s)) return 'sante';
    if (/educ|enseign|form/i.test(s)) return 'education';
    if (/énergi|électri/i.test(s)) return 'energie';
    return 'autre';
  }

  /* ══════════════════════════════════════════════════════════
     GÉNÉRATEURS DE CONTENU
  ══════════════════════════════════════════════════════════ */

  function generateBios(ctx) {
    const secteurKey = getSecteurKey(ctx.secteur || ctx.metier);
    const comps = (COMPETENCES_PAR_SECTEUR[secteurKey] || COMPETENCES_PAR_SECTEUR.autre).slice(0, 6);
    const quals = _shuffle(QUALITES_UNIVERSELLES).slice(0, 4);
    const expLabels = ['quelques mois', '1 an', '2 ans', '3 ans', '5 ans', 'plus de 5 ans'];
    const expLabel = expLabels[Math.floor(Math.random() * 3) + 1];
    const nom = (ctx.prenom || '') + (ctx.prenom && ctx.nom ? ' ' + ctx.nom : '');

    return BIO_TEMPLATES.map((tpl, i) => {
      const comp1 = comps[i % comps.length] || 'la gestion de projet';
      const comp2 = comps[(i + 1) % comps.length] || 'la communication';
      const q1 = quals[i % quals.length];
      const q2 = quals[(i + 1) % quals.length];
      let text = tpl
        .replace(/{metier}/g, ctx.metier || 'Professionnel(le)')
        .replace(/{secteur}/g, ctx.secteur || 'mon domaine')
        .replace(/{exp}/g, expLabel)
        .replace(/{comp1}/g, comp1)
        .replace(/{comp2}/g, comp2)
        .replace(/{qualite1}/g, q1)
        .replace(/{qualite2}/g, q2);
      if (nom) text = text.replace(/^/, '');
      return text;
    });
  }

  function generateOfferDescriptions(ctx) {
    return DESCRIPTION_OFFRE_TEMPLATES.map(tpl => {
      return tpl
        .replace(/{titre}/g, ctx.titre || 'ce poste')
        .replace(/{entreprise}/g, ctx.entreprise || 'notre entreprise')
        .replace(/{exp}/g, '2 à 3 ans');
    });
  }

  function generateMissions(ctx) {
    const secteurKey = getSecteurKey(ctx.secteur || ctx.metier);
    const missions = (MISSIONS_PAR_SECTEUR[secteurKey] || MISSIONS_PAR_SECTEUR.autre);
    const shuffled = _shuffle([...missions]);
    // Return 3 groups of 4-5 missions as separate suggestions
    return [
      shuffled.slice(0, 4).join('\n'),
      shuffled.slice(2, 6).join('\n'),
      [...shuffled.slice(4), ...shuffled.slice(0, 2)].slice(0, 4).join('\n')
    ];
  }

  function reformulerTexte(texte, ctx) {
    if (!texte || texte.trim().length < 20) return null;
    const clean = texte.trim();
    const short = clean.length < 120;

    const variations = [
      // Plus professionnel
      _capitalize(clean) + (clean.endsWith('.') ? '' : '.'),
      // Reformulation en 3e personne professionnelle
      _reformulationPro(clean, ctx),
      // Version condensée dynamique
      _versionDynamique(clean, ctx)
    ];
    return variations.filter(Boolean);
  }

  function _reformulationPro(text, ctx) {
    const metier = ctx.metier || '';
    const prefixes = [
      'Professionnel(le) ' + (metier ? 'en ' + metier + ', ' : '') + 'je me distingue par ',
      'Reconnu(e) pour mes compétences en ' + (ctx.secteur || 'mon domaine') + ', ',
      'Dans mon parcours, '
    ];
    const prefix = prefixes[Math.floor(Math.random() * prefixes.length)];
    // Simplified reformulation: prefix + lowercase original
    const reformulated = prefix + text.charAt(0).toLowerCase() + text.slice(1);
    return _capitalize(reformulated);
  }

  function _versionDynamique(text, ctx) {
    const adverbs = ['Passionné(e)', 'Motivé(e)', 'Engagé(e)', 'Déterminé(e)', 'Ambitieux(se)'];
    const adv = adverbs[Math.floor(Math.random() * adverbs.length)];
    const metier = ctx.metier ? 'en ' + ctx.metier : '';
    return adv + ' ' + metier + ', ' + text.charAt(0).toLowerCase() + text.slice(1);
  }

  function _capitalize(s) { return s ? s.charAt(0).toUpperCase() + s.slice(1) : s; }

  function _shuffle(arr) {
    const a = [...arr];
    for (let i = a.length - 1; i > 0; i--) {
      const j = Math.floor(Math.random() * (i + 1));
      [a[i], a[j]] = [a[j], a[i]];
    }
    return a;
  }

  function getCompetencesList(ctx) {
    const secteurKey = getSecteurKey(ctx.secteur || ctx.metier);
    return (COMPETENCES_PAR_SECTEUR[secteurKey] || COMPETENCES_PAR_SECTEUR.autre);
  }

  function getQualitesList() {
    return _shuffle([...QUALITES_UNIVERSELLES]);
  }

  /* ══════════════════════════════════════════════════════════
     INTERFACE UTILISATEUR (PANEL)
  ══════════════════════════════════════════════════════════ */

  let _panel = null;
  let _overlay = null;
  let _currentTarget = null;
  let _currentType = null;
  let _selectedChips = new Set();
  let _insertCallback = null;

  function buildOverlay() {
    if (_overlay) return;

    _overlay = document.createElement('div');
    _overlay.className = 'ai-overlay';
    _overlay.id = 'aiOverlay';
    _overlay.addEventListener('click', function(e) {
      if (e.target === _overlay) closePanel();
    });

    _panel = document.createElement('div');
    _panel.className = 'ai-panel';
    _panel.id = 'aiPanel';

    _overlay.appendChild(_panel);
    document.body.appendChild(_overlay);
  }

  function openPanel(targetEl, type, callback) {
    buildOverlay();
    _currentTarget = targetEl;
    _currentType = type;
    _selectedChips.clear();
    _insertCallback = callback || null;

    const ctx = readContext();
    renderPanel(ctx, type);

    _overlay.classList.add('active');
    document.body.style.overflow = 'hidden';
  }

  function closePanel() {
    if (_overlay) _overlay.classList.remove('active');
    document.body.style.overflow = '';
  }

  function _validateCtx(ctx, type) {
    /* Retourne la liste des champs manquants, ou [] si tout est OK */
    if (type === 'bio' || type === 'missions') {
      if (!ctx.metier && !ctx.secteur) return ['Métier / Poste', 'Secteur d\'activité'];
    }
    if (type === 'description') {
      const missing = [];
      if (!ctx.titre && !ctx.metier) missing.push('Titre du poste');
      if (!ctx.entreprise) missing.push('Nom de l\'entreprise');
      return missing;
    }
    if (type === 'reformuler') {
      const texte = _currentTarget ? (_currentTarget.value || _currentTarget.textContent || '').trim() : '';
      if (texte.length < 20) return ['Texte à améliorer (minimum 20 caractères)'];
    }
    return [];
  }

  function renderPanel(ctx, type) {
    const titles = {
      bio: 'Rédiger votre présentation',
      description: 'Générer une description d\'offre',
      missions: 'Suggérer des missions',
      competences: 'Suggestions de compétences',
      qualites: 'Qualités personnelles',
      reformuler: 'Améliorer votre texte'
    };

    const ctxInfo = [];
    if (ctx.metier) ctxInfo.push('<strong>Métier :</strong> ' + escHtml(ctx.metier));
    if (ctx.secteur) ctxInfo.push('<strong>Secteur :</strong> ' + escHtml(ctx.secteur));
    if (ctx.entreprise) ctxInfo.push('<strong>Entreprise :</strong> ' + escHtml(ctx.entreprise));
    if (ctx.titre) ctxInfo.push('<strong>Poste :</strong> ' + escHtml(ctx.titre));

    /* Vérifier les champs manquants avant d'afficher "Génération en cours" */
    const manquants = _validateCtx(ctx, type);

    _panel.innerHTML = `
      <div class="ai-panel__header">
        <div class="ai-panel__title">
          <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="url(#aiGrad)" stroke-width="2">
            <defs><linearGradient id="aiGrad" x1="0" y1="0" x2="1" y2="1"><stop offset="0%" stop-color="#7c3aed"/><stop offset="100%" stop-color="#a855f7"/></linearGradient></defs>
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/>
          </svg>
          ${escHtml(titles[type] || 'Assistant IA')}
          <span class="ai-badge">IA</span>
        </div>
        <button class="ai-panel__close" onclick="AIAssistant.close()">✕</button>
      </div>
      ${ctxInfo.length ? `<div class="ai-panel__context">Contenu généré d'après votre profil : ${ctxInfo.join(' · ')}</div>` : ''}
      <div id="aiContent">${manquants.length
        ? `<div class="ai-panel__context" style="color:#185FA5;border-color:rgba(24,95,165,0.3);background:rgba(24,95,165,0.05);">Veuillez remplir les champs suivants afin que l'IA puisse générer un contenu personnalisé : <strong>${escHtml(manquants.join(', '))}</strong>.</div>`
        : '<div class="ai-loading"><span class="ai-spinner">✦</span> Génération en cours…</div>'
      }</div>
    `;

    if (!manquants.length) {
      // Génération avec micro-délai pour l'effet "chargement"
      setTimeout(() => renderContent(ctx, type), 350);
    }
  }

  function renderContent(ctx, type) {
    const content = document.getElementById('aiContent');
    if (!content) return;

    if (type === 'bio') {
      const bios = generateBios(ctx);
      content.innerHTML = `
        <div class="ai-suggestions">
          ${bios.map((bio, i) => `
            <div class="ai-suggestion" id="aiSug${i}">
              <div class="ai-suggestion__text">${escHtml(bio)}</div>
              <div class="ai-suggestion__footer">
                <span class="ai-suggestion__label">Version ${i + 1}</span>
                <button class="ai-suggestion__use" onclick="AIAssistant.use(${i},'${escAttr(bio)}')">Utiliser cette version</button>
              </div>
            </div>
          `).join('')}
        </div>
      `;
    }

    else if (type === 'description') {
      const descs = generateOfferDescriptions(ctx);
      content.innerHTML = `
        <div class="ai-suggestions">
          ${descs.map((d, i) => `
            <div class="ai-suggestion" id="aiSug${i}">
              <div class="ai-suggestion__text">${escHtml(d)}</div>
              <div class="ai-suggestion__footer">
                <span class="ai-suggestion__label">Modèle ${i + 1}</span>
                <button class="ai-suggestion__use" onclick="AIAssistant.use(${i},'${escAttr(d)}')">Utiliser</button>
              </div>
            </div>
          `).join('')}
        </div>
      `;
    }

    else if (type === 'missions') {
      const missions = generateMissions(ctx);
      content.innerHTML = `
        <div class="ai-suggestions">
          ${missions.map((m, i) => `
            <div class="ai-suggestion" id="aiSug${i}">
              <div class="ai-suggestion__text">${escHtml(m)}</div>
              <div class="ai-suggestion__footer">
                <span class="ai-suggestion__label">Sélection ${i + 1}</span>
                <button class="ai-suggestion__use" onclick="AIAssistant.use(${i},'${escAttr(m)}')">Utiliser</button>
              </div>
            </div>
          `).join('')}
        </div>
      `;
    }

    else if (type === 'competences' || type === 'qualites') {
      const list = type === 'competences' ? getCompetencesList(ctx) : getQualitesList();
      const label = type === 'competences' ? 'compétences' : 'qualités';
      _selectedChips.clear();
      content.innerHTML = `
        <div class="ai-chips-section">
          <div class="ai-chips-section__title">Sélectionnez les ${label} à ajouter :</div>
          <div class="ai-chips" id="aiChipsList">
            ${list.map((item, i) => `<span class="ai-chip" data-val="${escAttr(item)}" onclick="AIAssistant.toggleChip(this)">${escHtml(item)}</span>`).join('')}
          </div>
          <button class="ai-chips-insert" id="aiChipsInsert" onclick="AIAssistant.insertChips()">
            Ajouter la sélection au formulaire
          </button>
        </div>
      `;
    }

    else if (type === 'reformuler') {
      const texte = _currentTarget ? (_currentTarget.value || _currentTarget.textContent || '') : '';
      const variations = reformulerTexte(texte, ctx);
      if (!variations || !variations.length) {
        content.innerHTML = `<div class="ai-panel__context">Veuillez d'abord saisir du texte dans le champ pour pouvoir l'améliorer.</div>`;
        return;
      }
      content.innerHTML = `
        <div class="ai-suggestions">
          ${variations.map((v, i) => `
            <div class="ai-suggestion" id="aiSug${i}">
              <div class="ai-suggestion__text">${escHtml(v)}</div>
              <div class="ai-suggestion__footer">
                <span class="ai-suggestion__label">Variation ${i + 1}</span>
                <button class="ai-suggestion__use" onclick="AIAssistant.use(${i},'${escAttr(v)}')">Utiliser</button>
              </div>
            </div>
          `).join('')}
        </div>
      `;
    }
  }

  function escHtml(s) {
    return String(s || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
  }
  function escAttr(s) {
    return String(s || '').replace(/'/g,'&#39;').replace(/"/g,'&quot;').replace(/\n/g,'\\n');
  }

  /* ══════════════════════════════════════════════════════════
     ACTIONS PUBLIQUES
  ══════════════════════════════════════════════════════════ */

  function useText(idx, text) {
    if (!_currentTarget) return;
    // Replace \n back
    const realText = text.replace(/\\n/g, '\n');
    if (_currentTarget.tagName === 'TEXTAREA' || _currentTarget.tagName === 'INPUT') {
      _currentTarget.value = realText;
      _currentTarget.dispatchEvent(new Event('input', { bubbles: true }));
      _currentTarget.dispatchEvent(new Event('change', { bubbles: true }));
    } else if (_currentTarget.isContentEditable) {
      _currentTarget.innerText = realText;
    }
    closePanel();
    // Flash feedback
    if (_currentTarget) {
      _currentTarget.style.transition = 'box-shadow .3s';
      _currentTarget.style.boxShadow = '0 0 0 3px rgba(124,58,237,.35)';
      setTimeout(() => { if (_currentTarget) _currentTarget.style.boxShadow = ''; }, 1200);
    }
  }

  function toggleChip(el) {
    const val = el.dataset.val;
    if (_selectedChips.has(val)) {
      _selectedChips.delete(val);
      el.classList.remove('selected');
    } else {
      _selectedChips.add(val);
      el.classList.add('selected');
    }
  }

  function insertChips() {
    if (_selectedChips.size === 0) return;
    const vals = Array.from(_selectedChips);

    // Custom callback (e.g. for dynamic competence/qualité fields)
    if (_insertCallback) {
      _insertCallback(vals);
      _insertCallback = null;
      _selectedChips.clear();
      closePanel();
      return;
    }

    if (!_currentTarget) { closePanel(); return; }

    // If target is a textarea/input, append as comma-separated
    if (_currentTarget.tagName === 'TEXTAREA' || _currentTarget.tagName === 'INPUT') {
      const existing = _currentTarget.value.trim();
      const joined = vals.join(', ');
      _currentTarget.value = existing ? existing + (existing.endsWith(',') ? ' ' : ', ') + joined : joined;
      _currentTarget.dispatchEvent(new Event('input', { bubbles: true }));
      _currentTarget.dispatchEvent(new Event('change', { bubbles: true }));
    }

    // If there's a tag-pills system near the target, try to add pills
    const container = _currentTarget.closest('.form-row, .field, [data-ai-pills]');
    if (container) {
      const pillsList = document.getElementById('competenceTagsList') ||
                        document.getElementById('competencesTagsList') ||
                        document.getElementById('competencesList');
      if (pillsList) {
        vals.forEach(val => {
          const pill = document.createElement('span');
          pill.className = 'tag-pill';
          pill.innerHTML = `${escHtml(val)}<button type="button" class="tag-pill__remove" onclick="this.closest('.tag-pill').remove()">✕</button>`;
          pillsList.appendChild(pill);
        });
      }
    }

    closePanel();
  }

  /* ══════════════════════════════════════════════════════════
     INJECTION DES BOUTONS IA SUR LES FORMULAIRES
  ══════════════════════════════════════════════════════════ */

  function createBtn(label, type, targetEl) {
    const btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'ai-btn';
    btn.innerHTML = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z"/></svg> ${label}`;
    btn.addEventListener('click', () => openPanel(targetEl, type));
    return btn;
  }

  function insertBtnAfter(el, btn) {
    el.parentNode.insertBefore(btn, el.nextSibling);
  }

  function attachButtons() {
    // data-ai="bio" — sur textarea de présentation / bio
    document.querySelectorAll('[data-ai="bio"]').forEach(el => {
      const row = document.createElement('div');
      row.className = 'ai-btn-row';
      row.appendChild(createBtn('✨ Générer une présentation', 'bio', el));
      row.appendChild(createBtn('✍ Améliorer mon texte', 'reformuler', el));
      el.parentNode.insertBefore(row, el.nextSibling);
    });

    // data-ai="description" — textarea description d'offre
    document.querySelectorAll('[data-ai="description"]').forEach(el => {
      const row = document.createElement('div');
      row.className = 'ai-btn-row';
      row.appendChild(createBtn('✨ Générer une description', 'description', el));
      row.appendChild(createBtn('✍ Améliorer ce texte', 'reformuler', el));
      el.parentNode.insertBefore(row, el.nextSibling);
    });

    // data-ai="missions" — textarea missions
    document.querySelectorAll('[data-ai="missions"]').forEach(el => {
      const row = document.createElement('div');
      row.className = 'ai-btn-row';
      row.appendChild(createBtn('✨ Suggérer des missions', 'missions', el));
      row.appendChild(createBtn('✍ Améliorer', 'reformuler', el));
      el.parentNode.insertBefore(row, el.nextSibling);
    });

    // data-ai="competences" — input compétences
    document.querySelectorAll('[data-ai="competences"]').forEach(el => {
      const row = document.createElement('div');
      row.className = 'ai-btn-row';
      row.appendChild(createBtn('✨ Suggestions de compétences', 'competences', el));
      el.parentNode.insertBefore(row, el.nextSibling);
    });

    // data-ai="qualites" — input qualités personnelles
    document.querySelectorAll('[data-ai="qualites"]').forEach(el => {
      const row = document.createElement('div');
      row.className = 'ai-btn-row';
      row.appendChild(createBtn('✨ Suggestions de qualités', 'qualites', el));
      el.parentNode.insertBefore(row, el.nextSibling);
    });

    // data-ai="reformuler" — champ libre
    document.querySelectorAll('[data-ai="reformuler"]').forEach(el => {
      const btn = createBtn('✍ Améliorer ce texte', 'reformuler', el);
      btn.style.marginTop = '6px';
      el.parentNode.insertBefore(btn, el.nextSibling);
    });
  }

  /* ══════════════════════════════════════════════════════════
     INIT
  ══════════════════════════════════════════════════════════ */

  function init() {
    injectStyles();
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', attachButtons);
    } else {
      attachButtons();
    }
  }

  // Auto-init
  init();

  return {
    init,
    open: openPanel,
    openChips: function(type, callback) { openPanel(null, type, callback); },
    close: closePanel,
    use: useText,
    toggleChip,
    insertChips,
    readContext,
    getCompetences: getCompetencesList,
    getQualites: getQualitesList
  };
})();
