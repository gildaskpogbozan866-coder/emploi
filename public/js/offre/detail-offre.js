﻿/* ── Mobile nav ── */
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

    /* ── Data ── */
    const OFFRES = [
      {
        id: 0,
        titre: "Stagiaire Ressources Humaines",
        entreprise: "DEVAS SARL",
        lieu: "Cotonou, Bénin",
        type: "Stage", domaine: "RH",
        date: "18.05.2026", deadline: "30.06.2026",
        metier: "RH, formation",
        region: "Cotonou", ville: "Cotonou",
        teletravail: "Non",
        experience: ["Etudiant, jeune diplômé", "Débutant < 2 ans"],
        etudes: ["Bac+3"],
        secteur: "Secteur, espace recruteur",
        nbPostes: 2,
        management: "Non",
        langues: ["Français (maternelle)"],
        resume: "Nous recherchons un Stagiaire en Ressources Humaines pour renforcer notre équipe dans plusieurs domaines essentiels de la gestion des ressources humaines.",
        description: "Dans le cadre de nos activités, nous recherchons un Stagiaire en Ressources Humaines pour rejoindre notre équipe dynamique.",
        missions: [
          "Participer au processus de recrutement et d'intégration de nouveaux collaborateurs et en organisant des entretiens",
          "Contribuer à la gestion administrative, notamment la tenue des dossiers du personnel",
          "Aider à la mise à jour des politiques et des procédures RH, en faisant une veille des lois en vigueur dans le domaine",
          "Soutenir l'organisation des formations et des activités de développement des ressources humaines",
          "Participer à la gestion de la communication interne et à la préparation des rapports RH"
        ],
        profil: [
          "Formation en cours en Ressources Humaines, Administration ou domaine connexe",
          "Niveau d'études requis : Bac+3 minimum",
          "©ge maximum : 28 ans au 31 décembre 2026",
          "Excellentes compétences interpersonnelles et de communication",
          "Organisé(e) et Flexible(e) avec la capacité de gérer plusieurs t€ches simultanément"
        ],
        competences: ["Communication Interne", "Formation", "Gestion Administrative", "Gestion des Ressources Humaines", "Processus de Recrutement"],
        descEntreprise: "DEVAS SARL est une entreprise béninoise (basée à Cotonou). Elle accompagne ses clients dans la gestion et le développement de leurs ressources humaines.",
        siteEntreprise: "https://www.devas-sarl.com"
      },
      {
        id: 1,
        titre: "Agent Commercial",
        entreprise: "TOOS",
        lieu: "Porto-Novo, Bénin",
        type: "CDD", domaine: "Communication",
        date: "18.05.2026", deadline: "15.06.2026",
        metier: "Commerce, vente",
        region: "Porto-Novo", ville: "Porto-Novo",
        teletravail: "Non",
        experience: ["Etudiant, jeune diplômé", "1 à 3 ans"],
        etudes: ["Bac+1", "Bac+3", "Bac+4 et plus"],
        secteur: "Commerce, distribution",
        nbPostes: 1,
        management: "Non",
        langues: ["Français (maternelle)"],
        resume: "Nous recherchons un Agent Commercial pour rejoindre notre équipe et développer notre portefeuille clients.",
        description: "TOOS recherche un Agent Commercial dynamique pour renforcer son équipe commerciale à Porto-Novo.",
        missions: [
          "Prospecter et développer un portefeuille de clients",
          "Présenter et vendre les produits et services de l'entreprise",
          "Assurer le suivi commercial et la fidélisation des clients",
          "Atteindre les objectifs de vente fixés"
        ],
        profil: [
          "Formation commerciale ou expérience équivalente",
          "Excellentes capacités de communication et de négociation",
          "Dynamisme, autonomie et sens du résultat"
        ],
        competences: ["Communication", "Marketing", "Prospection", "Prospection Commerciale", "Vente"]
      },
      {
        id: 2,
        titre: "Lead Developer Flutter / Architecte Mobile",
        entreprise: "GLOBEX SERVICES",
        lieu: "Cotonou, Bénin",
        type: "CDI", domaine: "Informatique",
        date: "18.05.2026", deadline: "30.06.2026",
        metier: "Informatique, développement",
        region: "Cotonou", ville: "Cotonou",
        teletravail: "Hybride",
        experience: ["Expérience entre 5 ans et 10 ans", "Expérience > 10 ans"],
        etudes: ["Bac+3", "Bac+4 et plus"],
        secteur: "Technologies de l'information",
        nbPostes: 1,
        management: "Oui",
        langues: ["Français (maternelle)", "Anglais (professionnel)"],
        resume: "Nous recherchons un Lead Developer Flutter / Architecte Mobile pour piloter le développement mobile de nos applications.",
        description: "GLOBEX SERVICES recrute un Lead Developer Flutter pour concevoir et maintenir des applications mobiles performantes.",
        missions: [
          "Concevoir l'architecture des applications mobiles Flutter",
          "Piloter une équipe de développeurs mobiles",
          "Assurer la qualité du code et les bonnes pratiques",
          "Collaborer avec les équipes produit et backend",
          "Participer aux choix techniques et technologiques"
        ],
        profil: [
          "5 ans minimum d'expérience en développement mobile",
          "Maîtrise de Flutter / Dart",
          "Expérience en architecture mobile (Clean Architecture, MVVM)",
          "Capacité de leadership et de mentorat d'équipe"
        ],
        competences: ["Flutter", "Dart", "Mobile Architecture", "Firebase", "REST API"],
        descEntreprise: "GLOBEX SERVICES est une entreprise de services numériques basée à Cotonou, spécialisée dans le développement d'applications mobiles et web pour l'Afrique.",
        siteEntreprise: "https://www.globex-services.bj"
      },
      {
        id: 3,
        titre: "Développeur Web Full-Stack",
        entreprise: "TechAfrique",
        lieu: "Cotonou, Bénin",
        type: "CDI", domaine: "Informatique",
        date: "10.04.2026", deadline: "10.05.2026",
        metier: "Informatique, développement",
        region: "Cotonou", ville: "Cotonou",
        teletravail: "Hybride",
        experience: ["3 à 5 ans"],
        etudes: ["Bac+4"],
        secteur: "Technologies de l'information",
        nbPostes: 1,
        management: "Non",
        langues: ["Français (maternelle)"],
        resume: "Rejoignez une startup tech en croissance et développez des applications web modernes pour l'Afrique.",
        description: "TechAfrique recrute un développeur Full-Stack expérimenté pour renforcer son équipe produit. Vous concevrez et maintiendrez des applications web performantes, travaillerez en équipe agile et participerez aux choix techniques.",
        missions: [
          "Développer des fonctionnalités frontend et backend",
          "Concevoir et maintenir des APIs RESTful",
          "Collaborer avec l'équipe produit en méthodologie agile",
          "Participer aux revues de code et aux choix techniques",
          "Assurer la performance et la scalabilité des applications"
        ],
        profil: [
          "3 à 5 ans d'expérience en développement Full-Stack",
          "Maîtrise de React ou Vue.js et Node.js",
          "Bonne connaissance des bases de données SQL et NoSQL",
          "Esprit d'équipe et passion pour l'innovation tech africaine"
        ],
        competences: ["React ou Vue.js", "Node.js / Express", "MongoDB ou PostgreSQL", "Git & CI/CD", "API RESTful"],
        descEntreprise: "TechAfrique est une startup béninoise spécialisée dans le développement de solutions numériques innovantes pour le marché africain."
      },
      {
        id: 4,
        titre: "Chargé(e) de Communication",
        entreprise: "MediaGroup BJ",
        lieu: "Abidjan, Côte d'Ivoire",
        type: "CDD", domaine: "Communication",
        date: "09.04.2026", deadline: "09.05.2026",
        metier: "Communication, médias",
        region: "Abidjan", ville: "Abidjan",
        teletravail: "Non",
        experience: ["1 à 3 ans"],
        etudes: ["Bac+3"],
        secteur: "Médias et communication",
        nbPostes: 1,
        management: "Non",
        langues: ["Français (maternelle)"],
        resume: "Pilotez la stratégie de communication digitale d'un groupe média africain en pleine expansion.",
        description: "MediaGroup BJ recherche un(e) chargé(e) de communication dynamique pour gérer sa présence digitale et ses relations presse.",
        missions: [
          "Gérer la présence digitale et les réseaux sociaux du groupe",
          "Rédiger des contenus engageants pour les différents canaux",
          "Coordonner les campagnes de communication",
          "Assurer les relations presse et les partenariats médias"
        ],
        profil: [
          "Licence en Communication, Journalisme ou domaine connexe",
          "1 à 3 ans d'expérience en communication digitale",
          "Maîtrise des outils de création graphique (Canva, Adobe)"
        ],
        competences: ["Rédaction web", "Community management", "Canva / Adobe", "Relations presse", "Analyse de KPIs"]
      },
      {
        id: 5,
        titre: "Stage en Marketing Digital",
        entreprise: "StartupHub Lomé",
        lieu: "Lomé, Togo",
        type: "Stage", domaine: "Marketing",
        date: "08.04.2026", deadline: "08.05.2026",
        metier: "Marketing digital",
        region: "Lomé", ville: "Lomé",
        teletravail: "Hybride",
        experience: ["Etudiant, jeune diplômé"],
        etudes: ["Bac+2"],
        secteur: "Marketing et publicité",
        nbPostes: 2,
        management: "Non",
        langues: ["Français (maternelle)"],
        resume: "Intégrez une startup innovante et participez activement aux campagnes marketing digitales.",
        description: "StartupHub Lomé offre un stage enrichissant au sein de son département marketing.",
        missions: [
          "Participer à la création et à la gestion des campagnes Google Ads et Meta Ads",
          "Analyser les performances des campagnes et proposer des optimisations",
          "Contribuer à la stratégie de contenu SEO/SEA",
          "Gérer les newsletters et les emailings"
        ],
        profil: [
          "Étudiant(e) en Marketing, Communication ou domaine connexe",
          "Connaissance des bases du marketing digital",
          "Curieux(se), créatif(ve) et motivé(e)"
        ],
        competences: ["Google Ads / Meta Ads", "SEO / SEA", "Email marketing", "Google Analytics", "Notion ou Trello"]
      },
      {
        id: 6,
        titre: "Comptable Senior",
        entreprise: "Finance & Co",
        lieu: "Dakar, Sénégal",
        type: "CDI", domaine: "Finance",
        date: "07.04.2026", deadline: "07.05.2026",
        metier: "Finance, comptabilité",
        region: "Dakar", ville: "Dakar",
        teletravail: "Non",
        experience: ["5 ans et plus"],
        etudes: ["Bac+5"],
        secteur: "Finance et audit",
        nbPostes: 1,
        management: "Non",
        langues: ["Français (maternelle)"],
        resume: "Gérez la comptabilité générale et analytique d'un cabinet financier de renom à Dakar.",
        description: "Finance & Co recrute un comptable senior rigoureux pour prendre en charge la comptabilité de ses clients entreprises.",
        missions: [
          "Superviser la clôture des comptes mensuels et annuels",
          "Préparer les déclarations fiscales",
          "Conseiller la direction sur les décisions financières",
          "Réaliser des missions d'audit interne"
        ],
        profil: [
          "Master en Comptabilité, Finance ou Audit",
          "5 ans minimum d'expérience en cabinet ou entreprise",
          "Maîtrise du SYSCOHADA et de Sage Comptabilité"
        ],
        competences: ["OHADA / SYSCOHADA", "Sage Comptabilité", "Fiscalité sénégalaise", "Audit interne", "Excel avancé"]
      },
      {
        id: 7,
        titre: "Responsable Ressources Humaines",
        entreprise: "BTP Construct",
        lieu: "Douala, Cameroun",
        type: "CDI", domaine: "RH",
        date: "06.04.2026", deadline: "06.05.2026",
        metier: "RH, formation",
        region: "Douala", ville: "Douala",
        teletravail: "Non",
        experience: ["5 ans et plus"],
        etudes: ["Bac+5"],
        secteur: "BTP, construction",
        nbPostes: 1,
        management: "Oui",
        langues: ["Français (maternelle)"],
        resume: "Structurez et développez les ressources humaines d'un groupe BTP en forte croissance.",
        description: "BTP Construct recherche un(e) DRH opérationnel(le) pour accompagner sa croissance.",
        missions: [
          "Piloter le recrutement et l'intégration des nouveaux collaborateurs",
          "Gérer la formation et le développement des compétences",
          "Superviser la paie et l'administration du personnel",
          "Garantir la conformité avec le droit du travail OHADA"
        ],
        profil: [
          "Master en RH, Management ou droit social",
          "5 ans minimum d'expérience en DRH",
          "Connaissance du droit du travail OHADA",
          "Leadership et capacité à gérer des équipes terrain"
        ],
        competences: ["Droit du travail OHADA", "Recrutement & onboarding", "Gestion de la paie", "GPEC", "Communication interne"]
      },
      {
        id: 8,
        titre: "Designer UI/UX",
        entreprise: "DigitalLab Africa",
        lieu: "Remote",
        type: "Freelance", domaine: "Design",
        date: "05.04.2026", deadline: "05.05.2026",
        metier: "Design, créatif",
        region: "Remote", ville: "Remote",
        teletravail: "100% Remote",
        experience: ["3 à 5 ans"],
        etudes: ["Bac+3"],
        secteur: "Technologies de l'information",
        nbPostes: 1,
        management: "Non",
        langues: ["Français (maternelle)", "Anglais (professionnel)"],
        resume: "Concevez des interfaces intuitives et modernes pour des produits digitaux à fort impact en Afrique.",
        description: "DigitalLab Africa cherche un designer UI/UX freelance pour collaborer sur plusieurs projets mobiles et web.",
        missions: [
          "Concevoir des maquettes haute fidélité sur Figma",
          "Créer et maintenir des design systems",
          "Réaliser des tests utilisateurs et itérer sur les designs",
          "Collaborer étroitement avec les équipes de développement"
        ],
        profil: [
          "3 à 5 ans d'expérience en design UI/UX",
          "Maîtrise de Figma",
          "Portfolio solide de projets web et mobile",
          "Sensibilité aux usages africains"
        ],
        competences: ["Figma (maîtrise)", "Design system", "Prototypage", "Tests utilisateurs", "Notion de HTML/CSS"]
      },
      {
        id: 9,
        titre: "Ingénieur Électrique",
        entreprise: "EnergySol",
        lieu: "Bamako, Mali",
        type: "CDD", domaine: "Énergie",
        date: "04.04.2026", deadline: "04.05.2026",
        metier: "Ingénierie, énergie",
        region: "Bamako", ville: "Bamako",
        teletravail: "Non",
        experience: ["3 à 5 ans"],
        etudes: ["Bac+5"],
        secteur: "Énergie renouvelable",
        nbPostes: 1,
        management: "Non",
        langues: ["Français (maternelle)"],
        resume: "Participez au déploiement de solutions d'énergie solaire pour électrifier les zones rurales du Mali.",
        description: "EnergySol recrute un ingénieur électrique pour son antenne de Bamako.",
        missions: [
          "Superviser l'installation de systèmes photovoltaïques",
          "Réaliser les études techniques et de dimensionnement",
          "Former les techniciens locaux",
          "Assurer la maintenance préventive et corrective"
        ],
        profil: [
          "Master en Génie Électrique ou Énergies Renouvelables",
          "3 à 5 ans d'expérience dans le solaire PV",
          "Maîtrise de l'AutoCAD Électrique et des normes IEC",
          "Capacité à travailler sur le terrain"
        ],
        competences: ["Énergie solaire / PV", "AutoCAD Électrique", "Normes IEC", "Supervision chantier", "Formation terrain"]
      },
      {
        id: 10,
        titre: "Assistant(e) de Direction",
        entreprise: "Groupe Benin SA",
        lieu: "Porto-Novo, Bénin",
        type: "Stage", domaine: "Administration",
        date: "03.04.2026", deadline: "03.05.2026",
        metier: "Administration, secrétariat",
        region: "Porto-Novo", ville: "Porto-Novo",
        teletravail: "Non",
        experience: ["Etudiant, jeune diplômé"],
        etudes: ["Bac+2"],
        secteur: "Services aux entreprises",
        nbPostes: 1,
        management: "Non",
        langues: ["Français (maternelle)"],
        resume: "Assistez la direction générale d'un grand groupe béninois dans ses missions quotidiennes.",
        description: "Groupe Benin SA recrute un(e) assistant(e) de direction stagiaire pour soutenir le Directeur Général.",
        missions: [
          "Gérer l'agenda et les déplacements de la direction",
          "Rédiger les comptes rendus de réunions et courriers officiels",
          "Préparer les présentations et documents de travail",
          "Assurer la coordination entre les différents services"
        ],
        profil: [
          "BTS Assistanat de Direction ou domaine connexe",
          "Maîtrise du Pack Office (Word, Excel, PowerPoint)",
          "Excellente présentation et discrétion professionnelle",
          "Rigueur et sens de l'organisation"
        ],
        competences: ["Pack Office", "Rédaction professionnelle", "Gestion d'agenda", "Discrétion", "Organisation"]
      }
    ];

    const TYPE_COLORS = {
      "CDI":      { bg:"#e6f6ee", color:"#1a7a3c" },
      "CDD":      { bg:"#fff0e0", color:"#c25e00" },
      "Stage":    { bg:"#eef4ff", color:"#2563eb" },
      "Freelance":{ bg:"#fdf4ff", color:"#7c3aed" }
    };
    const DOM_COLORS = ["#378ADD","#185FA5","#F5C842","#38A169","#e85d04","#7c3aed","#d62828","#023e8a"];

    function escHtml(s) {
      return String(s || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
    }

    function userOffreToDetail(o) {
      const lieu    = Array.isArray(o.region) && o.region.length ? o.region.join(', ') : (o.ville || 'Bénin');
      const domaine = Array.isArray(o.secteur) && o.secteur.length ? o.secteur[0]
                    : Array.isArray(o.metier)  && o.metier.length  ? o.metier[0] : 'Autre';
      const div = document.createElement('div');
      div.innerHTML = o.desc || '';
      const resumeTxt = div.textContent.trim().substring(0, 180);
      const date = o.paidAt ? new Date(o.paidAt).toLocaleDateString('fr-FR',{day:'2-digit',month:'long',year:'numeric'}) : "Aujourd'hui";
      return {
        id: 'user_' + (o.payRef || '0'),
        titre: o.titre || 'Offre sans titre',
        entreprise: o.entreprise || '',
        lieu, region: lieu, type: o.contrat || 'CDI', domaine, secteur: domaine, date,
        resume: resumeTxt || 'Offre publiée sur la plateforme.',
        description: o.desc || '', profil: [],
        _profilHtml: o.profil || '',
        competences: Array.isArray(o.tags) ? o.tags : [],
        salaire: o.salaire ? (o.salaire + ' ' + (o.salPeriode||'')).trim() : null,
        _isUser: true
      };
    }

    (function init() {
      const params = new URLSearchParams(window.location.search);
      const ref    = params.get('ref');
      const id     = parseInt(params.get('id'), 10);
      let offre    = null;

      if (ref) {
        try {
          const list  = JSON.parse(localStorage.getItem('offres_publiees') || '[]');
          const found = list.find(o => o.payRef === ref) || list[parseInt(ref.replace(/\D/g,''),10)];
          if (found) offre = userOffreToDetail(found);
        } catch(e) {}
      }
      if (!offre) offre = OFFRES.find(o => o.id === id);

      if (!offre) {
        document.getElementById('pageTitle').textContent = 'Offre introuvable';
        document.title = 'Offre introuvable — Emploi Bouge Bénin';
        return;
      }

      const col = DOM_COLORS[offre.domaine.charCodeAt(0) % DOM_COLORS.length];
      document.title = offre.titre + ' — ' + offre.entreprise + ' — Emploi Bouge Bénin';

      /* Liens postuler */
      const postulerHref = 'postuler.html?id=' + offre.id;
      document.getElementById('topPostulerBtn').href    = postulerHref;
      document.getElementById('resumePostulerBtn').href = postulerHref;
      document.getElementById('mainPostulerBtn').href   = postulerHref;

      /* Pack Diffusion : pas de bouton Postuler, afficher contact */
      const DIFFUSION_PLANS = ['1jour', '3jours', '1semaine'];
      if (offre.planLabel && DIFFUSION_PLANS.includes(String(offre.planLabel).toLowerCase())) {
        ['topPostulerBtn', 'resumePostulerBtn', 'mainPostulerBtn'].forEach(function(btnId) {
          var btn = document.getElementById(btnId);
          if (btn) btn.style.display = 'none';
        });

        var CONTACT_PHONE = '+22901519298';
        var CONTACT_DISPLAY = '+229 01 51 92 98 56';
        var PHONE_SVG = '<svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>';
        var WA_SVG = '<svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.123.557 4.116 1.529 5.945L.057 24l6.305-1.654A11.882 11.882 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.818a9.815 9.815 0 01-5.007-1.374l-.36-.213-3.713.909.946-3.595-.234-.371A9.818 9.818 0 012.182 12C2.182 6.58 6.58 2.182 12 2.182S21.818 6.58 21.818 12 17.42 21.818 12 21.818z"/></svg>';

        /* Bloc contact dans la carte Résumé du poste */
        var resumeBtn = document.getElementById('resumePostulerBtn');
        if (resumeBtn && resumeBtn.parentNode) {
          var contactCard = document.createElement('div');
          contactCard.className = 'dh-diffusion-contact';
          contactCard.innerHTML =
            '<div class="dh-diffusion-contact__title">Intéressé(e) par ce poste ?</div>' +
            '<p class="dh-diffusion-contact__text">Contactez directement le recruteur par appel ou WhatsApp.</p>' +
            '<div class="dh-diffusion-contact__btns">' +
              '<a href="tel:' + CONTACT_PHONE + '" class="dh-diffusion-btn dh-diffusion-btn--call">' + PHONE_SVG + ' Appel direct</a>' +
              '<a href="https://wa.me/' + CONTACT_PHONE + '" target="_blank" rel="noopener" class="dh-diffusion-btn dh-diffusion-btn--wa">' + WA_SVG + ' WhatsApp</a>' +
            '</div>';
          resumeBtn.parentNode.insertBefore(contactCard, resumeBtn);
        }

        /* Bloc contact dans dh-bottom (avant Retour aux offres) */
        var postulerSection = document.getElementById('postuler-section');
        if (postulerSection) {
          var bottomContact = document.createElement('div');
          bottomContact.className = 'dh-diffusion-contact dh-diffusion-contact--row';
          bottomContact.innerHTML =
            '<p class="dh-diffusion-contact__text">Postulez en contactant le recruteur directement :</p>' +
            '<div class="dh-diffusion-contact__btns">' +
              '<a href="tel:' + CONTACT_PHONE + '" class="dh-diffusion-btn dh-diffusion-btn--call">' + PHONE_SVG + ' ' + CONTACT_DISPLAY + '</a>' +
              '<a href="https://wa.me/' + CONTACT_PHONE + '" target="_blank" rel="noopener" class="dh-diffusion-btn dh-diffusion-btn--wa">' + WA_SVG + ' WhatsApp</a>' +
            '</div>';
          postulerSection.insertBefore(bottomContact, postulerSection.firstChild);
        }
      }

      /* Boutons de partage dynamiques */
      const shareUrl   = encodeURIComponent(window.location.href);
      const shareText  = encodeURIComponent(offre.titre + ' — ' + offre.entreprise + ' — Emploi Bouge Bénin : ');
      document.getElementById('shareFb').href = 'https://www.facebook.com/sharer/sharer.php?u=' + shareUrl;
      document.getElementById('shareWa').href = 'https://wa.me/?text=' + shareText + shareUrl;

      /* Header */
      document.getElementById('pageTitle').textContent = offre.titre + ' — ' + (offre.ville || offre.lieu);
      document.getElementById('pageDate').textContent  = 'Publié le ' + offre.date;

      /* ── RÉSUMÉ DU POSTE ── */
      const SVG = {
        metier:    `<svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>`,
        lieu:      `<svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>`,
        contrat:   `<svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>`,
        exp:       `<svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>`,
        etudes:    `<svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>`,
        teletravail:`<svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>`,
        deadline:  `<svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path stroke-linecap="round" d="M16 2v4M8 2v4M3 10h18"/></svg>`
      };

      const rows = [
        { icon: SVG.metier,  label: 'Métier',           value: offre.metier || offre.domaine },
        { icon: SVG.lieu,    label: 'Localisation',     value: offre.region || offre.lieu },
        { icon: SVG.contrat, label: 'Type de contrat',  value: offre.type }
      ];
      if (offre.experience) rows.push({ icon: SVG.exp,        label: 'Expérience',      value: offre.experience.join(' · ') });
      if (offre.etudes)     rows.push({ icon: SVG.etudes,     label: "Niveau d'études", value: offre.etudes.join(' · ') });
      if (offre.teletravail)rows.push({ icon: SVG.teletravail, label: 'Télétravail',    value: offre.teletravail });
      if (offre.deadline)   rows.push({ icon: SVG.deadline,   label: 'Date limite',     value: offre.deadline });

      document.getElementById('resumeInfos').innerHTML = rows.map(r => `
        <div class="dh-info-row">
          <div class="dh-info-row__icon">${r.icon}</div>
          <div>
            <div class="dh-info-row__label">${escHtml(r.label)}</div>
            <div class="dh-info-row__value">${escHtml(r.value)}</div>
          </div>
        </div>`).join('');

      /* ── ENTREPRISE ── */
      let entHtml = `
        <div class="dh-ent-logo" style="background:${col}20;color:${col}">${escHtml(offre.entreprise.charAt(0))}</div>
        <div class="dh-ent-name">${escHtml(offre.entreprise)}</div>
        <div class="dh-ent-sector">${escHtml(offre.secteur || offre.domaine)}</div>`;
      if (offre.siteEntreprise) entHtml += `
        <a href="${escHtml(offre.siteEntreprise)}" target="_blank" rel="noopener" class="dh-ent-site">
          <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
          ${escHtml(offre.siteEntreprise.replace('https://','').replace(/\/$/,''))}
        </a>`;
      if (offre.descEntreprise) entHtml += `
        <div class="dh-ent-desc-label">Description de l'entreprise</div>
        <p class="dh-ent-desc">${escHtml(offre.descEntreprise)}</p>`;
      entHtml += `
        <a href="list-offre.html" class="dh-ent-all">
          <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
          Voir toutes les annonces
        </a>`;
      document.getElementById('entrepriseBody').innerHTML = entHtml;

      /* ── DÉTAILS DE L'ANNONCE ── */
      let detHtml = '';

      /* Poste proposé */
      detHtml += `<div class="dh-section">
        <div class="dh-section__title">Poste proposé : ${escHtml(offre.titre)} — ${escHtml(offre.ville || offre.lieu)}</div>
        <p class="dh-section__text">${escHtml(offre.resume)}</p>`;
      if (offre.missions && offre.missions.length) {
        detHtml += `<ul class="dh-ul">${offre.missions.map(m => `<li>${escHtml(m)}</li>`).join('')}</ul>`;
      } else if (offre.description) {
        const descContent = offre._isUser ? offre.description : escHtml(offre.description);
        detHtml += `<div class="dh-section__text dh-section__text--html" style="margin-top:8px;">${descContent}</div>`;
      }
      detHtml += `</div>`;

      /* Profil recherché */
      if ((offre.profil && offre.profil.length) || (offre._profilHtml && offre._profilHtml.trim())) {
        detHtml += `<div class="dh-section">
          <div class="dh-section__title">Profil recherché pour le poste : ${escHtml(offre.titre)} — ${escHtml(offre.ville || offre.lieu)}</div>`;
        if (offre._profilHtml && offre._profilHtml.trim()) {
          detHtml += `<div class="dh-section__text">${offre._profilHtml}</div>`;
        } else {
          detHtml += `<ul class="dh-ul">${offre.profil.map(p => `<li>${escHtml(p)}</li>`).join('')}</ul>`;
        }
        detHtml += `</div>`;
      }

      /* Critères */
      const criteriaItems = [];
      if (offre.metier)      criteriaItems.push({ k: 'Métier',              v: offre.metier });
      if (offre.secteur)     criteriaItems.push({ k: "Secteur d'activité",  v: offre.secteur });
      if (offre.type)        criteriaItems.push({ k: 'Type de contrat',     v: offre.type });
      if (offre.region)      criteriaItems.push({ k: 'Région',              v: offre.region });
      if (offre.teletravail) criteriaItems.push({ k: 'Travail à distance',  v: offre.teletravail });
      if (offre.experience)  criteriaItems.push({ k: "Niveau d'expérience", v: null, tags: offre.experience });
      if (offre.etudes)      criteriaItems.push({ k: "Niveau d'études",     v: null, tags: offre.etudes });
      if (offre.nbPostes)    criteriaItems.push({ k: 'Nombre de postes',    v: String(offre.nbPostes) });
      if (offre.management)  criteriaItems.push({ k: "Management d'équipe", v: offre.management });
      if (offre.langues)     criteriaItems.push({ k: 'Langues',             v: null, tags: offre.langues });
      if (offre.competences && offre.competences.length)
                             criteriaItems.push({ k: 'Compétences clés',    v: null, tags: offre.competences });

      if (criteriaItems.length) {
        detHtml += `<div class="dh-section">
          <div class="dh-section__title">Critères de l'offre pour le poste : ${escHtml(offre.titre)} — ${escHtml(offre.ville || offre.lieu)}</div>
          <div class="dh-criteria">
            ${criteriaItems.map(c => `
              <div class="dh-criteria-row">
                <span class="dh-criteria-key">${escHtml(c.k)} :</span>
                <span class="dh-criteria-val">
                  ${c.tags
                    ? `<span class="dh-tags">${c.tags.map(t => `<span class="dh-tag">${escHtml(t)}</span>`).join('')}</span>`
                    : escHtml(c.v)}
                </span>
              </div>`).join('')}
          </div>
        </div>`;
      }

      document.getElementById('detailsBody').innerHTML = detHtml;
    })();
