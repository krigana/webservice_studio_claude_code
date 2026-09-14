<?php
declare(strict_types=1);

require __DIR__ . '/../includes/admin-bootstrap.php';
Auth::requireLogin();

$id = !empty($_GET['id']) ? (int) $_GET['id'] : null;
$post = $id ? BlogPost::find($id) : null;
if ($id && $post === null) {
    flash_set('admin_error', 'Статтю не знайдено.');
    redirect('/admin/blog/');
}

$categories = BlogCategory::all('name ASC');
$existingTags = $post ? implode(', ', array_column(BlogPost::tagsFor($id), 'name')) : '';

$error = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) {
        $error = 'Форма застаріла, спробуйте ще раз.';
    } else {
        $title = trim((string) ($_POST['title'] ?? ''));
        $content = (string) ($_POST['content'] ?? '');
        if ($title === '' || trim(strip_tags($content)) === '') {
            $error = 'Заповніть заголовок і текст статті.';
        } else {
            $slugInput = trim((string) ($_POST['slug'] ?? '')) ?: $title;
            $slug = unique_slug('blog_posts', slugify($slugInput), $id);

            $coverImage = $post['cover_image'] ?? null;
            try {
                $uploaded = Upload::image($_FILES['cover_image'] ?? [], 'blog');
                if ($uploaded !== null) {
                    $coverImage = $uploaded;
                }
            } catch (RuntimeException $e) {
                $error = $e->getMessage();
            }

            if ($error === null) {
                $status = ($_POST['status'] ?? 'draft') === 'published' ? 'published' : 'draft';
                $publishedAt = trim((string) ($_POST['published_at'] ?? ''));
                if ($status === 'published' && $publishedAt === '') {
                    // UTC, бо BlogPost::publishedList() і суміжні методи порівнюють
                    // published_at з UTC_TIMESTAMP() — щоб не залежати від того,
                    // чи збігається часовий пояс PHP з часовим поясом MySQL-сервера
                    // на хостингу (саме розбіжність тут ховала щойно опубліковані
                    // статті аж до моменту, коли час PHP-сервера "наздоганяв" це).
                    $publishedAt = gmdate('Y-m-d\TH:i');
                }

                $data = [
                    'category_id' => !empty($_POST['category_id']) ? (int) $_POST['category_id'] : null,
                    'title' => $title,
                    'slug' => $slug,
                    'excerpt' => trim((string) ($_POST['excerpt'] ?? '')) ?: null,
                    'content' => $content,
                    'cover_image' => $coverImage,
                    'status' => $status,
                    'published_at' => $publishedAt !== '' ? str_replace('T', ' ', $publishedAt) . ':00' : null,
                    'seo_title' => trim((string) ($_POST['seo_title'] ?? '')) ?: null,
                    'seo_description' => trim((string) ($_POST['seo_description'] ?? '')) ?: null,
                    'og_image' => trim((string) ($_POST['og_image'] ?? '')) ?: null,
                ];

                if ($id) {
                    BlogPost::update($id, $data);
                    $postId = $id;
                } else {
                    $data['admin_id'] = Auth::user()['id'];
                    $postId = BlogPost::create($data);
                }

                $tagNames = array_filter(array_map('trim', explode(',', (string) ($_POST['tags'] ?? ''))));
                $tagIds = array_map(static fn ($n) => BlogTag::findOrCreateByName($n), $tagNames);
                BlogPost::syncTags($postId, $tagIds);

                flash_set('admin_ok', 'Статтю збережено.');
                redirect('/admin/blog/edit.php?id=' . $postId);
            }
        }
    }
}

