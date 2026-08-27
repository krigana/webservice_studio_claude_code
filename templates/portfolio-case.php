<?php
$pageTitle = 'Кейс — Webservice Studio';
require __DIR__ . '/partials/header.php';
?>
<main class="container section">
  <h1>Картка кейса</h1>
  <p style="color:var(--color-muted);">Деталі проєкту (slug: <?= htmlspecialchars($params['slug'] ?? '') ?>) з'являться тут.</p>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
