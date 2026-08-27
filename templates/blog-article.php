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

$tags = BlogPost::tagsFor((int) $post['id']);
$related = BlogPost::related((int) $post['id'], $post['category_id'] ? (int) $post['category_id'] : null);

require __DIR__ . '/partials/header.php';
?>
<main class="container section">
  <p style="color:var(--color-faint); font-size:13.5px;"><a href="/blog">Блог</a> <?= !empty($post['category_name']) ? '/ ' . h($post['category_name']) : '' ?></p>
  <h1><?= h($post['title']) ?></h1>
  <p style="color:var(--color-faint); font-size:13.5px; margin:12px 0 24px;"><?= h(date('d.m.Y', strtotime($post['published_at']))) ?></p>

  <?php if (!empty($post['cover_image'])): ?>
    <img src="<?= h($post['cover_image']) ?>" alt="<?= h($post['title']) ?>" style="width:100%; max-height:420px; object-fit:cover; border-radius:20px; margin-bottom:32px; background:var(--color-tint-2);">
  <?php endif; ?>

  <div style="max-width:760px; font-size:16px; line-height:1.75; color:var(--color-ink-soft);">
    <?= $post['content'] /* HTML из WYSIWYG-редактора, экранирование не нужно */ ?>
  </div>

  <?php if (!empty($tags)): ?>
    <div style="display:flex; gap:8px; flex-wrap:wrap; margin-top:32px;">
      <?php foreach ($tags as $tag): ?>
        <span style="font-size:12.5px; padding:6px 12px; border-radius:999px; background:var(--color-tint); color:var(--color-brand);">#<?= h($tag['name']) ?></span>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($related)): ?>
    <div style="margin-top:56px; padding-top:32px; border-top:1px solid var(--color-border);">
      <h2 style="font-size:20px; margin-bottom:20px;">Читайте також</h2>
      <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:16px;">
        <?php foreach ($related as $r): ?>
          <a href="/blog/<?= h($r['slug']) ?>" style="display:block; border:1px solid var(--color-border); border-radius:14px; padding:16px; color:var(--color-ink); font-weight:600; font-size:14px;"><?= h($r['title']) ?></a>
        <?php endforeach; ?>
      </div>
    </div>
  <?php endif; ?>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
