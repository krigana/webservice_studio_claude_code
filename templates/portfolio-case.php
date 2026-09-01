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
$activeNav = 'portfolio';
$breadcrumbs = [
    ['name' => 'Головна', 'url' => '/'],
    ['name' => 'Портфоліо', 'url' => '/portfolio'],
    ['name' => $case['title']],
];

$images = PortfolioCase::images((int) $case['id']);
$next = PortfolioCase::nextCase((int) $case['id']);

require __DIR__ . '/partials/header.php';
?>
<main>
  <div class="container" style="padding-top:40px;">
    <div class="breadcrumb">
      <a href="/portfolio">Роботи</a>
      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#7C99A1" stroke-width="2.4"><path d="M9 6l6 6-6 6"/></svg>
      <span><?= h($case['title']) ?></span>
    </div>
  </div>

  <div class="container" style="padding-bottom:40px; display:flex; align-items:flex-end; justify-content:space-between; gap:32px; flex-wrap:wrap;">
    <div style="display:flex; flex-direction:column; gap:14px; max-width:720px;">
      <?php if (!empty($case['category_name'])): ?>
        <span style="font-size:12.5px; font-weight:700; color:var(--color-brand); text-transform:uppercase; letter-spacing:0.1em;"><?= h($case['category_name']) ?></span>
      <?php endif; ?>
      <h1 style="font-size:38px; font-weight:800;"><?= h($case['title']) ?></h1>
      <?php if (!empty($case['description'])): ?>
        <p style="font-size:16px; line-height:1.6; color:var(--color-muted); white-space:pre-line;"><?= h($case['description']) ?></p>
      <?php endif; ?>
    </div>
  </div>

  <div class="container">
    <img src="<?= h($case['cover_image']) ?>" alt="<?= h($case['title']) ?>" class="thumb" style="width:100%; aspect-ratio:16/8; object-fit:cover;">
  </div>

  <?php if (!empty($case['project_url'])): ?>
    <div class="container" style="padding-top:24px;">
      <a href="<?= h($case['project_url']) ?>" target="_blank" rel="noopener" class="btn-ghost">Переглянути проєкт →</a>
    </div>
  <?php endif; ?>

  <?php if (!empty($images)): ?>
    <div class="container" style="padding:64px 0;">
      <div class="grid-3">
        <?php foreach ($images as $img): ?>
          <img src="<?= h($img['image_path']) ?>" alt="" class="thumb" style="width:100%; aspect-ratio:3/4;">
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif; ?>

  <?php if ($next && $next['id'] != $case['id']): ?>
    <div style="border-top:1px solid var(--color-border);">
      <a href="/portfolio/<?= h($next['slug']) ?>" class="container" style="display:flex; align-items:center; justify-content:space-between; padding-top:40px; padding-bottom:40px;">
        <div style="display:flex; flex-direction:column; gap:6px;">
          <span style="font-size:12.5px; font-weight:700; color:var(--color-faint); text-transform:uppercase; letter-spacing:0.08em;">Наступний проєкт</span>
          <span style="font-size:20px; font-weight:800; color:var(--color-ink);"><?= h($next['title']) ?></span>
        </div>
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="var(--color-ink)" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
      </a>
    </div>
  <?php endif; ?>

  <div class="section">
    <div class="container">
      <div class="cta-band">
        <div>
          <h2>Хочете подібний проєкт?</h2>
          <p>Розкажіть про свою задачу — запропонуємо рішення й орієнтовну вартість.</p>
        </div>
        <a href="/kontakty" class="btn-primary accent" style="flex-shrink:0;">Обговорити проєкт
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </a>
      </div>
    </div>
  </div>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
