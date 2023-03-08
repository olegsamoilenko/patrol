<template>
    <v-layout column>
        <v-container fluid>
            <div v-if="isLoader">
                <Loader/>
            </div>
            <div v-else>

                <StatisticsIncidents />

                <OperationalSituation />

                <MainPageIncidents />

            </div>

        </v-container>
    </v-layout>
</template>

<script setup>
import {onMounted, computed} from 'vue'
import {useIncidentsStore} from "@/stores/incidents";
import {useSummaryStore} from "@/stores/summary";
import {useMainPageIncidentsStore} from "@/stores/mainPageIncidents";
import {useDistrictsStore} from "@/stores/districts";
import StatisticsIncidents from "@/components/mainPage/StatisticsIncidents.vue";
import OperationalSituation from "@/components/mainPage/OperationalSituation.vue";
import MainPageIncidents from "@/components/mainPage/MainPageIncidents.vue";
import Loader from "@/components/Loader.vue";

const incidentsStore = useIncidentsStore()
const summaryStore = useSummaryStore()
const mainPageIncidentsStore = useMainPageIncidentsStore()
const districtsStore = useDistrictsStore()

onMounted(() => {
    incidentsStore.getIncidentStat()
    summaryStore.getSummary()
    mainPageIncidentsStore.getMainPageIncidents()
    districtsStore.getDistricts()
})


const isLoader = computed(() => {
    return !incidentsStore.isLoadedIncidentsStat || !summaryStore.isLoadedSummary || !mainPageIncidentsStore.mainPageIncidents
});


</script>

<style scoped>

</style>
