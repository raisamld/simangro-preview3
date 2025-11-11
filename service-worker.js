/* Service Worker for SIMANGRO - simple precache + runtime caching
   - Precaches core site shell
   - Network-first for JSON/CSV (dynamic data) with cache fallback
   - Cache-first for other same-origin assets
*/

const PRECACHE = 'simangro-precache-v1';
const RUNTIME = 'simangro-runtime-v1';

const PRECACHE_URLS = [
  '/',
  '/index.html',
  '/peta sebaran.html',
  '/quiz.html',
  '/jenis/index.html',
  '/offline.html',
  '/manifest.json',
  '/img/logo-KKP.png'
];

self.addEventListener('install', event => {
  self.skipWaiting();
  event.waitUntil(
    caches.open(PRECACHE).then(cache => cache.addAll(PRECACHE_URLS))
  );
});

self.addEventListener('activate', event => {
  event.waitUntil(
    caches.keys().then(keys => Promise.all(
      keys.filter(k => k !== PRECACHE && k !== RUNTIME).map(k => caches.delete(k))
    )).then(() => self.clients.claim())
  );
});

// Utility to fetch and update cache
async function fetchAndCache(request) {
  try {
    const response = await fetch(request);
    if (!response || response.status !== 200) return response;
    const cache = await caches.open(RUNTIME);
    cache.put(request, response.clone());
    return response;
  } catch (err) {
    return caches.match(request);
  }
}

self.addEventListener('fetch', event => {
  const req = event.request;
  const url = new URL(req.url);

  // navigation requests: try network first, fallback to cache/offline
  if (req.mode === 'navigate') {
    event.respondWith(
      fetch(req).then(resp => {
        // put in runtime cache the fetched page
        const copy = resp.clone();
        caches.open(RUNTIME).then(c => c.put(req, copy));
        return resp;
      }).catch(() => caches.match('/offline.html'))
    );
    return;
  }

  // Dynamic data (CSV/JSON/GeoJSON) -> network-first with cache fallback
  if (url.pathname.endsWith('.json') || url.pathname.endsWith('.geojson') || url.pathname.endsWith('.csv')) {
    event.respondWith(
      fetch(req).then(resp => {
        if (resp && resp.status === 200) {
          const copy = resp.clone();
          caches.open(RUNTIME).then(c => c.put(req, copy));
        }
        return resp;
      }).catch(() => caches.match(req))
    );
    return;
  }

  // For other same-origin requests use cache-first strategy
  if (url.origin === location.origin) {
    event.respondWith(
      caches.match(req).then(cached => cached || fetchAndCache(req)).catch(() => caches.match('/offline.html'))
    );
    return;
  }

  // For cross-origin requests, try network and fallback to cache
  event.respondWith(fetch(req).catch(() => caches.match(req)));
});

self.addEventListener('message', event => {
  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
});
