<?php
declare(strict_types=1);

/**
 * Часті запитання (/chasti-zapytannya) — таблиця faqs. Публічна сторінка
 * віддає їх з FAQPage-мікророзміткою Schema.org (templates/faq.php) — це
 * найвище пріоритетне, чого раніше не вистачало сайту для кращого
 * потрапляння в Google AI Overviews/AI Mode та ChatGPT Search.
 */
class Faq extends Model
{
    protected static string $table = 'faqs';

    public static function ordered(): array
    {
        return static::all('sort_order ASC, id ASC');
    }
}
