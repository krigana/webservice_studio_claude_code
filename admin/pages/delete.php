<?php
declare(strict_types=1);

require __DIR__ . '/../includes/admin-bootstrap.php';
Auth::requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify()) {
    $id = (int) ($_POST['id'] ?? 0);
    if ($id) {
        Page::delete($id);
        flash_set('admin_ok', 'Сторінку видалено.');
    }
}
redirect('/admin/pages/');
