<?php
$pageTitle = 'Калькулятор вартості сайту — Webservice Studio';
$pageDescription = 'Орієнтовний розрахунок вартості розробки сайту онлайн: оберіть тип сайту та потрібні опції — миттєво побачите приблизну ціну.';
$activeNav = 'calculator';
$breadcrumbs = [
    ['name' => 'Головна', 'url' => '/'],
    ['name' => 'Калькулятор вартості', 'url' => '/kalkulyator'],
];

$contactEmail = Setting::get('contact_email', 'support@webservice.studio');
$contactPhoneDisplay = Setting::get('contact_phone_display', '+380 95 921 22 03');

// Фіксований перелік типів сайту та опцій із середньоринковими цінами
// (грн). За рішенням замовника — без адмінки, правиться прямо в коді.
// Стосується поки лише категорії послуг "Розробка сайтів".
$calcTypes = [
    ['id' => 'landing', 'label' => 'Лендинг', 'hint' => 'Одна сторінка під конкретну пропозицію', 'price' => 8000],
    ['id' => 'corporate', 'label' => 'Корпоративний сайт', 'hint' => 'До 6 сторінок: головна, послуги, про компанію тощо', 'price' => 16000],
    ['id' => 'store', 'label' => 'Інтернет-магазин', 'hint' => 'Каталог товарів, кошик, оформлення замовлення', 'price' => 28000],
];
$calcPagePrice = 800;
$calcOptions = [
    ['id' => 'design', 'label' => 'Індивідуальний дизайн (не за шаблоном)', 'price' => 6000],
    ['id' => 'admin', 'label' => 'Адмін-панель для самостійного керування контентом', 'price' => 4000],
    ['id' => 'blog', 'label' => 'Блог / розділ новин', 'price' => 3500],
    ['id' => 'seo', 'label' => 'SEO-оптимізація (мета-теги, sitemap, мікророзмітка)', 'price' => 2500],
    ['id' => 'pwa', 'label' => 'PWA — встановлення сайту як застосунку', 'price' => 2000],
    ['id' => 'telegram', 'label' => 'Форма зв’язку зі сповіщеннями у Telegram', 'price' => 1500],
    ['id' => 'payment', 'label' => 'Інтеграція онлайн-оплати', 'price' => 3500],
    ['id' => 'lang', 'label' => 'Додаткова мовна версія сайту', 'price' => 5000],
];

require __DIR__ . '/partials/header.php';
?>
<main>
  <div class="hero" style="padding-bottom:24px;">
    <div class="container">
      <span class="eyebrow">Калькулятор</span>
      <h1 style="max-width:640px;">Розрахуйте орієнтовну вартість сайту</h1>
      <p class="lead">Оберіть тип сайту та потрібні опції — миттєво побачите приблизну ціну на основі середньоринкових тарифів. Поки що калькулятор охоплює лише напрямок «Розробка сайтів».</p>
    </div>
  </div>

  <div class="container calc-layout no-print">
    <div class="calc-main">
      <div>
        <h2 style="font-size:20px; font-weight:800; margin-bottom:16px;">1. Тип сайту</h2>
        <div class="calc-type-grid">
          <?php foreach ($calcTypes as $i => $t): ?>
            <label class="calc-type-card">
              <input type="radio" name="calc-type" value="<?= h($t['id']) ?>" data-price="<?= (int) $t['price'] ?>" data-label="<?= h($t['label']) ?>" <?= $i === 0 ? 'checked' : '' ?>>
              <span class="calc-type-card__box">
                <span class="calc-type-card__title"><?= h($t['label']) ?></span>
                <span class="calc-type-card__price">від <?= number_format($t['price'], 0, '.', ' ') ?> грн</span>
                <span class="calc-type-card__hint"><?= h($t['hint']) ?></span>
              </span>
            </label>
          <?php endforeach; ?>
        </div>
      </div>

      <div>
        <h2 style="font-size:20px; font-weight:800; margin-bottom:16px;">2. Додаткові сторінки</h2>
        <div class="calc-pages-row">
          <span>Кількість сторінок понад базовий пакет</span>
          <input type="number" id="calc-pages" min="0" max="50" step="1" value="0">
        </div>
        <p style="font-size:13px; color:var(--color-faint); margin-top:10px;">За кожну додаткову сторінку — <?= number_format($calcPagePrice, 0, '.', ' ') ?> грн.</p>
      </div>

      <div>
        <h2 style="font-size:20px; font-weight:800; margin-bottom:16px;">3. Додаткові опції</h2>
        <div class="calc-options">
          <?php foreach ($calcOptions as $o): ?>
            <label class="calc-option-row">
              <span class="calc-option-row__label">
                <input type="checkbox" data-price="<?= (int) $o['price'] ?>" data-label="<?= h($o['label']) ?>">
                <?= h($o['label']) ?>
              </span>
              <span class="calc-option-row__price">+<?= number_format($o['price'], 0, '.', ' ') ?> грн</span>
            </label>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <div class="calc-side">
      <div class="calc-total-box">
        <span class="calc-total-box__label">Орієнтовна вартість</span>
        <span class="calc-total-box__value" id="calc-total-value">0 грн</span>
        <button type="button" id="calc-print-btn" class="btn-primary accent block" style="justify-content:center;">
          Друк / зберегти PDF
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9V2h12v7M6 18H4a1 1 0 0 1-1-1v-5a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1h-2M6 14h12v8H6v-8z"/></svg>
        </button>
        <a href="/kontakty" class="btn-ghost block" style="justify-content:center; background:transparent; border-color:rgba(255,255,255,0.25); color:#fff;">Обговорити проєкт з нами</a>
        <p class="calc-total-box__note">Розрахунок орієнтовний і не є остаточною комерційною пропозицією. Точну вартість погоджуємо індивідуально після обговорення деталей проєкту.</p>
      </div>
    </div>
  </div>

  <!-- Друкована версія — заповнюється скриптом перед window.print() -->
  <div class="print-quote" id="printQuote">
    <div class="print-quote__head">
      <img src="/assets/icons/logo-full-teal.png" alt="" style="height:36px; width:auto;">
      <div>
        <strong>Webservice Studio</strong><br>
        <?= h($contactEmail) ?> · <?= h($contactPhoneDisplay) ?>
      </div>
    </div>
    <h1>Орієнтовний розрахунок вартості сайту</h1>
    <p class="print-quote__date"></p>
    <table class="print-quote__table">
      <tbody id="printQuoteRows"></tbody>
    </table>
    <p class="print-quote__total"></p>
    <p class="print-quote__disclaimer">Розрахунок орієнтовний і не є остаточною комерційною пропозицією. Точну вартість погоджуємо індивідуально після обговорення деталей проєкту з менеджером студії.<br>web-service.studio</p>
  </div>

  <div class="section no-print">
    <div class="container">
      <div class="cta-band">
        <div>
          <h2>Готові обговорити деталі?</h2>
          <p>Надішліть форму — уточнимо задачу і погодимо точну вартість та терміни.</p>
        </div>
        <a href="/kontakty" class="btn-primary accent" style="flex-shrink:0;">Обговорити проєкт
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
        </a>
      </div>
    </div>
  </div>
