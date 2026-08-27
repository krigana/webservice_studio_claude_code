<?php
/**
 * Авторизация админ-панели: логин/пароль, хэширование (password_hash),
 * блокировка после серии неудачных попыток — см. ТЗ п.2.4 и п.4.5.
 */

declare(strict_types=1);

final class Auth
{
    private const MAX_ATTEMPTS = 5;
    private const LOCK_MINUTES = 15;

    public static function attempt(string $username, string $password): bool
    {
        $admin = Admin::byUsername($username);
        if ($admin === null) {
            // фиксированная задержка, чтобы не выдавать таймингом существование логина
            usleep(300000);
            return false;
        }

        if (!empty($admin['locked_until']) && strtotime($admin['locked_until']) > time()) {
            return false;
        }

        if (!password_verify($password, $admin['password_hash'])) {
            $attempts = (int) $admin['failed_login_attempts'] + 1;
            $data = ['failed_login_attempts' => $attempts];
            if ($attempts >= self::MAX_ATTEMPTS) {
                $data['locked_until'] = date('Y-m-d H:i:s', time() + self::LOCK_MINUTES * 60);
                $data['failed_login_attempts'] = 0;
            }
            Admin::update((int) $admin['id'], $data);
            return false;
        }

        Admin::update((int) $admin['id'], [
            'failed_login_attempts' => 0,
            'locked_until' => null,
            'last_login_at' => date('Y-m-d H:i:s'),
        ]);

        session_regenerate_id(true);
        $_SESSION['admin_id'] = (int) $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];

        return true;
    }

    public static function check(): bool
    {
        return !empty($_SESSION['admin_id']);
    }

    public static function user(): ?array
    {
        if (!self::check()) {
            return null;
        }
        return ['id' => $_SESSION['admin_id'], 'username' => $_SESSION['admin_username']];
    }

    public static function logout(): void
    {
        $_SESSION = [];
        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }
        session_destroy();
    }

    public static function requireLogin(): void
    {
        if (!self::check()) {
            redirect('/admin/login.php');
        }
    }
}
