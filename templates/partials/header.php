<?php
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'web-service.studio';
$canonical = $scheme . '://' . $host . ($_SERVER['REQUEST_URI'] ?? '/');
$title = $pageTitle ?? 'Webservice Studio';
$description = $pageDescription ?? "Веб-студія — розробка сайтів, застосунків та послуги для арбітражу трафіку.";
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
  <?php if (!empty($pageImage)): ?><meta property="og:image" content="<?= h($pageImage) ?>"><?php endif; ?>
  <meta name="twitter:card" content="summary_large_image">
  <link rel="manifest" href="/manifest.json">
  <link rel="icon" href="/favicon.png">
  <link rel="apple-touch-icon" href="/assets/icons/apple-touch-icon.png">
  <meta name="theme-color" content="#00A7C7">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@600;700;800&family=Work+Sans:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="/assets/css/main.css">
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