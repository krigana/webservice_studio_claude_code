<?php
$pageTitle = 'Калькулятор вартості сайту — Webservice Studio';
$pageDescription = 'Орієнтовний розрахунок вартості розробки сайту онлайн: оберіть тип сайту та потрібні опції — миттєво побачите приблизну ціну.';
$activeNav = 'calculator';
$breadcrumbs = [
    ['name' => 'Головна', 'url' => '/'],
    ['name' => 'Калькулятор вартості', 'url' => '/kalkulyator'],
];

$contactEmail = Setting::get('contact_email', 'support@web-service.studio');
$contactPhoneDisplay = Setting::get('contact_phone_display', '+380 95 921 22 03');

// Фіксований перелік типів сайту та опцій із середньоринковими цінами
// (грн). За рішенням замовника — без адмінки, правиться прямо в коді.
// Стосується поки лише категорії послуг "Розробка сайтів".
$calcTypes = [
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

// Другий, окремий калькулятор — для напряму "Арбітраж трафіку" (послуга
// "Вайтпейдж"). Два варіанти: індивідуальний вайтпейдж під нішу/гео, і
// пакет 10 шт за єдиним конвеєрним шаблоном (значно дешевше за штуку).
$wpUnitPrice = 5000;
$wpPackagePerUnit = 500;
$wpPackageSize = 10;
$wpPackagePrice = $wpPackagePerUnit * $wpPackageSize;
$wpTypes = [
    ['id' => 'unique', 'label' => 'Унікальний вайтпейдж', 'hint' => 'Індивідуальний дизайн під вашу нішу і гео, проходить модерацію Google/Facebook Ads', 'price' => $wpUnitPrice, 'qtyLabel' => 'Кількість унікальних вайтпейджів'],
    ['id' => 'package', 'label' => 'Пакет ' . $wpPackageSize . ' шт (конвеєрний шаблон)', 'hint' => 'Один шаблон, адаптований під ' . $wpPackageSize . ' офферів/гео — ' . number_format($wpPackagePerUnit, 0, '.', ' ') . ' грн/шт', 'price' => $wpPackagePrice, 'qtyLabel' => 'Кількість пакетів по ' . $wpPackageSize . ' шт'],
];

// Третій калькулятор — послуга "Лендінги" напряму "Арбітраж трафіку".
// Базовий тип (шаблонний / індивідуальний / пакет 5 шт за одним
// шаблоном) + додаткові роботи, які реально пропонують на ринку
// (A/B-тест, копірайтинг, локалізація, інтеграція трекера тощо).
$landingTemplatePrice = 3000;
$landingCustomPrice = 6000;
$landingPackagePerUnit = 2000;
$landingPackageSize = 5;
$landingPackagePrice = $landingPackagePerUnit * $landingPackageSize;
$landingTypes = [
    ['id' => 'template', 'label' => 'Лендинг за готовим шаблоном', 'hint' => 'Швидка адаптація готового шаблону під ваш оффер і гео — 2–4 дні', 'price' => $landingTemplatePrice, 'qtyLabel' => 'Кількість лендингів'],
    ['id' => 'custom', 'label' => 'Лендинг з індивідуальним дизайном', 'hint' => 'Унікальний дизайн під нішу — довше в роботі, але вирізняється серед конкурентів', 'price' => $landingCustomPrice, 'qtyLabel' => 'Кількість лендингів'],
    ['id' => 'package', 'label' => 'Пакет ' . $landingPackageSize . ' шт (один шаблон)', 'hint' => 'Один шаблон, адаптований під ' . $landingPackageSize . ' офферів/гео — ' . number_format($landingPackagePerUnit, 0, '.', ' ') . ' грн/шт', 'price' => $landingPackagePrice, 'qtyLabel' => 'Кількість пакетів по ' . $landingPackageSize . ' шт'],
];
$landingOptions = [
    ['id' => 'abtest', 'label' => 'A/B тестування (другий варіант сторінки)', 'price' => 1500],
    ['id' => 'copywriting', 'label' => 'Копірайтинг тексту під нішу', 'price' => 1200],
    ['id' => 'lang', 'label' => 'Додаткова мовна версія', 'price' => 1000],
    ['id' => 'tracker', 'label' => 'Інтеграція трекера (Keitaro/Binom)', 'price' => 800],
    ['id' => 'form', 'label' => 'Форма заявки зі сповіщеннями у Telegram/CRM', 'price' => 700],
    ['id' => 'seo', 'label' => 'SEO-розмітка та мета-теги', 'price' => 600],
];

// Четвертий калькулятор — послуга "Клоакінг". Проєктна послуга (без
// кількості) з базовим налаштуванням + додатковими роботами під ринкові
// потреби (трекер, ротація доменів, індивідуальні правила фільтрації,
// щомісячна підтримка).
$cloakTypes = [
    ['id' => 'basic', 'label' => 'Базове налаштування', 'hint' => '1 домен, фільтрація ботів і модераторів рекламних кабінетів', 'price' => 3500],
    ['id' => 'advanced', 'label' => 'Розширене налаштування', 'hint' => 'Кілька доменів, логіка white/black сторінок, гнучкі правила фільтрації', 'price' => 6000],
];
$cloakOptions = [
    ['id' => 'tracker', 'label' => 'Інтеграція трекера (Keitaro/Binom)', 'price' => 1000],
    ['id' => 'domain', 'label' => 'Додатковий домен для ротації', 'price' => 800],
    ['id' => 'rules', 'label' => 'Індивідуальні правила фільтрації (гео/User-Agent/реферер)', 'price' => 1200],
    ['id' => 'support', 'label' => 'Щомісячна підтримка та моніторинг', 'price' => 1500],
];

// П'ятий калькулятор — послуга "Адміністрування доменів та сайтів".
// На відміну від попередніх, тут немає єдиного "базового типу" — послуга
// складається з окремих робіт, кожна із власною ринковою ціною, тому
// калькулятор — це просто максимально повний перелік чекбоксів-робіт,
// сума яких і дає орієнтовну вартість. Ціни підібрані на основі
// середньоринкових тарифів українських студій/фрилансерів на технічну
// підтримку, адміністрування хостингу/сервера, SSL, бекапи тощо.
$adminWorks = [
    ['id' => 'domain', 'label' => 'Реєстрація або продовження домену', 'price' => 300],
    ['id' => 'dns', 'label' => 'Прив’язка домену до хостингу, налаштування DNS-записів (A/CNAME/MX/TXT)', 'price' => 500],
    ['id' => 'ssl', 'label' => 'Встановлення та налаштування SSL-сертифіката (HTTPS)', 'price' => 400],
    ['id' => 'migration', 'label' => 'Перенесення сайту на інший хостинг (міграція файлів і бази даних)', 'price' => 2500],
    ['id' => 'mail', 'label' => 'Налаштування корпоративної пошти на домені (Google Workspace/Zoho тощо)', 'price' => 800],
    ['id' => 'backup', 'label' => 'Налаштування автоматичного резервного копіювання', 'price' => 700],
    ['id' => 'updates', 'label' => 'Оновлення CMS, плагінів і версії PHP до актуальних', 'price' => 600],
    ['id' => 'cleanup', 'label' => 'Пошук і видалення вірусів/шкідливого коду (лікування зламаного сайту)', 'price' => 3000],
    ['id' => 'monitoring', 'label' => 'Налаштування моніторингу доступності сайту (uptime-сповіщення)', 'price' => 400],
    ['id' => 'speed', 'label' => 'Оптимізація швидкості завантаження (кешування, стиснення зображень)', 'price' => 1500],
    ['id' => 'security', 'label' => 'Базове налаштування безпеки (firewall, захист від брутфорсу)', 'price' => 1200],
    ['id' => 'vps', 'label' => 'Початкове налаштування VPS/виділеного сервера', 'price' => 3500],
    ['id' => 'edits', 'label' => 'Дрібні правки на сайті (текст/зображення/стилі) — 1 година', 'price' => 500],
    ['id' => 'support', 'label' => 'Щомісячний технічний супровід сайту (моніторинг, бекапи, оновлення)', 'price' => 1500],
];

require __DIR__ . '/partials/header.php';
?>
<main>
  <div class="hero" style="padding-bottom:24px;">
    <div class="container">
      <span class="eyebrow">Калькулятор</span>
      <h1 style="max-width:640px;">Розрахуйте орієнтовну вартість сайту</h1>
      <p class="lead">Оберіть тип сайту та потрібні опції — миттєво побачите приблизну ціну на основі середньоринкових тарифів. Нижче — окремі розрахунки для послуг напряму «Арбітраж трафіку» (вайтпейдж, лендінги, клоакінг) і для «Адміністрування доменів та сайтів».</p>
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
        <div class="calc-options" id="calc-options">
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

  <div class="container calc-layout no-print" id="vajtpejdzh" style="padding-top:8px; scroll-margin-top:100px;">
    <div class="calc-main">
      <div>
        <h2 style="font-size:24px; font-weight:800; margin-bottom:8px;">Вайтпейдж — окремий розрахунок</h2>
        <p style="font-size:14px; line-height:1.55; color:var(--color-muted); margin-bottom:20px;">Стосується послуги «Вайтпейдж» напряму «Арбітраж трафіку» — оберіть індивідуальний варіант або пакетну пропозицію за шаблоном.</p>
        <div class="calc-type-grid">
          <?php foreach ($wpTypes as $i => $t): ?>
            <label class="calc-type-card">
              <input type="radio" name="wp-type" value="<?= h($t['id']) ?>" data-price="<?= (int) $t['price'] ?>" data-label="<?= h($t['label']) ?>" data-qty-label="<?= h($t['qtyLabel']) ?>" <?= $i === 0 ? 'checked' : '' ?>>
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
        <div class="calc-pages-row">
          <span id="wp-qty-label"><?= h($wpTypes[0]['qtyLabel']) ?></span>
          <input type="number" id="wp-qty" min="1" max="50" step="1" value="1">
        </div>
        <p style="font-size:13px; color:var(--color-faint); margin-top:10px;">Для пакета кількість вказується в кількості пакетів по <?= (int) $wpPackageSize ?> шт (тобто «2» = <?= (int) ($wpPackageSize * 2) ?> вайтпейджів).</p>
      </div>
    </div>

    <div class="calc-side">
      <div class="calc-total-box">
        <span class="calc-total-box__label">Орієнтовна вартість</span>
        <span class="calc-total-box__value" id="wp-total-value">0 грн</span>
        <button type="button" id="wp-print-btn" class="btn-primary accent block" style="justify-content:center;">
          Друк / зберегти PDF
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9V2h12v7M6 18H4a1 1 0 0 1-1-1v-5a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1h-2M6 14h12v8H6v-8z"/></svg>
        </button>
        <a href="/kontakty" class="btn-ghost block" style="justify-content:center; background:transparent; border-color:rgba(255,255,255,0.25); color:#fff;">Обговорити проєкт з нами</a>
        <p class="calc-total-box__note">Розрахунок орієнтовний і не є остаточною комерційною пропозицією. Точну вартість погоджуємо індивідуально після обговорення деталей проєкту.</p>
      </div>
    </div>
  </div>

  <div class="container calc-layout no-print" id="lendingy" style="padding-top:8px; scroll-margin-top:100px;">
    <div class="calc-main">
      <div>
        <h2 style="font-size:24px; font-weight:800; margin-bottom:8px;">Лендінги — окремий розрахунок</h2>
        <p style="font-size:14px; line-height:1.55; color:var(--color-muted); margin-bottom:20px;">Стосується послуги «Лендінги» напряму «Арбітраж трафіку» — оберіть базовий варіант і додайте потрібні роботи з ринкового переліку нижче.</p>
        <div class="calc-type-grid">
          <?php foreach ($landingTypes as $i => $t): ?>
            <label class="calc-type-card">
              <input type="radio" name="landing-type" value="<?= h($t['id']) ?>" data-price="<?= (int) $t['price'] ?>" data-label="<?= h($t['label']) ?>" data-qty-label="<?= h($t['qtyLabel']) ?>" <?= $i === 0 ? 'checked' : '' ?>>
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
        <div class="calc-pages-row">
          <span id="landing-qty-label"><?= h($landingTypes[0]['qtyLabel']) ?></span>
          <input type="number" id="landing-qty" min="1" max="50" step="1" value="1">
        </div>
        <p style="font-size:13px; color:var(--color-faint); margin-top:10px;">Для пакета кількість вказується в кількості пакетів по <?= (int) $landingPackageSize ?> шт (тобто «2» = <?= (int) ($landingPackageSize * 2) ?> лендингів).</p>
      </div>

      <div>
        <h2 style="font-size:20px; font-weight:800; margin-bottom:16px;">Додаткові роботи</h2>
        <div class="calc-options" id="landing-options">
          <?php foreach ($landingOptions as $o): ?>
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
        <span class="calc-total-box__value" id="landing-total-value">0 грн</span>
        <button type="button" id="landing-print-btn" class="btn-primary accent block" style="justify-content:center;">
          Друк / зберегти PDF
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9V2h12v7M6 18H4a1 1 0 0 1-1-1v-5a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1h-2M6 14h12v8H6v-8z"/></svg>
        </button>
        <a href="/kontakty" class="btn-ghost block" style="justify-content:center; background:transparent; border-color:rgba(255,255,255,0.25); color:#fff;">Обговорити проєкт з нами</a>
        <p class="calc-total-box__note">Розрахунок орієнтовний і не є остаточною комерційною пропозицією. Точну вартість погоджуємо індивідуально після обговорення деталей проєкту.</p>
      </div>
    </div>
  </div>

  <div class="container calc-layout no-print" id="kloaking" style="padding-top:8px; scroll-margin-top:100px;">
    <div class="calc-main">
      <div>
        <h2 style="font-size:24px; font-weight:800; margin-bottom:8px;">Клоакінг — окремий розрахунок</h2>
        <p style="font-size:14px; line-height:1.55; color:var(--color-muted); margin-bottom:20px;">Стосується послуги «Клоакінг» напряму «Арбітраж трафіку» — проєктна послуга: оберіть рівень налаштування і додайте потрібні роботи з ринкового переліку нижче.</p>
        <div class="calc-type-grid">
          <?php foreach ($cloakTypes as $i => $t): ?>
            <label class="calc-type-card">
              <input type="radio" name="cloak-type" value="<?= h($t['id']) ?>" data-price="<?= (int) $t['price'] ?>" data-label="<?= h($t['label']) ?>" <?= $i === 0 ? 'checked' : '' ?>>
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
        <h2 style="font-size:20px; font-weight:800; margin-bottom:16px;">Додаткові роботи</h2>
        <div class="calc-options" id="cloak-options">
          <?php foreach ($cloakOptions as $o): ?>
            <label class="calc-option-row">
              <span class="calc-option-row__label">
                <input type="checkbox" data-price="<?= (int) $o['price'] ?>" data-label="<?= h($o['label']) ?>">
                <?= h($o['label']) ?>
              </span>
              <span class="calc-option-row__price">+<?= number_format($o['price'], 0, '.', ' ') ?> грн</span>
            </label>
          <?php endforeach; ?>
        </div>
        <p style="font-size:13px; color:var(--color-faint); margin-top:10px;">Пункт «Щомісячна підтримка та моніторинг» — рекурентний, вказана сума за місяць.</p>
      </div>
    </div>

    <div class="calc-side">
      <div class="calc-total-box">
        <span class="calc-total-box__label">Орієнтовна вартість</span>
        <span class="calc-total-box__value" id="cloak-total-value">0 грн</span>
        <button type="button" id="cloak-print-btn" class="btn-primary accent block" style="justify-content:center;">
          Друк / зберегти PDF
          <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"><path d="M6 9V2h12v7M6 18H4a1 1 0 0 1-1-1v-5a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1h-2M6 14h12v8H6v-8z"/></svg>
        </button>
        <a href="/kontakty" class="btn-ghost block" style="justify-content:center; background:transparent; border-color:rgba(255,255,255,0.25); color:#fff;">Обговорити проєкт з нами</a>
        <p class="calc-total-box__note">Розрахунок орієнтовний і не є остаточною комерційною пропозицією. Точну вартість погоджуємо індивідуально після обговорення деталей проєкту.</p>
      </div>
    </div>
  </div>

  <div class="container calc-layout no-print" id="administruvannia" style="padding-top:8px; scroll-margin-top:100px;">
    <div class="calc-main">
      <div>
        <h2 style="font-size:24px; font-weight:800; margin-bottom:8px;">Адміністрування — окремий розрахунок</h2>
        <p style="font-size:14px; line-height:1.55; color:var(--color-muted); margin-bottom:20px;">Стосується послуги «Адміністрування доменів та сайтів» — тут немає базового пакета, просто позначте потрібні роботи зі списку, і калькулятор порахує суму.</p>
        <div class="calc-options" id="admin-options">
          <?php foreach ($adminWorks as $o): ?>
            <label class="calc-option-row">
              <span class="calc-option-row__label">
                <input type="checkbox" data-price="<?= (int) $o['price'] ?>" data-label="<?= h($o['label']) ?>">
                <?= h($o['label']) ?>
              </span>
              <span class="calc-option-row__price">+<?= number_format($o['price'], 0, '.', ' ') ?> грн</span>
            </label>
          <?php endforeach; ?>
        </div>
        <p style="font-size:13px; color:var(--color-faint); margin-top:10px;">Пункт «Щомісячний технічний супровід» — рекурентний, вказана сума за місяць. Дрібні правки — за годину; на більший обсяг погоджуємо кількість годин окремо.</p>
      </div>
    </div>

    <div class="calc-side">
      <div class="calc-total-box">
        <span class="calc-total-box__label">Орієнтовна вартість</span>
        <span class="calc-total-box__value" id="admin-total-value">0 грн</span>
        <button type="button" id="admin-print-btn" class="btn-primary accent block" style="justify-content:center;">
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
    <h1 id="printQuoteTitle">Орієнтовний розрахунок вартості сайту</h1>
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
    document.querySelectorAll('#calc-options input[type=checkbox]:checked').forEach(function (cb) {
      var price = Number(cb.dataset.price);
      optionsSum += price;
      selected.push({ label: cb.dataset.label, price: price });
    });
    var total = base + pagesSum + optionsSum;
    document.getElementById('calc-total-value').textContent = formatUAH(total);
    return { typeLabel: typeLabel, base: base, pages: pages, pagesSum: pagesSum, selected: selected, total: total };
  }

  function fillAndPrintQuote(title, rows, total) {
    document.getElementById('printQuoteTitle').textContent = title;
    document.getElementById('printQuoteRows').innerHTML = rows;
    document.querySelector('.print-quote__total').textContent = 'Разом: ' + formatUAH(total);
    document.querySelector('.print-quote__date').textContent = 'Дата: ' + new Date().toLocaleDateString('uk-UA');
    window.print();
  }

  document.querySelectorAll('input[name="calc-type"]').forEach(function (el) {
    el.addEventListener('change', calc);
  });
  document.querySelectorAll('#calc-options input[type=checkbox]').forEach(function (el) {
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
      fillAndPrintQuote('Орієнтовний розрахунок вартості сайту', rows, data.total);
    });
  }

  calc();

  // --- Окремий калькулятор для "Вайтпейдж" ---
  function wpCalc() {
    var typeInput = document.querySelector('input[name="wp-type"]:checked');
    var perUnit = typeInput ? Number(typeInput.dataset.price) : 0;
    var typeLabel = typeInput ? typeInput.dataset.label : '';
    var qtyLabel = typeInput ? typeInput.dataset.qtyLabel : '';
    var qtyLabelEl = document.getElementById('wp-qty-label');
    if (qtyLabelEl && qtyLabel) qtyLabelEl.textContent = qtyLabel;
    var qtyInput = document.getElementById('wp-qty');
    var qty = Math.max(1, parseInt(qtyInput.value, 10) || 1);
    var total = perUnit * qty;
    document.getElementById('wp-total-value').textContent = formatUAH(total);
    return { typeLabel: typeLabel, perUnit: perUnit, qty: qty, total: total };
  }

  document.querySelectorAll('input[name="wp-type"]').forEach(function (el) {
    el.addEventListener('change', wpCalc);
  });
  var wpQtyInput = document.getElementById('wp-qty');
  if (wpQtyInput) wpQtyInput.addEventListener('input', wpCalc);

  var wpPrintBtn = document.getElementById('wp-print-btn');
  if (wpPrintBtn) {
    wpPrintBtn.addEventListener('click', function () {
      var data = wpCalc();
      var rows = '<tr><td>' + data.typeLabel + ' (' + data.qty + ' × ' + formatUAH(data.perUnit) + ')</td><td>' + formatUAH(data.total) + '</td></tr>';
      fillAndPrintQuote('Орієнтовний розрахунок вартості вайтпейджа', rows, data.total);
    });
  }

  if (document.querySelector('input[name="wp-type"]')) wpCalc();

  // --- Окремий калькулятор для "Лендінги" (базовий тип + додаткові роботи) ---
  function landingCalc() {
    var typeInput = document.querySelector('input[name="landing-type"]:checked');
    var perUnit = typeInput ? Number(typeInput.dataset.price) : 0;
    var typeLabel = typeInput ? typeInput.dataset.label : '';
    var qtyLabel = typeInput ? typeInput.dataset.qtyLabel : '';
    var qtyLabelEl = document.getElementById('landing-qty-label');
    if (qtyLabelEl && qtyLabel) qtyLabelEl.textContent = qtyLabel;
    var qtyInput = document.getElementById('landing-qty');
    var qty = Math.max(1, parseInt(qtyInput.value, 10) || 1);
    var baseSum = perUnit * qty;
    var optionsSum = 0;
    var selected = [];
    document.querySelectorAll('#landing-options input[type=checkbox]:checked').forEach(function (cb) {
      var price = Number(cb.dataset.price);
      optionsSum += price;
      selected.push({ label: cb.dataset.label, price: price });
    });
    var total = baseSum + optionsSum;
    document.getElementById('landing-total-value').textContent = formatUAH(total);
    return { typeLabel: typeLabel, perUnit: perUnit, qty: qty, baseSum: baseSum, selected: selected, total: total };
  }

  document.querySelectorAll('input[name="landing-type"]').forEach(function (el) {
    el.addEventListener('change', landingCalc);
  });
  document.querySelectorAll('#landing-options input[type=checkbox]').forEach(function (el) {
    el.addEventListener('change', landingCalc);
  });
  var landingQtyInput = document.getElementById('landing-qty');
  if (landingQtyInput) landingQtyInput.addEventListener('input', landingCalc);

  var landingPrintBtn = document.getElementById('landing-print-btn');
  if (landingPrintBtn) {
    landingPrintBtn.addEventListener('click', function () {
      var data = landingCalc();
      var rows = '<tr><td>' + data.typeLabel + ' (' + data.qty + ' × ' + formatUAH(data.perUnit) + ')</td><td>' + formatUAH(data.baseSum) + '</td></tr>';
      data.selected.forEach(function (o) {
        rows += '<tr><td>' + o.label + '</td><td>' + formatUAH(o.price) + '</td></tr>';
      });
      fillAndPrintQuote('Орієнтовний розрахунок вартості лендингу', rows, data.total);
    });
  }

  if (document.querySelector('input[name="landing-type"]')) landingCalc();

  // --- Окремий калькулятор для "Клоакінг" (тип налаштування + додаткові роботи) ---
  function cloakCalc() {
    var typeInput = document.querySelector('input[name="cloak-type"]:checked');
    var base = typeInput ? Number(typeInput.dataset.price) : 0;
    var typeLabel = typeInput ? typeInput.dataset.label : '';
    var optionsSum = 0;
    var selected = [];
    document.querySelectorAll('#cloak-options input[type=checkbox]:checked').forEach(function (cb) {
      var price = Number(cb.dataset.price);
      optionsSum += price;
      selected.push({ label: cb.dataset.label, price: price });
    });
    var total = base + optionsSum;
    document.getElementById('cloak-total-value').textContent = formatUAH(total);
    return { typeLabel: typeLabel, base: base, selected: selected, total: total };
  }

  document.querySelectorAll('input[name="cloak-type"]').forEach(function (el) {
    el.addEventListener('change', cloakCalc);
  });
  document.querySelectorAll('#cloak-options input[type=checkbox]').forEach(function (el) {
    el.addEventListener('change', cloakCalc);
  });

  var cloakPrintBtn = document.getElementById('cloak-print-btn');
  if (cloakPrintBtn) {
    cloakPrintBtn.addEventListener('click', function () {
      var data = cloakCalc();
      var rows = '<tr><td>' + data.typeLabel + '</td><td>' + formatUAH(data.base) + '</td></tr>';
      data.selected.forEach(function (o) {
        rows += '<tr><td>' + o.label + '</td><td>' + formatUAH(o.price) + '</td></tr>';
      });
      fillAndPrintQuote('Орієнтовний розрахунок вартості клоакінгу', rows, data.total);
    });
  }

  if (document.querySelector('input[name="cloak-type"]')) cloakCalc();

  // --- Окремий калькулятор для "Адміністрування" (лише перелік робіт, без базового типу) ---
  function adminCalc() {
    var sum = 0;
    var selected = [];
    document.querySelectorAll('#admin-options input[type=checkbox]:checked').forEach(function (cb) {
      var price = Number(cb.dataset.price);
      sum += price;
      selected.push({ label: cb.dataset.label, price: price });
    });
    document.getElementById('admin-total-value').textContent = formatUAH(sum);
    return { selected: selected, total: sum };
  }

  document.querySelectorAll('#admin-options input[type=checkbox]').forEach(function (el) {
    el.addEventListener('change', adminCalc);
  });

  var adminPrintBtn = document.getElementById('admin-print-btn');
  if (adminPrintBtn) {
    adminPrintBtn.addEventListener('click', function () {
      var data = adminCalc();
      var rows = '';
      data.selected.forEach(function (o) {
        rows += '<tr><td>' + o.label + '</td><td>' + formatUAH(o.price) + '</td></tr>';
      });
      if (!data.selected.length) {
        rows = '<tr><td>Роботи не обрано</td><td>0 грн</td></tr>';
      }
      fillAndPrintQuote('Орієнтовний розрахунок вартості адміністрування', rows, data.total);
    });
  }

  adminCalc();
})();
</script>
<?php require __DIR__ . '/partials/footer.php'; ?>
