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
  #editor img { max-width:100%; height:auto; border-radius:8px; }
  #editor blockquote {
    position: relative; margin:36px 0 20px; padding:26px 20px 20px;
    background:#0B1F26; border-radius:14px;
    box-shadow: 0 14px 28px -14px rgba(11,31,38,0.45);
    color:#fff; font-weight:700; font-style:normal; line-height:1.5;
  }
  #editor blockquote::before {
    content:'\201C'; position:absolute; z-index:1; top:-18px; left:18px;
    width:36px; height:36px; display:flex; align-items:center; justify-content:center;
    border-radius:50%; background:#00A7C7; color:#fff;
    font-family: Georgia, 'Times New Roman', serif; font-size:22px; font-weight:700; line-height:1;
  }
  #editor pre { background:#0B1F26; color:#E7F3F5; padding:12px 14px; border-radius:8px; overflow-x:auto; }
  #editor code { font-family:Consolas, Menlo, monospace; background:#F1FBFC; padding:1px 5px; border-radius:4px; }
  #editor .video-embed { position:relative; width:100%; max-width:480px; aspect-ratio:16/9; margin:10px 0; border-radius:8px; overflow:hidden; }
  #editor .video-embed iframe { position:absolute; inset:0; width:100%; height:100%; border:0; }
  .editor-toolbar {
    position: sticky; top: 0; z-index: 5; background: #fff;
    display: flex; gap: 6px; margin-bottom: 8px; flex-wrap: wrap;
    padding: 8px 0; border-bottom: 1px solid #E4EEF0;
  }
  #editor { max-height: 55vh; overflow-y: auto; resize: vertical; }
  .modal-overlay {
    display: none; position: fixed; inset: 0; background: rgba(11,31,38,0.45);
    z-index: 100; align-items: center; justify-content: center; padding: 20px;
  }
  .modal-overlay.is-open { display: flex; }
  .modal-box { background: #fff; border-radius: 14px; padding: 22px; max-width: 560px; width: 100%; max-height: 80vh; overflow-y: auto; }
  .modal-box h3 { margin: 0 0 14px; font-size: 16px; }
  .modal-box textarea { min-height: 160px; font-family: Consolas, Menlo, monospace; font-size: 13px; }
  .modal-actions { display: flex; gap: 10px; justify-content: flex-end; }
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
    <a href="/admin/pages/" class="<?= $active === 'pages' ? 'active' : '' ?>">Сторінки</a>
    <a href="/admin/faq/" class="<?= $active === 'faq' ? 'active' : '' ?>">Часті запитання</a>
    <a href="/admin/ads/" class="<?= $active === 'ads' ? 'active' : '' ?>">Рекламні банери</a>
    <a href="/admin/settings/" class="<?= $active === 'settings' ? 'active' : '' ?>">Налаштування</a>
    <a href="/admin/migrations/" class="<?= $active === 'migrations' ? 'active' : '' ?>">Оновлення БД</a>
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
