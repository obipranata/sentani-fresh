var CACHE_NAME = "sentani-fresh-v1";

var urlsToCache = [
    "/assets/css/open-iconic-bootstrap.min.css",
    "/assets/css/animate.css",
    "/assets/css/owl.carousel.min.css",
    "/assets/css/owl.theme.default.min.css",
    "/assets/css/magnific-popup.css",
    "/assets/css/aos.css",
    "/assets/css/ionicons.min.css",
    "/assets/css/bootstrap-datepicker.css",
    "/assets/css/jquery.timepicker.css",
    "/assets/css/flaticon.css",
    "/assets/css/icomoon.css",
    "/assets/css/style.css",
];

self.addEventListener("install", function (event) {
    // Perform install steps
    event.waitUntil(
        caches.open(CACHE_NAME).then(function (cache) {
            console.log("Opened cache");
            return cache.addAll(urlsToCache);
        })
    );
});

// self.addEventListener('fetch', function (event) {
//     event.respondWith(
//         caches.match(event.request)
//         .then(function (response) {
//             // Cache hit - return response
//             if (response) {
//                 return response;
//             }

//             return fetch(event.request).then(
//                 function (response) {
//                     // Check if we received a valid response
//                     if (!response || response.status !== 200 || response.type !== 'basic') {
//                         return response;
//                     }

//                     // IMPORTANT: Clone the response. A response is a stream
//                     // and because we want the browser to consume the response
//                     // as well as the cache consuming the response, we need
//                     // to clone it so we have two streams.
//                     var responseToCache = response.clone();

//                     caches.open(CACHE_NAME)
//                         .then(function (cache) {
//                             cache.put(event.request, responseToCache);
//                         });

//                     return response;
//                 }
//             )
//         }).catch(function () {
//             return caches.match('/offline.html');
//         })
//     );
// });

self.addEventListener("fetch", function (event) {
    event.respondWith(
        // Try the cache
        caches
            .match(event.request)
            .then(function (response) {
                // Fall back to network
                return response || fetch(event.request);
            })
            .catch(function () {
                // If both fail, show a generic fallback:
                return caches.match("/");
                // However, in reality you'd have many different
                // fallbacks, depending on URL & headers.
                // Eg, a fallback silhouette image for avatars.
            })
    );
});

self.addEventListener("activate", function (event) {
    var cacheWhitelist = CACHE_NAME;

    event.waitUntil(
        caches.keys().then(function (cacheNames) {
            return Promise.all(
                cacheNames.map(function (cacheName) {
                    if (cacheWhitelist.indexOf(cacheName) === -1) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});
