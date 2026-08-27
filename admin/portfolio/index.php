<?php
declare(strict_types=1);

require __DIR__ . '/../includes/admin-bootstrap.php';
Auth::requireLogin();

$cases = PortfolioCase::all('created_at DESC');
admin_header('Портфоліо', 'portfolio');
?>
<div style="display:flex; justify-content:flex-end; margin-bottom:16px;">
  <a href="/admin/portfolio/edit.php" class="btn">+ Новий кейс</a>
</div>
<div class="card" style="overflow-x:auto;">
<table>
<thead><tr><th>Назва</th><th>Статус</th><th></th></tr></thead>
<tbody>
<?php foreach ($cases as $case): ?>
<tr>
<td><?= h($case['title']) ?></td>
<td><span class="badge badge-<?= h($case['status']) ?>"><?= $case['status'] === 'published' ? 'Опубліковано' : 'Приховано' ?></span></td>
<td style="white-space:nowrap;">
<a href="/admin/portfolio/edit.php?id=<?= (int) $case['id'] ?>">Редагувати</a>
&nbsp;·&nbsp;
<form method="post" action="/admin/portfolio/delete.php" style="display:inline;" onsubmit="return confirm('Видалити кейс?');">
<?= csrf_field() ?><input type="hidden" name="id" value="<?= (int) $case['id'] ?>">
<button type="submit" class="link-btn">Видалити</button>
</form>
</td>
</tr>
<?php endforeach; ?>
<?php if (empty($cases)): ?><tr><td colspan="3" style="color:#7C99A1;">Кейсів поки немає.</td></tr><?php endif; ?>
</tbody>
</table>
</div>
<?php admin_footer(); ?>
