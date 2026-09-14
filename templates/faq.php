<?php
$pageTitle = 'Часті запитання — Webservice Studio';
$pageDescription = 'Відповіді на поширені запитання про розробку сайтів, Android-застосунків, послуги для арбітражу трафіку, ціни, терміни та підтримку після запуску.';
$activeNav = 'faq';
$breadcrumbs = [
    ['name' => 'Головна', 'url' => '/'],
    ['name' => 'Часті запитання', 'url' => '/chasti-zapytannya'],
];

$faqs = Faq::ordered();

// FAQPage — Schema.org, головна причина існування цієї сторінки: дає
// пошуковим системам і AI-пошуку (Google AI Overviews/AI Mode, ChatGPT
// Search) готові пари "питання-відповідь" для прямого цитування.
if (!empty($faqs)) {
    $extraSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => array_map(static function (array $f): array {
            return [
                '@type' => 'Question',
                'name' => $f['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $f['answer'],
                ],
            ];
        }, $faqs),
    ];
}

require __DIR__ . '/partials/header.php';
?>
<main>
  <div class="hero" style="padding-bottom:24px;">
    <div class="container">
      <span class="eyebrow">Часті запитання</span>
      <h1>Відповіді на поширені запитання</h1>
      <p class="lead">Коротко про ціни, терміни, послуги та підтримку. Не знайшли відповідь — напишіть нам на сторінці «Контакти».</p>
    </div>
  </div>

  <div class="container section" style="padding-top:8px; max-width:800px;">
    <?php if (empty($faqs)): ?>
      <p style="color:var(--color-muted);">Розділ поки порожній.</p>
    <?php else: ?>
      <?php foreach ($faqs as $i => $f): ?>
        <details class="faq-item" <?= $i === 0 ? 'open' : '' ?>>
          <summary><?= h($f['question']) ?></summary>
          <div class="faq-item__answer"><?= nl2br(h($f['answer'])) ?></div>
        </details>
      <?php endforeach; ?>
    <?php endif; ?>

    <div class="cta-band" style="margin-top:40px;">
      <h2>Не знайшли відповідь?</h2>
      <p>Напишіть нам — відповідаємо протягом дня.</p>
      <a href="/kontakty" class="btn-primary accent">Звʼязатися з нами</a>
    </div>
  </div>
</main>
<?php require __DIR__ . '/partials/footer.php'; ?>
