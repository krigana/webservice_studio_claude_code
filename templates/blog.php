<?php
$pageTitle = 'Блог — Webservice Studio';
$pageDescription = 'Статті про розробку, SEO та арбітраж трафіку від команди Webservice Studio.';
$activeNav = 'blog';
$breadcrumbs = [
    ['name' => 'Головна', 'url' => '/'],
    ['name' => 'Блог', 'url' => '/blog'],
];

$categories = BlogCategory::all('name ASC');
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
$activeCategoryId = $activeCategory ? (int) $activeCategory['id'] : null;

$perPage = 9;
$page = max(1, (int) ($_GET['page'] ?? 1));
$total = BlogPost::publishedCount($activeCategoryId);
$totalPages = (int) max(1, ceil($total / $perPage));
$page = min($page, $totalPages);
$posts = BlogPost::publishedList($perPage, ($page - 1) * $perPage, $activeCategoryId);

// На першій сторінці без фільтра першу статтю показуємо як featured-картку
$featured = null;
if ($page === 1 && !empty($posts)) {
    $featured = array_shift($posts);
}

if (!function_exists('blog_page_url')) {
    function blog_page_url(?string $category, int $page): string
    {
        $params = [];
        if ($category) {
            $params['category'] = $category;
        }
        if ($page > 1) {
            $params['page'] = $page;
        }
        return '/blog' . ($params ? '?' . http_build_query($params) : '');
    }
}

require __DIR__ . '/partials/header.php';
?>
<main>
  <div class="hero" style="padding-bottom:40px;">
    <div class="container">
      <span class="eyebrow">Блог</span>
      <h1>Статті про розробку, SEO та арбітраж трафіку</h1>
    </div>
  </div>

  <?php if (!empty($categories)): ?>
  <div class="container" style="padding-top:24px;">
    <div class="chip-row">
      <a href="/blog" class="chip <?= $activeCategory ? '' : 'active' ?>">Усі</a>
      <?php foreach ($categories as $cat): ?>
        <a href="/blog?category=<?= urlencode($cat['slug']) ?>" class="chip <?= ($activeCategory && $activeCategory['slug'] === $cat['slug']) ? 'active' : '' ?>"><?= h($cat['name']) ?></a>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

  <?php if (empty($posts) && empty($featured)): ?>
    <div class="container section"><p style="color:var(--color-muted);">Статей поки немає — перша скоро з'явиться.</p></div>
  <?php else: ?>

    <?php if ($featured): ?>
    <div class="container" style="padding-top:40px;">
      <a href="/blog/<?= h($featured['slug']) ?>" class="card" style="display:flex; align-items:stretch; overflow:hidden; flex-wrap:wrap;">
        <div style="flex:1 1 320px; min-height:220px;">
          <?php if (!empty($featured['cover_image'])): ?>
            <img src="<?= h($featured['cover_image']) ?>" alt="<?= h($featured['title']) ?>" style="width:100%; height:100%; object-fit:cover; display:block;">
          <?php else: ?>
            <div style="width:100%; height:100%; min-height:220px; background:var(--color-tint-2);"></div>
          <?php endif; ?>
        </div>
        <div style="flex:1 1 320px; padding:40px; display:flex; flex-direction:column; gap:14px; justify-content:center;">
          <?php if (!empty($featured['category_name'])): ?>
            <span style="font-size:12px; font-weight:700; color:var(--color-brand); text-transform:uppercase; letter-spacing:0.06em;"><?= h($featured['category_name']) ?></span>
          <?php endif; ?>
          <h2 style="font-size:26px; font-weight:800; color:var(--color-ink); line-height:1.3;"><?= h($featured['title']) ?></h2>
          <?php if (!empty($featured['excerpt'])): ?>
            <p style="font-size:14.5px; line-height:1.6; color:var(--color-muted);"><?= h($featured['excerpt']) ?></p>
          <?php endif; ?>
          <span style="font-size:13px; color:var(--color-faint);"><?= h(date('d.m.Y', strtotime($featured['published_at']))) ?> · <?= reading_time($featured['content']) ?> хв читання</span>
        </div>
      </a>
    </div>
    <?php endif; ?>

    <?php if (!empty($posts)): ?>
    <div class="container section">
      <div class="grid-3">
        <?php foreach ($posts as $post): ?>
          <a href="/blog/<?= h($post['slug']) ?>" class="card" style="overflow:hidden;">
            <?php if (!empty($post['cover_image'])): ?>
              <img src="<?= h($post['cover_image']) ?>" alt="<?= h($post['title']) ?>" style="width:100%; height:170px; object-fit:cover;">
            <?php else: ?>
              <div style="height:170px; background:var(--color-tint-2);"></div>
            <?php endif; ?>
            <div style="padding:22px; display:flex; flex-direction:column; gap:10px;">
              <?php if (!empty($post['category_name'])): ?>
                <span style="font-size:12px; font-weight:700; color:var(--color-brand); text-transform:uppercase; letter-spacing:0.06em;"><?= h($post['category_name']) ?></span>
              <?php endif; ?>
              <h3 style="font-size:16px; font-weight:700; margin:0; color:var(--color-ink);"><?= h($post['title']) ?></h3>
              <span style="font-size:12.5px; color:var(--color-faint);"><?= h(date('d.m.Y', strtotime($post['published_at']))) ?></span>
            </div>
          </a>
        <?php endforeach; ?>
      </div>

      <?php if ($totalPages > 1): ?>
        <div style="display:flex; align-items:center; justify-content:center; gap:8px; margin-top:56px;">
          <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="<?= h(blog_page_url($activeSlug, $i)) ?>" class="page-dot <?= $i === $page ? 'active' : '' ?>"><?= $i ?></a>
          <?php endfor; ?>
        </div>
      <?php endif; ?>
    </div>
    <?php endif; ?>

  <?php endif; ?>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
