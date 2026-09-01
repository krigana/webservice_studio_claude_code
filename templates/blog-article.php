<?php
$slug = $params['slug'] ?? '';
$post = BlogPost::bySlugPublished($slug);

if ($post === null) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    return;
}

$pageTitle = ($post['seo_title'] ?: $post['title']) . ' — Webservice Studio';
$pageDescription = $post['seo_description'] ?: ($post['excerpt'] ?: mb_substr(strip_tags($post['content']), 0, 300));
$pageImage = $post['og_image'] ?: $post['cover_image'];
$activeNav = 'blog';
$breadcrumbs = [
    ['name' => 'Головна', 'url' => '/'],
    ['name' => 'Блог', 'url' => '/blog'],
    ['name' => $post['title']],
];

// Article JSON-LD (ТЗ, розділ 5) — siteUrl тут обчислюємо так само, як у header.php,
// оскільки $extraSchema потрібен ДО require header.php.
$articleScheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$articleSiteUrl = $articleScheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'web-service.studio');
$extraSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'Article',
    'headline' => $post['title'],
    'description' => $pageDescription,
    'datePublished' => $post['published_at'] ? date('c', strtotime($post['published_at'])) : null,
    'dateModified' => date('c', strtotime($post['updated_at'] ?? $post['published_at'])),
    'mainEntityOfPage' => $articleSiteUrl . '/blog/' . $post['slug'],
    'author' => ['@type' => 'Organization', 'name' => 'Webservice Studio'],
    'publisher' => [
        '@type' => 'Organization',
        'name' => 'Webservice Studio',
        'logo' => ['@type' => 'ImageObject', 'url' => $articleSiteUrl . '/assets/icons/icon-512.png'],
    ],
];
if (!empty($pageImage)) {
    $extraSchema['image'] = str_starts_with($pageImage, 'http') ? $pageImage : $articleSiteUrl . $pageImage;
}

$tags = BlogPost::tagsFor((int) $post['id']);
$related = BlogPost::related((int) $post['id'], $post['category_id'] ? (int) $post['category_id'] : null);

require __DIR__ . '/partials/header.php';
?>
<main>
  <div class="container" style="max-width:800px; padding-top:56px;">
    <div class="breadcrumb">
      <a href="/blog">Блог</a>
      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#7C99A1" stroke-width="2.4"><path d="M9 6l6 6-6 6"/></svg>
      <span><?= h($post['category_name'] ?? $post['title']) ?></span>
    </div>
    <?php if (!empty($post['category_name'])): ?>
      <span style="font-size:12.5px; font-weight:700; color:var(--color-brand); text-transform:uppercase; letter-spacing:0.1em;"><?= h($post['category_name']) ?></span>
    <?php endif; ?>
    <h1 style="font-size:36px; font-weight:800; margin:14px 0 18px; line-height:1.25;"><?= h($post['title']) ?></h1>
    <div style="display:flex; align-items:center; gap:12px; margin-bottom:40px;">
      <div style="width:36px; height:36px; border-radius:50%; background:var(--color-brand); display:flex; align-items:center; justify-content:center; color:#fff; font-family:var(--font-heading); font-weight:700; font-size:14px; flex-shrink:0;">WS</div>
      <div style="display:flex; flex-direction:column; line-height:1.3;">
        <span style="font-size:14px; font-weight:600; color:var(--color-ink);">Команда Webservice Studio</span>
        <span style="font-size:12.5px; color:var(--color-faint);"><?= h(date('d.m.Y', strtotime($post['published_at']))) ?> · <?= reading_time($post['content']) ?> хв читання</span>
      </div>
    </div>
  </div>

  <?php if (!empty($post['cover_image'])): ?>
    <div class="container" style="max-width:1000px;">
      <img src="<?= h($post['cover_image']) ?>" alt="<?= h($post['title']) ?>" class="thumb" style="width:100%; aspect-ratio:16/7; object-fit:cover;">
    </div>
  <?php endif; ?>

  <div class="container prose" style="padding-top:48px;">
    <?= $post['content'] /* HTML из WYSIWYG-редактора, экранирование не нужно */ ?>

    <?php if (!empty($tags)): ?>
      <div style="display:flex; gap:8px; flex-wrap:wrap; margin-top:32px;">
        <?php foreach ($tags as $tag): ?>
          <span style="font-size:12.5px; padding:6px 12px; border-radius:999px; background:var(--color-tint); color:var(--color-brand);">#<?= h($tag['name']) ?></span>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

  <?php if (!empty($related)): ?>
    <div class="section">
      <div class="container">
        <h2 style="font-size:24px; font-weight:800; margin-bottom:28px;">Читайте також</h2>
        <div class="grid-3">
          <?php foreach ($related as $r): ?>
            <a href="/blog/<?= h($r['slug']) ?>" class="card" style="overflow:hidden;">
              <?php if (!empty($r['cover_image'])): ?>
                <img src="<?= h($r['cover_image']) ?>" alt="<?= h($r['title']) ?>" style="width:100%; height:150px; object-fit:cover;">
              <?php else: ?>
                <div style="height:150px; background:var(--color-tint-2);"></div>
              <?php endif; ?>
              <div style="padding:20px; display:flex; flex-direction:column; gap:8px;">
                <?php if (!empty($r['category_name'])): ?>
                  <span style="font-size:11.5px; font-weight:700; color:var(--color-brand); text-transform:uppercase; letter-spacing:0.06em;"><?= h($r['category_name']) ?></span>
                <?php endif; ?>
                <h3 style="font-size:15px; font-weight:700; margin:0; color:var(--color-ink); line-height:1.35;"><?= h($r['title']) ?></h3>
              </div>
            </a>
          <?php endforeach; ?>
        </div>
      </div>
    </div>
  <?php endif; ?>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
