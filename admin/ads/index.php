<?php
declare(strict_types=1);

require __DIR__ . '/../includes/admin-bootstrap.php';
Auth::requireLogin();

$banners = AdBanner::all('sort_order ASC, created_at DESC');
admin_header('Рекламні банери', 'ads');
?>
<div style="display:flex; justify-content:flex-end; margin-bottom:16px;">
  <a href="/admin/ads/edit.php" class="btn">+ Новий банер</a>
</div>
<div class="card" style="overflow-x:auto;">
<table>
<thead><tr><th>Прев'ю</th><th>Назва</th><th>Посилання</th><th>Висота</th><th>Статус</th><th></th></tr></thead>
<tbody>
<?php foreach ($banners as $banner): ?>
<tr>
<td><img src="<?= h($banner['image_path']) ?>" style="width:90px; height:44px; object-fit:cover; border-radius:6px; display:block;"></td>
<td><?= h($banner['title']) ?></td>
<td style="max-width:280px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;"><?= h($banner['target_url']) ?></td>
<td>
<?= !empty($banner['max_height']) ? (int) $banner['max_height'] . ' px' : '<span style="color:#7C99A1;">типова</span>' ?>
<?php if (!empty($banner['max_height_mobile'])): ?><br><span style="font-size:12px; color:#7C99A1;">моб.: <?= (int) $banner['max_height_mobile'] ?> px</span><?php endif; ?>
</td>
<td><span class="badge badge-<?= h($banner['status']) ?>"><?= $banner['status'] === 'published' ? 'Активний' : 'Приховано' ?></span></td>
<td style="white-space:nowrap;">
<a href="/admin/ads/edit.php?id=<?= (int) $banner['id'] ?>">Редагувати</a>
&nbsp;·&nbsp;
<form method="post" action="/admin/ads/delete.php" style="display:inline;" onsubmit="return confirm('Видалити банер?');">
<?= csrf_field() ?><input type="hidden" name="id" value="<?= (int) $banner['id'] ?>">
<button type="submit" class="link-btn">Видалити</button>
</form>
</td>
</tr>
<?php endforeach; ?>
<?php if (empty($banners)): ?><tr><td colspan="6" style="color:#7C99A1;">Банерів поки немає — активних показів на сайті не буде.</td></tr><?php endif; ?>
</tbody>
</table>
</div>
<p style="font-size:13px; color:#7C99A1;">На кожній публічній сторінці сайту під шапкою показується один випадковий банер зі статусом «Активний». Якщо активних банерів декілька — вони чергуються випадково при кожному завантаженні сторінки.</p>
<?php admin_footer(); ?>
