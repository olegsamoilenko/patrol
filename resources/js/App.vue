<template>
    <router-view></router-view>
</template>

<script setup>
import { onBeforeMount } from 'vue'
import { useAuthStore } from "@/stores/auth";

const store = useAuthStore();

    onBeforeMount(() => {
        console.log(111)
        store.signIn();
    })

</script>

<style scoped></style>
