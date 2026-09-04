<footer class="site-footer">
  <div class="container">
    <div class="site-footer__grid">
      <div style="display:flex; flex-direction:column; gap:16px;">
        <div class="site-footer__brand">
          <img src="/assets/icons/logo-full-white.png" alt="" class="site-footer__brand-image">
          <span class="site-footer__brand-word">
            <span class="site-footer__brand-main">Webservice</span>
            <span class="site-footer__brand-sub">Studio</span>
          </span>
        </div>
        <p style="font-size:14px; line-height:1.6; color:#9FBCC4; max-width:260px;">Розробляємо сайти й застосунки, налаштовуємо інструменти для трафіку та адмініструємо домени — під ключ.</p>
      </div>

      <div>
        <p class="footer-col-title">Розділи</p>
        <a class="footer-col-link" href="/">Головна</a>
        <a class="footer-col-link" href="/pro-studiyu">Про студію</a>
        <a class="footer-col-link" href="/poslugy">Послуги</a>
        <a class="footer-col-link" href="/tsiny">Ціни</a>
        <a class="footer-col-link" href="/portfolio">Наші роботи</a>
        <a class="footer-col-link" href="/blog">Блог</a>
      </div>

      <div>
        <p class="footer-col-title">Послуги</p>
        <a class="footer-col-link" href="/poslugy#rozrobka-saitiv">Розробка сайтів</a>
        <a class="footer-col-link" href="/poslugy#android-dodatky">Застосунки під Android</a>
        <a class="footer-col-link" href="/poslugy#arbitrazh-trafiku">Арбітраж трафіку</a>
        <a class="footer-col-link" href="/poslugy#administruvannia">Адміністрування доменів</a>
      </div>

      <?php
        $footerEmail = Setting::get('contact_email', 'support@web-service.studio');
        $footerPhoneDisplay = Setting::get('contact_phone_display', '+380 95 921 22 03');
        $footerWhatsapp = Setting::get('contact_whatsapp', 'https://api.whatsapp.com/send/?phone=380959212203');
        $footerFacebook = Setting::get('contact_facebook', 'https://www.facebook.com/webservicestudio/');
        $footerInstagram = Setting::get('contact_instagram', 'https://www.instagram.com/webservicestudio/');
        $footerTelegram = Setting::get('contact_telegram', 'https://t.me/webservices_studio');
      ?>
      <div>
        <p class="footer-col-title">Контакти</p>
        <a class="footer-col-link" href="mailto:<?= h($footerEmail) ?>"><?= h($footerEmail) ?></a>
        <a class="footer-col-link" href="<?= h($footerWhatsapp) ?>" target="_blank" rel="noopener"><?= h($footerPhoneDisplay) ?></a>
        <div class="site-footer__social">
          <a href="<?= h($footerFacebook) ?>" target="_blank" rel="noopener" aria-label="Facebook">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#D7E7EA" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 9h3V6h-3c-2.2 0-4 1.8-4 4v2H8v3h2v6h3v-6h3l1-3h-4v-2c0-.6.4-1 1-1z"/></svg>
          </a>
          <a href="<?= h($footerInstagram) ?>" target="_blank" rel="noopener" aria-label="Instagram">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#D7E7EA" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="4" y="4" width="16" height="16" rx="4"/><circle cx="12" cy="12" r="3.5"/><circle cx="16.8" cy="7.2" r="0.6" fill="#D7E7EA" stroke="none"/></svg>
          </a>
          <a href="<?= h($footerTelegram) ?>" target="_blank" rel="noopener" aria-label="Telegram">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#D7E7EA" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 4L3 11l6 2.5M21 4l-3 16-8-6.5M21 4L9 13.5v5.5l3-3.5"/></svg>
          </a>
          <a href="<?= h($footerWhatsapp) ?>" target="_blank" rel="noopener" aria-label="WhatsApp">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#D7E7EA" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 20l1.4-4.1A8 8 0 1 1 8.6 19L4 20z"/><path d="M9 10c0 3 2 5 5 5"/></svg>
          </a>
        </div>
      </div>
    </div>

    <div class="site-footer__bottom">
      <span>© <?= date('Y') ?> Webservice Studio. Всі права захищені.</span>
      <a href="/polityka-konfidentsiynosti">Політика конфіденційності</a>
    </div>
  </div>
</footer>
</body>
</html>
