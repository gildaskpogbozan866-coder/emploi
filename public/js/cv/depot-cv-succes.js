/* ── Affichage des infos candidat depuis localStorage ── */
    (function() {
      let data = {};
      try { data = JSON.parse(localStorage.getItem('cv_soumis_data') || '{}'); } catch(e) {}

      const strip = document.getElementById('candidatStrip');
      const nameEl  = document.getElementById('candidatName');
      const emailEl = document.getElementById('candidatEmail');
      const dateEl  = document.getElementById('candidatDate');

      if (data.prenom || data.nom || data.email) {
        const fullName = [data.prenom, data.nom].filter(Boolean).join(' ');
        nameEl.textContent  = fullName || 'Candidat';
        emailEl.textContent = data.email || '';
        dateEl.textContent  = data.date  || new Date().toLocaleDateString('fr-FR', { day:'numeric', month:'long', year:'numeric' });
        strip.style.display = 'flex';
      }
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
