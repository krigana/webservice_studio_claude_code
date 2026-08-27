<?php
$pageTitle = 'Послуги — Webservice Studio';
$pageDescription = 'Розробка сайтів, застосунки під Android, арбітраж трафіку, адміністрування доменів та сайтів.';

$categories = ServiceCategory::published();

require __DIR__ . '/partials/header.php';
?>
<main class="container section">
  <h1>Послуги</h1>

  <?php if (empty($categories)): ?>
    <p style="color:var(--color-muted);">Розділ послуг поки порожній.</p>
  <?php endif; ?>

  <?php foreach ($categories as $cat): ?>
    <?php $services = Service::publishedByCategory((int) $cat['id']); ?>
    <section id="<?= h($cat['slug']) ?>" style="margin-top:40px;">
      <h2><?= h($cat['name']) ?></h2>
      <?php if (empty($services)): ?>
        <p style="color:var(--color-faint); font-size:14px;">Послуги цієї категорії скоро з'являться.</p>
      <?php else: ?>
        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:16px;">
          <?php foreach ($services as $service): ?>
            <div style="border:1px solid var(--color-border); border-radius:14px; padding:20px;">
              <h3 style="font-size:16px; margin-bottom:8px;"><?= h($service['title']) ?></h3>
              <?php if (!empty($service['description'])): ?>
                <p style="color:var(--color-muted); font-size:14px; margin-bottom:12px;"><?= h($service['description']) ?></p>
              <?php endif; ?>
              <a href="/kontakty?service=<?= urlencode($service['slug']) ?>" class="btn-primary" style="font-size:13px; padding:10px 18px;">Замовити</a>
            </div>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </section>
  <?php endforeach; ?>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
