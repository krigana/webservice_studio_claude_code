<?php
declare(strict_types=1);

require __DIR__ . '/../includes/admin-bootstrap.php';
Auth::requireLogin();

// Список полів — єдине місце, де перераховані ключі settings, які редагує
// ця сторінка. Додати нове поле = додати рядок сюди, без міграції БД
// (Setting::set() сам створює ключ, якщо його ще нема).
$fields = [
    'Контакти' => [
        'contact_email' => 'Email',
        'contact_phone_display' => 'Телефон (як показувати на сайті)',
        'contact_whatsapp' => 'Посилання WhatsApp',
        'contact_telegram' => 'Посилання Telegram',
        'contact_telegram_handle' => 'Telegram (як показувати на сайті)',
        'contact_facebook' => 'Посилання Facebook',
        'contact_instagram' => 'Посилання Instagram',
    ],
    'Текст сторінки «Контакти»' => [
        'contacts_hero_title' => 'Заголовок',
        'contacts_hero_subtitle' => 'Підзаголовок',
    ],
];

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        $error = 'Форма застаріла, спробуйте ще раз.';
    } else {
        $values = [];
        foreach ($fields as $group) {
            foreach (array_keys($group) as $key) {
                $values[$key] = trim((string) ($_POST[$key] ?? ''));
            }
        }
        Setting::setMany($values);
        flash_set('admin_ok', 'Налаштування збережено.');
        redirect('/admin/settings/');
    }
}

admin_header('Налаштування сайту', 'settings');
?>
<form method="post" class="card">
  <?= csrf_field() ?>
  <?php if ($error): ?><div class="flash-error"><?= h($error) ?></div><?php endif; ?>

  <?php foreach ($fields as $groupTitle => $groupFields): ?>
    <h3 style="font-size:15px; margin:0 0 14px; <?= $groupTitle === array_key_first($fields) ? '' : 'padding-top:8px; border-top:1px solid #E4EEF0; margin-top:20px; padding-top:20px;' ?>"><?= h($groupTitle) ?></h3>
    <?php foreach ($groupFields as $key => $label): ?>
      <label><?= h($label) ?></label>
      <input type="text" name="<?= h($key) ?>" value="<?= h(Setting::get($key)) ?>">
    <?php endforeach; ?>
  <?php endforeach; ?>

  <button type="submit" class="btn">Зберегти</button>
</form>
<?php admin_footer(); ?>
