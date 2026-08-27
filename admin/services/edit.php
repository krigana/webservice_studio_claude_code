<?php
declare(strict_types=1);

require __DIR__ . '/../includes/admin-bootstrap.php';
Auth::requireLogin();

$id = !empty($_GET['id']) ? (int) $_GET['id'] : null;
$service = $id ? Service::find($id) : null;
if ($id && $service === null) {
    flash_set('admin_error', 'Послугу не знайдено.');
    redirect('/admin/services/');
}

$categories = ServiceCategory::all('sort_order ASC');

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        $error = 'Форма застаріла, спробуйте ще раз.';
    } else {
        $title = trim((string) ($_POST['title'] ?? ''));
        $categoryId = (int) ($_POST['category_id'] ?? 0);
        if ($title === '' || $categoryId <= 0) {
            $error = 'Вкажіть назву та категорію послуги.';
        } else {
            $slugInput = trim((string) ($_POST['slug'] ?? '')) ?: $title;
            $slug = unique_slug('services', slugify($slugInput), $id);

            $priceFromRaw = trim((string) ($_POST['price_from'] ?? ''));
            $priceToRaw = trim((string) ($_POST['price_to'] ?? ''));

            $data = [
                'category_id' => $categoryId,
                'title' => $title,
                'slug' => $slug,
                'description' => trim((string) ($_POST['description'] ?? '')) ?: null,
                'price_from' => $priceFromRaw !== '' ? (float) $priceFromRaw : null,
                'price_to' => $priceToRaw !== '' ? (float) $priceToRaw : null,
                'price_note' => trim((string) ($_POST['price_note'] ?? '')) ?: null,
                'currency' => trim((string) ($_POST['currency'] ?? 'UAH')) ?: 'UAH',
                'sort_order' => (int) ($_POST['sort_order'] ?? 0),
                'status' => ($_POST['status'] ?? 'published') === 'hidden' ? 'hidden' : 'published',
                'seo_title' => trim((string) ($_POST['seo_title'] ?? '')) ?: null,
                'seo_description' => trim((string) ($_POST['seo_description'] ?? '')) ?: null,
            ];

            if ($id) {
                Service::update($id, $data);
                $serviceId = $id;
            } else {
                $serviceId = Service::create($data);
            }

            flash_set('admin_ok', 'Послугу збережено.');
            redirect('/admin/services/edit.php?id=' . $serviceId);
        }
    }
}

admin_header($service ? 'Редагування послуги' : 'Нова послуга', 'services');
?>
<form method="post" class="card">
  <?= csrf_field() ?>
  <?php if ($error): ?><div class="flash-error"><?= h($error) ?></div><?php endif; ?>

  <label>Назва</label>
  <input type="text" name="title" value="<?= h($service['title'] ?? '') ?>" required>

  <label>ЧПУ-адреса (slug)</label>
  <input type="text" name="slug" value="<?= h($service['slug'] ?? '') ?>">

  <label>Категорія</label>
  <select name="category_id" required>
    <option value="">— оберіть —</option>
    <?php foreach ($categories as $c): ?>
      <option value="<?= (int) $c['id'] ?>" <?= (($service['category_id'] ?? null) == $c['id']) ? 'selected' : '' ?>><?= h($c['name']) ?></option>
    <?php endforeach; ?>
  </select>

  <label>Опис</label>
  <textarea name="description" rows="3"><?= h($service['description'] ?? '') ?></textarea>

  <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px;">
    <div><label>Ціна від</label><input type="number" step="0.01" name="price_from" value="<?= h($service['price_from'] ?? '') ?>"></div>
    <div><label>Ціна до (необов'язково)</label><input type="number" step="0.01" name="price_to" value="<?= h($service['price_to'] ?? '') ?>"></div>
  </div>
  <label>Примітка до ціни (напр. «від», «договірна»)</label>
  <input type="text" name="price_note" value="<?= h($service['price_note'] ?? '') ?>">
  <label>Валюта</label>
  <input type="text" name="currency" value="<?= h($service['currency'] ?? 'UAH') ?>" maxlength="3">

  <label>Порядок сортування (менше — вище)</label>
  <input type="number" name="sort_order" value="<?= (int) ($service['sort_order'] ?? 0) ?>">

  <label>Статус</label>
  <select name="status">
    <option value="published" <?= (($service['status'] ?? 'published') === 'published') ? 'selected' : '' ?>>Опубліковано</option>
    <option value="hidden" <?= (($service['status'] ?? '') === 'hidden') ? 'selected' : '' ?>>Приховано</option>
  </select>

  <label>SEO title</label>
  <input type="text" name="seo_title" value="<?= h($service['seo_title'] ?? '') ?>">
  <label>SEO description</label>
  <textarea name="seo_description" rows="2"><?= h($service['seo_description'] ?? '') ?></textarea>

  <button type="submit" class="btn">Зберегти</button>
  <a href="/admin/services/" class="btn btn-secondary">Скасувати</a>
</form>
<?php admin_footer(); ?>
