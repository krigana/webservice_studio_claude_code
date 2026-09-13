<?php
declare(strict_types=1);

/**
 * Одноразовий (і на майбутнє — регулярний) аудит завантажених зображень:
 * проходить по всіх cover_image / gallery-картинках блогу й портфоліо,
 * а також по посиланнях на зображення всередині тексту статей блогу,
 * і виводить ті, чий фізичний файл у uploads-storage/ відсутній.
 *
 * Такі "осиротілі" записи виникають, коли картинку завантажили ДО
 * переходу на постійне сховище (uploads-storage/ поза public_html,
 * див. hostinger-new-hosting-migration.md) — сам файл загубився під час
 * чергового деплою, а посилання на нього лишилось у БД.
 *
 * Запуск на сервері через SSH:
 *   cd ~/domains/web-service.studio/public_html
 *   php scripts/check-uploads.php
 *
 * Нічого не змінює — тільки друкує звіт. Знайдені записи виправляються
 * вручну через адмінку: видалити зламане зображення й завантажити
 * наново (нове завантаження вже піде в uploads-storage і переживе
 * будь-який наступний git push).
 */

require __DIR__ . '/../includes/bootstrap.php';

function check(string $label, string $url): bool
{
    if (Upload::exists($url)) {
        return true;
    }
    echo "  [ЗЛАМАНО] {$label}: {$url}\n";
    return false;
}

$totalChecked = 0;
$totalBroken = 0;

echo "== Обкладинки та галереї портфоліо ==\n";
foreach (PortfolioCase::all() as $case) {
    if (!empty($case['cover_image'])) {
        $totalChecked++;
        if (!check("кейс «{$case['title']}» (id={$case['id']}) — обкладинка", $case['cover_image'])) {
            $totalBroken++;
        }
    }
    foreach (PortfolioCase::images((int) $case['id']) as $img) {
        $totalChecked++;
        if (!check("кейс «{$case['title']}» (id={$case['id']}) — галерея, image_id={$img['id']}", $img['image_path'])) {
            $totalBroken++;
        }
    }
}

echo "\n== Обкладинки статей блогу ==\n";
foreach (BlogPost::all() as $post) {
    if (!empty($post['cover_image'])) {
        $totalChecked++;
        if (!check("стаття «{$post['title']}» (id={$post['id']}) — обкладинка", $post['cover_image'])) {
            $totalBroken++;
        }
    }
}

echo "\n== Зображення всередині тексту статей блогу ==\n";
foreach (BlogPost::all() as $post) {
    if (preg_match_all('/<img[^>]+src=["\']([^"\']+)["\']/i', (string) $post['content'], $m)) {
        foreach ($m[1] as $src) {
            $totalChecked++;
            if (!check("стаття «{$post['title']}» (id={$post['id']}) — картинка в тексті", $src)) {
                $totalBroken++;
            }
        }
    }
}

echo "\nПеревірено зображень: {$totalChecked}. Зламано: {$totalBroken}.\n";
if ($totalBroken === 0) {
    echo "Все гаразд — жодного загубленого файлу не знайдено.\n";
}
