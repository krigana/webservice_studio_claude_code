<?php
declare(strict_types=1);

/**
 * Ключ-значення налаштувань сайту (контакти, соцмережі, тексти хедерів
 * сторінок) — таблиця settings з docs/db-schema-webservice-studio.sql.
 * Не наслідує звичайні create()/update() з Model, бо первинний ключ тут
 * `key` (VARCHAR), а не автоінкрементний id.
 */
class Setting extends Model
{
    protected static string $table = 'settings';

    private static ?array $cache = null;

    /**
     * @return array<string,string> усі налаштування, key => value
     * Названо НЕ all() — цей метод конфліктує за сигнатурою з успадкованим
     * Model::all(string $orderBy = 'id DESC'): array (фатальна помилка PHP
     * при неспівпадінні сигнатур перевизначеного статичного методу).
     */
    public static function map(): array
    {
        if (self::$cache === null) {
            $stmt = static::db()->query('SELECT `key`, `value` FROM settings');
            self::$cache = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        }
        return self::$cache;
    }

    public static function get(string $key, string $default = ''): string
    {
        $value = self::map()[$key] ?? null;
        return ($value === null || $value === '') ? $default : $value;
    }

    public static function set(string $key, string $value): void
    {
        // SQLite (лише для локального smoke-тестування репозиторію) не розуміє
        // MySQL-синтаксис "ON DUPLICATE KEY UPDATE" — для неї свій апсерт.
        $isSqliteTest = ($GLOBALS['config']['db']['host'] ?? '') === 'sqlite-test';
        $sql = $isSqliteTest
            ? 'INSERT INTO settings (`key`, `value`) VALUES (:k, :v)
               ON CONFLICT(`key`) DO UPDATE SET `value` = excluded.value'
            : 'INSERT INTO settings (`key`, `value`) VALUES (:k, :v)
               ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)';

        $stmt = static::db()->prepare($sql);
        $stmt->execute(['k' => $key, 'v' => $value]);
        self::$cache = null;
    }

    /** @param array<string,string> $values */
    public static function setMany(array $values): void
    {
        foreach ($values as $key => $value) {
            self::set($key, $value);
        }
    }
}
