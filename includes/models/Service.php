<?php
declare(strict_types=1);

class Service extends Model
{
    protected static string $table = 'services';

    public static function publishedAll(): array
    {
        $stmt = static::db()->query(
            "SELECT s.*, c.name AS category_name, c.slug AS category_slug
             FROM services s
             JOIN service_categories c ON c.id = s.category_id
             WHERE s.status = 'published' AND c.status = 'published'
             ORDER BY c.sort_order, s.sort_order, s.title"
        );
        return $stmt->fetchAll();
    }

    public static function publishedByCategory(int $categoryId): array
    {
        $stmt = static::db()->prepare(
            "SELECT * FROM services WHERE category_id = ? AND status = 'published' ORDER BY sort_order, title"
        );
        $stmt->execute([$categoryId]);
        return $stmt->fetchAll();
    }

    public static function bySlug(string $slug): ?array
    {
        return static::findBy('slug', $slug);
    }

    public static function allWithCategory(): array
    {
        $stmt = static::db()->query(
            "SELECT s.*, c.name AS category_name
             FROM services s JOIN service_categories c ON c.id = s.category_id
             ORDER BY c.sort_order, s.sort_order"
        );
        return $stmt->fetchAll();
    }
}
