/* ── Infos candidat depuis localStorage ── */
    (function() {
      var data = {};
      try { data = JSON.parse(localStorage.getItem('cv_premium_data') || '{}'); } catch(e) {}
      var bloc = document.getElementById('spCandidat');
      if (data.prenom || data.nom || data.email) {
        var fullName = [data.prenom, data.nom].filter(Boolean).join(' ');
        var initials = ((data.prenom || '').charAt(0) + (data.nom || '').charAt(0)).toUpperCase() || 'CV';
        document.getElementById('spInitials').textContent = initials;
        document.getElementById('spName').textContent     = fullName || 'Candidat Premium';
        document.getElementById('spEmail').textContent    = data.email || '';
        document.getElementById('spDate').textContent     = data.date || new Date().toLocaleDateString('fr-FR', { day:'numeric', month:'long', year:'numeric' });
        bloc.style.display = 'flex';
      }
    })();

    /* ── Menu mobile ── */
    const hamburger  = document.getElementById('hamburger');
    const mobileMenu = document.getElementById('mobileMenu');
    hamburger.addEventListener('click', () => {
      const open = mobileMenu.classList.toggle('open');
      hamburger.classList.toggle('open', open);
      hamburger.setAttribute('aria-expanded', open);
    });
    mobileMenu.querySelectorAll('a').forEach(l => l.addEventListener('click', () => {
      mobileMenu.classList.remove('open');
      hamburger.classList.remove('open');
      hamburger.setAttribute('aria-expanded', 'false');
    }));
