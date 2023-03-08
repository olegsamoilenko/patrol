<template>
    <v-card-text>
        <v-col cols="12">
            <v-pagination
                v-model="incidentsStore.paginationCurrentPage"
                :length="incidentsStore.paginationLastPage"
                :total-visible="5"
                @update:modelValue="onPageChange"
            ></v-pagination>
        </v-col>
    </v-card-text>
</template>

<script setup>
import { useIncidentsStore } from "@/stores/incidents";

const incidentsStore = useIncidentsStore();
function onPageChange() {
    incidentsStore.getIncidents();
}
</script>

<style scoped>

</style>
