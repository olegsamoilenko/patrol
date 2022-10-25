import './bootstrap';
import '../sass/app.scss'


import { createApp } from 'vue'

import vuetify from "@/plugins/vuetify";

import pinia from "@/plugins/pinia";
import Router from "@/router";
import App from './App.vue'
// import Layout from './layouts/Layout.vue'


createApp(App)
    .use(pinia)
    .use(vuetify)
    .use(Router)
    // .component('Layout', Layout)
    .mount("#app")
