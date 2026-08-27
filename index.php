<?php
/**
 * Front controller. Все запросы (кроме реальных файлов) проходят через
 * этот файл — см. .htaccess.
 */

declare(strict_types=1);

// Локальный тест через `php -S` (PHP built-in server): отдаём реальные файлы
// как есть — на проде это делает Apache через .htaccess (условие !-f).
if (PHP_SAPI === 'cli-server') {
    $reqPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
    $file = __DIR__ . $reqPath;
    if ($reqPath !== '/' && is_file($file)) {
        return false;
    }
}

require __DIR__ . '/includes/Router.php';
require __DIR__ . '/includes/Database.php';

$config = require __DIR__ . '/config/config.php';

if ($config['app']['debug']) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
}

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

$router->post('/kontakty', function () {
    require __DIR__ . '/templates/contacts-submit.php';
});

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
