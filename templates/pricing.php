<?php
$pageTitle = 'Ціни — Webservice Studio';
$pageDescription = 'Орієнтовна вартість послуг веб-студії: розробка сайтів, Android-застосунків, арбітраж трафіку, адміністрування.';

$services = Service::publishedAll();
$byCategory = [];
foreach ($services as $s) {
    $byCategory[$s['category_name']][] = $s;
}

require __DIR__ . '/partials/header.php';
?>
<main class="container section">
  <h1>Ціни</h1>
  <p style="color:var(--color-muted); max-width:560px;">Орієнтовні тарифи. Для точного розрахунку — залиште заявку, ми уточнимо деталі проєкту.</p>

  <?php if (empty($byCategory)): ?>
    <p style="color:var(--color-muted);">Розділ цін поки порожній.</p>
  <?php endif; ?>

  <?php foreach ($byCategory as $categoryName => $items): ?>
    <section style="margin-top:40px;">
      <h2><?= h($categoryName) ?></h2>
      <div style="display:flex; flex-direction:column; gap:12px;">
        <?php foreach ($items as $service): ?>
          <div style="display:flex; align-items:center; justify-content:space-between; gap:16px; padding:16px 20px; border:1px solid var(--color-border); border-radius:14px;">
            <span style="font-weight:600;"><?= h($service['title']) ?></span>
            <span style="color:var(--color-brand); font-weight:700; white-space:nowrap;">
              <?= format_price($service['price_from'], $service['price_to'], $service['price_note'], $service['currency']) ?>
            </span>
          </div>
        <?php endforeach; ?>
      </div>
    </section>
  <?php endforeach; ?>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
