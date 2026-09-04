<?php
/**
 * Общая инициализация: конфиг, автозагрузка моделей, сессия.
 * Подключается и во front controller (index.php), и в скриптах /admin.
 */

declare(strict_types=1);

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
