<?php
declare(strict_types=1);

class BlogTag extends Model
{
    protected static string $table = 'blog_tags';

    public static function findOrCreateByName(string $name): int
    {
        $name = trim($name);
        $slug = slugify($name);
        $existing = static::findBy('slug', $slug);
        if ($existing !== null) {
            return (int) $existing['id'];
        }
        return static::create(['name' => $name, 'slug' => $slug]);
    }
}
