<?php
declare(strict_types=1);

/**
 * Рекламний банер (партнерські/реферальні посилання — хостинг, банківські
 * продукти, криптобіржі) — один випадковий опублікований банер під шапкою
 * на кожній публічній сторінці. Керується з /admin/ads/.
 *
 * try/catch навколо запиту — навмисно: якщо міграцію 0009_ad_banners.sql
 * ще не виконано на проді (таблиці ad_banners немає), сторінка все одно
 * має відкриватись нормально, просто без банера, а не падати з 500 на
 * КОЖНІЙ сторінці сайту (header.php підключає цей partial всюди).
 */
$adBanner = null;
try {
    $adBanner = AdBanner::randomPublished();
} catch (\Throwable $e) {
    $adBanner = null;
}
?>
<?php if ($adBanner): ?>
<div class="ad-banner no-print">
  <div class="container">
    <div class="ad-banner__box">
      <span class="ad-banner__label">Реклама</span>
      <?php
        // max_height — окрема міграція 0010; ?? null на випадок, якщо вона
        // ще не виконана на проді (колонки в БД тоді просто немає в рядку).
        $adBannerMaxHeight = $adBanner['max_height'] ?? null;
        $adBannerImgStyle = $adBannerMaxHeight ? 'max-height:' . (int) $adBannerMaxHeight . 'px;' : '';
      ?>
      <a href="<?= h($adBanner['target_url']) ?>" class="ad-banner__link" target="_blank" rel="sponsored noopener" aria-label="<?= h($adBanner['alt_text'] ?: $adBanner['title']) ?>">
        <img src="<?= h($adBanner['image_path']) ?>" alt="<?= h($adBanner['alt_text'] ?: '') ?>" class="ad-banner__img" style="<?= h($adBannerImgStyle) ?>" loading="lazy">
      </a>
    </div>
  </div>
</div>
<?php endif; ?>
