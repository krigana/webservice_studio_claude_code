<?php
$slug = $params['slug'] ?? '';
$page = Page::bySlug($slug);

if ($page === null) {
    http_response_code(404);
    require __DIR__ . '/404.php';
    return;
}

$pageTitle = ($page['seo_title'] ?: $page['title']) . ' — Webservice Studio';
$pageDescription = $page['seo_description'] ?: mb_substr(strip_tags($page['content']), 0, 300);
// Підсвітити пункт меню, якщо сторінка з таблиці pages відповідає одному з
// пунктів навігації (наразі лише «Про студію» — решта such сторінок у меню нема).
$activeNav = $slug === 'pro-studiyu' ? 'about' : '';
$breadcrumbs = [
    ['name' => 'Головна', 'url' => '/'],
    ['name' => $page['title']],
];

require __DIR__ . '/partials/header.php';
?>
<main>
  <div class="container" style="max-width:800px; padding-top:40px;">
    <div class="breadcrumb">
      <a href="/">Головна</a>
      <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#7C99A1" stroke-width="2.4"><path d="M9 6l6 6-6 6"/></svg>
      <span><?= h($page['title']) ?></span>
    </div>
    <h1 style="font-size:32px; font-weight:800; margin-bottom:8px;"><?= h($page['title']) ?></h1>
    <p style="font-size:13.5px; color:var(--color-faint); margin-bottom:36px;">Оновлено: <?= h(date('d.m.Y', strtotime($page['updated_at']))) ?></p>
  </div>
  <div class="container prose" style="padding-bottom:80px;">
    <?= $page['content'] /* HTML из адмін-редактора, екранування не потрібне */ ?>
  </div>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