admin_header($post ? 'Редагування статті' : 'Нова стаття', 'blog');
?>
<form method="post" enctype="multipart/form-data" class="card">
  <?= csrf_field() ?>
  <?php if ($error): ?><div class="flash-error"><?= h($error) ?></div><?php endif; ?>

  <label>Заголовок</label>
  <input type="text" name="title" value="<?= h($post['title'] ?? '') ?>" required>

  <label>ЧПУ-адреса (slug, необов'язково — згенерується із заголовка)</label>
  <input type="text" name="slug" value="<?= h($post['slug'] ?? '') ?>">

  <label>Категорія</label>
  <select name="category_id">
    <option value="">— без категорії —</option>
    <?php foreach ($categories as $c): ?>
      <option value="<?= (int) $c['id'] ?>" <?= (($post['category_id'] ?? null) == $c['id']) ? 'selected' : '' ?>><?= h($c['name']) ?></option>
    <?php endforeach; ?>
  </select>

  <label>Теги (через кому)</label>
  <input type="text" name="tags" value="<?= h($existingTags) ?>" placeholder="SEO, Розробка">

  <label>Короткий опис (excerpt)</label>
  <textarea name="excerpt" rows="2"><?= h($post['excerpt'] ?? '') ?></textarea>

  <label>Текст статті</label>
  <div class="editor-toolbar">
    <button type="button" data-cmd="bold" class="btn btn-secondary" style="padding:6px 10px;" title="Жирний"><b>B</b></button>
    <button type="button" data-cmd="italic" class="btn btn-secondary" style="padding:6px 10px;" title="Курсив"><i>I</i></button>
    <button type="button" data-cmd="underline" class="btn btn-secondary" style="padding:6px 10px;" title="Підкреслення"><u>U</u></button>
    <button type="button" data-cmd="strikeThrough" class="btn btn-secondary" style="padding:6px 10px;" title="Закреслення"><s>S</s></button>
    <button type="button" data-cmd="formatBlock" data-val="H2" class="btn btn-secondary" style="padding:6px 10px;">H2</button>
    <button type="button" data-cmd="formatBlock" data-val="H3" class="btn btn-secondary" style="padding:6px 10px;">H3</button>
    <button type="button" data-cmd="formatBlock" data-val="BLOCKQUOTE" class="btn btn-secondary" style="padding:6px 10px;" title="Цитата">❝ Цитата</button>
    <button type="button" data-cmd="insertUnorderedList" class="btn btn-secondary" style="padding:6px 10px;">• список</button>
    <button type="button" data-cmd="insertOrderedList" class="btn btn-secondary" style="padding:6px 10px;">1. список</button>
    <button type="button" data-cmd="createLink" class="btn btn-secondary" style="padding:6px 10px;">Посилання</button>
    <button type="button" data-cmd="removeFormat" class="btn btn-secondary" style="padding:6px 10px;" title="Прибрати форматування">Очистити стиль</button>
    <button type="button" id="insertImageBtn" class="btn btn-secondary" style="padding:6px 10px;">Зображення</button>
    <button type="button" id="insertYoutubeBtn" class="btn btn-secondary" style="padding:6px 10px;">YouTube</button>
    <button type="button" id="insertHtmlBtn" class="btn btn-secondary" style="padding:6px 10px;">HTML-код</button>
  </div>
  <div id="editor" contenteditable="true" style="min-height:280px; border:1.5px solid #DCEAEE; border-radius:10px; padding:14px; margin-bottom:14px;"><?= $post['content'] ?? '' ?></div>
  <textarea name="content" id="content" style="display:none;"></textarea>
  <input type="file" id="inlineImageInput" accept="image/*" style="display:none;">

  <div class="modal-overlay" id="youtubeModal">
    <div class="modal-box">
      <h3>Вставити відео з YouTube</h3>
      <label>Посилання на відео</label>
      <input type="url" id="youtubeUrlInput" placeholder="https://www.youtube.com/watch?v=...">
      <div class="modal-actions">
        <button type="button" class="btn btn-secondary" data-modal-close="youtubeModal">Скасувати</button>
        <button type="button" class="btn" id="youtubeInsertBtn">Вставити</button>
      </div>
    </div>
  </div>

  <div class="modal-overlay" id="htmlModal">
    <div class="modal-box">
      <h3>Вставити HTML-код</h3>
      <label>HTML, який буде вставлено в місце курсора</label>
      <textarea id="htmlCodeInput" placeholder="<div>...</div>"></textarea>
      <div class="modal-actions">
        <button type="button" class="btn btn-secondary" data-modal-close="htmlModal">Скасувати</button>
        <button type="button" class="btn" id="htmlInsertBtn">Вставити</button>
      </div>
    </div>
  </div>

  <label>Обкладинка (зображення)</label>
  <?php if (!empty($post['cover_image'])): ?><img src="<?= h($post['cover_image']) ?>" style="max-width:200px; border-radius:10px; display:block; margin-bottom:10px;"><?php endif; ?>
  <input type="file" name="cover_image" accept="image/*">

  <label>Статус</label>
  <select name="status">
    <option value="draft" <?= (($post['status'] ?? 'draft') === 'draft') ? 'selected' : '' ?>>Чернетка</option>
    <option value="published" <?= (($post['status'] ?? '') === 'published') ? 'selected' : '' ?>>Опубліковано</option>
  </select>

  <label>Дата публікації</label>
  <input type="datetime-local" name="published_at" value="<?= !empty($post['published_at']) ? h(str_replace(' ', 'T', substr((string) $post['published_at'], 0, 16))) : '' ?>">

  <label>SEO title</label>
  <input type="text" name="seo_title" value="<?= h($post['seo_title'] ?? '') ?>">
  <label>SEO description</label>
  <textarea name="seo_description" rows="2"><?= h($post['seo_description'] ?? '') ?></textarea>
  <label>OG-зображення (URL, необов'язково — інакше береться обкладинка)</label>
  <input type="text" name="og_image" value="<?= h($post['og_image'] ?? '') ?>">

  <button type="submit" class="btn">Зберегти</button>
  <a href="/admin/blog/" class="btn btn-secondary">Скасувати</a>
</form>
<script>
const editorEl = document.getElementById('editor');

// Курсор/виділення в редакторі губиться, щойно фокус іде у поле модалки
// (вставка YouTube/HTML). Тому постійно запам'ятовуємо останню позицію
// виділення всередині #editor, щоб потім вставити код саме туди, а не в
// кінець статті.
let savedRange = null;
document.addEventListener('selectionchange', () => {
  const sel = window.getSelection();
  if (sel.rangeCount > 0 && editorEl.contains(sel.anchorNode)) {
    savedRange = sel.getRangeAt(0).cloneRange();
  }
});

function restoreSelectionAndInsert(html) {
  editorEl.focus();
  const sel = window.getSelection();
  sel.removeAllRanges();
  if (savedRange) {
    sel.addRange(savedRange);
  } else {
    const range = document.createRange();
    range.selectNodeContents(editorEl);
    range.collapse(false);
    sel.addRange(range);
  }
  document.execCommand('insertHTML', false, html);
}

function openModal(id) { document.getElementById(id).classList.add('is-open'); }
function closeModal(id) { document.getElementById(id).classList.remove('is-open'); }
document.querySelectorAll('[data-modal-close]').forEach((btn) => {
  btn.addEventListener('click', () => closeModal(btn.dataset.modalClose));
});
document.querySelectorAll('.modal-overlay').forEach((overlay) => {
  overlay.addEventListener('click', (e) => { if (e.target === overlay) overlay.classList.remove('is-open'); });
});

document.querySelectorAll('[data-cmd]').forEach((btn) => {
  btn.addEventListener('click', () => {
    const cmd = btn.dataset.cmd;
    let val = btn.dataset.val || null;
    if (cmd === 'createLink') { val = prompt('URL посилання:'); if (!val) return; }
    editorEl.focus();
    document.execCommand(cmd, false, val);
  });
});

document.getElementById('insertImageBtn').addEventListener('click', () => {
  document.getElementById('inlineImageInput').click();
});
document.getElementById('inlineImageInput').addEventListener('change', async (e) => {
  const file = e.target.files[0];
  if (!file) return;
  const fd = new FormData();
  fd.append('image', file);
  fd.append('csrf_token', document.querySelector('input[name=csrf_token]').value);
  const res = await fetch('/admin/upload-image.php', { method: 'POST', body: fd });
  const data = await res.json();
  if (data.url) {
    restoreSelectionAndInsert('<img src="' + data.url + '">');
  } else {
    alert(data.error || 'Помилка завантаження зображення');
  }
});

// YouTube: розпізнаємо watch?v=, youtu.be/, /shorts/ і /embed/ — і
// вставляємо адаптивний (16:9) блок, який не вилазить за межі колонки
// ані в редакторі, ані на публічній сторінці статті (.prose .video-embed).
function youtubeIdFromUrl(url) {
  try {
    const u = new URL(url.trim());
    if (!/(^|\.)youtube\.com$|(^|\.)youtu\.be$/.test(u.hostname)) return null;
    if (u.hostname.includes('youtu.be')) return u.pathname.split('/').filter(Boolean)[0] || null;
    const parts = u.pathname.split('/').filter(Boolean);
    if (parts[0] === 'shorts' || parts[0] === 'embed') return parts[1] || null;
    return u.searchParams.get('v');
  } catch (e) {
    return null;
  }
}
document.getElementById('insertYoutubeBtn').addEventListener('click', () => {
  document.getElementById('youtubeUrlInput').value = '';
  openModal('youtubeModal');
  document.getElementById('youtubeUrlInput').focus();
});
document.getElementById('youtubeInsertBtn').addEventListener('click', () => {
  const url = document.getElementById('youtubeUrlInput').value.trim();
  const id = youtubeIdFromUrl(url);
  if (!id) { alert('Не вдалося розпізнати посилання на YouTube-відео. Приклад: https://www.youtube.com/watch?v=XXXXXXXXXXX'); return; }
  const html = '<div class="video-embed" contenteditable="false">'
    + '<iframe src="https://www.youtube.com/embed/' + id + '" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>'
    + '</div><p><br></p>';
  restoreSelectionAndInsert(html);
  closeModal('youtubeModal');
});

document.getElementById('insertHtmlBtn').addEventListener('click', () => {
  document.getElementById('htmlCodeInput').value = '';
  openModal('htmlModal');
  document.getElementById('htmlCodeInput').focus();
});
document.getElementById('htmlInsertBtn').addEventListener('click', () => {
  const html = document.getElementById('htmlCodeInput').value;
  if (html.trim() === '') { closeModal('htmlModal'); return; }
  restoreSelectionAndInsert(html);
  closeModal('htmlModal');
});

document.querySelector('form').addEventListener('submit', () => {
  document.getElementById('content').value = editorEl.innerHTML;
});
</script>
<?php admin_footer(); ?>
