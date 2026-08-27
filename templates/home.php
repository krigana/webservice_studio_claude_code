<?php
$pageTitle = 'Webservice Studio — розробка сайтів, застосунків та арбітраж трафіку';
$pageDescription = 'Веб-студія: сайти, лендинги, застосунки під Android, послуги для арбітражу трафіку, адміністрування доменів.';

$categories = ServiceCategory::published();
$latestPosts = BlogPost::publishedList(3);
$latestCases = PortfolioCase::publishedList();
$latestCases = array_slice($latestCases, 0, 3);

require __DIR__ . '/partials/header.php';
?>
<main>
  <div class="container section">
    <h1>Сайти й інструменти для трафіку, які приносять результат</h1>
    <p style="max-width:560px; color:var(--color-muted);">Розробка сайтів, застосунків та рішень для арбітражу трафіку — під ключ, з фокусом на результат.</p>
    <a href="/kontakty" class="btn-primary">Обговорити проєкт</a>
  </div>

  <?php if (!empty($categories)): ?>
  <div class="container section">
    <h2>Послуги</h2>
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(220px, 1fr)); gap:16px;">
      <?php foreach ($categories as $cat): ?>
        <a href="/poslugy#<?= h($cat['slug']) ?>" style="display:block; padding:20px; border:1px solid var(--color-border); border-radius:14px; color:var(--color-ink); font-weight:600;">
          <?= h($cat['name']) ?>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

  <?php if (!empty($latestCases)): ?>
  <div class="container section">
    <h2>Останні роботи</h2>
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(260px, 1fr)); gap:20px;">
      <?php foreach ($latestCases as $case): ?>
        <a href="/portfolio/<?= h($case['slug']) ?>" style="display:block; border:1px solid var(--color-border); border-radius:16px; overflow:hidden; color:var(--color-ink);">
          <img src="<?= h($case['cover_image']) ?>" alt="<?= h($case['title']) ?>" style="width:100%; height:160px; object-fit:cover; display:block; background:var(--color-tint-2);">
          <div style="padding:16px;"><strong><?= h($case['title']) ?></strong></div>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

  <?php if (!empty($latestPosts)): ?>
  <div class="container section">
    <h2>Останні статті блогу</h2>
    <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(260px, 1fr)); gap:20px;">
      <?php foreach ($latestPosts as $post): ?>
        <a href="/blog/<?= h($post['slug']) ?>" style="display:block; border:1px solid var(--color-border); border-radius:16px; padding:20px; color:var(--color-ink);">
          <strong><?= h($post['title']) ?></strong>
          <p style="color:var(--color-muted); font-size:14px; margin-top:8px;"><?= h($post['excerpt'] ?? '') ?></p>
        </a>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
