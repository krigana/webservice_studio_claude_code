<?php
declare(strict_types=1);

require __DIR__ . '/../includes/admin-bootstrap.php';
Auth::requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify()) {
    $id = (int) ($_POST['id'] ?? 0);
    if ($id) {
        try {
            Service::delete($id);
            flash_set('admin_ok', 'Послугу видалено.');
        } catch (PDOException $e) {
            flash_set('admin_error', 'Не вдалося видалити послугу — можливо, на неї посилаються заявки.');
        }
    }
}
redirect('/admin/services/');
