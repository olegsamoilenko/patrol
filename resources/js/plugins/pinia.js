import { markRaw } from 'vue'
import { createPinia } from 'pinia'
import piniaPluginPersistedstate from 'pinia-plugin-persistedstate'
import Router from "@/router";


const pinia = createPinia()
pinia
    .use(piniaPluginPersistedstate)
    .use(({ store }) => {
        store.router = markRaw(Router)
    })

export default pinia;
