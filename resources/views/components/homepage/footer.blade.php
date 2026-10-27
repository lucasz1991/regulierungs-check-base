<footer class="site-footer">
 <div class="container footer-grid">
  <div class="footer-brand">
   <a aria-label="Regulierungs-CHECK Startseite" href="{{ route('home') }}" wire:navigate="">
    <img alt="" class="brand-light" height="232" src="{{ asset('site-images/logo/logo-text-only.png') }}" width="671"/>
    <img alt="" class="brand-dark" height="707" src="{{ asset('site-images/logo/logo-white-yelllow.png') }}" width="2048"/>
   </a>
   <p>
    Leistungs-CHECK auf Basis
    <br/>
    echter Schadenerfahrungen.
   </p>
  </div>
  <nav aria-label="Funktionen">
   <h3>
    Funktionen
   </h3>
   <a class="" href="{{ route('insurances') }}" wire:navigate="">
    Versicherungen
   </a>
   <a class="" href="{{ route('reviews') }}" wire:navigate="">
    Bewertungen
   </a>
  </nav>
  <nav aria-label="Unternehmen">
   <h3>
    Unternehmen
   </h3>
   <a class="" href="{{ route('aboutus') }}" wire:navigate="">
    Über uns
   </a>
   <a class="" href="{{ route('howto') }}" wire:navigate="">
    So funktioniert’s
   </a>
  </nav>
  <nav aria-label="Informationen">
   <h3>
    Informationen
   </h3>
   <a class="" href="{{ route('abos') }}" wire:navigate="">
    Abonnements
   </a>
   <a class="" href="{{ route('faqs') }}" wire:navigate="">
    FAQ
   </a>
   <a class="" href="{{ route('contact') }}" wire:navigate="">
    Kontakt
   </a>
  </nav>
 </div>
 <div class="container footer-bottom">
  <span>
   © {{ date('Y') }} Regulierungs-CHECK. Alle Rechte vorbehalten.
  </span>
  <div>
   <a class="" href="{{ route('terms') }}" wire:navigate="">
    AGB
   </a>
   <a class="" href="{{ route('privacypolicy') }}" wire:navigate="">
    Datenschutz
   </a>
   <a class="" href="{{ route('imprint') }}" wire:navigate="">
    Impressum
   </a>
   <a class="" href="https://www.instagram.com/regulierungs_check?igsh=MWV2MWI5NTk0NWtscQ%3D%3D&amp;utm_source=qr" rel="noopener" target="_blank">
    Instagram
   </a>
  </div>
  <a aria-label="Zurück nach oben" class="back-top" href="#main">
   ↑
  </a>
 </div>
</footer>
