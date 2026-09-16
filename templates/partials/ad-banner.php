<?php
declare(strict_types=1);

/**
 * Рекламний банер (партнерські/реферальні посилання — хостинг, банківські
 * продукти, криптобіржі) — один випадковий опублікований банер. Керується
 * з /admin/ads/. На всіх сторінках, КРІМ головної, підключається прямо з
 * header.php (одразу під шапкою). На головній (templates/home.php) header.php
 * пропускає підключення ($skipAdBanner = true), а сам home.php підключає
 * цей partial нижче, під блоком hero — так попросив користувач.
 *
 * try/catch навколо запиту — навмисно: якщо міграцію 0009_ad_banners.sql
 * ще не виконано на проді (таблиці ad_banners немає), сторінка все одно
 * має відкриватись нормально, просто без банера, а не падати з 500.
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
      <?php
        // image_path_mobile — окрема міграція 0011; ?? null на випадок,
        // якщо вона ще не виконана (колонки тоді просто немає в рядку).
        $adBannerImageMobile = $adBanner['image_path_mobile'] ?? null;
      ?>
      <a href="<?= h($adBanner['target_url']) ?>" class="ad-banner__link" target="_blank" rel="sponsored noopener" aria-label="<?= h($adBanner['alt_text'] ?: $adBanner['title']) ?>">
        <picture>
          <?php if ($adBannerImageMobile): ?>
          <source media="(max-width: 640px)" srcset="<?= h($adBannerImageMobile) ?>">
          <?php endif; ?>
          <img src="<?= h($adBanner['image_path']) ?>" alt="<?= h($adBanner['alt_text'] ?: '') ?>" class="ad-banner__img" style="<?= h($adBannerImgStyle) ?>" loading="lazy">
        </picture>
      </a>
    </div>
  </div>
</div>
<?php endif; ?>
