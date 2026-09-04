<?php
/**
 * Google reCAPTCHA v2 ("Я не робот") для форми "Контакти" — захист від
 * спам-ботів, які підбирають honeypot-поле. Ключі — з .env, ніколи не
 * зберігаються в коді. Якщо ключі не задані — капча вимкнена, форма
 * працює як раніше (щоб не зламати сайт до того, як ключі буде додано).
 */

declare(strict_types=1);

final class Recaptcha
{
    public static function isEnabled(array $config): bool
    {
        return ($config['recaptcha']['site_key'] ?? '') !== ''
            && ($config['recaptcha']['secret_key'] ?? '') !== '';
    }

    public static function siteKey(array $config): string
    {
        return (string) ($config['recaptcha']['site_key'] ?? '');
    }

    /**
     * Перевіряє токен `g-recaptcha-response` через Google siteverify API.
     * Повертає true, якщо капчу вимкнено (ключі не задані) — щоб не
     * блокувати форму на сайтах/середовищах, де капча ще не налаштована.
     */
    public static function verify(array $config, string $token, ?string $remoteIp): bool
    {
        $secret = $config['recaptcha']['secret_key'] ?? '';
        if ($secret === '') {
            return true;
        }
        if ($token === '') {
            return false;
        }

        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $payload = http_build_query([
            'secret' => $secret,
            'response' => $token,
            'remoteip' => $remoteIp ?? '',
        ]);

        $result = null;
        if (function_exists('curl_init')) {
            $ch = curl_init($url);
            curl_setopt_array($ch, [
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $payload,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 5,
                CURLOPT_CONNECTTIMEOUT => 5,
            ]);
            $result = curl_exec($ch);
            curl_close($ch);
        } else {
            $context = stream_context_create([
                'http' => [
                    'method' => 'POST',
                    'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                    'content' => $payload,
                    'timeout' => 5,
                ],
            ]);
            $result = @file_get_contents($url, false, $context);
        }

        if ($result === false || $result === null) {
            // Google недоступний — не блокуємо живих відвідувачів через мережеву проблему.
            return true;
        }

        $data = json_decode((string) $result, true);
        return is_array($data) && !empty($data['success']);
    }
}
