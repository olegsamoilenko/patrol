import 'vuetify/styles'
import '@fortawesome/fontawesome-free/css/all.css'
import '@mdi/font/css/materialdesignicons.css'

import { createVuetify } from 'vuetify'
import * as components from 'vuetify/components'
import { aliases, fa } from 'vuetify/iconsets/fa'
import { mdi } from 'vuetify/iconsets/mdi'
// import { mdi } from 'vuetify/iconsets/mdi-svg'

export default createVuetify({
    components,
    icons: {
        defaultSet: 'fa',
        aliases,
        sets: {
            fa,
            mdi
        }
    },
})
