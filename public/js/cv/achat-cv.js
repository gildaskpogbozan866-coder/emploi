﻿function ajouterPanier(pack, prix) {
      const toast   = document.getElementById('cartToast');
      const msgEl   = document.getElementById('cartToastMsg');
      msgEl.textContent = pack + ' — ' + prix + ' ajouté au panier !';
      toast.classList.add('show');
      setTimeout(() => toast.classList.remove('show'), 3500);
    }

    const hamburgerBtn = document.getElementById('hamburger');
    const mobileMenuEl = document.getElementById('mobileMenu');
    hamburgerBtn.addEventListener('click', () => {
      const isOpen = mobileMenuEl.classList.toggle('open');
      hamburgerBtn.classList.toggle('open', isOpen);
      hamburgerBtn.setAttribute('aria-expanded', isOpen);
    });
