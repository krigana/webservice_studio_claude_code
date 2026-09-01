<?php
declare(strict_types=1);

class BlogPost extends Model
{
    protected static string $table = 'blog_posts';

    public static function publishedList(int $limit = 9, int $offset = 0): array
    {
        $stmt = static::db()->prepare(
            "SELECT p.*, c.name AS category_name, c.slug AS category_slug
             FROM blog_posts p LEFT JOIN blog_categories c ON c.id = p.category_id
             WHERE p.status = 'published' AND p.published_at <= NOW()
             ORDER BY p.published_at DESC LIMIT ? OFFSET ?"
        );
        $stmt->bindValue(1, $limit, PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function publishedCount(): int
    {
        return (int) static::db()->query(
            "SELECT COUNT(*) FROM blog_posts WHERE status = 'published' AND published_at <= NOW()"
        )->fetchColumn();
    }

    public static function bySlugPublished(string $slug): ?array
    {
        $stmt = static::db()->prepare(
            "SELECT p.*, c.name AS category_name, c.slug AS category_slug
             FROM blog_posts p LEFT JOIN blog_categories c ON c.id = p.category_id
             WHERE p.slug = ? AND p.status = 'published' AND p.published_at <= NOW() LIMIT 1"
        );
        $stmt->execute([$slug]);
        $row = $stmt->fetch();
        return $row === false ? null : $row;
    }

    /**
     * Все опубликованные статьи (без пагинации) — для sitemap.xml.
     */
    public static function sitemapEntries(): array
    {
        return static::db()->query(
            "SELECT slug, updated_at FROM blog_posts WHERE status = 'published' AND published_at <= NOW()"
        )->fetchAll();
    }

    public static function tagsFor(int $postId): array
    {
        $stmt = static::db()->prepare(
            "SELECT t.* FROM blog_tags t
             JOIN blog_post_tags pt ON pt.tag_id = t.id
             WHERE pt.post_id = ? ORDER BY t.name"
        );
        $stmt->execute([$postId]);
        return $stmt->fetchAll();
    }

    public static function syncTags(int $postId, array $tagIds): void
    {
        $pdo = static::db();
        $pdo->prepare('DELETE FROM blog_post_tags WHERE post_id = ?')->execute([$postId]);
        $stmt = $pdo->prepare('INSERT INTO blog_post_tags (post_id, tag_id) VALUES (?, ?)');
        foreach (array_unique($tagIds) as $tagId) {
            $stmt->execute([$postId, $tagId]);
        }
    }

    public static function related(int $postId, ?int $categoryId, int $limit = 3): array
    {
        if ($categoryId !== null) {
            $stmt = static::db()->prepare(
                "SELECT * FROM blog_posts WHERE id != ? AND category_id = ? AND status = 'published' AND published_at <= NOW()
                 ORDER BY published_at DESC LIMIT ?"
            );
            $stmt->bindValue(1, $postId, PDO::PARAM_INT);
            $stmt->bindValue(2, $categoryId, PDO::PARAM_INT);
            $stmt->bindValue(3, $limit, PDO::PARAM_INT);
            $stmt->execute();
            $rows = $stmt->fetchAll();
            if (count($rows) >= $limit) {
                return $rows;
            }
        } else {
            $rows = [];
        }

        $excludeIds = array_merge([$postId], array_column($rows, 'id'));
        $placeholders = implode(',', array_fill(0, count($excludeIds), '?'));
        $need = $limit - count($rows);
        $stmt = static::db()->prepare(
            "SELECT * FROM blog_posts WHERE id NOT IN ($placeholders) AND status = 'published' AND published_at <= NOW()
             ORDER BY published_at DESC LIMIT " . (int) $need
        );
        $stmt->execute($excludeIds);
        return array_merge($rows, $stmt->fetchAll());
    }
}
