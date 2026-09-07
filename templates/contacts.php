<?php
$pageTitle = 'Контакти — Webservice Studio';
$pageDescription = 'Зв\'яжіться з нами: залиште заявку і ми відповімо протягом дня.';
$activeNav = 'contacts';
$breadcrumbs = [
    ['name' => 'Головна', 'url' => '/'],
    ['name' => 'Контакти', 'url' => '/kontakty'],
];

$services = Service::publishedAll();
$preselectSlug = $_GET['service'] ?? null;
$sent = isset($_GET['sent']);

$contactEmail = Setting::get('contact_email', 'support@web-service.studio');
$contactPhoneDisplay = Setting::get('contact_phone_display', '+380 95 921 22 03');
$contactWhatsapp = Setting::get('contact_whatsapp', 'https://api.whatsapp.com/send/?phone=380959212203');
$contactTelegram = Setting::get('contact_telegram', 'https://t.me/webservices_studio');
$contactTelegramHandle = Setting::get('contact_telegram_handle', '@webservices_studio');
$contactFacebook = Setting::get('contact_facebook', 'https://www.facebook.com/webservicestudio/');
$contactInstagram = Setting::get('contact_instagram', 'https://www.instagram.com/webservicestudio/');
$heroTitle = Setting::get('contacts_hero_title', 'Розкажіть про свій проєкт');
$heroSubtitle = Setting::get('contacts_hero_subtitle', 'Заповніть форму або напишіть напряму — відповідаємо протягом дня.');

$recaptchaEnabled = Recaptcha::isEnabled($GLOBALS['config']);
$recaptchaSiteKey = $recaptchaEnabled ? Recaptcha::siteKey($GLOBALS['config']) : '';

