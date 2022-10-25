import "vuetify/styles";
import "@fortawesome/fontawesome-free/css/all.css";
import "@mdi/font/css/materialdesignicons.css";

import { createVuetify } from "vuetify";
import * as components from "vuetify/components";
import { aliases, fa } from "vuetify/iconsets/fa";
import { mdi } from "vuetify/iconsets/mdi";
// import { mdi } from 'vuetify/iconsets/mdi-svg'

import { uk } from "vuetify/locale";

// const myCustomLightTheme: ThemeDefinition = {
//     dark: false,
//     colors: {
//         background: '#FFFFFF',
//         surface: '#FFFFFF',
//         primary: '#6200EE',
//         'primary-darken-1': '#3700B3',
//         secondary: '#03DAC6',
//         'secondary-darken-1': '#018786',
//         error: '#B00020',
//         info: '#2196F3',
//         success: '#4CAF50',
//         warning: '#FB8C00',
//     }
// }

export default createVuetify({
    components,
    locale: {
        defaultLocale: "uk",
        fallbackLocale: "uk",
        messages: { uk },
    },
    theme: {
        defaultTheme: localStorage.getItem("theme") || "dark",

    },
    icons: {
        defaultSet: "fa",
        aliases,
        sets: {
            fa,
            mdi,
        },
    },
    global: {
        // ripple: false,
    },
});
