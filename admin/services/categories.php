<?php
declare(strict_types=1);

require __DIR__ . '/../includes/admin-bootstrap.php';
Auth::requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify()) {
    $action = $_POST['action'] ?? '';
    if ($action === 'create') {
        $name = trim((string) ($_POST['name'] ?? ''));
        if ($name !== '') {
            $slug = unique_slug('service_categories', slugify($name));
            ServiceCategory::create(['name' => $name, 'slug' => $slug, 'sort_order' => (int) ($_POST['sort_order'] ?? 0)]);
            flash_set('admin_ok', 'Категорію додано.');
        }
    } elseif ($action === 'delete' && !empty($_POST['id'])) {
        try {
            ServiceCategory::delete((int) $_POST['id']);
            flash_set('admin_ok', 'Категорію видалено.');
        } catch (PDOException $e) {
            flash_set('admin_error', 'Неможливо видалити категорію, поки в ній є послуги.');
        }
    }
    redirect('/admin/services/categories.php');
}

$categories = ServiceCategory::all('sort_order ASC');
admin_header('Категорії послуг', 'services');
?>
<div class="card" style="max-width:480px;">
  <form method="post">
    <?= csrf_field() ?>
    <input type="hidden" name="action" value="create">
    <label>Нова категорія</label>
    <input type="text" name="name" placeholder="Назва категорії" required>
    <label>Порядок сортування</label>
    <input type="number" name="sort_order" value="0">
    <button type="submit" class="btn">Додати</button>
  </form>
</div>
<div class="card">
<table>
<thead><tr><th>Назва</th><th>Slug</th><th>Порядок</th><th></th></tr></thead>
<tbody>
<?php foreach ($categories as $c): ?>
<tr>
<td><?= h($c['name']) ?></td>
<td><?= h($c['slug']) ?></td>
<td><?= (int) $c['sort_order'] ?></td>
<td>
<form method="post" onsubmit="return confirm('Видалити категорію?');" style="display:inline;">
<?= csrf_field() ?>
<input type="hidden" name="action" value="delete">
<input type="hidden" name="id" value="<?= (int) $c['id'] ?>">
<button type="submit" class="link-btn">Видалити</button>
</form>
</td>
</tr>
<?php endforeach; ?>
<?php if (empty($categories)): ?><tr><td colspan="4" style="color:#7C99A1;">Категорій поки немає.</td></tr><?php endif; ?>
</tbody>
</table>
</div>
<a href="/admin/services/" class="btn btn-secondary">← До послуг</a>
<?php admin_footer(); ?>
