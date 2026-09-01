<?php
declare(strict_types=1);

class PortfolioCase extends Model
{
    protected static string $table = 'portfolio_cases';

    public static function publishedList(?int $categoryId = null): array
    {
        if ($categoryId !== null) {
            $stmt = static::db()->prepare(
                "SELECT pc.*, sc.name AS category_name, sc.slug AS category_slug
                 FROM portfolio_cases pc LEFT JOIN service_categories sc ON sc.id = pc.category_id
                 WHERE pc.status = 'published' AND pc.category_id = ? ORDER BY pc.sort_order, pc.id DESC"
            );
            $stmt->execute([$categoryId]);
            return $stmt->fetchAll();
        }
        $stmt = static::db()->query(
            "SELECT pc.*, sc.name AS category_name, sc.slug AS category_slug
             FROM portfolio_cases pc LEFT JOIN service_categories sc ON sc.id = pc.category_id
             WHERE pc.status = 'published' ORDER BY pc.sort_order, pc.id DESC"
        );
        return $stmt->fetchAll();
    }

    /**
     * Все опубликованные кейсы (slug + updated_at) — для sitemap.xml.
     */
    public static function sitemapEntries(): array
    {
        return static::db()->query(
            "SELECT slug, updated_at FROM portfolio_cases WHERE status = 'published'"
        )->fetchAll();
    }

    public static function bySlugPublished(string $slug): ?array
    {
        $stmt = static::db()->prepare(
            "SELECT pc.*, sc.name AS category_name, sc.slug AS category_slug
             FROM portfolio_cases pc LEFT JOIN service_categories sc ON sc.id = pc.category_id
             WHERE pc.slug = ? AND pc.status = 'published' LIMIT 1"
        );
        $stmt->execute([$slug]);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    public static function images(int $caseId): array
    {
        $stmt = static::db()->prepare(
            "SELECT * FROM portfolio_case_images WHERE case_id = ? ORDER BY sort_order, id"
        );
        $stmt->execute([$caseId]);
        return $stmt->fetchAll();
    }

    public static function nextCase(int $currentId): ?array
    {
        $stmt = static::db()->prepare(
            "SELECT * FROM portfolio_cases WHERE status = 'published' AND id < ? ORDER BY id DESC LIMIT 1"
        );
        $stmt->execute([$currentId]);
        $row = $stmt->fetch();
        if ($row !== false) {
            return $row;
        }
        $stmt = static::db()->prepare(
            "SELECT * FROM portfolio_cases WHERE status = 'published' AND id != ? ORDER BY id DESC LIMIT 1"
        );
        $stmt->execute([$currentId]);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }
}

class PortfolioCaseImage extends Model
{
    protected static string $table = 'portfolio_case_images';
}
