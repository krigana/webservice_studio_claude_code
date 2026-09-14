<?php
declare(strict_types=1);

require __DIR__ . '/../includes/admin-bootstrap.php';
Auth::requireLogin();

$id = !empty($_GET['id']) ? (int) $_GET['id'] : null;
$faq = $id ? Faq::find($id) : null;
if ($id && $faq === null) {
    flash_set('admin_error', 'Запитання не знайдено.');
    redirect('/admin/faq/');
}

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        $error = 'Форма застаріла, спробуйте ще раз.';
    } else {
        $question = trim((string) ($_POST['question'] ?? ''));
        $answer = trim((string) ($_POST['answer'] ?? ''));
        if ($question === '' || $answer === '') {
            $error = 'Заповніть питання і відповідь.';
        } else {
            $data = [
                'question' => $question,
                'answer' => $answer,
                'sort_order' => (int) ($_POST['sort_order'] ?? 0),
            ];

            if ($id) {
                Faq::update($id, $data);
                $faqId = $id;
            } else {
                $faqId = Faq::create($data);
            }

            flash_set('admin_ok', 'Запитання збережено.');
            redirect('/admin/faq/edit.php?id=' . $faqId);
        }
    }
}

admin_header($faq ? 'Редагування запитання' : 'Нове запитання', 'faq');
?>
<form method="post" class="card">
  <?= csrf_field() ?>
  <?php if ($error): ?><div class="flash-error"><?= h($error) ?></div><?php endif; ?>

  <label>Запитання</label>
  <input type="text" name="question" value="<?= h($faq['question'] ?? '') ?>" required maxlength="300">

  <label>Відповідь</label>
  <p style="color:#7C99A1; font-size:12.5px; margin:-8px 0 6px;">Порада: перше речення варто робити самодостатньою відповіддю на питання із заголовка — саме так відповіді краще потрапляють у Google AI Overviews й подібні AI-пошукові сервіси.</p>
  <textarea name="answer" rows="5" required><?= h($faq['answer'] ?? '') ?></textarea>

  <label>Порядок сортування (менше — вище)</label>
  <input type="number" name="sort_order" value="<?= h((string) ($faq['sort_order'] ?? 0)) ?>">

  <button type="submit" class="btn">Зберегти</button>
  <a href="/admin/faq/" class="btn btn-secondary">Скасувати</a>
</form>
<?php admin_footer(); ?>
