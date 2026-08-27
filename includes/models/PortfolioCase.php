<?php
declare(strict_types=1);

class PortfolioCase extends Model
{
    protected static string $table = 'portfolio_cases';

    public static function publishedList(?int $categoryId = null): array
    {
        if ($categoryId !== null) {
            $stmt = static::db()->prepare(
                "SELECT * FROM portfolio_cases WHERE status = 'published' AND category_id = ? ORDER BY sort_order, id DESC"
            );
            $stmt->execute([$categoryId]);
            return $stmt->fetchAll();
        }
        $stmt = static::db()->query(
            "SELECT * FROM portfolio_cases WHERE status = 'published' ORDER BY sort_order, id DESC"
        );
        return $stmt->fetchAll();
    }

    public static function bySlugPublished(string $slug): ?array
    {
        $stmt = static::db()->prepare(
            "SELECT * FROM portfolio_cases WHERE slug = ? AND status = 'published' LIMIT 1"
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
