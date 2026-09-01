<?php
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'web-service.studio';
$canonical = $scheme . '://' . $host . ($_SERVER['REQUEST_URI'] ?? '/');
$title = $pageTitle ?? 'Webservice Studio';
$description = $pageDescription ?? "Веб-студія — розробка сайтів, застосунків та послуги для арбітражу трафіку.";
$siteUrl = $scheme . '://' . $host;

// Organization — на всіх сторінках (ТЗ, розділ 5: мікророзмітка Schema.org)
$organizationSchema = [
    '@context' => 'https://schema.org',
    '@type' => 'Organization',
    'name' => 'Webservice Studio',
    'url' => $siteUrl . '/',
    'logo' => $siteUrl . '/assets/icons/icon-512.png',
    'email' => 'support@web-service.studio',
    'sameAs' => [
        'https://www.facebook.com/webservicestudio/',
        'https://www.instagram.com/webservicestudio/',
        'https://t.me/webservices_studio',
        'https://api.whatsapp.com/send/?phone=380959212203',
    ],
];

// BreadcrumbList — якщо шаблон сторінки заповнив $breadcrumbs = [['name'=>.., 'url'=>..], ...]
// (url останнього елемента можна не вказувати — це поточна сторінка)
$breadcrumbSchema = null;
if (!empty($breadcrumbs) && is_array($breadcrumbs)) {
    $items = [];
    foreach ($breadcrumbs as $i => $crumb) {
        $entry = [
            '@type' => 'ListItem',
            'position' => $i + 1,
            'name' => $crumb['name'],
        ];
        if (!empty($crumb['url'])) {
            $entry['item'] = $siteUrl . $crumb['url'];
        }
        $items[] = $entry;
    }
    $breadcrumbSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => $items,
    ];
}

if (!function_exists('jsonld')) {
    function jsonld(array $data): string
    {
        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);
    }
}
?>
<!doctype html>
<html lang="uk">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= h($title) ?></title>
  <meta name="description" content="<?= h($description) ?>">
  <link rel="canonical" href="<?= h($canonical) ?>">
  <meta property="og:type" content="website">
  <meta property="og:title" content="<?= h($title) ?>">
  <meta property="og:description" content="<?= h($description) ?>">
  <meta property="og:url" content="<?= h($canonical) ?>">
  <?php if (!empty($pageImage)): $pageImageAbs = str_starts_with($pageImage, 'http') ? $pageImage : $siteUrl . $pageImage; ?>
  <meta property="og:image" content="<?= h($pageImageAbs) ?>">
  <meta name="twitter:image" content="<?= h($pageImageAbs) ?>">
  <?php endif; ?>
  <meta name="twitter:card" content="summary_large_image">
  <link rel="manifest" href="/manifest.json">
  <link rel="icon" href="/favicon.png">
  <link rel="apple-touch-icon" href="/assets/icons/apple-touch-icon.png">
  <meta name="theme-color" content="#00A7C7">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@600;700;800&family=Work+Sans:wght@400;500;600&family=Oswald:wght@500;600;700&display=swap" rel="stylesheet">
  <?php
  // Версія у query-параметрі — щоб серверний/браузерний кеш (напр. LiteSpeed на
  // Hostinger) не віддавав стару версію файлу після деплою нових правок.
  $mainCssPath = __DIR__ . '/../../assets/css/main.css';
  $mainCssVer = is_file($mainCssPath) ? filemtime($mainCssPath) : time();
  ?>
  <link rel="stylesheet" href="/assets/css/main.css?v=<?= (int) $mainCssVer ?>">
  <script type="application/ld+json"><?= jsonld($organizationSchema) ?></script>
  <?php if ($breadcrumbSchema !== null): ?>
  <script type="application/ld+json"><?= jsonld($breadcrumbSchema) ?></script>
  <?php endif; ?>
  <?php if (!empty($extraSchema)): ?>
  <script type="application/ld+json"><?= jsonld($extraSchema) ?></script>
  <?php endif; ?>
</head>
<body>
<?php
$navItems = [
    'home' => ['/', 'Головна'],
    'services' => ['/poslugy', 'Послуги'],
    'pricing' => ['/tsiny', 'Ціни'],
    'portfolio' => ['/portfolio', 'Роботи'],
    'blog' => ['/blog', 'Блог'],
    'contacts' => ['/kontakty', 'Контакти'],
];
$active = $activeNav ?? '';
?>
<div class="site-header">
  <div class="container site-header__row">
    <a href="/" class="site-logo">
      <img src="/assets/icons/logo-full-teal.png" alt="" class="site-logo__image">
      <span class="site-logo__word">
        <span class="site-logo__main">Webservice</span>
        <span class="site-logo__sub">Studio</span>
      </span>
    </a>

    <nav class="site-nav" id="site-nav">
      <?php foreach ($navItems as $key => [$url, $label]): ?>
        <a href="<?= h($url) ?>" class="<?= $active === $key ? 'is-active' : '' ?>"><?= h($label) ?></a>
      <?php endforeach; ?>
    </nav>

    <a href="/kontakty" class="btn-primary site-header__cta">
      Замовити проєкт
      <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
    </a>

    <button type="button" class="site-nav-toggle" id="site-nav-toggle" aria-label="Меню" aria-expanded="false" aria-controls="site-nav">
      <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg>
    </button>
  </div>
</div>
<script>
(function () {
  var toggle = document.getElementById('site-nav-toggle');
  var nav = document.getElementById('site-nav');
  if (!toggle || !nav) return;
  toggle.addEventListener('click', function () {
    var open = nav.classList.toggle('is-open');
    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
  });
})();
if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => navigator.serviceWorker.register('/sw.js'));
}
</script>