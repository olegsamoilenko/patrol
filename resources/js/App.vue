<template>
    <Layout v-cloak>
        <router-view></router-view>
    </Layout>

</template>

<script setup>
import Layout from '@/layouts/Layout.vue'
import { onBeforeMount } from 'vue'
import { useAuthStore } from "@/stores/auth";

const store = useAuthStore();

    onBeforeMount(() => {
        store.signIn();
    })

</script>

<style scoped></style>
