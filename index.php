<?php
/**
 * Front controller. Все запросы (кроме реальных файлов) проходят через
 * этот файл — см. .htaccess. Админ-панель (/admin) — отдельные файлы,
 * в этот роутинг не входит (см. README).
 */

declare(strict_types=1);

// Локальный тест через `php -S` (PHP built-in server): отдаём реальные файлы
// как есть — на проде это делает Apache через .htaccess (условие !-f).
if (PHP_SAPI === 'cli-server') {
    $reqPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
    $file = __DIR__ . $reqPath;
    // директории вроде /admin/ — отдаём их index.php (как это по умолчанию делает Apache)
    if ($reqPath !== '/' && is_dir($file) && is_file(rtrim($file, '/') . '/index.php')) {
        require rtrim($file, '/') . '/index.php';
        exit;
    }
    if ($reqPath !== '/' && is_file($file)) {
        return false;
    }
}

require __DIR__ . '/includes/bootstrap.php';
require __DIR__ . '/includes/Router.php';

$router = new Router();

// --- Публичные страницы ---
$router->get('/', function () {
    require __DIR__ . '/templates/home.php';
});

$router->get('/poslugy', function () {
    require __DIR__ . '/templates/services.php';
});

$router->get('/tsiny', function () {
    require __DIR__ . '/templates/pricing.php';
});

$router->get('/portfolio', function () {
    require __DIR__ . '/templates/portfolio.php';
});

$router->get('/portfolio/{slug}', function (array $params) {
    require __DIR__ . '/templates/portfolio-case.php';
});

$router->get('/blog', function () {
    require __DIR__ . '/templates/blog.php';
});

$router->get('/blog/{slug}', function (array $params) {
    require __DIR__ . '/templates/blog-article.php';
});

$router->get('/kontakty', function () {
    require __DIR__ . '/templates/contacts.php';
});

$router->get('/sitemap.xml', function () {
    require __DIR__ . '/templates/sitemap.php';
});

$router->post('/kontakty', function () {
    require __DIR__ . '/templates/contacts-submit.php';
});

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
