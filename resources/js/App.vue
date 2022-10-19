<template>
    <Layout>
        <router-view  v-slot="{ Component }">
            <v-scale-transition>
                <component :is="Component" />
            </v-scale-transition>
        </router-view>
    </Layout>

</template>

<script setup>
import Layout from '@/layouts/Layout.vue'
import { onBeforeMount } from 'vue'
import { useAuthStore } from "@/stores/auth";

const store = useAuthStore();

    onBeforeMount(() => {
        return axios
            .get("/api/user")
            .then(({ data }) => {
                if (JSON.stringify(data.roles) !== JSON.stringify(store.user.roles)) {
                    store.signIn();
                }
            })
            .catch(() => {
                store.signOut();
            })
    })

</script>

<style scoped></style>
