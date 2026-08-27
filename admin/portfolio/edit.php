<?php
declare(strict_types=1);

require __DIR__ . '/../includes/admin-bootstrap.php';
Auth::requireLogin();

$id = !empty($_GET['id']) ? (int) $_GET['id'] : null;
$case = $id ? PortfolioCase::find($id) : null;
if ($id && $case === null) {
    flash_set('admin_error', 'Кейс не знайдено.');
    redirect('/admin/portfolio/');
}

$categories = ServiceCategory::all('sort_order ASC');
$existingImages = $id ? PortfolioCase::images($id) : [];

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        $error = 'Форма застаріла, спробуйте ще раз.';
    } else {
        $title = trim((string) ($_POST['title'] ?? ''));
        if ($title === '') {
            $error = 'Вкажіть назву кейса.';
        } else {
            $slugInput = trim((string) ($_POST['slug'] ?? '')) ?: $title;
            $slug = unique_slug('portfolio_cases', slugify($slugInput), $id);

            $coverImage = $case['cover_image'] ?? null;
            try {
                $uploaded = Upload::image($_FILES['cover_image'] ?? [], 'portfolio');
                if ($uploaded !== null) {
                    $coverImage = $uploaded;
                }
            } catch (RuntimeException $e) {
                $error = $e->getMessage();
            }

            if ($error === null && $coverImage === null) {
                $error = 'Завантажте обкладинку кейса.';
            }

            if ($error === null) {
                $data = [
                    'category_id' => !empty($_POST['category_id']) ? (int) $_POST['category_id'] : null,
                    'title' => $title,
                    'slug' => $slug,
                    'description' => trim((string) ($_POST['description'] ?? '')) ?: null,
                    'cover_image' => $coverImage,
                    'project_url' => trim((string) ($_POST['project_url'] ?? '')) ?: null,
                    'sort_order' => (int) ($_POST['sort_order'] ?? 0),
                    'status' => ($_POST['status'] ?? 'published') === 'hidden' ? 'hidden' : 'published',
                    'seo_title' => trim((string) ($_POST['seo_title'] ?? '')) ?: null,
                    'seo_description' => trim((string) ($_POST['seo_description'] ?? '')) ?: null,
                ];

                if ($id) {
                    PortfolioCase::update($id, $data);
                    $caseId = $id;
                } else {
                    $caseId = PortfolioCase::create($data);
                }

                if (!empty($_FILES['gallery']['name'][0])) {
                    foreach ($_FILES['gallery']['name'] as $i => $name) {
                        if ($name === '') {
                            continue;
                        }
                        $file = [
                            'name' => $_FILES['gallery']['name'][$i],
                            'type' => $_FILES['gallery']['type'][$i],
                            'tmp_name' => $_FILES['gallery']['tmp_name'][$i],
                            'error' => $_FILES['gallery']['error'][$i],
                            'size' => $_FILES['gallery']['size'][$i],
                        ];
                        try {
                            $path = Upload::image($file, 'portfolio');
                            if ($path !== null) {
                                PortfolioCaseImage::create(['case_id' => $caseId, 'image_path' => $path, 'sort_order' => $i]);
                            }
                        } catch (RuntimeException $e) {
                            // пропускаем неудачный файл галереи, не прерывая сохранение кейса
                        }
                    }
                }

                flash_set('admin_ok', 'Кейс збережено.');
                redirect('/admin/portfolio/edit.php?id=' . $caseId);
            }
        }
    }
}

admin_header($case ? 'Редагування кейса' : 'Новий кейс', 'portfolio');
?>
<form method="post" enctype="multipart/form-data" class="card">
  <?= csrf_field() ?>
  <?php if ($error): ?><div class="flash-error"><?= h($error) ?></div><?php endif; ?>

  <label>Назва</label>
  <input type="text" name="title" value="<?= h($case['title'] ?? '') ?>" required>

  <label>ЧПУ-адреса (slug)</label>
  <input type="text" name="slug" value="<?= h($case['slug'] ?? '') ?>">

  <label>Категорія послуги</label>
  <select name="category_id">
    <option value="">— без категорії —</option>
    <?php foreach ($categories as $c): ?>
      <option value="<?= (int) $c['id'] ?>" <?= (($case['category_id'] ?? null) == $c['id']) ? 'selected' : '' ?>><?= h($c['name']) ?></option>
    <?php endforeach; ?>
  </select>

  <label>Опис</label>
  <textarea name="description" rows="4"><?= h($case['description'] ?? '') ?></textarea>

  <label>Посилання на проєкт (необов'язково)</label>
  <input type="url" name="project_url" value="<?= h($case['project_url'] ?? '') ?>">

  <label>Обкладинка<?= $case ? '' : ' (обов\'язково)' ?></label>
  <?php if (!empty($case['cover_image'])): ?><img src="<?= h($case['cover_image']) ?>" style="max-width:200px; border-radius:10px; display:block; margin-bottom:10px;"><?php endif; ?>
  <input type="file" name="cover_image" accept="image/*" <?= $case ? '' : 'required' ?>>

  <label>Галерея (можна вибрати декілька файлів)</label>
  <?php if (!empty($existingImages)): ?>
    <div style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:10px;">
      <?php foreach ($existingImages as $img): ?>
        <img src="<?= h($img['image_path']) ?>" style="width:70px; height:70px; object-fit:cover; border-radius:8px;">
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
  <input type="file" name="gallery[]" accept="image/*" multiple>

  <label>Порядок сортування (менше — вище)</label>
  <input type="number" name="sort_order" value="<?= (int) ($case['sort_order'] ?? 0) ?>">

  <label>Статус</label>
  <select name="status">
    <option value="published" <?= (($case['status'] ?? 'published') === 'published') ? 'selected' : '' ?>>Опубліковано</option>
    <option value="hidden" <?= (($case['status'] ?? '') === 'hidden') ? 'selected' : '' ?>>Приховано</option>
  </select>

  <label>SEO title</label>
  <input type="text" name="seo_title" value="<?= h($case['seo_title'] ?? '') ?>">
  <label>SEO description</label>
  <textarea name="seo_description" rows="2"><?= h($case['seo_description'] ?? '') ?></textarea>

  <button type="submit" class="btn">Зберегти</button>
  <a href="/admin/portfolio/" class="btn btn-secondary">Скасувати</a>
</form>
<?php admin_footer(); ?>
