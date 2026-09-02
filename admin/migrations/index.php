<?php
declare(strict_types=1);

require __DIR__ . '/../includes/admin-bootstrap.php';
Auth::requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify()) {
    $pending = array_values(array_diff(Migration::allFiles(), Migration::appliedFilenames()));
    $toRun = $_POST['run_all'] ?? null
        ? $pending
        : array_filter([basename((string) ($_POST['filename'] ?? ''))]);

    $done = 0;
    foreach ($toRun as $filename) {
        if (!in_array($filename, $pending, true)) {
            continue;
        }
        try {
            Migration::run($filename);
            $done++;
        } catch (Throwable $e) {
            flash_set('admin_error', 'Помилка у файлі "' . $filename . '": ' . $e->getMessage());
            break;
        }
    }
    if ($done > 0 && !flash_get('admin_error')) {
        flash_set('admin_ok', $done === 1 ? 'Міграцію виконано.' : "Виконано міграцій: $done.");
    }
    redirect('/admin/migrations/');
}

$allFiles = Migration::allFiles();
$appliedMap = Migration::appliedMap();
$pendingCount = count(array_diff($allFiles, array_keys($appliedMap)));

admin_header('Оновлення БД', 'migrations');
?>
<p style="color:#7C99A1; margin-top:-8px;">
  Сюди приходять нові SQL-файли разом зі звичайними код-патчами (git pull / git am).
  Щоб застосувати — не потрібен phpMyAdmin, достатньо натиснути «Виконати» нижче.
</p>

<?php if ($pendingCount > 0): ?>
<form method="post" style="margin-bottom:16px;">
  <?= csrf_field() ?>
  <input type="hidden" name="run_all" value="1">
  <button type="submit" class="btn">Виконати нові оновлення (<?= $pendingCount ?>)</button>
</form>
<?php endif; ?>

<div class="card" style="overflow-x:auto;">
<table>
<thead><tr><th>Файл</th><th>Статус</th><th>Виконано</th><th></th></tr></thead>
<tbody>
<?php foreach ($allFiles as $filename): $isApplied = isset($appliedMap[$filename]); ?>
<tr>
<td><code><?= h($filename) ?></code></td>
<td><span class="badge <?= $isApplied ? 'badge-processed' : 'badge-new' ?>"><?= $isApplied ? 'Виконано' : 'Очікує' ?></span></td>
<td><?= $isApplied ? h(date('d.m.Y H:i', strtotime($appliedMap[$filename]))) : '—' ?></td>
<td>
<?php if (!$isApplied): ?>
<form method="post" style="display:inline;">
  <?= csrf_field() ?>
  <input type="hidden" name="filename" value="<?= h($filename) ?>">
  <button type="submit" class="btn btn-secondary" style="padding:6px 14px; font-size:13px;">Виконати</button>
</form>
<?php endif; ?>
</td>
</tr>
<?php endforeach; ?>
<?php if (empty($allFiles)): ?><tr><td colspan="4" style="color:#7C99A1;">Файлів міграцій поки немає.</td></tr><?php endif; ?>
</tbody>
</table>
</div>
<?php admin_footer(); ?>
