﻿/* ══ DONNÉES OFFRES ══ */
    const OFFRES = [
      { id:0, titre:"Développeur Web Full-Stack",       entreprise:"TechAfrique" },
      { id:1, titre:"Chargé(e) de Communication",       entreprise:"MediaGroup BJ" },
      { id:2, titre:"Stage en Marketing Digital",       entreprise:"StartupHub Lomé" },
      { id:3, titre:"Comptable Senior",                 entreprise:"Finance & Co" },
      { id:4, titre:"Responsable Ressources Humaines",  entreprise:"BTP Construct" },
      { id:5, titre:"Designer UI/UX",                   entreprise:"DigitalLab Africa" },
      { id:6, titre:"Ingénieur Électrique",              entreprise:"EnergySol" },
      { id:7, titre:"Assistant(e) de Direction",         entreprise:"Groupe Benin SA" },
      { id:8, titre:"Journaliste",                       entreprise:"ECOMA" }
    ];

    /* ══ LECTURE LOCALSTORAGE ══ */
    (function loadData() {
      let data = {};
      try {
        data = JSON.parse(localStorage.getItem('candidature_data') || '{}');
      } catch(e) {}

      const offre = OFFRES.find(o => o.id === data.offreId) || null;

      const rows = [];

      /* Nom du candidat */
      if (data.prenom || data.nom) {
        rows.push({
          icon: `<svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>`,
          label: 'Candidat',
          value: (data.prenom + ' ' + data.nom).trim()
        });
      }

      /* Poste */
      rows.push({
        icon: `<svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>`,
        label: 'Poste',
        value: offre ? offre.titre : 'Non précisé'
      });

      /* Entreprise */
      rows.push({
        icon: `<svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="14" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" d="M16 7V5a2 2 0 00-2-2h-4a2 2 0 00-2 2v2"/></svg>`,
        label: 'Entreprise',
        value: offre ? offre.entreprise : 'Non précisée'
      });

      /* Date */
      rows.push({
        icon: `<svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path stroke-linecap="round" d="M16 2v4M8 2v4M3 10h18"/></svg>`,
        label: 'Date de soumission',
        value: data.date || new Date().toLocaleDateString('fr-FR', { day:'numeric', month:'long', year:'numeric' })
      });

      document.getElementById('infoList').innerHTML = rows.map(r => `
        <div class="succes-info-row">
          <div class="succes-info-row__icon">${r.icon}</div>
          <div>
            <div class="succes-info-row__label">${r.label}</div>
            <div class="succes-info-row__value">${r.value}</div>
          </div>
        </div>`).join('');

      /* Titre de l'onglet */
      if (offre) document.title = 'Candidature — ' + offre.titre + ' — Emploi Bouge Bénin';
    })();

    /* ── Menu mobile ── */
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
