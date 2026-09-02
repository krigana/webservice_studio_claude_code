<?php
declare(strict_types=1);

/**
 * Реєстр виконаних SQL-міграцій (таблиця migrations). Самі файли лежать
 * у docs/migrations/*.sql — приходять звичайним git-патчем, а накатуються
 * однією кнопкою в /admin/migrations/ замість ручного копіювання в
 * phpMyAdmin щоразу.
 */
class Migration extends Model
{
    protected static string $table = 'migrations';

    public static function appliedFilenames(): array
    {
        $stmt = static::db()->query('SELECT filename FROM migrations');
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /** @return array<string,string> ім'я файлу => applied_at */
    public static function appliedMap(): array
    {
        $stmt = static::db()->query('SELECT filename, applied_at FROM migrations');
        return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
    }

    public static function markApplied(string $filename): void
    {
        static::create(['filename' => $filename]);
    }

    /**
     * Каталог з .sql-файлами міграцій, відсортований за іменем файлу —
     * тому файли мають нумерований префікс (0001_..., 0002_...).
     */
    public static function directory(): string
    {
        return dirname(__DIR__, 2) . '/docs/migrations';
    }

    /** @return string[] імена файлів у порядку виконання */
    public static function allFiles(): array
    {
        $dir = static::directory();
        if (!is_dir($dir)) {
            return [];
        }
        $files = glob($dir . '/*.sql') ?: [];
        $names = array_map('basename', $files);
        sort($names, SORT_STRING);
        return $names;
    }

    /**
     * Виконує один файл міграції в окремому з'єднанні з увімкненими
     * мультизапитами (файл може містити декілька SQL-виразів через ";").
     * Кидає виняток при помилці — викликач вирішує, що з цим робити.
     */
    public static function run(string $filename): void
    {
        $safeName = basename($filename);
        if (!in_array($safeName, static::allFiles(), true)) {
            throw new InvalidArgumentException('Файл міграції не знайдено: ' . $safeName);
        }

        $path = static::directory() . '/' . $safeName;
        $sql = file_get_contents($path);
        if ($sql === false || trim($sql) === '') {
            throw new RuntimeException('Порожній файл міграції: ' . $safeName);
        }

        $config = $GLOBALS['config']['db'];
        if ($config['host'] === 'sqlite-test') {
            // Гілка лише для локального smoke-тестування репозиторію (не
            // використовується на проді) — щоб не потрібен був живий MySQL.
            // Наївний розбір по ";" тут не годиться — цей символ трапляється
            // й усередині звичайного тексту/HTML, тож виконуємо файл одним
            // викликом exec() (як і буде на проді для одноінструкційних
            // міграцій; багатоінструкційні файли перевіряти на реальному
            // MySQL/staging).
            $pdo = Database::connect($GLOBALS['config']);
            $pdo->exec($sql);
        } else {
            // Окреме з'єднання (не спільний Database::connect()) — тільки тут
            // явно вмикаємо мультизапити, щоб файл міграції міг містити
            // декілька SQL-виразів підряд.
            $dsn = sprintf(
                'mysql:host=%s;port=%d;dbname=%s;charset=%s',
                $config['host'],
                $config['port'],
                $config['name'],
                $config['charset']
            );
            $pdo = new PDO($dsn, $config['user'], $config['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_MULTI_STATEMENTS => true,
            ]);
            $pdo->exec($sql);
        }

        static::markApplied($safeName);
    }
}
