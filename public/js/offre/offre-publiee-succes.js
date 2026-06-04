﻿function escHtml(s) {
    return String(s || '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
  }

  /* ── LECTURE ── */
  let offre = null;
  try {
    const raw = localStorage.getItem('offre_publiee');
    if (raw) offre = JSON.parse(raw);
  } catch(e) {}

  /* ── DÉTAIL OFFRE ── */
  const offreBody = document.getElementById('offreBody');
  if (!offre || !offre.titre) {
    offreBody.innerHTML = `
      <div style="text-align:center;padding:32px 20px;color:#64748b;font-size:14px">
        Aucune offre publiée trouvée.
        <br><br><a href="publier-offre.html" style="color:#185FA5;font-weight:700">← Créer une offre</a>
      </div>`;
  } else {
    const regions   = Array.isArray(offre.region)  && offre.region.length  ? offre.region.join(', ')  : (offre.ville || 'Non précisé');
    const secteurs  = Array.isArray(offre.secteur) && offre.secteur.length ? offre.secteur.join(', ') : 'Non précisé';
    const paidDate  = offre.paidAt ? new Date(offre.paidAt).toLocaleDateString('fr-FR', { day:'2-digit', month:'long', year:'numeric' }) : 'Aujourd\'hui';
    const expiDate  = offre.paidAt ? new Date(new Date(offre.paidAt).getTime() + 30 * 864e5).toLocaleDateString('fr-FR', { day:'2-digit', month:'long', year:'numeric' }) : '—';

    offreBody.innerHTML = `
      <div class="pub-status">Publiée — Active</div>
      <div class="pub-title">${escHtml(offre.titre)}</div>
      <div class="pub-company">${escHtml(offre.entreprise)} · ${escHtml(offre.email)}</div>
      <div class="pub-grid">
        <div class="pub-stat">
          <div class="pub-stat__icon"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
          <div class="pub-stat__val">${escHtml(offre.contrat || '—')}</div>
          <div class="pub-stat__label">Contrat</div>
        </div>
        <div class="pub-stat">
          <div class="pub-stat__icon"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg></div>
          <div class="pub-stat__val">${escHtml(regions)}</div>
          <div class="pub-stat__label">Localisation</div>
        </div>
        <div class="pub-stat">
          <div class="pub-stat__icon"><svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"/></svg></div>
          <div class="pub-stat__val">30 jours</div>
          <div class="pub-stat__label">Durée</div>
        </div>
      </div>
      <div style="font-size:12px;color:#94a3b8;padding-top:10px;border-top:1px solid #f1f5f9">
        Publiée le ${paidDate} · Expire le ${expiDate}
      </div>`;

    /* ── REÇU ── */
    const methodLabels = { momo: 'Mobile Money', card: 'Carte bancaire', cash: 'Virement bancaire' };
    document.getElementById('receiptBody').innerHTML = `
      <div class="receipt-grid">
        <div class="receipt-item">
          <div class="receipt-item__label">Référence</div>
          <div class="receipt-item__val receipt-item__val--blue">${escHtml(offre.payRef || '—')}</div>
        </div>
        <div class="receipt-item">
          <div class="receipt-item__label">Statut paiement</div>
          <div class="receipt-item__val receipt-item__val--green">✓ Réussi</div>
        </div>
        <div class="receipt-item">
          <div class="receipt-item__label">Montant payé</div>
          <div class="receipt-item__val">${(offre.montant || 2000).toLocaleString('fr-FR')} FCFA</div>
        </div>
        <div class="receipt-item">
          <div class="receipt-item__label">Mode de paiement</div>
          <div class="receipt-item__val">${escHtml(methodLabels[offre.payMethod] || offre.payMethod || '—')}</div>
        </div>
        <div class="receipt-item">
          <div class="receipt-item__label">Date</div>
          <div class="receipt-item__val">${paidDate}</div>
        </div>
        <div class="receipt-item">
          <div class="receipt-item__label">Durée de diffusion</div>
          <div class="receipt-item__val">30 jours</div>
        </div>
      </div>`;
  }

  /* ── CONFETTI ── */
  const colors = ['#F5C842','#185FA5','#38A169','#378ADD','#042C53','#185FA5'];
  const wrap = document.getElementById('confettiWrap');
  for (let i = 0; i < 60; i++) {
    const el = document.createElement('div');
    el.className = 'confetti-piece';
    el.style.cssText = [
      'left:'       + Math.random() * 100 + '%',
      'background:' + colors[Math.floor(Math.random() * colors.length)],
      'width:'      + (6 + Math.random() * 8) + 'px',
      'height:'     + (6 + Math.random() * 8) + 'px',
      'border-radius:' + (Math.random() > 0.5 ? '50%' : '2px'),
      'animation-duration:' + (1.5 + Math.random() * 2.5) + 's',
      'animation-delay:'    + (Math.random() * 1.2) + 's'
    ].join(';');
    wrap.appendChild(el);
  }
  // Retire les confettis après animation
  setTimeout(() => wrap.remove(), 4500);

  /* ── NAV MOBILE ── */
  const hamburgerBtn = document.getElementById('hamburger');
  const mobileMenuEl = document.getElementById('mobileMenu');
  hamburgerBtn.addEventListener('click', () => {
    const isOpen = mobileMenuEl.classList.toggle('open');
    hamburgerBtn.classList.toggle('open', isOpen);
    hamburgerBtn.setAttribute('aria-expanded', isOpen);
  });
