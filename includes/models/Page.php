<?php
declare(strict_types=1);

/**
 * Статические страницы, редактируемые з адмінки (Політика конфіденційності,
 * "Про студію" тощо) — таблиця pages з docs/db-schema-webservice-studio.sql.
 */
class Page extends Model
{
    protected static string $table = 'pages';

    public static function bySlug(string $slug): ?array
    {
        return static::findBy('slug', $slug);
    }
}
