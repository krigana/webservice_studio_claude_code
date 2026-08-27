<?php
declare(strict_types=1);

function h($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $to): void
{
    header('Location: ' . $to);
    exit;
}

/**
 * Транслитерация украинской/русской кириллицы в латиницу для ЧПУ-адресов (slug).
 */
function slugify(string $text): string
{
    $map = [
        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'h', 'ґ' => 'g', 'д' => 'd',
        'е' => 'e', 'є' => 'ie', 'ж' => 'zh', 'з' => 'z', 'и' => 'y', 'і' => 'i',
        'ї' => 'i', 'й' => 'i', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n',
        'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u',
        'ф' => 'f', 'х' => 'kh', 'ц' => 'ts', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shch',
        'ь' => '', 'ю' => 'iu', 'я' => 'ia', 'ы' => 'y', 'э' => 'e', 'ъ' => '',
    ];
    $text = mb_strtolower($text, 'UTF-8');
    $text = strtr($text, $map);
    $text = preg_replace('/[^a-z0-9]+/u', '-', $text);
    $text = trim((string) $text, '-');
    return $text !== '' ? $text : ('item-' . substr(md5((string) microtime(true)), 0, 8));
}

/**
 * Гарантирует уникальность slug в таблице — добавляет -2, -3, ... при коллизии.
 */
function unique_slug(string $table, string $slug, ?int $excludeId = null): string
{
    $pdo = Database::connect($GLOBALS['config']);
    $base = $slug;
    $i = 2;
    while (true) {
        $sql = "SELECT id FROM {$table} WHERE slug = ?" . ($excludeId ? ' AND id != ?' : '');
        $params = $excludeId ? [$slug, $excludeId] : [$slug];
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        if ($stmt->fetch() === false) {
            return $slug;
        }
        $slug = $base . '-' . $i;
        $i++;
    }
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . h(csrf_token()) . '">';
}

function csrf_verify(): bool
{
    $token = $_POST['csrf_token'] ?? '';
    return is_string($token) && !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function flash_set(string $key, string $message): void
{
    $_SESSION['flash'][$key] = $message;
}

function flash_get(string $key): ?string
{
    if (empty($_SESSION['flash'][$key])) {
        return null;
    }
    $msg = $_SESSION['flash'][$key];
    unset($_SESSION['flash'][$key]);
    return $msg;
}

function format_price(?string $from, ?string $to, ?string $note, string $currency = 'UAH'): string
{
    $symbol = $currency === 'UAH' ? 'грн' : $currency;
    if ($from === null && $to === null) {
        return $note !== null && $note !== '' ? h($note) : 'ціна за запитом';
    }
    $text = $note !== null && $note !== '' ? h($note) . ' ' : 'від ';
    $text .= number_format((float) $from, 0, '.', ' ') . ' ' . $symbol;
    if ($to !== null && $to !== '' && (float) $to > (float) $from) {
        $text .= ' до ' . number_format((float) $to, 0, '.', ' ') . ' ' . $symbol;
    }
    return $text;
}
