<template>
    <!--Filter Incidents------------------------------------------------------------>
    <v-card-text>
        <v-row>
            <v-col cols="12" sm="6" md="4">
                <v-select
                    v-model="incidentsStore.filterIncidentsParams.district"
                    :items="districtsStore.allDistrictsSelect"
                    :item-title="(item) => item.title"
                    :item-value="(item) => String(item.id)"
                    placeholder="Виберіть район"
                >
                </v-select>
            </v-col>

            <v-col cols="12" sm="6" md="4">
                <v-select
                    v-model="sortIncidents"
                    :items="sortIncidentsMap"
                    placeholder="Дата події"
                >
                </v-select>
            </v-col>

            <v-col cols="12" md="4" class="relative">
                <v-text-field
                    v-model="incidentsStore.filterIncidentsParams.search"
                    variant="solo"
                    label="Пошук"
                    append-inner-icon="mdi:mdi-magnify"
                    single-line
                    hide-details
                ></v-text-field>
            </v-col>

            <v-col cols="12"
                   class="relative">
                <v-switch v-model="incidentsStore.filterIncidentsParams.onlyTheirOwn" label="Тільки свої" color="success" hide-details></v-switch>
            </v-col>

            <v-col v-if="authStore.user.roles.find((r) => r.name === 'Супер Адміністратор')" cols="12"
                   class="relative">
                <v-switch v-model="incidentsStore.filterIncidentsParams.onlyTrashed" label="Тимчасово видалені" color="success" hide-details></v-switch>
            </v-col>
        </v-row>
    </v-card-text>
    <!--End Filter Incidents--------------------------------------------------------->
</template>

<script setup>
import { ref, watch } from 'vue';
import { useDistrictsStore } from "@/stores/districts";
import { useIncidentsStore } from "@/stores/incidents";
import { useAuthStore } from "@/stores/auth";

const authStore = useAuthStore();
const districtsStore = useDistrictsStore();
const incidentsStore = useIncidentsStore()

// TODO Сделать изначальная загрузка
const sortIncidents = localStorage.getItem("sortIncidentsFilter") ? ref(localStorage.getItem("sortIncidentsFilter")) : ref('dateDesc');
const sortIncidentsMap = ref([
    { value: "dateDesc", title: "Від найновіших" },
    { value: "dateAsc", title: "Від найстаріших" },
]);

watch(sortIncidents, () => {
    if (sortIncidents.value === "dateAsc") {
        incidentsStore.filterIncidentsParams.sortDirection = "ASC";
    } else if (sortIncidents.value === "dateDesc") {
        incidentsStore.filterIncidentsParams.sortDirection = "DESC";
    }
    localStorage.setItem("sortIncidentsFilter", sortIncidents.value)
    incidentsStore.paginationCurrentPage = 1;
    incidentsStore.getIncidents();
});

watch(incidentsStore.filterIncidentsParams, () => {
    localStorage.setItem("sortIncidentsFilter", sortIncidents.value)
    incidentsStore.paginationCurrentPage = 1;
    incidentsStore.getIncidents();
});
</script>

<style scoped>

</style>
