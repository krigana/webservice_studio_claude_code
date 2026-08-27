<?php
$slug = $params['slug'] ?? '';
$case = PortfolioCase::bySlugPublished($slug);

if ($case === null) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    return;
}

$pageTitle = ($case['seo_title'] ?: $case['title']) . ' — Webservice Studio';
$pageDescription = $case['seo_description'] ?: mb_substr(strip_tags((string) $case['description']), 0, 300);
$pageImage = $case['cover_image'];

$images = PortfolioCase::images((int) $case['id']);
$next = PortfolioCase::nextCase((int) $case['id']);

require __DIR__ . '/partials/header.php';
?>
<main class="container section">
  <p style="color:var(--color-faint); font-size:13.5px;"><a href="/portfolio">Роботи</a> / <?= h($case['title']) ?></p>
  <h1><?= h($case['title']) ?></h1>
  <?php if (!empty($case['description'])): ?>
    <p style="color:var(--color-muted); max-width:720px; white-space:pre-line;"><?= h($case['description']) ?></p>
  <?php endif; ?>

  <img src="<?= h($case['cover_image']) ?>" alt="<?= h($case['title']) ?>" style="width:100%; max-height:480px; object-fit:cover; border-radius:20px; margin:24px 0; background:var(--color-tint-2);">

  <?php if (!empty($case['project_url'])): ?>
    <p><a href="<?= h($case['project_url']) ?>" target="_blank" rel="noopener">Переглянути проєкт →</a></p>
  <?php endif; ?>

  <?php if (!empty($images)): ?>
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:16px; margin-top:24px;">
      <?php foreach ($images as $img): ?>
        <img src="<?= h($img['image_path']) ?>" alt="" style="width:100%; aspect-ratio:3/4; object-fit:cover; border-radius:14px; background:var(--color-tint);">
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <?php if ($next && $next['id'] != $case['id']): ?>
    <div style="margin-top:56px; padding-top:24px; border-top:1px solid var(--color-border);">
      <a href="/portfolio/<?= h($next['slug']) ?>" style="display:flex; justify-content:space-between; align-items:center; color:var(--color-ink);">
        <span>Наступний проєкт: <strong><?= h($next['title']) ?></strong></span>
        <span>→</span>
      </a>
    </div>
  <?php endif; ?>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
