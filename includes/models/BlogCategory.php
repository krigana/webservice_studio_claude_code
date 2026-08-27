<?php
declare(strict_types=1);

class BlogCategory extends Model
{
    protected static string $table = 'blog_categories';

    public static function bySlug(string $slug): ?array
    {
        return static::findBy('slug', $slug);
    }
}
