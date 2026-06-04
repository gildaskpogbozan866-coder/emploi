﻿/* ── Données des services ── */
    const SERVICES = {
      'cv-seul': {
        label: 'CV Professionnel',
        sub: 'Livraison sous 24–48h · Format PDF + Word',
        price: 2500,
        lines: ['Rédaction CV', '1 révision gratuite'],
        includes: ['Analyse du profil professionnel', 'CV moderne & design', '1 révision gratuite']
      },
      'lm-seule': {
        label: 'Lettre de Motivation',
        sub: 'Livraison sous 24h · Format PDF + Word',
        price: 1000,
        lines: ['Lettre personnalisée', '1 révision gratuite'],
        includes: ['Lettre adaptée au poste', 'Ton professionnel & convaincant', '1 révision gratuite']
      },
      'cv-lm': {
        label: 'CV + Lettre de Motivation',
        sub: 'Livraison sous 24–48h · Format PDF + Word',
        price: 3200,
        lines: ['CV Professionnel', 'Lettre de Motivation', '1 révision gratuite'],
        includes: ['Analyse du profil professionnel', 'CV moderne & design', 'Lettre personnalisée', '1 révision gratuite']
      }
    };

    const svgCheck = `<svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>`;

    function updateRecap(key) {
      const s = SERVICES[key];
      if (!s) return;
      document.getElementById('recapServiceName').textContent = s.label;
      document.getElementById('recapServiceSub').textContent = s.sub;
      document.getElementById('recapTotal').textContent = s.price.toLocaleString('fr-FR') + ' FCFA';
      document.getElementById('recapLines').innerHTML = s.lines.map(l =>
        `<div class="commande-recap__line"><span>${l}</span><span>inclus</span></div>`
      ).join('');
      document.getElementById('recapIncludes').innerHTML = s.includes.map(i =>
        `<div class="commande-recap__include">${svgCheck}${i}</div>`
      ).join('');
    }

    const serviceSelect = document.getElementById('service');
    updateRecap(serviceSelect.value);
    serviceSelect.addEventListener('change', () => updateRecap(serviceSelect.value));

    /* ── Nav hamburger ── */
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

    /* ── Soumission → redirection vers paiement ── */
    document.getElementById('commandeForm').addEventListener('submit', function (e) {
      e.preventDefault();
      const nom      = document.getElementById('nom').value.trim();
      const email    = document.getElementById('email').value.trim();
      const whatsapp = document.getElementById('whatsapp').value.trim();
      const service  = document.getElementById('service').value;
      const message  = document.getElementById('message').value.trim();
      if (!nom || !email || !whatsapp) {
        alert('Veuillez remplir tous les champs obligatoires (*).');
        return;
      }
      localStorage.setItem('commande_data', JSON.stringify({ nom, email, whatsapp, service, message }));
      window.location.href = 'paiement-commande.html';
    });
