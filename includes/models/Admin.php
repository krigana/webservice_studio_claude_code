<?php
declare(strict_types=1);

class Admin extends Model
{
    protected static string $table = 'admins';

    public static function byUsername(string $username): ?array
    {
        return static::findBy('username', $username);
    }
}
