<?php
/**
 * Простая база для моделей — только prepared statements (PDO), без ORM
 * и внешних библиотек (см. ТЗ п.1.4 — самописный бэкенд без фреймворка).
 */

declare(strict_types=1);

abstract class Model
{
    protected static string $table;
    protected static string $primaryKey = 'id';

    protected static function db(): PDO
    {
        return Database::connect($GLOBALS['config']);
    }

    public static function all(string $orderBy = 'id DESC'): array
    {
        $stmt = static::db()->query('SELECT * FROM ' . static::$table . ' ORDER BY ' . $orderBy);
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $stmt = static::db()->prepare('SELECT * FROM ' . static::$table . ' WHERE ' . static::$primaryKey . ' = ? LIMIT 1');
        $stmt->execute([$id]);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    public static function findBy(string $column, $value): ?array
    {
        $stmt = static::db()->prepare('SELECT * FROM ' . static::$table . ' WHERE ' . $column . ' = ? LIMIT 1');
        $stmt->execute([$value]);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    public static function where(string $column, $value, string $orderBy = 'id DESC'): array
    {
        $stmt = static::db()->prepare('SELECT * FROM ' . static::$table . ' WHERE ' . $column . ' = ? ORDER BY ' . $orderBy);
        $stmt->execute([$value]);
        return $stmt->fetchAll();
    }

    public static function create(array $data): int
    {
        $columns = array_keys($data);
        $placeholders = array_map(fn ($c) => ':' . $c, $columns);
        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            static::$table,
            implode(', ', $columns),
            implode(', ', $placeholders)
        );
        $stmt = static::db()->prepare($sql);
        $stmt->execute($data);
        return (int) static::db()->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $set = implode(', ', array_map(fn ($c) => "$c = :$c", array_keys($data)));
        $sql = sprintf('UPDATE %s SET %s WHERE %s = :__id', static::$table, $set, static::$primaryKey);
        $data['__id'] = $id;
        $stmt = static::db()->prepare($sql);
        $stmt->execute($data);
    }

    public static function delete(int $id): void
    {
        $stmt = static::db()->prepare('DELETE FROM ' . static::$table . ' WHERE ' . static::$primaryKey . ' = ?');
        $stmt->execute([$id]);
    }

    public static function count(): int
    {
        return (int) static::db()->query('SELECT COUNT(*) FROM ' . static::$table)->fetchColumn();
    }
}
