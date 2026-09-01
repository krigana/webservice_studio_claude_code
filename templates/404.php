<?php
$pageTitle = 'Сторінку не знайдено — Webservice Studio';
require __DIR__ . '/partials/header.php';
?>
<main>
  <div class="container" style="padding:96px 40px; text-align:center; display:flex; flex-direction:column; align-items:center; gap:20px;">
    <span style="font-family:var(--font-heading); font-weight:800; font-size:80px; color:var(--color-tint-2); line-height:1;">404</span>
    <h1 style="font-size:28px; font-weight:800;">Сторінку не знайдено</h1>
    <p style="color:var(--color-muted); max-width:420px;">Можливо, сторінку перенесли або її ніколи не існувало. Перевірте адресу або поверніться на головну.</p>
    <a href="/" class="btn-primary">На головну</a>
  </div>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