</main>
<script>
(function () {
  var PAGE_PRICE = <?= (int) $calcPagePrice ?>;

  function formatUAH(n) {
    return n.toLocaleString('uk-UA') + ' грн';
  }

  function calc() {
    var typeInput = document.querySelector('input[name="calc-type"]:checked');
    var base = typeInput ? Number(typeInput.dataset.price) : 0;
    var typeLabel = typeInput ? typeInput.dataset.label : '';
    var pagesInput = document.getElementById('calc-pages');
    var pages = Math.max(0, parseInt(pagesInput.value, 10) || 0);
    var pagesSum = pages * PAGE_PRICE;
    var optionsSum = 0;
    var selected = [];
    document.querySelectorAll('.calc-option-row input[type=checkbox]:checked').forEach(function (cb) {
      var price = Number(cb.dataset.price);
      optionsSum += price;
      selected.push({ label: cb.dataset.label, price: price });
    });
    var total = base + pagesSum + optionsSum;
    document.getElementById('calc-total-value').textContent = formatUAH(total);
    return { typeLabel: typeLabel, base: base, pages: pages, pagesSum: pagesSum, selected: selected, total: total };
  }

  document.querySelectorAll('input[name="calc-type"]').forEach(function (el) {
    el.addEventListener('change', calc);
  });
  document.querySelectorAll('.calc-option-row input[type=checkbox]').forEach(function (el) {
    el.addEventListener('change', calc);
  });
  var pagesInput = document.getElementById('calc-pages');
  if (pagesInput) pagesInput.addEventListener('input', calc);

  var printBtn = document.getElementById('calc-print-btn');
  if (printBtn) {
    printBtn.addEventListener('click', function () {
      var data = calc();
      var rows = '';
      rows += '<tr><td>' + data.typeLabel + '</td><td>' + formatUAH(data.base) + '</td></tr>';
      if (data.pages > 0) {
        rows += '<tr><td>Додаткові сторінки (' + data.pages + ' × ' + formatUAH(PAGE_PRICE) + ')</td><td>' + formatUAH(data.pagesSum) + '</td></tr>';
      }
      data.selected.forEach(function (o) {
        rows += '<tr><td>' + o.label + '</td><td>' + formatUAH(o.price) + '</td></tr>';
      });
      document.getElementById('printQuoteRows').innerHTML = rows;
      document.querySelector('.print-quote__total').textContent = 'Разом: ' + formatUAH(data.total);
      document.querySelector('.print-quote__date').textContent = 'Дата: ' + new Date().toLocaleDateString('uk-UA');
      window.print();
    });
  }

  calc();
})();
</script>
<?php require __DIR__ . '/partials/footer.php'; ?>
