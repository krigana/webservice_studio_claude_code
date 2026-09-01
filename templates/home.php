<?php
$pageTitle = 'Webservice Studio — розробка сайтів, застосунків та арбітраж трафіку';
$pageDescription = 'Веб-студія: сайти, лендинги, застосунки під Android, послуги для арбітражу трафіку, адміністрування доменів.';
$activeNav = 'home';

$categories = ServiceCategory::published();
$latestPosts = BlogPost::publishedList(3);
$latestCases = PortfolioCase::publishedList();
$latestCases = array_slice($latestCases, 0, 3);

// Іконки та короткі описи по слагу категорії — статичний маркетинговий текст
// (у service_categories немає поля description, і воно не потрібне з адмінки).
$categoryMeta = [
    'rozrobka-saitiv' => [
        'icon' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c2.5 2.6 3.8 5.7 3.8 9s-1.3 6.4-3.8 9c-2.5-2.6-3.8-5.7-3.8-9s1.3-6.4 3.8-9z"/></svg>',
        'desc' => 'Лендинги, корпоративні сайти й інтернет-магазини під ключ.',
    ],
    'android-dodatky' => [
        'icon' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="6" y="3" width="12" height="18" rx="2.5"/><path d="M11 18h2"/></svg>',
        'desc' => 'Мобільні застосунки під бізнес-задачі та продукти.',
    ],
    'arbitrazh-trafiku' => [
        'icon' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 3l7 3v6c0 5-3 8-7 9-4-1-7-4-7-9V6l7-3z"/></svg>',
        'desc' => 'Вайтпейдж, лендинги та клоакінг для арбітражних кампаній.',
    ],
    'administruvannia' => [
        'icon' => '<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="4" y="4" width="16" height="6" rx="1.5"/><rect x="4" y="14" width="16" height="6" rx="1.5"/><circle cx="7.5" cy="7" r="0.6" fill="currentColor" stroke="none"/><circle cx="7.5" cy="17" r="0.6" fill="currentColor" stroke="none"/></svg>',
        'desc' => 'Домени, сервери та технічна підтримка сайтів.',
    ],
];

$whyUs = [
    ['n' => '01', 'title' => 'Швидкий запуск', 'desc' => 'Перші результати вже на етапі прототипу, без довгих узгоджень.'],
    ['n' => '02', 'title' => 'Розуміємо нішу', 'desc' => 'Працюємо з вимогами арбітражу: клоакінг, вайтпейджі, партнерки.'],
    ['n' => '03', 'title' => 'Прозорі ціни', 'desc' => 'Вартість і склад пакета видно ще до старту робіт.'],
    ['n' => '04', 'title' => 'Підтримка після запуску', 'desc' => 'Домени, хостинг і сайт залишаються під наглядом і після здачі.'],
];

