<template>
    <v-card class="mb-3">
        <v-card-title>Кількість подій</v-card-title>
        <v-card-text>
            <v-row
                :class=" {'font-weight-bold': incidentsStore.todayIncidentCount > 0, 'text-error':  incidentsStore.todayIncidentCount > 0 }">
                <v-col cols="9"> За сьогодні:</v-col>
                <v-col cols="3"> {{ incidentsStore.todayIncidentCount }}</v-col>
            </v-row>
            <v-row>
                <v-col cols="9"> За тиждень:</v-col>
                <v-col cols="3"> {{ incidentsStore.lastWeekIncidentCount }}</v-col>
            </v-row>
            <v-row>
                <v-col cols="9"> За місяць:</v-col>
                <v-col cols="3"> {{ incidentsStore.lastMonthIncidentCount }}</v-col>
            </v-row>
            <v-row>
                <v-col cols="9"> Всього:</v-col>
                <v-col cols="3"> {{ incidentsStore.incidentCount }}</v-col>
            </v-row>
        </v-card-text>
    </v-card>
</template>

<script setup>
import {useIncidentsStore} from "@/stores/incidents";

const incidentsStore = useIncidentsStore();
</script>

<style scoped>

</style>
