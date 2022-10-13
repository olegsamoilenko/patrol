import './bootstrap';
import 'vuetify/styles'

import {createApp} from 'vue'
import { createPinia } from 'pinia'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import router from "@/router";

import App from './App.vue'
const pinia = createPinia()
const vuetify = createVuetify({
    components,
})

createApp(App)
    .use(pinia)
    .use(vuetify)
    .use(router)
    .mount("#app")
