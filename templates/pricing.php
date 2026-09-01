<?php
$pageTitle = 'Ціни — Webservice Studio';
$pageDescription = 'Орієнтовна вартість послуг веб-студії: розробка сайтів, Android-застосунків, арбітраж трафіку, адміністрування.';
$activeNav = 'pricing';
$breadcrumbs = [
    ['name' => 'Головна', 'url' => '/'],
    ['name' => 'Ціни', 'url' => '/tsiny'],
];

$services = Service::publishedAll();
$byCategory = [];
foreach ($services as $s) {
    $byCategory[$s['category_name']][] = $s;
}

require __DIR__ . '/partials/header.php';
?>
<main>
  <div class="hero" style="padding-bottom:24px;">
    <div class="container">
      <span class="eyebrow">Ціни</span>
      <h1>Орієнтовна вартість послуг</h1>
      <p class="lead">Ціни нижче — орієнтир по ринку. Фінальну вартість погоджуємо індивідуально після короткого брифу щодо задачі.</p>
    </div>
  </div>

  <?php if (empty($byCategory)): ?>
    <div class="container section"><p style="color:var(--color-muted);">Розділ цін поки порожній.</p></div>
  <?php endif; ?>

  <?php $catIndex = 0; foreach ($byCategory as $categoryName => $items): $catIndex++; ?>
    <div class="<?= $catIndex % 2 === 0 ? 'section' : '' ?>" style="<?= $catIndex % 2 === 0 ? 'background:var(--color-surface);' : '' ?>">
      <div class="container" style="padding-top:32px; padding-bottom:32px;">
        <h2 style="font-size:24px; font-weight:800; margin-bottom:28px;"><?= h($categoryName) ?></h2>
        <div class="grid-3">
          <?php foreach ($items as $i => $service): $accent = ($i % 3) === 1; ?>
            <div id="service-<?= (int) $service['id'] ?>" class="price-card <?= $accent ? 'accent' : '' ?>" style="scroll-margin-top:100px;">
              <div>
                <h3><?= h($service['title']) ?></h3>
                <span class="price-value"><?= format_price($service['price_from'], $service['price_to'], $service['price_note'], $service['currency']) ?></span>
              </div>
              <?php if (!empty($service['description'])): ?>
                <p style="font-size:14px; line-height:1.55; color:<?= $accent ? '#C9DEE3' : 'var(--color-muted)' ?>;"><?= h($service['description']) ?></p>
              <?php endif; ?>
              <a href="/kontakty?service=<?= urlencode($service['slug']) ?>" class="btn-primary <?= $accent ? 'accent' : '' ?>" style="justify-content:center;">Замовити</a>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  <?php endforeach; ?>

  <p class="container" style="margin-top:24px; font-size:13.5px; color:var(--color-faint);">* Ціни орієнтовні та уточнюються під час обговорення проєкту.</p>

  <div class="section">
    <div class="container">
      <div class="cta-band">
        <div>
          <h2>Хочете точний розрахунок?</h2>
          <p>Опишіть задачу у формі — надішлемо кошторис протягом дня.</p>
        </div>
        <a href="/kontakty" class="btn-primary accent" style="flex-shrink:0;">Отримати розрахунок
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </a>
      </div>
    </div>
  </div>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
