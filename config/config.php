<?php
/**
 * Загрузка конфигурации из .env (без Composer/сторонних библиотек —
 * требование ТЗ п.2.1: минимум зависимостей на проде).
 *
 * Использование: $config = require __DIR__ . '/config.php'; $config['db']['host'];
 */

declare(strict_types=1);

function wsstudio_load_env(string $path): array
{
    $env = [];
    if (!is_file($path)) {
        return $env;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || str_starts_with($line, '#')) {
            continue;
        }
        if (!str_contains($line, '=')) {
            continue;
        }
        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim($value);
        // снимаем обрамляющие кавычки, если есть
        if (strlen($value) >= 2 && (
            ($value[0] === '"' && $value[-1] === '"') ||
            ($value[0] === "'" && $value[-1] === "'")
        )) {
            $value = substr($value, 1, -1);
        }
        $env[$key] = $value;
    }
    return $env;
}

$envPath = dirname(__DIR__) . '/.env';
$env = wsstudio_load_env($envPath);

function wsstudio_env(array $env, string $key, $default = null)
{
    return $env[$key] ?? $default;
}

$appEnv = wsstudio_env($env, 'APP_ENV', 'production');
$appDebug = filter_var(wsstudio_env($env, 'APP_DEBUG', 'false'), FILTER_VALIDATE_BOOLEAN);

return [
    'app' => [
        'env' => $appEnv,
        'debug' => $appDebug,
        'url' => rtrim((string) wsstudio_env($env, 'APP_URL', ''), '/'),
    ],
    'db' => [
        'host' => wsstudio_env($env, 'DB_HOST', 'localhost'),
        'port' => (int) wsstudio_env($env, 'DB_PORT', 3306),
        'name' => wsstudio_env($env, 'DB_NAME', ''),
        'user' => wsstudio_env($env, 'DB_USER', ''),
        'pass' => wsstudio_env($env, 'DB_PASS', ''),
        'charset' => wsstudio_env($env, 'DB_CHARSET', 'utf8mb4'),
    ],
    'telegram' => [
        'bot_token' => wsstudio_env($env, 'TELEGRAM_BOT_TOKEN', ''),
        'chat_id' => wsstudio_env($env, 'TELEGRAM_CHAT_ID', ''),
    ],
    'admin' => [
        'session_name' => wsstudio_env($env, 'ADMIN_SESSION_NAME', 'wsstudio_admin'),
    ],
];
