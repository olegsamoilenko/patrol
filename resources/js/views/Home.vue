<template>
    <v-layout column>
        <v-container fluid>
            <v-card>
                <v-card-title> Кількість подій</v-card-title>
                <v-card-text v-if="loader">
                    <v-col cols="12" class="d-flex justify-center">
                        <v-progress-circular
                            :size="50"
                            indeterminate
                            color="green"
                        ></v-progress-circular>
                    </v-col>

                </v-card-text>
                <v-card-text v-else>
                    <v-row :class=" {'font-weight-bold': todayIncidentCount > 0, 'text-error':  todayIncidentCount > 0 }">
                        <v-col cols="9"> За сьогодні: </v-col>
                        <v-col cols="3"> {{todayIncidentCount}} </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="9"> За тиждень: </v-col>
                        <v-col cols="3"> {{lastWeekIncidentCount }} </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="9"> За місяць: </v-col>
                        <v-col cols="3"> {{lastMonthIncidentCount}} </v-col>
                    </v-row>
                    <v-row>
                        <v-col cols="9"> Всього: </v-col>
                        <v-col cols="3"> {{ incidentCount }} </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
        </v-container>
    </v-layout>
</template>

<script setup>
import { onMounted, ref } from 'vue'

const loader = ref(false)

onMounted(() => {
    getIncidentStat()
    console.log('Home mounted')
})

const incidentCount = ref(0)
const todayIncidentCount = ref(0)
const lastWeekIncidentCount = ref(0)
const lastMonthIncidentCount = ref(0)
async function getIncidentStat() {
    loader.value = true
    await axios
        .get("/api/get-incident-statistics")
        .then(({ data }) => {
            incidentCount.value = data.incidentCount;
            todayIncidentCount.value = data.todayIncidentCount;
            lastWeekIncidentCount.value = data.lastWeekIncidentCount;
            lastMonthIncidentCount.value = data.lastMonthIncidentCount;
        })
        .catch((error) => {
            console.log('error', error);
        })
        .finally(() => {
            loader.value = false
        });
}

</script>

<style scoped>

</style>
