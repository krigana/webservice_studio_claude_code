-- Виправлення міграції 0004: користувач підтвердив, що робоча пошта — САМЕ
-- "support@web-service.studio" (з дефісом, як домен сайту), а не
-- "support@webservice.studio", як помилково вирішили у міграції 0004.
-- Повертаємо значення назад до "support@web-service.studio" — і в
-- налаштуваннях (звідки читається у футері, шапці, формі "Контакти",
-- калькуляторі), і в тексті сторінки "Політика конфіденційності".

UPDATE settings
SET `value` = 'support@web-service.studio'
WHERE `key` = 'contact_email' AND `value` = 'support@webservice.studio';

UPDATE pages
SET content = REPLACE(content, 'support@webservice.studio', 'support@web-service.studio')
WHERE slug = 'polityka-konfidentsiynosti';
