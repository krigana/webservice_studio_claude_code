-- Рекламні банери (партнерські/реферальні посилання на хостинг, банки,
-- криптобіржі тощо) — керуються з нової сторінки /admin/ads/. Один
-- випадковий опублікований банер показується під шапкою на кожній
-- публічній сторінці сайту (templates/partials/ad-banner.php).
--
-- IF NOT EXISTS — на випадок повторного запуску міграції.

CREATE TABLE IF NOT EXISTS ad_banners (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title       VARCHAR(190) NOT NULL COMMENT 'внутрішня назва для адмінки, на сайті не показується',
    image_path  VARCHAR(255) NOT NULL,
    target_url  VARCHAR(500) NOT NULL COMMENT 'партнерське/реферальне посилання',
    alt_text    VARCHAR(255) NULL,
    sort_order  INT NOT NULL DEFAULT 0,
    status      ENUM('published','hidden') NOT NULL DEFAULT 'published',
    created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
