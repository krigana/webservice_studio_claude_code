-- =========================================================================
-- Webservice Studio (web-service.studio) — схема базы данных
-- Версия: 1.0   Дата: 27.08.2026
-- СУБД: MySQL / MariaDB, кодировка utf8mb4, движок InnoDB
--
-- Соответствует ТЗ v1.13 (проект "Webservice Studio Site"):
--   - Блог (4.1), Портфолио (4.2), Услуги и цены (4.3),
--     Форма связи + уведомления в Telegram (4.4), Админ-панель (4.5)
--   - Сайт одноязычный (украинский) — мультиязычных полей в схеме нет
--     (см. п. 3.1 ТЗ: EN-версия — отдельная копия сайта, не общая БД)
-- =========================================================================

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- -------------------------------------------------------------------------
-- Администраторы (вход в /admin)
-- -------------------------------------------------------------------------
CREATE TABLE admins (
    id                     INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username               VARCHAR(60)  NOT NULL,
    email                  VARCHAR(190) NOT NULL,
    password_hash          VARCHAR(255) NOT NULL COMMENT 'password_hash(), не хранить пароль в открытом виде',
    failed_login_attempts  TINYINT UNSIGNED NOT NULL DEFAULT 0,
    locked_until           DATETIME NULL COMMENT 'блокировка входа после серии неудачных попыток',
    last_login_at          DATETIME NULL,
    created_at             TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_admins_username (username),
    UNIQUE KEY uq_admins_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------------------
-- Категории услуг — общая таблица для раздела "Услуги" И для фильтра
-- в "Портфолио" (в ТЗ портфолио фильтруется "по категории услуги", п.4.2)
-- -------------------------------------------------------------------------
CREATE TABLE service_categories (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(150) NOT NULL,
    slug        VARCHAR(160) NOT NULL,
    sort_order  SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    status      ENUM('published','hidden') NOT NULL DEFAULT 'published',
    created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_service_categories_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------------------
-- Услуги: разработка сайтов, Android, арбитраж (вайтпейдж/ленды/клоакинг),
-- администрирование доменов и сайтов — полностью управляются из админки
-- -------------------------------------------------------------------------
CREATE TABLE services (
    id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id      INT UNSIGNED NOT NULL,
    title            VARCHAR(190) NOT NULL,
    slug             VARCHAR(200) NOT NULL,
    description      TEXT NULL,
    price_from       DECIMAL(10,2) NULL COMMENT 'NULL, пока не задано в админке',
    price_to         DECIMAL(10,2) NULL,
    price_note       VARCHAR(190) NULL COMMENT 'напр. "від", "договірна", назва пакету',
    currency         CHAR(3) NOT NULL DEFAULT 'UAH',
    sort_order       SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    status           ENUM('published','hidden') NOT NULL DEFAULT 'published',
    seo_title        VARCHAR(190) NULL,
    seo_description  VARCHAR(320) NULL,
    created_at       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_services_slug (slug),
    KEY idx_services_category (category_id),
    CONSTRAINT fk_services_category FOREIGN KEY (category_id)
        REFERENCES service_categories(id) ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------------------
-- Блог: категории, теги, статьи
-- -------------------------------------------------------------------------
CREATE TABLE blog_categories (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(150) NOT NULL,
    slug        VARCHAR(160) NOT NULL,
    created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_blog_categories_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE blog_tags (
    id    INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name  VARCHAR(100) NOT NULL,
    slug  VARCHAR(110) NOT NULL,
    UNIQUE KEY uq_blog_tags_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE blog_posts (
    id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id      INT UNSIGNED NULL,
    admin_id         INT UNSIGNED NULL COMMENT 'автор публикации',
    title            VARCHAR(220) NOT NULL,
    slug             VARCHAR(230) NOT NULL,
    excerpt          VARCHAR(400) NULL COMMENT 'краткое описание для ленты и превью',
    content          LONGTEXT NOT NULL COMMENT 'HTML из WYSIWYG-редактора',
    cover_image      VARCHAR(255) NULL,
    status           ENUM('draft','published') NOT NULL DEFAULT 'draft',
    published_at     DATETIME NULL,
    seo_title        VARCHAR(190) NULL,
    seo_description  VARCHAR(320) NULL,
    og_image         VARCHAR(255) NULL,
    created_at       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_blog_posts_slug (slug),
    KEY idx_blog_posts_status_published (status, published_at),
    KEY idx_blog_posts_category (category_id),
    CONSTRAINT fk_blog_posts_category FOREIGN KEY (category_id)
        REFERENCES blog_categories(id) ON UPDATE CASCADE ON DELETE SET NULL,
    CONSTRAINT fk_blog_posts_admin FOREIGN KEY (admin_id)
        REFERENCES admins(id) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE blog_post_tags (
    post_id  INT UNSIGNED NOT NULL,
    tag_id   INT UNSIGNED NOT NULL,
    PRIMARY KEY (post_id, tag_id),
    CONSTRAINT fk_bpt_post FOREIGN KEY (post_id) REFERENCES blog_posts(id) ON DELETE CASCADE,
    CONSTRAINT fk_bpt_tag  FOREIGN KEY (tag_id)  REFERENCES blog_tags(id)  ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------------------
-- Портфолио: кейсы + галерея изображений
-- -------------------------------------------------------------------------
CREATE TABLE portfolio_cases (
    id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_id      INT UNSIGNED NULL COMMENT 'категория услуги — ссылка на service_categories',
    title            VARCHAR(220) NOT NULL,
    slug             VARCHAR(230) NOT NULL,
    description      TEXT NULL,
    cover_image      VARCHAR(255) NOT NULL,
    project_url      VARCHAR(255) NULL,
    sort_order       SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    status           ENUM('published','hidden') NOT NULL DEFAULT 'published',
    seo_title        VARCHAR(190) NULL,
    seo_description  VARCHAR(320) NULL,
    created_at       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_portfolio_cases_slug (slug),
    KEY idx_portfolio_cases_category (category_id),
    CONSTRAINT fk_portfolio_cases_category FOREIGN KEY (category_id)
        REFERENCES service_categories(id) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE portfolio_case_images (
    id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    case_id     INT UNSIGNED NOT NULL,
    image_path  VARCHAR(255) NOT NULL,
    sort_order  SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    CONSTRAINT fk_pci_case FOREIGN KEY (case_id) REFERENCES portfolio_cases(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------------------
-- Заявки с формы связи (уведомление в Telegram + хранение в БД, п.4.4 ТЗ)
-- -------------------------------------------------------------------------
CREATE TABLE leads (
    id                INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name              VARCHAR(150) NOT NULL,
    contact           VARCHAR(190) NOT NULL COMMENT 'email / телефон / мессенджер — как ввёл пользователь',
    service_id        INT UNSIGNED NULL COMMENT 'интересующая услуга (предзаполняется кнопкой "Заказать")',
    message           TEXT NULL,
    source_url        VARCHAR(255) NULL COMMENT 'страница, с которой отправлена заявка',
    status            ENUM('new','processed') NOT NULL DEFAULT 'new',
    telegram_notified TINYINT(1) NOT NULL DEFAULT 0,
    ip_address        VARCHAR(45) NULL,
    created_at        TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    KEY idx_leads_status (status, created_at),
    CONSTRAINT fk_leads_service FOREIGN KEY (service_id)
        REFERENCES services(id) ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------------------
-- Статические страницы, редактируемые из админки (О студии, Политика
-- конфиденциальности и т.п.)
-- -------------------------------------------------------------------------
CREATE TABLE pages (
    id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug             VARCHAR(100) NOT NULL,
    title            VARCHAR(220) NOT NULL,
    content          LONGTEXT NOT NULL,
    seo_title        VARCHAR(190) NULL,
    seo_description  VARCHAR(320) NULL,
    updated_at       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY uq_pages_slug (slug)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------------------------
-- Настройки сайта: контакты, соцсети, SEO по умолчанию (простой key-value)
-- -------------------------------------------------------------------------
CREATE TABLE settings (
    `key`   VARCHAR(100) NOT NULL PRIMARY KEY,
    `value` TEXT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

SET FOREIGN_KEY_CHECKS = 1;

-- =========================================================================
-- Стартовые данные (seed)
-- =========================================================================

-- Категории услуг — согласно ТЗ п.3 и п.4.3 (контент на украинском —
-- это основной язык сайта, см. ТЗ п.3.1)
INSERT INTO service_categories (name, slug, sort_order) VALUES
    ('Розробка сайтів', 'rozrobka-saitiv', 10),
    ('Розробка додатків під Android', 'android-dodatky', 20),
    ('Арбітраж трафіку', 'arbitrazh-trafiku', 30),
    ('Адміністрування доменів та сайтів', 'administruvannia', 40);

-- Услуги внутри категории "Арбітраж трафіку": вайтпейдж, лендинги, клоакинг
-- Цены НЕ заполняются — по решению из ТЗ (п.4.3) заказчик проставит их сам
-- через админку, как только она будет готова
INSERT INTO services (category_id, title, slug, sort_order)
SELECT id, 'Вайтпейдж', 'whitepage', 10 FROM service_categories WHERE slug = 'arbitrazh-trafiku';
INSERT INTO services (category_id, title, slug, sort_order)
SELECT id, 'Лендінги', 'lendingy', 20 FROM service_categories WHERE slug = 'arbitrazh-trafiku';
INSERT INTO services (category_id, title, slug, sort_order)
SELECT id, 'Клоакінг', 'kloaking', 30 FROM service_categories WHERE slug = 'arbitrazh-trafiku';

-- Контакты и соцсети — из ТЗ п.1.5, уже подтверждены заказчиком
INSERT INTO settings (`key`, `value`) VALUES
    ('contact_email',    'support@webservice.studio'),
    ('contact_facebook', 'https://www.facebook.com/webservicestudio/'),
    ('contact_instagram','https://www.instagram.com/webservicestudio/'),
    ('contact_telegram', 'https://t.me/webservices_studio'),
    ('contact_whatsapp', 'https://api.whatsapp.com/send/?phone=380959212203');

-- Примечание: администратор (таблица admins) намеренно не сеется в этом
-- дампе — учётку и пароль нужно создать отдельно на этапе развёртывания
-- (password_hash() в PHP), а не хранить готовый пароль в файле миграции.
