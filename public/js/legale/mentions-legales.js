const hamburgerBtn = document.getElementById('hamburger');
    const mobileMenuEl  = document.getElementById('mobileMenu');
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
