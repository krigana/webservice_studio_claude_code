<?php
/**
 * AJAX-эндпоинт для вставки изображения прямо в текст статьи блога
 * (кнопка "Зображення" в редакторе, см. admin/blog/edit.php).
 */

declare(strict_types=1);

require __DIR__ . '/includes/admin-bootstrap.php';
Auth::requireLogin();

header('Content-Type: application/json; charset=utf-8');

if (!csrf_verify()) {
    echo json_encode(['error' => 'CSRF']);
    exit;
}

try {
    $url = Upload::image($_FILES['image'] ?? [], 'blog');
    echo json_encode(['url' => $url]);
} catch (RuntimeException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
