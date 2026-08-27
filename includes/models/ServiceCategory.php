<?php
declare(strict_types=1);

class ServiceCategory extends Model
{
    protected static string $table = 'service_categories';

    public static function published(): array
    {
        $stmt = static::db()->query("SELECT * FROM service_categories WHERE status = 'published' ORDER BY sort_order, name");
        return $stmt->fetchAll();
    }

    public static function bySlug(string $slug): ?array
    {
        return static::findBy('slug', $slug);
    }
}
