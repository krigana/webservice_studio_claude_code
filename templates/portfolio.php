<?php
$pageTitle = 'Портфоліо — Webservice Studio';
$pageDescription = "Приклади робіт студії: сайти, лендинги, застосунки, рішення для арбітражу трафіку.";
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
<main class="container section">
  <h1>Портфоліо</h1>

  <div style="display:flex; flex-wrap:wrap; gap:10px; margin:20px 0;">
    <a href="/portfolio" style="padding:9px 16px; border-radius:999px; font-size:13.5px; font-weight:600; border:1.5px solid var(--color-border); color:<?= $activeCategory ? 'var(--color-muted)' : '#fff' ?>; background:<?= $activeCategory ? 'transparent' : 'var(--color-ink)' ?>;">Усі</a>
    <?php foreach ($categories as $cat): ?>
      <a href="/portfolio?category=<?= urlencode($cat['slug']) ?>" style="padding:9px 16px; border-radius:999px; font-size:13.5px; font-weight:600; border:1.5px solid var(--color-border); color:<?= ($activeCategory && $activeCategory['slug'] === $cat['slug']) ? '#fff' : 'var(--color-muted)' ?>; background:<?= ($activeCategory && $activeCategory['slug'] === $cat['slug']) ? 'var(--color-ink)' : 'transparent' ?>;"><?= h($cat['name']) ?></a>
    <?php endforeach; ?>
  </div>

  <?php if (empty($cases)): ?>
    <p style="color:var(--color-muted);">У цій категорії поки немає кейсів.</p>
  <?php else: ?>
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(260px, 1fr)); gap:20px;">
      <?php foreach ($cases as $case): ?>
        <a href="/portfolio/<?= h($case['slug']) ?>" style="display:block; border:1px solid var(--color-border); border-radius:16px; overflow:hidden; color:var(--color-ink);">
          <img src="<?= h($case['cover_image']) ?>" alt="<?= h($case['title']) ?>" style="width:100%; height:170px; object-fit:cover; display:block; background:var(--color-tint-2);">
          <div style="padding:18px;"><strong><?= h($case['title']) ?></strong></div>
        </a>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
