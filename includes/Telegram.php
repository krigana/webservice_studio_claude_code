<?php
/**
 * Отправка уведомлений в Telegram-канал через Bot API (ТЗ п.4.4).
 * Токен и chat_id — из .env, никогда не хранятся в коде.
 */

declare(strict_types=1);

final class Telegram
{
    public static function send(array $config, string $text): bool
    {
        $token = $config['telegram']['bot_token'] ?? '';
        $chatId = $config['telegram']['chat_id'] ?? '';
        if ($token === '' || $chatId === '') {
            return false;
        }

        $url = "https://api.telegram.org/bot{$token}/sendMessage";
        $payload = http_build_query([
            'chat_id' => $chatId,
            'text' => $text,
            'parse_mode' => 'HTML',
        ]);

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
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            return $result !== false && $code === 200;
        }

        // Фолбэк, если на хостинге отключён cURL
        $context = stream_context_create([
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
                'content' => $payload,
                'timeout' => 5,
            ],
        ]);
        $result = @file_get_contents($url, false, $context);
        return $result !== false;
    }
}
