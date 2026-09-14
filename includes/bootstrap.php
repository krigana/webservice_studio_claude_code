<?php
/**
 * Общая инициализация: конфиг, автозагрузка моделей, сессия.
 * Подключается и во front controller (index.php), и в скриптах /admin.
 */

declare(strict_types=1);

// Явний часовий пояс для відображення дат на сайті (копірайт у футері,
// формат дат в адмінці тощо). Порівняння published_at у BlogPost робиться
// окремо через UTC (gmdate()/UTC_TIMESTAMP()), незалежно від цього — щоб
// не залежати від того, який часовий пояс налаштований на MySQL-сервері.
date_default_timezone_set('Europe/Kyiv');

// Hostinger кешує повні HTML-сторінки через LiteSpeed (LSCache) на своєму
// сервері на кілька днів. Без цього заголовка будь-яка зміна через
// адмінку — новий блог-пост, зміна цін/налаштувань, редизайн CSS, будь-що —
// могла лишатись невидимою для відвідувачів (і навіть для нас під час
// перевірки) аж до ручного «Purge All» у hPanel, хоча версійні URL
// статики (?v=..., /assets/uploads/...) вже давно і правильно захищені.
// Явний X-LiteSpeed-Cache-Control: no-cache — офіційний спосіб LiteSpeed
// вимкнути серверне кешування конкретної відповіді для будь-якого PHP-
// застосунку (не тільки WordPress), без потреби в панелі хостингу.
// Не стосується /assets/uploads/{path} (Upload::serve()) — той маршрут
// сам виставляє свій Cache-Control і від цього заголовка не залежить.
if (!headers_sent()) {
    header('X-LiteSpeed-Cache-Control: no-cache');
}

$config = require dirname(__DIR__) . '/config/config.php';

if ($config['app']['debug']) {
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
}

require __DIR__ . '/helpers.php';
require __DIR__ . '/Database.php';
require __DIR__ . '/Model.php';
require __DIR__ . '/Auth.php';
require __DIR__ . '/Telegram.php';
require __DIR__ . '/Recaptcha.php';
require __DIR__ . '/Upload.php';

foreach (glob(__DIR__ . '/models/*.php') as $modelFile) {
    require $modelFile;
}

session_name($config['admin']['session_name']);
session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax',
    'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
]);
session_start();
