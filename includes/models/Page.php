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

    /**
     * Для sitemap.xml і людської карти сайту (/karta-sajtu) — усі сторінки
     * з адмінки (немає статусу published/hidden, бо таблиця pages не має
     * такого поля: усе, що створено в /admin/pages/, вважається опублікованим).
     *
     * @return array<int, array{slug: string, title: string, updated_at: string}>
     */
    public static function sitemapEntries(): array
    {
        return static::db()->query('SELECT slug, title, updated_at FROM pages ORDER BY title')->fetchAll();
    }
}
