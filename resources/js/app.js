import './bootstrap';
import '../sass/app.scss'
import 'vuetify/styles'

import { createApp, markRaw } from 'vue'
import { createPinia } from 'pinia'
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import Router from "@/router";

import App from './App.vue'

const pinia = createPinia()
pinia
    .use(piniaPluginPersistedstate)
    .use(({ store }) => {
        store.router = markRaw(Router)
    })

const vuetify = createVuetify({
    components,
})

createApp(App)
    .use(pinia)
    .use(vuetify)
    .use(Router)
    .mount("#app")
