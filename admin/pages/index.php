<?php
declare(strict_types=1);

require __DIR__ . '/../includes/admin-bootstrap.php';
Auth::requireLogin();

$pages = Page::all('title ASC');
admin_header('Сторінки', 'pages');
?>
<div style="display:flex; justify-content:flex-end; margin-bottom:16px;">
  <a href="/admin/pages/edit.php" class="btn">+ Нова сторінка</a>
</div>
<div class="card" style="overflow-x:auto;">
<table>
<thead><tr><th>Назва</th><th>Адреса</th><th>Оновлено</th><th></th></tr></thead>
<tbody>
<?php foreach ($pages as $p): ?>
<tr>
<td><?= h($p['title']) ?></td>
<td>/<?= h($p['slug']) ?></td>
<td><?= h(date('d.m.Y H:i', strtotime($p['updated_at']))) ?></td>
<td style="white-space:nowrap;">
<a href="/<?= h($p['slug']) ?>" target="_blank">Переглянути</a>
&nbsp;·&nbsp;
<a href="/admin/pages/edit.php?id=<?= (int) $p['id'] ?>">Редагувати</a>
&nbsp;·&nbsp;
<form method="post" action="/admin/pages/delete.php" style="display:inline;" onsubmit="return confirm('Видалити сторінку?');">
<?= csrf_field() ?><input type="hidden" name="id" value="<?= (int) $p['id'] ?>">
<button type="submit" class="link-btn">Видалити</button>
</form>
</td>
</tr>
<?php endforeach; ?>
<?php if (empty($pages)): ?><tr><td colspan="4" style="color:#7C99A1;">Сторінок поки немає.</td></tr><?php endif; ?>
</tbody>
</table>
</div>
<?php admin_footer(); ?>
