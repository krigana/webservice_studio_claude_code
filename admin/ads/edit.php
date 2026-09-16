<?php
declare(strict_types=1);

require __DIR__ . '/../includes/admin-bootstrap.php';
Auth::requireLogin();

$id = !empty($_GET['id']) ? (int) $_GET['id'] : null;
$banner = $id ? AdBanner::find($id) : null;
if ($id && $banner === null) {
    flash_set('admin_error', 'Банер не знайдено.');
    redirect('/admin/ads/');
}

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        $error = 'Форма застаріла, спробуйте ще раз.';
    } else {
        $title = trim((string) ($_POST['title'] ?? ''));
        $targetUrl = trim((string) ($_POST['target_url'] ?? ''));

        $maxHeightRaw = trim((string) ($_POST['max_height'] ?? ''));
        $maxHeight = null;
        if ($maxHeightRaw !== '') {
            if (!ctype_digit($maxHeightRaw) || (int) $maxHeightRaw < 20 || (int) $maxHeightRaw > 800) {
                $error = 'Висота банера — число від 20 до 800 (px), або залиште поле порожнім для типової висоти.';
            } else {
                $maxHeight = (int) $maxHeightRaw;
            }
        }

        if ($error === null && $title === '') {
            $error = 'Вкажіть внутрішню назву банера (для орієнтації в адмінці).';
        } elseif ($error === null && ($targetUrl === '' || filter_var($targetUrl, FILTER_VALIDATE_URL) === false)) {
            $error = 'Вкажіть коректне партнерське посилання (з http:// або https://).';
        }

        $imagePath = $banner['image_path'] ?? null;
        if ($error === null) {
            try {
                $uploaded = Upload::image($_FILES['image'] ?? [], 'ads');
                if ($uploaded !== null) {
                    $imagePath = $uploaded;
                }
            } catch (RuntimeException $e) {
                $error = $e->getMessage();
            }
        }

        // Мобільна картинка — опційна, підміняє основну на маленьких
        // екранах (templates/partials/ad-banner.php, <picture>/<source>).
        // Чекбокс "видалити" — щоб можна було прибрати раніше завантажену
        // мобільну картинку й повернутись до показу основної скрізь.
        $imagePathMobile = !empty($_POST['delete_image_mobile']) ? null : ($banner['image_path_mobile'] ?? null);
        if ($error === null) {
            try {
                $uploadedMobile = Upload::image($_FILES['image_mobile'] ?? [], 'ads');
                if ($uploadedMobile !== null) {
                    $imagePathMobile = $uploadedMobile;
                }
            } catch (RuntimeException $e) {
                $error = $e->getMessage();
            }
        }

        if ($error === null && $imagePath === null) {
            $error = 'Завантажте зображення банера.';
        }

        if ($error === null) {
            $data = [
                'title' => $title,
                'image_path' => $imagePath,
                'image_path_mobile' => $imagePathMobile,
                'target_url' => $targetUrl,
                'alt_text' => trim((string) ($_POST['alt_text'] ?? '')) ?: null,
                'max_height' => $maxHeight,
                'sort_order' => (int) ($_POST['sort_order'] ?? 0),
                'status' => ($_POST['status'] ?? 'published') === 'hidden' ? 'hidden' : 'published',
            ];

            if ($id) {
                AdBanner::update($id, $data);
                $bannerId = $id;
            } else {
                $bannerId = AdBanner::create($data);
            }

            flash_set('admin_ok', 'Банер збережено.');
            redirect('/admin/ads/edit.php?id=' . $bannerId);
        }
    }
}

admin_header($banner ? 'Редагування банера' : 'Новий банер', 'ads');
?>
<form method="post" enctype="multipart/form-data" class="card">
  <?= csrf_field() ?>
  <?php if ($error): ?><div class="flash-error"><?= h($error) ?></div><?php endif; ?>

  <label>Внутрішня назва (для адмінки, на сайті не показується)</label>
  <input type="text" name="title" value="<?= h($banner['title'] ?? '') ?>" placeholder="Напр.: Hostinger — хостинг" required>

  <label>Партнерське/реферальне посилання</label>
  <input type="url" name="target_url" value="<?= h($banner['target_url'] ?? '') ?>" placeholder="https://..." required>

  <label>Зображення (десктоп і за замовчуванням)<?= $banner ? '' : ' (обов\'язково)' ?></label>
  <?php if (!empty($banner['image_path'])): ?><img src="<?= h($banner['image_path']) ?>" style="max-width:280px; max-height:110px; object-fit:cover; border-radius:10px; display:block; margin-bottom:10px;"><?php endif; ?>
  <input type="file" name="image" accept="image/*" <?= $banner ? '' : 'required' ?>>
  <p style="font-size:12px; color:#7C99A1; margin:-8px 0 14px;">Рекомендоване співвідношення сторін — широке (напр. 1200×250 px), банер розтягується на всю ширину блоку під шапкою. Показується скрізь, якщо мобільну картинку нижче не завантажено.</p>

  <label>Зображення для мобільних екранів (необов'язково)</label>
  <?php if (!empty($banner['image_path_mobile'])): ?>
    <img src="<?= h($banner['image_path_mobile']) ?>" style="max-width:200px; max-height:110px; object-fit:cover; border-radius:10px; display:block; margin-bottom:10px;">
    <label style="display:flex; align-items:center; gap:6px; font-weight:400; font-size:13px; color:#7C99A1; margin-bottom:10px;">
      <input type="checkbox" name="delete_image_mobile" value="1" style="width:auto; margin:0;"> видалити мобільну картинку (повернутись до показу основної на всіх екранах)
    </label>
  <?php endif; ?>
  <input type="file" name="image_mobile" accept="image/*">
  <p style="font-size:12px; color:#7C99A1; margin:-8px 0 14px;">Якщо завантажити — саме ця картинка підміняє основну на екранах до 640px завширшки (телефони). Якщо не завантажувати — на мобільній теж показується основне зображення вище.</p>

  <label>Alt-текст зображення (опис для скрін-рідерів і SEO)</label>
  <input type="text" name="alt_text" value="<?= h($banner['alt_text'] ?? '') ?>" placeholder="Напр.: Hostinger — хостинг зі знижкою за партнерським посиланням">

  <label>Висота показу банера на сайті, px (необов'язково)</label>
  <input type="number" name="max_height" min="20" max="800" value="<?= h((string) ($banner['max_height'] ?? '')) ?>" placeholder="Напр.: 160">
  <p style="font-size:12px; color:#7C99A1; margin:-8px 0 14px;">Порожньо — типова висота (180px на десктопі, 110px на мобільній). Задане значення застосовується однаково на всіх екранах, ширина завжди 100% блоку.</p>

  <label>Порядок сортування в списку адмінки (на показ на сайті не впливає)</label>
  <input type="number" name="sort_order" value="<?= (int) ($banner['sort_order'] ?? 0) ?>">

  <label>Статус</label>
  <select name="status">
    <option value="published" <?= (($banner['status'] ?? 'published') === 'published') ? 'selected' : '' ?>>Активний (показується на сайті)</option>
    <option value="hidden" <?= (($banner['status'] ?? '') === 'hidden') ? 'selected' : '' ?>>Приховано</option>
  </select>

  <button type="submit" class="btn">Зберегти</button>
  <a href="/admin/ads/" class="btn btn-secondary">Скасувати</a>
</form>
<?php admin_footer(); ?>
