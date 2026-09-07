<?php
/**
 * Загрузка изображений (обложки блога/портфолио) — с проверкой MIME-типа
 * и размера.
 *
 * ВАЖЛИВО: фізично файли зберігаються ПОЗА `public_html` (на рівень вище,
 * поруч із `.env` — той самий каталог, який шукає config/config.php).
 * Причина: автодеплой на Hostinger при кожному `git push` робить свіжий
 * `git clone` + "Publishing" у `public_html`, і будь-які файли всередині
 * `public_html`, яких немає в git (а завантажені зображення туди
 * навмисно не коммітяться — див. .gitignore), при цьому видаляються.
 * Це зафіксовано двічі: одразу після переїзду на новий хостинг, і ще раз
 * після кількох звичайних `git push` без жодної міграції хостингу.
 *
 * Замість файлу в `public_html/assets/uploads/...` (який деплой стирає)
 * URL `/assets/uploads/...` тепер обробляється роутом
 * `GET /assets/uploads/{path}` (див. index.php) через self::serve() —
 * PHP сам знаходить файл у постійному сховищі й віддає його. Для
 * відвідувача URL не змінився.
 */

declare(strict_types=1);

final class Upload
{
    private const ALLOWED = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'image/gif' => 'gif',
    ];
    private const MAX_SIZE = 5 * 1024 * 1024;

    /** Каталог поза public_html, що переживає автодеплой (поруч із .env). */
    private static function storageRoot(): string
    {
        return dirname(__DIR__, 2) . '/uploads-storage';
    }

    /**
     * @return string|null относительный URL загруженного файла, либо null если файл не передан
     * @throws RuntimeException при ошибке валидации/загрузки
     */
    public static function image(array $file, string $subdir): ?string
    {
        if (!isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException('Помилка завантаження файлу');
        }
        if ($file['size'] > self::MAX_SIZE) {
            throw new RuntimeException('Файл занадто великий (макс. 5 МБ)');
        }

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);
        if (!isset(self::ALLOWED[$mime])) {
            throw new RuntimeException('Дозволені формати зображень: JPG, PNG, WEBP, GIF');
        }

        $ext = self::ALLOWED[$mime];
        $name = bin2hex(random_bytes(12)) . '.' . $ext;
        $targetDir = self::storageRoot() . '/' . $subdir;
        if (!is_dir($targetDir) && !mkdir($targetDir, 0755, true) && !is_dir($targetDir)) {
            throw new RuntimeException('Не вдалося створити папку для завантажень');
        }
        $target = $targetDir . '/' . $name;

        if (!move_uploaded_file($file['tmp_name'], $target)) {
            throw new RuntimeException('Не вдалося зберегти файл');
        }

        return '/assets/uploads/' . $subdir . '/' . $name;
    }

    private const MIME_BY_EXT = [
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png' => 'image/png',
        'webp' => 'image/webp',
        'gif' => 'image/gif',
    ];

    /**
     * Віддає файл із постійного сховища за відносним шляхом виду
     * "portfolio/xxxxx.jpg" (те, що прийшло в {path} з роута
     * /assets/uploads/{path}). Завершує запит самостійно.
     */
    public static function serve(string $relativePath): void
    {
        // Захист від directory traversal — прибираємо будь-які "..",
        // залишаємо тільки безпечні символи імені файлу/підпапки.
        $relativePath = ltrim($relativePath, '/');
        if ($relativePath === '' || str_contains($relativePath, '..') || !preg_match('#^[a-zA-Z0-9_/-]+\.[a-zA-Z0-9]+$#', $relativePath)) {
            http_response_code(404);
            require dirname(__DIR__) . '/templates/404.php';
            return;
        }

        $ext = strtolower((string) pathinfo($relativePath, PATHINFO_EXTENSION));
        if (!isset(self::MIME_BY_EXT[$ext])) {
            http_response_code(404);
            require dirname(__DIR__) . '/templates/404.php';
            return;
        }

        $root = self::storageRoot();
        $full = $root . '/' . $relativePath;
        $realRoot = realpath($root);
        $realFull = realpath($full);
        if ($realRoot === false || $realFull === false || !str_starts_with($realFull, $realRoot) || !is_file($realFull)) {
            http_response_code(404);
            require dirname(__DIR__) . '/templates/404.php';
            return;
        }

        header('Content-Type: ' . self::MIME_BY_EXT[$ext]);
        header('Content-Length: ' . (string) filesize($realFull));
        // Рік кешу — ім'я файлу випадкове (bin2hex), тому файл за одним
        // і тим самим URL ніколи не змінюється.
        header('Cache-Control: public, max-age=31536000, immutable');
        readfile($realFull);
    }
}
