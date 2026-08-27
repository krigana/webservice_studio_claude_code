<?php
/**
 * Одноразовое создание первого администратора. Работает, только пока
 * таблица admins пуста. После использования рекомендуется удалить этот
 * файл с сервера (или он просто откажет в доступе сам, см. ниже).
 */

declare(strict_types=1);

require __DIR__ . '/includes/admin-bootstrap.php';

if (Admin::count() > 0) {
    http_response_code(403);
    echo 'Встановлення вже виконано. Видаліть файл admin/install.php з сервера.';
    exit;
}

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        $error = 'Форма застаріла, спробуйте ще раз.';
    } else {
        $username = trim((string) ($_POST['username'] ?? ''));
        $email = trim((string) ($_POST['email'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $passwordConfirm = (string) ($_POST['password_confirm'] ?? '');

        if ($username === '' || $email === '' || strlen($password) < 8) {
            $error = 'Заповніть усі поля, пароль — мінімум 8 символів.';
        } elseif ($password !== $passwordConfirm) {
            $error = 'Паролі не збігаються.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = 'Некоректний email.';
        } else {
            Admin::create([
                'username' => $username,
                'email' => $email,
                'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            ]);
            redirect('/admin/login.php');
        }
    }
}
?>
<!doctype html>
<html lang="uk">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Встановлення — Адмін-панель</title>
<meta name="robots" content="noindex, nofollow">
<style>
  body { font-family: system-ui, sans-serif; background:#0B1F26; display:flex; align-items:center; justify-content:center; min-height:100vh; margin:0; }
  .box { background:#fff; padding:36px; border-radius:16px; width:100%; max-width:380px; box-sizing:border-box; }
  input { width:100%; border:1.5px solid #E4EEF0; border-radius:10px; padding:12px 14px; margin-bottom:14px; font-size:14px; box-sizing:border-box; }
  button { width:100%; background:#00A7C7; color:#fff; border:none; padding:13px; border-radius:999px; font-weight:700; font-size:14px; cursor:pointer; }
  .err { background:#FDEAEA; color:#7A1F1F; padding:10px 14px; border-radius:10px; margin-bottom:14px; font-size:13.5px; }
  p.hint { font-size:12.5px; color:#7C99A1; margin-top:16px; }
</style>
</head>
<body>
<form class="box" method="post">
  <h1 style="font-size:18px; margin-bottom:6px;">Перше налаштування</h1>
  <p style="font-size:13px; color:#7C99A1; margin-bottom:20px;">Створіть обліковий запис адміністратора сайту.</p>
  <?php if ($error): ?><div class="err"><?= h($error) ?></div><?php endif; ?>
  <?= csrf_field() ?>
  <input type="text" name="username" placeholder="Логін" required autofocus>
  <input type="email" name="email" placeholder="Email" required>
  <input type="password" name="password" placeholder="Пароль (мін. 8 символів)" required minlength="8">
  <input type="password" name="password_confirm" placeholder="Повторіть пароль" required minlength="8">
  <button type="submit">Створити</button>
  <p class="hint">Після створення видаліть цей файл (admin/install.php) з сервера — він більше не потрібен.</p>
</form>
</body>
</html>
