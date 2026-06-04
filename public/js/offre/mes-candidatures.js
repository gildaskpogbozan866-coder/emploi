﻿/* ══ COULEURS PAR INITIALE ══ */
    const COLORS = ["#378ADD","#185FA5","#F5C842","#38A169","#e85d04","#7c3aed","#d62828","#023e8a"];
    function logoColor(str) {
      return COLORS[(str || 'A').charCodeAt(0) % COLORS.length];
    }

    /* ══ ÉTAT ══ */
    let allCandidatures = [];
    let activeFilter    = 'tous';
    let pendingDeleteId = null;

    /* ══ CHARGEMENT ══ */
    function load() {
      try {
        allCandidatures = JSON.parse(localStorage.getItem('mes_candidatures') || '[]');
      } catch(e) {
        allCandidatures = [];
      }
    }

    /* ══ SAUVEGARDE ══ */
    function save() {
      localStorage.setItem('mes_candidatures', JSON.stringify(allCandidatures));
    }

    /* ══ RENDU ══ */
    function render() {
      const filtered = activeFilter === 'tous'
        ? allCandidatures
        : allCandidatures.filter(c => c.statut === activeFilter);

      const listEl   = document.getElementById('candidaturesList');
      const emptyEl  = document.getElementById('emptyState');
      const statsEl  = document.getElementById('statsBar');
      const toolbarEl= document.getElementById('toolbar');

      /* Stats */
      const total    = allCandidatures.length;
      const attente  = allCandidatures.filter(c => c.statut === 'En attente').length;
      const acceptee = allCandidatures.filter(c => c.statut === 'Acceptée').length;

      if (total === 0) {
        statsEl.style.display   = 'none';
        toolbarEl.style.display = 'none';
        listEl.innerHTML        = '';
        emptyEl.style.display   = '';
        return;
      }

      statsEl.style.display    = '';
      toolbarEl.style.display  = '';
      emptyEl.style.display    = 'none';
      document.getElementById('statTotal').textContent    = total;
      document.getElementById('statAttente').textContent  = attente;
      document.getElementById('statAcceptee').textContent = acceptee;
      document.getElementById('toolbarCount').textContent =
        filtered.length + ' candidature' + (filtered.length > 1 ? 's' : '');

      if (filtered.length === 0) {
        listEl.innerHTML = `<div style="text-align:center;padding:40px 0;font-family:var(--font-body);color:#94a3b8;font-size:14px;">Aucune candidature dans cette catégorie.</div>`;
        return;
      }

      listEl.innerHTML = filtered.map((c, i) => {
        const col        = logoColor(c.entreprise);
        const badgeCls   = c.statut === 'Acceptée' ? 'mc-badge--acceptee'
                         : c.statut === 'Refusée'  ? 'mc-badge--refusee'
                         : 'mc-badge--attente';
        const badgeDot   = c.statut === 'Acceptée' ? '✓'
                         : c.statut === 'Refusée'  ? '✕' : '…';
        const viewHref   = c.offreId != null
          ? `detail-offre.html?id=${c.offreId}`
          : 'list-offre.html';

        return `
          <div class="mc-card" style="animation-delay:${i * 0.05}s">
            <div class="mc-card__logo" style="background:${col}18;color:${col}">
              ${(c.entreprise || 'E').charAt(0)}
            </div>
            <div class="mc-card__info">
              <div class="mc-card__titre">${c.titre || 'Poste non précisé'}</div>
              <div class="mc-card__entreprise">${c.entreprise || '—'}</div>
              <div class="mc-card__meta">
                <span class="mc-card__meta-item">
                  <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path stroke-linecap="round" d="M16 2v4M8 2v4M3 10h18"/></svg>
                  ${c.date || '—'}
                </span>
                ${c.prenom || c.nom ? `
                <span class="mc-card__meta-item">
                  <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                  ${(c.prenom + ' ' + c.nom).trim()}
                </span>` : ''}
              </div>
            </div>
            <div class="mc-card__actions">
              <span class="mc-badge ${badgeCls}">${badgeDot} ${c.statut}</span>
              <div style="display:flex;gap:8px;">
                <a href="${viewHref}" class="mc-btn mc-btn--view">
                  <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                  Voir l'offre
                </a>
                <button class="mc-btn mc-btn--delete" data-uid="${c.uid}">
                  <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                  Supprimer
                </button>
              </div>
            </div>
          </div>`;
      }).join('');

      /* Écouteurs boutons supprimer */
      listEl.querySelectorAll('.mc-btn--delete').forEach(btn => {
        btn.addEventListener('click', () => openDeleteModal(Number(btn.dataset.uid)));
      });
    }

    /* ══ FILTRE ══ */
    document.querySelectorAll('.mc-filter-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        document.querySelectorAll('.mc-filter-btn').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        activeFilter = btn.dataset.filter;
        render();
      });
    });

    /* ══ MODAL SUPPRESSION ══ */
    function openDeleteModal(uid) {
      pendingDeleteId = uid;
      document.getElementById('deleteOverlay').classList.add('open');
    }
    function closeDeleteModal() {
      pendingDeleteId = null;
      document.getElementById('deleteOverlay').classList.remove('open');
    }

    document.getElementById('cancelDelete').addEventListener('click', closeDeleteModal);
    document.getElementById('deleteOverlay').addEventListener('click', function(e) {
      if (e.target === this) closeDeleteModal();
    });
    document.getElementById('confirmDelete').addEventListener('click', () => {
      if (pendingDeleteId == null) return;
      allCandidatures = allCandidatures.filter(c => c.uid !== pendingDeleteId);
      save();
      closeDeleteModal();
      render();
    });

    /* ══ MENU MOBILE ══ */
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

    /* ══ INIT ══ */
    load();
    render();
