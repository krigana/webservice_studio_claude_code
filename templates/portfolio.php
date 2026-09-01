<?php
$pageTitle = 'Портфоліо — Webservice Studio';
$pageDescription = "Приклади робіт студії: сайти, лендинги, застосунки, рішення для арбітражу трафіку.";
$activeNav = 'portfolio';
$breadcrumbs = [
    ['name' => 'Головна', 'url' => '/'],
    ['name' => 'Портфоліо', 'url' => '/portfolio'],
];

$categories = ServiceCategory::published();
$activeSlug = $_GET['category'] ?? null;
$activeCategory = null;
if ($activeSlug) {
    foreach ($categories as $c) {
        if ($c['slug'] === $activeSlug) {
            $activeCategory = $c;
            break;
        }
    }
}
$cases = PortfolioCase::publishedList($activeCategory ? (int) $activeCategory['id'] : null);

require __DIR__ . '/partials/header.php';
?>
<main>
  <div class="hero" style="padding-bottom:40px;">
    <div class="container">
      <span class="eyebrow">Наші роботи</span>
      <h1>Приклади проєктів студії</h1>
      <p class="lead">Сайти, застосунки та інструменти для трафіку, які ми зробили для клієнтів.</p>
    </div>
  </div>

  <div class="container">
    <div class="chip-row" style="padding:24px 0 40px;">
      <a href="/portfolio" class="chip <?= $activeCategory ? '' : 'active' ?>">Усі</a>
      <?php foreach ($categories as $cat): ?>
        <a href="/portfolio?category=<?= urlencode($cat['slug']) ?>" class="chip <?= ($activeCategory && $activeCategory['slug'] === $cat['slug']) ? 'active' : '' ?>"><?= h($cat['name']) ?></a>
      <?php endforeach; ?>
    </div>

    <?php if (empty($cases)): ?>
      <p style="color:var(--color-muted); padding-bottom:96px;">У цій категорії поки немає кейсів.</p>
    <?php else: ?>
      <div class="grid-3" style="padding-bottom:96px;">
        <?php foreach ($cases as $case): ?>
          <a href="/portfolio/<?= h($case['slug']) ?>">
            <img src="<?= h($case['cover_image']) ?>" alt="<?= h($case['title']) ?>" class="thumb" style="width:100%; aspect-ratio:4/3; margin-bottom:16px;">
            <span style="font-size:12px; font-weight:700; color:var(--color-brand); text-transform:uppercase; letter-spacing:0.06em;"><?= h($case['category_name'] ?? '') ?></span>
            <h3 style="font-size:17px; font-weight:700; margin-top:6px; color:var(--color-ink);"><?= h($case['title']) ?></h3>
          </a>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
