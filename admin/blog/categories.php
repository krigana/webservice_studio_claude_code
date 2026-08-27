<?php
declare(strict_types=1);

require __DIR__ . '/../includes/admin-bootstrap.php';
Auth::requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify()) {
    $action = $_POST['action'] ?? '';
    if ($action === 'create') {
        $name = trim((string) ($_POST['name'] ?? ''));
        if ($name !== '') {
            $slug = unique_slug('blog_categories', slugify($name));
            BlogCategory::create(['name' => $name, 'slug' => $slug]);
            flash_set('admin_ok', 'Категорію додано.');
        }
    } elseif ($action === 'delete' && !empty($_POST['id'])) {
        BlogCategory::delete((int) $_POST['id']);
        flash_set('admin_ok', 'Категорію видалено.');
    }
    redirect('/admin/blog/categories.php');
}

$categories = BlogCategory::all('name ASC');
admin_header('Категорії блогу', 'blog');
?>
<div class="card" style="max-width:480px;">
  <form method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="create">
    <label>Нова категорія</label>
    <input type="text" name="name" placeholder="Назва категорії" required>
    <button type="submit" class="btn">Додати</button>
  </form>
</div>
<div class="card">
<table>
<thead><tr><th>Назва</th><th>Slug</th><th></th></tr></thead>
<tbody>
<?php foreach ($categories as $c): ?>
<tr>
<td><?= h($c['name']) ?></td>
<td><?= h($c['slug']) ?></td>
<td>
<form method="post" onsubmit="return confirm('Видалити категорію? Статті залишаться без категорії.');" style="display:inline;">
<?= csrf_field() ?>
<input type="hidden" name="action" value="delete">
<input type="hidden" name="id" value="<?= (int) $c['id'] ?>">
<button type="submit" class="link-btn">Видалити</button>
</form>
</td>
</tr>
<?php endforeach; ?>
<?php if (empty($categories)): ?><tr><td colspan="3" style="color:#7C99A1;">Категорій поки немає.</td></tr><?php endif; ?>
</tbody>
</table>
</div>
<a href="/admin/blog/" class="btn btn-secondary">← До статей</a>
<?php admin_footer(); ?>
