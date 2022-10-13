<template>
    <h1>{{ counterStore.count }}</h1>
    <div>{{ counterStore.doubleCount }}</div>
    <button @click="counterStore.increment">Increment</button>
</template>

<script setup>
import { useCounterStore } from "@/stores/counter";

const counterStore = useCounterStore();
</script>

<style scoped></style>
