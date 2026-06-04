﻿/* ── LECTURE DU BROUILLON ── */
  let offre = null;
  try {
    const raw = localStorage.getItem('offre_pending');
    if (raw) offre = JSON.parse(raw);
  } catch(e) {}

  /* ── RÉCAPITULATIF ── */
  const recapBody = document.getElementById('recapBody');
  if (!offre || !offre.titre) {
    recapBody.innerHTML = `
      <div class="recap-empty">
        <p>Aucune offre en attente de paiement.</p>
        <p style="margin-top:10px"><a href="publier-offre.html">← Retour à la création de l'offre</a></p>
      </div>`;
  } else {
    const regions = Array.isArray(offre.region) && offre.region.length
      ? offre.region.join(', ') : (offre.ville || 'Non précisé');
    const secteurs = Array.isArray(offre.secteur) && offre.secteur.length
      ? offre.secteur.join(', ') : 'Non précisé';

    recapBody.innerHTML = `
      <div class="recap-badge">
        <svg width="10" height="10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        En attente de paiement
      </div>
      <div class="recap-title">${escHtml(offre.titre)}</div>
      <div class="recap-company">${escHtml(offre.entreprise)}</div>
      <div class="recap-meta">
        <div class="recap-meta-row">
          <div class="recap-meta-icon">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
          </div>
          <div>
            <div class="recap-meta-label">Type de contrat</div>
            <div class="recap-meta-val">${escHtml(offre.contrat || 'Non précisé')}</div>
          </div>
        </div>
        <div class="recap-meta-row">
          <div class="recap-meta-icon">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
          </div>
          <div>
            <div class="recap-meta-label">Localisation</div>
            <div class="recap-meta-val">${escHtml(regions)}</div>
          </div>
        </div>
        <div class="recap-meta-row">
          <div class="recap-meta-icon">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
          </div>
          <div>
            <div class="recap-meta-label">Secteur</div>
            <div class="recap-meta-val">${escHtml(secteurs)}</div>
          </div>
        </div>
        ${offre.salaire ? `
        <div class="recap-meta-row">
          <div class="recap-meta-icon">
            <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          </div>
          <div>
            <div class="recap-meta-label">Salaire</div>
            <div class="recap-meta-val">${escHtml(offre.salaire)} ${escHtml(offre.salPeriode || '')}</div>
          </div>
        </div>` : ''}
      </div>`;
  }

  /* ── MÉTHODE DE PAIEMENT ── */
  let currentMethod = 'momo';

  function selectMethod(m) {
    currentMethod = m;
    ['momo','card','cash'].forEach(id => {
      document.getElementById('method-' + id).classList.toggle('selected', id === m);
    });
    document.getElementById('phoneWrap').classList.toggle('visible', m === 'momo');
    document.getElementById('cardWrap').classList.toggle('visible', m === 'card');
  }

  function formatCard(input) {
    let v = input.value.replace(/\D/g,'').substring(0,16);
    input.value = v.replace(/(.{4})/g,'$1 ').trim();
  }

  /* ── PAIEMENT ── */
  function escHtml(s) {
    return String(s || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
  }

  function showModal(html) {
    document.getElementById('payModal').innerHTML = html;
    document.getElementById('payOverlay').classList.add('visible');
  }

  function lancerPaiement() {
    // Vérification basique
    if (currentMethod === 'momo') {
      const phone = document.getElementById('phoneInput').value.trim();
      if (!phone) {
        document.getElementById('phoneInput').style.borderColor = '#185FA5';
        document.getElementById('phoneInput').style.boxShadow = '0 0 0 3px rgba(24,95,165,0.15)';
        document.getElementById('phoneInput').focus();
        return;
      }
      document.getElementById('phoneInput').style.borderColor = '';
      document.getElementById('phoneInput').style.boxShadow = '';
    }

    if (!offre || !offre.titre) {
      alert('Aucune offre à payer. Veuillez créer une offre.');
      window.location.href = 'publier-offre.html';
      return;
    }

    document.getElementById('payBtn').disabled = true;

    // Étape 1 : Traitement en cours
    showModal(`
      <div class="pay-modal__icon pay-modal__icon--loading">
        <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
          style="animation:spin 1s linear infinite">
          <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
        </svg>
      </div>
      <div class="pay-modal__title">Paiement en cours…</div>
      <div class="pay-modal__sub">Connexion au service de paiement.<br>Veuillez patienter.</div>
    `);

    // Étape 2 : Confirmation (simulée à 1.5s)
    setTimeout(() => {
      showModal(`
        <div class="pay-modal__icon pay-modal__icon--loading">
          <svg width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
            style="animation:spin 1s linear infinite">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
          </svg>
        </div>
        <div class="pay-modal__title">Validation du paiement…</div>
        <div class="pay-modal__sub">Vérification de la transaction.<br>Ne fermez pas cette page.</div>
      `);
    }, 1500);

    // Étape 3 : Succès et redirection (à 3.2s)
    setTimeout(() => {
      var offreExtra = {};
      try { offreExtra = JSON.parse(localStorage.getItem('offre_extra') || '{}'); } catch(e) {}
      const ref = 'JER-' + Date.now().toString(36).toUpperCase();
      const offrePubliee = {
        ...offre,
        planLabel:   offreExtra.planLabel || null,
        status:      'published',
        paidAt:      new Date().toISOString(),
        payRef:      ref,
        payMethod:   currentMethod,
        montant:     offreExtra.finalPrix || 2000,
        duree:       30
      };
      try {
        localStorage.setItem('offre_publiee', JSON.stringify(offrePubliee));
        const existing = JSON.parse(localStorage.getItem('offres_publiees') || '[]');
        existing.unshift(offrePubliee);
        localStorage.setItem('offres_publiees', JSON.stringify(existing));
        localStorage.removeItem('offre_pending');
        localStorage.removeItem('offre_draft');
        localStorage.removeItem('offre_extra');
      } catch(e) {}

      showModal(`
        <div class="pay-modal__icon pay-modal__icon--success">
          <svg width="36" height="36" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>
        <div class="pay-modal__title">Paiement réussi !</div>
        <div class="pay-modal__sub">Votre offre est en cours de publication.<br>Redirection en cours…</div>
        <div class="pay-modal__ref">Référence : <strong>${ref}</strong></div>
      `);

      setTimeout(() => {
        window.location.href = 'offre-publiee-succes.html';
      }, 1800);

    }, 3200);
  }

  /* ── NAV MOBILE ── */
  const hamburgerBtn = document.getElementById('hamburger');
  const mobileMenuEl = document.getElementById('mobileMenu');
  hamburgerBtn.addEventListener('click', () => {
    const isOpen = mobileMenuEl.classList.toggle('open');
    hamburgerBtn.classList.toggle('open', isOpen);
    hamburgerBtn.setAttribute('aria-expanded', isOpen);
  });
