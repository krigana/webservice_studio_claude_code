<?php
declare(strict_types=1);

function admin_header(string $title, string $active = ''): void
{
    $user = Auth::user();
    ?>
<!doctype html>
<html lang="uk">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= h($title) ?> — Адмін-панель</title>
<meta name="robots" content="noindex, nofollow">
<style>
  * { box-sizing: border-box; }
  body { margin:0; font-family: system-ui, sans-serif; background:#F7FBFC; color:#0B1F26; }
  a { color:#00A7C7; text-decoration:none; }
  .layout { display:flex; min-height:100vh; }
  .sidebar { width:220px; background:#0B1F26; color:#fff; padding:24px 16px; flex-shrink:0; }
  .sidebar a { display:block; color:#9FBCC4; padding:10px 12px; border-radius:8px; font-size:14px; font-weight:600; margin-bottom:4px; }
  .sidebar a.active, .sidebar a:hover { background:#14313B; color:#fff; }
  .content { flex:1; padding:32px; max-width:1100px; }
  .card { background:#fff; border:1px solid #E4EEF0; border-radius:14px; padding:24px; margin-bottom:20px; }
  table { width:100%; border-collapse:collapse; font-size:14px; }
  th, td { text-align:left; padding:10px 8px; border-bottom:1px solid #E4EEF0; vertical-align:top; }
  .btn { display:inline-flex; align-items:center; gap:6px; background:#0B1F26; color:#fff; padding:10px 18px; border-radius:999px; font-size:13.5px; font-weight:600; border:none; cursor:pointer; text-decoration:none; }
  .btn-secondary { background:#fff; color:#0B1F26; border:1.5px solid #E4EEF0; }
  input[type=text], input[type=email], input[type=password], input[type=number], input[type=url], input[type=datetime-local], select, textarea {
    width:100%; border:1.5px solid #DCEAEE; border-radius:10px; padding:10px 14px; font-size:14px; font-family: inherit; margin-bottom:14px;
  }
  label { font-size:13px; font-weight:600; color:#34474E; display:block; margin-bottom:6px; }
  .flash-ok { background:#EAF7FA; color:#0B1F26; padding:12px 16px; border-radius:10px; margin-bottom:20px; }
  .flash-error { background:#FDEAEA; color:#7A1F1F; padding:12px 16px; border-radius:10px; margin-bottom:20px; }
  .badge { font-size:11.5px; font-weight:700; padding:3px 9px; border-radius:999px; }
  .badge-new { background:#EAF7FA; color:#00A7C7; }
  .badge-processed { background:#EDEDED; color:#7C99A1; }
  .badge-published { background:#E6F7EA; color:#1E7D34; }
  .badge-hidden, .badge-draft { background:#EDEDED; color:#7C99A1; }
  .link-btn { background:none; border:none; color:#B3261E; cursor:pointer; padding:0; font:inherit; }
</style>
</head>
<body>
<div class="layout">
  <div class="sidebar">
    <div style="font-weight:800; padding:10px 12px 20px;">Webservice Studio</div>
    <a href="/admin/" class="<?= $active === 'dashboard' ? 'active' : '' ?>">Дашборд</a>
    <a href="/admin/leads/" class="<?= $active === 'leads' ? 'active' : '' ?>">Заявки</a>
    <a href="/admin/blog/" class="<?= $active === 'blog' ? 'active' : '' ?>">Блог</a>
    <a href="/admin/portfolio/" class="<?= $active === 'portfolio' ? 'active' : '' ?>">Портфоліо</a>
    <a href="/admin/services/" class="<?= $active === 'services' ? 'active' : '' ?>">Послуги</a>
    <div style="margin-top:24px; padding-top:16px; border-top:1px solid #14313B;">
      <a href="/" target="_blank">↗ Переглянути сайт</a>
      <a href="/admin/logout.php">Вийти<?= $user ? ' (' . h($user['username']) . ')' : '' ?></a>
    </div>
  </div>
  <div class="content">
    <h1 style="font-size:22px; margin-bottom:20px;"><?= h($title) ?></h1>
    <?php if ($ok = flash_get('admin_ok')): ?><div class="flash-ok"><?= h($ok) ?></div><?php endif; ?>
    <?php if ($err = flash_get('admin_error')): ?><div class="flash-error"><?= h($err) ?></div><?php endif; ?>
<?php
}

function admin_footer(): void
{
    ?>
  </div>
</div>
</body>
</html>
<?php
}
