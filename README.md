# Webservice Studio — сайт веб-студии

Репозиторий сайта [web-service.studio](https://web-service.studio). Источник правды для деплоя на хостинг Hostinger (Git-деплой из ветки `main`).

## Стек

- Backend: PHP 8.5
- СУБД: MySQL / MariaDB
- Frontend: без тяжёлого фреймворка (нативный JS/CSS), PWA (manifest.json, Service Worker)
- Языковые версии: основная — украинская (в корне), английская копия — в подпапке `/en/`

## Документация

- [`docs/tz-webservice-studio.md`](docs/tz-webservice-studio.md) — техническое задание
- [`docs/db-schema-webservice-studio.md`](docs/db-schema-webservice-studio.md) — схема базы данных (описание)
- [`docs/db-schema-webservice-studio.sql`](docs/db-schema-webservice-studio.sql) — SQL-дамп схемы БД

## Деплой

Деплой на хостинг настроен через Git-интеграцию Hostinger (hPanel), идёт из ветки `main` в папку `public_html`. Это значит, что **корень репозитория = корень сайта** (веб-доступный public_html) — весь публичный код (index.php, .htaccess, assets, /en/ и т.д.) должен лежать прямо в корне, а не во вложенной папке.

Папка `docs/` в веб-корне закрыта от прямого доступа через `.htaccess` (это внутренняя документация проекта, не часть сайта).

**Важно:** реальные учётные данные (SSH-доступ, токены API, пароли БД) в этот репозиторий не коммитятся — они хранятся отдельно вне репозитория (например, в `.env`, который в `.gitignore`).

## Структура проекта

```
index.php          — front controller (единая точка входа, роутинг)
.htaccess           — mod_rewrite (ЧПУ), закрытие служебных папок
config/config.php   — загрузка .env, без сторонних библиотек
includes/           — Database.php (PDO-обёртка), Router.php
templates/          — PHP-шаблоны страниц (шапка/футер общие)
assets/             — css, иконки
manifest.json, sw.js, offline.html — PWA
robots.txt, sitemap.xml — SEO
docs/               — ТЗ и схема БД (закрыто от веб-доступа через .htaccess)
```

Админ-панель (`/admin`), backend блога/портфолио/услуг и подключение к БД будут добавлены на этапе "Бэкенд и админка" (см. `docs/tz-webservice-studio.md`, раздел 7).

## Установка на хостинг (первый деплой)

1. Создать базу данных MySQL в hPanel, импортировать `docs/db-schema-webservice-studio.sql`.
2. Скопировать `.env.example` в `.env`, заполнить реальные данные БД и Telegram-бота (файл не коммитится в Git).
3. Убедиться, что деплой в hPanel настроен на ветку `main` → папку `public_html`.
4. Проверить, что на хостинге включён `mod_rewrite` и активен SSL (HTTPS).
5. Открыть сайт — все запросы идут через `index.php`.

