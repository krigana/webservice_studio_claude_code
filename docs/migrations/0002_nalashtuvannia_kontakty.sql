-- Нові ключі налаштувань для розділу /admin/settings/ — заголовок і підзаголовок
-- сторінки «Контакти» та текстове представлення телефону/Telegram-акаунту.
-- Існуючі ключі (contact_email, contact_facebook, contact_instagram,
-- contact_telegram, contact_whatsapp) вже є в базі з початкового дампу —
-- цей файл лише додає нові, ON DUPLICATE KEY UPDATE робить його безпечним
-- для повторного запуску.

INSERT INTO settings (`key`, `value`) VALUES
    ('contacts_hero_title',    'Розкажіть про свій проєкт'),
    ('contacts_hero_subtitle', 'Заповніть форму або напишіть напряму — відповідаємо протягом дня.'),
    ('contact_phone_display',  '+380 95 921 22 03'),
    ('contact_telegram_handle', '@webservices_studio')
ON DUPLICATE KEY UPDATE `value` = `value`;
