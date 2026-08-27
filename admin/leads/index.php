<?php
declare(strict_types=1);

require __DIR__ . '/../includes/admin-bootstrap.php';
Auth::requireLogin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && csrf_verify() && !empty($_POST['toggle_id'])) {
    $lead = Lead::find((int) $_POST['toggle_id']);
    if ($lead !== null) {
        Lead::update((int) $lead['id'], ['status' => $lead['status'] === 'new' ? 'processed' : 'new']);
    }
    redirect('/admin/leads/');
}

$leads = Lead::withService();
admin_header('Заявки', 'leads');
?>
<div class="card" style="overflow-x:auto;">
<table>
<thead><tr><th>Дата</th><th>Ім'я</th><th>Контакт</th><th>Послуга</th><th>Повідомлення</th><th>Статус</th><th></th></tr></thead>
<tbody>
<?php foreach ($leads as $lead): ?>
<tr>
<td style="white-space:nowrap;"><?= h(date('d.m.Y H:i', strtotime($lead['created_at']))) ?></td>
<td><?= h($lead['name']) ?></td>
<td><?= h($lead['contact']) ?></td>
<td><?= h($lead['service_title'] ?? '—') ?></td>
<td style="max-width:260px;"><?= h($lead['message'] ?? '') ?></td>
<td><span class="badge badge-<?= h($lead['status']) ?>"><?= $lead['status'] === 'new' ? 'Нова' : 'Оброблена' ?></span></td>
<td>
<form method="post">
<?= csrf_field() ?>
<input type="hidden" name="toggle_id" value="<?= (int) $lead['id'] ?>">
<button type="submit" class="btn btn-secondary" style="padding:6px 12px; font-size:12px;">
<?= $lead['status'] === 'new' ? 'Обробити' : 'Повернути' ?>
</button>
</form>
</td>
</tr>
<?php endforeach; ?>
<?php if (empty($leads)): ?><tr><td colspan="7" style="color:#7C99A1;">Заявок поки немає.</td></tr><?php endif; ?>
</tbody>
</table>
</div>
<?php admin_footer(); ?>
