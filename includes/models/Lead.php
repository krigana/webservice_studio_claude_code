<?php
declare(strict_types=1);

class Lead extends Model
{
    protected static string $table = 'leads';

    public static function withService(): array
    {
        $stmt = static::db()->query(
            "SELECT l.*, s.title AS service_title
             FROM leads l LEFT JOIN services s ON s.id = l.service_id
             ORDER BY l.created_at DESC"
        );
        return $stmt->fetchAll();
    }

    public static function newCount(): int
    {
        return (int) static::db()->query("SELECT COUNT(*) FROM leads WHERE status = 'new'")->fetchColumn();
    }
}
