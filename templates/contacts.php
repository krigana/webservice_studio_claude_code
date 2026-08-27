<?php
$pageTitle = 'Контакти — Webservice Studio';
$pageDescription = 'Зв\'яжіться з нами: залиште заявку і ми відповімо протягом дня.';

$services = Service::publishedAll();
$preselectSlug = $_GET['service'] ?? null;
$sent = isset($_GET['sent']);

require __DIR__ . '/partials/header.php';
?>
<main class="container section">
  <h1>Контакти</h1>

  <?php if ($sent): ?>
    <div style="background:#EAF7FA; color:#0B1F26; padding:16px 20px; border-radius:12px; margin:20px 0; max-width:480px;">
      Дякуємо! Заявку отримано, ми скоро зв'яжемось з вами.
    </div>
  <?php endif; ?>
  <?php if ($error = flash_get('contact_error')): ?>
    <div style="background:#FDEAEA; color:#7A1F1F; padding:16px 20px; border-radius:12px; margin:20px 0; max-width:480px;">
      <?= h($error) ?>
    </div>
  <?php endif; ?>

  <form method="post" action="/kontakty" style="max-width:480px; display:flex; flex-direction:column; gap:12px; margin-top:20px;">
    <?= csrf_field() ?>
    <input type="text" name="name" placeholder="Ім'я" required maxlength="150">
    <input type="text" name="contact" placeholder="Email / телефон / месенджер" required maxlength="190">
    <select name="service_id">
      <option value="">Яка послуга цікавить (необов'язково)</option>
      <?php foreach ($services as $s): ?>
        <option value="<?= (int) $s['id'] ?>" <?= ($preselectSlug === $s['slug']) ? 'selected' : '' ?>><?= h($s['category_name']) ?> — <?= h($s['title']) ?></option>
      <?php endforeach; ?>
    </select>
    <textarea name="message" placeholder="Повідомлення" rows="4" maxlength="2000"></textarea>
    <!-- honeypot-поле проти спам-ботів -->
    <input type="text" name="website" style="position:absolute; left:-9999px;" tabindex="-1" autocomplete="off">
    <button type="submit" class="btn-primary">Надіслати</button>
  </form>

  <div style="margin-top:48px; display:flex; flex-direction:column; gap:8px; font-size:14.5px;">
    <a href="mailto:support@webservice.studio">support@webservice.studio</a>
    <a href="https://api.whatsapp.com/send/?phone=380959212203" target="_blank" rel="noopener">WhatsApp: +380 95 921 22 03</a>
    <a href="https://t.me/webservices_studio" target="_blank" rel="noopener">Telegram: @webservices_studio</a>
    <a href="https://www.facebook.com/webservicestudio/" target="_blank" rel="noopener">Facebook</a>
    <a href="https://www.instagram.com/webservicestudio/" target="_blank" rel="noopener">Instagram</a>
  </div>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
