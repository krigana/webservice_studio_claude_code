<?php
$pageTitle = 'Блог — Webservice Studio';
$pageDescription = 'Статті про розробку, SEO та арбітраж трафіку від команди Webservice Studio.';
$breadcrumbs = [
    ['name' => 'Головна', 'url' => '/'],
    ['name' => 'Блог', 'url' => '/blog'],
];

$perPage = 9;
$page = max(1, (int) ($_GET['page'] ?? 1));
$total = BlogPost::publishedCount();
$totalPages = (int) max(1, ceil($total / $perPage));
$page = min($page, $totalPages);
$posts = BlogPost::publishedList($perPage, ($page - 1) * $perPage);

require __DIR__ . '/partials/header.php';
?>
<main class="container section">
  <h1>Блог</h1>

  <?php if (empty($posts)): ?>
    <p style="color:var(--color-muted);">Статей поки немає — перша скоро з'явиться.</p>
  <?php else: ?>
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(260px, 1fr)); gap:20px; margin-top:24px;">
      <?php foreach ($posts as $post): ?>
        <a href="/blog/<?= h($post['slug']) ?>" style="display:block; border:1px solid var(--color-border); border-radius:16px; overflow:hidden; color:var(--color-ink);">
          <?php if (!empty($post['cover_image'])): ?>
            <img src="<?= h($post['cover_image']) ?>" alt="<?= h($post['title']) ?>" style="width:100%; height:170px; object-fit:cover; display:block; background:var(--color-tint-2);">
          <?php endif; ?>
          <div style="padding:20px;">
            <?php if (!empty($post['category_name'])): ?>
              <span style="font-size:11.5px; font-weight:700; color:var(--color-brand); text-transform:uppercase;"><?= h($post['category_name']) ?></span>
            <?php endif; ?>
            <h3 style="font-size:16px; margin:8px 0;"><?= h($post['title']) ?></h3>
            <span style="font-size:12.5px; color:var(--color-faint);"><?= h(date('d.m.Y', strtotime($post['published_at']))) ?></span>
          </div>
        </a>
      <?php endforeach; ?>
    </div>

    <?php if ($totalPages > 1): ?>
      <div style="display:flex; gap:8px; justify-content:center; margin-top:40px;">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
          <a href="/blog?page=<?= $i ?>" style="width:34px; height:34px; display:flex; align-items:center; justify-content:center; border-radius:9px; border:1px solid var(--color-border); font-size:13.5px; font-weight:600; color:<?= $i === $page ? '#fff' : 'var(--color-muted)' ?>; background:<?= $i === $page ? 'var(--color-ink)' : 'transparent' ?>;"><?= $i ?></a>
        <?php endfor; ?>
      </div>
    <?php endif; ?>
  <?php endif; ?>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
