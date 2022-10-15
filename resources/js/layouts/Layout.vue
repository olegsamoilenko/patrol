<template v-cloak>
    <div v-cloak>
        <component :is="layout" v-cloak>
            <slot v-cloak />
        </component>
    </div>

</template>

<script>
import LayoutDefault from './LayoutDefault.vue'
import { watch, shallowRef } from 'vue'
import { useRoute } from 'vue-router'
export default {

    name: 'AppLayout',
    setup() {
        const layout = shallowRef(LayoutDefault)
        const route = useRoute()
        watch(
            () => route.meta,
            async meta => {
                try {
                    const component = await import(`./Layout${meta.layout}.vue`)
                    layout.value = component?.default || LayoutDefault
                } catch (e) {
                    layout.value = LayoutDefault
                }
            },
            { immediate: true }
        )
        return { layout }
    }
}
</script>

<style>
[v-cloak] {
    display: none;
}
</style>
