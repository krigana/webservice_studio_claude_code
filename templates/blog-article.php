<?php
$pageTitle = 'Стаття — Webservice Studio';
require __DIR__ . '/partials/header.php';
?>
<main class="container section">
  <h1>Стаття блогу</h1>
  <p style="color:var(--color-muted);">Текст статті (slug: <?= htmlspecialchars($params['slug'] ?? '') ?>) з'явиться тут.</p>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
