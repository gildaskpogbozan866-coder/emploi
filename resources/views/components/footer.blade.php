<footer class="footer">
  <div class="container">
    <div class="footer__grid">

      {{-- Colonne marque --}}
      <div class="footer-col">
        <a href="{{ route('home') }}" class="footer__brand">
          Emploi<span class="footer__brand-accent"> Bouge</span> Bénin
        </a>
        <p class="footer__text">
          Le pont entre les recruteurs et les talents en Afrique.
          Offres vérifiées, informations fiables, mises à jour régulières.
        </p>
        <div class="footer__socials">
          <a href="https://whatsapp.com/channel/0029VbCGlUo5q08ZH1bnm11F" target="_blank" rel="noopener" class="footer__social" title="WhatsApp">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
              <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
          </a>
          <a href="{{ route('contact') }}" class="footer__social" title="Contact">
            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
          </a>
        </div>
        <a href="{{ route('auth.inscription') }}" class="btn--outline-white">
          <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
          Commencer gratuitement
        </a>
      </div>

      {{-- Colonne liens plateforme --}}
      <div class="footer-col">
        <p class="footer__col-title">Plateforme</p>
        <ul class="footer__nav-list">
          <li><a href="{{ route('offre.list') }}">Offres d'emploi</a></li>
          <li><a href="{{ route('cv.public.theque') }}">CVthèque</a></li>
          <li><a href="{{ route('talent.public.list') }}">Profilthèque Talents</a></li>
          <li><a href="{{ route('service.list') }}">Services</a></li>
          <li><a href="{{ route('blog.list') }}">Blog &amp; Conseils</a></li>
          <li><a href="{{ route('cv.public.depot') }}">Déposer mon CV</a></li>
          <li><a href="{{ route('offre.publier') }}">Publier une offre</a></li>
        </ul>
      </div>

      {{-- Colonne infos & contact --}}
      <div class="footer-col">
        <p class="footer__col-title">Informations</p>
        <ul class="footer__nav-list">
          <li><a href="{{ route('a-propos') }}">À propos</a></li>
          <li><a href="{{ route('faq') }}">FAQ</a></li>
          <li><a href="{{ route('contact') }}">Contact</a></li>
        </ul>

        <p class="footer__col-title" style="margin-top:12px">Légal</p>
        <ul class="footer__nav-list">
          <li><a href="{{ url('/legale/mentions-legales') }}">Mentions légales</a></li>
          <li><a href="{{ url('/legale/politique-confidentialite') }}">Politique de confidentialité</a></li>
          <li><a href="{{ url('/legale/cgv') }}">CGV</a></li>
        </ul>

        <ul class="footer__contact-list" style="margin-top:8px">
          <li class="footer__contact-item">
            <span class="footer__contact-icon">
              <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </span>
            <a href="mailto:contact@emploibougebenin.com" class="footer__contact-link">contact@emploibougebenin.com</a>
          </li>
          <li class="footer__contact-item">
            <span class="footer__contact-icon">
              <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </span>
            <span class="footer__contact-link">Cotonou, Bénin</span>
          </li>
        </ul>
      </div>

    </div>
  </div>

  <div class="footer__bottom">
    <div class="container">
      <div class="footer__bottom-inner">
        <p class="footer__copyright">© {{ date('Y') }} Emploi Bouge Bénin — Tous droits réservés</p>
        <p class="footer__credit">Conçu avec ❤ pour la jeunesse africaine</p>
      </div>
    </div>
  </div>
</footer>
