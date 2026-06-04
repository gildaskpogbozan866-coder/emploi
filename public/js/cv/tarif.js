﻿/* ── Menu mobile ── */
    const hamburgerBtn = document.getElementById('hamburger');
    const mobileMenuEl = document.getElementById('mobileMenu');
    hamburgerBtn.addEventListener('click', () => {
      const isOpen = mobileMenuEl.classList.toggle('open');
      hamburgerBtn.classList.toggle('open', isOpen);
      hamburgerBtn.setAttribute('aria-expanded', isOpen);
    });

    /* ── Modale Comment ça marche ── */
    function openHow(tabIndex) {
      document.getElementById('howOverlay').classList.add('open');
      document.body.style.overflow = 'hidden';
      switchHow(tabIndex);
    }

    function closeHow() {
      document.getElementById('howOverlay').classList.remove('open');
      document.body.style.overflow = '';
    }

    function closeHowOutside(e) {
      if (e.target === document.getElementById('howOverlay')) closeHow();
    }

    function switchHow(index) {
      document.querySelectorAll('.how-tab').forEach(function(t, i) {
        t.classList.toggle('active', i === index);
      });
      document.querySelectorAll('.how-panel').forEach(function(p, i) {
        p.classList.toggle('active', i === index);
      });
    }

    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape') { closeHow(); closeCVHow(); }
    });

    /* ── Modale CVthèque ── */
    function openCVHow(panel) {
      document.getElementById('cvhowOverlay').classList.add('open');
      document.body.style.overflow = 'hidden';
      switchCVHow(panel);
    }
    function closeCVHow() {
      document.getElementById('cvhowOverlay').classList.remove('open');
      document.body.style.overflow = '';
    }
    function closeCVHowOutside(e) {
      if (e.target === document.getElementById('cvhowOverlay')) closeCVHow();
    }
    function switchCVHow(index) {
      document.querySelectorAll('.cvhow-tab').forEach(function(t, i) {
        t.classList.toggle('active', i === index);
      });
      document.querySelectorAll('.cvhow-panel').forEach(function(p, i) {
        p.classList.toggle('active', i === index);
      });
    }
    document.querySelectorAll('.cvhow-tab').forEach(function(btn) {
      btn.addEventListener('click', function() {
        switchCVHow(parseInt(btn.dataset.panel, 10));
      });
    });

    /* ── Sélecteur de durée ── */
    var BASE_PREMIUM  = 30300;
    var BASE_ILLIMITE = 50500;

    function formatFCFA(n) {
      return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
    }

    function durationLabel(months) {
      if (months === 1)  return '1 mois';
      if (months === 3)  return '3 mois';
      if (months === 6)  return '6 mois';
      if (months === 12) return '1 an';
      return months + ' mois';
    }

    function updatePrices(months) {
      document.getElementById('price-premium').textContent  = formatFCFA(BASE_PREMIUM  * months);
      document.getElementById('price-illimite').textContent = formatFCFA(BASE_ILLIMITE * months);
      var label = months === 1 ? '/ mois' : '/ ' + durationLabel(months);
      document.getElementById('dur-premium').textContent  = label;
      document.getElementById('dur-illimite').textContent = label;
      var btnLabel = durationLabel(months);
      document.getElementById('dur-btn-premium').textContent  = btnLabel;
      document.getElementById('dur-btn-illimite').textContent = btnLabel;
    }

    document.querySelectorAll('.dur-btn').forEach(function(btn) {
      btn.addEventListener('click', function() {
        document.querySelectorAll('.dur-btn').forEach(function(b) { b.classList.remove('active'); });
        btn.classList.add('active');
        updatePrices(parseInt(btn.dataset.months, 10));
      });
    });
