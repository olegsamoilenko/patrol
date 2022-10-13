import './bootstrap';
import 'vuetify/styles'

import {createApp} from 'vue'
import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'

import App from './App.vue'
const vuetify = createVuetify({
    components,
})

createApp(App)
    .use(vuetify)
    .mount("#app")
