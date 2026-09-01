<?php
declare(strict_types=1);

require __DIR__ . '/../includes/admin-bootstrap.php';
Auth::requireLogin();

$id = !empty($_GET['id']) ? (int) $_GET['id'] : null;
$page = $id ? Page::find($id) : null;
if ($id && $page === null) {
    flash_set('admin_error', 'Сторінку не знайдено.');
    redirect('/admin/pages/');
}

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        $error = 'Форма застаріла, спробуйте ще раз.';
    } else {
        $title = trim((string) ($_POST['title'] ?? ''));
        $content = (string) ($_POST['content'] ?? '');
        if ($title === '' || trim(strip_tags($content)) === '') {
            $error = 'Заповніть заголовок і текст сторінки.';
        } else {
            $slugInput = trim((string) ($_POST['slug'] ?? '')) ?: $title;
            $slug = unique_slug('pages', slugify($slugInput), $id);

            $data = [
                'title' => $title,
                'slug' => $slug,
                'content' => $content,
                'seo_title' => trim((string) ($_POST['seo_title'] ?? '')) ?: null,
                'seo_description' => trim((string) ($_POST['seo_description'] ?? '')) ?: null,
            ];

            if ($id) {
                Page::update($id, $data);
                $pageId = $id;
            } else {
                $pageId = Page::create($data);
            }

            flash_set('admin_ok', 'Сторінку збережено.');
            redirect('/admin/pages/edit.php?id=' . $pageId);
        }
    }
}

admin_header($page ? 'Редагування сторінки' : 'Нова сторінка', 'pages');
?>
<form method="post" class="card">
  <?= csrf_field() ?>
  <?php if ($error): ?><div class="flash-error"><?= h($error) ?></div><?php endif; ?>

  <label>Заголовок</label>
  <input type="text" name="title" value="<?= h($page['title'] ?? '') ?>" required>

  <label>Адреса (slug, необов'язково — згенерується із заголовка)</label>
  <input type="text" name="slug" value="<?= h($page['slug'] ?? '') ?>" placeholder="polityka-konfidentsiynosti">

  <label>Текст сторінки</label>
  <div style="display:flex; gap:6px; margin-bottom:8px; flex-wrap:wrap;">
    <button type="button" data-cmd="bold" class="btn btn-secondary" style="padding:6px 10px;"><b>B</b></button>
    <button type="button" data-cmd="italic" class="btn btn-secondary" style="padding:6px 10px;"><i>I</i></button>
    <button type="button" data-cmd="formatBlock" data-val="H2" class="btn btn-secondary" style="padding:6px 10px;">H2</button>
    <button type="button" data-cmd="insertUnorderedList" class="btn btn-secondary" style="padding:6px 10px;">• список</button>
    <button type="button" data-cmd="createLink" class="btn btn-secondary" style="padding:6px 10px;">Посилання</button>
  </div>
  <div id="editor" contenteditable="true" style="min-height:360px; border:1.5px solid #DCEAEE; border-radius:10px; padding:14px; margin-bottom:14px;"><?= $page['content'] ?? '' ?></div>
  <textarea name="content" id="content" style="display:none;"></textarea>

  <label>SEO title</label>
  <input type="text" name="seo_title" value="<?= h($page['seo_title'] ?? '') ?>">
  <label>SEO description</label>
  <textarea name="seo_description" rows="2"><?= h($page['seo_description'] ?? '') ?></textarea>

  <button type="submit" class="btn">Зберегти</button>
  <a href="/admin/pages/" class="btn btn-secondary">Скасувати</a>
</form>
<script>
document.querySelectorAll('[data-cmd]').forEach((btn) => {
  btn.addEventListener('click', () => {
    const cmd = btn.dataset.cmd;
    let val = btn.dataset.val || null;
    if (cmd === 'createLink') { val = prompt('URL посилання:'); if (!val) return; }
    document.execCommand(cmd, false, val);
    document.getElementById('editor').focus();
  });
});
document.querySelector('form').addEventListener('submit', () => {
  document.getElementById('content').value = document.getElementById('editor').innerHTML;
});
</script>
<?php admin_footer(); ?>
