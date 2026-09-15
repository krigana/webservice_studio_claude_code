-- Google Analytics (GA4) Measurement ID для сайту — нове поле "Аналітика"
-- в /admin/settings/. ON DUPLICATE KEY UPDATE робить файл безпечним для
-- повторного запуску і для випадку, якщо ключ уже існує з іншим значенням
-- (наприклад, хтось встиг вручну зберегти щось через адмінку раніше).

INSERT INTO settings (`key`, `value`) VALUES
    ('ga_measurement_id', 'G-KMBLJY9VEM')
ON DUPLICATE KEY UPDATE `value` = VALUES(`value`);
