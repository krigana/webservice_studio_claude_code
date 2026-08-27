<?php
/**
 * Загрузка изображений (обложки блога/портфолио) — с проверкой MIME-типа
 * и размера. Файлы кладутся в /assets/uploads/, доступ на выполнение PHP
 * там запрещён отдельным .htaccess (см. assets/uploads/.htaccess).
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
        $targetDir = dirname(__DIR__) . '/assets/uploads/' . $subdir;
        if (!is_dir($targetDir) && !mkdir($targetDir, 0755, true) && !is_dir($targetDir)) {
            throw new RuntimeException('Не вдалося створити папку для завантажень');
        }
        $target = $targetDir . '/' . $name;

        if (!move_uploaded_file($file['tmp_name'], $target)) {
            throw new RuntimeException('Не вдалося зберегти файл');
        }

        return '/assets/uploads/' . $subdir . '/' . $name;
    }
}
