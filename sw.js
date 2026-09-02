const CACHE_NAME = 'wsstudio-shell-v2';
const OFFLINE_URL = '/offline.html';
const APP_SHELL = [
  '/',
  '/offline.html',
  '/assets/css/main.css',
  '/assets/icons/icon-192.png',
];

self.addEventListener('install', (event) => {
  // cache.addAll() падає повністю, якщо не вдалось завантажити хоча б один
  // ресурс — через це install міг зриватися через тимчасовий збій одного
  // файлу. Кешуємо кожен ресурс окремо, щоб один невдалий запит не ламав
  // встановлення сервіс-воркера цілком.
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) =>
      Promise.all(
        APP_SHELL.map((url) =>
          cache.add(url).catch((err) => console.warn('SW: не вдалося закешувати', url, err))
        )
      )
    )
  );
  self.skipWaiting();
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((keys) =>
      Promise.all(keys.filter((k) => k !== CACHE_NAME).map((k) => caches.delete(k)))
    )
  );
  self.clients.claim();
});

self.addEventListener('fetch', (event) => {
  if (event.request.mode === 'navigate') {
    event.respondWith(
      fetch(event.request).catch(() => caches.match(OFFLINE_URL))
    );
    return;
  }

  event.respondWith(
    caches.match(event.request).then((cached) => cached || fetch(event.request))
  );
});
