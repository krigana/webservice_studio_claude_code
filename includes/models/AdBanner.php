<?php
declare(strict_types=1);

class AdBanner extends Model
{
    protected static string $table = 'ad_banners';

    /**
     * Один випадковий опублікований банер — для показу під шапкою на
     * публічних сторінках (templates/partials/ad-banner.php). RAND() —
     * не найшвидший спосіб для великих таблиць, але банерів тут завжди
     * буде одиниці-десятки, тож продуктивність не критична.
     *
     * SQLite (лише для локального smoke-тестування репозиторію) не знає
     * RAND() — використовує RANDOM(), як і Setting::set().
     */
    public static function randomPublished(): ?array
    {
        $isSqliteTest = ($GLOBALS['config']['db']['host'] ?? '') === 'sqlite-test';
        $orderFn = $isSqliteTest ? 'RANDOM()' : 'RAND()';
        $stmt = static::db()->query(
            "SELECT * FROM ad_banners WHERE status = 'published' ORDER BY $orderFn LIMIT 1"
        );
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }
}
