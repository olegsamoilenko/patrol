<template>
    <v-layout column>
        <v-container fluid>
            <v-card>
                <v-card-title> Події</v-card-title>
                <div v-if="isLoader">
                    <Loader />
                </div>
                <div v-else>
                    <FilterIncidents />
                    <CardIncident />
                    <PaginationIncident />
                </div>
            </v-card>
        </v-container>
    </v-layout>
</template>

<script setup>
import { onMounted, computed } from 'vue';
import { useIncidentsStore } from "@/stores/incidents";
import {useDistrictsStore} from "@/stores/districts";
import FilterIncidents from "@/components/incidents/FilterIncidents.vue";
import Loader from "@/components/Loader.vue";
import PaginationIncident from "@/components/incidents/PaginationIncident.vue";
import CardIncident from "@/components/incidents/CardIncident.vue";

const incidentsStore = useIncidentsStore();
const districtsStore = useDistrictsStore();

onMounted(() => {
    incidentsStore.getIncidents()
    districtsStore.getDistricts()
});

const isLoader = computed(() => {
    return !incidentsStore.isLoadedIncidents || !districtsStore.isLoadedDistricts;
});






</script>

<style scoped>

</style>