require __DIR__ . '/partials/header.php';
?>
<main>
  <div class="hero">
    <div class="container" style="display:flex; gap:64px; align-items:center;">
      <div style="flex:1 1 0; display:flex; flex-direction:column; gap:24px;">
        <span class="eyebrow" style="margin-bottom:0;">Веб-студія повного циклу</span>
        <h1 style="font-size:52px; line-height:1.1; font-weight:800; max-width:620px;">Сайти й інструменти для трафіку, які приносять результат</h1>
        <p style="font-size:18px; line-height:1.6; color:var(--color-muted); max-width:540px;">Розробляємо сайти, лендинги та Android-застосунки, налаштовуємо вайтпейджі й клоакінг для арбітражу, адмініструємо домени — від ідеї до підтримки після запуску.</p>
        <div style="display:flex; gap:14px; margin-top:8px; flex-wrap:wrap;">
          <a href="/kontakty" class="btn-primary">Обговорити проєкт
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
          </a>
          <a href="/portfolio" class="btn-ghost">Переглянути роботи</a>
        </div>
      </div>
      <div style="flex:1 1 0; display:flex; align-items:center; justify-content:center;" class="hero-visual">
        <div style="width:100%; aspect-ratio:4/3; border-radius:24px; background:var(--color-brand); position:relative; overflow:hidden; display:flex; align-items:center; justify-content:center;">
          <div style="position:absolute; inset:0; background:radial-gradient(circle at 78% 18%, rgba(255,255,255,0.22), transparent 55%);"></div>
          <img src="/assets/icons/logo-mark.png" alt="" style="width:34%; height:auto; opacity:0.9;">
        </div>
      </div>
    </div>
  </div>

  <?php if (!empty($categories)): ?>
  <div class="section">
    <div class="container">
      <div class="section-head">
        <div>
          <span class="eyebrow">Що ми робимо</span>
          <h2>Чотири напрямки — один підрядник</h2>
        </div>
        <a href="/poslugy" class="link-more">Усі послуги
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </a>
      </div>
      <div class="grid-4">
        <?php foreach ($categories as $cat): $meta = $categoryMeta[$cat['slug']] ?? null; ?>
          <a href="/poslugy#<?= h($cat['slug']) ?>" class="service-card">
            <span class="icon-badge"><?= $meta['icon'] ?? '' ?></span>
            <h3><?= h($cat['name']) ?></h3>
            <p><?= h($meta['desc'] ?? '') ?></p>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <div class="section" style="background:var(--color-surface);">
    <div class="container">
      <div style="margin-bottom:44px;">
        <span class="eyebrow">Чому саме ми</span>
        <h2 style="font-size:34px; font-weight:800; max-width:640px;">Розуміємо не тільки розробку, а й нішу арбітражу</h2>
      </div>
      <div class="grid-4">
        <?php foreach ($whyUs as $w): ?>
          <div style="display:flex; flex-direction:column; gap:14px;">
            <div style="width:40px; height:40px; border-radius:50%; background:var(--color-ink); color:#fff; display:flex; align-items:center; justify-content:center; font-family:var(--font-heading); font-weight:700; font-size:15px;"><?= h($w['n']) ?></div>
            <h3 style="font-size:16.5px; font-weight:700;"><?= h($w['title']) ?></h3>
            <p style="font-size:14px; line-height:1.55; color:var(--color-muted);"><?= h($w['desc']) ?></p>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <?php if (!empty($latestCases)): ?>
  <div class="section">
    <div class="container">
      <div class="section-head">
        <div>
          <span class="eyebrow">Роботи</span>
          <h2>Кілька прикладів наших проєктів</h2>
        </div>
        <a href="/portfolio" class="link-more">Усі роботи
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </a>
      </div>
      <div class="grid-3">
        <?php foreach ($latestCases as $case): ?>
          <a href="/portfolio/<?= h($case['slug']) ?>">
            <img src="<?= h($case['cover_image']) ?>" alt="<?= h($case['title']) ?>" class="thumb" style="width:100%; aspect-ratio:4/3; margin-bottom:16px;">
            <span style="font-size:12px; font-weight:700; color:var(--color-brand); text-transform:uppercase; letter-spacing:0.06em;"><?= h($case['category_name'] ?? '') ?></span>
            <h3 style="font-size:17px; font-weight:700; margin-top:6px; color:var(--color-ink);"><?= h($case['title']) ?></h3>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <?php if (!empty($latestPosts)): ?>
  <div class="section" style="background:var(--color-surface);">
    <div class="container">
      <div class="section-head">
        <div>
          <span class="eyebrow">Блог</span>
          <h2>Свіжі статті студії</h2>
        </div>
        <a href="/blog" class="link-more">Усі статті
          <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </a>
      </div>
      <div class="grid-3">
        <?php foreach ($latestPosts as $post): ?>
          <a href="/blog/<?= h($post['slug']) ?>" class="card" style="overflow:hidden;">
            <?php if (!empty($post['cover_image'])): ?>
              <img src="<?= h($post['cover_image']) ?>" alt="<?= h($post['title']) ?>" style="width:100%; height:160px; object-fit:cover;">
            <?php else: ?>
              <div style="height:160px; background:var(--color-tint-2);"></div>
            <?php endif; ?>
            <div style="padding:22px; display:flex; flex-direction:column; gap:10px;">
              <?php if (!empty($post['category_name'])): ?>
                <span style="font-size:12px; font-weight:700; color:var(--color-brand); text-transform:uppercase; letter-spacing:0.06em;"><?= h($post['category_name']) ?></span>
              <?php endif; ?>
              <h3 style="font-size:16px; font-weight:700; color:var(--color-ink); line-height:1.35;"><?= h($post['title']) ?></h3>
            </div>
          </a>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
  <?php endif; ?>

  <div class="section">
    <div class="container">
      <div class="cta-band">
        <div>
          <h2>Готові обговорити ваш проєкт?</h2>
          <p>Розкажіть, що потрібно — сайт, застосунок чи інструмент для трафіку. Відповідаємо протягом дня.</p>
        </div>
        <a href="/kontakty" class="btn-primary accent" style="flex-shrink:0;">Написати нам
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </a>
      </div>
    </div>
  </div>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
