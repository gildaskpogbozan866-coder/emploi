﻿/* ── Données services ── */
    const SERVICES = {
      'cv-seul': { label: 'CV Professionnel', sub: 'Livraison sous 24–48h', price: 2500, features: ['CV moderne & design', 'Analyse du profil', '1 révision gratuite'] },
      'lm-seule': { label: 'Lettre de Motivation', sub: 'Livraison sous 24h', price: 1000, features: ['Lettre personnalisée', 'Adaptée au poste', '1 révision gratuite'] },
      'cv-lm': { label: 'CV + Lettre de Motivation', sub: 'Livraison sous 24–48h', price: 3200, features: ['CV moderne & design', 'Lettre personnalisée', 'Analyse du profil', '1 révision gratuite'] }
    };
    const svgCheck = `<svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>`;

    /* ── Lecture des données commande ── */
    let commandeData = {};
    try { commandeData = JSON.parse(localStorage.getItem('commande_data') || '{}'); } catch(e) {}

    if (!commandeData.service) {
      /* Aucune donnée → rediriger vers le formulaire */
      window.location.replace('commande.html');
    }

    const service = SERVICES[commandeData.service] || SERVICES['cv-seul'];

    /* Ordre card */
    document.getElementById('orderTitle').textContent = service.label;
    document.getElementById('orderPrice').textContent = service.price.toLocaleString('fr-FR');
    document.getElementById('orderFeatures').innerHTML = service.features.map(f =>
      `<div class="order-feature">${svgCheck} ${f}</div>`
    ).join('');

    /* Client recap */
    const initials = (commandeData.nom || '?').split(' ').map(w => w[0]).join('').slice(0,2).toUpperCase();
    document.getElementById('clientAvatar').textContent = initials;
    document.getElementById('clientName').textContent = commandeData.nom || '—';
    document.getElementById('clientContact').textContent = [commandeData.email, commandeData.whatsapp].filter(Boolean).join(' · ');

    /* ── Sélection méthode ── */
    function selectMethod(m) {
      document.querySelectorAll('.pay-method').forEach(el => el.classList.remove('selected'));
      document.querySelectorAll('.pay-detail').forEach(el => el.classList.remove('active'));
      document.getElementById('method-' + m).classList.add('selected');
      document.getElementById('method-' + m).querySelector('input').checked = true;
      document.getElementById('detail-' + m).classList.add('active');
    }

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

    /* ── Validation du champ actif ── */
    function getPaymentError() {
      const m = document.querySelector('.pay-method.selected input').value;
      if (m === 'mm') {
        const v = document.getElementById('phoneInput').value.trim();
        if (!v || v.replace(/\s/g,'').length < 8) return 'Veuillez entrer un numéro Mobile Money valide.';
      }
      if (m === 'card') {
        if (!document.getElementById('cardNumber').value.trim()) return 'Veuillez entrer le numéro de votre carte.';
        if (!document.getElementById('cardExpiry').value.trim()) return "Veuillez entrer la date d'expiration.";
        if (!document.getElementById('cardCvc').value.trim()) return 'Veuillez entrer le CVV.';
      }
      if (m === 'pp') {
        const v = document.getElementById('ppEmail').value.trim();
        if (!v || !v.includes('@')) return 'Veuillez entrer votre adresse email PayPal.';
      }
      return null;
    }

    /* ── Paiement ── */
    document.getElementById('payBtn').addEventListener('click', async () => {
      const errorEl   = document.getElementById('payError');
      const errorText = document.getElementById('payErrorText');
      const payBtn    = document.getElementById('payBtn');
      const method    = document.querySelector('.pay-method.selected input').value;
      const err       = getPaymentError();

      errorEl.classList.remove('show');
      if (err) { errorText.textContent = err; errorEl.classList.add('show'); return; }

      payBtn.disabled = true;
      payBtn.innerHTML = `<div class="pay-btn__spinner"></div><span>Traitement en cours…</span>`;
      await new Promise(r => setTimeout(r, 2200));

      const methodLabels = { mm: 'Mobile Money', card: 'Carte bancaire', pp: 'PayPal', bank: 'Virement bancaire' };
      document.getElementById('payFormWrap').style.display = 'none';

      document.getElementById('successDetail').innerHTML = `
        <div class="pay-success__detail-row"><span class="pay-success__detail-label">Service</span><span class="pay-success__detail-value">${service.label}</span></div>
        <div class="pay-success__detail-row"><span class="pay-success__detail-label">Montant payé</span><span class="pay-success__detail-value">${service.price.toLocaleString('fr-FR')} FCFA</span></div>
        <div class="pay-success__detail-row"><span class="pay-success__detail-label">Méthode</span><span class="pay-success__detail-value">${methodLabels[method]}</span></div>
        <div class="pay-success__detail-row"><span class="pay-success__detail-label">Livraison</span><span class="pay-success__detail-value">${service.sub}</span></div>
      `;
      document.getElementById('paySuccess').classList.add('show');
      localStorage.removeItem('commande_data');

      if (commandeData.service === 'cv-seul' || commandeData.service === 'cv-lm') {
        const msg = encodeURIComponent(
          'Bonjour,\n\nJe viens de payer pour le service : ' + service.label + '.\n\nVoici mes informations :\n- Nom : ' + (commandeData.nom||'') + '\n- Email : ' + (commandeData.email||'') + '\n- WhatsApp : ' + (commandeData.whatsapp||'') + '\n\nMerci de créer mon CV.'
        );
        document.getElementById('waBtn').href = 'https://wa.me/22901519298?text=' + msg;
        document.getElementById('cvInfoSection').classList.add('show');
      }
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
