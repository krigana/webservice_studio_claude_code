<?php
declare(strict_types=1);

/**
 * Людська (HTML) карта сайту — /karta-sajtu. На відміну від /sitemap.xml
 * (машиночитаний, для пошукових систем), ця сторінка призначена для людей:
 * усі розділи одним поглядом. Дані ті самі моделі, що й XML-версія
 * (Page/BlogPost/PortfolioCase::sitemapEntries(), ServiceCategory::published()),
 * тож нові сторінки/статті/кейси з'являються тут автоматично, без правок коду.
 */

$pageTitle = 'Карта сайту — Webservice Studio';
$pageDescription = 'Усі сторінки сайту Webservice Studio в одному місці: розділи, послуги, портфоліо, блог та контакти.';
$activeNav = '';
$breadcrumbs = [
    ['name' => 'Головна', 'url' => '/'],
    ['name' => 'Карта сайту'],
];

$mainPages = [
    ['/', 'Головна'],
    ['/pro-studiyu', 'Про студію'],
    ['/poslugy', 'Послуги'],
    ['/tsiny', 'Ціни'],
    ['/portfolio', 'Портфоліо'],
    ['/blog', 'Блог'],
    ['/kontakty', 'Контакти'],
];

$serviceCategories = ServiceCategory::published();
$portfolioCases = PortfolioCase::sitemapEntries();
$blogPosts = BlogPost::sitemapEntries();
// «Про студію» вже показана окремо серед основних розділів вище — тут не дублюємо.
$otherPages = array_filter(Page::sitemapEntries(), static fn (array $p): bool => $p['slug'] !== 'pro-studiyu');

require __DIR__ . '/partials/header.php';

$cardStyle = 'padding:32px 32px 24px;';
$gridStyle = 'display:grid; grid-template-columns:repeat(auto-fill, minmax(200px, 1fr)); gap:4px 32px;';
$linkStyle = 'display:block; padding:11px 2px; font-size:14.5px; font-weight:600; color:var(--color-ink); border-bottom:1px solid var(--color-border);';
$sectionTitleStyle = 'font-size:12px; font-weight:700; color:var(--color-faint); text-transform:uppercase; letter-spacing:0.06em; margin-bottom:18px;';
?>
<main>
  <div class="hero" style="padding-bottom:24px;">
    <div class="container">
      <span class="eyebrow">Карта сайту</span>
      <h1 style="max-width:620px;">Усі сторінки сайту</h1>
      <p class="lead">Швидкий доступ до кожного розділу. Машиночитану версію для пошукових систем дивіться за адресою <a href="/sitemap.xml" style="color:var(--color-brand);">/sitemap.xml</a>.</p>
    </div>
  </div>

  <div class="container" style="padding:40px 40px 96px 40px; max-width:920px; display:flex; flex-direction:column; gap:24px;">

    <div class="card" style="<?= $cardStyle ?>">
      <p style="<?= $sectionTitleStyle ?>">Основні розділи</p>
      <div style="<?= $gridStyle ?>">
        <?php foreach ($mainPages as [$url, $label]): ?>
          <a href="<?= h($url) ?>" style="<?= $linkStyle ?>"><?= h($label) ?></a>
        <?php endforeach; ?>
      </div>
    </div>

    <?php if ($serviceCategories): ?>
    <div class="card" style="<?= $cardStyle ?>">
      <p style="<?= $sectionTitleStyle ?>">Послуги</p>
      <div style="<?= $gridStyle ?>">
        <?php foreach ($serviceCategories as $cat): ?>
          <a href="/poslugy#<?= h($cat['slug']) ?>" style="<?= $linkStyle ?>"><?= h($cat['name']) ?></a>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <?php if ($portfolioCases): ?>
    <div class="card" style="<?= $cardStyle ?>">
      <p style="<?= $sectionTitleStyle ?>">Портфоліо</p>
      <div style="<?= $gridStyle ?>">
        <?php foreach ($portfolioCases as $case): ?>
          <a href="/portfolio/<?= h($case['slug']) ?>" style="<?= $linkStyle ?>"><?= h($case['title']) ?></a>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <?php if ($blogPosts): ?>
    <div class="card" style="<?= $cardStyle ?>">
      <p style="<?= $sectionTitleStyle ?>">Блог</p>
      <div style="<?= $gridStyle ?>">
        <?php foreach ($blogPosts as $post): ?>
          <a href="/blog/<?= h($post['slug']) ?>" style="<?= $linkStyle ?>"><?= h($post['title']) ?></a>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

    <?php if ($otherPages): ?>
    <div class="card" style="<?= $cardStyle ?>">
      <p style="<?= $sectionTitleStyle ?>">Інші сторінки</p>
      <div style="<?= $gridStyle ?>">
        <?php foreach ($otherPages as $page): ?>
          <a href="/<?= h($page['slug']) ?>" style="<?= $linkStyle ?>"><?= h($page['title']) ?></a>
        <?php endforeach; ?>
      </div>
    </div>
    <?php endif; ?>

  </div>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
