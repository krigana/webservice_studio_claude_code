-- Окрема висота показу для мобільної картинки банера (image_path_mobile) —
-- досі висота (max_height) була одна для десктопної й мобільної версій
-- одночасно; тепер можна задати їх незалежно. NULL (типово) — на
-- мобільній застосовується типова висота з CSS (110px), якщо основна
-- max_height теж не задана.

ALTER TABLE ad_banners
    ADD COLUMN max_height_mobile SMALLINT UNSIGNED NULL
        COMMENT 'бажана висота показу МОБІЛЬНОЇ картинки банера в px; NULL — типова висота з CSS'
        AFTER max_height;
