<?php
declare(strict_types=1);

require __DIR__ . '/../includes/admin-bootstrap.php';
Auth::requireLogin();

$services = Service::allWithCategory();
admin_header('Послуги', 'services');
?>
<div style="display:flex; justify-content:flex-end; gap:10px; margin-bottom:16px;">
  <a href="/admin/services/categories.php" class="btn btn-secondary">Категорії</a>
  <a href="/admin/services/edit.php" class="btn">+ Нова послуга</a>
</div>
<div class="card" style="overflow-x:auto;">
<table>
<thead><tr><th>Назва</th><th>Категорія</th><th>Ціна</th><th>Статус</th><th></th></tr></thead>
<tbody>
<?php foreach ($services as $s): ?>
<tr>
<td><?= h($s['title']) ?></td>
<td><?= h($s['category_name']) ?></td>
<td><?= format_price($s['price_from'], $s['price_to'], $s['price_note'], $s['currency']) ?></td>
<td><span class="badge badge-<?= h($s['status']) ?>"><?= $s['status'] === 'published' ? 'Опубліковано' : 'Приховано' ?></span></td>
<td style="white-space:nowrap;">
<a href="/admin/services/edit.php?id=<?= (int) $s['id'] ?>">Редагувати</a>
&nbsp;·&nbsp;
<form method="post" action="/admin/services/delete.php" style="display:inline;" onsubmit="return confirm('Видалити послугу?');">
<?= csrf_field() ?><input type="hidden" name="id" value="<?= (int) $s['id'] ?>">
<button type="submit" class="link-btn">Видалити</button>
</form>
</td>
</tr>
<?php endforeach; ?>
<?php if (empty($services)): ?><tr><td colspan="5" style="color:#7C99A1;">Послуг поки немає.</td></tr><?php endif; ?>
</tbody>
</table>
</div>
<?php admin_footer(); ?>