require __DIR__ . '/partials/header.php';
if ($recaptchaEnabled) {
    echo '<script src="https://www.google.com/recaptcha/api.js?hl=uk" async defer></script>' . "\n";
}
?>
<main>
  <div class="hero" style="padding-bottom:24px;">
    <div class="container">
      <span class="eyebrow">Контакти</span>
      <h1 style="max-width:620px;"><?= h($heroTitle) ?></h1>
      <p class="lead"><?= h($heroSubtitle) ?></p>
    </div>
  </div>

  <div class="container contacts-layout">

    <div class="contacts-card">
      <?php if ($sent): ?>
        <div style="background:#EAF7FA; color:var(--color-ink); padding:16px 20px; border-radius:12px; margin-bottom:20px;">
          Дякуємо! Заявку отримано, ми скоро зв'яжемось з вами.
        </div>
      <?php endif; ?>
      <?php if ($error = flash_get('contact_error')): ?>
        <div style="background:#FDEAEA; color:#7A1F1F; padding:16px 20px; border-radius:12px; margin-bottom:20px;">
          <?= h($error) ?>
        </div>
      <?php endif; ?>

      <form method="post" action="/kontakty">
        <?= csrf_field() ?>
        <div class="field">
          <label>Ім'я</label>
          <input type="text" name="name" placeholder="Як до вас звертатись" required maxlength="150">
        </div>
        <div class="field">
          <label>Контакт (email, телефон або месенджер)</label>
          <input type="text" name="contact" placeholder="you@example.com" required maxlength="190">
        </div>
        <div class="field">
          <label>Яка послуга цікавить</label>
          <select name="service_id">
            <option value="">Не визначились (необов'язково)</option>
            <?php foreach ($services as $s): ?>
              <option value="<?= (int) $s['id'] ?>" <?= ($preselectSlug === $s['slug']) ? 'selected' : '' ?>><?= h($s['category_name']) ?> — <?= h($s['title']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="field">
          <label>Повідомлення</label>
          <textarea name="message" placeholder="Коротко опишіть задачу" rows="4" maxlength="2000"></textarea>
        </div>
        <!-- honeypot-поле проти спам-ботів -->
        <input type="text" name="website" style="position:absolute; left:-9999px;" tabindex="-1" autocomplete="off">
        <?php if ($recaptchaEnabled): ?>
          <div class="field">
            <div class="g-recaptcha" data-sitekey="<?= h($recaptchaSiteKey) ?>"></div>
          </div>
        <?php endif; ?>
        <button type="submit" class="btn-primary block">Надіслати заявку
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </button>
        <p style="font-size:12.5px; color:var(--color-faint); margin-top:14px; text-align:center;">Заявка одразу потрапляє нашій команді в Telegram. Надсилаючи форму, ви погоджуєтесь із <a href="/polityka-konfidentsiynosti">політикою конфіденційності</a>.</p>
      </form>
    </div>

    <div style="flex:1 1 260px; display:flex; flex-direction:column; gap:32px;">
      <div style="display:flex; align-items:center; gap:14px;">
        <span class="icon-badge"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="5" width="18" height="14" rx="2.5"/><path d="M3 7l9 6 9-6"/></svg></span>
        <div>
          <p style="font-size:12px; font-weight:700; color:var(--color-faint); text-transform:uppercase; letter-spacing:0.06em; margin-bottom:4px;">Email</p>
          <a href="mailto:<?= h($contactEmail) ?>" style="font-size:16px; font-weight:600; color:var(--color-ink);"><?= h($contactEmail) ?></a>
        </div>
      </div>
      <div style="display:flex; align-items:center; gap:14px;">
        <span class="icon-badge"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 20l1.4-4.1A8 8 0 1 1 8.6 19L4 20z"/><path d="M9 10c0 3 2 5 5 5"/></svg></span>
        <div>
          <p style="font-size:12px; font-weight:700; color:var(--color-faint); text-transform:uppercase; letter-spacing:0.06em; margin-bottom:4px;">WhatsApp / Телефон</p>
          <a href="<?= h($contactWhatsapp) ?>" target="_blank" rel="noopener" style="font-size:16px; font-weight:600; color:var(--color-ink);"><?= h($contactPhoneDisplay) ?></a>
        </div>
      </div>
      <div style="display:flex; align-items:center; gap:14px;">
        <span class="icon-badge"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M21 4L3 11l6 2.5M21 4l-3 16-8-6.5M21 4L9 13.5v5.5l3-3.5"/></svg></span>
        <div>
          <p style="font-size:12px; font-weight:700; color:var(--color-faint); text-transform:uppercase; letter-spacing:0.06em; margin-bottom:4px;">Telegram</p>
          <a href="<?= h($contactTelegram) ?>" target="_blank" rel="noopener" style="font-size:16px; font-weight:600; color:var(--color-ink);"><?= h($contactTelegramHandle) ?></a>
        </div>
      </div>

      <div style="height:1px; background:var(--color-border);"></div>

      <div>
        <p style="font-size:12px; font-weight:700; color:var(--color-faint); text-transform:uppercase; letter-spacing:0.06em; margin-bottom:14px;">Ми в соцмережах</p>
        <div style="display:flex; gap:10px;">
          <a href="<?= h($contactFacebook) ?>" target="_blank" rel="noopener" style="width:44px; height:44px; border-radius:12px; background:var(--color-surface); border:1px solid var(--color-border); display:flex; align-items:center; justify-content:center;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--color-ink)" stroke-width="2"><path d="M14 9h3V6h-3c-2.2 0-4 1.8-4 4v2H8v3h2v6h3v-6h3l1-3h-4v-2c0-.6.4-1 1-1z"/></svg>
          </a>
          <a href="<?= h($contactInstagram) ?>" target="_blank" rel="noopener" style="width:44px; height:44px; border-radius:12px; background:var(--color-surface); border:1px solid var(--color-border); display:flex; align-items:center; justify-content:center;">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="var(--color-ink)" stroke-width="2"><rect x="4" y="4" width="16" height="16" rx="4"/><circle cx="12" cy="12" r="3.5"/><circle cx="16.8" cy="7.2" r="0.6" fill="var(--color-ink)" stroke="none"/></svg>
          </a>
        </div>
      </div>
    </div>
  </div>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
