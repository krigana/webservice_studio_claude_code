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
    'email' => 'support@webservice.studio',
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
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@600;700;800&family=Work+Sans:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/main.css">
  <script type="application/ld+json"><?= jsonld($organizationSchema) ?></script>
  <?php if ($breadcrumbSchema !== null): ?>
  <script type="application/ld+json"><?= jsonld($breadcrumbSchema) ?></script>
  <?php endif; ?>
  <?php if (!empty($extraSchema)): ?>
  <script type="application/ld+json"><?= jsonld($extraSchema) ?></script>
  <?php endif; ?>
</head>
<body>
<header class="container" style="display:flex; align-items:center; justify-content:space-between; padding-top:20px; padding-bottom:20px;">
  <a href="/" style="font-family:var(--font-heading); font-weight:800; color:var(--color-ink);">Webservice Studio</a>
  <nav style="display:flex; gap:20px; font-size:14px; font-weight:600;">
    <a href="/poslugy">Послуги</a>
    <a href="/tsiny">Ціни</a>
    <a href="/portfolio">Роботи</a>
    <a href="/blog">Блог</a>
    <a href="/kontakty">Контакти</a>
  </nav>
</header>
<script>
if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => navigator.serviceWorker.register('/sw.js'));
}
</script>