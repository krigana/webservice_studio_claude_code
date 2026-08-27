<?php
declare(strict_types=1);

require __DIR__ . '/includes/admin-bootstrap.php';
Auth::logout();
redirect('/admin/login.php');
