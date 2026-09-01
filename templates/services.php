<?php
$pageTitle = 'Послуги — Webservice Studio';
$pageDescription = 'Розробка сайтів, застосунки під Android, арбітраж трафіку, адміністрування доменів та сайтів.';
$activeNav = 'services';
$breadcrumbs = [
    ['name' => 'Головна', 'url' => '/'],
    ['name' => 'Послуги', 'url' => '/poslugy'],
];

$categoryIcons = [
    'rozrobka-saitiv' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.5 2.6 3.8 5.7 3.8 9s-1.3 6.4-3.8 9c-2.5-2.6-3.8-5.7-3.8-9s1.3-6.4 3.8-9z"/></svg>',
    'android-dodatky' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="6" y="3" width="12" height="18" rx="2.5"/><path d="M11 18h2"/></svg>',
    'arbitrazh-trafiku' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3l7 3v6c0 5-3 8-7 9-4-1-7-4-7-9V6l7-3z"/></svg>',
    'administruvannia' => '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="4" y="4" width="16" height="6" rx="1.5"/><rect x="4" y="14" width="16" height="6" rx="1.5"/><circle cx="7.5" cy="7" r="0.6" fill="currentColor" stroke="none"/><circle cx="7.5" cy="17" r="0.6" fill="currentColor" stroke="none"/></svg>',
];

$categories = ServiceCategory::published();
$categoryServices = [];
$allServicesForSchema = [];
foreach ($categories as $cat) {
    $categoryServices[$cat['id']] = Service::publishedByCategory((int) $cat['id']);
    array_push($allServicesForSchema, ...$categoryServices[$cat['id']]);
}
if (!empty($allServicesForSchema)) {
    $extraSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'ItemList',
        'itemListElement' => array_map(static function (array $s, int $i): array {
            $item = [
                '@type' => 'Service',
                'name' => $s['title'],
                'provider' => ['@type' => 'Organization', 'name' => 'Webservice Studio'],
            ];
            if (!empty($s['description'])) {
                $item['description'] = $s['description'];
            }
            return ['@type' => 'ListItem', 'position' => $i + 1, 'item' => $item];
        }, $allServicesForSchema, array_keys($allServicesForSchema)),
    ];
}

require __DIR__ . '/partials/header.php';
?>
<main>
  <div class="hero">
    <div class="container">
      <span class="eyebrow">Послуги</span>
      <h1>Від сайту до інструментів для трафіку — все під одним дахом</h1>
      <p class="lead">Чотири напрямки роботи студії. Орієнтовні ціни — на сторінці «Ціни», фінальну вартість погоджуємо після короткого брифу.</p>
    </div>
  </div>

  <?php if (empty($categories)): ?>
    <div class="container section"><p style="color:var(--color-muted);">Розділ послуг поки порожній.</p></div>
  <?php endif; ?>

  <?php foreach ($categories as $i => $cat): $services = $categoryServices[$cat['id']]; ?>
    <div class="<?= $i % 2 === 1 ? 'section' : '' ?>" style="<?= $i % 2 === 1 ? 'background:var(--color-surface);' : '' ?>">
      <div class="container" style="padding-top:40px; padding-bottom:40px;">
        <div style="display:flex; align-items:center; gap:14px; margin-bottom:32px;">
          <span class="icon-badge <?= $i % 2 === 1 ? 'bordered' : '' ?>"><?= $categoryIcons[$cat['slug']] ?? '' ?></span>
          <h2 id="<?= h($cat['slug']) ?>" style="font-size:26px; font-weight:800; scroll-margin-top:100px;"><?= h($cat['name']) ?></h2>
        </div>
        <?php if (empty($services)): ?>
          <p style="color:var(--color-faint); font-size:14px;">Послуги цієї категорії скоро з'являться.</p>
        <?php else: ?>
          <div class="grid-3">
            <?php foreach ($services as $service): ?>
              <div class="service-card" style="<?= $i % 2 === 1 ? 'background:#FFFFFF;' : '' ?>">
                <h3><?= h($service['title']) ?></h3>
                <?php if (!empty($service['description'])): ?>
                  <p><?= h($service['description']) ?></p>
                <?php endif; ?>
                <a href="/tsiny#service-<?= (int) $service['id'] ?>" class="order-link">Дізнатись ціну
                  <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </a>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  <?php endforeach; ?>

  <div class="section">
    <div class="container">
      <div class="cta-band">
        <div>
          <h2>Не знайшли потрібну послугу?</h2>
          <p>Опишіть задачу — підберемо рішення навіть під нестандартний запит.</p>
        </div>
        <a href="/kontakty" class="btn-primary accent" style="flex-shrink:0;">Написати нам
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </a>
      </div>
    </div>
  </div>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
