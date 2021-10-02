window._ = require('lodash');

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// window.Pusher = require('pusher-js');

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     forceTLS: true
// });

window.Vue = require('vue').default;

import axios from 'axios';
Vue.prototype.$http = axios;

import { BootstrapVue } from 'bootstrap-vue';
import '@/assets/scss/app.scss';
Vue.use(BootstrapVue);

import App from './App.vue';

import VueCompositionApi from '@vue/composition-api';
Vue.use(VueCompositionApi);

import VueMeta from 'vue-meta';
Vue.use(VueMeta);

import store from '@/store';

import router from '@/router';

import i18n from '@/libs/i18n';
import Vue from 'vue';

const app = new Vue({
    i18n,
    store,
    router,
    components: { App },
    template: '<App />',
}).$mount('#app');
