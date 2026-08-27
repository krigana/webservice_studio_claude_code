<?php
declare(strict_types=1);

require __DIR__ . '/../includes/admin-bootstrap.php';
Auth::requireLogin();

$posts = BlogPost::all('created_at DESC');
admin_header('Блог', 'blog');
?>
<div style="display:flex; justify-content:flex-end; gap:10px; margin-bottom:16px;">
  <a href="/admin/blog/categories.php" class="btn btn-secondary">Категорії</a>
  <a href="/admin/blog/edit.php" class="btn">+ Нова стаття</a>
</div>
<div class="card" style="overflow-x:auto;">
<table>
<thead><tr><th>Заголовок</th><th>Статус</th><th>Дата публікації</th><th></th></tr></thead>
<tbody>
<?php foreach ($posts as $post): ?>
<tr>
<td><?= h($post['title']) ?></td>
<td><span class="badge badge-<?= h($post['status']) ?>"><?= $post['status'] === 'published' ? 'Опубліковано' : 'Чернетка' ?></span></td>
<td><?= $post['published_at'] ? h(date('d.m.Y', strtotime($post['published_at']))) : '—' ?></td>
<td style="white-space:nowrap;">
<a href="/admin/blog/edit.php?id=<?= (int) $post['id'] ?>">Редагувати</a>
&nbsp;·&nbsp;
<form method="post" action="/admin/blog/delete.php" style="display:inline;" onsubmit="return confirm('Видалити статтю?');">
<?= csrf_field() ?><input type="hidden" name="id" value="<?= (int) $post['id'] ?>">
<button type="submit" class="link-btn">Видалити</button>
</form>
</td>
</tr>
<?php endforeach; ?>
<?php if (empty($posts)): ?><tr><td colspan="4" style="color:#7C99A1;">Статей поки немає.</td></tr><?php endif; ?>
</tbody>
</table>
</div>
<?php admin_footer(); ?>
