<?php
declare(strict_types=1);

require __DIR__ . '/includes/admin-bootstrap.php';

if (Auth::check()) {
    redirect('/admin/');
}

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        $error = 'Форма застаріла, спробуйте ще раз.';
    } else {
        $username = trim((string) ($_POST['username'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        if (Auth::attempt($username, $password)) {
            redirect('/admin/');
        }
        $error = 'Невірний логін або пароль (або обліковий запис тимчасово заблоковано).';
    }
}
?>
<!doctype html>
<html lang="uk">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Вхід — Адмін-панель</title>
<meta name="robots" content="noindex, nofollow">
<style>
  body { font-family: system-ui, sans-serif; background:#0B1F26; display:flex; align-items:center; justify-content:center; height:100vh; margin:0; }
  .box { background:#fff; padding:36px; border-radius:16px; width:100%; max-width:340px; box-sizing:border-box; }
  input { width:100%; border:1.5px solid #E4EEF0; border-radius:10px; padding:12px 14px; margin-bottom:14px; font-size:14px; box-sizing:border-box; }
  button { width:100%; background:#00A7C7; color:#fff; border:none; padding:13px; border-radius:999px; font-weight:700; font-size:14px; cursor:pointer; }
  .err { background:#FDEAEA; color:#7A1F1F; padding:10px 14px; border-radius:10px; margin-bottom:14px; font-size:13.5px; }
</style>
</head>
<body>
<form class="box" method="post">
  <h1 style="font-size:18px; margin-bottom:20px;">Webservice Studio — вхід</h1>
  <?php if ($error): ?><div class="err"><?= h($error) ?></div><?php endif; ?>
  <?= csrf_field() ?>
  <input type="text" name="username" placeholder="Логін" required autofocus>
  <input type="password" name="password" placeholder="Пароль" required>
  <button type="submit">Увійти</button>
</form>
</body>
</html>
