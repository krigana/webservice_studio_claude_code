<?php
declare(strict_types=1);

require __DIR__ . '/../includes/admin-bootstrap.php';
Auth::requireLogin();

$faqs = Faq::ordered();
admin_header('Часті запитання', 'faq');
?>
<div style="display:flex; justify-content:flex-end; margin-bottom:16px;">
  <a href="/admin/faq/edit.php" class="btn">+ Нове запитання</a>
</div>
<p style="color:#7C99A1; margin-top:-8px;">Показуються на сторінці <a href="/chasti-zapytannya" target="_blank">/chasti-zapytannya</a> у порядку сортування (менше — вище), з мікророзміткою FAQPage для пошукових систем і AI-пошуку.</p>
<div class="card" style="overflow-x:auto;">
<table>
<thead><tr><th>Порядок</th><th>Запитання</th><th></th></tr></thead>
<tbody>
<?php foreach ($faqs as $f): ?>
<tr>
<td><?= (int) $f['sort_order'] ?></td>
<td><?= h($f['question']) ?></td>
<td style="white-space:nowrap;">
<a href="/admin/faq/edit.php?id=<?= (int) $f['id'] ?>">Редагувати</a>
&nbsp;·&nbsp;
<form method="post" action="/admin/faq/delete.php" style="display:inline;" onsubmit="return confirm('Видалити запитання?');">
<?= csrf_field() ?><input type="hidden" name="id" value="<?= (int) $f['id'] ?>">
<button type="submit" class="link-btn">Видалити</button>
</form>
</td>
</tr>
<?php endforeach; ?>
<?php if (empty($faqs)): ?><tr><td colspan="3" style="color:#7C99A1;">Запитань поки немає.</td></tr><?php endif; ?>
</tbody>
</table>
</div>
<?php admin_footer(); ?>
