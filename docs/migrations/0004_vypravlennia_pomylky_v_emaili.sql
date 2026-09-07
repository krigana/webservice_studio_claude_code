-- Виправлення помилки в контактному email: у стартовому дампі та в
-- міграції 0001 усюди помилково записано "support@web-service.studio"
-- (з дефісом, як домен сайту) — реальна робоча пошта БЕЗ дефіса:
-- "support@webservice.studio". Виправляємо і налаштування (звідки
-- значення читається у футері, шапці, формі "Контакти", калькуляторі),
-- і текст сторінки "Політика конфіденційності" (там пошта вже
-- збережена як текст у content, окремо від settings).

UPDATE settings
SET `value` = 'support@webservice.studio'
WHERE `key` = 'contact_email' AND `value` = 'support@web-service.studio';

UPDATE pages
SET content = REPLACE(content, 'support@web-service.studio', 'support@webservice.studio')
WHERE slug = 'polityka-konfidentsiynosti';
