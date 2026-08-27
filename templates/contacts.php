<?php
$pageTitle = 'Контакти — Webservice Studio';
require __DIR__ . '/partials/header.php';
?>
<main class="container section">
  <h1>Контакти</h1>
  <form method="post" action="/kontakty" style="max-width:480px; display:flex; flex-direction:column; gap:12px;">
    <input type="text" name="name" placeholder="Ім'я" required>
    <input type="text" name="contact" placeholder="Email / телефон" required>
    <textarea name="message" placeholder="Повідомлення" rows="4"></textarea>
    <!-- honeypot-поле проти спам-ботів -->
    <input type="text" name="website" style="display:none" tabindex="-1" autocomplete="off">
    <button type="submit" class="btn-primary">Надіслати</button>
  </form>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
