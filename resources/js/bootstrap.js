import loadash from 'lodash'
window._ = loadash

import * as Popper from '@popperjs/core'
window.Popper = Popper

import 'bootstrap';

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.axios.defaults.withCredentials = true

// window.axios.interceptors.response.use(
//     function(response) {
//         return response;
//     },
//     function (error) {
//         switch (error.response.status) {
//             case 401: // Not logged in
//             case 419: // Session expired
//             case 503: // Down for maintenance
//                 console.log('interceptors', error.response.status)
//                 // window.location.reload();
//                 break;
//             case 500:
//                 alert('Oops, something went wrong!  The team have been notified.');
//                 break;
//             default:
//                 // Allow individual requests to handle other errors
//                 return Promise.reject(error);
//         }
//     });

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true
});

