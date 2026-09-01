<?php
/**
 * Динамический sitemap.xml — формируется из статичных страниц + опубликованных
 * статей блога и кейсов портфолио (ТЗ, раздел 5: "автоматически формируемый
 * sitemap.xml, обновляющийся при добавлении новых статей/кейсов").
 * Статичного файла sitemap.xml в корне больше нет — .htaccess пропускает
 * запрос сюда, во front controller (см. index.php, маршрут GET /sitemap.xml).
 */

declare(strict_types=1);

$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'web-service.studio';
$base = $scheme . '://' . $host;

function sitemap_date(?string $value): string
{
    $ts = $value ? strtotime($value) : false;
    return $ts !== false ? date('Y-m-d', $ts) : date('Y-m-d');
}

$staticPages = ['/', '/poslugy', '/tsiny', '/portfolio', '/blog', '/kontakty'];

$urls = [];
foreach ($staticPages as $path) {
    $urls[] = ['loc' => $base . $path, 'lastmod' => date('Y-m-d')];
}
foreach (BlogPost::sitemapEntries() as $post) {
    $urls[] = ['loc' => $base . '/blog/' . $post['slug'], 'lastmod' => sitemap_date($post['updated_at'])];
}
foreach (PortfolioCase::sitemapEntries() as $case) {
    $urls[] = ['loc' => $base . '/portfolio/' . $case['slug'], 'lastmod' => sitemap_date($case['updated_at'])];
}

header('Content-Type: application/xml; charset=UTF-8');
echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach ($urls as $u): ?>
  <url>
    <loc><?= h($u['loc']) ?></loc>
    <lastmod><?= h($u['lastmod']) ?></lastmod>
  </url>
<?php endforeach; ?>
</urlset>
